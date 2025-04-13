<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Auth\UserLoggedInResource;
use App\Services\V1\Auth\LoginService;

class LoginController extends Controller
{
    public function login(LoginService $service): UserLoggedInResource
    {
        $dto = $service->login();

        return new UserLoggedInResource($dto);
    }
}
