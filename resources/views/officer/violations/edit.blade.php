@extends('layouts.mobile')
@section('title', 'Edit Violation')
@section('back_url', route('officer.violations.show', $violation))

@section('content')

{{-- Motorist context --}}
<div class="mob-card" style="border-left:4px solid #dc2626;">
    <div class="mob-card-body d-flex align-items-center gap-3">
        <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#1d4ed8,#1e40af);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.95rem;font-weight:800;color:#fff;">
            {{ strtoupper(substr($violation->violator->first_name, 0, 1)) }}
        </div>
        <div>
            <div style="font-size:.62rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;font-weight:700;">Editing violation for</div>
            <div style="font-size:.95rem;font-weight:800;color:#0f172a;">{{ $violation->violator->last_name }}, {{ $violation->violator->first_name }}</div>
        </div>
    </div>
</div>

<div class="mob-card">
    <div class="mob-card-body">
        <form method="POST" action="{{ route('officer.violations.update', $violation) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="mob-alert mob-alert-danger">
                <i class="ph-fill ph-warning-circle flex-shrink-0"></i>
                <div>{{ $errors->first() }}</div>
            </div>
            @endif

            {{-- Violation --}}
            <div class="mob-form-divider">
                <span class="mob-form-divider-text">Violation</span>
                <span class="mob-form-divider-line"></span>
            </div>

            <div class="mb-3">
                <label class="mob-label">Violation Type <span class="text-danger">*</span></label>
                <select name="violation_type_id" class="form-select mob-input @error('violation_type_id') is-invalid @enderror" required>
                    <option value="">Select type…</option>
                    @foreach($violationTypes as $type)
                        <option value="{{ $type->id }}" @selected(old('violation_type_id', $violation->violation_type_id) == $type->id)>
                            {{ $type->name }}@if($type->fine_amount > 0) — ₱{{ number_format($type->fine_amount, 2) }}@endif
                        </option>
                    @endforeach
                </select>
                @error('violation_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="mob-label">Date of Violation <span class="text-danger">*</span></label>
                <input type="date" name="date_of_violation" required max="{{ date('Y-m-d') }}"
                       value="{{ old('date_of_violation', $violation->date_of_violation?->format('Y-m-d')) }}"
                       class="form-control mob-input @error('date_of_violation') is-invalid @enderror">
                @error('date_of_violation')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="mob-label">Location</label>
                <input type="text" name="location" placeholder="Street / Barangay / Landmark"
                       value="{{ old('location', $violation->location) }}"
                       class="form-control mob-input @error('location') is-invalid @enderror">
                @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="mob-label">Ticket Number</label>
                <input type="text" name="ticket_number"
                       value="{{ old('ticket_number', $violation->ticket_number) }}"
                       class="form-control mob-input @error('ticket_number') is-invalid @enderror">
                @error('ticket_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="mob-label">Notes</label>
                <textarea name="notes" rows="3" class="form-control mob-input @error('notes') is-invalid @enderror">{{ old('notes', $violation->notes) }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Vehicle --}}
            <div class="mob-form-divider">
                <span class="mob-form-divider-text">Vehicle</span>
                <span class="mob-form-divider-line"></span>
            </div>

            @if($violatorVehicles->isNotEmpty())
            <div class="mb-3">
                <label class="mob-label">Registered Vehicle</label>
                <select name="vehicle_id" class="form-select mob-input">
                    <option value="">None / Manual entry below</option>
                    @foreach($violatorVehicles as $v)
                        <option value="{{ $v->id }}" @selected(old('vehicle_id', $violation->vehicle_id) == $v->id)>
                            {{ $v->plate_number }} — {{ $v->make }} {{ $v->model }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="mb-3">
                <label class="mob-label">Plate Number</label>
                <input type="text" name="vehicle_plate"
                       value="{{ old('vehicle_plate', $violation->vehicle_plate) }}"
                       class="form-control mob-input" style="text-transform:uppercase;">
            </div>

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="mob-label">Make</label>
                    <input type="text" name="vehicle_make"
                           value="{{ old('vehicle_make', $violation->vehicle_make) }}"
                           class="form-control mob-input">
                </div>
                <div class="col-6">
                    <label class="mob-label">Model</label>
                    <input type="text" name="vehicle_model"
                           value="{{ old('vehicle_model', $violation->vehicle_model) }}"
                           class="form-control mob-input">
                </div>
            </div>

            <div class="mb-3">
                <label class="mob-label">Color</label>
                <input type="text" name="vehicle_color"
                       value="{{ old('vehicle_color', $violation->vehicle_color) }}"
                       class="form-control mob-input">
            </div>

            {{-- Citation Ticket Photo --}}
            <div class="mob-form-divider">
                <span class="mob-form-divider-text">Citation Photo</span>
                <span class="mob-form-divider-line"></span>
            </div>

            @if($violation->citation_ticket_photo)
            <div class="mb-2 text-center">
                <img src="{{ uploaded_file_url($violation->citation_ticket_photo) }}"
                     style="max-width:100%;max-height:160px;border-radius:10px;object-fit:cover;" alt="Citation Ticket">
                <div style="font-size:.72rem;color:#64748b;margin-top:.3rem;">Current photo — upload below to replace</div>
            </div>
            @endif

            <div class="mb-4">
                <label class="mob-label">{{ $violation->citation_ticket_photo ? 'Replace' : 'Upload' }} Citation Ticket Photo</label>
                <input type="file" name="citation_ticket_photo" accept="image/*" capture="environment"
                       class="form-control mob-input @error('citation_ticket_photo') is-invalid @enderror">
                @error('citation_ticket_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary w-100" style="border-radius:12px;font-weight:700;padding:.75rem;">
                <i class="ph ph-floppy-disk me-2"></i>Save Changes
            </button>
        </form>
    </div>
</div>

@endsection
