<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    protected $contactService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return $this->userService->listUsers();
    }

    public function show($id)
    {
        return $this->userService->getUser($id);
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();

        return $this->userService->createUser($data);
    }

    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        return $user;
    }

    public function destroy($id)
    {
        return $this->userService->deleteUser($id);
    }
}
