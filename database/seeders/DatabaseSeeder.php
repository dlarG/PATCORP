<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Driver;
use App\Models\FileCategory;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $admin = User::create([
            'username' => 'admin',
            'password_hash' => Hash::make('admin123'),
            'email' => 'admin@system.com',
            'user_type' => 'admin',
            'first_name' => 'System',
            'last_name' => 'Admin',
            'is_active' => true
        ]);

        // Create driver user
        $driver = User::create([
            'username' => 'driver001',
            'password_hash' => Hash::make('driver123'),
            'email' => 'driver001@email.com',
            'user_type' => 'driver',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '09123456789',
            'is_active' => true
        ]);

        // Create driver record
        Driver::create([
            'user_id' => $driver->id,
            'driver_id' => 'DRV-2024-001',
            'license_number' => 'L123456789',
            'license_expiry' => now()->addYears(5),
            'vehicle_type' => 'car',
            'vehicle_plate' => 'ABC123',
            'address' => '123 Main St, City',
            'emergency_contact' => 'Jane Doe',
            'emergency_phone' => '09123456788',
            'hire_date' => now()->subMonths(6),
            'status' => 'active',
            'payment_status' => 'paid',
            'monthly_salary' => 25000.00
        ]);

        // Create file categories
        $categories = [
            ['category_name' => 'Driver Documents', 'description' => 'Licenses, contracts, and identification'],
            ['category_name' => 'Payment Records', 'description' => 'Salary slips and payment proofs'],
            ['category_name' => 'Vehicle Documents', 'description' => 'Registration and insurance papers'],
            ['category_name' => 'General', 'description' => 'Miscellaneous files']
        ];

        foreach ($categories as $category) {
            FileCategory::create([
                'category_name' => $category['category_name'],
                'description' => $category['description'],
                'created_by' => $admin->id
            ]);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin / admin123');
        $this->command->info('Driver: driver001 / driver123');
    }
}