<?php
$factory->define(Jlab\Taxonomy\Vocabulary::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->text(80),
        'description' => $faker->unique()->text(1000),
    ];
});