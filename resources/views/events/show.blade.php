<x-layouts.app>
    @push('meta')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush

    <section class="max-w-7xl mx-auto py-12 px-6">
        <nav class="mb-6">
            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="{{ route('home') }}" class="link link-hover">Beranda</a></li>
                    <li><a href="#" class="link link-hover">Event</a></li>
                    <li class="font-semibold">{{ $event->judul }}</li>
                </ul>
            </div>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="card bg-base-100 shadow-xl overflow-hidden">
                    <figure>
                        <img src="{{ $event->gambar ? asset('images/events/' . $event->gambar) : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
                             alt="{{ $event->judul }}" class="w-full h-96 object-cover" />
                    </figure>
                    <div class="card-body">
                        <div class="flex flex-wrap gap-2 mb-2">
                            <div class="badge badge-primary">{{ $event->kategori?->nama ?? 'Umum' }}</div>
                            <div class="badge badge-ghost">{{ $event->user?->name ?? 'Penyelenggara' }}</div>
                        </div>
                        
                        <h1 class="card-title text-3xl font-black">{{ $event->judul }}</h1>
                        <p class="text-gray-500 flex items-center gap-2 mt-2">
                            <span>📅 {{ \Carbon\Carbon::parse($event->tanggal_waktu)->locale('id')->translatedFormat('d F Y, H:i') }}</span>
                            <span>•</span>
                            <span>📍 {{ $event->lokasi }}</span>
                        </p>

                        <div class="divider">Deskripsi</div>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $event->deskripsi }}</p>

                        <div class="divider">Pilih Tiket</div>
                        <div class="space-y-4">
                            @forelse($event->tikets as $tiket)
                                <div class="flex flex-col md:flex-row md:items-center justify-between p-4 border rounded-xl hover:border-primary transition-colors bg-base-50">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-lg">{{ $tiket->tipe }}</h4>
                                        <p class="text-sm text-gray-500">Sisa stok: <span id="stock-{{ $tiket->id }}" class="font-medium text-warning">{{ $tiket->stok }}</span></p>

                                        
                                        @if($tiket->stok < 2)
                                            <p class="text-xs mt-1 text-red-500 font-semibold">⚠️ Tiket hampir habis</p>
                                        @endif
                                        @if($tiket->keterangan)
                                            <p class="text-xs mt-1 text-gray-400 italic">{{ $tiket->keterangan }}</p>
                                        @endif
                                    </div>

                                    <div class="mt-4 md:mt-0 md:text-right">
                                        <div class="text-xl font-extrabold text-primary mb-2">
                                            {{ $tiket->harga > 0 ? 'Rp ' . number_format($tiket->harga, 0, ',', '.') : 'Gratis' }}
                                        </div>

                                        <div class="flex items-center justify-start md:justify-end gap-3">
                                            <button type="button" class="btn btn-circle btn-xs btn-outline" data-action="dec" data-id="{{ $tiket->id }}">−</button>
                                            <input id="qty-{{ $tiket->id }}" type="number" min="0" max="{{ $tiket->stok }}" 
                                                value="0" class="input input-bordered input-sm w-16 text-center font-bold"
                                                data-id="{{ $tiket->id }}" />
                                            <button type="button" class="btn btn-circle btn-xs btn-outline" data-action="inc" data-id="{{ $tiket->id }}">+</button>
                                        </div>
                                        <div class="text-xs text-gray-400 mt-2">Subtotal: <span id="subtotal-{{ $tiket->id }}">Rp 0</span></div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-warning">
                                    <span>Tiket belum tersedia untuk acara ini.</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <aside class="lg:col-span-1">
                <div class="card sticky top-24 bg-base-100 shadow-xl border border-base-200">
                    <div class="card-body">
                        <h4 class="card-title text-lg border-b pb-2">Ringkasan Pembelian</h4>

                        <div class="space-y-3 mt-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Total Tiket</span>
                                <span id="summaryItems" class="font-bold">0</span>
                            </div>
                            <div id="selectedList" class="space-y-2 text-xs border-y py-3 my-3">
                                <p class="text-gray-400 italic">Belum ada tiket dipilih</p>
                            </div>
                            <div class="flex justify-between items-end">
                                <span class="text-sm font-medium">Total Bayar</span>
                                <span id="summaryTotal" class="text-2xl font-black text-primary">Rp 0</span>
                            </div>
                        </div>

                        @auth
                            <button id="checkoutButton" class="btn btn-primary btn-block mt-6" onclick="openCheckout()" disabled>
                                Checkout Sekarang
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline btn-primary btn-block mt-6">
                                Login untuk Membeli
                            </a>
                        @endauth
                    </div>
                </div>
            </aside>
        </div>

        <dialog id="checkout_modal" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box">
                <h3 class="font-bold text-xl mb-4">Konfirmasi Pesanan</h3>
                <div id="modalItems" class="space-y-3 border-b pb-4">
                    </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-bold">Total Pembayaran</span>
                    <span class="font-extrabold text-xl text-primary" id="modalTotal">Rp 0</span>
                </div>
                <div class="modal-action">
                    <form method="dialog">
                        <button class="btn btn-ghost">Batal</button>
                    </form>
                    <button type="button" class="btn btn-primary px-8" id="confirmCheckout">Konfirmasi & Bayar</button>
                </div>
            </div>
        </dialog>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formatRupiah = (val) => 'Rp ' + Number(val).toLocaleString('id-ID');

            const tickets = {
                @foreach ($event->tikets as $tiket)
                    "{{ $tiket->id }}": {
                        id: {{ $tiket->id }},
                        price: {{ $tiket->harga ?? 0 }},
                        stock: {{ $tiket->stok }},
                        tipe: "{{ $tiket->tipe }}"
                    },
                @endforeach
            };

            const summaryItemsEl = document.getElementById('summaryItems');
            const summaryTotalEl = document.getElementById('summaryTotal');
            const selectedListEl = document.getElementById('selectedList');
            const checkoutButton = document.getElementById('checkoutButton');

            function updateSummary() {
                let totalQty = 0;
                let totalPrice = 0;
                let selectedHtml = '';

                Object.values(tickets).forEach(t => {
                    const input = document.getElementById('qty-' + t.id);
                    if (!input) return;
                    
                    const qty = parseInt(input.value) || 0;
                    if (qty > 0) {
                        totalQty += qty;
                        totalPrice += qty * t.price;
                        selectedHtml += `
                            <div class="flex justify-between items-center">
                                <span>${t.tipe} <span class="text-gray-400">x${qty}</span></span>
                                <span class="font-semibold">${formatRupiah(qty * t.price)}</span>
                            </div>`;
                    }
                });

                summaryItemsEl.textContent = totalQty;
                summaryTotalEl.textContent = formatRupiah(totalPrice);
                selectedListEl.innerHTML = selectedHtml || '<p class="text-gray-400 italic">Belum ada tiket dipilih</p>';
                if(checkoutButton) checkoutButton.disabled = totalQty === 0;
            }

            // Tombol Tambah/Kurang
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('[data-action]');
                if (!btn) return;

                const id = btn.dataset.id;
                const input = document.getElementById('qty-' + id);
                const info = tickets[id];
                let val = parseInt(input.value) || 0;

                if (btn.dataset.action === 'inc' && val < info.stock) val++;
                else if (btn.dataset.action === 'dec' && val > 0) val--;

                input.value = val;
                document.getElementById('subtotal-' + id).textContent = formatRupiah(val * info.price);
                updateSummary();
            });

            // Input Manual
            document.querySelectorAll('input[id^="qty-"]').forEach(input => {
                input.addEventListener('change', (e) => {
                    const id = e.target.dataset.id;
                    const info = tickets[id];
                    let val = parseInt(e.target.value) || 0;

                    if (val < 0) val = 0;
                    if (val > info.stock) val = info.stock;
                    
                    e.target.value = val;
                    document.getElementById('subtotal-' + id).textContent = formatRupiah(val * info.price);
                    updateSummary();
                });
            });

            window.openCheckout = function() {
                const modal = document.getElementById('checkout_modal');
                const modalItems = document.getElementById('modalItems');
                const modalTotal = document.getElementById('modalTotal');
                
                let itemsHtml = '';
                let total = 0;

                Object.values(tickets).forEach(t => {
                    const qty = parseInt(document.getElementById('qty-' + t.id).value) || 0;
                    if (qty > 0) {
                        itemsHtml += `
                            <div class="flex justify-between text-sm">
                                <span>${t.tipe} (x${qty})</span>
                                <span class="font-bold">${formatRupiah(qty * t.price)}</span>
                            </div>`;
                        total += qty * t.price;
                    }
                });

                modalItems.innerHTML = itemsHtml;
                modalTotal.textContent = formatRupiah(total);
                modal.showModal();
            };

            document.getElementById('confirmCheckout')?.addEventListener('click', async () => {
                const btn = document.getElementById('confirmCheckout');
                const originalText = btn.textContent;
                
                const items = [];
                Object.values(tickets).forEach(t => {
                    const qty = parseInt(document.getElementById('qty-' + t.id).value) || 0;
                    if (qty > 0) items.push({ tiket_id: t.id, jumlah: qty });
                });

                btn.disabled = true;
                btn.innerHTML = '<span class="loading loading-spinner loading-xs"></span> Memproses...';

                try {
                    const response = await fetch("{{ route('orders.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        },
                        body: JSON.stringify({ event_id: {{ $event->id }}, items })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        window.location.href = data.redirect || '{{ route('orders.index') }}';
                    } else {
                        throw new Error(data.message || 'Gagal membuat pesanan');
                    }
                } catch (err) {
                    alert(err.message);
                    btn.disabled = false;
                    btn.textContent = originalText;
                }
            });
        });
    </script>
</x-layouts.app>