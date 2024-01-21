<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PaymentType::query();

        $query->when($request->name, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        });

        $per_page = $request->per_page ?? 10;
        
        session(['payment_types_url' => request()->fullUrl()]);

        return view('payment-types.index', [
            'payment_types' => $query->orderBy('name', 'asc')->paginate($per_page)->appends($request->all()),
            'per_page_options' => [10, 25, 50]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payment-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required'
        ]);

        PaymentType::create($validatedData);

        return redirect(session('payment_types_url', '/payment-types'))->with('success', 'Metode pembayaran berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentType $paymentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentType $paymentType)
    {
        return view('payment-types.edit', [
            'paymentType' => $paymentType
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentType $paymentType)
    {
        $validatedData = $request->validate([
            'name' => 'required'
        ]);

        $paymentType->update($validatedData);

        return redirect(session('payment_types_url', '/payment-types'))->with('success', 'Metode pembayaran berhasil diedit!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentType $paymentType)
    {
        try {
            PaymentType::destroy($paymentType->id);
            return redirect(session('payment_types_url', '/payment-types'))->with('success', 'Metode pembayaran berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect(session('payment_types_url', '/payment-types'))->with('error', 'Metode pembayaran gagal dihapus!');
        }
    }
}
