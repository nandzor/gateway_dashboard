@props([
    'name' => '',
    'label' => '',
    'value' => [],
    'placeholder' => 'Enter IP address and press Enter',
    'id' => null,
    'required' => false,
    'disabled' => false
])

@php
    $id = $id ?? $name ?? 'ip-tags-' . uniqid();
    $value = is_array($value) ? $value : (is_string($value) ? explode(',', $value) : []);
    $value = array_filter(array_map('trim', $value)); // Remove empty values and trim whitespace
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
        <!-- IP Tags Input Container -->
        <div class="min-h-[48px] w-full rounded-lg border border-gray-300 bg-white px-4 py-3 shadow-sm transition-all duration-200 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-100 {{ $disabled ? 'bg-gray-50 cursor-not-allowed opacity-60' : 'hover:border-gray-400' }}"
             onclick="focusInput('{{ $id }}')">
            <!-- Flex container for tags and input -->
            <div class="flex flex-wrap items-center gap-2 min-h-[20px]">
                <!-- IP Tags Display -->
                <div id="{{ $id }}-tags" class="flex flex-wrap gap-2 items-center">
                    <!-- Tags will be dynamically inserted here -->
                </div>
                <!-- Input Field -->
                <input type="text"
                       id="{{ $id }}-input"
                       class="flex-1 min-w-0 bg-transparent border-none outline-none text-sm {{ $disabled ? 'cursor-not-allowed' : '' }}"
                       placeholder="{{ $placeholder }}"
                       {{ $disabled ? 'disabled' : '' }}
                       onkeydown="handleKeyDown(event, '{{ $id }}')"
                       onblur="addCurrentInput('{{ $id }}')"
                       oninput="handleInput(event, '{{ $id }}')"
                       autocomplete="off">
            </div>
            <!-- Hidden input for form submission -->
            <input type="hidden"
                   name="{{ $name }}"
                   id="{{ $id }}-hidden"
                   value="{{ implode(',', $value) }}">
        </div>

        <!-- Helper text -->
        <div class="mt-1 text-xs text-gray-500">
            Enter IP addresses (e.g., 192.168.1.1) and press Enter to add as tags
        </div>

        <!-- Error message container -->
        <div id="{{ $id }}-error" class="mt-1 text-xs text-red-600 hidden"></div>
    </div>

    <!-- Helper text slot -->
    @if($slot->isNotEmpty())
        <div class="text-xs text-gray-500 mt-1">
            {{ $slot }}
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeIpTags('{{ $id }}');
});

function initializeIpTags(containerId) {
    const input = document.getElementById(containerId + '-input');
    const tagsContainer = document.getElementById(containerId + '-tags');
    const hiddenInput = document.getElementById(containerId + '-hidden');

    // Initial load
    updateIpTags(containerId);
    updateInputVisibility(containerId);

    // Prevent form submission on Enter in input
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });
}

function focusInput(containerId) {
    const input = document.getElementById(containerId + '-input');
    if (input && !input.disabled) {
        input.focus();
    }
}

function handleKeyDown(event, containerId) {
    if (event.key === 'Enter') {
        event.preventDefault();
        addCurrentInput(containerId);
    } else if (event.key === 'Backspace') {
        const input = event.target;
        if (input.value === '') {
            // Remove last tag if input is empty
            removeLastTag(containerId);
        }
    } else if (event.key === 'Escape') {
        // Clear input on Escape
        event.target.value = '';
        hideError(containerId);
    }
}

function handleInput(event, containerId) {
    const value = event.target.value.trim();
    hideError(containerId);
    // Show real-time validation
    if (value && !isValidIpAddress(value)) {
        showError(containerId, 'Invalid IP format');
    } else {
        hideError(containerId);
    }
}

function addCurrentInput(containerId) {
    const input = document.getElementById(containerId + '-input');
    const value = input.value.trim();
    if (value && isValidIpAddress(value)) {
        addIpTag(containerId, value);
        input.value = '';
        updateInputVisibility(containerId);
        hideError(containerId);
    } else if (value && !isValidIpAddress(value)) {
        showError(containerId, 'Invalid IP address format');
    }
}

function isValidIpAddress(ip) {
    const ipRegex = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
    return ipRegex.test(ip);
}

function addIpTag(containerId, ip) {
    const hiddenInput = document.getElementById(containerId + '-hidden');
    const currentValues = hiddenInput.value ? hiddenInput.value.split(',').map(v => v.trim()).filter(v => v) : [];
    // Check if IP already exists
    if (currentValues.includes(ip)) {
        showError(containerId, 'IP address already exists');
        return;
    }
    // Add to values
    currentValues.push(ip);
    hiddenInput.value = currentValues.join(',');
    updateIpTags(containerId);
}

function removeIpTag(containerId, ip) {
    const hiddenInput = document.getElementById(containerId + '-hidden');
    const currentValues = hiddenInput.value ? hiddenInput.value.split(',').map(v => v.trim()).filter(v => v) : [];
    // Remove IP from values
    const filteredValues = currentValues.filter(value => value !== ip);
    hiddenInput.value = filteredValues.join(',');
    updateIpTags(containerId);
    updateInputVisibility(containerId);
}

function removeLastTag(containerId) {
    const hiddenInput = document.getElementById(containerId + '-hidden');
    const currentValues = hiddenInput.value ? hiddenInput.value.split(',').map(v => v.trim()).filter(v => v) : [];
    if (currentValues.length > 0) {
        currentValues.pop();
        hiddenInput.value = currentValues.join(',');
        updateIpTags(containerId);
        updateInputVisibility(containerId);
    }
}

function updateIpTags(containerId) {
    const tagsContainer = document.getElementById(containerId + '-tags');
    const hiddenInput = document.getElementById(containerId + '-hidden');
    const currentValues = hiddenInput.value ? hiddenInput.value.split(',').map(v => v.trim()).filter(v => v) : [];
    tagsContainer.innerHTML = '';
    // Don't show placeholder text in tags container - let input handle it
    if (currentValues.length === 0) {
        return;
    }
    currentValues.forEach(ip => {
        const tag = document.createElement('span');
        tag.className = 'inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium bg-green-50 text-green-700 border border-green-200 shadow-sm hover:bg-green-100 transition-colors duration-150';
        tag.innerHTML = `
            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span class="flex-shrink-0 font-mono">${ip}</span>
            <button type="button"
                    class="inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-green-200 transition-colors duration-150 flex-shrink-0"
                    onclick="removeIpTag('${containerId}', '${ip}')">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;
        tagsContainer.appendChild(tag);
    });
}

function updateInputVisibility(containerId) {
    const input = document.getElementById(containerId + '-input');
    const hiddenInput = document.getElementById(containerId + '-hidden');
    const currentValues = hiddenInput.value ? hiddenInput.value.split(',').map(v => v.trim()).filter(v => v) : [];
    if (currentValues.length === 0) {
        input.placeholder = 'Enter IP address and press Enter';
    } else {
        input.placeholder = 'Add another IP...';
    }
}

function showError(containerId, message) {
    const errorDiv = document.getElementById(containerId + '-error');
    errorDiv.textContent = message;
    errorDiv.classList.remove('hidden');
}

function hideError(containerId) {
    const errorDiv = document.getElementById(containerId + '-error');
    errorDiv.classList.add('hidden');
}
</script>
