<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Open',        'color' => 'info',      'is_default' => true,  'is_active' => true],
            ['name' => 'In Progress', 'color' => 'primary',   'is_default' => false, 'is_active' => true],
            ['name' => 'On Hold',     'color' => 'warning',   'is_default' => false, 'is_active' => true],
            ['name' => 'Resolved',    'color' => 'success',   'is_default' => false, 'is_active' => true],
            ['name' => 'Closed',      'color' => 'secondary', 'is_default' => false, 'is_active' => true],
        ];

        foreach ($statuses as $data) {
            Status::firstOrCreate(['name' => $data['name']], $data);
        }
    }
}