<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Nicolaslopezj\Searchable\SearchableTrait;

class Account extends Model
{
    use SearchableTrait, CustomTraits\FormatsTrait;

    protected $searchable = [
        'columns' => [
            'account_name' => 5,
            'type' => 5,
        ],
    ];

    protected $guarded = [];

    // protected $appends = [ 'balance', ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('misc', function (Builder $builder) {
            $builder->withMisc();
        });
    }

    public function orderTransaction()
    {
    	return $this->hasMany(OrderTransaction::class);
    }

    // promo
    public function deals()
    {
    	return $this->belongsToMany(Deal::class)->withTimestamps();
    }

    public function prices()
    {
        return $this->belongsToMany( Price::class );
    }

    public function accountMisc()
    {
        return $this->hasOne( AccountMisc::class );
    }

    // has one through
    // broad for all - not specified what kind of status
    public function orderMedicinesThroughOrderTransaction()
    {
        return $this->hasManyThrough( OrderMedicine::class, OrderTransaction::class );
    }

    public function scopeSalesPerProduct($query)
    {
        return $query->with(['orderTransaction' => function($query){
            $query->whereSales();
        }]);
    }


    /**
     * ACCESSORS
     */
    // public function getTotalBillAttribute()
    // {
    //     return $this->orderTransaction()
    //                 ->whereBills()
    //                 ->withDetails()
    //                 ->get()
    //                 ->transform( function($value){
    //                     return [
    //                         'total_bill' => is_null($value['total_bill']) ? $value['total_cost'] : $value['total_bill'],
    //                     ];
    //                 } )
    //                 ->sum('total_bill');
    // }

    /**
     * SCOPES
     */
    public function scopeWhereHasBill($query)
    {
        return $query->whereHas('orderTransaction', function($query){
            return $query->whereIn('status', [ 'Delivered', 'Partially Paid', ]);
        });
    }

    // public function scopeWithTotalBill($query)
    // {
    //     return $query->addSelect([
    //                         \DB::raw('sum(order_transactions.total_bill) as total_bill'),
    //                     ])
    //                 ->joinSub( OrderTransaction::withDetails(), 'order_transactions', 'accounts.id', '=', 'order_transactions.account_id' );
    // }

    public function deliverTransaction()
    {
        return $this->belongsTo( DeliverTransaction::class );
    }

    public function scopeWhereSalesIn($query, $from_date, $to_date)
    {
        return $query->whereHas('orderTransaction', function($query) use ($from_date, $to_date){
            return $query->withDeliveryDate()
                        ->whereBetween('delivery_date', [$from_date, $to_date]);
        })->with(['orderTransaction' => function($query) use ($from_date, $to_date){
            return $query->withDeliveryDate()
                        ->whereBetween('delivery_date', [$from_date, $to_date])
                        ->withDetails();
        }]);
    }

    // whereCollectionsIn($from_date, $to_date)
    public function scopeWhereCollectionsIn($query, $from_date, $to_date)
    {
        return $query->whereHas('orderTransaction.orderMedicine', function($query) use ($from_date, $to_date){
            return $query->whereInCollectionDate($from_date, $to_date);
        })
        ->with(['orderTransaction.orderMedicine' => function($query) use ($from_date, $to_date){
            return $query->whereInCollectionDate($from_date, $to_date);
        }]);
    }

    public function scopeWithMisc($query)
    {
        return $query
                ->withTotalBill()
                ->withExcessPayment()
                ;
    }

    // withTotalBill
    public function scopeWithTotalBill($query)
    {
        return $query->addSubSelect('total_bill', function($query){
            return $query->select('account_miscs.total_bill')
                        ->from('account_miscs')
                        ->whereColumn('accounts.id', 'account_miscs.account_id')
                        ->limit(1);
        });
    }

    // withExcessPayment
    public function scopeWithExcessPayment($query)
    {
        return $query->addSubSelect('excess_payment', function($query){
            return $query->select('account_miscs.excess_payment')
                        ->from('account_miscs')
                        ->whereColumn('accounts.id', 'account_miscs.account_id')
                        ->limit(1);
        });
    }

    // promoOfProductFor
    // public function scopePromoOfProductFor($query, $account)
    // {
    //     return $query->
    // }

    // public function scopeWithBalance($query)
    // {
    //     return $query->
    // }

    /**
     * CUSTOM LOGICS
     */

    // get bills that can be paid using excess payment
    public function get_bills_can_paid_using_excess_payment()
    {
        return $this->orderTransaction()
                    ->whereSales()
                    ->withDetails()
                    ->withTotalBill()
                    ->withTotalCost()
                    ->get()
                    ->filter(function($orderTransaction){
                        // 
                        $total_bill = (is_null($orderTransaction['total_bill']) ?
                                        $orderTransaction['total_cost'] :
                                        $orderTransaction['total_bill']);

                        // return order transaction...
                        return $total_bill <= $this->excess_payment && $total_bill > 0;
                    });
    }

    // get the bills of each order transactions
    public function setTotalBill()
    {
        $this
            ->accountMisc()
            ->update([
                'total_bill' => $this
                            ->orderTransaction()
                            ->whereSales()
                            ->withTotalBill()
                            ->get()
                            ->transform( function($value, $key){
                                return [
                                    'total_bill' => is_null($value['total_bill']) ? $value['total_cost'] : $value['total_bill']
                                ];
                            } )
                            ->sum('total_bill'),
            ]);
    }

    // for charts
    public static function getSalesPerAccount($request)
    {
        // SALES PER ACCOUNT
        return Account::whereSalesIn($request->query('from_date'), $request->query('to_date'))
                    ->get()
                    ->each( function($account, $key) {
                        $account['total_sales'] = collect($account['orderTransaction'])->reduce( function($carry, $item){
                            return $carry + $item['total_cost'];
                        }, 0 );
                    } )
                    ->filter(function($value){
                        return $value['total_sales'] > 0;
                    });
    }

    // for charts
    public static function getCollectionsPerAccount($request)
    {
        return Account::whereCollectionsIn($request->query('from_date'), $request->query('to_date'))
                        ->get()
                        ->each( function($account, $key) {
                            $account['total_collections'] = collect($account['orderTransaction'])->reduce( function($carry, $item){
                                // total sales...
                                return $carry + collect($item['orderMedicine'])->reduce( function($carry, $item){
                                    // total carry...
                                    return $carry + collect($item['collectionTransactions'])->reduce( function($carry, $item){
                                        return $carry + $item['paid']['paid_amount'];
                                    }, 0);
                                }, 0 );

                            }, 0 );
                        } );
    }


    // 
    // public function getRouteKeyName()
    // {
    //     return 'account_name';
    // }
}
