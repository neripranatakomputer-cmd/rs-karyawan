<?php
// app/Http/Controllers/CustomFieldController.php

namespace App\Http\Controllers;

use App\Models\CustomField;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    public function index()
    {
        // Group fields by category
        $categories = CustomField::getCategories();
        $fieldsByCategory = [];
        
        foreach ($categories as $key => $category) {
            $fieldsByCategory[$key] = CustomField::where('category', $key)
                ->orderBy('order')
                ->get();
        }

        return view('custom-fields.index', compact('fieldsByCategory', 'categories'));
    }

    public function create()
    {
        $categories = CustomField::getCategories();
        return view('custom-fields.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'field_name' => 'required|string|max:255',
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|in:text,textarea,number,date,file,select',
            'field_options' => 'nullable|string',
            'is_required' => 'boolean',
            'order' => 'nullable|integer',
            'category' => 'required|in:data_pribadi,data_kontak,data_pendidikan,data_kepegawaian,lainnya',
        ]);

        CustomField::create($validated);

        return redirect()->route('custom-fields.index')->with('success', 'Field berhasil ditambahkan!');
    }

    public function edit(CustomField $customField)
    {
        $categories = CustomField::getCategories();
        return view('custom-fields.edit', compact('customField', 'categories'));
    }

    public function update(Request $request, CustomField $customField)
    {
        $validated = $request->validate([
            'field_name' => 'required|string|max:255',
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|in:text,textarea,number,date,file,select',
            'field_options' => 'nullable|string',
            'is_required' => 'boolean',
            'order' => 'nullable|integer',
            'category' => 'required|in:data_pribadi,data_kontak,data_pendidikan,data_kepegawaian,lainnya',
        ]);

        $customField->update($validated);

        return redirect()->route('custom-fields.index')->with('success', 'Field berhasil diupdate!');
    }

    public function destroy(CustomField $customField)
    {
        $customField->delete();
        return redirect()->route('custom-fields.index')->with('success', 'Field berhasil dihapus!');
    }
}