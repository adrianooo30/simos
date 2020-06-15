<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Account;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
    	'profile_img' => $faker->randomElement([
    		'/images/users/user.png',
    		'/images/users/user2.png',
    		'/images/users/user3.png',
    		'/images/users/user4.png' ]),
        'account_name' => $faker->name,
        'type' => $faker->randomElement(['Hospital', 'Clinic', 'Pharmacy']),
        'address' => $faker->address,
        'contact_no' => $faker->phoneNumber,
        'contact_person' => $faker->name,
    ];
});
