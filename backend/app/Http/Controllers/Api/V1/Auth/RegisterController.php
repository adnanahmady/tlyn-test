<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Auth\UserRegisteredResource;
use App\Services\V1\Auth\RegisterService;

class RegisterController extends Controller
{
    public function register(RegisterService $service): UserRegisteredResource
    {
        $user = $service->register();

        return new UserRegisteredResource($user);
    }
}
