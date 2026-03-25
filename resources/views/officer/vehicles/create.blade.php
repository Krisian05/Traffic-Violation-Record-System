@extends('layouts.mobile')
@section('title', 'Add Vehicle')
@section('back_url', route('officer.motorists.show', $violator))

@section('content')

{{-- Motorist context --}}
<div class="mob-card" style="border-left:4px solid #1d4ed8;">
    <div class="mob-card-body d-flex align-items-center gap-3">
        <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#1d4ed8,#1e40af);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.95rem;font-weight:800;color:#fff;">
            {{ strtoupper(substr($violator->first_name, 0, 1)) }}
        </div>
        <div>
            <div style="font-size:.62rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;font-weight:700;">Adding vehicle for</div>
            <div style="font-size:.95rem;font-weight:800;color:#0f172a;">{{ $violator->last_name }}, {{ $violator->first_name }}</div>
        </div>
    </div>
</div>

<div class="mob-card">
    <div class="mob-card-body">
        <form method="POST" action="{{ route('officer.motorists.vehicles.store', $violator) }}">
            @csrf

            @if($errors->any())
            <div class="mob-alert mob-alert-danger">
                <i class="ph-fill ph-warning-circle flex-shrink-0"></i>
                <div>{{ $errors->first() }}</div>
            </div>
            @endif

            {{-- Vehicle Identity --}}
            <div class="mob-form-divider">
                <span class="mob-form-divider-text">Vehicle Identity</span>
                <span class="mob-form-divider-line"></span>
            </div>

            <div class="mb-3">
                <label class="mob-label">Plate Number <span class="text-danger">*</span></label>
                <input type="text" name="plate_number" value="{{ old('plate_number') }}" required
                       class="form-control mob-input @error('plate_number') is-invalid @enderror"
                       placeholder="e.g. ABC 1234"
                       style="text-transform:uppercase;font-weight:700;letter-spacing:.05em;">
                @error('plate_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="mob-label">Vehicle Type</label>
                <select name="vehicle_type" class="form-select mob-select @error('vehicle_type') is-invalid @enderror">
                    <option value="">— Select type —</option>
                    @foreach(['Motorcycle','Tricycle','Car','SUV','Van','Truck','Bus','Other'] as $type)
                    <option value="{{ $type }}" {{ old('vehicle_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                @error('vehicle_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="mob-label">Make</label>
                    <input type="text" name="make" value="{{ old('make') }}"
                           class="form-control mob-input @error('make') is-invalid @enderror"
                           placeholder="e.g. Honda">
                    @error('make')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-6">
                    <label class="mob-label">Model</label>
                    <input type="text" name="model" value="{{ old('model') }}"
                           class="form-control mob-input @error('model') is-invalid @enderror"
                           placeholder="e.g. Click 125">
                    @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="mob-label">Color</label>
                <input type="text" name="color" value="{{ old('color') }}"
                       class="form-control mob-input @error('color') is-invalid @enderror"
                       placeholder="e.g. Red">
                @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Registration --}}
            <div class="mob-form-divider">
                <span class="mob-form-divider-text">Registration</span>
                <span class="mob-form-divider-line"></span>
            </div>

            <div class="row g-2 mb-4">
                <div class="col-6">
                    <label class="mob-label">OR Number</label>
                    <input type="text" name="or_number" value="{{ old('or_number') }}"
                           class="form-control mob-input @error('or_number') is-invalid @enderror"
                           placeholder="Official Receipt #">
                    @error('or_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-6">
                    <label class="mob-label">CR Number</label>
                    <input type="text" name="cr_number" value="{{ old('cr_number') }}"
                           class="form-control mob-input @error('cr_number') is-invalid @enderror"
                           placeholder="Certificate of Reg. #">
                    @error('cr_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <button type="submit" class="mob-btn-primary mb-2">
                <i class="ph-bold ph-check"></i> Save Vehicle
            </button>
            <a href="{{ route('officer.motorists.show', $violator) }}" class="mob-btn-outline">
                <i class="ph ph-x-circle"></i> Cancel
            </a>
        </form>
    </div>
</div>

@endsection
