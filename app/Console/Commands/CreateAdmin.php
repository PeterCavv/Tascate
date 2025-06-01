<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {--name= : The name of the admin user} {--email= : The email of the admin user} {--password= : The password for the admin user}';

    // Example usage:
    // php artisan admin:create --name=test --email=test@gmail.com --password=12345678
    // php artisan admin:create

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->option('name') ?? $this->ask('What is the admin name?');
        $email = $this->option('email') ?? $this->ask('What is the admin email?');
        $password = $this->option('password') ?? $this->secret('What is the admin password?');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        if (User::role(\App\Enums\Role::ADMIN->value)->exists()) {
            $this->warn('An admin user already exists. Do you want to create another one?');
            if (!$this->confirm('Do you wish to continue?')) {
                return 0;
            }
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'role' => \App\Enums\Role::ADMIN->value,
        ]);

        $user->assignRole( \App\Enums\Role::ADMIN->value);

        $this->info('Admin user created successfully!');
        $this->table(
            ['Name', 'Email'],
            [[$user->name, $user->email]]
        );

        return 0;
    }
}
