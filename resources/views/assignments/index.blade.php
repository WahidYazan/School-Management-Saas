<x-app-layout>
    @section('title', 'Tugas')
    @section('page-title', 'Tugas')

    <div class="space-y-6">
        <div class="flex items-center justify-between gap-4">
            <p class="text-sm text-gray-500">{{ $assignments->total() }} tugas</p>
            @if (auth()->user()->isTeacher() || auth()->user()->isSuperAdmin())
                <a href="{{ route('assignments.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Tugas
                </a>
            @endif
        </div>

        @if (auth()->user()->isStudent())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-semibold text-gray-900">Sudah Dikumpulkan</h2>
                        <span class="text-sm text-gray-500">{{ $collectedAssignments->count() }} tugas</span>
                    </div>

                    @forelse ($collectedAssignments as $assignment)
                        @include('assignments._card', ['assignment' => $assignment])
                    @empty
                        <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center text-sm text-gray-500">Belum ada tugas yang sudah dikumpulkan.</div>
                    @endforelse
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-semibold text-gray-900">Daftar Tugas</h2>
                        <span class="text-sm text-gray-500">{{ $assignments->total() }} tugas</span>
                    </div>

                    @forelse ($assignments as $assignment)
                        @include('assignments._card', ['assignment' => $assignment])
                    @empty
                        <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center text-sm text-gray-500">Belum ada tugas.</div>
                    @endforelse

                    <div>
                        {{ $assignments->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @forelse ($assignments as $assignment)
                    @include('assignments._card', ['assignment' => $assignment])
                @empty
                    <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center text-sm text-gray-500">Belum ada tugas.</div>
                @endforelse
            </div>

            <div>
                {{ $assignments->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
