<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        DB::table('role_user')->truncate();
        $adminRole = Role::where('name', 'admin')->first();
        $customerRole = Role::where('name', 'customer')->first();
        $password = 'password';

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make($password)
        ]);
        $cust = User::create([
            'name' => 'Favour Okunola',
            'email' => 'favourokunola@gmail.com',
            'password' => Hash::make($password)
        ]);
        $admin->roles()->attach($adminRole);
        $cust->roles()->attach($customerRole);
    }
}
