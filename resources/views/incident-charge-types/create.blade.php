@extends('layouts.app')
@section('title', 'Add Charge Type')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('incident-charge-types.index') }}" style="color:#78716c;text-decoration:none;">Charge Types</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Charge Type</li>
@endsection

@section('content')

<div class="ict-form-card">

    <div class="ict-form-header">
        <span class="ict-form-icon" style="background:linear-gradient(135deg,#7c3aed,#5b21b6);box-shadow:0 3px 10px rgba(124,58,237,.35);">
            <i class="bi bi-shield-plus" style="color:#fff;font-size:1rem;"></i>
        </span>
        <div>
            <div class="ict-form-title">Add Charge Type</div>
            <div class="ict-form-sub">Define a new criminal charge for incident reports</div>
        </div>
    </div>

    <div class="ict-form-body">
        <form method="POST" action="{{ route('incident-charge-types.store') }}">
            @csrf

            <div class="mb-3">
                <label class="ict-label">Charge / Offense Name <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text ict-ig-icon" style="background:#faf5ff;border-color:#d8b4fe;">
                        <i class="bi bi-shield-exclamation" style="color:#7c3aed;"></i>
                    </span>
                    <input type="text" name="name"
                           class="form-control ict-input @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           required placeholder="e.g. Reckless Imprudence Resulting in Homicide">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="ict-label">Legal Basis / Description</label>
                <div class="input-group align-items-start">
                    <span class="input-group-text ict-ig-icon" style="background:#eff6ff;border-color:#bfdbfe;">
                        <i class="bi bi-card-text" style="color:#1d4ed8;"></i>
                    </span>
                    <textarea name="description"
                              class="form-control ict-input @error('description') is-invalid @enderror"
                              rows="3"
                              placeholder="e.g. Art. 365, Revised Penal Code">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex gap-2 pt-2">
                <button type="submit" class="ict-submit-btn">
                    <i class="bi bi-check-lg"></i> Save Charge Type
                </button>
                <a href="{{ route('incident-charge-types.index') }}"
                   class="btn d-inline-flex align-items-center gap-2 rounded-pill"
                   style="border:1.5px solid #d6d3d1;color:#78716c;background:#fff;font-weight:500;">
                    <i class="bi bi-x-circle" style="font-size:.85rem;"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.ict-form-card {
    max-width: 560px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 6px 24px rgba(0,0,0,.06);
    overflow: hidden;
}
.ict-form-header {
    display: flex; align-items: center; gap: 1rem;
    padding: 1.1rem 1.4rem;
    background: linear-gradient(135deg, #faf5ff 0%, #f5f0fe 100%);
    border-bottom: 1.5px solid #e9d5ff;
}
.ict-form-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.ict-form-title { font-size: .95rem; font-weight: 700; color: #1c1917; }
.ict-form-sub   { font-size: .74rem; color: #a8a29e; margin-top: .1rem; }
.ict-form-body  { padding: 1.4rem; }

.ict-label {
    font-size: .72rem;
    font-weight: 700;
    color: #78716c;
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: .4rem;
    display: block;
}
.ict-ig-icon {
    border-right: none;
    padding: .45rem .75rem;
    border-radius: 10px 0 0 10px !important;
}
.ict-input {
    border-left: none;
    border-radius: 0 10px 10px 0 !important;
    font-size: .875rem;
}
.ict-input:focus { box-shadow: none; border-color: #d8b4fe; }

.ict-submit-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .5rem 1.25rem;
    border-radius: 10px;
    font-size: .84rem; font-weight: 700;
    background: linear-gradient(135deg, #7c3aed, #5b21b6);
    color: #fff;
    border: none;
    box-shadow: 0 2px 8px rgba(124,58,237,.3);
    cursor: pointer;
    transition: all .15s;
}
.ict-submit-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(124,58,237,.45); }
</style>

@endsection
