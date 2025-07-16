<?php

namespace App\Domains\Auth\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
            'is_verified' => $data['role'] === 'seller' ? false : true,
        ]);
    }

    public function login(array $data): User
    {
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The credentials are incorrect.'],
            ]);
        }
        if ($user->role === 'seller' && !$user->is_verified) {
            throw ValidationException::withMessages([
                'email' => ['Seller account not verified.'],
            ]);
        }
        $user->tokens()->delete();
        return $user;
    }
}
