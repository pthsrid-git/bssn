<?php

namespace App\Imports;

use App\Models\IntermediateOutcomeLv2;
use App\Models\ImmediateOutcomeLv3;
use App\Models\ImmediateOutcomeLv3Indicator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImmediateOutcomeLv3Import implements ToCollection, WithHeadingRow, WithValidation
{
    protected $periodeId;

    public function __construct($periodeId)
    {
        $this->periodeId = $periodeId;
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $intermediateOutcomeLv2 = IntermediateOutcomeLv2::where('kode_int_o_lv2', $row['kode_int_o_lv2'])
                    ->whereHas('intermediateOutcomeLv1.finalOutcome', function ($q) {
                        $q->where('periode_id', $this->periodeId);
                    })
                    ->first();

                if (!$intermediateOutcomeLv2) {
                    throw new \Exception("Intermediate Outcome Level 2 dengan kode {$row['kode_int_o_lv2']} tidak ditemukan untuk periode ini");
                }

                $immediateOutcome = ImmediateOutcomeLv3::create([
                    'intermediate_outcome_lv2_id' => $intermediateOutcomeLv2->id,
                    'kode_uo' => $row['kode_uo'],
                    'kode_int_o_lv1' => $row['kode_int_o_lv1'],
                    'kode_int_o_lv2' => $row['kode_int_o_lv2'],
                    'kode_imm_o_lv3' => $row['kode_imm_o_lv3'],
                    'sasaran_kegiatan' => $row['sasaran_kegiatan'],
                ]);

                $indikators = $this->parseMultipleValues($row['iksk']);

                foreach ($indikators as $indikator) {
                    if (!empty(trim($indikator))) {
                        ImmediateOutcomeLv3Indicator::create([
                            'immediate_outcome_lv3_id' => $immediateOutcome->id,
                            'iksk' => trim($indikator),
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("ImmediateOutcomeLv3 Import failed", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            'kode_uo' => 'required|string|max:10',
            'kode_int_o_lv1' => 'required|string|max:20',
            'kode_int_o_lv2' => 'required|string|max:30',
            'kode_imm_o_lv3' => 'required|string|max:40',
            'sasaran_kegiatan' => 'required|string',
            'iksk' => 'required|string',
        ];
    }

    private function parseMultipleValues($value)
    {
        return preg_split('/[|;\n\r]+/', $value);
    }
}
