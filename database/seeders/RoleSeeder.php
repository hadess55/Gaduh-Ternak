<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin','farmer','mediator','legal','dinas'] as $r) {
            Role::findOrCreate($r);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Administrator','password'=>bcrypt('admin123')]
        );
        $admin->assignRole('admin');
    }
}
