<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['sustainability_officer', 'pemasok', 'management', 'eksportir_importir'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $akun = [
            ['Officer Demo',    'officer@sawit.test',    'sustainability_officer'],
            ['Management Demo', 'management@sawit.test', 'management'],
            ['Eksportir Demo',  'eksportir@sawit.test',  'eksportir_importir'],
            ['Pemasok Demo',    'pemasok@sawit.test',    'pemasok'],
        ];

        foreach ($akun as [$nama, $email, $role]) {
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $nama, 'password' => Hash::make('password123'), 'email_verified_at' => now()]
            );
            $user->syncRoles($role);
        }
    }
}
