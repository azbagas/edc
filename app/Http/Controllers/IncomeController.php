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
            return $query->whereBetween('date_time', [$start_date->startOfDay(), $end_date->endOfDay()]);
        }, function ($query) {
            return $query->whereDate('date_time', now()->today());
        });

        $query->has('payment');
        
        $query->when($request->status, function ($query) use ($request) {
            return $query->whereHas('payment', function ($query) use ($request) {
                return $query->where('status', $request->status);
            });
        });

        $per_page = $request->per_page ?? 10;

        return view('income.index', [
            'appointments' => $query->orderBy('date_time', 'desc')->paginate($per_page)->appends($request->all()),
            'per_page_options' => [10, 25, 50]
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
