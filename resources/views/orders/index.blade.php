<x-layouts.app>
    <section class="max-w-7xl mx-auto py-12 px-6">
        <h1 class="text-2xl font-bold">Pesanan Saya</h1>

        <div class="mt-6 space-y-4">
            @forelse($orders as $order)
                <div class="card p-4">
                    <div class="flex justify-between items-center">
                        <div>#{{ $order->id }} • {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->translatedFormat('d F Y, H:i') : '' }}</div>
                        <div class="font-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                    </div>

                    <div class="mt-2 text-sm text-gray-700 space-y-1">
                        @foreach($order->detailOrders as $d)
                            <div class="flex justify-between">
                                <div>{{ $d->tiket->tipe }} x {{ $d->jumlah }}</div>
                                <div>Rp {{ number_format($d->subtotal_harga, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Belum ada pesanan.</div>
            @endforelse
        </div>
    </section>
</x-layouts.app>
