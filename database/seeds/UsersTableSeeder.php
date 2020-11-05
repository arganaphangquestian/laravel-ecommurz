<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->delete();
        User::create(['name' => 'Seller', 'email' => 'seller@mail.com', 'password' => Hash::make('seller123')]);
        User::create(['name' => 'Buyer', 'email' => 'buyer@mail.com', 'password' => Hash::make('buyer123')]);
    }
}
