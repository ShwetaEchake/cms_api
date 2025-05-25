<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
      public function run()
       {
           User::create([
               'name' => 'Admin User',
               'email' => 'admin@gmail.com',
               'password' => Hash::make('password'),
               'role' => 'admin',
           ]);
           User::create([
               'name' => 'Author User',
               'email' => 'author@gmail.com',
               'password' => Hash::make('password'),
               'role' => 'author',
           ]);


       }
}
