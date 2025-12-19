<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\TeethRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeethRecordController extends Controller
{
    public function showChart(Patient $patient)
    {
        $teethRecords = TeethRecord::where('patient_id', $patient->id)->get()->keyBy('tooth_number');
        return view('patient.teeth-chart', compact('patient', 'teethRecords'));
    }

    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'tooth_number' => ['required', 'integer', 'in:11,12,13,14,15,16,17,18,21,22,23,24,25,26,27,28,31,32,33,34,35,36,37,38,41,42,43,44,45,46,47,48'],
            'condition' => ['nullable', 'string', 'max:255', 'in:healthy,cavity,filling,crown,extracted,missing,impacted,root_canal,other'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        // Sanitize inputs
        $sanitized = [
            'patient_id' => $patient->id,
            'tooth_number' => $validated['tooth_number'],
            'condition' => strip_tags(trim($validated['condition'] ?? '')),
            'remarks' => strip_tags(trim($validated['remarks'] ?? '')),
        ];

        // Update or create teeth record
        TeethRecord::updateOrCreate(
            [
                'patient_id' => $patient->id,
                'tooth_number' => $validated['tooth_number']
            ],
            $sanitized
        );

        return response()->json([
            'success' => true,
            'message' => 'Teeth record saved successfully.'
        ]);
    }

    public function getRecord(Patient $patient, $toothNumber)
    {
        $record = TeethRecord::where('patient_id', $patient->id)
            ->where('tooth_number', $toothNumber)
            ->first();

        return response()->json([
            'success' => true,
            'record' => $record
        ]);
    }
}
