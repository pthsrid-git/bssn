<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LogbookController extends Controller
{
    public function index(Request $request)
    {
        $query = Logbook::where('user_id', auth()->id());

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('tanggal', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->where('tanggal', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $logbooks = $query->orderBy('tanggal', 'asc')
            // ->orderBy('jam_mulai', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $logbooks
        ]);
    }

    public function store(Request $request)
    {
        // Log incoming request for debugging
        \Log::info('Logbook store request', [
            'all_data' => $request->all(),
            'has_file' => $request->hasFile('file'),
            'files' => $request->allFiles(),
        ]);

        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'rencana_hasil_kinerja_skp' => 'required|string',
            'indikator_hasil_rencana_kerja' => 'required|string',
            'aktivitas_kegiatan_harian' => 'required|string',
            'keterangan' => 'nullable|string',
            'status' => 'nullable|in:Disubmit,Disetujui,Ditolak',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only([
            'tanggal',
            'jam_mulai',
            'jam_selesai',
            'rencana_hasil_kinerja_skp',
            'indikator_hasil_rencana_kerja',
            'aktivitas_kegiatan_harian',
            'keterangan',
        ]);

        $data['user_id'] = auth()->id();
        $data['status'] = $request->status ?? 'Draft';

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            \Log::info('File upload details', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'valid' => $file->isValid(),
            ]);

            if ($file->isValid()) {
                try {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('logbooks', $fileName, 'public');

                    $data['file_path'] = $filePath;
                    $data['file_name'] = $file->getClientOriginalName();
                    $data['file_size'] = $file->getSize();

                    \Log::info('File stored successfully', ['path' => $filePath]);
                } catch (\Exception $e) {
                    \Log::error('File upload error', ['error' => $e->getMessage()]);
                    return response()->json([
                        'success' => false,
                        'message' => 'File upload failed: ' . $e->getMessage()
                    ], 500);
                }
            } else {
                \Log::error('Invalid file upload');
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file upload'
                ], 422);
            }
        }

        try {
            $logbook = Logbook::create($data);

            \Log::info('Logbook created successfully', ['id' => $logbook->id]);

            // Load relationships if needed
            $logbook->load('user'); // Jika ada relasi user

            return response()->json([
                'success' => true,
                'message' => 'Logbook created successfully',
                'data' => $logbook
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Database error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create logbook: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $logbook = Logbook::where('user_id', auth()->id())
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $logbook
        ]);
    }

    public function update(Request $request, $id)
    {
        $logbook = Logbook::where('user_id', auth()->id())
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tanggal' => 'sometimes|date',
            'jam_mulai' => 'sometimes|date_format:H:i',
            'jam_selesai' => 'sometimes|date_format:H:i|after:jam_mulai',
            'rencana_hasil_kinerja_skp' => 'sometimes|string',
            'indikator_hasil_rencana_kerja' => 'sometimes|string',
            'aktivitas_kegiatan_harian' => 'sometimes|string',
            'keterangan' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'status' => 'sometimes|in:Disubmit,Disetujui,Ditolak',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only([
            'tanggal',
            'jam_mulai',
            'jam_selesai',
            'rencana_hasil_kinerja_skp',
            'indikator_hasil_rencana_kerja',
            'aktivitas_kegiatan_harian',
            'keterangan',
            'status',
        ]);

        // Validate status value if provided
        if (isset($data['status']) && !in_array($data['status'], ['Draft', 'Disubmit', 'Disetujui', 'Ditolak'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status value'
            ], 422);
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($logbook->file_path) {
                Storage::disk('public')->delete($logbook->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('logbooks', $fileName, 'public');

            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        $logbook->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Logbook updated successfully',
            'data' => $logbook
        ]);
    }

    public function destroy($id)
    {
        $logbook = Logbook::where('user_id', auth()->id())
            ->findOrFail($id);

        // Delete file if exists
        if ($logbook->file_path) {
            Storage::disk('public')->delete($logbook->file_path);
        }

        $logbook->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logbook deleted successfully'
        ]);
    }

    // Submit logbook
    public function submit($id)
    {
        $logbook = Logbook::where('user_id', auth()->id())
            ->findOrFail($id);

        $logbook->update(['status' => 'Disubmit']);

        return response()->json([
            'success' => true,
            'message' => 'Logbook submitted successfully',
            'data' => $logbook
        ]);
    }
}
