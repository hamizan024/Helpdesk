<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'          => fake()->unique()->words(2, true),
            'description'   => fake()->optional(0.6)->sentence(),
            'department_id' => Department::inRandomOrder()->value('id'),
            'is_active'     => fake()->boolean(90),
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}