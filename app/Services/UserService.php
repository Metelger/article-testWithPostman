<?php

namespace App\Services;

use App\Models\User;
use App\Http\Resources\UserResource;

class UserService
{
    public function createUser($data)
    {
        $phone = $data['phone'];
        if (preg_match('/[a-z]/i', $phone)) {
            $errorMessage = [
                'erro' => true,
                'message' => 'O campo "phone" não deve conter letras!'
            ];

            return response()->json($errorMessage, 201);
        }

        $formatPhone = preg_replace('/(\d{2})(\d+)(\d{4})/','($1) $2-$3', $phone);
        $data['phone'] = $formatPhone;
        
        $user = User::create($data);

        return response()->json($user, 201);
    }

    public function getUser()
    {
        // Lógica aqui
    }

    public function listUsers()
    {
        // Lógica aqui
    }

    public function deleteUser()
    {
        // Lógica aqui
    }

    public function deleteManyUsers()
    {
        // Lógica aqui
    }
}
