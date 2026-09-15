<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Repositories\User\UserRepository;

//Le contrôleur gère uniquement les requêtes HTTP

class UserController extends Controller
{

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository; //Ce constructeur permet d’injecter le UserRepository pour l’utiliser dans le contrôleur
    }

    public function user()
    {
        try {
            return $this->userRepository->user();//Cette méthode retourne l’utilisateur connecté.
        } catch (\Throwable $th) {
            Log::error($th);
            $msg = __("error");
            return response()->json(['message' => $msg], 500);
        }
    }

    public function get()
    {
        try {
            return $this->userRepository->getUsers();//Cette méthode récupère la liste des utilisateurs.
        } catch (\Throwable $th) {
            Log::error($th);
            $msg = __("error");
            return response()->json(['message' => $msg], 500);
        }
    }

    public function add(Request $request)
    {
        try {
            return $this->userRepository->addUser($request->all());//Cette méthode permet d’ajouter un nouvel utilisateur
        } catch (\Throwable $th) {
            Log::error($th);
            $msg = __("error");
            return response()->json(['message' => $msg], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            return $this->userRepository->deleteUser($request->all());
        } catch (\Throwable $th) {
            Log::error($th);
            $msg = __("error");
            return response()->json(['message' => $msg], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            return $this->userRepository->updateUser($request->all());
        } catch (\Throwable $th) {
            Log::error($th);
            $msg = __("error");
            return response()->json(['message' => $msg], 500);
        }
    }

}
