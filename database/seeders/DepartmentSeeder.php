<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Information Technology', 'description' => 'Manages all IT infrastructure, hardware, and software.', 'is_active' => true],
            ['name' => 'Human Resources',        'description' => 'Handles recruitment, payroll, and employee relations.', 'is_active' => true],
            ['name' => 'Finance & Accounting',   'description' => 'Oversees budgets, financial reporting, and audits.',   'is_active' => true],
            ['name' => 'Operations',             'description' => 'Coordinates day-to-day business operations.',           'is_active' => true],
            ['name' => 'Marketing',              'description' => 'Manages brand, campaigns, and customer engagement.',    'is_active' => true],
        ];

        foreach ($departments as $data) {
            Department::firstOrCreate(['name' => $data['name']], $data);
        }
    }
}