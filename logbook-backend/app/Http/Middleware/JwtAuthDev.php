<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthDev
{
    /**
     * Handle an incoming request.
     * Di development, bypass JWT dan gunakan test user dari env
     * Di production, parse JWT dan cari user berdasarkan GUID
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah mode development (bypass JWT)
        $bypassJwt = config('app.env') === 'local' || env('JWT_BYPASS', false);

        if ($bypassJwt) {
            // Mode development: gunakan test user dari env atau user pertama
            $testUserId = env('TEST_USER_ID');

            if ($testUserId) {
                $user = User::find($testUserId);
            } else {
                // Fallback ke user pertama jika tidak ada TEST_USER_ID
                $user = User::first();
            }

            if ($user) {
                // Set user ke auth
                auth()->login($user);
                \Log::info('JWT Bypass: Using test user', ['user_id' => $user->id, 'name' => $user->name]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No test user found. Please create a user or set TEST_USER_ID in .env'
                ], 401);
            }
        } else {
            // Mode production: parse JWT dan cari user berdasarkan GUID
            $jwt = $this->requestHasToken($request);

            if (!$jwt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token not provided'
                ], 401);
            }

            $guid = $this->parseGuidFromToken($jwt);

            if (!$guid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid authorization token'
                ], 401);
            }

            // Cari user berdasarkan GUID
            $user = User::where('guid', $guid)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 401);
            }

            // Set user ke auth
            auth()->login($user);

            // Set GUID ke header untuk digunakan di controller jika perlu
            $request->headers->set('guid', $guid);
        }

        return $next($request);
    }

    /**
     * Check if request has Bearer token
     */
    private function requestHasToken(Request $request): string|bool
    {
        $authorization = explode(' ', $request->header('Authorization') ?? '');

        if (!str_contains(strtolower($authorization[0] ?? ''), 'bearer')) {
            return false;
        }

        return $authorization[1] ?? false;
    }

    /**
     * Parse GUID (sub claim) from JWT token
     */
    private function parseGuidFromToken(string $jwt): string|bool
    {
        try {
            $parser = new Parser(new JoseEncoder());
            $payload = $parser->parse($jwt);

            return $payload->claims()->get('sub');
        } catch (\Exception $e) {
            \Log::error('JWT Parse Error', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
