<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $departments = [
            ['name' => 'BSBA', 'code' => 'BSBA', 'is_active' => true],
            ['name' => 'BSCS', 'code' => 'BSCS', 'is_active' => true],
            ['name' => 'BSED', 'code' => 'BSED', 'is_active' => true],
            ['name' => 'BSHM', 'code' => 'BSHM', 'is_active' => true],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@campus.edu',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '555-0100',
            'department' => 'BSCS',
        ]);

        User::create([
            'name' => 'John Smith',
            'email' => 'john@campus.edu',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '555-0101',
            'department' => 'BSCS',
            'year_level' => 2,
            'semester' => '1st',
        ]);

        User::create([
            'name' => 'Maria Garcia',
            'email' => 'maria@campus.edu',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '555-0102',
            'department' => 'BSBA',
            'year_level' => 1,
            'semester' => '1st',
        ]);

        User::create([
            'name' => 'David Johnson',
            'email' => 'david@campus.edu',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '555-0103',
            'department' => 'BSED',
            'year_level' => 3,
            'semester' => '2nd',
        ]);

        User::create([
            'name' => 'Sarah Williams',
            'email' => 'sarah@campus.edu',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '555-0104',
            'department' => 'BSHM',
            'year_level' => 4,
            'semester' => '1st',
        ]);

        User::create([
            'name' => 'Michael Brown',
            'email' => 'michael@campus.edu',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '555-0105',
            'department' => 'BSCS',
            'year_level' => 1,
            'semester' => '2nd',
        ]);
    }
}
