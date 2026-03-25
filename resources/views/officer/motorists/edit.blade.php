@extends('layouts.mobile')
@section('title', 'Edit Motorist')
@section('back_url', route('officer.motorists.show', $violator))

@section('content')

<div class="mob-card">
    <div class="mob-card-body">
        <form method="POST" action="{{ route('officer.motorists.update', $violator) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="mob-alert mob-alert-danger">
                <i class="ph-fill ph-warning-circle flex-shrink-0"></i>
                <div>{{ $errors->first() }}</div>
            </div>
            @endif

            {{-- Personal Info --}}
            <div class="mob-form-divider">
                <span class="mob-form-divider-text">Personal Info</span>
                <span class="mob-form-divider-line"></span>
            </div>

            <div class="mb-3">
                <label class="mob-label">First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name" value="{{ old('first_name', $violator->first_name) }}" required
                       class="form-control mob-input @error('first_name') is-invalid @enderror">
                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="mob-label">Middle Name</label>
                <input type="text" name="middle_name" value="{{ old('middle_name', $violator->middle_name) }}"
                       class="form-control mob-input @error('middle_name') is-invalid @enderror"
                       placeholder="Optional">
                @error('middle_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="mob-label">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" value="{{ old('last_name', $violator->last_name) }}" required
                       class="form-control mob-input @error('last_name') is-invalid @enderror">
                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- License --}}
            <div class="mob-form-divider">
                <span class="mob-form-divider-text">License</span>
                <span class="mob-form-divider-line"></span>
            </div>

            <div class="mb-3">
                <label class="mob-label">License Number</label>
                <input type="text" name="license_number" value="{{ old('license_number', $violator->license_number) }}"
                       class="form-control mob-input @error('license_number') is-invalid @enderror"
                       placeholder="e.g. N01-01-123456">
                @error('license_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-2 mb-3">
                <div class="col-7">
                    <label class="mob-label">License Type</label>
                    <select name="license_type" class="form-select mob-select @error('license_type') is-invalid @enderror">
                        <option value="">— Select —</option>
                        <option value="Non-Professional" {{ old('license_type', $violator->license_type) === 'Non-Professional' ? 'selected' : '' }}>Non-Professional</option>
                        <option value="Professional"     {{ old('license_type', $violator->license_type) === 'Professional'     ? 'selected' : '' }}>Professional</option>
                    </select>
                    @error('license_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-5">
                    <label class="mob-label">Expiry Date</label>
                    <input type="date" name="license_expiry_date"
                           value="{{ old('license_expiry_date', $violator->license_expiry_date?->format('Y-m-d')) }}"
                           class="form-control mob-input @error('license_expiry_date') is-invalid @enderror">
                    @error('license_expiry_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Contact --}}
            <div class="mob-form-divider">
                <span class="mob-form-divider-text">Contact</span>
                <span class="mob-form-divider-line"></span>
            </div>

            <div class="mb-3">
                <label class="mob-label">Contact Number</label>
                <input type="text" name="contact_number" value="{{ old('contact_number', $violator->contact_number) }}"
                       class="form-control mob-input @error('contact_number') is-invalid @enderror"
                       placeholder="e.g. 09XX-XXX-XXXX">
                @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="mob-label">Address</label>
                <textarea name="address" rows="2"
                          class="form-control mob-input @error('address') is-invalid @enderror"
                          placeholder="Barangay / Municipality / Province"
                          style="min-height:auto;resize:none;">{{ old('address', $violator->temporary_address) }}</textarea>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Photo --}}
            <div class="mob-form-divider">
                <span class="mob-form-divider-text">Photo</span>
                <span class="mob-form-divider-line"></span>
            </div>

            @if($violator->photo)
            <div class="d-flex align-items-center gap-3 mb-3 p-3" style="background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;">
                <img src="{{ asset('storage/' . $violator->photo) }}" alt="Current photo"
                     style="width:52px;height:52px;border-radius:12px;object-fit:cover;flex-shrink:0;">
                <div>
                    <div style="font-size:.75rem;font-weight:600;color:#0f172a;">Current photo</div>
                    <div style="font-size:.68rem;color:#94a3b8;margin-top:.1rem;">Upload a new one to replace</div>
                </div>
            </div>
            @endif

            <div class="mb-4">
                <input type="file" name="photo" accept="image/*" capture="environment"
                       class="form-control mob-input @error('photo') is-invalid @enderror">
                @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="mob-btn-primary mb-2">
                <i class="ph-bold ph-check"></i> Save Changes
            </button>
            <a href="{{ route('officer.motorists.show', $violator) }}" class="mob-btn-outline">
                <i class="ph ph-x-circle"></i> Cancel
            </a>
        </form>
    </div>
</div>

@endsection
