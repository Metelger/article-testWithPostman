<?php

namespace App\Services;

use App\Models\User;
use App\Http\Resources\UsersResource;

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

        return new UsersResource($user);
    }

    public function getUser($id)
    {
        $user = User::find($id);
        if ($user === null) {
            $message = [
                'error' => false,
                'description' => 'Nenhum usuário cadastrado na base possui esse identificador'
            ];
            return response()->json($message, 200);
        }

        return new UsersResource($user);
    }

    public function listUsers()
    {
        $getAllUsers = User::all();
        if ($getAllUsers->isEmpty()) {
            $message = [
                'error' => false,
                'description' => 'Nenhum usuário cadastrado na base'
            ];
            return response($message, 200);
        }
        return response($getAllUsers);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user === null) {
            $message = [
                'error' => false,
                'message' => 'Deleção não realizada. Nenhum usuário encontrado com esse id'
            ];

            return response($message, 200);
        }
        $user->delete();
        $message = [
            'error' => false,
            'message' => 'Usuário deletado com sucesso'
        ];

        return response($message, 200);
    }
}
