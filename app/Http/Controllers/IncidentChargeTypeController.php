<?php

namespace App\Http\Controllers;

use App\Models\IncidentChargeType;
use Illuminate\Http\Request;

class IncidentChargeTypeController extends Controller
{
    public function index()
    {
        $chargeTypes = IncidentChargeType::withCount('incidentMotorists')->orderBy('name')->get();
        return view('incident-charge-types.index', compact('chargeTypes'));
    }

    public function create()
    {
        return view('incident-charge-types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:200', 'unique:incident_charge_types,name'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        IncidentChargeType::create($data);

        return redirect()->route('incident-charge-types.index')
            ->with('success', 'Charge type added.');
    }

    public function edit(IncidentChargeType $incidentChargeType)
    {
        return view('incident-charge-types.edit', compact('incidentChargeType'));
    }

    public function update(Request $request, IncidentChargeType $incidentChargeType)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:200', "unique:incident_charge_types,name,{$incidentChargeType->id}"],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $incidentChargeType->update($data);

        return redirect()->route('incident-charge-types.index')
            ->with('success', 'Charge type updated.');
    }

    public function destroy(IncidentChargeType $incidentChargeType)
    {
        if ($incidentChargeType->incidentMotorists()->exists()) {
            return back()->with('error', 'Cannot delete a charge type that is in use by existing incident records.');
        }

        $incidentChargeType->delete();

        return redirect()->route('incident-charge-types.index')
            ->with('success', 'Charge type deleted.');
    }
}
