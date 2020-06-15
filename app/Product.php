<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use Nicolaslopezj\Searchable\SearchableTrait;

class Product extends Model
{
    use SearchableTrait, CustomTraits\FormatsTrait;

    protected $guarded = [];

    protected $appends = [ 'product_name', /*'stock', 'stock_in_order', 'current_holder', 'unit_price_format'*/ ];

    public $timestamps = false;

   /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'generic_name' => 10,
            'brand_name' => 10,
            'strength' => 2,
            'weight_volume' => 5,
        ],
        'joins' => [
            'batch_nos' => ['products.id','batch_nos.product_id'],
        ],
    ];

    // protected $casts = [
    //     'stock' => 'integer',
    //     'stock_in_order' => 'integer',
    // ];

    protected static function booted()
    {
        static::addGlobalScope('stock', function(Builder $builder){
            $builder->withStock()
                    ->withStockInOrder();
        });
    }

    public function batchNo()
    {
    	return $this->hasMany(BatchNo::class);
    }

    public function orderMedicine()
    {
    	return $this->hasMany(OrderMedicine::class);
    }

    // public function freeMedicine()
    // {
    //     return $this->hasMany(FreeMedicine::class);
    // }

    public function loss()
    {
        return $this->hasMany(Loss::class);
    }

    // holder of this
    public function employee()
    {
        return $this->belongsToMany(Employee::class)
                    ->using( EmployeeProduct::class )
                    ->withPivot(['active'])
                    ->withTimestamps();
    }

    public function currentHolder()
    {
        return $this->employee()
                    ->where('active', true)
                    ->latest()
                    ->first();
    }

    // promo
    public function deals()
    {
        return $this->hasMany(Deal::class);
    }


    // returned in
    public function returnedOrderMedicines()
    {
        return $this->hasMany( ReturnedOrderMedicine::class );
    }

    public function prices()
    {
        return $this->hasMany( Price::class );
    }

    /**
     * Section for SCOPES.
     *
     * @return query
     */

    // whereHasExpired
    public function scopeWhereHasExpired($query/*, $from = now()->startOfMonth(), $to = now()->endOfMonth()*/)
    {
        return $query->whereHas('batchNo', function($query){
            return $query->whereExpired();
        });
    }

    // whereSalesIn($from_date, $to_date)
    public function scopeWhereSalesIn($query, $from_date, $to_date)
    {
        return $query->whereHas('orderMedicine', function($query) use ($from_date, $to_date){
            return $query->whereInDeliveredDate($from_date, $to_date);
        })
        ->with(['orderMedicine' => function($query) use ($from_date, $to_date){
            return $query->whereInDeliveredDate($from_date, $to_date)
                        ->withDetails();
        }]);
    }

    // whereCollectionsIn($from_date, $to_date)
    public function scopeWhereCollectionsIn($query, $from_date, $to_date)
    {
        return $query->whereHas('orderMedicine', function($query) use ($from_date, $to_date){
            return $query->whereInCollectionDate($from_date, $to_date);
        })
        ->with(['orderMedicine' => function($query) use ($from_date, $to_date){
            return $query->whereInCollectionDate($from_date, $to_date);
        }]);
    }

    // get all stock...
    public function scopeWithStock($query)
    {
        return $query->addSubSelect('stock', function($query){
            return $query->selectRaw('sum(quantity) as stock')
                ->from('batch_nos')
                ->whereColumn('product_id', 'products.id')
                ->limit(1);
        });
    }

    // get only the number of product only able to order...
    public function scopeWithStockInOrder($query)
    {
        return $query->addSelect([
            'stock_in_order' => BatchNo::selectRaw('sum(quantity) as stock_in_order')
                                ->whereColumn('product_id', 'products.id')
                                ->whereNotExpired()
                                ->limit(1),
        ]);
    }

    // 
    // public function scopeWithStockInOrder()
    // {
    //     return $query->add
    // }

    /**
     * Section for MUTATORS.
     *
     * @return query
     */


    /**
     * Section for ACCESORS.
     *
     * @return query
     */
    public function getProductNameAttribute()
    {
        return "$this->generic_name $this->strength";
    }

    public function getUnitPriceFormatAttribute()
    {
        return number_format($this->unit_price, 2);
    }

    public function getCurrentHolderAttribute()
    {
        return $this
                ->employee()
                ->where('active', '1')
                ->first();;
    }

    /**
     * Section for RELATIONSHIP.
     *
     * @return logics
     */


    /**
     * Section for CUSTOM LOGICS.
     *
     * @return logics
     */

    // critical stock
    public function getCriticalStock()
    {
        return $this
            ->withStockInOrder()
            ->whereColumn('stock_in_order', '<=', 'critical_quantity')
            ->get();
    }

    // out of stock
    public function getOutOfStock()
    {
        return $this
            ->withStockInOrder()
            ->where('stock_in_order', 0)
            ->get();
    }

    // for sales per product charts
    public static function getSalesPerProduct($request)
    {
        // SALES PER PRODUCT
        return Product::whereSalesIn($request->query('from_date'), $request->query('to_date'))
                ->get()
                ->each( function($product, $key) {
                    $product['total_sales'] = collect($product['orderMedicine'])
                                                ->reduce( function($carry, $item){
                                                    return $carry + $item['total_quantity'];
                                                }, 0 );
                } );
    }

    // for charts
    public static function getCollectionsPerProduct($request)
    {
        return Product::whereCollectionsIn($request->query('from_date'), $request->query('to_date'))
                ->get()
                ->each( function($product, $key) {
                    // total sales...
                    $product['total_collections'] = collect($product['orderMedicine'])->reduce( function($carry, $item){
                        // total carry...
                        return $carry + collect($item['collectionTransactions'])->reduce( function($carry, $item){
                            return $carry + $item['paid']['paid_quantity'];
                        }, 0);
                    }, 0 );
                } );
    }

}
