<?php

namespace App\Http\Controllers;

use App\Models\ReturnRecord;
use App\Models\Loan;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = ReturnRecord::with('loan.item');
        
        // Filter by search term
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('loan', function($loanQuery) use ($search) {
                    $loanQuery->where('borrower_name', 'like', "%{$search}%")
                              ->orWhereHas('item', function($itemQuery) use ($search) {
                                  $itemQuery->where('name', 'like', "%{$search}%");
                              });
                });
            });
        }
        
        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('return_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('return_date', '<=', $request->date_to);
        }
        
        $returns = $query->latest()->paginate(10)->withQueryString();
        return view('returns.index', compact('returns'));
    }

    public function create()
    {
        // hanya pinjaman dengan status masih Dipinjam
        $loans = Loan::with('item')->where('status', 'Dipinjam')->get();
        return view('returns.create', compact('loans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'return_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            // jika kamu pakai quantity_returned di form, validasikan di sini
        ]);

        $loan = Loan::with('returnRecords')->findOrFail($validated['loan_id']);

        // jumlah yang akan dianggap dikembalikan — jika UI tidak input jumlah,
        // kita anggap seluruh sisa pinjaman dikembalikan
        $quantityToReturn = $loan->remaining_quantity ?? ($loan->quantity - $loan->returnRecords()->sum('quantity_returned'));
        // fallback: jika accessor belum ada, pakai:
        // $quantityToReturn = max(0, $loan->quantity - $loan->returnRecords()->sum('quantity_returned'));

        // simpan record pengembalian
        $return = ReturnRecord::create([
            'loan_id' => $loan->id,
            'return_date' => $validated['return_date'],
            'quantity_returned' => $quantityToReturn,
            'condition' => $validated['notes'] ?? null, // sesuaikan nama kolom condition/notes di model-mu
            'notes' => $validated['notes'] ?? null,
        ]);

        // Tandai loan sebagai Dikembalikan jika total returned >= quantity loan
        $totalReturned = $loan->returnRecords()->sum('quantity_returned');
        if ($totalReturned >= $loan->quantity) {
            $loan->update([
                'status' => 'Dikembalikan',
                'return_date' => $validated['return_date'],
            ]);
        }

        return redirect()->route('returns.index')->with('success', 'Pengembalian berhasil dicatat.');
    }


    public function show(ReturnRecord $return)
    {
        return view('returns.show', compact('return'));
    }

    public function export(Request $request)
    {
        $query = ReturnRecord::with('loan.item');
        
        // Apply same filters as index method
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('loan', function($loanQuery) use ($search) {
                    $loanQuery->where('borrower_name', 'like', "%{$search}%")
                              ->orWhereHas('item', function($itemQuery) use ($search) {
                                  $itemQuery->where('name', 'like', "%{$search}%");
                              });
                });
            });
        }
        
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        
        if ($request->filled('date_from')) {
            $query->where('return_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('return_date', '<=', $request->date_to);
        }
        
        $returns = $query->latest()->get();
        
        $filename = 'data_pengembalian_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($returns) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'No',
                'Barang',
                'Peminjam',
                'Jumlah',
                'Tanggal Pinjam',
                'Tanggal Kembali',
                'Kondisi',
                'Catatan',
                'Tanggal Dibuat',
                'Tanggal Diperbarui'
            ]);
            
            // Data
            $counter = 0;
            foreach ($returns as $return) {
                $counter++;
                fputcsv($file, [
                    $counter,
                    $return->loan->item->name ?? '-',
                    $return->loan->borrower_name ?? '-',
                    $return->quantity_returned,
                    \Carbon\Carbon::parse($return->loan->loan_date)->format('d/m/Y'),
                    \Carbon\Carbon::parse($return->return_date)->format('d/m/Y'),
                    $return->condition ?? '-',
                    $return->notes ?? '-',
                    $return->created_at->format('d/m/Y H:i'),
                    $return->updated_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}