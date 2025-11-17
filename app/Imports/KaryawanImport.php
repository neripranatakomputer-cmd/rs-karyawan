<?php
// app/Imports/KaryawanImport.php

namespace App\Imports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class KaryawanImport implements 
    ToModel, 
    WithHeadingRow, 
    WithValidation,
    SkipsEmptyRows,
    SkipsOnError,
    SkipsOnFailure
{
    protected $errors = [];
    protected $successCount = 0;
    protected $errorCount = 0;

    public function model(array $row)
    {
        try {
            $this->successCount++;
            
            return new Karyawan([
                'nip'                  => $row['nip'],
                'nik'                  => $row['nik'],
                'nama_lengkap'         => $row['nama_lengkap'],
                'nama_gelar'           => $row['nama_gelar'] ?? null,
                'jenis_kelamin'        => $row['jenis_kelamin'],
                'no_hp'                => $row['no_hp'],
                'email'                => $row['email'],
                'alamat'               => $row['alamat'],
                'pendidikan_terakhir'  => $row['pendidikan_terakhir'],
                'tahun_lulus'          => $row['tahun_lulus'],
                'jabatan'              => $row['jabatan'],
                'unit'                 => $row['unit'],
            ]);
        } catch (\Exception $e) {
            $this->errorCount++;
            $this->errors[] = [
                'row' => $this->successCount + $this->errorCount,
                'error' => $e->getMessage()
            ];
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
            'email.unique' => 'Email sudah terdaftar',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan',
        ];
    }

    public function onError(\Throwable $e)
    {
        $this->errorCount++;
        $this->errors[] = $e->getMessage();
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
        }
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