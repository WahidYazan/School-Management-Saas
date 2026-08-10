<x-app-layout>
    @section('title', 'Pengumuman')
    @section('page-title', 'Pengumuman')

    <div class="space-y-6">
        <div class="flex items-center justify-between gap-4">
            <p class="text-sm text-gray-500">{{ $announcements->total() }} pengumuman</p>
            @if (auth()->user()->canManageSchoolData())
                <a href="{{ route('announcements.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Pengumuman
                </a>
            @endif
        </div>

        <div class="space-y-4">
            @forelse ($announcements as $announcement)
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">{{ $announcement->title }}</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Oleh {{ $announcement->author?->name ?? 'Admin' }} &middot; {{ $announcement->created_at->translatedFormat('d M Y, H:i') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <div class="flex flex-wrap gap-1 justify-end">
                                @foreach (($announcement->audience ?? ['all']) as $aud)
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                        {{ $aud === 'all' ? 'Semua' : ucfirst($aud) }}
                                    </span>
                                @endforeach
                            </div>
                            @if (auth()->user()->canManageSchoolData())
                                <form method="POST" action="{{ route('announcements.destroy', $announcement) }}" onsubmit="return confirm('Hapus pengumuman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <p class="mt-3 text-sm text-gray-700 whitespace-pre-line">{{ $announcement->body }}</p>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center text-sm text-gray-500">Belum ada pengumuman.</div>
            @endforelse
        </div>

        <div>
            {{ $announcements->links() }}
        </div>
    </div>
</x-app-layout>
