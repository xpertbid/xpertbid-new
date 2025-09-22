<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ListUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all users in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all(['id', 'name', 'email', 'status', 'role']);

        if ($users->isEmpty()) {
            $this->info('No users found in the database.');
            return;
        }

        $this->info('Users in database:');
        $this->line('');

        $headers = ['ID', 'Name', 'Email', 'Role', 'Status'];
        $rows = [];

        foreach ($users as $user) {
            $rows[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->role ?? 'user',
                $user->status ?? 'active'
            ];
        }

        $this->table($headers, $rows);
    }
}