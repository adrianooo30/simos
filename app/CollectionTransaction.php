<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectionTransaction extends Model
{

      use \Staudenmeir\EloquentHasManyDeep\HasRelationships, CustomTraits\FormatsTrait;

   	protected $guarded = [];

      // protected $appends = [ 'total_collected_amount', 'total_collected_amount_format', 'account' ];
      
   	public function cheque()
   	{
   		return $this->hasOne(Cheque::class);
   	}

   	public function employee()
   	{
   		return $this->belongsTo(Employee::class);
   	}

   	public function deposit()
   	{
   		return $this->hasOne(Deposit::class);
   	}

      public function orderMedicines()
      {
         return $this->belongsToMany(OrderMedicine::class, 'collection_transaction_order_medicine')
                    ->using( CollectionTransactionOrderMedicine::class )
                    ->withPivot(['paid_quantity', 'paid_amount'])
                    ->as('paid')
                    ->withTimestamps();
      }

      // HAS ONE THROUGH
      public function getAccountAttribute()
      {
         return $this->orderMedicines()->first()->orderTransaction()->first()->account;
      }

      /**
     * SCOPES.
     */

      public function scopeWithTotalCollectedAmount($query)
      {
         return $query->addSubSelect('total_collected_amount', function($query) {
            return $query->selectRaw('sum(collection_transaction_order_medicine.paid_amount) as total_collected_amount')
                    ->from('collection_transaction_order_medicine')
                    ->whereColumn('collection_transactions.id', 'collection_transaction_order_medicine.collection_transaction_id')
                    ->limit(1);
        });
      }


      // custom logics
      public function getOrderTransactions()
      {
         $orderMedicines = $this->orderMedicines()->get();

         $orderTransactions = $orderMedicines->pluck('order_transaction_id')->unique();

         return OrderTransaction::with(['deliverTransaction'])
                  ->whereIn('order_transactions.id', $orderTransactions)
                  ->withDetails()
                  ->get();
      }

      public function getPaidOrderMedicineFor($order_transaction_id)
      {
         return $this->orderMedicines()
                  ->with(['product'])
                  ->get()
                  ->filter( function($value, $key) use ($order_transaction_id){
                     return $value['order_transaction_id'] == $order_transaction_id;
                  } );
      }

      public function getTotalPaidFor($order_transaction_id)
      {
         $total_paid = $this->getPaidOrderMedicineFor($order_transaction_id)
                     ->reduce( function( $carry, $value ){
                        // return total paid amount
                        return $carry + $value['paid']['paid_amount'];
                     }, 0 );

         return [
            'total_paid' => $total_paid,
            'total_paid_format' => $this->pesoFormat($total_paid),
         ];

      }

}
