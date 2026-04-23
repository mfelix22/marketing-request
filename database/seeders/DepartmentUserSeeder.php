<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DepartmentUserSeeder extends Seeder
{
    public function run(): void
    {
        // Example department heads / key users
        $departments = [
            'Marcom (Marketing Communication)' => [
                // Admin Marketing: manages production workflow (marcom role)
                // Marcom Manager: approves requests as Sales Manager (manager role)
                ['name' => 'Marcom Manager', 'username' => 'marcommgr', 'email' => 'marcommgr@hartonogroup.com', 'role' => 'manager'],
            ],
            'Sales' => [
                ['name' => 'Sales Manager', 'username' => 'salesmgr', 'email' => 'salesmgr@hartonogroup.com', 'role' => 'manager'],
            ],
            'Aftersales' => [
                ['name' => 'Aftersales Manager', 'username' => 'aftersalesmgr', 'email' => 'aftersalesmgr@hartonogroup.com', 'role' => 'manager'],
            ],
            'Finance' => [
                ['name' => 'Finance Manager', 'username' => 'finmgr', 'email' => 'finmgr@hartonogroup.com', 'role' => 'manager'],
            ],
            'Management' => [
                ['name' => 'General Manager', 'username' => 'gm', 'email' => 'gm@hartonogroup.com', 'role' => 'gm'],
                ['name' => 'Director', 'username' => 'albert', 'email' => 'albert@hartonogroup.com', 'role' => 'director'],
            ],
        ];

        foreach ($departments as $deptName => $users) {
            $department = Department::firstOrCreate(['name' => $deptName]);
            foreach ($users as $user) {
                User::updateOrCreate(
                    ['email' => $user['email']],
                    [
                        'name'          => $user['name'],
                        'username'      => $user['username'],
                        'password'      => Hash::make($user['username'] . '1234'),
                        'role'          => $user['role'],
                        'department_id' => $department->id,
                    ]
                );
            }
        }
    }
}
