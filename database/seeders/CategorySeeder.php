<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Department;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $it  = Department::where('name', 'Information Technology')->value('id');
        $hr  = Department::where('name', 'Human Resources')->value('id');
        $fin = Department::where('name', 'Finance & Accounting')->value('id');
        $ops = Department::where('name', 'Operations')->value('id');
        $mkt = Department::where('name', 'Marketing')->value('id');

        $categories = [
            ['name' => 'Hardware Issue',        'description' => 'Problems related to physical hardware components.',     'department_id' => $it,  'is_active' => true],
            ['name' => 'Software Issue',        'description' => 'Bugs, crashes, or application malfunctions.',           'department_id' => $it,  'is_active' => true],
            ['name' => 'Network & Connectivity','description' => 'Internet, VPN, and LAN connectivity issues.',           'department_id' => $it,  'is_active' => true],
            ['name' => 'Account & Access',      'description' => 'Login failures, password resets, and permissions.',     'department_id' => $it,  'is_active' => true],
            ['name' => 'Payroll Query',         'description' => 'Questions or errors regarding salary and deductions.',   'department_id' => $hr,  'is_active' => true],
            ['name' => 'Leave & Attendance',    'description' => 'Leave requests and attendance discrepancies.',           'department_id' => $hr,  'is_active' => true],
            ['name' => 'Invoice & Billing',     'description' => 'Issues with vendor invoices and client billing.',       'department_id' => $fin, 'is_active' => true],
            ['name' => 'Procurement Request',   'description' => 'Requests for purchasing new equipment or supplies.',    'department_id' => $ops, 'is_active' => true],
            ['name' => 'Facility Maintenance',  'description' => 'Office maintenance and facility-related issues.',       'department_id' => $ops, 'is_active' => true],
            ['name' => 'Campaign Support',      'description' => 'Requests to support marketing campaigns and materials.','department_id' => $mkt, 'is_active' => true],
        ];

        foreach ($categories as $data) {
            Category::firstOrCreate(['name' => $data['name']], $data);
        }
    }
}