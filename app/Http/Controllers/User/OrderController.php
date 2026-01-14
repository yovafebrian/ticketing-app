<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Tiket;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('detailOrders.tiket', 'event')
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'items' => 'required|array|min:1',
            'items.*.tiket_id' => 'required|exists:tikets,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        $user = $request->user();

        DB::beginTransaction();

        try {
            $total = 0;

            $order = Order::create([
                'user_id' => $user->id,
                'event_id' => $data['event_id'],
                'order_date' => Carbon::now(),
                'total_harga' => 0,
            ]);

            foreach ($data['items'] as $it) {
                $tiket = Tiket::lockForUpdate()->find($it['tiket_id']);
                if (!$tiket) {
                    throw new \Exception('Tiket tidak ditemukan');
                }

                if ($it['jumlah'] > $tiket->stok) {
                    throw new \Exception("Stok untuk {$tiket->tipe} tidak cukup");
                }

                $subtotal = $it['jumlah'] * ($tiket->harga ?? 0);

                $order->detailOrders()->create([
                    'tiket_id' => $tiket->id,
                    'jumlah' => $it['jumlah'],
                    'subtotal_harga' => $subtotal,
                ]);

                $tiket->stok -= $it['jumlah'];
                $tiket->save();

                $total += $subtotal;
            }

            $order->total_harga = $total;
            $order->save();

            DB::commit();

            return response()->json(['redirect' => route('orders.index')]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('detailOrders.tiket', 'event');

        return view('orders.show', compact('order'));
    }
}
