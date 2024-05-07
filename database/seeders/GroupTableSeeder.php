<?php

namespace Database\Seeders;

use App\Models\User;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = (User::first())->id;
        if ($user) {
            DB::table('groups')->insert([
                [
                    'name'      => 'APARELHOS DE MEDIÇÃO E ORIENTAÇÃO',
                    'code'     => '101',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
                [
                    'name'      => 'APARELHOS E EQUIPAMENTOS DE COMUNICAÇÃO',
                    'code'     => '102',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
                [
                    'name'      => 'EQUIPAM/UTENSÍLIOS MÉDICOS, ODONTO, LAB E HOSP',
                    'code'     => '103',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
                [
                    'name'      => 'APARELHO E EQUIPAMENTO P/ESPORTES E DIVERSÕES',
                    'code'     => '104',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
                [
                    'name'      => 'EQUIPAMENTO DE PROTEÇÃO, SEGURANÇA E SOCORRO',
                    'code'     => '105',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
                [
                    'name'      => 'MÁQUINAS E EQUIPAMENTOS INDUSTRIAIS',
                    'code'     => '106',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
                [
                    'name'      => 'MÁQUINAS E EQUIPAMENTOS ENERGÉTICOS',
                    'code'     => '107',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
                [
                    'name'      => 'MÁQUINAS E UTENSÍLIOS AGROPECUÁRIO/RODOVIÁRIO',
                    'code'     => '120',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
                [
                    'name'      => 'MÁQUINAS, UTENSÍLIOS E EQUIPAMENTOS DIVERSOS',
                    'code'     => '125',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
                [
                    'name'      => 'MÁQUINAS E FERRAMENTAS DE OFICINA',
                    'code'     => '199',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
                [
                    'name'      => 'EQUIPAMENTOS DE TECNOLOGIA E COMUNICAÇÃO/TIC',
                    'code'     => '201',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
                [
                    'name'      => 'APARELHOS E UTENSÍLIOS DOMÉSTICOS',
                    'code'     => '301',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
                [
                    'name'      => 'MOBILIÁRIO EM GERAL',
                    'code'     => '303',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
                [
                    'name'      => 'INSTRUMENTOS MUSICAIS E ARTíSTICOS',
                    'code'     => '404',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
                [
                    'name'      => 'EQUIPAMENTOS PARA ÁUDIO, VÍDEO E FOTO',
                    'code'     => '405',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
                [
                    'name'      => 'VEÍCULOS DE TRAÇÃO MECÂNICA',
                    'code'     => '503',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
                [
                    'name'      => 'SEMOVENTES',
                    'code'     => '1000',
                    'user_id'  =>  $user,
                    'created_at' => new DateTime('now')
                ],
            ]);
        }
    }
}
