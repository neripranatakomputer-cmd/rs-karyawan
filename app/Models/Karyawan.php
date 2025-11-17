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
}