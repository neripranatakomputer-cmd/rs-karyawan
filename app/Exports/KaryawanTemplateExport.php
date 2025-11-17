<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KaryawanTemplateExport implements 
    FromArray, 
    WithHeadings, 
    WithStyles, 
    WithColumnWidths,
    ShouldAutoSize
{
    public function array(): array
    {
        // Return 3 rows contoh data
        return [
            [
                '123456789',                    // nip
                '3201234567890001',            // nik
                'Dr. Budi Santoso',            // nama_lengkap
                'Dr. Budi Santoso, Sp.PD',     // nama_gelar
                'Laki-laki',                   // jenis_kelamin
                '081234567890',                // no_hp
                'budi.santoso@rs.com',         // email
                'Jl. Kesehatan No. 123, Jakarta', // alamat
                'S2',                          // pendidikan_terakhir
                '2020',                        // tahun_lulus
                'Dokter Spesialis Penyakit Dalam', // jabatan
                'Poliklinik Penyakit Dalam',   // unit
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
            [
                '111222333',
                '3201234567890003',
                'Ahmad Fauzi',
                '',  // nama_gelar kosong (opsional)
                'Laki-laki',
                '081234567892',
                'ahmad.fauzi@rs.com',
                'Jl. Anggrek No. 78, Jakarta',
                'D3',
                '2021',
                'Teknisi Lab',
                'Laboratorium',
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
            // Style header row
            1 => [
                'font' => [
                    'bold' => true, 
                    'size' => 12, 
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '667eea']
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                ],
            ],
            // Style example rows
            2 => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8F5E9']
                ]
            ],
            3 => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E3F2FD']
                ]
            ],
            4 => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFF3E0']
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // nip
            'B' => 18, // nik
            'C' => 25, // nama_lengkap
            'D' => 30, // nama_gelar
            'E' => 15, // jenis_kelamin
            'F' => 15, // no_hp
            'G' => 25, // email
            'H' => 35, // alamat
            'I' => 20, // pendidikan_terakhir
            'J' => 12, // tahun_lulus
            'K' => 25, // jabatan
            'L' => 25, // unit
        ];
    }
}