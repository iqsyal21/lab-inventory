<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Tampilkan daftar semua barang.
     */
    public function index(Request $request)
    {
        $query = Item::with('loans');
        
        // Filter by search term
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        
        // Filter by availability
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->whereRaw('quantity_total > (SELECT COALESCE(SUM(quantity), 0) FROM loans WHERE item_id = items.id AND status = "Dipinjam")');
            } elseif ($request->availability === 'unavailable') {
                $query->whereRaw('quantity_total <= (SELECT COALESCE(SUM(quantity), 0) FROM loans WHERE item_id = items.id AND status = "Dipinjam")');
            }
        }
        
        $items = $query->orderBy('name')->paginate(10)->withQueryString();
        return view('items.index', compact('items'));
    }

    /**
     * Tampilkan form tambah barang.
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Simpan data barang baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:items,code',
            'name' => 'required|string|max:255',
            'quantity_total' => 'required|integer|min:0',
            'condition' => 'required|string',
            'location' => 'nullable|string|max:255',
        ]);

        Item::create($validated);

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit untuk barang tertentu.
     */
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    /**
     * Tampilkan detail barang tertentu.
     */
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    /**
     * Update data barang ke database.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'code' => 'required|unique:items,code,' . $item->id,
            'name' => 'required|string|max:255',
            'quantity_total' => 'required|integer|min:0',
            'condition' => 'required|string',
            'location' => 'nullable|string|max:255',
        ]);

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Hapus data barang.
     */
    public function export(Request $request)
    {
        $query = Item::with('loans');
        
        // Apply same filters as index method
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->whereRaw('quantity_total > (SELECT COALESCE(SUM(quantity), 0) FROM loans WHERE item_id = items.id AND status = "Dipinjam")');
            } elseif ($request->availability === 'unavailable') {
                $query->whereRaw('quantity_total <= (SELECT COALESCE(SUM(quantity), 0) FROM loans WHERE item_id = items.id AND status = "Dipinjam")');
            }
        }
        
        $items = $query->orderBy('name')->get();
        
        $filename = 'data_barang_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($items) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'No',
                'Kode',
                'Nama Barang',
                'Jumlah Total',
                'Jumlah Tersedia',
                'Kondisi',
                'Lokasi',
                'Tanggal Dibuat',
                'Tanggal Diperbarui'
            ]);
            
            // Data
            $counter = 0;
            foreach ($items as $item) {
                $counter++;
                fputcsv($file, [
                    $counter,
                    $item->code,
                    $item->name,
                    $item->quantity_total,
                    $item->quantity_available,
                    $item->condition,
                    $item->location ?? '-',
                    $item->created_at->format('d/m/Y H:i'),
                    $item->updated_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}