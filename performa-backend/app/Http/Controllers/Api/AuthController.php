<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'fullname'  => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'nip'       => 'required|string|unique:users,nip',
            'password'  => 'required|string|min:8|confirmed',
            'pangkat'               => 'nullable|string|max:255',
            'jabatan'               => 'nullable|string|max:255',
            'kode_unit_organisasi'  => 'nullable|string|max:50',
            'parent_id'             => 'nullable|exists:users,id',
        ]);

        $user = User::create([
            'guid'      => Str::uuid()->toString(),
            'uuid'      => Str::uuid()->toString(),
            'name'      => $request->name,
            'fullname'  => $request->fullname,
            'email'     => $request->email,
            'fpid'      => $request->nip,
            'nip'       => $request->nip,
            'unit_kerja' => $request->unit_kerja,
            'pangkat'   => $request->pangkat,
            'jabatan'   => $request->jabatan,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'parent_id'            => $request->parent_id,
            'kode_unit_organisasi' => $request->kode_unit_organisasi,
        ]);

        $token = auth('api')->login($user);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => new UserResource($user),
            ]
        ], 201);
    }

    /**
     * Login user with JWT
     */
    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by NIP
        $user = User::where('nip', $request->nip)->first();

        // Check credentials
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'nip' => ['NIP atau password salah.'],
            ]);
        }

        // Generate JWT token
        $token = auth('api')->login($user);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => new UserResource($user)
            ]
        ]);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => new UserResource(auth()->user())
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    /**
     * Refresh JWT token
     */
    public function refresh()
    {
        return response()->json([
            'success' => true,
            'message' => 'Token berhasil diperbarui',
            'data' => [
                'token' => auth()->refresh(),
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ]);
    }
}
