<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    // Menampilkan daftar tipe pembayaran
    public function index()
    {
        $paymentTypes = PaymentType::latest()->get();
        return view('admin.payment-type.index', compact('paymentTypes'));
    }

    // Menampilkan form tambah
    public function create()
    {
        return view('admin.payment-type.create');
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:payment_types,name',
        ], [
            'name.required' => 'Nama tipe pembayaran wajib diisi.',
            'name.unique' => 'Tipe pembayaran ini sudah ada.',
        ]);

        PaymentType::create($request->all());

        return redirect()->route('admin.payment-types.index')
                         ->with('success', 'Tipe pembayaran berhasil ditambahkan!');
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $paymentType = PaymentType::findOrFail($id);
        return view('admin.payment-type.edit', compact('paymentType'));
    }

    // Update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:payment_types,name,'.$id,
        ]);

        $paymentType = PaymentType::findOrFail($id);
        $paymentType->update($request->all());

        return redirect()->route('admin.payment-types.index')
                         ->with('success', 'Tipe pembayaran berhasil diperbarui!');
    }

    // Hapus data
    public function destroy($id)
    {
        $paymentType = PaymentType::findOrFail($id);
        $paymentType->delete();

        return redirect()->route('admin.payment-types.index')
                         ->with('success', 'Tipe pembayaran berhasil dihapus!');
    }
}
