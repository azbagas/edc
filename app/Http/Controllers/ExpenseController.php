<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Expense::query();

        $query->when($request->start_date, function ($query) use ($request) {
            $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date);
            $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date);
            return $query->whereBetween('date', [$start_date->startOfDay(), $end_date->endOfDay()]);
        }, function ($query) {
            return $query->whereDate('date', now()->today());
        });

        $query->when($request->name, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        });

        $query->when($request->date_order, function ($query) use ($request) {
            return $query->orderBy('date', $request->date_order);
        }, function ($query) {
            return $query->orderBy('date', 'asc');
        });

        session(['expenses_url' => request()->fullUrl()]);

        $expenses = $query->get();

        return view('expenses.index', [
            'expenses' => $query->paginate(10)->appends($request->all()),
            'total_expenses' => $expenses->sum('amount')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Change format currency
        if ($request->amount) {
            $amount = $request->amount;
            $amount = change_currency_format_to_decimal($amount);
            $request->merge(['amount' => $amount]);
        }

        $validatedData = $request->validate([
            'date' => 'required|date_format:d-m-Y',
            'name' => 'required',
            'amount' => 'required|numeric'
        ]);

        Expense::create($validatedData);

        return redirect(session('expenses_url', '/expenses'))->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        return view('expenses.edit', [
            'expense' => $expense
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        // Change format currency
        if ($request->amount) {
            $request->merge(['amount' => change_currency_format_to_decimal($request->amount)]);
        }

        $validatedData = $request->validate([
            'date' => 'required|date_format:d-m-Y',
            'name' => 'required',
            'amount' => 'required|numeric'
        ]);

        $expense->update($validatedData);

        return redirect(session('expenses_url', '/expenses'))->with('success', 'Pengeluaran berhasil diedit!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        try {
            Expense::destroy($expense->id);
            return redirect(session('expenses_url', '/expenses'))->with('success', 'Pengeluaran berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect(session('expenses_url', '/expenses'))->with('error', 'Gagal menghapus pengeluaran!');
        }
    }
}
