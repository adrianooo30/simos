<?php

namespace App\CustomClasses\NotificationFilter;

use Illuminate\Support\Str;

use Closure;

abstract class Filter
{
	public function handle($request, Closure $next)
	{
		// if( request('notification_type') == $this->notificationType() )
		if( request('state') == $this->filterName() )
		{
			$builder = $next( $request );

			return $this->applyFilter($builder);
		}

		return $next( $request );
	}

	// kebab case
	protected function filterName()
	{
		return Str::kebab(class_basename($this));
	}

	// // must be in child class - i think so...
	// public function applyFilter($builder)
	// {		
	// 	return $builder->where('type', 'LIKE', '%'.class_basename($this).'%');		
	// }

	public abstract function applyFilter($builder);

	/**
     * This is method is actually in controller.
     *
     * @return attribute
     */

	public function index()
	{
		return app( Pipeline::class )
				->send( auth()->user()->notifications() )
				->through([
					// // products
					\App\CustomClasses\NotificationFilter\CriticalStock::class,
					\App\CustomClasses\NotificationFilter\Expired::class,
					\App\CustomClasses\NotificationFilter\OutOfStock::class,
					\App\CustomClasses\NotificationFilter\SoonExpiring::class,

					// // orders
					\App\CustomClasses\NotificationFilter\NewOrder::class,
					\App\CustomClasses\NotificationFilter\UpdatesOnCreatedOrder::class,

					// // collections
					\App\CustomClasses\NotificationFilter\PaidBillsInCollection::class,
				])
				->thenReturn()
				->get()
				->transform(function($notification, $index){
					return [
						'notification_html' => NotificationGenerator::createUiNotification($notification),
						'notification_data' => $notification,
					];
				});
	}
}