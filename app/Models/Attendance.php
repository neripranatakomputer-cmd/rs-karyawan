<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'status',
        'jam_masuk',
        'jam_keluar',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relasi ke Karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    // Status labels
    public static function getStatusLabels()
    {
        return [
            'hadir' => ['label' => 'Hadir', 'color' => 'success', 'icon' => 'check-circle'],
            'izin' => ['label' => 'Izin', 'color' => 'warning', 'icon' => 'file-text'],
            'sakit' => ['label' => 'Sakit', 'color' => 'danger', 'icon' => 'heart-pulse'],
            'cuti' => ['label' => 'Cuti', 'color' => 'info', 'icon' => 'calendar-x'],
            'alpa' => ['label' => 'Alpa', 'color' => 'dark', 'icon' => 'x-circle'],
            'wfh' => ['label' => 'WFH', 'color' => 'primary', 'icon' => 'house'],
        ];
    }

    // Get status info
    public function getStatusInfo()
    {
        $labels = self::getStatusLabels();
        return $labels[$this->status] ?? $labels['hadir'];
    }

    // Scope untuk filter
    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('tanggal', $month)
                     ->whereYear('tanggal', $year);
    }

    public function scopeByKaryawan($query, $karyawanId)
    {
        return $query->where('karyawan_id', $karyawanId);
    }
}