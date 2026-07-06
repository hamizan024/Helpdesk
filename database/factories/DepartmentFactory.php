<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    public function definition(): array
    {
        static $departments = [
            'Information Technology', 'Human Resources', 'Finance & Accounting',
            'Operations', 'Marketing', 'Sales', 'Legal & Compliance',
            'Customer Support', 'Research & Development', 'Procurement',
        ];

        return [
            'name'        => array_shift($departments) ?? fake()->unique()->company(),
            'description' => fake()->optional(0.7)->sentence(),
            'is_active'   => fake()->boolean(90),
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}