<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class FixUserPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fix-passwords {--password=password : The password to set for all users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix user passwords by re-hashing them properly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $password = $this->option('password');
        
        $this->info('Updating passwords for all users...');
        
        $users = User::all();
        
        foreach ($users as $user) {
            // Set password directly - Laravel will hash it automatically due to 'hashed' cast
            $user->password = $password;
            $user->save();
            
            $this->info("Updated password for: {$user->email}");
        }
        
        $this->info("Successfully updated passwords for {$users->count()} users.");
        $this->info("All users now have password: {$password}");
        
        return Command::SUCCESS;
    }
}
