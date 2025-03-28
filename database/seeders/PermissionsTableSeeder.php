<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        DB::table('permissions')->insert([
            /** ACL  1 to 11 */
            [
                'name' => 'Acessar ACL',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Listar Permissões',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Criar Permissões',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Editar Permissões',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Excluir Permissões',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Listar Perfis',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Criar Perfis',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Editar Perfis',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Excluir Perfis',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Sincronizar Perfis',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Atribuir Perfis',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],

            /** Users 12 to 17 */
            [
                'name' => 'Acessar Usuários',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Listar Usuários',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Criar Usuários',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Editar Usuário',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Editar Usuários',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Excluir Usuários',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            /** Departments  18 to 22 */
            [
                'name' => 'Acessar Setores',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Listar Setores',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Criar Setores',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Editar Setores',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Excluir Setores',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            /** Groups  23 to 27 */
            [
                'name' => 'Acessar Grupos',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Listar Grupos',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Criar Grupos',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Editar Grupos',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Excluir Grupos',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            /** Materials  28 to 32 */
            [
                'name' => 'Acessar Materiais',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Listar Materiais',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Criar Materiais',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Editar Materiais',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
            [
                'name' => 'Excluir Materiais',
                'guard_name' => 'web',
                'created_at' => new DateTime('now'),
            ],
        ]);
    }
}
