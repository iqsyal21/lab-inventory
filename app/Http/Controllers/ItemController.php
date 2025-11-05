<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Tampilkan semua data barang
     */
    public function index()
    {
        $items = Item::query()
            ->when(request('search'), function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('code', 'like', '%' . request('search') . '%')
                    ->orWhere('location', 'like', '%' . request('search') . '%');
            })
            ->when(request('condition'), function ($query) {
                $query->where('condition', request('condition'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10) // ← ini kuncinya
            ->withQueryString(); // agar filter & search tidak hilang saat berpindah halaman

        return view('items.index', compact('items'));
    }


    /**
     * Tampilkan form tambah barang
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Simpan data barang baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:items,code',
            'name' => 'required|string|max:255',
            'condition' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Item::create($validated);

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail barang tertentu
     */
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    /**
     * Tampilkan form edit barang
     */
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    /**
     * Update data barang
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'code' => 'required|unique:items,code,' . $item->id,
            'name' => 'required|string|max:255',
            'condition' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $item->update($validated);

        return redirect()->route('items.index')
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Hapus barang
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $items = \App\Models\Item::orderBy('name')->get();

        $filename = 'items_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($items) {
            $file = fopen('php://output', 'w');

            // BOM UTF-8 supaya karakter non-ASCII terbaca
            fwrite($file, "\xEF\xBB\xBF");

            // Header CSV
            fputcsv($file, ['No', 'Kode', 'Nama', 'Kondisi', 'Lokasi', 'Tanggal Dibuat', 'Tanggal Diperbarui'], ';');

            $counter = 1;
            foreach ($items as $item) {
                fputcsv($file, [
                    $counter++,
                    $item->code,
                    $item->name,
                    $item->condition,
                    $item->location ?? '-',
                    $item->created_at->format('d/m/Y'),
                    $item->updated_at->format('d/m/Y'),
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
