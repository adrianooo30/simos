<?php

namespace App;

// trait
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

// custom classes
use App\CustomClasses\NotificationGenerator;

// 
use Hash;
use Illuminate\Support\Str;
use Illuminate\Pipeline\Pipeline;

class Employee extends Authenticatable
{
    use Notifiable, HasRoles, CustomTraits\FormatsTrait;

    protected $guarded = [];

    protected $appends = [ 'full_name' ];

    protected $hidden = ['password'];
    

    public function orderTransaction()
    {
    	return $this->hasMany(OrderTransaction::class);
    }

   	public function deliverTransaction()
   	{
   		return $this->hasMany(DeliverTransaction::class);
   	}

   	public function collectionTransaction()
   	{
   		return $this->hasMany(CollectionTransaction::class);
   	}

   	public function deposit()
   	{
   		return $this->hasMany(Deposit::class);
   	}

    // target amount
    public function target()
    {
      return $this->hasMany(Target::class);
    }

    // *********************************************
    // *********************************************

    // product holding
    public function products()
    {
      return $this->belongsToMany(Product::class)
                  ->using( EmployeeProduct::class )
                  ->withPivot(['active'])
                  ->withTimestamps();
    }

    public function productsBatchNo()
    {
      return $this->hasManyThrough( BatchNo::class, Product::class );
    }

    // not normalize well
    public function position()
    {
      return $this->belongsTo(Position::class);
    }

    // has many through
    // public function employeeOrderedMedicine()
    // {
    //   return $this->hasManyThrough( OrderMedicine::class, OrderTransaction::class );
    // }


    // MUTATORS
    public function setFNameAttribute($value)
    {
        $this->attributes['fname'] = ucfirst($value);
    }

    public function setMNameAttribute($value)
    {
        $this->attributes['mname'] = ucfirst($value);
    }

    public function setLNameAttribute($value)
    {
        $this->attributes['lname'] = ucfirst($value);
    }

    public function setPasswordAttribute($value)
    {
      $this->attributes['password'] = Hash::make($value);
    }

    // ACCESSORS
    public function getFullNameAttribute()
    {
      return "{$this->attributes['lname']}, {$this->attributes['fname']} ".substr($this->attributes['mname'], 0, 1).".";
    }

    // ******************************************
    //                   SCOPES
    // ******************************************
    // public function scopeGetPSRs( $query )
    // {
    // 		return $query->orderBy('id', 'desc')
    //                 ->where('status', 'Pending')
    //                 ->get();
    // }

    public function scopeWithActiveProducts($query, $search)
    {
      return $query->with(['products' => function($query) use ($search){
        return $query->wherePivot('active', true)
                    ->search( $search )
                    ->withStockInOrder()
                    ->with(['deals']);
      }]);
    }

    // products
    // from_date, to_date

    public function scopeWhereInTargetAmount($query, $from_date, $to_date)
    {
      return $query->whereHas('target', function($query) use ($from_date, $to_date){
        return $query->whereBetween('start_date', [ $from_date, $to_date ]);
      })
      ->with(['target']);
    }

    public function scopeWithProductsCollected($query, $from_date, $to_date)
    {

    }

    // count notifications
    public static function countUnreadNotification($type = 'all')
    {
        $unreadNotifications = auth()->user()
              ->unreadNotifications();

        // if to get "all" the unread notifications
        if($type != 'all')
          $unreadNotifications
              ->where('type', 'LIKE', '%'.Str::studly($type).'%');

        return [ '.--'.Str::kebab($type).'-notif-count' => $unreadNotifications->count() ];
    }

    // get all notifications
    public static function getNotifications()
    {
      return app( Pipeline::class )
              ->send( auth()->user() )
              // notification - state
              ->through([
                \App\CustomClasses\NotificationFilter\All::class,
                \App\CustomClasses\NotificationFilter\Read::class,
                \App\CustomClasses\NotificationFilter\Unread::class,
              ])
              ->thenReturn()
              ->where('type', 'LIKE', '%'.Str::studly( request('notification_type') ).'%')
              ->whereBetween('created_at', [
                request('from_date') ?? now()->startOfMonth(),
                request('to_date') ?? now()->endOfMonth()
              ])
              ->get()
              ->transform(function($notification, $index){
                return [
                  'notification_html' => NotificationGenerator::createUiNotification($notification),
                  'notification_data' => $notification,
                  // 'notification_section' => 'today',
                ];
              });
    }
}
