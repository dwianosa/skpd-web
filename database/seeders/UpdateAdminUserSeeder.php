<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing admin user
        $adminUser = User::where('email', 'admin@admin.com')->first();
        
        if ($adminUser) {
            $adminUser->update(['is_admin' => true]);
            $this->command->info('Admin user updated successfully!');
            $this->command->info('Email: ' . $adminUser->email);
            $this->command->info('is_admin: ' . ($adminUser->is_admin ? 'true' : 'false'));
        } else {
            // Create new admin user if not exists
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]);
            $this->command->info('New admin user created successfully!');
            $this->command->info('Email: admin@admin.com');
            $this->command->info('Password: password');
        }
    }
}
