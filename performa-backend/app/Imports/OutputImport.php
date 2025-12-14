<?php

namespace App\Imports;

use App\Models\ImmediateOutcomeLv3;
use App\Models\Output;
use App\Models\OutputIndicator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OutputImport implements ToCollection, WithHeadingRow, WithValidation
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
                $immediateOutcomeLv3 = ImmediateOutcomeLv3::where('kode_imm_o_lv3', $row['kode_imm_o_lv3'])
                    ->whereHas('intermediateOutcomeLv2.intermediateOutcomeLv1.finalOutcome', function ($q) {
                        $q->where('periode_id', $this->periodeId);
                    })
                    ->first();

                if (!$immediateOutcomeLv3) {
                    throw new \Exception("Immediate Outcome Level 3 dengan kode {$row['kode_imm_o_lv3']} tidak ditemukan untuk periode ini");
                }

                $output = Output::create([
                    'immediate_outcome_lv3_id' => $immediateOutcomeLv3->id,
                    'kode_uo' => $row['kode_uo'],
                    'kode_int_o_lv1' => $row['kode_int_o_lv1'],
                    'kode_int_o_lv2' => $row['kode_int_o_lv2'],
                    'kode_imm_o_lv3' => $row['kode_imm_o_lv3'],
                    'kode_io' => $row['kode_io'],
                    'deskripsi' => $row['deskripsi'],
                ]);

                $indikators = $this->parseMultipleValues($row['indikator_output']);

                foreach ($indikators as $indikator) {
                    if (!empty(trim($indikator))) {
                        OutputIndicator::create([
                            'output_id' => $output->id,
                            'indikator_output' => trim($indikator),
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Output Import failed", ['error' => $e->getMessage()]);
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
            'kode_io' => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'indikator_output' => 'required|string',
        ];
    }

    private function parseMultipleValues($value)
    {
        return preg_split('/[|;\n\r]+/', $value);
    }
}
