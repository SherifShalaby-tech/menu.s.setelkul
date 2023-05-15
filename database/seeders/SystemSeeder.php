<?php

namespace Database\Seeders;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(env('ENABLE_POS_SYNC')){
            DB::table('systems')->insert([
                'key' => 'language',
                'value' => 'ar', 'date_and_time' => Carbon::now(), 'created_by' => 1
            ]);
            DB::table('systems')->insert([
                'key' => 'currency',
                'value' => 35, 'date_and_time' => Carbon::now(), 'created_by' => 1
            ]);
        }
    }
}
