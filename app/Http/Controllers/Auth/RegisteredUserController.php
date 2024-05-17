<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'states' => $request->states,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // إنشاء Token
        $token = $user->createToken('auth_token')->plainTextToken;

        if ($user->states == 'Pharmacy') {
                return response()->json([
                'user' => $user,
                'token' => $token,
                ]);
        }elseif ($user->states == 'Admin') {
            return response()->json([
                'user' => $user,
                'token' => $token,
                ]);
        }else {
        return response()->json([
            'user' => $user,
            'token' => $token,
            ]);
        }
        
    }
}
