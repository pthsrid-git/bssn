<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login', 'register']]);
    // }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'nama_pegawai' => 'required|string|max:255',
            'guid' => 'required|string|unique:users',
            'fpid' => 'required|string',
            'nip_nrp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'guid' => $request->guid,
            'fpid' => $request->fpid,
            'nama_pegawai' => $request->nama_pegawai,
            'name' => $request->nama_pegawai,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nip_nrp' => $request->nip_nrp,
            'pangkat_golongan' => $request->pangkat_golongan,
            'golongan' => $request->golongan,
            'kode_peta_jabatan' => $request->kode_peta_jabatan,
            'nama_jabatan' => $request->nama_jabatan,
            'kelas' => $request->kelas,
            'kode_unit_organisasi' => $request->kode_unit_organisasi,
            'nama_unit_organisasi' => $request->nama_unit_organisasi,
            'nama_lengkap_peta_jabatan' => $request->nama_lengkap_peta_jabatan,
        ]);

        $token = auth('api')->login($user);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip_nrp' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('nip_nrp', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function profile()
    {
        return response()->json([
            'data' => auth()->user()
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
