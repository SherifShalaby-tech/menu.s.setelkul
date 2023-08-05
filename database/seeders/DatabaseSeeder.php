<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user_data = [
            'name' => 'superadmin',
            'email' => 'admin@sherifshalaby.tech',
            'password' => Hash::make('123456'),
            'is_superadmin' => 1,
            'is_default' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        $user = User::create($user_data);






        //call the permission and currencies seeder
        $this->call([
            PermissionTableSeeder::class,
            CurrenciesTableSeeder::class,
            SystemSeeder::class,
            StoreSeeder::class,
        ]);
    }
}
