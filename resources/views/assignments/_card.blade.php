<div class="bg-white rounded-2xl border border-gray-200 p-6">
    <div class="flex items-start justify-between gap-4">
        <a href="{{ route('assignments.show', $assignment) }}" class="group min-w-0">
            <h3 class="text-base font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $assignment->title }}</h3>
            <p class="mt-1 text-sm text-gray-500">
                {{ $assignment->subject?->name ?? 'Umum' }}
                @if ($assignment->class)
                    &middot; {{ $assignment->class->name }}
                @else
                    &middot; Semua kelas
                @endif
            </p>
        </a>
        <div class="flex items-center gap-2 shrink-0">
            @if ($assignment->isLate())
                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Tenggat lewat</span>
            @else
                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Terbuka</span>
            @endif
            @if (auth()->user()->isTeacher() || auth()->user()->isSuperAdmin())
                <a href="{{ route('assignments.edit', $assignment) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Edit</a>
                <form method="POST" action="{{ route('assignments.destroy', $assignment) }}" onsubmit="return confirm('Hapus tugas ini beserta seluruh pengumpulannya?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Hapus</button>
                </form>
            @endif
        </div>
    </div>

    <p class="mt-3 text-sm text-gray-700 line-clamp-2">{{ $assignment->description }}</p>

    <div class="mt-4 flex flex-wrap items-center gap-4 text-xs text-gray-500">
        <span class="inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            @if ($assignment->due_at)
                Tenggat {{ $assignment->due_at->translatedFormat('d M Y, H:i') }}
            @else
                Tanpa tenggat
            @endif
        </span>
        @if (auth()->user()->isTeacher() || auth()->user()->isSuperAdmin())
            <span class="inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                {{ $assignment->submissions_count }} pengumpulan
            </span>
        @elseif (auth()->user()->isStudent())
            <span class="inline-flex items-center gap-1">
                @if ($assignment->submissions->isNotEmpty())
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium text-emerald-600">Sudah dikumpulkan</span>
                @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-gray-500">Belum dikumpulkan</span>
                @endif
            </span>
        @endif
    </div>
</div>
