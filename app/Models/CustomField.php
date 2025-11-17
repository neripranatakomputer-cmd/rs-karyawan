<?php
// app/Models/CustomField.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_name', 'field_label', 'field_type', 'field_options', 'is_required', 'order', 'category'
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    // Daftar kategori yang tersedia
    public static function getCategories()
    {
        return [
            'data_pribadi' => [
                'label' => '1️⃣ Data Pribadi',
                'icon' => 'bi-person-badge',
                'color' => 'primary'
            ],
            'data_kontak' => [
                'label' => '2️⃣ Data Kontak',
                'icon' => 'bi-telephone',
                'color' => 'success'
            ],
            'data_pendidikan' => [
                'label' => '3️⃣ Data Pendidikan',
                'icon' => 'bi-mortarboard',
                'color' => 'info'
            ],
            'data_kepegawaian' => [
                'label' => '4️⃣ Data Kepegawaian',
                'icon' => 'bi-briefcase',
                'color' => 'warning'
            ],
            'lainnya' => [
                'label' => '➕ Data Lainnya',
                'icon' => 'bi-ui-checks',
                'color' => 'secondary'
            ],
        ];
    }

    // Get category info
    public function getCategoryInfo()
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? $categories['lainnya'];
    }
}