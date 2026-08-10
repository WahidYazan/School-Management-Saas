<x-app-layout>
    @section('title', 'Jurusan')
    @section('page-title', 'Jurusan')

    <div class="space-y-6">
        <form method="POST" action="{{ route('majors.store') }}" class="bg-white rounded-2xl border border-gray-200 p-5">
            @csrf
            <div class="flex flex-col sm:flex-row gap-3 items-end">
                <div class="flex-1">
                    <x-input-label for="code" :value="__('Kode')" />
                    <x-text-input id="code" name="code" type="text" class="mt-1 block w-full sm:w-40" :value="old('code')" placeholder="RPL" required />
                    <x-input-error :messages="$errors->get('code')" class="mt-2" />
                </div>
                <div class="flex-[2]">
                    <x-input-label for="name" :value="__('Nama Jurusan')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" placeholder="Rekayasa Perangkat Lunak" required />
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
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Siswa</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($majors as $major)
                        <tr class="hover:bg-gray-50/70">
                            <td class="px-5 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-semibold">{{ $major->code }}</span>
                            </td>
                            <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ $major->name }}</td>
                            <td class="px-5 py-3 text-sm text-gray-700">{{ $major->students_count }}</td>
                            <td class="px-5 py-3 text-right">
                                <form method="POST" action="{{ route('majors.destroy', $major) }}" onsubmit="return confirm('Hapus jurusan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-16 text-center text-sm text-gray-500">Belum ada jurusan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $majors->links() }}
        </div>
    </div>
</x-app-layout>
