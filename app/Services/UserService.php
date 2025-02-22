<?php

namespace App\Services;

use App\Models\User;
use App\DTOs\UserDTO;

class UserService
{
    public function create(UserDTO $userDTO)
    {
        $user = new User();
        $user->name = $userDTO->name;
        $user->email = $userDTO->email;
        $user->password = bcrypt($userDTO->password);
        $user->tenant_id = app('tenant')->id;
        $user->save();

        return $user;
    }
} 