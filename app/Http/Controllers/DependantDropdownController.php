<?php

namespace App\Http\Controllers;

use App\Models\Diagnose;
use App\Models\Disease;
use App\Models\Medicine;
use App\Models\MedicineType;
use App\Models\Treatment;
use App\Models\TreatmentType;
use Illuminate\Http\Request;

class DependantDropdownController extends Controller
{
    public function getDiseases()
    {
        $diseases = Disease::all();
        return response()->json($diseases);
    }

    public function getTreatmentTypes()
    {
        $treatmentTypes = TreatmentType::all();
        return response()->json($treatmentTypes);
    }

    public function getMedicineTypes()
    {
        $medicineTypes = MedicineType::all();
        return response()->json($medicineTypes);
    }

    public function getDiagnoses(Request $request)
    {
        $diagnoses = Diagnose::where('disease_id', $request->disease)
                             ->without('disease')
                             ->get(['id', 'diagnose_code', 'name']);
        return response()->json($diagnoses);
    }

    public function getTreatments(Request $request)
    {
        $treatments = Treatment::where('treatment_type_id', $request->treatment_type)
                               ->without('treatment_type')
                               ->get(['id', 'name']);
        return response()->json($treatments);
    }

    public function getMedicines(Request $request)
    {
        $medicines = Medicine::where('medicine_type_id', $request->medicine_type)
                               ->without('medicine_type')
                               ->get(['id', 'name', 'dose', 'stock', 'unit', 'price']);
        return response()->json($medicines);
    }
}
