<x-layouts.admin title="Manajemen Tipe Tiket">
    <div class="container mx-auto p-6 lg:p-10">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tipe Tiket</h1>
                <p class="text-sm text-gray-500">Kelola variasi tiket yang tersedia dalam sistem.</p>
            </div>
            <a href="{{ route('admin.tiket-types.create') }}" class="btn btn-primary text-white shadow-md gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah Baru
            </a>
        </div>

        @if($message = Session::get('success'))
            <div class="alert alert-success shadow-lg mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ $message }}</span>
            </div>
        @endif

        <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold">
                        <tr>
                            <th class="py-4 pl-6">No</th>
                            <th>Nama Tipe Tiket</th>
                            <th class="text-center">Dibuat Pada</th>
                            <th class="text-center pr-6">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($tiketTypes as $type)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="pl-6 font-medium text-gray-500">{{ $loop->iteration }}</td>
                            <td class="font-bold text-gray-800 text-base">{{ $type->nama }}</td>
                            <td class="text-center text-sm text-gray-500">{{ $type->created_at->format('d M Y') }}</td>
                            <td class="pr-6">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.tiket-types.edit', $type->id) }}" class="btn btn-square btn-ghost btn-sm text-blue-600 hover:bg-blue-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    <form action="{{ route('admin.tiket-types.destroy', $type->id) }}" method="POST" onsubmit="return confirm('Hapus tipe tiket ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-square btn-ghost btn-sm text-red-600 hover:bg-red-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-10 text-gray-400">Belum ada tipe tiket.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin>
