<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin','farmer', 'perangkat_desa'] as $r) {
            Role::findOrCreate($r);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Administrator','password'=>bcrypt('admin123')]
        );
        $admin->assignRole('admin');

        $user = User::firstOrCreate(
        ['email'=>'desa@gmail.coom'],
        ['name'=>'Perangkat Desa','password'=>bcrypt('desa123')]
        );
        $user->assignRole('perangkat_desa');
    }
}
