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
            'nip_nrp' => 'required|string|unique:users',
            'role' => 'required|string|in:ka-unit,pmk,pko,admin',
            'parent_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validasi hierarki role
        if ($request->parent_id) {
            $parent = User::find($request->parent_id);
            $validHierarchy = $this->validateRoleHierarchy($request->role, $parent->role);

            if (!$validHierarchy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid role hierarchy. Check parent-child role relationship.',
                ], 422);
            }
        }

        $user = User::create([
            'guid' => $request->guid,
            'fpid' => $request->fpid,
            'nama_pegawai' => $request->nama_pegawai,
            'name' => $request->nama_pegawai,
            'role' => $request->role,
            'parent_id' => $request->parent_id,
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
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => $this->formatUserResponse($user),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip_nrp' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cari user berdasarkan nip_nrp
        $user = User::where('nip_nrp', $request->nip_nrp)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'NIP/NRP atau password salah'
            ], 401);
        }

        // Generate token
        $token = auth('api')->login($user);

        return $this->respondWithToken($token);
    }

    public function profile()
    {
        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'data' => $this->formatUserResponse($user)
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    public function refresh()
    {
        try {
            $token = auth('api')->refresh();
            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token refresh failed',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    protected function respondWithToken($token)
    {
        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => $this->formatUserResponse($user)
            ]
        ]);
    }

    /**
     * Format user response dengan informasi dasar
     */
    protected function formatUserResponse($user)
    {
        // Load relasi yang dibutuhkan
        $user->load(['parent', 'children']);

        $response = [
            'id' => $user->id,
            'guid' => $user->guid,
            'fpid' => $user->fpid,
            'nama_pegawai' => $user->nama_pegawai,
            'name' => $user->name,
            'email' => $user->email,
            'nip_nrp' => $user->nip_nrp,
            'role' => $user->role,
            'parent_id' => $user->parent_id,
            'pangkat_golongan' => $user->pangkat_golongan,
            'golongan' => $user->golongan,
            'kode_peta_jabatan' => $user->kode_peta_jabatan,
            'nama_jabatan' => $user->nama_jabatan,
            'kelas' => $user->kelas,
            'kode_unit_organisasi' => $user->kode_unit_organisasi,
            'nama_unit_organisasi' => $user->nama_unit_organisasi,
            'nama_lengkap_peta_jabatan' => $user->nama_lengkap_peta_jabatan,

            // Informasi hierarki
            'parent' => $user->parent ? [
                'guid' => $user->parent->guid,
                'fpid' => $user->parent->fpid,
                'nama_pegawai' => $user->parent->nama_pegawai,
                'name' => $user->parent->name,
                'email' => $user->parent->email,
                'nip_nrp' => $user->parent->nip_nrp,
                'role' => $user->parent->role,
                'pangkat_golongan' => $user->parent->pangkat_golongan,
                'golongan' => $user->parent->golongan,
                'kode_peta_jabatan' => $user->parent->kode_peta_jabatan,
                'nama_jabatan' => $user->parent->nama_jabatan,
                'kelas' => $user->parent->kelas,
                'kode_unit_organisasi' => $user->parent->kode_unit_organisasi,
                'nama_unit_organisasi' => $user->parent->nama_unit_organisasi,
                'nama_lengkap_peta_jabatan' => $user->parent->nama_lengkap_peta_jabatan,
            ] : null,
            'children_count' => $user->children->count(),
        ];

        return $response;
    }

    /**
     * Validasi hierarki role
     */
    protected function validateRoleHierarchy($childRole, $parentRole)
    {
        $validHierarchy = [
            'pmk' => ['ka-unit'],
            'pko' => ['pmk'],
        ];

        if (!isset($validHierarchy[$childRole])) {
            return true; // ka-unit dan admin tidak perlu parent
        }

        return in_array($parentRole, $validHierarchy[$childRole]);
    }
}
