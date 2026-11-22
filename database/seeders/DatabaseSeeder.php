<?php

namespace Database\Seeders;


use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
<<<<<<< HEAD
        $this->call([
            RoleSeeder::class,
            SupplierDemoSeeder::class,
        ]);
=======
        $this->call(RoleSeeder::class);
>>>>>>> f716e265f6a8063e242afe9c946d7b21e5255ca8
    }
}
