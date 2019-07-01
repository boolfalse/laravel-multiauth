<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run()
    {
        foreach (config('project.admin.roles') as $role) {
            Role::firstOrCreate([
                'guard_name' => 'admin',
                'name' => $role
            ]);
        };

        $admins = [
            [
                'role' => 'administrator',
                'name' => 'Admin',
                'email' => 'administrator@gmail.com',
                'password' => 'secret',
            ],
            [
                'role' => 'moderator',
                'name' => 'Moderator',
                'email' => 'moderator@gmail.com',
                'password' => 'secret',
            ],
            [
                'role' => 'manager',
                'name' => 'Manager',
                'email' => 'manager@gmail.com',
                'password' => 'secret',
            ],
        ];

        foreach ($admins as $admin) {
            $exist = Admin::where('email', $admin['email'])->first();
            if(empty($exist)){
                $super_admin = Admin::firstOrCreate([
                    'name' => $admin['name'],
                    'email' => $admin['email'],
                    'password' => bcrypt($admin['password']),
                ]);
                $super_admin->assignRole($admin['role']);
            }
        }
    }
}