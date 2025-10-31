<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LembagaPaud;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $lembaga1 = LembagaPaud::where('nama_lembaga', 'PAUD Cemerlang Labuha')->first();
        $lembaga2 = LembagaPaud::where('nama_lembaga', 'PAUD Harapan Amasing')->first();

        // Super admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@paud-halsel.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'status' => 1,
        ]);

        // User Lembaga 1
        User::create([
            'name' => 'User Labuha',
            'email' => 'labuha@paud.com',
            'password' => Hash::make('password'),
            'role' => 'lembaga',
            'lembaga_id' => $lembaga1->id,
            'status' => 1,
        ]);

        // User Lembaga 2
        User::create([
            'name' => 'User Amasing',
            'email' => 'amasing@paud.com',
            'password' => Hash::make('password'),
            'role' => 'lembaga',
            'lembaga_id' => $lembaga2->id,
            'status' => 1,
        ]);
    }
}
