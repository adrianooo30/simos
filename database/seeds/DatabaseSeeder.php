<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
        	AccountSeeder::class,
        	SupplierSeeder::class,
        	ProductSeeder::class,
        	BatchNoSeeder::class,
            // AdminDefaultDatasSeeder::class,
            RolesAndPermissionsSeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}
