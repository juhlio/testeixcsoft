<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('wallets')->insert([
            [
                'user_id' => '1',
                'balance' => 1000,

            ],
            [
                'user_id' => '2',
                'balance' => 1000,

            ],

        ]);
    }
}
