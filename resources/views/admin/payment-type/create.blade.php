<x-layouts.admin title="Tambah Tipe Pembayaran">
    <div class="container mx-auto p-6 lg:p-10">
        <div class="max-w-xl mx-auto">

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Metode Baru</h1>
                <a href="{{ route('admin.payment-types.index') }}" class="btn btn-ghost btn-sm text-gray-500">
                    &larr; Kembali
                </a>
            </div>

            <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl">
                <div class="card-body p-8">
                    <form action="{{ route('admin.payment-types.store') }}" method="POST">
                        @csrf

                        <div class="form-control w-full mb-6">
                            <label class="label font-semibold text-gray-700">Nama Metode Pembayaran</label>
                            <input type="text" name="name" placeholder="Contoh: Transfer Bank BCA, E-Wallet OVO" class="input input-bordered w-full focus:input-primary" required />
                            @error('name')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary text-white w-full shadow-md">
                            Simpan Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>