<?php
// app/Exports/KaryawanExport.php
// Generate dengan: php artisan make:export KaryawanExport --model=Karyawan

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KaryawanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    public function collection()
    {
        return Karyawan::all();
    }

    public function headings(): array
    {
        return [
            'NIP',
            'NIK',
            'Nama Lengkap',
            'Nama dengan Gelar',
            'Jenis Kelamin',
            'No HP',
            'Email',
            'Alamat',
            'Pendidikan Terakhir',
            'Tahun Lulus',
            'Jabatan',
            'Unit',
        ];
    }

    public function map($karyawan): array
    {
        return [
            $karyawan->nip,
            $karyawan->nik,
            $karyawan->nama_lengkap,
            $karyawan->nama_gelar,
            $karyawan->jenis_kelamin,
            $karyawan->no_hp,
            $karyawan->email,
            $karyawan->alamat,
            $karyawan->pendidikan_terakhir,
            $karyawan->tahun_lulus,
            $karyawan->jabatan,
            $karyawan->unit,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // NIP
            'B' => 18, // NIK
            'C' => 25, // Nama Lengkap
            'D' => 30, // Nama Gelar
            'E' => 15, // Jenis Kelamin
            'F' => 15, // No HP
            'G' => 25, // Email
            'H' => 35, // Alamat
            'I' => 20, // Pendidikan
            'J' => 12, // Tahun Lulus
            'K' => 25, // Jabatan
            'L' => 25, // Unit
        ];
    }
}