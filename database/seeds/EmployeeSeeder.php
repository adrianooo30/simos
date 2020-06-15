<?php

use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $rolesDependency;

    public function __construct()
    {
    	
    }

    public function run()
    {
        $employee = factory(\App\Employee::class)->create();
        // 
        $employee->assignRole('PSR');
    }
}
