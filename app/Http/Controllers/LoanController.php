<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Item;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = Loan::with('item');
        
        // Filter by search term
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('borrower_name', 'like', "%{$search}%")
                  ->orWhere('borrower_department', 'like', "%{$search}%")
                  ->orWhereHas('item', function($itemQuery) use ($search) {
                      $itemQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by borrower role
        if ($request->filled('role')) {
            $query->where('borrower_role', $request->role);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('loan_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('loan_date', '<=', $request->date_to);
        }
        
        $loans = $query->latest()->paginate(10)->withQueryString();
        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $items = Item::with('loans')->get(); // pastikan accessor quantity_available aktif
        return view('loans.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'borrower_name' => 'required|string|max:255',
            'borrower_role' => 'nullable|string|max:100',
            'borrower_department' => 'nullable|string|max:150',
            'quantity' => 'required|integer|min:1',
            'loan_date' => 'required|date',
        ]);

        $item = Item::findOrFail($validated['item_id']);
        $available = $item->quantity_available;

        if ($validated['quantity'] > $available) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => 'Jumlah yang dipinjam melebihi stok tersedia (' . $available . ')']);
        }

        // Set status default ke "Dipinjam"
        $validated['status'] = 'Dipinjam';

        Loan::create($validated);

        return redirect()->route('loans.index')->with('success', 'Data peminjaman berhasil ditambahkan.');
    }

    public function show(Loan $loan)
    {
        return view('loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        $items = Item::with('loans')->get();
        return view('loans.edit', compact('loan', 'items'));
    }

    public function update(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'borrower_name' => 'required|string|max:255',
            'borrower_role' => 'nullable|string|max:100',
            'borrower_department' => 'nullable|string|max:150',
            'quantity' => 'required|integer|min:1',
            'loan_date' => 'required|date',
            'return_date' => 'nullable|date',
            // ❌ status tidak perlu diinput manual
        ]);

        $item = Item::findOrFail($validated['item_id']);

        // Hitung stok tersedia kecuali pinjaman ini sendiri
        $borrowedByOthers = $item->loans()
            ->where('status', 'Dipinjam')
            ->where('id', '!=', $loan->id)
            ->sum('quantity');

        $available = $item->quantity_total - $borrowedByOthers;

        // Cegah overloan (hanya jika masih berstatus dipinjam)
        if ($loan->status === 'Dipinjam' && $validated['quantity'] > $available) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => 'Jumlah yang dipinjam melebihi stok tersedia (' . $available . ')']);
        }

        $loan->update($validated);

        return redirect()->route('loans.index')->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    public function export(Request $request)
    {
        $query = Loan::with('item');
        
        // Apply same filters as index method
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('borrower_name', 'like', "%{$search}%")
                  ->orWhere('borrower_department', 'like', "%{$search}%")
                  ->orWhereHas('item', function($itemQuery) use ($search) {
                      $itemQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('role')) {
            $query->where('borrower_role', $request->role);
        }
        
        if ($request->filled('date_from')) {
            $query->where('loan_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('loan_date', '<=', $request->date_to);
        }
        
        $loans = $query->latest()->get();
        
        $filename = 'data_peminjaman_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($loans) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'No',
                'Barang',
                'Peminjam',
                'Peran',
                'Prodi / Unit',
                'Jumlah',
                'Tanggal Pinjam',
                'Tanggal Kembali',
                'Status',
                'Tanggal Dibuat',
                'Tanggal Diperbarui'
            ]);
            
            // Data
            $counter = 0;
            foreach ($loans as $loan) {
                $counter++;
                fputcsv($file, [
                    $counter,
                    $loan->item->name ?? '-',
                    $loan->borrower_name,
                    $loan->borrower_role,
                    $loan->borrower_department ?? '-',
                    $loan->quantity,
                    \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y'),
                    $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') : '-',
                    $loan->status,
                    $loan->created_at->format('d/m/Y H:i'),
                    $loan->updated_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}