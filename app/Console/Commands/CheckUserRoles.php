<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check-roles {email? : The email of the user to check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the roles assigned to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        if ($email) {
            $user = User::where('email', $email)->first();
        } else {
            // List all users since we can't get the authenticated user in CLI
            $this->info('Here are all users:');
            $users = User::all(['id', 'name', 'email']);
            $this->table(['ID', 'Name', 'Email'], $users->toArray());
            
            $email = $this->ask('Enter the email of the user to check:');
            $user = User::where('email', $email)->first();
        }
        
        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }
        
        $this->info("User: {$user->name} ({$user->email})");
        $this->info("User ID: {$user->id}");
        
        if (method_exists($user, 'getRoleNames')) {
            $roles = $user->getRoleNames();
            $this->info("Roles: " . ($roles->count() > 0 ? $roles->implode(', ') : 'No roles assigned'));
        } else {
            $this->error("The getRoleNames method is not available on the User model.");
        }
        
        // Check if user can perform specific actions
        $this->info("\nPermission checks for truck deployments:");
        $this->table(
            ['Action', 'Authorized?'],
            [
                ['viewAny TruckDeployment', $user->can('viewAny', \App\Models\TruckDeployment::class) ? 'Yes' : 'No'],
                ['create TruckDeployment', $user->can('create', \App\Models\TruckDeployment::class) ? 'Yes' : 'No'],
                ['open TruckDeployment', $user->can('open', \App\Models\TruckDeployment::class) ? 'Yes' : 'No'],
                ['close TruckDeployment', $user->can('close', \App\Models\TruckDeployment::class) ? 'Yes' : 'No'],
                ['cancel TruckDeployment', $user->can('cancel', \App\Models\TruckDeployment::class) ? 'Yes' : 'No'],
            ]
        );
        
        return 0;
    }
}
