<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAdminPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:reset-password {--email=admin@xpertbid.com} {--password=admin123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset admin password';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return;
        }

        $user->update([
            'password' => Hash::make($password)
        ]);

        $this->info("Password updated successfully for {$user->name} ({$user->email})");
        $this->line("New password: {$password}");
    }
}