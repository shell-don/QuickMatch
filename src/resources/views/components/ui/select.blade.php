@props([
    'name',
    'label' => null,
    'placeholder' => 'Sélectionner...',
    'options' => [],
    'selected' => null,
    'error' => null,
    'required' => false,
    'multiple' => false,
])

<div class="space-y-1">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <select
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        id="{{ $name }}"
        {{ $multiple ? 'multiple' : '' }}
        {{ $required ? 'required' : '' }}
        class="block w-full rounded-md border-gray-300 shadow-sm transition-colors duration-200
            focus:border-indigo-500 focus:ring-indigo-500 focus:ring-2 focus:ring-offset-2
            {{ $error ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : '' }}
            px-4 py-2"
    >
        @if($placeholder && !$multiple)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $value => $label)
            @if(is_array($label))
                <optgroup label="{{ $value }}">
                    @foreach($label as $v => $l)
                        <option value="{{ $v }}" {{ in_array($v, old($name, $multiple ? ($selected ?? []) : [$selected])) ? 'selected' : '' }}>
                            {{ $l }}
                        </option>
                    @endforeach
                </optgroup>
            @else
                <option value="{{ $value }}" {{ in_array($value, old($name, $multiple ? ($selected ?? []) : [$selected])) ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endif
        @endforeach
    </select>
    
    @if($error)
        <p class="text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
