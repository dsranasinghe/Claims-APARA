<div class="mb-4">
    <label for="{{ $name }}" class="block text-gray-700 text-sm font-bold mb-2">
        {{ $label }}
        @if(isset($required) && $required) <span class="text-red-500">*</span> @endif
    </label>
    <input type="file" 
           name="{{ $name }}" 
           id="{{ $name }}"
           class="block w-full text-sm text-gray-500
                  file:mr-4 file:py-2 file:px-4
                  file:rounded-md file:border-0
                  file:text-sm file:font-semibold
                  file:bg-blue-50 file:text-blue-700
                  hover:file:bg-blue-100"
           @if(isset($required) && $required) required @endif
           {{ $attributes }}>
    @error($name)
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
    @if(isset($currentFile) && $currentFile)
        <div class="mt-2 text-sm text-gray-500">
            Current file: <a href="{{ Storage::url($currentFile) }}" target="_blank" class="text-blue-500">View</a>
        </div>
    @endif
</div>