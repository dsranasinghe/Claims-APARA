<div class="mb-4">
    <label for="{{ $name }}" class="block text-gray-700 text-sm font-bold mb-2">
        {{ $label }}
        @if(isset($required) && $required) <span class="text-red-500">*</span> @endif
    </label>
    <input type="{{ $type ?? 'text' }}" 
           name="{{ $name }}" 
           id="{{ $name }}"
           value="{{ $value ?? '' }}"
           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
           @if(isset($required) && $required) required @endif
           @if(isset($readonly) && $readonly) readonly @endif
           {{ $attributes }}>
    @error($name)
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>