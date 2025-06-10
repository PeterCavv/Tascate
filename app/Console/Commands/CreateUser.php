<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use \App\Enums\Role as UseRole;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create
        {--name= : The name of the user}
        {--email= : The email of the user}
        {--password= : The password for the user}
        {--role= : The role to assign to the user (Admin, Owner, Manager, Employee, Tasca)}';

    // Example usage:
    // php artisan user:create --name=test --email=test@gmail.com --password=12345678 --role=Tasca
    // php artisan user:create

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user with a specific role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->option('name') ?? $this->ask('What is the user name?');
        $email = $this->option('email') ?? $this->ask('What is the user email?');
        $password = $this->option('password') ?? $this->secret('What is the user password?');

        $roleOptions = array_map(fn(UseRole $r) => $r->value, UseRole::cases());

        $role = $this->option('role')
            ?? $this->choice(
            'What role should the user have?',
            $roleOptions,
            UseRole::CUSTOMER->value
        );

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:'.implode(',', $roleOptions)],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        // Check if role exists
        if (!Role::where('name', $role)->exists()) {
            $this->error("Role '{$role}' does not exist. Please run the database seeders first.");
            return 1;
        }

        // If creating an Admin, show warning
        if ($role === UseRole::ADMIN->value) {
            $this->warn('You are creating an Admin user. This user will have full system access.');
            if (!$this->confirm('Do you wish to continue?')) {
                return 0;
            }
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($role);

        $this->info('User created successfully!');
        $this->table(
            ['Name', 'Email', 'Role'],
            [[$user->name, $user->email, $role]]
        );

        return 0;
    }
}
