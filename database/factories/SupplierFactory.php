<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Supplier;
use Faker\Generator as Faker;

$factory->define(Supplier::class, function (Faker $faker) {
    return [
        'profile_img' => $faker->randomElement([
    		'/images/users/user.png',
    		'/images/users/user2.png',
    		'/images/users/user3.png',
    		'/images/users/user4.png' ]),
        'supplier_name' => $faker->company,
        'address' => $faker->address,
        'contact_no' => $faker->phoneNumber,
    ];
});
