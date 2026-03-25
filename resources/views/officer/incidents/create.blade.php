@extends('layouts.mobile')
@section('title', 'Record Incident')
@section('back_url', route('officer.incidents.index'))

@section('content')

<div class="mob-card">
    <div class="mob-card-body">
        <form method="POST" action="{{ route('officer.incidents.store') }}" enctype="multipart/form-data">
            @csrf

            @if($errors->any())
            <div class="mob-alert mob-alert-danger">
                <i class="ph-fill ph-warning-circle flex-shrink-0"></i>
                <div>{{ $errors->first() }}</div>
            </div>
            @endif

            {{-- Incident Info --}}
            <div class="mob-form-divider">
                <span class="mob-form-divider-text">Incident Info</span>
                <span class="mob-form-divider-line"></span>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-7">
                    <label class="mob-label">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date_of_incident"
                           value="{{ old('date_of_incident', now()->format('Y-m-d')) }}"
                           max="{{ now()->format('Y-m-d') }}" required
                           class="form-control mob-input @error('date_of_incident') is-invalid @enderror">
                    @error('date_of_incident')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-5">
                    <label class="mob-label">Time</label>
                    <input type="time" name="time_of_incident" value="{{ old('time_of_incident') }}"
                           class="form-control mob-input @error('time_of_incident') is-invalid @enderror">
                    @error('time_of_incident')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="mob-label">Location <span class="text-danger">*</span></label>
                <input type="text" name="location" value="{{ old('location') }}" required
                       class="form-control mob-input @error('location') is-invalid @enderror"
                       placeholder="Street / Barangay / Municipality">
                @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="mob-label">Description</label>
                <textarea name="description" rows="2"
                          class="form-control mob-input @error('description') is-invalid @enderror"
                          style="min-height:auto;resize:none;"
                          placeholder="Brief description of what happened...">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Motorists --}}
            <div class="mob-form-divider">
                <span class="mob-form-divider-text">Motorists Involved</span>
                <span class="mob-form-divider-line"></span>
            </div>
            <div style="font-size:.72rem;color:#94a3b8;margin-bottom:.75rem;">Minimum 2 motorists required</div>

            <div id="motorists-container">
                @php $oldMotorists = old('motorists', [
                    ['violator_id'=>'','motorist_name'=>'','motorist_license'=>'','incident_charge_type_id'=>'','notes'=>''],
                    ['violator_id'=>'','motorist_name'=>'','motorist_license'=>'','incident_charge_type_id'=>'','notes'=>'']
                ]); @endphp

                @foreach($oldMotorists as $mi => $m)
                <div class="motorist-row mb-2" style="background:#f8fafc;border-radius:12px;border:1.5px solid #e2e8f0;padding:.875rem;" data-index="{{ $mi }}">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div style="font-size:.7rem;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.06em;">
                            Motorist #{{ $mi + 1 }}
                        </div>
                        @if($mi >= 2)
                        <button type="button" class="btn btn-sm remove-motorist"
                                style="color:#dc2626;background:none;border:none;font-size:.75rem;padding:0;font-weight:600;">
                            <i class="ph ph-x-circle me-1"></i>Remove
                        </button>
                        @endif
                    </div>

                    <div class="mb-2">
                        <label class="mob-label" style="font-size:.62rem;">Link to registered motorist</label>
                        <select name="motorists[{{ $mi }}][violator_id]"
                                class="form-select mob-select violator-select"
                                style="font-size:.85rem;">
                            <option value="">— Unregistered / enter name below —</option>
                            @foreach($violators as $v)
                            <option value="{{ $v->id }}" data-license="{{ $v->license_number }}"
                                    {{ ($m['violator_id'] ?? '') == $v->id ? 'selected' : '' }}>
                                {{ $v->last_name }}, {{ $v->first_name }}
                                @if($v->license_number) ({{ $v->license_number }}) @endif
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2">
                        <div class="col-12">
                            <input type="text" name="motorists[{{ $mi }}][motorist_name]"
                                   value="{{ $m['motorist_name'] ?? '' }}"
                                   class="form-control mob-input motorist-name-field"
                                   placeholder="Full name (if unregistered)">
                            @error("motorists.{$mi}.motorist_name")
                            <div style="font-size:.72rem;color:#dc2626;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-7">
                            <input type="text" name="motorists[{{ $mi }}][motorist_license]"
                                   value="{{ $m['motorist_license'] ?? '' }}"
                                   class="form-control mob-input"
                                   placeholder="License number">
                        </div>
                        <div class="col-5">
                            <select name="motorists[{{ $mi }}][incident_charge_type_id]"
                                    class="form-select mob-select">
                                <option value="">— Charge —</option>
                                @foreach($chargeTypes as $ct)
                                <option value="{{ $ct->id }}" {{ ($m['incident_charge_type_id'] ?? '') == $ct->id ? 'selected' : '' }}>
                                    {{ $ct->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <input type="text" name="motorists[{{ $mi }}][notes]"
                                   value="{{ $m['notes'] ?? '' }}"
                                   class="form-control mob-input"
                                   placeholder="Notes (optional)">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" id="add-motorist"
                    style="display:flex;align-items:center;justify-content:center;gap:.4rem;width:100%;min-height:42px;border-radius:10px;border:1.5px dashed #93c5fd;background:transparent;color:#1d4ed8;font-weight:700;font-size:.85rem;cursor:pointer;margin-bottom:1.1rem;transition:background .15s;"
                    onmouseenter="this.style.background='#eff6ff'" onmouseleave="this.style.background='transparent'">
                <i class="ph ph-plus-circle"></i> Add Another Motorist
            </button>

            {{-- Documentation --}}
            <div class="mob-form-divider">
                <span class="mob-form-divider-text">Scene Photos</span>
                <span class="mob-form-divider-line"></span>
            </div>

            <div class="mb-4">
                <label class="mob-label">Incident Photos <span style="font-size:.68rem;color:#94a3b8;">(up to 6)</span></label>
                <input type="file" name="incident_photos[]" accept="image/*" capture="environment" multiple
                       class="form-control mob-input @error('incident_photos') is-invalid @enderror">
                @error('incident_photos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div style="font-size:.68rem;color:#94a3b8;margin-top:.3rem;">Scene photos, ticket photos, etc.</div>
            </div>

            <button type="submit" class="mob-btn-primary mob-btn-danger mb-2">
                <i class="ph-bold ph-check"></i> Save Incident
            </button>
            <a href="{{ route('officer.incidents.index') }}" class="mob-btn-outline">
                <i class="ph ph-x-circle"></i> Cancel
            </a>
        </form>
    </div>
</div>

@endsection

@php
    $violatorsForJs = $violators->map(function($v) {
        $suffix = $v->license_number ? ' (' . $v->license_number . ')' : '';
        return ['id' => $v->id, 'name' => $v->last_name . ', ' . $v->first_name . $suffix, 'license' => $v->license_number ?? ''];
    });
    $chargeTypesForJs = $chargeTypes->map(function($c) {
        return ['id' => $c->id, 'name' => $c->name];
    });
@endphp

<div id="page-data"
     data-motorist-count="{{ count($oldMotorists ?? []) }}"
     data-violators="{{ json_encode($violatorsForJs, JSON_HEX_TAG|JSON_HEX_QUOT) }}"
     data-charge-types="{{ json_encode($chargeTypesForJs, JSON_HEX_TAG|JSON_HEX_QUOT) }}"
     hidden></div>

@push('scripts')
<script>
// @ts-nocheck
var _pd = document.getElementById('page-data').dataset;
var motoristCount = parseInt(_pd.motoristCount, 10);
var violatorsJson = JSON.parse(_pd.violators);
var chargeTypesJson = JSON.parse(_pd.chargeTypes);

function buildMotoristRow(index) {
    var options = '<option value="">— Unregistered / enter name below —</option>';
    violatorsJson.forEach(function(v) {
        options += '<option value="'+v.id+'" data-license="'+v.license+'">' + v.name + '</option>';
    });
    var chargeOptions = '<option value="">— Charge —</option>';
    chargeTypesJson.forEach(function(c) {
        chargeOptions += '<option value="'+c.id+'">'+c.name+'</option>';
    });

    return '<div class="motorist-row mb-2" style="background:#f8fafc;border-radius:12px;border:1.5px solid #e2e8f0;padding:.875rem;" data-index="'+index+'">'
         + '<div class="d-flex align-items-center justify-content-between mb-2">'
         + '<div style="font-size:.7rem;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.06em;">Motorist #'+(index+1)+'</div>'
         + '<button type="button" class="btn btn-sm remove-motorist" style="color:#dc2626;background:none;border:none;font-size:.75rem;padding:0;font-weight:600;"><i class="ph ph-x-circle me-1"></i>Remove</button>'
         + '</div>'
         + '<div class="mb-2"><label class="mob-label" style="font-size:.62rem;">Link to registered motorist</label>'
         + '<select name="motorists['+index+'][violator_id]" class="form-select mob-select violator-select" style="font-size:.85rem;">'+options+'</select></div>'
         + '<div class="row g-2">'
         + '<div class="col-12"><input type="text" name="motorists['+index+'][motorist_name]" class="form-control mob-input motorist-name-field" placeholder="Full name (if unregistered)"></div>'
         + '<div class="col-7"><input type="text" name="motorists['+index+'][motorist_license]" class="form-control mob-input" placeholder="License number"></div>'
         + '<div class="col-5"><select name="motorists['+index+'][incident_charge_type_id]" class="form-select mob-select">'+chargeOptions+'</select></div>'
         + '<div class="col-12"><input type="text" name="motorists['+index+'][notes]" class="form-control mob-input" placeholder="Notes (optional)"></div>'
         + '</div></div>';
}

document.getElementById('add-motorist').addEventListener('click', function() {
    var container = document.getElementById('motorists-container');
    var div = document.createElement('div');
    div.innerHTML = buildMotoristRow(motoristCount);
    container.appendChild(div.firstChild);
    motoristCount++;
    attachViolatorSelect();
});

document.getElementById('motorists-container').addEventListener('click', function(e) {
    if (e.target.closest('.remove-motorist')) {
        var row = e.target.closest('.motorist-row');
        if (row) row.remove();
    }
});

function attachViolatorSelect() {
    document.querySelectorAll('.violator-select').forEach(function(sel) {
        if (sel._attached) return;
        sel._attached = true;
        sel.addEventListener('change', function() {
            var opt = sel.options[sel.selectedIndex];
            var license = opt.dataset.license || '';
            var row = sel.closest('.motorist-row');
            if (row) {
                var licenseField = row.querySelectorAll('input.mob-input')[1];
                if (license) licenseField.value = license;
            }
        });
    });
}
attachViolatorSelect();
</script>
@endpush
