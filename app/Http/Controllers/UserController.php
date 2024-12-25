<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserRegisterResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function register(UserRegisterRequest $request): UserRegisterResource
    {
        $data = $request->validated();

        if(User::where('email', $data['email'])->exists()) {
            throw new \Exception('User already exists');
        }

        $user = new User($data);
        $user->password = bcrypt($data['password']);
        $user->save();

        return new UserRegisterResource($user);
    }

    public function login(UserLoginRequest $request): UserRegisterResource {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new \Exception('Invalid credentials');
        }

        $user->token = Str::uuid()->toString();

        return new UserRegisterResource($user);
    }

    public function get(Request $request): UserRegisterResource {
        $user = Auth::user();
        return new UserRegisterResource($user);
    }

    public function update(UserUpdateRequest $request): UserRegisterResource {
        $data = $request->validated();
        $user = Auth::user();

        if(isset($data['email'])) {
            $user->email = $data['email']; 
        }

        if(isset($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        $user->update;

        return new UserRegisterResource($user);
    
    }

    public function logout(Request $request) {
        $user = Auth::user();
        $user->token = null;
        $user->update;
        return new UserRegisterResource($user);
    }
}
