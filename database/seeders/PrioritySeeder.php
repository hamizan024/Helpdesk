<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    public function run(): void
    {
        $priorities = [
            ['name' => 'Critical', 'color' => 'danger',    'level' => 1, 'is_active' => true],
            ['name' => 'High',     'color' => 'warning',   'level' => 2, 'is_active' => true],
            ['name' => 'Medium',   'color' => 'primary',   'level' => 3, 'is_active' => true],
            ['name' => 'Low',      'color' => 'secondary', 'level' => 4, 'is_active' => true],
        ];

        foreach ($priorities as $data) {
            Priority::firstOrCreate(['name' => $data['name']], $data);
        }
    }
}