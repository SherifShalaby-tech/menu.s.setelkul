<?php

namespace Database\Seeders;

use App\Models\System;
use Carbon\Carbon;
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
                    ['value' => 'ar', 'date_and_time' => Carbon::now(), 'created_by' => 1]
                );
                System::updateOrCreate(
                    ['key' => 'currency'],
                    ['value' => 35, 'date_and_time' => Carbon::now(), 'created_by' => 1]
                );
        } 
    }
}
