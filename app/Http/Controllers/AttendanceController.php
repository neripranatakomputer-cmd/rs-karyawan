<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('karyawan');
        
        // Filter by month & year
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $karyawanId = $request->get('karyawan_id');
        
        $query->whereMonth('tanggal', $month)
              ->whereYear('tanggal', $year);
        
        if ($karyawanId) {
            $query->where('karyawan_id', $karyawanId);
        }
        
        $attendances = $query->orderBy('tanggal', 'desc')->paginate(20);
        
        // Get karyawan list for filter
        $karyawans = Karyawan::orderBy('nama_lengkap')->get();
        
        return view('attendance.index', compact('attendances', 'karyawans', 'month', 'year', 'karyawanId'));
    }

    public function create()
    {
        $karyawans = Karyawan::orderBy('nama_lengkap')->get();
        $statusLabels = Attendance::getStatusLabels();
        
        return view('attendance.create', compact('karyawans', 'statusLabels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,cuti,alpa,wfh',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i|after:jam_masuk',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'karyawan_id.required' => 'Karyawan wajib dipilih',
            'tanggal.required' => 'Tanggal wajib diisi',
            'status.required' => 'Status wajib dipilih',
            'jam_keluar.after' => 'Jam keluar harus setelah jam masuk',
        ]);

        // Cek duplikat
        $exists = Attendance::where('karyawan_id', $validated['karyawan_id'])
                           ->where('tanggal', $validated['tanggal'])
                           ->exists();
        
        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Absensi untuk karyawan ini pada tanggal tersebut sudah ada!');
        }

        Attendance::create($validated);

        return redirect()->route('attendance.index')
            ->with('success', 'Absensi berhasil ditambahkan!');
    }

    public function edit(Attendance $attendance)
    {
        $karyawans = Karyawan::orderBy('nama_lengkap')->get();
        $statusLabels = Attendance::getStatusLabels();
        
        return view('attendance.edit', compact('attendance', 'karyawans', 'statusLabels'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,cuti,alpa,wfh',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i|after:jam_masuk',
            'keterangan' => 'nullable|string|max:500',
        ]);

        // Cek duplikat (exclude current record)
        $exists = Attendance::where('karyawan_id', $validated['karyawan_id'])
                           ->where('tanggal', $validated['tanggal'])
                           ->where('id', '!=', $attendance->id)
                           ->exists();
        
        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Absensi untuk karyawan ini pada tanggal tersebut sudah ada!');
        }

        $attendance->update($validated);

        return redirect()->route('attendance.index')
            ->with('success', 'Absensi berhasil diupdate!');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendance.index')
            ->with('success', 'Absensi berhasil dihapus!');
    }

    public function rekap(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $unit = $request->get('unit');
        
        $query = Karyawan::with(['attendances' => function($q) use ($month, $year) {
            $q->whereMonth('tanggal', $month)
              ->whereYear('tanggal', $year);
        }]);
        
        if ($unit) {
            $query->where('unit', $unit);
        }
        
        $karyawans = $query->orderBy('nama_lengkap')->get();
        
        // Get unit list for filter
        $units = Karyawan::select('unit')->distinct()->orderBy('unit')->pluck('unit');
        
        return view('attendance.rekap', compact('karyawans', 'month', 'year', 'units', 'unit'));
    }
}