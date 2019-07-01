<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::firstOrCreate([
            'name' => config('project.seed.dev_name'),
            'email' => config('project.seed.dev_email'),
            'password' => bcrypt(config('project.seed.dev_password')),
        ]);
    }
}
