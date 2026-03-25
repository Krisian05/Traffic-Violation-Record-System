@extends('layouts.mobile')
@section('title', 'Dashboard')

@section('content')

{{-- ── Officer Hero ── --}}
<div class="mob-hero mb-3" style="position:relative;z-index:1;">
    <div class="d-flex align-items-center gap-3 mb-3" style="position:relative;z-index:1;">
        <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:1.25rem;font-weight:800;color:#fff;flex-shrink:0;">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div style="flex:1;min-width:0;">
            <div style="font-size:.62rem;font-weight:700;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.07em;">Traffic Officer</div>
            <div style="font-size:1rem;font-weight:800;color:#fff;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->name }}</div>
        </div>
        <div style="background:rgba(255,255,255,.15);border-radius:10px;padding:.3rem .65rem;text-align:center;flex-shrink:0;">
            <div style="font-size:.58rem;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.06em;font-weight:700;">Today</div>
            <div style="font-size:.82rem;font-weight:800;color:#fff;">{{ now()->format('M d') }}</div>
        </div>
    </div>

    {{-- Mini Stats --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem;position:relative;z-index:1;">
        <div style="background:rgba(255,255,255,.15);border-radius:10px;padding:.6rem .4rem;text-align:center;">
            <div style="font-size:1.2rem;font-weight:800;color:#fff;line-height:1;">{{ $motoristCount }}</div>
            <div style="font-size:.58rem;font-weight:700;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.05em;margin-top:.15rem;">Motorists</div>
        </div>
        <div style="background:rgba(255,255,255,.15);border-radius:10px;padding:.6rem .4rem;text-align:center;">
            <div style="font-size:1.2rem;font-weight:800;color:#fff;line-height:1;">{{ $openIncidentCount }}</div>
            <div style="font-size:.58rem;font-weight:700;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.05em;margin-top:.15rem;">Open</div>
        </div>
        @if($overdueCount > 0)
        <div style="background:rgba(239,68,68,.3);border-radius:10px;padding:.6rem .4rem;text-align:center;">
        @else
        <div style="background:rgba(255,255,255,.15);border-radius:10px;padding:.6rem .4rem;text-align:center;">
        @endif
            <div style="font-size:1.2rem;font-weight:800;color:#fff;line-height:1;">{{ $overdueCount }}</div>
            <div style="font-size:.58rem;font-weight:700;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.05em;margin-top:.15rem;">Overdue</div>
        </div>
    </div>
</div>

{{-- ── Overdue Alert ── --}}
@if($overdueCount > 0)
<div class="mob-alert mob-alert-warning">
    <i class="ph-fill ph-clock-countdown flex-shrink-0" style="font-size:1.05rem;"></i>
    <div>
        <div style="font-weight:700;font-size:.875rem;">{{ $overdueCount }} overdue violation{{ $overdueCount > 1 ? 's' : '' }}</div>
        <div style="font-size:.75rem;margin-top:.1rem;opacity:.85;">Pending payment for more than 72 hours.</div>
    </div>
</div>
@endif

{{-- ── Quick Access ── --}}
<div class="mob-heading">Quick Access</div>

<a href="{{ route('officer.motorists.index') }}" class="mob-action-card">
    <div class="mob-action-icon mob-action-icon--blue">
        <i class="ph-fill ph-identification-card"></i>
    </div>
    <div style="flex:1;min-width:0;">
        <div style="font-size:.95rem;font-weight:800;color:#0f172a;">Motorists</div>
        <div style="font-size:.73rem;color:#94a3b8;margin-top:.1rem;">{{ $motoristCount }} registered record{{ $motoristCount !== 1 ? 's' : '' }}</div>
    </div>
    <i class="ph ph-caret-right" style="color:#d6d3d1;font-size:.85rem;flex-shrink:0;"></i>
</a>

<a href="{{ route('officer.incidents.index') }}" class="mob-action-card">
    <div class="mob-action-icon mob-action-icon--red">
        <i class="ph-fill ph-flag"></i>
    </div>
    <div style="flex:1;min-width:0;">
        <div style="font-size:.95rem;font-weight:800;color:#0f172a;">Incidents</div>
        <div style="font-size:.73rem;color:#94a3b8;margin-top:.1rem;">{{ $openIncidentCount }} open case{{ $openIncidentCount !== 1 ? 's' : '' }}</div>
    </div>
    <i class="ph ph-caret-right" style="color:#d6d3d1;font-size:.85rem;flex-shrink:0;"></i>
</a>

{{-- ── Field Actions ── --}}
<div class="mob-heading mt-1">Field Actions</div>

<div class="mob-card">
    <a href="{{ route('officer.motorists.create') }}" class="mob-list-item">
        <div style="width:36px;height:36px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-right:.875rem;">
            <i class="ph-fill ph-user-plus" style="color:#1d4ed8;font-size:.95rem;"></i>
        </div>
        <div style="flex:1;">
            <div style="font-size:.875rem;font-weight:700;color:#0f172a;">New Motorist</div>
            <div style="font-size:.72rem;color:#94a3b8;margin-top:.05rem;">Register a motorist profile</div>
        </div>
        <i class="ph ph-caret-right" style="color:#d6d3d1;font-size:.8rem;"></i>
    </a>
    <a href="{{ route('officer.incidents.create') }}" class="mob-list-item">
        <div style="width:36px;height:36px;border-radius:10px;background:#fef2f2;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-right:.875rem;">
            <i class="ph-fill ph-plus-circle" style="color:#dc2626;font-size:.95rem;"></i>
        </div>
        <div style="flex:1;">
            <div style="font-size:.875rem;font-weight:700;color:#0f172a;">Record Incident</div>
            <div style="font-size:.72rem;color:#94a3b8;margin-top:.05rem;">Create an incident report</div>
        </div>
        <i class="ph ph-caret-right" style="color:#d6d3d1;font-size:.8rem;"></i>
    </a>
</div>

@endsection
