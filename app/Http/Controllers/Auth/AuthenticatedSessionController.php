<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
    $request->validate([
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ]);

    // المصادقة
    $request->authenticate();

    // ابحث عن المستخدم باستخدام البريد الإلكتروني
    $user = User::where('email', $request->email)->first();

    // التحقق من وجود المستخدم
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $user->tokens()->delete();
    Auth::login($user);
    // أنشئ توكن جديد
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

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        $user = $request->user();

        // قم بإلغاء التوكنات الحالية للمستخدم
        $user->tokens()->delete();

        return response()->noContent();
    }
}
