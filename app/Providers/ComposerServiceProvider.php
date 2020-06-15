<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Auth;

// package model
use Spatie\Permission\Models\Role;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // \Debugbar::disable();

        // FOR EMPLOYEE IF MATCH THE POSITION WHICH CAN HE HOLD A PRODUCT
        // View::composer(['includes.datatables.action'], function($view){

        //     $positions_that_holds_products = ['PSR', 'FOM'];

        //     $view->with('positions_that_holds_products', $positions_that_holds_products);
        // });

        // PRODUCTS
        View::composer(['inventory.product'], function($view){
            // $employeeDependency = new \App\Dependency\EmployeeDependency();

            $view->with('suppliers', Cache::rememberForever('suppliers', function(){
                return \App\Supplier::all();
            }));
                // ->with('employees', $employeeDependency->getEmployeesFor('create order', 'can add order') );
        });

        View::composer(['order.create'], function($view){
            // add accounts
            $view->with('accounts', \App\Account::paginate(12));
        });


        // RETURNS
        // View::composer(['inventory.returnee'], function($view){
        //     $view->with('returnees', \App\Returnee::all());
        // });

        // // RETURNS
        // View::composer(['inventory.price'], function($view){
        //     $view->with('accounts', \App\Account::all());
        // });


        // LOSSES
        // View::composer(['inventory.loss'], function($view){
        //     // $lossDependency = new \App\Dependency\LossDependency();

        //     $view->with('losses', $lossDependency->get_lossProducts());
        // });


        // SOON EXPIRING
        // View::composer(['inventory.soon-expiring'], function($view){
        //     $soonExpiringDependencys = new \App\Dependency\SoonExpiringDependency();

        //     $view->with('soonExpiring', $soonExpiringDependencys->get_soonExpiringProducts());
        // });


        // EXPIRED
        // View::composer(['inventory.expired'], function($view){
        //     $expiredDependency = new \App\Dependency\ExpiredDependency();
        //     // return expired
        //     $view->with('expired', $expiredDependency->get_expiredProducts());
        // });


        // ACCOUNTS
        // View::composer(['users.account'], function($view){
        //     $view->with( 'accounts', \App\Account::all() );
        // });


        // // EMPLOYEES
        View::composer(['users.employee'], function($view){
            $view->with( 'employees', \App\Employee::all() )
                ->with( 'roles', Role::all() );
        });


        // // SUPPLIERS
        // View::composer(['users.supplier'], function($view){
        //     $view->with( 'suppliers', \App\Supplier::all() );
        // });

        // // CREATE ORDER
        // View::composer(['order.create'], function($view){
        //     $view->with( 'accounts', \App\Account::paginate(3) )
        //         ->with( 'products', \App\Product::paginate(2) );
        // });


        // ORDER TRANSACTIONS
        // View::composer(['order.view'], function($view){
        //     $orders = \App\OrderTransaction::where('status', 'Pending')->get();

        //     $view->with( 'orders', $orders );
        // });


        // ACCOUNTS - WITH BALANCE
        // View::composer(['receivable.index'], function($view){
        //     $accountDependency = new \App\Dependency\AccountDependency();

        //     $view->with('receivables', $accountDependency->get_accountsWithBalance());
        // });



        // COLLECTIONS
        // View::composer(['transactions.collection'], function($view){
        //     $collectionDependency = new \App\Dependency\CollectionDependency();

        //     $view->with('collections', $collectionDependency->get_collections());
        // });



        // TRACK ORDER
        // View::composer(['order.track'], function($view){
        //     $trackOrderDependency = new \App\Dependency\TrackOrderDependency();
        //    // get query
        //     $orderTransactions = $trackOrderDependency->getQuery();
        //     // retrieve all orders - track
        //     $view->with( 'orders', $trackOrderDependency->get_trackOrders($orderTransactions) );
        // });



        // SALES
        // View::composer(['transactions.sales'], function($view){
        //     $salesDependency = new \App\Dependency\SalesDependency();
        //     // get query
        //     $orderTransactions = $salesDependency->getQuery();
        //     // retrieve all sales
        //     $view->with( 'sales', $salesDependency->get_sales($orderTransactions) );
        // });



        // GET PSR EMPLOYEE
        // View::composer(['transactions.collection'], function($view){
        //     $getEmployees = \App\Employee::orderBy('lname')->get();
            
        //     $positionToGet = 'PSR';
        //     $employees = array();
        //     foreach ($getEmployees as $employee) {
        //         // GET THE SPECIFIED POSITION
        //         if(strtoupper( $employee->position['position_name'] ) == strtoupper($positionToGet) ) {
        //             $employeeDependency = new \App\Dependency\EmployeeDependency();
        //             // insert in array
        //             array_push($employees, $employeeDependency->getEmployeeDetails($employee) );
        //         }
        //     }

        //     $view->with('employees', $employees);
        // });

        View::composer(['roles'], function($view){
            // return authenticated user, and well formatted that information
            $view->with('roles', Role::all());
        });

        // View::composer(['includes.roles.permissions-list'], function($view){
        //     // return authenticated user, and well formatted that information
        //     $view->with('position', \App\Position::find(1))
        //         ->with('modules_and_permissions', Role::with(['permissions'])->get());
        // });



        // EVERY CALL OF PARENT - APP
        // View::composer(['layouts.app'], function($view){
        //     $view->with('positions', \App\Position::all());
        // });
    }
}
