<!-- resources/views/custom-fields/index.blade.php -->
@extends('layouts.app')

@section('title', 'Custom Fields')
@section('page-title', 'Manajemen Custom Fields')

@section('content')
<style>
    .category-card {
        border-left: 4px solid;
        transition: all 0.3s;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .category-card.primary { border-left-color: #667eea; }
    .category-card.success { border-left-color: #10b981; }
    .category-card.info { border-left-color: #06b6d4; }
    .category-card.warning { border-left-color: #f59e0b; }
    .category-card.secondary { border-left-color: #6c757d; }
    
    .field-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
    }
</style>

<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>
    <strong>Custom Fields</strong> memungkinkan Anda menambahkan field tambahan yang otomatis dikelompokkan berdasarkan kategori data.
</div>

<div class="mb-3">
    <a href="{{ route('custom-fields.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Field Baru
    </a>
</div>

<!-- Display by Categories -->
@foreach($categories as $categoryKey => $categoryInfo)
    @php
        $fields = $fieldsByCategory[$categoryKey] ?? collect();
    @endphp
    
    <div class="card category-card {{ $categoryInfo['color'] }} mb-4">
        <div class="card-header bg-{{ $categoryInfo['color'] }} bg-opacity-10">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="{{ $categoryInfo['icon'] }} me-2"></i>
                    {{ $categoryInfo['label'] }}
                </h5>
                <span class="badge bg-{{ $categoryInfo['color'] }}">
                    {{ $fields->count() }} Fields
                </span>
            </div>
        </div>
        <div class="card-body">
            @if($fields->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">Order</th>
                            <th style="width: 20%">Field Name</th>
                            <th style="width: 25%">Label</th>
                            <th style="width: 15%">Type</th>
                            <th style="width: 10%">Required</th>
                            <th style="width: 15%">Options</th>
                            <th style="width: 10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fields as $field)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark">{{ $field->order }}</span>
                            </td>
                            <td>
                                <code class="text-primary">{{ $field->field_name }}</code>
                            </td>
                            <td>
                                <strong>{{ $field->field_label }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    <i class="bi bi-{{ 
                                        $field->field_type == 'text' ? 'input-cursor-text' : 
                                        ($field->field_type == 'textarea' ? 'textarea' : 
                                        ($field->field_type == 'number' ? '123' : 
                                        ($field->field_type == 'date' ? 'calendar-date' : 
                                        ($field->field_type == 'file' ? 'file-earmark' : 'ui-checks'))))
                                    }}"></i>
                                    {{ ucfirst($field->field_type) }}
                                </span>
                            </td>
                            <td>
                                @if($field->is_required)
                                <span class="badge bg-danger">
                                    <i class="bi bi-asterisk"></i> Required
                                </span>
                                @else
                                <span class="badge bg-secondary">Optional</span>
                                @endif
                            </td>
                            <td>
                                @if($field->field_options)
                                <small class="text-muted">{{ Str::limit($field->field_options, 20) }}</small>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('custom-fields.edit', $field) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('custom-fields.destroy', $field) }}" 
                                          method="POST" 
                                          class="d-inline" 
                                          onsubmit="return confirm('Yakin ingin menghapus field ini? Data terkait akan ikut terhapus!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <i class="bi bi-inbox fs-1 text-muted mb-2 d-block"></i>
                <p class="text-muted">Belum ada custom field di kategori ini</p>
                <a href="{{ route('custom-fields.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Field
                </a>
            </div>
            @endif
        </div>
    </div>
@endforeach

<!-- Info Card -->
<div class="card border-info">
    <div class="card-body">
        <h6 class="text-info mb-3">
            <i class="bi bi-lightbulb me-2"></i>Cara Kerja Kategorisasi
        </h6>
        <ul class="mb-0">
            <li class="mb-2">Saat menambah field baru, pilih kategori yang sesuai</li>
            <li class="mb-2">Field akan otomatis muncul di section kategori yang dipilih</li>
            <li class="mb-2">Order menentukan urutan field dalam kategori (angka kecil = atas)</li>
            <li class="mb-2">Field dapat dipindah kategori dengan edit field</li>
            <li class="mb-0">Kategori kosong tetap ditampilkan untuk referensi</li>
        </ul>
    </div>
</div>
@endsection