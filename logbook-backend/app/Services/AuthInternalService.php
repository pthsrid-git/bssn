<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AuthInternalService
{
    protected $baseUrl;
    protected $roleInAuthBssn;

    public function __construct()
    {
        $this->baseUrl = config('bssn.api.auth.internal');
        $this->roleInAuthBssn = config('bssn.api.auth.role');
    }

    public function assign($guid, $role)
    {
        $roleKey = $this->roleInAuthBssn[$role] ?? null;
        return $this->assignAndRevoke($guid, $roleKey, 'assign');
    }

    public function revoke($guid, $role)
    {
        $roleKey = $this->roleInAuthBssn[$role] ?? null;
        return $this->assignAndRevoke($guid, $roleKey, 'revoke');
    }

    public function assignAndRevoke($guid, $role, $action = 'assign')
    {
        // If no base URL configured, just return success (for local development)
        if ($this->baseUrl == null) {
            return [
                'success' => 1,
                'message' => [],
            ];
        }

        $apiUrl = $this->baseUrl . $guid . '/' . $action . '/roles/' . $role;
        $token = request()->header('Authorization');

        try {
            $response = Http::timeout(10)
                ->retry(3, 100)
                ->withHeaders([
                    'Authorization' => $token,
                ])->patch($apiUrl);

            if ($response->successful()) {
                return [
                    'success' => 1,
                    'message' => $response->json(),
                ];
            } else {
                return [
                    'success' => 0,
                    'message' => $response->getReasonPhrase(),
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => 0,
                'message' => $e->getMessage(),
            ];
        }
    }
}
