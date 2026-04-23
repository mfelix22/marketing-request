<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Departments
        $departments = [
            'Marcom (Marketing Communication)',
            'Sales',
            'Aftersales',
            'Finance',
            'Sparepart',
            'Purchasing',
            'HR & General Affair',
            'IT',
            'Management',
        ];

        foreach ($departments as $name) {
            Department::firstOrCreate(['name' => $name]);
        }

        $marcomDept = Department::where('name', 'Marcom (Marketing Communication)')->first();
        $mgmtDept   = Department::where('name', 'Management')->first();
        $salesDept  = Department::where('name', 'Sales')->first();

        // Demo users
        User::firstOrCreate(
            ['email' => 'admin@hartonogroup.com'],
            [
                'name'          => 'Administrator',
                'username'      => 'admin',
                'password'      => Hash::make('admin1234'),
                'role'          => 'admin',
                'department_id' => $marcomDept?->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'marcom@hartonogroup.com'],
            [
                'name'          => 'Marcom Team',
                'username'      => 'marcom',
                'password'      => Hash::make('marcom1234'),
                'role'          => 'marcom',
                'department_id' => $marcomDept?->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'manager@hartonogroup.com'],
            [
                'name'          => 'Branch Manager',
                'username'      => 'manager',
                'password'      => Hash::make('manager1234'),
                'role'          => 'manager',
                'department_id' => $mgmtDept?->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff@hartonogroup.com'],
            [
                'name'          => 'Sales Staff',
                'username'      => 'staff',
                'password'      => Hash::make('staff1234'),
                'role'          => 'staff',
                'department_id' => $salesDept?->id,
            ]
        );
        // Seed department heads and key users
        $this->call(DepartmentUserSeeder::class);
    }
}
