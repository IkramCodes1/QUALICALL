<?php

namespace App\Repositories\User;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

//gestion des utilisateurs (CRUD + user connecté + permissions)

class UserRepository
{

    public function user()
    {
        $user = Auth::user(); // On récupère l’utilisateur actuellement connecté
        \Log::info($user);

        if (!$user) {
            return response()->json(['user' => null]);// Si aucun utilisateur → renvoie user: null
        }

        $user = $this->getUserWithRelations($user->id); // charge l’utilisateur avec ses relations (role, societe, permissions
        \Log::info($user);

        $userData = [ //  On construit un objet avec les informations utiles de l’utilisateur
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role->name ?? '',
            'societe' => $user->societe->name ?? '',
            'langue' => $user->langue,
            'permissions' => $this->getPermissions($user),
        ];

        return response()->json(['user' => $userData]); // Retourne ces données en JSON.
    }

    protected function getUserWithRelations($userId) // On charge les relations pour éviter plusieurs requêtes SQL (eager loading)
    {
        return User::with(['role', 'societe', 'role.rolePermissions.permission'])->find($userId);
    }

    protected function getPermissions($user)
    {
        $permissions = [];
        if ($user->role && $user->role->rolePermissions) {// On vérifie que l’utilisateur a bien un rôle et que ce rôle possède des rolePermissions (les permissions liées au rôle)
            foreach ($user->role->rolePermissions as $rp) {
                if ($rp->permission) {
                    $permissions[] = [
                        'id' => $rp->permission->id,
                        'name' => $rp->permission->name,
                    ];
                }
            }
        }
        return $permissions;
    }

    public function getUsers()
    {
        $user = Auth::user();

        $users = User::where('societe_id', $user->societe_id)->with('role')->get();

        $result = $users->map(function ($u) {
            return [
                'id' => encrypt($u->id),
                'name' => $u->name,
                'email' => $u->email,
                'role_id' => $u->role_id,
                'role' => $u->role->name ?? '',
            ];
        });

        return response()->json(['users' => $result]);
    }

    public function addUser($data)
    {
        // Validation
        $validator = \Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        $authUser = Auth::user();

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $generatedPassword = \Str::random(12);
        $user->password = bcrypt($generatedPassword);

        // Set langue and societe_id from the authenticated user
        $user->langue = $authUser->langue;
        $user->societe_id = $authUser->societe_id;

        $user->is_active = true;
        $user->role_id = $data['role_id'];
        $user->must_change_pwd = true;
        $user->save();

        \Log::info($generatedPassword);

        $msg = __("added");
        return response()->json([
            'message' => $msg
        ], 200);
    }

    public function deleteUser($data)
    {
        if (!isset($data['id'])) {
            return response()->json([
                'message' => __("User id is required")
            ], 400);
        }
        try {
            $userId = decrypt($data['id']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __("Invalid user id")
            ], 400);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => __("User not found")
            ], 404);
        }

        if ($user->is_owner) {
            return response()->json([
                'message' => __("Impossible to delete the owner.")
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => __("deleted")
        ]);
    }

    public function updateUser($data)
    {
        if (!isset($data['id'])) {
            return response()->json([
                'message' => __("User id is required")
            ], 400);
        }
        try {
            $userId = decrypt($data['id']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __("Invalid user id")
            ], 400);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => __("User not found")
            ], 404);
        }

        if ($user->is_owner) {
            return response()->json([
                'message' => __("Impossible to modify the owner.")
            ], 403);
        }

        if (isset($data['name'])) $user->name = $data['name'];
        if (isset($data['email'])) $user->email = $data['email'];
        if (isset($data['role_id'])) $user->role_id = $data['role_id'];
        if (isset($data['is_active'])) $user->is_active = $data['is_active'];

        $user->save();

        return response()->json([
            'message' => __("updated")
        ]);
    }

}
