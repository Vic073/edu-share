<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'email' => 'admin@edushare.mw',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'kyc_status' => 'verified',
            'subscription_tier' => 'premium',
        ]);
    }
}
