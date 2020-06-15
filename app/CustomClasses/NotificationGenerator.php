<?php

namespace App\CustomClasses;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Arr;

// notifications
use App\Notifications\CriticalStock;
use App\Notifications\OutOfStock;
use App\Notifications\Expired;
use App\Notifications\SoonExpiring;

use App\Notifications\NewOrder;
use App\Notifications\UpdatesOnCreatedOrder;

// includes
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class NotificationGenerator
{
	// returns all types of notifications
	public static function allTypesOfNotifications()
	{
		return collect([
					[ 'name' => 'out-of-stock', 'permissions' => 'access_product' ],
					[ 'name' => 'critical-stock', 'permissions' => 'access_product' ],
					[ 'name' => 'expired', 'permissions' => [
							'access_product',
							'access_expired_product'
						]
					],
					[ 'name' => 'soon-expiring', 'permissions' => [
							'access_product',
							'access_soon_expiring_product'
						]
					]
				]);
	}

	public static function getNotificationCounts()
	{
		return self::allTypesOfNotifications()
				->filter(function($type){
					// if has the ff. permissions
					return self::__isHasThePermission(auth()->user(), $type['permissions']);
				})
				->map(function($type){
					return [
						'selector' => '.--'.Str::kebab($type['name']).'-notif-count',
						'count' => auth()->user()
							->unreadNotifications()
			            	->where('type', 'LIKE', '%'.Str::studly( $type['name'] ).'%')
			            	->count(),

					];
				});
	}

	// get the employees - with the permissions specified
	public static function notifyThisEmployees($permissions)
	{
		return \App\Employee::all()
				->filter( function($employee) use ($permissions){
					// if has the ff. permissions
					return self::__isHasThePermission($employee, $permissions);
				});
	}

	// to shorten the code
	public static function __isHasThePermission($employee, $permissions)
	{
		return is_null(
				$employee
					->getAllPermissions()
					->whereIn('name', Arr::wrap($permissions)) ) ? false : true;
	}

	/**
     * Create a notification for critical stock.
     *
     * @return void
     */
	public static function criticalStock($product)
	{
		$product = $product
					->replicate()
					->withStock()
					->where( function($query){
						return $query->whereColumn('stock', '<=', 'critical_quantity')
									->where('stock', '<>', 0);
					})
					->find( $product['id'] );

		if( !is_null($product) )
			// notify this users
			Notification::send( self::notifyThisEmployees('access_product'), new CriticalStock($product) );
	}

	/**
     * Create a notification for out of stock.
     *
     * @return void
     */
	public static function outOfStock($product)
	{
		$product = $product
					->replicate()
					->withStock()
					->where('stock', 0)
					->find($product['id']);

		if( !is_null($product) )
			// notify this users
			Notification::send( self::notifyThisEmployees('access_product'), new OutOfStock($product) );
	}

	/**
     * Create a notification for soon expiring.
     *
     * @return void
     */
	public static function soonExpiring($product)
	{
		$batch_nos = $product
					->batchNo()
					->whereSoonExpiring()
					// ->whereNotNotifiedYet( Str::kebab( __FUNCTION__ ) )
					->get();

		$product['batch_nos'] = $batch_nos;

		if( count($batch_nos) > 0 )
			Notification::send( self::notifyThisEmployees([
				'access_soon_expiring_product',
			]), new SoonExpiring($product) ); 
	}

	/**
     * Create a notification for expired product.
     *
     * @return void
     */
	public static function expired($product)
	{
		$type = Str::kebab( __FUNCTION__ );

		$batch_nos = $product
					->batchNo()
					->whereExpired()
					// ->whereNotNotifiedYet( $type )
					->where('quantity', '<>', 0)
					->get();

		$product['batch_nos'] = $batch_nos;

		if( count($batch_nos) > 0 ){
			// set notifications
			Notification::send( self::notifyThisEmployees([
				'access_expired_product',
			]), new Expired($product) );

			// set batch nos notified
			self::__setBatchNoNotified($batch_nos, $type);
		}
	}

	public static function __setBatchNoNotified($batch_nos, $type)
	{
		$batch_nos->each(function($batch_no) use ($type){
			$batch_no->notified()
				->create([
					'type' => $type,
				]);
		});
	}

	/**
     * Create a notification for new order.
     *
     * @return void
     */
	public static function newOrder($orderTransaction)
	{
		$orderTransaction = $orderTransaction
					->replicate()
					->withEmployee()
					->withAccount()
					->withTotalCost()
					->find( $orderTransaction['id'] );

		// notify this users
		Notification::send( self::notifyThisEmployees([
			'access_pending_order',
			'access_track_order',
		]), new NewOrder($orderTransaction) );
	}

	/**
     * Create a notification for updates on created order.
	 *
     * Must use in observer of order transaction.
     *
     * @return void
     */
	public static function updatesOnCreatedOrder($orderTransaction)
	{
		$orderTransaction = $orderTransaction
					->replicate()
					->withEmployee()
					->withAccount()
					->withDelivery()
					->find( $orderTransaction['id'] );

		Notification::send( $orderTransaction['employee'], new UpdatesOnCreatedOrder($orderTransaction) );
	}

	/**
     * Create a notification for paid bills in collection.
     *
     * @return void
     */
	public static function paidBillsInCollection($product)
	{
		//
	}

	/**
     * Create a ui notification for each type.
	 *
	 * critical-stock, expired, out-of-stock, soon-expiring
	 * new-order, updates-on-created-order
	 * paid-bills-in-collection
	 *
     * @return void
     */
	public static function createUiNotification($notification)
	{
		// transform to kebab case...
		$type = Str::after($notification['type'], 'App\Notifications\\');
		$type = Str::kebab($type);

		$data = $notification['data'][0];

		return View::make('includes.notifications.'.$type.'', compact(['notification', 'data']))
						->render();
	}
	
}