<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // List semua user
    public function index()
    {
        $users = User::all();

        return new JsonResponse([
            'message' => 'List of all users',
            'users' => $users
        ]);
    }

    // Login API (email & password)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Email atau password salah',
                'data'    => null
            ], 401);
        }

        // Generate API token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login successful',
            'data'    => [
                'user' => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                ],
                'token' => $token
            ]
        ]);
    }

    // Login API via Google OAuth
    public function loginWithGoogle(Request $request)
    {

        $idToken = $request->input('token');

        try {
            // Ambil user dari Google menggunakan token yang dikirim frontend
            $googleUser = Socialite::driver('google')->stateless()->userFromToken($idToken);

            $user = User::where('email', $googleUser->getEmail())->first();
            if (!$user) {
                // Cari atau buat user
                $user = User::firstOrCreate(
                    ['email' => $googleUser->getEmail()],
                    [
                        'name' => $googleUser->getName(),
                        'google_id' => $googleUser->getId(), 
                        'google_token' => $googleUser->token,
                        'password' => bcrypt(Str::random(16)), // password acak
                        'role' => 0 // default role, ubah sesuai kebutuhan
                    ]
                );
            }

            // Generate API token
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'status'  => true,
                'message' => 'Login with Google successful',
                'data'    => [
                    'user' => [
                        'id'    => $user->id,
                        'name'  => $user->name,
                        'email' => $user->email,
                    ],
                    'token' => $token
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal login dengan Google: ' . $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }



    // Logout API
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil logout']);
    }
}
 