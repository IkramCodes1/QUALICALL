<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class InitialSocieteSeeder extends Seeder
{
    public function run()
    {
        // 1. Créer la société
        $societe = DB::table('societes')->updateOrInsert(
            ['id' => 1],
            ['name' => 'my societe'],
            ['created_at' => now()],
            ['updated_at' => now()]
        );

        // 2. Créer les rôles
        $roles = [
            'owner',
            'admin',
            'user',
        ];
        $roleIds = [];
        foreach ($roles as $roleName) {
            $roleIds[$roleName] = DB::table('roles')->insertGetId([
                'name' => $roleName,
                'societe_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Créer les permissions
        $permissions = [
            'user_view',
            'user_add',
            'user_delete',
            'user_update',
        ];
        $permissionIds = [];
        foreach ($permissions as $permName) {
            $permissionIds[$permName] = DB::table('permissions')->insertGetId([
                'name' => $permName,
                'description' => $permName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Associer toutes les permissions à chaque rôle
        foreach ($roleIds as $roleName => $roleId) {
            if ($roleName === 'owner') {
                foreach ($permissionIds as $permId) {
                    DB::table('permission_role')->insert([
                        'role_id' => $roleId,
                        'permission_id' => $permId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 5. Créer l'utilisateur
        $userId = DB::table('users')->insertGetId([
            'name' => 'Oussama Taabouz',
            'email' => 'oussamataabouzz@gmail.com',
            'password' => Hash::make('Ot123456'),
            'langue' => 'fr',
            'role_id' => $roleIds['owner'],
            'is_owner' => 1,
            'societe_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userId = DB::table('users')->insertGetId([
            'name' => 'ikram elabous',
            'email' => 'ikram@gmail.com',
            'password' => Hash::make('Ot123456'),
            'langue' => 'en',
            'role_id' => $roleIds['owner'],
            'is_owner' => 0,
            'societe_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 6. Ajouter la catégorie "ringover" pour la société id 1
        DB::table('categories')->insert([
            'name' => 'ringover',
            'societe_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
