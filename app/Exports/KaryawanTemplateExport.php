<?php
// app/Exports/KaryawanTemplateExport.php
// Generate dengan: php artisan make:export KaryawanTemplateExport

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KaryawanTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Template dengan contoh data
        return [
            [
                '123456789',                    // NIP
                '3201234567890001',            // NIK (16 digit)
                'Dr. Budi Santoso',            // Nama Lengkap
                'Dr. Budi Santoso, Sp.PD',     // Nama dengan Gelar (opsional)
                'Laki-laki',                   // Jenis Kelamin
                '081234567890',                // No HP
                'budi.santoso@rs.com',         // Email
                'Jl. Kesehatan No. 123, Jakarta', // Alamat
                'S2',                          // Pendidikan Terakhir
                '2020',                        // Tahun Lulus (4 digit)
                'Dokter Spesialis Penyakit Dalam', // Jabatan
                'Poliklinik Penyakit Dalam',   // Unit
            ],
            [
                '987654321',
                '3201234567890002',
                'Siti Nurhaliza',
                'Siti Nurhaliza, S.Kep',
                'Perempuan',
                '081234567891',
                'siti.nurhaliza@rs.com',
                'Jl. Mawar No. 45, Jakarta',
                'S1',
                '2019',
                'Perawat',
                'Ruang IGD',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nip',
            'nik',
            'nama_lengkap',
            'nama_gelar',
            'jenis_kelamin',
            'no_hp',
            'email',
            'alamat',
            'pendidikan_terakhir',
            'tahun_lulus',
            'jabatan',
            'unit',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '667eea']],
            ],
            2 => ['fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E8F5E9']]],
            3 => ['fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E3F2FD']]],
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
