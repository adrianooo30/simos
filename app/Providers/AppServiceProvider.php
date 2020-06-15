<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton( 'PostcardSendingService', function( $app ){
            // setting attributes
            return new \App\CustomClasses\PostcardSendingService( ['us', '5', '6'] );
        } );

        $this->app->singleton( 'ViewOrderDependency', function( $app ){
            // setting attributes
            return new \App\Dependency\ViewOrderDependency();
        } );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // observe
        \App\Account::observe( \App\Observers\AccountObserver::class );
        \App\OrderTransaction::observe( \App\Observers\OrderTransactionObserver::class );

        Schema::defaultStringLength(191); 

        // SET THE LOCALE - BASED ON APP.LOCALE
        Carbon::setLocale( config('app.locale') );

        Builder::macro('whereLike', function($attributes, string $searchTerms) {
            $this->where(function (Builder $query) use ($attributes, $searchTerms) {
                foreach ($attributes as $attribute) {
                    // $query->when(
                    //     str_contains($attribute, '.'),
                    //     function (Builder $query) use ($attribute, $searchTerms) {
                    //         [$relationName, $relationAttribute] = explode('.', $attribute);

                    //         $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerms) {
                    //             $query->where($relationAttribute, 'LIKE', "%{$searchTerms}%");
                    //         });
                    //     },
                    //     function (Builder $query) use ($attribute, $searchTerms) {
                    //         foreach( explode(' ',$searchTerms) as $term )
                    //             $query->orWhere($attribute, 'LIKE', "%{$term}%");

                    //     }
                    // );

                    foreach( explode(' ',$searchTerms) as $term )
                        $query->orWhere($attribute, 'LIKE', "%{$term}%");
                }
            });

            return $this;
        });
    }
}
