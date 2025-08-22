<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateNewAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing admin user first
        User::where('email', 'admin@admin.com')->delete();
        
        // Create new admin user
        $adminUser = User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
            'is_admin' => true,
        ]);
        
        $this->command->info('New admin user created successfully!');
        $this->command->info('Email: ' . $adminUser->email);
        $this->command->info('Password: 123456');
        $this->command->info('is_admin: ' . ($adminUser->is_admin ? 'true' : 'false'));
        
        // Also create a test user
        $testUser = User::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => Hash::make('123456'),
            'is_admin' => false,
        ]);
        
        $this->command->info('Test user created successfully!');
        $this->command->info('Email: test@test.com');
        $this->command->info('Password: 123456');
        $this->command->info('is_admin: ' . ($testUser->is_admin ? 'true' : 'false'));
    }
}
