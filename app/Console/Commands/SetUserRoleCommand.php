<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SetUserRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:set-role {--email=} {--role=user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set user role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $role = $this->option('role');

        if (!$email) {
            $this->error('Email is required!');
            return;
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return;
        }

        $user->update(['role' => $role]);

        $this->info("Role updated successfully for {$user->name} ({$user->email})");
        $this->line("New role: {$role}");
    }
}