<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MasterPegawaiService
{
    protected $baseUrl = null;
    protected $apiKey = null;

    public function __construct()
    {
        $this->baseUrl = config('bssn.api.master_data.url');
        $this->apiKey = config('bssn.api.master_data.key');
    }

    /**
     * Get all pegawai from master data API
     */
    public function getAll()
    {
        $cachePath = 'all_pegawai';
        if (Cache::has($cachePath)) {
            $inCache = Cache::get($cachePath);
            if ($inCache['success'] == 1) {
                return $inCache;
            }
        }

        // If no API configured, return from local database
        if ($this->baseUrl == null) {
            return $this->getAllFromLocal();
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->get($this->baseUrl . '/pegawai/all');

            if ($response->successful()) {
                $arrReturn = [
                    'success' => 1,
                    'message' => $response->json(),
                ];
                Cache::put($cachePath, $arrReturn, 600);
            } else {
                $arrReturn = [
                    'success' => 0,
                    'message' => $response->getReasonPhrase(),
                ];
            }
        } catch (\Exception $e) {
            $arrReturn = [
                'success' => 0,
                'message' => $e->getMessage(),
            ];
        }

        return $arrReturn;
    }

    /**
     * Get all pegawai from local database
     */
    public function getAllFromLocal()
    {
        $users = User::select('guid', 'fullname as nama_pegawai', 'kode_unit_organisasi as kode_unitOrganisasi', 'kode_unit_organisasi as nama_unitOrganisasi')
            ->whereNotNull('guid')
            ->get()
            ->toArray();

        return [
            'success' => 1,
            'message' => ['data' => $users],
        ];
    }

    /**
     * Get pegawai by GUID
     */
    public function getPegawai($guid)
    {
        $cachePath = 'getPegawai' . $guid;
        if (Cache::has($cachePath)) {
            $inCache = Cache::get($cachePath);
            if ($inCache['success'] == 1) {
                return $inCache;
            }
        }

        // If no API configured, return from local database
        if ($this->baseUrl == null) {
            return $this->getPegawaiFromLocal($guid);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->get($this->baseUrl . '/pegawai/identitas/' . $guid);

            if ($response->successful()) {
                $data = $response->json();
                $arrReturn = [
                    'success' => 1,
                    'message' => $data,
                ];

                Cache::put($cachePath, $arrReturn, 600);
            } else {
                $arrReturn = [
                    'success' => 0,
                    'message' => $response->getReasonPhrase(),
                ];
            }
        } catch (\Exception $e) {
            $arrReturn = [
                'success' => 0,
                'message' => $e->getMessage(),
            ];
        }

        return $arrReturn;
    }

    /**
     * Get pegawai from local database by GUID
     */
    public function getPegawaiFromLocal($guid)
    {
        $user = User::where('guid', $guid)->first();

        if ($user) {
            return [
                'success' => 1,
                'message' => [
                    'data' => [
                        'guid' => $user->guid,
                        'nama_pegawai' => $user->fullname ?? $user->name,
                        'kode_unitOrganisasi' => $user->kode_unit_organisasi,
                        'nama_unitOrganisasi' => $user->kode_unit_organisasi,
                    ]
                ],
            ];
        }

        return [
            'success' => 0,
            'message' => 'Pegawai tidak ditemukan',
        ];
    }
}
