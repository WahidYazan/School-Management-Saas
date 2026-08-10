<x-app-layout>
    @section('title', 'Mata Pelajaran')
    @section('page-title', 'Mata Pelajaran')

    <div class="space-y-6">
        <form method="POST" action="{{ route('subjects.store') }}" class="bg-white rounded-2xl border border-gray-200 p-5">
            @csrf
            <div class="flex flex-col sm:flex-row gap-3 items-end">
                <div>
                    <x-input-label for="code" :value="__('Kode')" />
                    <x-text-input id="code" name="code" type="text" class="mt-1 block w-full sm:w-40" :value="old('code')" placeholder="MTK" required />
                    <x-input-error :messages="$errors->get('code')" class="mt-2" />
                </div>
                <div class="flex-[2]">
                    <x-input-label for="name" :value="__('Nama Mapel')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" placeholder="Matematika" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div>
                    <x-primary-button>Tambah</x-primary-button>
                </div>
            </div>
        </form>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Pengajar</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($subjects as $subject)
                        <tr class="hover:bg-gray-50/70">
                            <td class="px-5 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-lg bg-violet-100 text-violet-700 text-xs font-semibold">{{ $subject->code }}</span>
                            </td>
                            <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ $subject->name }}</td>
                            <td class="px-5 py-3 text-sm text-gray-700">{{ $subject->teachers_count }}</td>
                            <td class="px-5 py-3 text-right">
                                <form method="POST" action="{{ route('subjects.destroy', $subject) }}" onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-16 text-center text-sm text-gray-500">Belum ada mata pelajaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $subjects->links() }}
        </div>
    </div>
</x-app-layout>
