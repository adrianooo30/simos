<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\BatchNo;
use Faker\Generator as Faker;


$factory->define(BatchNo::class, function (Faker $faker) {

	$currentDate = new \Carbon\Carbon();

    return [
        'product_id' => $faker->randomElement([1, 2, 3]),
        'batch_no' => $faker->word,
        'unit_cost' => $faker->numberBetween($min = 100, $max = 999),
        'quantity' => $faker->numberBetween($min = 100, $max = 999),
        'exp_date' => $currentDate->addDays(5)->toDateString(),
        'date_added' => $currentDate->toDateString(),
        'supplier_id' => $faker->numberBetween($min = 1, $max = 5),
    ];
});
