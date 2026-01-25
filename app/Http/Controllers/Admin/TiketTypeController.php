<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TiketType;
use Illuminate\Http\Request;

class TiketTypeController extends Controller
{
    // Menampilkan daftar tipe tiket
    public function index()
    {
        $tiketTypes = TiketType::latest()->get();
        return view('admin.tiket-type.index', compact('tiketTypes'));
    }

    // Menampilkan form tambah
    public function create()
    {
        return view('admin.tiket-type.create');
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:tiket_types,nama',
        ], [
            'nama.required' => 'Nama tipe tiket wajib diisi.',
            'nama.unique' => 'Tipe tiket ini sudah ada.',
        ]);

        TiketType::create($request->all());

        return redirect()->route('admin.tiket-types.index')
                         ->with('success', 'Tipe tiket berhasil ditambahkan!');
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $tiketType = TiketType::findOrFail($id);
        return view('admin.tiket-type.edit', compact('tiketType'));
    }

    // Update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:tiket_types,nama,'.$id,
        ]);

        $tiketType = TiketType::findOrFail($id);
        $tiketType->update($request->all());

        return redirect()->route('admin.tiket-types.index')
                         ->with('success', 'Tipe tiket berhasil diperbarui!');
    }

    // Hapus data
    public function destroy($id)
    {
        $tiketType = TiketType::findOrFail($id);
        $tiketType->delete();

        return redirect()->route('admin.tiket-types.index')
                         ->with('success', 'Tipe tiket berhasil dihapus!');
    }
}
