<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('actions')->insert([
            'action_id' => 1,
            'action' => 'Item',
        ]);
        DB::table('actions')->insert([
            'action_id' => 2,
            'action' => 'Delivery',
        ]);
        DB::table('actions')->insert([
            'action_id' => 3,
            'action' => 'RIS',
        ]);
        DB::table('actions')->insert([
            'action_id' => 4,
            'action' => 'Office',
        ]);
        DB::table('actions')->insert([
            'action_id' => 5,
            'action' => 'Unit',
        ]);
        DB::table('actions')->insert([
            'action_id' => 6,
            'action' => 'Classification',
        ]);
        DB::table('actions')->insert([
            'action_id' => 7,
            'action' => 'Article',
        ]);
        DB::table('actions')->insert([
            'action_id' => 8,
            'action' => 'Supplier',
        ]);
        DB::table('actions')->insert([
            'action_id' => 9,
            'action' => 'DBM Price',
        ]);
        DB::table('actions')->insert([
            'action_id' => 10,
            'action' => 'Signatory',
        ]);
        DB::table('actions')->insert([
            'action_id' => 11,
            'action' => 'Report',
        ]);
        
    }
}
