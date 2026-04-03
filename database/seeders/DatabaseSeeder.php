<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\LostItem;
use App\Models\FoundItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@campus.edu',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '555-0100',
            'department' => 'Administration',
        ]);

        User::create([
            'name' => 'John Student',
            'email' => 'john@campus.edu',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '555-0101',
            'department' => 'Computer Science',
        ]);

        User::create([
            'name' => 'Jane Faculty',
            'email' => 'jane@campus.edu',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '555-0102',
            'department' => 'Mathematics',
        ]);

        LostItem::create([
            'user_id' => 2,
            'item_name' => 'MacBook Pro 14"',
            'description' => 'Silver laptop with a blue sticker on the lid. Has a scratch on the left side. Serial number: ABC123XYZ',
            'category' => 'Electronics',
            'date_lost' => now()->subDays(3),
            'location' => 'Main Library, 2nd Floor',
            'status' => 'unclaimed',
        ]);

        LostItem::create([
            'user_id' => 3,
            'item_name' => 'Calculus Textbook',
            'description' => 'Blue cover, "Calculus: Early Transcendentals" by James Stewart. My name is written on the first page.',
            'category' => 'Books',
            'date_lost' => now()->subDays(5),
            'location' => 'Science Building Room 301',
            'status' => 'unclaimed',
        ]);

        FoundItem::create([
            'user_id' => 2,
            'item_name' => 'Silver Laptop',
            'description' => 'Found near the library entrance. Looks like a MacBook Pro. No visible identification marks. Please describe to claim.',
            'category' => 'Electronics',
            'date_found' => now()->subDays(2),
            'location' => 'Library Main Entrance',
            'status' => 'unclaimed',
        ]);

        FoundItem::create([
            'user_id' => 3,
            'item_name' => 'Blue Calculus Book',
            'description' => 'Found on a desk in the science building. Blue cover, several pages have highlights.',
            'category' => 'Books',
            'date_found' => now()->subDays(4),
            'location' => 'Science Building Study Area',
            'status' => 'unclaimed',
        ]);
    }
}
