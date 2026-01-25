<x-layouts.admin title="Tambah Tipe Tiket">
    <div class="container mx-auto p-6 lg:p-10 max-w-2xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Tambah Tipe Tiket</h1>
            <a href="{{ route('admin.tiket-types.index') }}" class="btn btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Kembali
            </a>
        </div>

        <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl">
            <form action="{{ route('admin.tiket-types.store') }}" method="POST" class="card-body">
                @csrf
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Nama Tipe Tiket <span class="text-red-500">*</span></span>
                    </label>
                    <input 
                        type="text" 
                        name="nama" 
                        placeholder="Contoh: VIP, Regular, Early Bird" 
                        class="input input-bordered @error('nama') input-error @enderror"
                        value="{{ old('nama') }}"
                        required />
                    @error('nama')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="card-actions justify-end mt-6">
                    <a href="{{ route('admin.tiket-types.index') }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
