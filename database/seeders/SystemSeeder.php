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
                ['value' => 35]
            );
            System::updateOrCreate(
                ['key' => 'currency'],
                ['value' => 'ar']
            );
        } 
    }
}
