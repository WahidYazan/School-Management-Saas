<x-app-layout>
    @section('title', $assignment->title)
    @section('page-title', $assignment->title)

    @php
        $isStaff = auth()->user()->isTeacher() || auth()->user()->isSuperAdmin();
        $canViewRekap = $isStaff || auth()->user()->isSchoolAdmin();
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-2">
                <a href="{{ route('assignments.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">Kembali</a>
                @if ($isStaff)
                    <a href="{{ route('assignments.edit', $assignment) }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Edit</a>
                @endif
            </div>
            @if ($assignment->isLate())
                <span class="inline-flex self-start px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Tenggat lewat</span>
            @else
                <span class="inline-flex self-start px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Terbuka</span>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500">
                <span>{{ $assignment->subject?->name ?? 'Umum' }}</span>
                <span>{{ $assignment->class?->name ?? 'Semua kelas' }}</span>
                @if ($assignment->teacher)
                    <span>Dibuat oleh {{ $assignment->teacher->name }}</span>
                @endif
                <span>
                    @if ($assignment->due_at)
                        Tenggat {{ $assignment->due_at->translatedFormat('d M Y, H:i') }}
                    @else
                        Tanpa tenggat
                    @endif
                </span>
            </div>
            <div class="mt-4 text-sm text-gray-700 whitespace-pre-line">{{ $assignment->description }}</div>
        </div>

        @if (auth()->user()->isStudent())
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">
                    {{ $submission ? 'Pengumpulan Anda' : 'Kumpulkan Tugas' }}
                </h2>

                @if ($submission)
                    <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800">
                        Tugas telah dikumpulkan pada {{ $submission->submitted_at?->translatedFormat('d M Y, H:i') }}.
                    </div>
                @endif

                @if ($assignment->isLate())
                    <p class="text-sm text-red-600">Batas waktu pengumpulan telah lewat. Anda tidak dapat mengumpulkan tugas ini.</p>
                @else
                    <form method="POST" action="{{ route('assignments.submit', $assignment) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="content" :value="__('Jawaban')" />
                            <textarea id="content" name="content" rows="5" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white" placeholder="Tulis jawaban Anda di sini...">{{ old('content', $submission?->content) }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="file" :value="__('File (opsional)')" />
                            <input id="file" name="file" type="file"
                                   class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100" />
                            @if ($submission?->file_path)
                                <p class="mt-2 text-sm text-gray-500">
                                    File saat ini:
                                    <a href="{{ route('assignments.download', [$assignment, $submission]) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">{{ $submission->original_name }}</a>
                                </p>
                            @endif
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <x-primary-button>{{ $submission ? 'Perbarui Pengumpulan' : 'Kumpulkan Tugas' }}</x-primary-button>
                        </div>
                    </form>
                @endif
            </div>
        @endif

        @if ($canViewRekap)
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-2">
                    <h2 class="text-base font-semibold text-gray-900">Rekap Pengumpulan</h2>
                    <span class="text-sm text-gray-500">
                        {{ $assignment->submissions->count() }} dari {{ $students->count() }} siswa telah mengumpulkan
                    </span>
                </div>

                @if ($students->isEmpty())
                    <p class="px-6 py-10 text-center text-sm text-gray-500">Belum ada siswa yang dapat mengumpulkan tugas ini.</p>
                @else
                    <ul class="divide-y divide-gray-100">
                        @foreach ($students as $item)
                            @php $sub = $item->submission; @endphp
                            <li class="px-6 py-4 flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $item->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->class?->name ?? '-' }}</p>
                                    @if ($sub?->content)
                                        <p class="mt-2 text-sm text-gray-600 line-clamp-2 whitespace-pre-line">{{ $sub->content }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    @if ($sub)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Sudah dikumpulkan</span>
                                        <span class="text-xs text-gray-500">{{ $sub->submitted_at?->translatedFormat('d M Y, H:i') }}</span>
                                        @if ($sub->file_path)
                                            <a href="{{ route('assignments.download', [$assignment, $sub]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Unduh</a>
                                        @endif
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Belum</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
