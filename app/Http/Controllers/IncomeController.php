<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Appointment;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Appointment::query();

        $query->when($request->start_date, function ($query) use ($request) {
            $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date);
            $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date);
            return $query->whereBetween('created_at', [$start_date->startOfDay(), $end_date->endOfDay()]);
        }, function ($query) {
            return $query->whereDate('created_at', now()->today());
        });

        $query->has('payment');

        return view('income.index', [
            'appointments' => $query->latest()->paginate(10)->appends($request->all())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
