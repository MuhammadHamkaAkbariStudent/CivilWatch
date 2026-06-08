<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DistrictController extends Controller
{
    /**
     * Menampilkan daftar wilayah dengan paginasi.
     */
    public function index()
    {
        $districts = District::withCount('reports')
                    ->orderBy('name', 'asc')
                    ->paginate(10);
        return view('admin.districts.index', compact('districts'));
    }

    /**
     * Menampilkan form tambah wilayah.
     */
    public function create()
    {
        return view('admin.districts.create');
    }

    /**
     * Menyimpan wilayah baru menggunakan cara modern.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:districts,name'],
        ], [
            'name.required' => 'Nama kecamatan wajib diisi.',
            'name.unique'   => 'Nama kecamatan ini sudah terdaftar.',
        ]);

        District::create($validated);

        return redirect()
            ->route('admin.districts.index')
            ->with('success', 'Wilayah berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit wilayah.
     */
    public function edit(District $district)
    {
        return view('admin.districts.edit', compact('district'));
    }

    /**
     * Memperbarui data wilayah menggunakan Rule::unique yang modern.
     */
    public function update(Request $request, District $district)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('districts', 'name')->ignore($district->id),
            ],
        ], [
            'name.required' => 'Nama kecamatan wajib diisi.',
            'name.unique'   => 'Nama kecamatan ini sudah digunakan oleh wilayah lain.',
        ]);

        $district->update($validated);

        return redirect()
            ->route('admin.districts.index')
            ->with('success', 'Wilayah berhasil diperbarui.');
    }

    /**
     * Menghapus wilayah dengan proteksi relasi database (restrict constraint).
     */
    public function destroy(District $district)
    {
        // Proteksi: Cegah error SQL jika kecamatan masih memiliki laporan
        if ($district->reports()->exists()) {
            return redirect()
                ->route('admin.districts.index')
                ->with('error', 'Gagal menghapus! Wilayah ini masih memiliki laporan infrastruktur aktif.');
        }

        $district->delete();

        return redirect()
            ->route('admin.districts.index')
            ->with('success', 'Wilayah berhasil dihapus.');
    }
}