<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    protected $signature = 'users:create {name} {email} {password?}';

    /**
     * @throws \Throwable
     */
    public function handle(): int
    {
        $name     = $this->argument('name');
        $email    = $this->argument('email');
        $password = $this->argument('password') ?? fake()->password(30);

        if (User::where('email', $email)->exists()) {
            $this->error('User with this email already exists.');

            return 1;
        }

        User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("User created successfully. Password: $password");

        return 0;
    }
}
