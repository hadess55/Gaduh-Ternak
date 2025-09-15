<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $guard = config('auth.defaults.guard', 'web');

        foreach (['admin', 'perangkat desa', 'peternak'] as $r) {
            Role::findOrCreate($r, $guard);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Administrator','password'=>bcrypt('admin123')]
        );
        $admin->assignRole('admin');

        $user = User::firstOrCreate(
        ['email'=>'desa@gmail.com'],
        ['name'=>'Perangkat Desa','password'=>bcrypt('desa123')]
        );
        $user->assignRole('perangkat desa');
    }
}
