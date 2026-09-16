<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class EnsureSuperAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'super admin', 'guard_name' => 'web']);

        $user = User::find(1);
        if ($user && ! $user->hasRole('super admin')) {
            $user->assignRole($role);
        }
    }
}
