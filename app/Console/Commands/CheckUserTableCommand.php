<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckUserTableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:user-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the structure of the users table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Users table structure:');
        
        $columns = Schema::getColumnListing('users');
        foreach ($columns as $column) {
            $this->line("- {$column}");
        }

        $this->line('');
        $this->info('Sample user data:');
        
        $user = DB::table('users')->first();
        if ($user) {
            foreach ((array)$user as $key => $value) {
                $this->line("{$key}: " . (is_null($value) ? 'NULL' : $value));
            }
        }
    }
}