<?php

namespace Database\Seeders;

use App\Models\System;
use Illuminate\Database\Seeder;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        
        $pos = System::getProperty('pos');
        if(!isset($pos)){
            System::updateOrCreate(
                ['key' => 'language'],
                ['value' => 'ar']
            );
            System::updateOrCreate(
                ['key' => 'currency'],
                ['value' => 35]
            );
        } 
    }
}
