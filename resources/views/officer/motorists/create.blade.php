@extends('layouts.mobile')
@section('title', 'New Motorist')
@section('back_url', route('officer.motorists.index'))

@section('content')

<div class="mob-card">
    <div class="mob-card-body">
        <form method="POST" action="{{ route('officer.motorists.store') }}" enctype="multipart/form-data">
            @csrf

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
                <input type="text" name="first_name" value="{{ old('first_name') }}" required
                       class="form-control mob-input @error('first_name') is-invalid @enderror"
                       placeholder="e.g. Juan">
                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="mob-label">Middle Name</label>
                <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                       class="form-control mob-input @error('middle_name') is-invalid @enderror"
                       placeholder="Optional">
                @error('middle_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="mob-label">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                       class="form-control mob-input @error('last_name') is-invalid @enderror"
                       placeholder="e.g. dela Cruz">
                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- License --}}
            <div class="mob-form-divider">
                <span class="mob-form-divider-text">License</span>
                <span class="mob-form-divider-line"></span>
            </div>

            <div class="mb-3">
                <label class="mob-label">License Number</label>
                <input type="text" name="license_number" value="{{ old('license_number') }}"
                       class="form-control mob-input @error('license_number') is-invalid @enderror"
                       placeholder="e.g. N01-01-123456">
                @error('license_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-2 mb-3">
                <div class="col-7">
                    <label class="mob-label">License Type</label>
                    <select name="license_type" class="form-select mob-select @error('license_type') is-invalid @enderror">
                        <option value="">— Select —</option>
                        <option value="Non-Professional" {{ old('license_type') === 'Non-Professional' ? 'selected' : '' }}>Non-Professional</option>
                        <option value="Professional"     {{ old('license_type') === 'Professional'     ? 'selected' : '' }}>Professional</option>
                    </select>
                    @error('license_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-5">
                    <label class="mob-label">Expiry Date</label>
                    <input type="date" name="license_expiry_date" value="{{ old('license_expiry_date') }}"
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
                <input type="text" name="contact_number" value="{{ old('contact_number') }}"
                       class="form-control mob-input @error('contact_number') is-invalid @enderror"
                       placeholder="e.g. 09XX-XXX-XXXX">
                @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="mob-label">Address</label>
                <textarea name="address" rows="2"
                          class="form-control mob-input @error('address') is-invalid @enderror"
                          placeholder="Barangay / Municipality / Province"
                          style="min-height:auto;resize:none;">{{ old('address') }}</textarea>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Photo --}}
            <div class="mob-form-divider">
                <span class="mob-form-divider-text">Photo</span>
                <span class="mob-form-divider-line"></span>
            </div>

            <div class="mb-4">
                <input type="file" name="photo" accept="image/*" capture="environment"
                       class="form-control mob-input @error('photo') is-invalid @enderror">
                <div style="font-size:.72rem;color:#94a3b8;margin-top:.35rem;">Take a photo or upload from gallery</div>
                @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="mob-btn-primary mb-2">
                <i class="ph-bold ph-check"></i> Save Motorist
            </button>
            <a href="{{ route('officer.motorists.index') }}" class="mob-btn-outline">
                <i class="ph ph-x-circle"></i> Cancel
            </a>
        </form>
    </div>
</div>

@endsection
