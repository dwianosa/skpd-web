<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    public function index()
    {
        $jenisSurat = JenisSurat::all();
        return view('admin.jenis-surat.index', compact('jenisSurat'));
    }

    public function create()
    {
        return view('admin.jenis-surat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_surat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'persyaratan' => 'nullable|string',
            'aktif' => 'boolean'
        ]);

        JenisSurat::create([
            'nama_surat' => $request->nama_surat,
            'deskripsi' => $request->deskripsi,
            'persyaratan' => $request->persyaratan,
            'aktif' => $request->has('aktif')
        ]);

        return redirect()->route('admin.jenis-surat.index')->with('success', 'Jenis surat berhasil ditambahkan');
    }

    public function edit(JenisSurat $jenisSurat)
    {
        return view('admin.jenis-surat.edit', compact('jenisSurat'));
    }

    public function update(Request $request, JenisSurat $jenisSurat)
    {
        $request->validate([
            'nama_surat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'persyaratan' => 'nullable|string',
            'aktif' => 'boolean'
        ]);

        $jenisSurat->update([
            'nama_surat' => $request->nama_surat,
            'deskripsi' => $request->deskripsi,
            'persyaratan' => $request->persyaratan,
            'aktif' => $request->has('aktif')
        ]);

        return redirect()->route('admin.jenis-surat.index')->with('success', 'Jenis surat berhasil diperbarui');
    }

    public function destroy(JenisSurat $jenisSurat)
    {
        $jenisSurat->delete();
        return redirect()->route('admin.jenis-surat.index')->with('success', 'Jenis surat berhasil dihapus');
    }
}