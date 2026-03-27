<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehiclePhoto;
use App\Models\Violator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::with(['violator', 'photos'])
            ->withCount('violations');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('plate_number', 'like', "%{$search}%")
                  ->orWhere('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('color', 'like', "%{$search}%")
                  ->orWhereHas('violator', fn($vq) =>
                      $vq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('middle_name', 'like', "%{$search}%")
                  );
            });
        }

        if ($type = $request->input('type')) {
            $query->where('vehicle_type', $type);
        }

        $vehicles = $query->orderBy('plate_number')->paginate(20)->withQueryString();

        return view('vehicles.index', compact('vehicles'));
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load([
            'violator',
            'photos',
            'violations' => fn($q) => $q->with('violationType')->orderByDesc('date_of_violation'),
        ]);

        return view('vehicles.show', compact('vehicle'));
    }

    public function create(Violator $violator)
    {
        return view('vehicles.create', compact('violator'));
    }

    public function store(Request $request, Violator $violator)
    {
        $data = $request->validate([
            'plate_number' => ['required', 'string', 'max:20', 'unique:vehicles,plate_number'],
            'vehicle_type' => ['required', 'in:MV,MC'],
            'make'         => ['nullable', 'string', 'max:100'],
            'model'        => ['nullable', 'string', 'max:100'],
            'color'        => ['nullable', 'string', 'max:50'],
            'year'         => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'or_number'      => ['nullable', 'string', 'max:50'],
            'cr_number'      => ['nullable', 'string', 'max:50'],
            'chassis_number' => ['nullable', 'string', 'max:50'],
            'photos'         => ['nullable', 'array', 'max:4'],
            'photos.*'     => ['image', 'max:20480'],
        ]);

        $data['violator_id'] = $violator->id;
        unset($data['photos']);

        $vehicle = Vehicle::create($data);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('vehicle-photos', uploads_disk());
                VehiclePhoto::create(['vehicle_id' => $vehicle->id, 'photo' => $path]);
            }
        }

        return redirect()->route('violators.show', $violator)
            ->with('success', 'Vehicle added successfully.');
    }

    public function edit(Vehicle $vehicle)
    {
        $vehicle->load(['violator', 'photos']);
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'plate_number' => ['required', 'string', 'max:20', "unique:vehicles,plate_number,{$vehicle->id}"],
            'vehicle_type' => ['required', 'in:MV,MC'],
            'make'         => ['nullable', 'string', 'max:100'],
            'model'        => ['nullable', 'string', 'max:100'],
            'color'        => ['nullable', 'string', 'max:50'],
            'year'         => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'or_number'      => ['nullable', 'string', 'max:50'],
            'cr_number'      => ['nullable', 'string', 'max:50'],
            'chassis_number' => ['nullable', 'string', 'max:50'],
            'photos'         => ['nullable', 'array'],
            'photos.*'       => ['image', 'max:10240'],
        ]);

        unset($data['photos']);
        $vehicle->update($data);

        if ($request->hasFile('photos')) {
            $currentCount = $vehicle->photos()->count();
            $newFiles     = $request->file('photos');
            $allowed      = 4 - $currentCount;

            if ($allowed <= 0) {
                return back()->withErrors(['photos' => 'Maximum 4 photos already reached. Delete an existing photo first.']);
            }

            foreach (array_slice($newFiles, 0, $allowed) as $file) {
                $path = $file->store('vehicle-photos', uploads_disk());
                VehiclePhoto::create(['vehicle_id' => $vehicle->id, 'photo' => $path]);
            }

            if (count($newFiles) > $allowed) {
                return redirect()->route('violators.show', $vehicle->violator_id)
                    ->with('success', 'Vehicle updated. Only ' . $allowed . ' photo(s) were saved (4-photo limit reached).');
            }
        }

        return redirect()->route('violators.show', $vehicle->violator_id)
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $violatorId = $vehicle->violator_id;

        foreach ($vehicle->photos as $photo) {
            Storage::disk(uploads_disk())->delete($photo->photo);
        }
        $vehicle->delete();

        return redirect()->route('violators.show', $violatorId)
            ->with('success', 'Vehicle removed.');
    }
}
