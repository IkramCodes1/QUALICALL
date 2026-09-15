<?php

namespace App\Repositories\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    /**🎯 Qu’est-ce que le Repository dans Laravel ?
            Le Repository est un design pattern qui sert à séparer la logique d’accès aux données (base de données) du reste de l’application.
        👉 Il se place entre :
            le Controller et le Model (Eloquent)
        💡 Pourquoi utiliser un Repository ?
            1. ✔️ Organisation du code
                Au lieu de mettre les requêtes SQL ou Eloquent dans le Controller ❌
                On les met dans le Repository ✅
            2. ✔️ Séparation des responsabilités
                Controller → gère les requêtes HTTP (request/response)
                Repository → gère les données (DB logic)
            3. ✔️ Flexibilité
                Tu peux changer la manière d’accéder aux données (DB, API, etc.)
s               ans modifier les Controllers 👌

     */
    public function register($request)
{
    $data = $request->only(['username', 'email', 'password']);

    // ✅ validation
    $validator = \Validator::make($data, [
        'username' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => $validator->errors()
        ], 422);
    }

    // ✅ création user
    $user = new User();
    $user->name = $data['username'];
    $user->email = $data['email'];
    $user->password = bcrypt($data['password']);

    // default values
    $user->is_active = true;
    $user->must_change_pwd = false;

    $user->save();

    // ✅ création token (باش يدخل مباشرة)
    $token = $user->createToken('api_token')->plainTextToken;

    return response()->json([
        'message' => 'User created successfully',
        'token' => $token
    ], 201);
}

    public function login($request)
    {
        $data = $request->only(['email', 'password']);

        $validator = \Validator::make($data, [
            'email' =>  'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422); //Si la validation échoue, on renvoie une erreur 422
        }

        $user = User::where('email', $data['email'])->first();//On cherche l’utilisateur par son email.
        if (!$user) {
            return response()->json(['message' => __("User not found")], 404);
        }

        // Gestion du blocage
        if ($user->is_active != 1) { //Vérifie si l’utilisateur est inactif
            if ($user->block_count >= 3) { // Si l’utilisateur a déjà été bloqué 3 fois ou plus, on considère qu’il est bloqué définitivement.
                return response()->json([
                    'message' => __("block_unlimited")
                ], 403);
            } else {
                if ($user->date_block) {
                    $dateBlock = $user->date_block instanceof \Carbon\Carbon
                        ? $user->date_block
                        : \Carbon\Carbon::parse($user->date_block);
                    $now = \Carbon\Carbon::now();

                    if ($dateBlock->diffInMinutes($now) < 15) {
                        // Toujours dans la période de blocage temporaire
                        return response()->json([
                            'message' => __("block")
                        ], 403);
                    } else {
                        // Déblocage après 15 minutes
                        $user->is_active = 1;
                        $user->date_block = null;
                        $user->failed_attempts = 0;
                        $user->last_failed_at = null;
                        $user->save();
                    }
                } else {
                    // Pas de date de blocage, mais inactif : on bloque
                    return response()->json([
                        'message' => __("block")
                    ], 403);
                }
            }
        }

        // Vérification du mot de passe
        if (!Hash::check($data['password'], $user->password)) {
            $now = \Carbon\Carbon::now();

            $last_failed_at = $user->last_failed_at
                ? ($user->last_failed_at instanceof \Carbon\Carbon
                    ? $user->last_failed_at
                    : \Carbon\Carbon::parse($user->last_failed_at))
                : $now;

            // Si la dernière tentative date de plus de 5 minutes, on reset à 1, sinon on incrémente
            if ($last_failed_at->diffInMinutes($now) > 5) {
                $user->failed_attempts = 1;
            } else {
                $user->failed_attempts += 1;
            }
            $user->last_failed_at = $now;

            // Blocage après 5 tentatives
            if ($user->failed_attempts >= 5) {
                $user->is_active = 0;
                $user->date_block = $now;
                $user->block_count = $user->block_count + 1;
            }

            $user->save();

            if ($user->is_active == 0) {
                return response()->json(['message' => __("block")], 403);
            } else {
                return response()->json(['message' => __("Invalid credentials")], 401);
            }
        }

        // Reset des tentatives après succès
        $user->failed_attempts = 0;
        $user->last_failed_at = null;
        $user->block_count = 0;
        $user->date_block = null;
        $user->save();

        $token = $user->createToken('api_token')->plainTextToken;

        return $token;
    }
}
