<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Module;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

       	// create roles
    	$role_admin = Role::create(['name' => 'Admin']);
		Role::create(['name' => 'Field Operation Manager']);
		Role::create(['name' => 'Accounting Officer']);
		Role::create(['name' => 'Inventory Officer']);
		Role::create(['name' => 'PSR']);

        // CREATE PERMISSIONS
        // product management
        // grouping permission into one single module
        Module::create(['name' => 'product management'])
			->permissions()
			->createMany([
				['name' => 'access_product'],
		        ['name' => 'add_product'],
		        ['name' => 'edit_product'],
		        ['name' => 'add_batch_number'],
		        ['name' => 'edit_batch_number'],
			]);

		// deals management
        Module::create(['name' => 'deals management'])
			->permissions()
			->createMany([
				['name' => 'access_deals'],
		        ['name' => 'add_deals'],
		        ['name' => 'edit_deals'],
			]);

		// product movement
        Module::create(['name' => 'price management'])
			->permissions()
			->createMany([
		        ['name' => 'access_price'],
			]);


		// product movement
        Module::create(['name' => 'product movement'])
			->permissions()
			->createMany([
		        ['name' => 'access_product_movement'],
			]);


		// soon expiring
        Module::create(['name' => 'soon expiring product'])
			->permissions()
			->createMany([
		        ['name' => 'access_soon_expiring_product'],
			]);


		// expired
        Module::create(['name' => 'expired product'])
			->permissions()
			->createMany([
		        ['name' => 'access_expired_product'],
			]);


		// returned product
        Module::create(['name' => 'returned product'])
			->permissions()
			->createMany([
		        ['name' => 'access_returned_product'],
			]);


		// loss product
        Module::create(['name' => 'loss product'])
			->permissions()
			->createMany([
		       ['name' => 'access_loss_product'],
		       ['name' => 'record_loss'],
			]);


		// ************
		// TRANSACTIONS
		// *************

		// sales
        Module::create(['name' => 'sales management'])
			->permissions()
			->createMany([
		        ['name' => 'access_sales'],
		        ['name' => 'record_returns'],
			]);


		// account receivables
        Module::create(['name' => 'account receivables'])
			->permissions()
			->createMany([
		        ['name' => 'access_receivables'],
			]);


		// collections
        Module::create(['name' => 'collections management'])
			->permissions()
			->createMany([
		        ['name' => 'access_collections'],
		        ['name' => 'record_deposit'],
			]);


		// *********************
		// Creating Transactions
		// *********************

		// create order
        Module::create(['name' => 'create order'])
			->permissions()
			->createMany([
				['name' => 'access_create_order'],
			]);


		// pending order
        Module::create(['name' => 'pending order'])
			->permissions()
			->createMany([
				['name' => 'access_pending_order'],
			]);


		// track order
        Module::create(['name' => 'track order'])
			->permissions()
			->createMany([
				['name' => 'access_track_order'],
				['name' => 'update_status'],
				['name' => 'deliver_order'],
			]);


		// *************
		// USERS
		// *************

		// account
        Module::create(['name' => 'customer account management'])
			->permissions()
			->createMany([
				['name' => 'access_account'],
				['name' => 'add_account'],
				['name' => 'edit_account'],
				['name' => 'view_account_history'],
			]);


		// roles
        Module::create(['name' => 'role management'])
			->permissions()
			->createMany([
				['name' => 'access_role'],
			]);

		// employee
        Module::create(['name' => 'employee management'])
			->permissions()
			->createMany([
				['name' => 'access_employee'],
				['name' => 'add_employee'],
				['name' => 'edit_employee'],
				['name' => 'view_employee_history'],
		        ['name' => 'view_employee_qoutas'],
		        ['name' => 'hold_product'],
			]);


		// supplier
        Module::create(['name' => 'supplier management'])
			->permissions()
			->createMany([
				['name' => 'access_supplier'],
				['name' => 'add_supplier'],
				['name' => 'edit_supplier'],
				['name' => 'view_supplier_history'],
			]);


		// outside modules

		// notifications
        Module::create(['name' => 'notification'])
			->permissions()
			->createMany([
				['name' => 'access_notifications'],
			]);


		// backup and restore
        Module::create(['name' => 'backup and restore'])
			->permissions()
			->createMany([
				['name' => 'access_backup_restore'],
			]);

		// give all the permission to admin
		$role_admin->syncPermissions( Permission::all() );

		// CREATING ADMIN ACCOUNT
		$faker = Faker\Factory::create();

		$admin_employee = \App\Employee::create([
			'profile_img' =>$faker->randomElement([
				            '/images/users/user.png',
				            '/images/users/user2.png',
				            '/images/users/user3.png',
				            '/images/users/user4.png' 
				        ]),
        
	        'fname' => $faker->firstNameMale,
	        'mname' => $faker->lastName,
	        'lname' => $faker->lastName,
	        
	        'contact_no' => $faker->phoneNumber,
	        'email' => 'adriannvalera@gmail.com',
	        'address' => $faker->address,
	        
	        'username' => 'admin',
	        'password' => '12341234',
		]);


		$admin_employee->assignRole('Admin');

		$admin_employee->target()->create([
			'start_date' => now()->addDays(1)->toDateString(),
			'target_amount' => 150000,
		]);

		// assign admin employee products...
		$admin_employee->products()->attach( [
			1 => [ 'active' => true ],
			2 => [ 'active' => true ],
			3 => [ 'active' => true ],
			4 => [ 'active' => true ],
		]);

    }
}