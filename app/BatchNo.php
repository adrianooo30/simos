<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BatchNo extends Model
{   
    // customs
    use CustomTraits\FormatsTrait;

    protected $guarded = [];

    protected $casts = [
        'exp_date' => 'datetime',
        // 'date_added' => 'date:Y-m-d',
        // 'created_at' => 'date:Y-m-d',
        // 'updated_at' => 'date:Y-m-d',
    ];

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
    	return $this->belongsTo(Supplier::class);
    }

    public function orderBatchNo()
    {
    	return $this->hasMany(OrderBatchNo::class);
    }

    public function changedBatchNo()
    {
        return $this->hasMany(ChangedBatchNo::class);
    }

    public function loss()
    {
        return $this->hasMany(Loss::class);
    }

    // polymorphic
    public function notified()
    {
        return $this->morphMany(Notified::class, 'notifiable');
    }


    // RETURNS
    public function returnedOrderBatchNos()
    {
        return $this->hasMany( ReturnedOrderBatchNo::class );
    }

    /**
     * Section for MUTATOR.
     *
     * @return attribute
     */
    public function setBatchNoAttribute($value)
    {
        $this->attributes['batch_no'] = strtoupper($value);
    }

    /**
     * Section for SCOPES.
     *
     * @return query
     */

    // whereNotExpired
    public function scopeWhereNotExpired($query)
    {
        return $query->whereDate( 'exp_date', '>', now()->toDateString() );
    }

    // whereSoonExpiring
    public function scopeWhereSoonExpiring($query)
    {
        return $query->whereBetween('exp_date', [
                                        now()->toDateString(),
                                        now()->addDays(30)->toDateString()
                                    ]);
    }

    // whereExpired
    public function scopeWhereExpired($query)
    {
        return $query->where('exp_date', '<=', now());
    }

    // whereHasLoss
    // public function scopeWhereHasLoss($query)
    // {
    //     return $query->has('loss');
    // }

    // withTotalLoss
    public function scopeWithTotalLoss($query)
    {
        return $query->addSubSelect('total_loss', function($query){
            return $query->selectRaw('sum(losses.quantity) as total_loss')
                        ->from('losses')
                        ->whereColumn('batch_nos.id', 'losses.batch_no_id')
                        ->limit(1);
        });
    }

    // whereNotNotifiedYet($type)
    public function scopeWhereNotNotifiedYet($query, $type)
    {
        return $query->whereDoesntHave('notified', function($query) use ($type){
            return $query->whereType( $type );
        });
    }

    /**
     * Section for CUSTOM LOGICS.
     *
     * @return logics
     */
    // public function 
}
