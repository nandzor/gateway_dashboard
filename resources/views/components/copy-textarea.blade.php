@props([
    'name' => '',
    'value' => '',
    'placeholder' => '',
    'label' => '',
    'hint' => '',
    'id' => null,
    'readonly' => true,
    'rows' => 3
])

@php
    $id = $id ?? $name ?? 'textarea-' . uniqid();
    $readonly = $readonly ? 'readonly' : '';
@endphp

<div class="space-y-1">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <div class="absolute top-2 right-2">
            <button type="button"
                    onclick="copyToClipboard('{{ $id }}')"
                    class="flex items-center justify-center w-6 h-6 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded transition-all duration-200"
                    title="Copy to clipboard">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                </svg>
            </button>
        </div>
        <textarea name="{{ $name }}"
                  id="{{ $id }}"
                  placeholder="{{ $placeholder }}"
                  rows="{{ $rows }}"
                  {{ $readonly }}
                  class="block w-full rounded-md border-gray-300 pr-10 text-sm font-mono resize-none {{ $readonly ? 'bg-gray-50 text-gray-600' : 'bg-white text-gray-900' }} shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  {{ $attributes->except(['class']) }}>{{ $value }}</textarea>
    </div>

</div>
