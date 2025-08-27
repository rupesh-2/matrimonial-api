<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:list';

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
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->info('No users found in the database.');
            return;
        }
        
        $this->info('Users in database:');
        $this->info('');
        
        foreach ($users as $user) {
            $this->line("ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Gender: {$user->gender}");
        }
        
        $this->info('');
        $this->info('Total users: ' . $users->count());
    }
}
