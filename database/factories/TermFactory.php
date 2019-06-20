<?php
$factory->define(Jlab\Taxonomy\Term::class, function (Faker\Generator $faker) {
    return [
        'vocabulary_id' => function () {
            return factory(Jlab\Taxonomy\Vocabulary::class)->create()->id;
        },
        'name' => $faker->unique()->text(80),
        'is_active' => 'true'
    ];
});