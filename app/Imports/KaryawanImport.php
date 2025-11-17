<?php

namespace App\Imports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Log;

class KaryawanImport implements 
    ToModel, 
    WithHeadingRow, 
    WithValidation,
    SkipsEmptyRows,
    SkipsOnError,
    SkipsOnFailure,
    WithBatchInserts,
    WithChunkReading
{
    protected $errors = [];
    protected $successCount = 0;
    protected $errorCount = 0;

    public function model(array $row)
    {
        // Debug: Log row data
        Log::info('Processing row:', $row);

        // Skip jika row kosong
        if (empty($row['nip']) && empty($row['nama_lengkap'])) {
            return null;
        }

        try {
            $this->successCount++;
            
            return new Karyawan([
                'nip'                  => trim($row['nip'] ?? ''),
                'nik'                  => trim($row['nik'] ?? ''),
                'nama_lengkap'         => trim($row['nama_lengkap'] ?? ''),
                'nama_gelar'           => trim($row['nama_gelar'] ?? '') ?: null,
                'jenis_kelamin'        => trim($row['jenis_kelamin'] ?? ''),
                'no_hp'                => trim($row['no_hp'] ?? ''),
                'email'                => trim($row['email'] ?? ''),
                'alamat'               => trim($row['alamat'] ?? ''),
                'pendidikan_terakhir'  => trim($row['pendidikan_terakhir'] ?? ''),
                'tahun_lulus'          => trim($row['tahun_lulus'] ?? ''),
                'jabatan'              => trim($row['jabatan'] ?? ''),
                'unit'                 => trim($row['unit'] ?? ''),
            ]);
        } catch (\Exception $e) {
            $this->errorCount++;
            $this->errors[] = [
                'row' => $this->successCount + $this->errorCount,
                'error' => $e->getMessage(),
                'data' => $row
            ];
            
            // Log error
            Log::error('Import error:', [
                'row' => $row,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'nip' => 'required|unique:karyawans,nip',
            'nik' => 'required|digits:16|unique:karyawans,nik',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|unique:karyawans,email',
            'alamat' => 'required|string',
            'pendidikan_terakhir' => 'required|string',
            'tahun_lulus' => 'required|digits:4',
            'jabatan' => 'required|string',
            'unit' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nip.required' => 'NIP wajib diisi',
            'nip.unique' => 'NIP sudah terdaftar',
            'nik.required' => 'NIK wajib diisi',
            'nik.digits' => 'NIK harus 16 digit',
            'nik.unique' => 'NIK sudah terdaftar',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan',
            'tahun_lulus.digits' => 'Tahun lulus harus 4 digit',
        ];
    }

    public function onError(\Throwable $e)
    {
        $this->errorCount++;
        $this->errors[] = [
            'error' => $e->getMessage()
        ];
        
        Log::error('Import general error:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errorCount++;
            $this->errors[] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values()
            ];
            
            Log::warning('Import validation failure:', [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors()
            ]);
        }
    }

    public function batchSize(): int
    {
        return 100; // Process 100 rows at a time
    }

    public function chunkSize(): int
    {
        return 100; // Read 100 rows at a time
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getErrorCount()
    {
        return $this->errorCount;
    }
}