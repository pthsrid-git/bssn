<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinalOutcome;
use App\Models\IntermediateOutcomeLv1;
use App\Models\IntermediateOutcomeLv2;
use App\Models\ImmediateOutcomeLv3;
use App\Models\Output;
use App\Models\Periode;
use App\Imports\FinalOutcomeImport;
use App\Imports\IntermediateOutcomeLv1Import;
use App\Imports\IntermediateOutcomeLv2Import;
use App\Imports\ImmediateOutcomeLv3Import;
use App\Imports\OutputImport;
use App\Imports\PenerjemahanIntermediateLv2Import;
use App\Imports\PenerjemahanImmediateLv3Import;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PohonKinerjaController extends Controller
{

    // ========== FINAL OUTCOME ==========

    public function getFinalOutcomes(Request $request)
    {
        try {
            $query = FinalOutcome::with(['periode', 'indicators']);

            if ($request->has('periode_id') && !empty($request->periode_id)) {
                $query->where('periode_id', $request->periode_id);
            }

            $finalOutcomes = $query->orderBy('created_at', 'desc')
                ->paginate(15);

            // Transform data to include indicators as array
            $finalOutcomes->getCollection()->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'periode_id' => $item->periode_id,
                    'kode_uo' => $item->kode_uo,
                    'sasaran' => $item->sasaran,
                    'indikator_kinerja' => $item->indicators->pluck('indikator_kinerja')->toArray(),
                    'periode' => $item->periode ? [
                        'id' => $item->periode->id,
                        'tahun' => $item->periode->tahun,
                        'nama' => $item->periode->nama,
                    ] : null,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });

            return response()->json($finalOutcomes, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching final outcomes', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch final outcomes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getFinalOutcome($id)
    {
        $finalOutcome = FinalOutcome::with(['indicators', 'periode', 'intermediateOutcomesLv1'])
            ->find($id);

        if (!$finalOutcome) {
            return response()->json([
                'success' => false,
                'message' => 'Final Outcome tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $finalOutcome
        ]);
    }

    public function storeFinalOutcome(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'periode_id' => 'required|exists:periode,id',
            'kode_uo' => 'required|string|max:10',
            'sasaran' => 'required|string',
            'indikator_kinerja' => 'required|array',
            'indikator_kinerja.*' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $finalOutcome = FinalOutcome::create([
                'periode_id' => $request->periode_id,
                'kode_uo' => $request->kode_uo,
                'sasaran' => $request->sasaran,
            ]);

            foreach ($request->indikator_kinerja as $indikator) {
                $finalOutcome->indicators()->create([
                    'indikator_kinerja' => $indikator,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Final Outcome berhasil ditambahkan',
                'data' => $finalOutcome->load('indicators')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan Final Outcome',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateFinalOutcome(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'periode_id' => 'sometimes|required|exists:periode,id',
            'kode_uo' => 'sometimes|required|string|max:10',
            'sasaran' => 'sometimes|required|string',
            'indikator_kinerja' => 'sometimes|required|array',
            'indikator_kinerja.*' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $finalOutcome = FinalOutcome::find($id);

        if (!$finalOutcome) {
            return response()->json([
                'success' => false,
                'message' => 'Final Outcome tidak ditemukan'
            ], 404);
        }

        DB::beginTransaction();
        try {
            $finalOutcome->update($request->only(['periode_id', 'kode_uo', 'sasaran']));

            if ($request->has('indikator_kinerja')) {
                $finalOutcome->indicators()->delete();

                foreach ($request->indikator_kinerja as $indikator) {
                    $finalOutcome->indicators()->create([
                        'indikator_kinerja' => $indikator,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Final Outcome berhasil diupdate',
                'data' => $finalOutcome->load('indicators')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate Final Outcome',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteFinalOutcome($id)
    {
        $finalOutcome = FinalOutcome::find($id);

        if (!$finalOutcome) {
            return response()->json([
                'success' => false,
                'message' => 'Final Outcome tidak ditemukan'
            ], 404);
        }

        try {
            $finalOutcome->delete();

            return response()->json([
                'success' => true,
                'message' => 'Final Outcome berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Final Outcome',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadFinalOutcome(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
            'periode_id' => 'required|exists:periode,id',
            'replace' => 'sometimes|boolean' // Optional: true to replace, false to append
        ]);

        DB::beginTransaction();
        try {
            $file = $request->file('file');
            $periodeId = $request->periode_id;
            $replace = $request->input('replace', false); // Default: append (false)

            Log::info('Starting Final Outcome import', [
                'periode_id' => $periodeId,
                'filename' => $file->getClientOriginalName(),
                'replace' => $replace
            ]);

            // Only delete if replace mode is enabled
            if ($replace) {
                $deleted = FinalOutcome::where('periode_id', $periodeId)->delete();
                Log::info("Deleted {$deleted} existing Final Outcomes (replace mode)");

                // Reset sequences after delete
                $this->resetSequence('final_outcomes');
                $this->resetSequence('final_outcome_indicators');
            } else {
                Log::info("Append mode: Keeping existing data");
            }

            // Import new data
            $import = new FinalOutcomeImport($periodeId);
            Excel::import($import, $file);

            $importedCount = $import->getImportedCount();
            $errors = $import->getErrors();

            DB::commit();

            if (count($errors) > 0) {
                Log::warning('Import completed with errors', [
                    'imported' => $importedCount,
                    'errors' => count($errors)
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "Import selesai dengan beberapa error. {$importedCount} data berhasil diimport.",
                    'imported_count' => $importedCount,
                    'error_count' => count($errors),
                    'errors' => array_slice($errors, 0, 10)
                ], 200);
            }

            Log::info('Import completed successfully', ['imported' => $importedCount]);

            return response()->json([
                'success' => true,
                'message' => "Data berhasil diimport. Total: {$importedCount} data.",
                'imported_count' => $importedCount
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Import gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset table sequence
     */
    protected function resetSequence($table)
    {
        try {
            $maxId = DB::table($table)->max('id') ?? 0;
            $nextVal = $maxId + 1;
            $sequenceName = "{$table}_id_seq";

            DB::statement("ALTER SEQUENCE {$sequenceName} RESTART WITH {$nextVal}");
            Log::info("Sequence {$sequenceName} reset to {$nextVal}");
        } catch (\Exception $e) {
            Log::warning("Failed to reset sequence for {$table}: " . $e->getMessage());
        }
    }
    // ========== INTERMEDIATE OUTCOME LEVEL 1 ==========

    public function getIntermediateOutcomesLv1(Request $request)
    {
        try {
            $query = IntermediateOutcomeLv1::with(['indicators', 'finalOutcome.periode']);

            if ($request->has('periode_id') && !empty($request->periode_id)) {
                $query->whereHas('finalOutcome', function ($q) use ($request) {
                    $q->where('periode_id', $request->periode_id);
                });
            }

            $intermediateOutcomes = $query->orderBy('created_at', 'desc')
                ->paginate(15);

            // Transform data to match frontend expectations
            $intermediateOutcomes->getCollection()->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'final_outcome_id' => $item->final_outcome_id,
                    'kode_uo' => $item->kode_uo,
                    'kode_int_o_lv1' => $item->kode_int_o_lv1,
                    'sasaran' => $item->sasaran,
                    'indikator_kinerja' => $item->indicators->pluck('indikator_kinerja')->toArray(),
                    'periode' => $item->finalOutcome && $item->finalOutcome->periode ? [
                        'id' => $item->finalOutcome->periode->id,
                        'tahun' => $item->finalOutcome->periode->tahun,
                        'nama' => $item->finalOutcome->periode->nama,
                    ] : null,
                    'final_outcome' => $item->finalOutcome ? [
                        'id' => $item->finalOutcome->id,
                        'kode_uo' => $item->finalOutcome->kode_uo,
                        'sasaran' => $item->finalOutcome->sasaran,
                    ] : null,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });

            return response()->json($intermediateOutcomes, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching intermediate outcomes lv1', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch intermediate outcomes lv1',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getIntermediateOutcomeLv1($id)
    {
        try {
            $intermediateOutcome = IntermediateOutcomeLv1::with([
                'indicators',
                'finalOutcome.periode',
                'intermediateOutcomesLv2'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $intermediateOutcome->id,
                    'final_outcome_id' => $intermediateOutcome->final_outcome_id,
                    'kode_uo' => $intermediateOutcome->kode_uo,
                    'kode_int_o_lv1' => $intermediateOutcome->kode_int_o_lv1,
                    'sasaran' => $intermediateOutcome->sasaran,
                    'indikator_kinerja' => $intermediateOutcome->indicators->pluck('indikator_kinerja')->toArray(),
                    'final_outcome' => $intermediateOutcome->finalOutcome,
                    'created_at' => $intermediateOutcome->created_at,
                    'updated_at' => $intermediateOutcome->updated_at,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Intermediate Outcome Level 1 tidak ditemukan'
            ], 404);
        }
    }

    public function storeIntermediateOutcomeLv1(Request $request)
    {
        $request->validate([
            'final_outcome_id' => 'required|exists:final_outcomes,id',
            'kode_uo' => 'required|string|max:50',
            'kode_int_o_lv1' => 'required|string|max:50',
            'sasaran' => 'required|string',
            'indikator_kinerja' => 'required|array',
            'indikator_kinerja.*' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $intermediateOutcome = IntermediateOutcomeLv1::create([
                'final_outcome_id' => $request->final_outcome_id,
                'kode_uo' => $request->kode_uo,
                'kode_int_o_lv1' => $request->kode_int_o_lv1,
                'sasaran' => $request->sasaran,
            ]);

            foreach ($request->indikator_kinerja as $indikator) {
                $intermediateOutcome->indicators()->create([
                    'indikator_kinerja' => $indikator,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Intermediate Outcome Level 1 berhasil ditambahkan',
                'data' => $intermediateOutcome->load('indicators')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error creating intermediate outcome lv1', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan Intermediate Outcome Level 1',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadIntermediateOutcomeLv1(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
            'periode_id' => 'required|exists:periode,id',
            'replace' => 'sometimes|boolean'
        ]);

        DB::beginTransaction();
        try {
            $file = $request->file('file');
            $periodeId = $request->periode_id;
            $replace = $request->input('replace', false);

            Log::info('Starting Intermediate LV1 import', [
                'periode_id' => $periodeId,
                'filename' => $file->getClientOriginalName(),
                'replace' => $replace
            ]);

            // Only delete if replace mode
            if ($replace) {
                // Delete through FinalOutcome relationship
                $deleted = IntermediateOutcomeLv1::whereHas('finalOutcome', function ($q) use ($periodeId) {
                    $q->where('periode_id', $periodeId);
                })->delete();

                Log::info("Deleted {$deleted} existing Intermediate LV1 (replace mode)");

                $this->resetSequence('intermediate_outcomes_lv1');
                $this->resetSequence('intermediate_outcome_lv1_indicators');
            }

            // Import new data
            $import = new IntermediateOutcomeLv1Import($periodeId);
            Excel::import($import, $file);

            $importedCount = $import->getImportedCount();
            $errors = $import->getErrors();

            DB::commit();

            if (count($errors) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => "Import selesai dengan beberapa error. {$importedCount} data berhasil diimport.",
                    'imported_count' => $importedCount,
                    'error_count' => count($errors),
                    'errors' => array_slice($errors, 0, 10)
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => "Data berhasil diimport. Total: {$importedCount} data.",
                'imported_count' => $importedCount
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Import gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========== INTERMEDIATE OUTCOME LEVEL 2 ==========

    public function getIntermediateOutcomesLv2(Request $request)
    {
        $periodeId = $request->get('periode_id');
        $perPage = $request->get('per_page', 15);

        $query = IntermediateOutcomeLv2::with([
            'indicators',
            'intermediateOutcomeLv1.finalOutcome.periode'
        ]);

        if ($periodeId) {
            $query->whereHas('intermediateOutcomeLv1.finalOutcome', function ($q) use ($periodeId) {
                $q->where('periode_id', $periodeId);
            });
        }

        $intermediateOutcomes = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $intermediateOutcomes
        ]);
    }

    public function getIntermediateOutcomeLv2($id)
    {
        $intermediateOutcome = IntermediateOutcomeLv2::with([
            'indicators',
            'intermediateOutcomeLv1',
            'immediateOutcomesLv3'
        ])->find($id);

        if (!$intermediateOutcome) {
            return response()->json([
                'success' => false,
                'message' => 'Intermediate Outcome Level 2 tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $intermediateOutcome
        ]);
    }

    public function storeIntermediateOutcomeLv2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'intermediate_outcome_lv1_id' => 'required|exists:intermediate_outcomes_lv1,id',
            'kode_uo' => 'required|string|max:10',
            'kode_int_o_lv1' => 'required|string|max:20',
            'kode_int_o_lv2' => 'required|string|max:30',
            'sasaran_program' => 'required|string',
            'iksp' => 'required|array',
            'iksp.*' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $intermediateOutcome = IntermediateOutcomeLv2::create([
                'intermediate_outcome_lv1_id' => $request->intermediate_outcome_lv1_id,
                'kode_uo' => $request->kode_uo,
                'kode_int_o_lv1' => $request->kode_int_o_lv1,
                'kode_int_o_lv2' => $request->kode_int_o_lv2,
                'sasaran_program' => $request->sasaran_program,
            ]);

            foreach ($request->iksp as $iksp) {
                $intermediateOutcome->indicators()->create([
                    'iksp' => $iksp,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Intermediate Outcome Level 2 berhasil ditambahkan',
                'data' => $intermediateOutcome->load('indicators')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan Intermediate Outcome Level 2',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadIntermediateOutcomeLv2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
            'periode_id' => 'required|exists:periode,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Excel::import(
                new IntermediateOutcomeLv2Import($request->periode_id),
                $request->file('file')
            );

            return response()->json([
                'success' => true,
                'message' => 'Data Intermediate Outcome Level 2 berhasil diimport'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal import data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ========== IMMEDIATE OUTCOME LEVEL 3 ==========

    public function getImmediateOutcomesLv3(Request $request)
    {
        $periodeId = $request->get('periode_id');
        $perPage = $request->get('per_page', 15);

        $query = ImmediateOutcomeLv3::with([
            'indicators',
            'intermediateOutcomeLv2.intermediateOutcomeLv1.finalOutcome.periode'
        ]);

        if ($periodeId) {
            $query->whereHas('intermediateOutcomeLv2.intermediateOutcomeLv1.finalOutcome', function ($q) use ($periodeId) {
                $q->where('periode_id', $periodeId);
            });
        }

        $immediateOutcomes = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $immediateOutcomes
        ]);
    }

    public function getImmediateOutcomeLv3($id)
    {
        $immediateOutcome = ImmediateOutcomeLv3::with([
            'indicators',
            'intermediateOutcomeLv2',
            'outputs'
        ])->find($id);

        if (!$immediateOutcome) {
            return response()->json([
                'success' => false,
                'message' => 'Immediate Outcome Level 3 tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $immediateOutcome
        ]);
    }

    public function storeImmediateOutcomeLv3(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'intermediate_outcome_lv2_id' => 'required|exists:intermediate_outcomes_lv2,id',
            'kode_uo' => 'required|string|max:10',
            'kode_int_o_lv1' => 'required|string|max:20',
            'kode_int_o_lv2' => 'required|string|max:30',
            'kode_imm_o_lv3' => 'required|string|max:40',
            'sasaran_kegiatan' => 'required|string',
            'iksk' => 'required|array',
            'iksk.*' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $immediateOutcome = ImmediateOutcomeLv3::create([
                'intermediate_outcome_lv2_id' => $request->intermediate_outcome_lv2_id,
                'kode_uo' => $request->kode_uo,
                'kode_int_o_lv1' => $request->kode_int_o_lv1,
                'kode_int_o_lv2' => $request->kode_int_o_lv2,
                'kode_imm_o_lv3' => $request->kode_imm_o_lv3,
                'sasaran_kegiatan' => $request->sasaran_kegiatan,
            ]);

            foreach ($request->iksk as $iksk) {
                $immediateOutcome->indicators()->create([
                    'iksk' => $iksk,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Immediate Outcome Level 3 berhasil ditambahkan',
                'data' => $immediateOutcome->load('indicators')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan Immediate Outcome Level 3',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadImmediateOutcomeLv3(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
            'periode_id' => 'required|exists:periode,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Excel::import(
                new ImmediateOutcomeLv3Import($request->periode_id),
                $request->file('file')
            );

            return response()->json([
                'success' => true,
                'message' => 'Data Immediate Outcome Level 3 berhasil diimport'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal import data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ========== OUTPUT ==========

    public function getOutputs(Request $request)
    {
        $periodeId = $request->get('periode_id');
        $perPage = $request->get('per_page', 15);

        $query = Output::with([
            'indicators',
            'immediateOutcomeLv3.intermediateOutcomeLv2.intermediateOutcomeLv1.finalOutcome.periode'
        ]);

        if ($periodeId) {
            $query->whereHas('immediateOutcomeLv3.intermediateOutcomeLv2.intermediateOutcomeLv1.finalOutcome', function ($q) use ($periodeId) {
                $q->where('periode_id', $periodeId);
            });
        }

        $outputs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $outputs
        ]);
    }

    public function getOutput($id)
    {
        $output = Output::with(['indicators', 'immediateOutcomeLv3'])->find($id);

        if (!$output) {
            return response()->json([
                'success' => false,
                'message' => 'Output tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $output
        ]);
    }

    public function storeOutput(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'immediate_outcome_lv3_id' => 'required|exists:immediate_outcomes_lv3,id',
            'kode_uo' => 'required|string|max:10',
            'kode_int_o_lv1' => 'required|string|max:20',
            'kode_int_o_lv2' => 'required|string|max:30',
            'kode_imm_o_lv3' => 'required|string|max:40',
            'kode_io' => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'indikator_output' => 'required|array',
            'indikator_output.*' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $output = Output::create([
                'immediate_outcome_lv3_id' => $request->immediate_outcome_lv3_id,
                'kode_uo' => $request->kode_uo,
                'kode_int_o_lv1' => $request->kode_int_o_lv1,
                'kode_int_o_lv2' => $request->kode_int_o_lv2,
                'kode_imm_o_lv3' => $request->kode_imm_o_lv3,
                'kode_io' => $request->kode_io,
                'deskripsi' => $request->deskripsi,
            ]);

            foreach ($request->indikator_output as $indikator) {
                $output->indicators()->create([
                    'indikator_output' => $indikator,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Output berhasil ditambahkan',
                'data' => $output->load('indicators')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan Output',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadOutput(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
            'periode_id' => 'required|exists:periode,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Excel::import(
                new OutputImport($request->periode_id),
                $request->file('file')
            );

            return response()->json([
                'success' => true,
                'message' => 'Data Output berhasil diimport'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal import data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ========== PENERJEMAHAN ==========

    public function uploadPenerjemahanIntermediateLv2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Excel::import(
                new PenerjemahanIntermediateLv2Import(),
                $request->file('file')
            );

            return response()->json([
                'success' => true,
                'message' => 'Data Penerjemahan Intermediate Level 2 berhasil diimport'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal import data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadPenerjemahanImmediateLv3(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Excel::import(
                new PenerjemahanImmediateLv3Import(),
                $request->file('file')
            );

            return response()->json([
                'success' => true,
                'message' => 'Data Penerjemahan Immediate Level 3 berhasil diimport'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal import data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ========== DOWNLOAD TEMPLATE ==========

    public function downloadTemplate($type)
    {
        $templates = [
            'final-outcome' => 'final_outcome_template.xlsx',
            'intermediate-lv1' => 'intermediate_lv1_template.xlsx',
            'intermediate-lv2' => 'intermediate_lv2_template.xlsx',
            'immediate-lv3' => 'immediate_lv3_template.xlsx',
            'output' => 'output_template.xlsx',
            'penerjemahan-intermediate-lv2' => 'penerjemahan_intermediate_lv2_template.xlsx',
            'penerjemahan-immediate-lv3' => 'penerjemahan_immediate_lv3_template.xlsx',
        ];

        if (!isset($templates[$type])) {
            return response()->json([
                'success' => false,
                'message' => 'Template tidak ditemukan'
            ], 404);
        }

        $filePath = storage_path('app/public/templates/' . $templates[$type]);

        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File template tidak ditemukan'
            ], 404);
        }

        return response()->download($filePath, $templates[$type], [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
