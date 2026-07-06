<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PriorityFactory extends Factory
{
    public function definition(): array
    {
        static $priorities = [
            ['name' => 'Critical', 'color' => 'danger',    'level' => 1],
            ['name' => 'High',     'color' => 'warning',   'level' => 2],
            ['name' => 'Medium',   'color' => 'primary',   'level' => 3],
            ['name' => 'Low',      'color' => 'secondary', 'level' => 4],
        ];

        $row = array_shift($priorities) ?? [
            'name'  => fake()->unique()->word(),
            'color' => fake()->randomElement(['danger', 'warning', 'primary', 'secondary', 'info', 'success']),
            'level' => fake()->unique()->numberBetween(5, 99),
        ];

        return array_merge($row, ['is_active' => true]);
    }
}