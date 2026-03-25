@extends('layouts.mobile')
@section('title', $violator->last_name . ', ' . $violator->first_name)
@section('back_url', route('officer.motorists.index'))

@section('content')

{{-- ── Profile Header ── --}}
<div class="mob-profile-header">
    <div class="d-flex align-items-center gap-3">
        @if($violator->photo)
            <img src="{{ asset('storage/' . $violator->photo) }}" alt="Photo"
                 class="mob-photo-single"
                 data-full="{{ asset('storage/' . $violator->photo) }}"
                 style="width:64px;height:64px;border-radius:18px;object-fit:cover;border:3px solid rgba(255,255,255,.3);flex-shrink:0;cursor:zoom-in;">
        @else
            <div style="width:64px;height:64px;border-radius:18px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.6rem;font-weight:800;color:#fff;">
                {{ strtoupper(substr($violator->first_name, 0, 1)) }}
            </div>
        @endif
        <div style="flex:1;min-width:0;">
            <div style="font-size:1.05rem;font-weight:800;color:#fff;line-height:1.25;">
                {{ $violator->last_name }}, {{ $violator->first_name }}
                @if($violator->middle_name) {{ $violator->middle_name }} @endif
            </div>
            @if($violator->license_number)
            <div style="display:inline-flex;align-items:center;gap:.3rem;background:rgba(255,255,255,.18);border-radius:20px;padding:.18rem .6rem;margin-top:.4rem;">
                <i class="ph ph-identification-badge" style="font-size:.68rem;color:rgba(255,255,255,.8);"></i>
                <span style="font-size:.7rem;font-weight:700;color:#fff;">{{ $violator->license_number }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Stats row --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem;margin-top:1rem;">
        <div style="background:rgba(255,255,255,.15);border-radius:10px;padding:.55rem .4rem;text-align:center;">
            <div style="font-size:1.1rem;font-weight:800;color:#fff;line-height:1;">{{ $violator->violations->count() }}</div>
            <div style="font-size:.58rem;font-weight:700;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.05em;margin-top:.12rem;">Violations</div>
        </div>
        <div style="background:rgba(255,255,255,.15);border-radius:10px;padding:.55rem .4rem;text-align:center;">
            <div style="font-size:1.1rem;font-weight:800;color:#fff;line-height:1;">{{ $violator->vehicles->count() }}</div>
            <div style="font-size:.58rem;font-weight:700;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.05em;margin-top:.12rem;">Vehicles</div>
        </div>
        <div style="background:rgba(255,255,255,.15);border-radius:10px;padding:.55rem .4rem;text-align:center;">
            <div style="font-size:1.1rem;font-weight:800;color:#fff;line-height:1;">{{ $incidents->count() }}</div>
            <div style="font-size:.58rem;font-weight:700;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.05em;margin-top:.12rem;">Incidents</div>
        </div>
    </div>
</div>

{{-- ── Motorist Photo ── --}}
@if($violator->photo)
<div class="mob-card">
    <div class="mob-section-title">ID Photo</div>
    <div class="mob-card-body pt-1 text-center">
        <img src="{{ asset('storage/' . $violator->photo) }}"
             alt="Motorist Photo"
             class="mob-photo-single"
             data-full="{{ asset('storage/' . $violator->photo) }}"
             style="max-width:100%;max-height:260px;object-fit:contain;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.1);cursor:zoom-in;">
    </div>
</div>
@endif

{{-- ── Details ── --}}
@if($violator->contact_number || $violator->license_type || $violator->license_expiry_date || $violator->temporary_address)
<div class="mob-card">
    <div class="mob-section-title">Motorist Details</div>
    <div class="mob-card-body pt-0">
        <div class="mob-info-grid">
            @if($violator->contact_number)
            <div class="mob-info-grid-full">
                <div class="mob-info-label"><i class="ph ph-phone me-1"></i>Contact</div>
                <div class="mob-info-value">{{ $violator->contact_number }}</div>
            </div>
            @endif
            @if($violator->license_type)
            <div>
                <div class="mob-info-label">License Type</div>
                <div class="mob-info-value">{{ $violator->license_type }}</div>
            </div>
            @endif
            @if($violator->license_expiry_date)
            <div>
                <div class="mob-info-label">Expiry Date</div>
                <div class="mob-info-value">{{ $violator->license_expiry_date->format('M d, Y') }}</div>
            </div>
            @endif
            @if($violator->temporary_address)
            <div class="mob-info-grid-full">
                <div class="mob-info-label"><i class="ph ph-map-pin me-1"></i>Address</div>
                <div class="mob-info-value">{{ $violator->temporary_address }}</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

{{-- ── Actions ── --}}
<div style="display:flex;flex-direction:column;gap:.5rem;margin-bottom:.875rem;">
    <a href="{{ route('officer.violations.create', $violator) }}" class="mob-btn-primary mob-btn-danger" style="display:flex;">
        <i class="ph-fill ph-file-plus"></i> Record Violation
    </a>
    <a href="{{ route('officer.motorists.edit', $violator) }}" class="mob-btn-outline">
        <i class="ph ph-pencil"></i> Edit Motorist
    </a>
</div>

{{-- ── Violations ── --}}
<div class="mob-card">
    <div class="mob-section-title">Violations ({{ $violator->violations->count() }})</div>

    @forelse($violator->violations as $viol)
    @php
        $isOverdue = $viol->status === 'pending' && $viol->created_at->lte(now()->subHours(72));
        $badgeClass = match($viol->status) {
            'settled'   => 'mob-badge-settled',
            'contested' => 'mob-badge-contested',
            default     => $isOverdue ? 'mob-badge-overdue' : 'mob-badge-pending',
        };
        $badgeLabel = match($viol->status) {
            'settled'   => 'Settled',
            'contested' => 'Contested',
            default     => $isOverdue ? 'Overdue' : 'Pending',
        };
    @endphp
    <a href="{{ route('officer.violations.show', $viol) }}" class="mob-list-item">
        <div style="width:36px;height:36px;border-radius:10px;background:#fef2f2;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-right:.85rem;">
            <i class="ph-fill ph-warning-circle" style="color:#dc2626;font-size:.9rem;"></i>
        </div>
        <div style="flex:1;min-width:0;">
            <div style="font-size:.875rem;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                {{ $viol->violationType->name ?? '—' }}
            </div>
            <div style="font-size:.72rem;color:#94a3b8;margin-top:.05rem;">
                {{ $viol->date_of_violation ? $viol->date_of_violation->format('M d, Y') : '—' }}
                @if($viol->location) · {{ Str::limit($viol->location, 28) }} @endif
            </div>
        </div>
        <div class="d-flex flex-column align-items-end gap-1 ms-2 flex-shrink-0">
            <span class="mob-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
            <i class="ph ph-caret-right" style="color:#d6d3d1;font-size:.75rem;"></i>
        </div>
    </a>
    @empty
    <div class="mob-empty">
        <i class="ph ph-file-x mob-empty-icon"></i>
        <div class="mob-empty-text">No violations recorded</div>
    </div>
    @endforelse
</div>

{{-- ── Vehicles ── --}}
<div class="mob-card">
    <div class="d-flex align-items-center justify-content-between pe-3">
        <div class="mob-section-title">Vehicles ({{ $violator->vehicles->count() }})</div>
        <a href="{{ route('officer.motorists.vehicles.create', $violator) }}"
           style="font-size:.72rem;font-weight:700;color:#1d4ed8;text-decoration:none;">
            <i class="ph ph-plus-circle me-1"></i>Add
        </a>
    </div>

    @forelse($violator->vehicles as $veh)
    <div class="mob-list-item" style="cursor:default;">
        <div style="width:36px;height:36px;border-radius:10px;background:#f8fafc;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-right:.875rem;">
            <i class="ph-fill ph-truck" style="color:#64748b;font-size:.9rem;"></i>
        </div>
        <div style="flex:1;min-width:0;">
            <div style="font-size:.875rem;font-weight:700;color:#0f172a;">{{ $veh->plate_number }}</div>
            <div style="font-size:.72rem;color:#94a3b8;margin-top:.05rem;">
                {{ trim($veh->make . ' ' . $veh->model) ?: '—' }}
                @if($veh->color) · {{ $veh->color }} @endif
                @if($veh->vehicle_type)
                <span style="display:inline-block;background:#f1f5f9;color:#64748b;border-radius:4px;padding:0 .3rem;font-size:.63rem;font-weight:700;margin-left:.2rem;">{{ $veh->vehicle_type }}</span>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="mob-empty">
        <i class="ph-fill ph-truck mob-empty-icon"></i>
        <div class="mob-empty-text">No vehicles on file</div>
    </div>
    @endforelse
</div>

{{-- ── Incidents ── --}}
@if($incidents->count() > 0)
<div class="mob-card">
    <div class="mob-section-title">Incidents ({{ $incidents->count() }})</div>
    @foreach($incidents as $inc)
    <a href="{{ route('officer.incidents.show', $inc) }}" class="mob-list-item">
        <div style="width:36px;height:36px;border-radius:10px;background:#fef2f2;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-right:.875rem;">
            <i class="ph-fill ph-flag" style="color:#dc2626;font-size:.8rem;"></i>
        </div>
        <div style="flex:1;min-width:0;">
            <div style="font-size:.875rem;font-weight:700;color:#0f172a;">{{ $inc->incident_number }}</div>
            <div style="font-size:.72rem;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:.05rem;">
                {{ $inc->date_of_incident ? \Carbon\Carbon::parse($inc->date_of_incident)->format('M d, Y') : '—' }}
                @if($inc->location) · {{ Str::limit($inc->location, 28) }} @endif
            </div>
        </div>
        @php $sc = ['open'=>'mob-badge-open','under_review'=>'mob-badge-review','closed'=>'mob-badge-closed'][$inc->status] ?? 'mob-badge-closed' @endphp
        <div class="d-flex flex-column align-items-end gap-1 ms-2 flex-shrink-0">
            <span class="mob-badge {{ $sc }}">{{ ucfirst(str_replace('_',' ',$inc->status)) }}</span>
            <i class="ph ph-caret-right" style="color:#d6d3d1;font-size:.75rem;"></i>
        </div>
    </a>
    @endforeach
</div>
@endif

@endsection
