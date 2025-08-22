<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FixAdminLoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua user yang ada
        User::truncate();
        
        // Buat admin user baru
        User::create([
            'name' => 'Administrator',
            'email' => 'Admin@gmail.com',
            'password' => Hash::make('123456'),
            'is_admin' => true,
        ]);
        
        echo "Admin user berhasil dibuat:\n";
        echo "Email: Admin@gmail.com\n";
        echo "Password: 123456\n";
        echo "is_admin: true\n";
    }
}








