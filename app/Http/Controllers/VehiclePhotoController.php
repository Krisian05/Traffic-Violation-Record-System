<?php

namespace App\Http\Controllers;

use App\Models\VehiclePhoto;
use Illuminate\Support\Facades\Storage;

class VehiclePhotoController extends Controller
{
    public function destroy(VehiclePhoto $vehiclePhoto)
    {
        $vehicle = $vehiclePhoto->vehicle;
        Storage::disk('public')->delete($vehiclePhoto->photo);
        $vehiclePhoto->delete();

        return back()->with('success', 'Photo deleted.');
    }
}
