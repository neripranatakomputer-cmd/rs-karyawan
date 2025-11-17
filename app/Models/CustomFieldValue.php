<?php
// app/Models/CustomFieldValue.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
    use HasFactory;

    protected $fillable = ['karyawan_id', 'custom_field_id', 'value'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function customField()
    {
        return $this->belongsTo(CustomField::class);
    }
}