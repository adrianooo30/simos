<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Employee;
use Faker\Generator as Faker;

use Illuminate\Support\Facades\Hash;

$factory->define(Employee::class, function (Faker $faker) {
    
    return [
        'profile_img' =>$faker->randomElement([
            '/images/users/user.png',
            '/images/users/user2.png',
            '/images/users/user3.png',
            '/images/users/user4.png' ]),
        
        'fname' => $faker->firstNameMale,
        'mname' => $faker->lastName,
        'lname' => $faker->lastName,
        
        'contact_no' => $faker->phoneNumber,
        'email' => $faker->email,
        'address' => $faker->address,
        
        'username' => 'psr',
        'password' => '12341234',
    ];

});
