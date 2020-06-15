<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use \Carbon\Carbon;

use \Illuminate\Support\Facades\Auth;

// use models
use \App\OrderTransaction;
use \App\OrderMedicine;
use \App\OrderBatchNo;

use \App\Account;
use \App\Product;
use \App\BatchNo;
use \App\Employee;
use \App\DeliverTransaction;

use Spatie\Permission\Models\Permission;

// use Illuminate\Database\Eloquent\Builder;

use App\Dependency\PriceDependency;

// Route::get('try/{account}/{product}', function(Account $account, Product $product){

// 	return (new PriceDependency)->getPriceFor($account, $product);

// });

// use App\Http\Resources\Product as ProductResource;

// Route::resource('photos.comments', 'Inventory\ProductController');

use \Illuminate\Support\Facades\DB;

Route::get('try', function(){

	return \App\CustomClasses\PostCard::hellow('hmmm', 'ako');
	
	return request()->input();
});

Route::resource('practice', 'PracticeController');

// use Yajra\DataTables\DataTables;

// Route::get('users', function(DataTables $dataTable) {
//     $builder = $dataTable->getHtmlBuilder();

//     return $builder->toSql();
// });

// Route::get('order/{account}', 'Order\CreateController@products');

Route::get('carbon', function(){


	return now('UTC')->today()->toDateString();

});

Route::view('app', 'layouts.app');


Auth::routes(['home' => false]);

Route::get('/home', 'HomeController@index')->name('home');

//*************************************
//			ALL ROUTE VIEWS
//*************************************

