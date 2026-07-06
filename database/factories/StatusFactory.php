<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StatusFactory extends Factory
{
    public function definition(): array
    {
        static $statuses = [
            ['name' => 'Open',        'color' => 'info',      'is_default' => true],
            ['name' => 'In Progress', 'color' => 'primary',   'is_default' => false],
            ['name' => 'On Hold',     'color' => 'warning',   'is_default' => false],
            ['name' => 'Resolved',    'color' => 'success',   'is_default' => false],
            ['name' => 'Closed',      'color' => 'secondary', 'is_default' => false],
        ];

        $row = array_shift($statuses) ?? [
            'name'       => fake()->unique()->word(),
            'color'      => fake()->randomElement(['danger', 'warning', 'primary', 'secondary', 'info', 'success']),
            'is_default' => false,
        ];

        return array_merge($row, ['is_active' => true]);
    }
}