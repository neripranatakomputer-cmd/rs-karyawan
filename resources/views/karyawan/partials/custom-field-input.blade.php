<!-- resources/views/karyawan/partials/custom-field-input.blade.php -->

@if($field->field_type == 'text')
    <input type="text" 
           name="custom_{{ $field->id }}" 
           class="form-control" 
           value="{{ $value }}"
           {{ $field->is_required ? 'required' : '' }}>

@elseif($field->field_type == 'textarea')
    <textarea name="custom_{{ $field->id }}" 
              class="form-control" 
              rows="3" 
              {{ $field->is_required ? 'required' : '' }}>{{ $value }}</textarea>

@elseif($field->field_type == 'number')
    <input type="number" 
           name="custom_{{ $field->id }}" 
           class="form-control" 
           value="{{ $value }}"
           {{ $field->is_required ? 'required' : '' }}>

@elseif($field->field_type == 'date')
    <input type="date" 
           name="custom_{{ $field->id }}" 
           class="form-control" 
           value="{{ $value }}"
           {{ $field->is_required ? 'required' : '' }}>

@elseif($field->field_type == 'file')
    @if($value)
    <div class="mb-2">
        <a href="{{ asset('storage/' . $value) }}" target="_blank" class="btn btn-sm btn-info">
            <i class="bi bi-file-earmark"></i> Lihat File
        </a>
    </div>
    @endif
    <input type="file" 
           name="custom_{{ $field->id }}" 
           class="form-control" 
           {{ !$value && $field->is_required ? 'required' : '' }}>

@elseif($field->field_type == 'select')
    <select name="custom_{{ $field->id }}" 
            class="form-select" 
            {{ $field->is_required ? 'required' : '' }}>
        <option value="">Pilih...</option>
        @if($field->field_options)
            @foreach(explode(',', $field->field_options) as $option)
            <option value="{{ trim($option) }}" {{ $value == trim($option) ? 'selected' : '' }}>
                {{ trim($option) }}
            </option>
            @endforeach
        @endif
    </select>
@endif