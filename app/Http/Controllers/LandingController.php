<?php
// app/Http/Controllers/LandingController.php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $totalKaryawan = Karyawan::count();
        $recentKaryawan = Karyawan::latest()->take(6)->get();
        
        return view('landing', compact('totalKaryawan', 'recentKaryawan'));
    }
}