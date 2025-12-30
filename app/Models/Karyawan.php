<?php
// app/Models/Karyawan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'foto_profil', 'nip', 'nik', 'nama_lengkap', 'nama_gelar', 'jenis_kelamin',
        'no_hp', 'email', 'alamat',
        'pendidikan_terakhir', 'tahun_lulus', 'ijazah',
        'jabatan', 'unit', 'sip', 'str'
    ];

    public function customFieldValues()
    {
        return $this->hasMany(CustomFieldValue::class);
    }

    public function attendances()
{
    return $this->hasMany(Attendance::class);
}

// Get total hadir bulan ini
public function getTotalHadirBulanIni()
{
    return $this->attendances()
        ->whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->where('status', 'hadir')
        ->count();
}

// Get rekap absensi
public function getRekapAbsensi($month = null, $year = null)
{
    $month = $month ?? now()->month;
    $year = $year ?? now()->year;
    
    $attendances = $this->attendances()
        ->whereMonth('tanggal', $month)
        ->whereYear('tanggal', $year)
        ->get();
    
    return [
        'hadir' => $attendances->where('status', 'hadir')->count(),
        'izin' => $attendances->where('status', 'izin')->count(),
        'sakit' => $attendances->where('status', 'sakit')->count(),
        'cuti' => $attendances->where('status', 'cuti')->count(),
        'alpa' => $attendances->where('status', 'alpa')->count(),
        'wfh' => $attendances->where('status', 'wfh')->count(),
        'total' => $attendances->count(),
    ];
}

}