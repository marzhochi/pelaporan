<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $credentials = request(['email', 'password']);
        if (!auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Username atau password salah',
                'status' => 'error'
            ], 422);
        }

        $user = Petugas::where('email', $request->email)->first();
        $authToken = $user->createToken('auth-token')->plainTextToken;

        $data['id'] = $user->id;
        $data['nip'] = $user->nip;
        $data['nama_lengkap'] = $user->nama_lengkap;
        $data['email'] = $user->email;
        $data['telp'] = $user->no_telp ?? '-';
        $data['role'] = $user->role;
        $data['avatar'] = isset($user->media->original_url) ? $user->media->original_url : 'https://dishub.online/images/no_avatar.jpg';

        return response()->json([
            'status' => 'success',
            'access_token' => $authToken,
            'data' => $data,
        ]);
    }
}
