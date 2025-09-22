<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {--name=} {--email=} {--password=} {--role=user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->option('name') ?: $this->ask('Enter user name');
        $email = $this->option('email') ?: $this->ask('Enter user email');
        $password = $this->option('password') ?: $this->secret('Enter user password');
        $role = $this->option('role');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error('User with this email already exists!');
            return;
        }

        // Create the user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'status' => 'active',
            'role' => $role,
        ]);

        $this->info("User created successfully!");
        $this->line("Name: {$user->name}");
        $this->line("Email: {$user->email}");
        $this->line("Status: {$user->status}");
        $this->line("Role: {$user->role}");
    }
}