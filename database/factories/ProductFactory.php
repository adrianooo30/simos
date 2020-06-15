<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'product_img' => $faker->randomElement([
        	'/images/products/medicine.png',
        	'/images/products/band-aid.png',
        	'/images/products/hospital.png',
        	'/images/products/pills.png' ]),

        'generic_name' => $faker->randomElement([ 'Pantrozole', 'Ampicillin+Sulbactam', 'Alucard' ]),
        'brand_name' => $faker->randomElement([ 'Pantrozole', 'Amsutas', 'Nicard' ]),
        'weight_volume' => $faker->randomElement([ '1ml + 0.5lq', '10ml', '7ml + 0.6ml' ]),

        'strength' => $faker->randomElement([ '1.5G', '750MG', '15MG' ]),
        
        'product_unit' => $faker->randomElement(['vial',]),
        
        'unit_price' => $faker->numberBetween($min = 100, $max = 900),
        'critical_quantity' => $faker->numberBetween($min = 100, $max = 900),
    ];
});
