<?php

use Illuminate\Database\Seeder;

use App\Dependency\RolesDependency;

class AdminDefaultDatasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $rolesDependency;

    public function __construct(RolesDependency $rolesDependency)
    {
    	$this->rolesDependency = $rolesDependency;
    }

    public function run()
    {

        $faker = \Faker\Factory::create();

        // set position and roles
        $this->rolesDependency->set_positionAndRoles();
        // create admin user in default
        \App\Employee::create([
            'profile_img' =>$faker->randomElement([
            '/images/users/user.png',
            '/images/users/user2.png',
            '/images/users/user3.png',
            '/images/users/user4.png' ]),
            
            'fname' => $faker->firstNameMale,
            'mname' => $faker->lastName,
            'lname' => $faker->lastName,

            'position_id' => 1,
            
            'contact_no' => $faker->phoneNumber,
            'email' => $faker->email,
            'address' => $faker->address,
            
            'username' => 'admin',
            'password' => Hash::make('12341234'),
        ]);
    }
}
