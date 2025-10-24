@props([
    'name' => '',
    'label' => '',
    'options' => [],
    'selected' => [],
    'placeholder' => 'Select options',
    'id' => null,
    'required' => false,
    'multiple' => true,
    'disabled' => false
])

@php
    $id = $id ?? $name ?? 'select-' . uniqid();
    $selected = is_array($selected) ? $selected : (is_string($selected) ? explode(',', $selected) : []);
    $selected = array_map('intval', $selected);
@endphp

<div class="space-y-3">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-1">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <!-- Multi-select container -->
        <div class="min-h-[48px] w-full rounded-lg border border-gray-300 bg-white px-4 py-3 shadow-sm transition-all duration-200 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-100 {{ $disabled ? 'bg-gray-50 cursor-not-allowed opacity-60' : 'cursor-pointer hover:border-gray-400' }}"
             onclick="toggleDropdown('{{ $id }}')">

            <!-- Selected tags display -->
            <div id="{{ $id }}-tags" class="flex flex-wrap gap-2 min-h-[20px] items-center">
                @if(empty($selected))
                    <span class="text-gray-400 text-sm">{{ $placeholder }}</span>
                @endif
            </div>

            <!-- Dropdown arrow -->
            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200"
                     id="{{ $id }}-arrow"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        <!-- Hidden select for form submission -->
        <select name="{{ $name }}{{ $multiple ? '[]' : '' }}"
                id="{{ $id }}"
                {{ $multiple ? 'multiple' : '' }}
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                class="hidden"
                {{ $attributes->except(['class']) }}>
            @foreach($options as $value => $label)
                <option value="{{ $value }}"
                        {{ in_array($value, $selected) ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <!-- Dropdown options -->
        <div id="{{ $id }}-dropdown"
             class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-xl hidden max-h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            @foreach($options as $value => $label)
                <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer transition-colors duration-150 flex items-center justify-between border-b border-gray-100 last:border-b-0"
                     onclick="toggleOption('{{ $id }}', '{{ $value }}', '{{ addslashes($label) }}')"
                     data-value="{{ $value }}">
                    <span class="text-sm font-medium text-gray-900">{{ $label }}</span>
                    <div class="flex items-center">
                        <input type="checkbox"
                               class="w-4 h-4 text-blue-600 bg-white border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 transition-colors duration-150"
                               {{ in_array($value, $selected) ? 'checked' : '' }}
                               data-value="{{ $value }}"
                               readonly>
                    </div>
                </div>
            @endforeach

            @if(empty($options))
                <div class="px-4 py-6 text-sm text-gray-500 text-center">
                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    No options available
                </div>
            @endif
        </div>
    </div>

    <!-- Helper text -->
    @if($slot->isNotEmpty())
        <div class="text-xs text-gray-500 mt-1">
            {{ $slot }}
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeMultiSelect('{{ $id }}');
});

function initializeMultiSelect(selectId) {
    const select = document.getElementById(selectId);
    const tagsContainer = document.getElementById(selectId + '-tags');
    const dropdown = document.getElementById(selectId + '-dropdown');
    const arrow = document.getElementById(selectId + '-arrow');

    // Initial load
    updateTags(selectId);

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('#' + selectId + '-dropdown') &&
            !event.target.closest('[onclick="toggleDropdown(\'' + selectId + '\')"]')) {
            closeDropdown(selectId);
        }
    });
}

function toggleDropdown(selectId) {
    const dropdown = document.getElementById(selectId + '-dropdown');
    const arrow = document.getElementById(selectId + '-arrow');

    if (dropdown.classList.contains('hidden')) {
        openDropdown(selectId);
    } else {
        closeDropdown(selectId);
    }
}

function openDropdown(selectId) {
    const dropdown = document.getElementById(selectId + '-dropdown');
    const arrow = document.getElementById(selectId + '-arrow');

    dropdown.classList.remove('hidden');
    arrow.style.transform = 'rotate(180deg)';
}

function closeDropdown(selectId) {
    const dropdown = document.getElementById(selectId + '-dropdown');
    const arrow = document.getElementById(selectId + '-arrow');

    dropdown.classList.add('hidden');
    arrow.style.transform = 'rotate(0deg)';
}

function toggleOption(selectId, value, label) {
    const select = document.getElementById(selectId);
    const option = select.querySelector(`option[value="${value}"]`);

    if (option) {
        if (option.selected) {
            option.selected = false;
        } else {
            option.selected = true;
        }

        // Update checkbox
        const checkbox = event.target.closest('.flex').querySelector('input[type="checkbox"]');
        checkbox.checked = option.selected;

        updateTags(selectId);
    }
}

function updateTags(selectId) {
    const select = document.getElementById(selectId);
    const tagsContainer = document.getElementById(selectId + '-tags');
    const selectedOptions = Array.from(select.selectedOptions);

    tagsContainer.innerHTML = '';

    if (selectedOptions.length === 0) {
        tagsContainer.innerHTML = '<span class="text-gray-400 text-sm">Select options</span>';
        return;
    }

    selectedOptions.forEach(option => {
        const tag = document.createElement('span');
        tag.className = 'inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200 shadow-sm hover:bg-blue-100 transition-colors duration-150';
        tag.innerHTML = `
            <span class="flex-shrink-0">${option.text}</span>
            <button type="button"
                    class="inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-blue-200 transition-colors duration-150 flex-shrink-0"
                    onclick="removeTag('${selectId}', '${option.value}')">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;
        tagsContainer.appendChild(tag);
    });
}

function removeTag(selectId, value) {
    const select = document.getElementById(selectId);
    const option = select.querySelector(`option[value="${value}"]`);

    if (option) {
        option.selected = false;

        // Update checkbox in dropdown using data-value attribute
        const dropdown = document.getElementById(selectId + '-dropdown');
        const checkbox = dropdown.querySelector(`input[type="checkbox"][data-value="${value}"]`);
        
        if (checkbox) {
            checkbox.checked = false;
        }

        updateTags(selectId);
    }
}
</script>