// to be access only if logged in
Route::middleware(['auth'])->group(function(){

	Route::view('dashboard', 'dashboard')->name('dashboard');

	// INVENTORY
	Route::prefix('inventory')->group(function(){
		Route::name('inventory.')->group(function(){

			Route::view('products', 'inventory.product')
				->name('products')
				->middleware(['permission:access_product']);


			
			Route::view('in-and-out', 'inventory.in-and-out')
				->name('in-and-out');
				// ->middleware(['permission:access_product']);

			Route::view('price', 'inventory.price')
				->name('price')
				->middleware(['permission:access_price'])
				;


			Route::view('returns', 'inventory.returns')
				->name('returns')
				->middleware(['permission:access_returned_product']);

			Route::view('loss', 'inventory.loss')
				->name('loss')
				->middleware(['permission:access_loss_product']);

			Route::view('soon-expiring', 'inventory.soon-expiring')
				->name('soon-expiring')
				->middleware(['permission:access_soon_expiring_product']);

			Route::view('expired', 'inventory.expired')
				->name('expired')
				->middleware(['permission:access_expired_product']);

			Route::view('movement', 'inventory.movement')
				->name('movement')
				->middleware(['permission:access_product_movement']);

			// promo
			Route::view('deals', 'inventory.deals')
				->name('deals')
				->middleware(['permission:access_deals']);

		});
	});

	// TRANSACTIONS
	Route::name('transactions.')->group(function(){

		Route::prefix('transactions')->group(function(){

			// SALES
			Route::view('sales', 'transactions.sales')
				->name('sales')
				->middleware(['permission:access_sales']);

			// COLLECTIONS
			Route::view('collections', 'transactions.collection')
				->name('collections')
				->middleware(['permission:access_collections']);

			// RECEIVABLES
			// Route::prefix('receivables')->group(function(){
			// 	Route::name('receivables.')->group(function(){

			// 		Route::view('index', 'receivable.index')->name('index');

			// 	});
			// });

			Route::view('receivables', 'receivable.index')
				->name('receivables')
				->middleware(['permission:access_receivables']);

			Route::resource('receivables', 'Transactions\ReceivablesController')->parameters([
					'receivables' => 'account'
				])->only('show')
				->middleware(['permission:access_receivables']);

		});
		
	});

	// ORDER
	Route::prefix('order')->group(function(){
		Route::name('order.')->group(function(){

			Route::view('create', 'order.create')
				->name('create')
				->middleware(['permission:access_create_order']);
			Route::view('pending', 'order.pending')
				->name('pending')
				->middleware(['permission:access_pending_order']);
			Route::view('track', 'order.track')
				->name('track')
				->middleware(['permission:access_track_order']);

			Route::view('re-dr', 'order.re-dr')
				->name('re-dr');
				// ->middleware(['permission:access_track_order']);

		});
	});

	Route::view('roles', 'roles')
		->name('roles')
		->middleware(['permission:access_create_order']);

	// USERS
	Route::prefix('users')->group(function(){
		Route::name('users.')->group(function(){

			Route::view('customers', 'users.customer')
				->name('customers')
				->middleware(['permission:access_account']);
			Route::get('customers/{account}/history', 'Users\AccountController@history')
					->name('customers.history');

			Route::view('employees', 'users.employee')
				->name('employees')
				->middleware(['permission:access_employee']);
			Route::get('employees/{employee}/history', 'Users\EmployeeController@history')
					->name('employees.history');

			Route::view('suppliers', 'users.supplier')
				->name('suppliers')
				->middleware(['permission:access_supplier']);
			Route::get('suppliers/{supplier}/history', 'Users\SupplierController@history')
					->name('suppliers.history');

		});
	});

	// NOTIFICATION
	Route::view('notifications', 'notifications')
		->name('notifications')
		->middleware(['permission:access_notifications']);


	// BACK UP AND RESTORE
	Route::view('backup-restore', 'backup-restore')->name('backup.restore');


	// *******************************************************
	//					REDIRECTIONS
	// *******************************************************
	Route::prefix('redirect')->group(function(){

		// for redirection of product
		Route::get('inventory/product', 'RedirectController@inventoryProduct');

	});



//********************************
//		ALL AJAX REQUEST
//********************************
	Route::prefix('ajax')->group(function(){

		// privileges
		Route::resource('privilege', 'PrivilegeController');

		//*********************************
		//			DASHBOARD
		//*********************************
			Route::prefix('dashboard')->group(function(){\
				// 
				Route::get('charts', 'DashboardController@charts');
			});
			// resource
			Route::resource('dashboard', 'DashboardController')->only(['index']);
		//*********************************
		//			INVENTORY
		//*********************************
			Route::prefix('inventory')->group(function(){

				// PRODUCT
				Route::post('products/{product}', 'Inventory\ProductController@update');
				Route::resource('products', 'Inventory\ProductController')->except(['update']);
					//BATCH NO
				Route::prefix('products')->group(function(){

					// SET HOLDER
					Route::post('{product}/holder', 'Inventory\ProductController@holder');

					// dependents in product
					Route::get('{product}/batchNo', 'Inventory\BatchNoController@index');
					Route::post('{product}/batchNo', 'Inventory\BatchNoController@store');

				});

				// every single batch number
				Route::resource('batchNo', 'Inventory\BatchNoController')->only([
					'show', 'update',
				]);

				// PROMO
				Route::get('deals/products', 'Inventory\DealsController@products');
				Route::resource('deals', 'Inventory\DealsController');


				Route::get('price/products', 'Inventory\PriceController@products');
				Route::resource('price', 'Inventory\PriceController');

				// loss
				Route::post('loss/{batchNo}', 'Inventory\LossProductsController@recordLoss');
				Route::resource('loss', 'Inventory\LossProductsController');

				// product movement
				Route::resource('movement', 'Inventory\MovementController');

				// soon expiring
				Route::resource('soon-expiring', 'Inventory\SoonExpiringController');

				// expired
				Route::resource('expired', 'Inventory\ExpiredController');

				// RETURNEE - dependent in order batch number in SALES
				Route::patch('returns/success/{returnee}', 'Inventory\ReturneeController@success');
				Route::resource('returns', 'Inventory\ReturneeController');

			});

		//*********************************
		//			TRANSACTIONS
		//*********************************
			Route::prefix('transactions')->group(function(){

				// SALES
				Route::prefix('sales')->group(function(){
				
					Route::get('orderMedicine/{orderTransaction}', 'Transactions\SalesController@showOrders');

					Route::get('{orderTransaction}/collections', 'Transactions\SalesController@getCollections');

					// Route::get('{orderTransaction}/toReturn', 'Transactions\SalesController@toReturn');

					// REPLACED PRODUCTS
					Route::get('{orderTransaction}/replacedProduct', 'Transactions\SalesController@replacedProduct');

					// chart reports
					Route::get('charts', 'Transactions\SalesController@charts');

				});
				// resource
				Route::resource('sales', 'Transactions\SalesController')->parameters([
					'sales' => 'orderTransaction'
				]);

				// ACCOUNT RECEIVABLES
				Route::resource('receivables', 'Transactions\ReceivablesController')->only([
					'index', 'show',
				])->parameters([ 'receivables' => 'account' ]);
				
				Route::prefix('receivables')->group(function(){

					Route::post('{orderTransaction}/getOrderMedicines', 'Transactions\ReceivablesController@getOrderMedicines');

					Route::get('{account}/bills', 'Transactions\ReceivablesController@showOrderTransaction');
					Route::post('{account}/payment', 'Transactions\ReceivablesController@receivablePayment');

				});

				// COLLECTIONS
				Route::prefix('collections')->group(function(){

					Route::post('{collectionTransaction}/deposit', 'Transactions\CollectionsController@deposit');

					Route::get('{collectionTransaction}/paidBills', 'Transactions\CollectionsController@getPaidBills');

					Route::get('charts', 'Transactions\CollectionsController@charts');

					Route::get('paidOrderMedicines', 'Transactions\CollectionsController@paidOrderMedicines');

				});
				Route::resource('collections', 'Transactions\CollectionsController')->only([
					'index', 'show',
				])->parameters([
					'collections' => 'collectionTransaction'
				]);

			});


		//*********************************
		//			ORDERS
		//*********************************
			// ORDER
			Route::prefix('order')->group(function(){

				// CREATE
				Route::resource('create', 'Order\CreateController')->only(['store']);
				Route::prefix('create')->group(function(){

					Route::get('account', 'Order\CreateController@accounts');
					Route::post('product/{account}', 'Order\CreateController@products');

				});

				// PENDING
				Route::prefix('pending')->group(function(){
					Route::get('orderMedicine/{orderTransaction}', 'Order\PendingController@showOrders');
				});
				Route::resource('pending', 'Order\PendingController')/*->only([
					'index', 'show',
				])*/->parameters([
					'pending' => 'orderTransaction'
				]);


				// TRACK
				Route::resource('track', 'Order\TrackController')->only([
					'index', 'show',
				])->parameters([
					'track' => 'orderTransaction'
				]);
				Route::prefix('track')->group(function(){

					Route::patch('status/{orderTransaction}', 'Order\TrackController@change_status');
					
					Route::post('delivery/{orderTransaction}', 'Order\TrackController@delivery');

					// get order medicine - datatables
					Route::get('orderMedicine/{orderTransaction}', 'Order\TrackController@showOrders');

				});

				// RE DR's
				Route::resource('re-dr', 'Order\ReplacementController')
					->parameters([
						're-dr' => 'orderTransaction',
					]);

			});


		//************************************
		//			ROLES
		//************************************
			// Route::get('roles/modulesAndTheirMethods', 'RolesController@get_modulesAndTheirMethods');
			// Route::patch('roles/update', 'RolesController@updateRoles');
			Route::resource('roles', 'RolesController')
				->only([ 'index', 'create', 'store', 'show', 'update' ]);

		//*********************************
		//			USERS
		//*********************************
			Route::prefix('users')->group(function(){
				// ACCOUNT
				Route::post('accounts/{account}', 'Users\AccountController@update');
				Route::resource('accounts', 'Users\AccountController')->except(['update']);
				
				// ACCOUNT HISTORY
				Route::prefix('accounts/history')->group(function(){

					Route::get('{account}', 'Users\AccountController@history');
					Route::get('{account}/ajax', 'Users\AccountController@getHistory');

					// /users/history/{account}/charts
					Route::get('{account}/charts', 'Users\AccountController@charts');

					Route::get('{account}/get_bills_can_paid_using_excess_payment', 'Users\AccountController@get_bills_can_paid_using_excess_payment');

				});

				// EMPLOYEE HOLDED PRODUCTS
				Route::prefix('employees')->group(function(){
					//
					Route::get('products', 'Users\EmployeeController@getHoldedProducts');

					// get products for employee
					Route::get('{employee}/getProductsFor', 'Users\EmployeeController@getProductsFor');
					// can be patch or post - but in ajax this is post
					Route::post('{employee}/setProductsFor', 'Users\EmployeeController@setProductsFor');

					// QOUTAS
					Route::get('qoutas', 'Users\EmployeeController@qoutas');

				});
				// EMPLOYEE
				Route::post('employees/{employee}', 'Users\EmployeeController@update');
				Route::resource('employees', 'Users\EmployeeController')->except(['update']);

				// SUPPLIER
				Route::post('suppliers/{supplier}', 'Users\SupplierController@update');
				Route::resource('suppliers', 'Users\SupplierController')->except(['update']);
			});

		//**********************************
		//		OUTSIDE MODULES
		//**********************************

		// update notification of read and unread
		Route::patch('notifications/markAsRead', 'NotificationController@markAsRead');
		Route::resource('notifications', 'NotificationController');

		// backup and restore
		Route::get('backup', 'BackupRestoreController@backup')->name('backup');
		Route::get('restore', 'BackupRestoreController@restore')->name('restore');

	});

}); // end of middleware - access if authenticated





// ******************************************************************************************

Route::get('view-product-movement', 'Inventory\ProductMovementController@viewProductMovement');
Route::get('getProductMovement', 'Inventory\ProductMovementController@getProductMovement');

Route::get('productMovementDateRange/{startDate}/{endDate}', 'Inventory\ProductMovementController@productMovementDateRange');


Route::get('forgotPasswordCheckUser/{username}', 'ForgotPasswordController@checkUser');

Route::get('forgotPasswordCheckRecoveryCode/{recoverycode}', 'ForgotPasswordController@checkRecoveryCode');



// forgot password
Route::view('/viewForgotPassword', 'forgotPassword');

Route::post('changePassword', 'ForgotPasswordController@changePassword');

Route::get('/bacupRestore', function () {
    return view('forgotPassword');
});


Route::get('/backupDatabase', 'BackupRestoreController@backupDatabase')->name('backupDatabase');

Route::get('/restoreDatabase/{dbpath}', 'BackupRestoreController@restoreDatabase')->name('restoreDatabase');
