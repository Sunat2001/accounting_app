<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Invoke метода для регистрации нового юзера
     *
     * @param RegisterRequest $request
     * @return AuthResource
     */
    public function __invoke(RegisterRequest $request): AuthResource
    {
        $user = User::query()->create($request->validated());

        $token = Auth::attempt($request->only(['email', 'password']));

        return new AuthResource($user, $token);
    }
}
