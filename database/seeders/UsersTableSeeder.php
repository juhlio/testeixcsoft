<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'João Silva',
                'email' => 'joao.silva@example.com',
                'password' => Hash::make('senha123'),
                'documento' => '123.456.789-00',
                'tipo' => 'fisica',
            ],
            [
                'name' => 'Empresa teste',
                'email' => 'empresateste@example.com',
                'password' => Hash::make('senha123'),
                'documento' => '12.345.678/0001-99',
                'tipo' => 'juridica',
            ],
            // Adicione mais usuários se necessário
        ]);
    }
}
