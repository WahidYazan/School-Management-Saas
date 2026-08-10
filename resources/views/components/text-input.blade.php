@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'py-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm bg-white text-gray-900']) }}>
