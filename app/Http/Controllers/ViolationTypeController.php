<?php

namespace App\Http\Controllers;

use App\Models\ViolationType;
use Illuminate\Http\Request;

class ViolationTypeController extends Controller
{
    public function index()
    {
        $types = ViolationType::withCount('violations')->orderBy('name')->get();
        return view('violation-types.index', compact('types'));
    }

    public function create()
    {
        return view('violation-types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150', 'unique:violation_types,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'fine_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        ViolationType::create($data);

        return redirect()->route('violation-types.index')
            ->with('success', 'Violation type added.');
    }

    public function edit(ViolationType $violationType)
    {
        return view('violation-types.edit', compact('violationType'));
    }

    public function update(Request $request, ViolationType $violationType)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150', "unique:violation_types,name,{$violationType->id}"],
            'description' => ['nullable', 'string', 'max:500'],
            'fine_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $violationType->update($data);

        return redirect()->route('violation-types.index')
            ->with('success', 'Violation type updated.');
    }

    public function destroy(ViolationType $violationType)
    {
        if ($violationType->violations()->exists()) {
            return back()->with('error', 'Cannot delete a violation type that has existing violation records.');
        }

        $violationType->delete();

        return redirect()->route('violation-types.index')
            ->with('success', 'Violation type deleted.');
    }
}
