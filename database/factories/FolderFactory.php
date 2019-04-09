<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Folder::class, function (Faker $faker) {
    return [
        'name' => $faker->randomAscii
    ];
});
