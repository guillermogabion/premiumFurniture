<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('add-admin-and-user', function () {
    $adminData = [
        'fullname' => "Test fullname admin",
        'gender' => "male",
        'contact' => "1231233123",
        'address' => "test address",
        'email' => "admin123@test.com",
        'password' => "Password01!",
        'role' => 'admin', // Specify role for admin
    ];
    $userData = [
        'fullname' => "Test fullname user",
        'gender' => "male",
        'contact' => "1231233123",
        'address' => "test address",
        'email' => "client123@test.com",
        'password' => "Password01!",
        'role' => 'client', // Specify role for user
    ];

    $users = [$adminData, $userData];

    foreach ($users as $data) {
        if (User::where('email', $data['email'])->exists()) {
            $this->error("User with email '{$data['email']}' already exists. Skipping.");
            continue;
        }

        // Create the user
        $user = new User();
        $user->fullname = $data['fullname'];
        $user->gender = $data['gender'];
        $user->contact = $data['contact'];
        $user->address = $data['address'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role = $data['role']; // Assuming your User model has a 'role' attribute

        if ($user->save()) {
            $this->info("{$data['email']} added successfully.");
        } else {
            $this->error("Failed to add {$data['email']}.");
        }
    }
})->purpose('Add Admin and User');
