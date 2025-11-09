<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Item;
use App\Models\Employee;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LoanController extends Controller
{
    /**
     * Tampilkan daftar semua peminjaman
     */
    public function index(Request $request)
    {
        $query = Loan::with(['item', 'employee'])
            ->orderBy('loan_date', 'desc');

        // Optional: filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas(
                'employee',
                fn($q) =>
                $q->where('name', 'like', "%{$search}%")
            )->orWhereHas(
                'item',
                fn($q) =>
                $q->where('name', 'like', "%{$search}%")
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('loan_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('loan_date', '<=', $request->date_to);
        }

        $loans = $query->paginate(10);

        return view('loans.index', compact('loans'));
    }

    /**
     * Form tambah peminjaman
     */
    public function create()
    {
        // Ambil hanya barang yang BELUM dipinjam
        $borrowedItemIds = Loan::where('status', 'Dipinjam')->pluck('item_id');
        $items = Item::whereNotIn('id', $borrowedItemIds)
            ->orderBy('name')
            ->get();

        $employees = Employee::orderBy('name')->get();

        return view('loans.create', compact('items', 'employees'));
    }

    /**
     * Simpan data peminjaman baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'employee_id' => 'required|exists:employees,id',
            'loan_date' => 'required|date',
            'expected_return_date' => 'nullable|date|after_or_equal:loan_date',
            'notes' => 'nullable|string|max:500',
        ]);

        // Pastikan barang belum dipinjam
        $isBorrowed = Loan::where('item_id', $validated['item_id'])
            ->where('status', 'Dipinjam')
            ->exists();

        if ($isBorrowed) {
            return back()->withErrors(['item_id' => 'Barang ini sedang dipinjam dan belum dikembalikan.'])->withInput();
        }

        Loan::create(array_merge($validated, [
            'status' => 'Dipinjam',
        ]));

        return redirect()->route('loans.index')
            ->with('success', 'Data peminjaman berhasil ditambahkan.');
    }

    /**
     * Detail peminjaman
     */
    public function show($id)
    {
        $loan = Loan::with(['item', 'employee'])->findOrFail($id);
        return view('loans.show', compact('loan'));
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $loan = Loan::findOrFail($id);

        // Barang lain yang belum dipinjam, plus barang ini sendiri
        $borrowedItemIds = Loan::where('status', 'Dipinjam')
            ->where('id', '<>', $loan->id)
            ->pluck('item_id');

        $items = Item::whereNotIn('id', $borrowedItemIds)
            ->orderBy('name')
            ->get();

        $employees = Employee::orderBy('name')->get();

        return view('loans.edit', compact('loan', 'items', 'employees'));
    }

    /**
     * Update data peminjaman
     */
    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'employee_id' => 'required|exists:employees,id',
            'loan_date' => 'required|date',
            'expected_return_date' => 'nullable|date|after_or_equal:loan_date',
            'actual_return_date' => 'nullable|date|after_or_equal:loan_date',
            'status' => 'required|string|in:Dipinjam,Dikembalikan,Hilang,Rusak',
            'condition_after' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $loan->update($validated);

        return redirect()->route('loans.show', $loan->id)
            ->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    /**
     * Hapus data peminjaman
     */
    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();

        return redirect()->route('loans.index')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }


    public function print($id)
    {
        $loan = Loan::with(['item', 'employee'])->findOrFail($id);

        $pdf = Pdf::loadView('loans.print', compact('loan'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Bukti_Peminjaman_' . $loan->id . '.pdf');
    }

    public function printMultiple(Request $request)
    {
        $loanIds = explode(',', $request->query('ids')); // dari URL ?ids=1,2,3
        $loans = Loan::with(['item', 'employee'])
            ->whereIn('id', $loanIds)
            ->get()
            ->groupBy('employee_id'); // kelompokkan per peminjam

        $pdf = Pdf::loadView('loans.print-multiple', compact('loans'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Formulir_Peminjaman_Barang.pdf');
    }

    public function export(Request $request)
    {
        $loans = Loan::with('item', 'employee')->orderBy('loan_date', 'desc')->get();

        $filename = 'loans_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($loans) {
            $file = fopen('php://output', 'w');

            // BOM UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            // Header CSV
            fputcsv($file, ['No', 'Barang', 'Peminjam', 'Departemen / Jabatan', 'Tanggal Pinjam', 'Tanggal Kembali', 'Status'], ';');

            $counter = 1;
            foreach ($loans as $loan) {
                fputcsv($file, [
                    $counter++,
                    $loan->item->name ?? '-',
                    $loan->employee->name ?? '-',
                    $loan->employee->department ?? '-',
                    \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y'),
                    $loan->expected_return_date ? \Carbon\Carbon::parse($loan->expected_return_date)->format('d/m/Y') : '-',
                    $loan->status,
                ], ';');
            }

            fclose($file);
        };


        return response()->stream($callback, 200, $headers);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['message' => 'Tidak ada data dipilih.'], 400);
        }

        Loan::whereIn('id', $ids)->delete();

        return response()->json(['message' => 'Data peminjaman terpilih berhasil dihapus.']);
    }
}
