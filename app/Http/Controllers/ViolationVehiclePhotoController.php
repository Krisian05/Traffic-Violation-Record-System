<?php

namespace App\Http\Controllers;

use App\Models\ViolationVehiclePhoto;
use Illuminate\Support\Facades\Storage;

class ViolationVehiclePhotoController extends Controller
{
    public function destroy(ViolationVehiclePhoto $violationVehiclePhoto)
    {
        Storage::disk(uploads_disk())->delete($violationVehiclePhoto->photo);
        $violationVehiclePhoto->delete();

        return back()->with('success', 'Photo deleted.');
    }
}
