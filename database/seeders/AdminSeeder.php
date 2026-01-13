<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah admin sudah ada
        $adminExists = User::where('email', 'admin@example.com')->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);

            echo "✓ Admin account created successfully!\n";
            echo "Email: admin@example.com\n";
            echo "Password: password123\n";
        } else {
            echo "✓ Admin account already exists!\n";
            echo "Email: admin@example.com\n";
            echo "Password: password123\n";
        }
    }
}
