<?php

/** @var Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use sbamtr\LaravelAutoHardDeleter\Tests\Models\SampleModel;

$factory->define(SampleModel::class, function (Faker $faker) {
    return [
        'foo' => $faker->numberBetween(),
        'bar' => $faker->text,
    ];
});
