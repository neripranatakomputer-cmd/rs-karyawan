<?php
// app/Http/Controllers/KaryawanController.php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\CustomField;
use App\Models\CustomFieldValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KaryawanImport;
use App\Exports\KaryawanExport;
use App\Exports\KaryawanTemplateExport;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::query();

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at'); // default sort by created_at
        $sortOrder = $request->get('sort_order', 'desc'); // default desc (terbaru)

        // Validasi sort column untuk keamanan
        $allowedSortColumns = [
            'nip', 'nik', 'nama_lengkap', 'jenis_kelamin', 
            'email', 'no_hp', 'jabatan', 'unit', 
            'pendidikan_terakhir', 'tahun_lulus', 'created_at'
        ];

        if (in_array($sortBy, $allowedSortColumns)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest(); // fallback ke default
        }

        // Search (bonus feature)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%")
                  ->orWhere('unit', 'like', "%{$search}%");
            });
        }

        $karyawans = $query->paginate(10)->withQueryString(); // withQueryString untuk maintain sort & search di pagination

        return view('karyawan.index', compact('karyawans', 'sortBy', 'sortOrder'));
    }

    public function create()
    {
        $customFields = CustomField::orderBy('order')->get();
        return view('karyawan.create', compact('customFields'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|unique:karyawans',
            'nik' => 'required|digits:16|unique:karyawans',
            'nama_lengkap' => 'required|string|max:255',
            'nama_gelar' => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|unique:karyawans',
            'alamat' => 'required|string',
            'pendidikan_terakhir' => 'required|string',
            'tahun_lulus' => 'required|digits:4',
            'jabatan' => 'required|string',
            'unit' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ijazah' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'sip' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'str' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        // Upload Files
        if ($request->hasFile('foto_profil')) {
            $validated['foto_profil'] = $request->file('foto_profil')->store('foto_profil', 'public');
        }
        if ($request->hasFile('ijazah')) {
            $validated['ijazah'] = $request->file('ijazah')->store('ijazah', 'public');
        }
        if ($request->hasFile('sip')) {
            $validated['sip'] = $request->file('sip')->store('sip', 'public');
        }
        if ($request->hasFile('str')) {
            $validated['str'] = $request->file('str')->store('str', 'public');
        }

        $karyawan = Karyawan::create($validated);

        // Save Custom Fields
        $customFields = CustomField::all();
        foreach ($customFields as $field) {
            $fieldName = 'custom_' . $field->id;
            if ($request->has($fieldName)) {
                $value = $request->input($fieldName);
                
                // Handle file upload for custom fields
                if ($field->field_type === 'file' && $request->hasFile($fieldName)) {
                    $value = $request->file($fieldName)->store('custom_fields', 'public');
                }
                
                CustomFieldValue::create([
                    'karyawan_id' => $karyawan->id,
                    'custom_field_id' => $field->id,
                    'value' => $value
                ]);
            }
        }

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan!');
    }

    public function show(Karyawan $karyawan)
    {
        $customFieldValues = $karyawan->customFieldValues()->with('customField')->get();
        return view('karyawan.show', compact('karyawan', 'customFieldValues'));
    }

    public function edit(Karyawan $karyawan)
    {
        $customFields = CustomField::orderBy('order')->get();
        $customFieldValues = $karyawan->customFieldValues()->pluck('value', 'custom_field_id');
        return view('karyawan.edit', compact('karyawan', 'customFields', 'customFieldValues'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $validated = $request->validate([
            'nip' => 'required|unique:karyawans,nip,' . $karyawan->id,
            'nik' => 'required|digits:16|unique:karyawans,nik,' . $karyawan->id,
            'nama_lengkap' => 'required|string|max:255',
            'nama_gelar' => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|unique:karyawans,email,' . $karyawan->id,
            'alamat' => 'required|string',
            'pendidikan_terakhir' => 'required|string',
            'tahun_lulus' => 'required|digits:4',
            'jabatan' => 'required|string',
            'unit' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ijazah' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'sip' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'str' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        // Update Files
        if ($request->hasFile('foto_profil')) {
            if ($karyawan->foto_profil) Storage::disk('public')->delete($karyawan->foto_profil);
            $validated['foto_profil'] = $request->file('foto_profil')->store('foto_profil', 'public');
        }
        if ($request->hasFile('ijazah')) {
            if ($karyawan->ijazah) Storage::disk('public')->delete($karyawan->ijazah);
            $validated['ijazah'] = $request->file('ijazah')->store('ijazah', 'public');
        }
        if ($request->hasFile('sip')) {
            if ($karyawan->sip) Storage::disk('public')->delete($karyawan->sip);
            $validated['sip'] = $request->file('sip')->store('sip', 'public');
        }
        if ($request->hasFile('str')) {
            if ($karyawan->str) Storage::disk('public')->delete($karyawan->str);
            $validated['str'] = $request->file('str')->store('str', 'public');
        }

        $karyawan->update($validated);

        // Update Custom Fields
        $customFields = CustomField::all();
        foreach ($customFields as $field) {
            $fieldName = 'custom_' . $field->id;
            if ($request->has($fieldName)) {
                $value = $request->input($fieldName);
                
                // Handle file upload
                if ($field->field_type === 'file' && $request->hasFile($fieldName)) {
                    $oldValue = CustomFieldValue::where('karyawan_id', $karyawan->id)
                        ->where('custom_field_id', $field->id)
                        ->first();
                    if ($oldValue && $oldValue->value) {
                        Storage::disk('public')->delete($oldValue->value);
                    }
                    $value = $request->file($fieldName)->store('custom_fields', 'public');
                }
                
                CustomFieldValue::updateOrCreate(
                    [
                        'karyawan_id' => $karyawan->id,
                        'custom_field_id' => $field->id
                    ],
                    ['value' => $value]
                );
            }
        }

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diupdate!');
    }

    public function destroy(Karyawan $karyawan)
    {
        // Delete files
        if ($karyawan->foto_profil) Storage::disk('public')->delete($karyawan->foto_profil);
        if ($karyawan->ijazah) Storage::disk('public')->delete($karyawan->ijazah);
        if ($karyawan->sip) Storage::disk('public')->delete($karyawan->sip);
        if ($karyawan->str) Storage::disk('public')->delete($karyawan->str);

        // Delete custom field files
        foreach ($karyawan->customFieldValues as $cfValue) {
            if ($cfValue->customField->field_type === 'file' && $cfValue->value) {
                Storage::disk('public')->delete($cfValue->value);
            }
        }

        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil dihapus!');
    }

    public function importForm()
{
    return view('karyawan.import');
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv|max:5120'
    ]);

    try {
        $import = new KaryawanImport();
        Excel::import($import, $request->file('file'));

        $successCount = $import->getSuccessCount();
        $errorCount = $import->getErrorCount();
        $errors = $import->getErrors();

        if ($errorCount > 0) {
            $message = "Import selesai dengan {$errorCount} error. {$successCount} data berhasil diimport.";
            return redirect()->route('karyawan.index')
                ->with('warning', $message)
                ->with('import_errors', $errors);
        }

        return redirect()->route('karyawan.index')
            ->with('success', "Berhasil import {$successCount} data karyawan!");

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal import: ' . $e->getMessage());
    }
}

public function export()
{
    return Excel::download(new KaryawanExport, 'data-karyawan-' . date('Y-m-d') . '.xlsx');
}

public function downloadTemplate()
{
    return Excel::download(new KaryawanTemplateExport, 'template-import-karyawan.xlsx');
}
}