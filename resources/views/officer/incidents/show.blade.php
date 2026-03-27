@extends('layouts.mobile')
@section('title', $incident->incident_number)
@section('back_url', route('officer.incidents.index'))

@section('content')

{{-- ── Incident Header ── --}}
@php $sc = ['open'=>'mob-badge-open','under_review'=>'mob-badge-review','closed'=>'mob-badge-closed'][$incident->status] ?? 'mob-badge-closed' @endphp

<div class="mob-card" style="border-left:4px solid #dc2626;">
    <div class="mob-card-body">
        <div class="d-flex align-items-start justify-content-between mb-3">
            <div>
                <div style="font-size:.62rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;">Incident Report</div>
                <div style="font-size:1.05rem;font-weight:800;color:#0f172a;margin-top:.1rem;">{{ $incident->incident_number }}</div>
            </div>
            <span class="mob-badge {{ $sc }}" style="flex-shrink:0;">{{ ucfirst(str_replace('_',' ',$incident->status)) }}</span>
        </div>

        <div class="mob-info-grid">
            <div>
                <div class="mob-info-label">Date</div>
                <div class="mob-info-value">{{ $incident->date_of_incident ? \Carbon\Carbon::parse($incident->date_of_incident)->format('M d, Y') : '—' }}</div>
            </div>
            @if($incident->time_of_incident)
            <div>
                <div class="mob-info-label">Time</div>
                <div class="mob-info-value">{{ \Carbon\Carbon::parse($incident->time_of_incident)->format('g:i A') }}</div>
            </div>
            @endif
            <div class="mob-info-grid-full">
                <div class="mob-info-label"><i class="ph ph-map-pin me-1"></i>Location</div>
                <div class="mob-info-value">{{ $incident->location }}</div>
            </div>
            @if($incident->description)
            <div class="mob-info-grid-full">
                <div class="mob-info-label">Description</div>
                <div class="mob-info-value" style="font-weight:400;color:#334155;">{{ $incident->description }}</div>
            </div>
            @endif
            @if($incident->recorder)
            <div class="mob-info-grid-full">
                <div class="mob-info-label"><i class="ph ph-user me-1"></i>Recorded by</div>
                <div class="mob-info-value">{{ $incident->recorder->name }}</div>
            </div>
            @endif
        </div>

        @can('update', $incident)
        <div class="mt-3">
            <a href="{{ route('officer.incidents.edit', $incident) }}"
               class="btn btn-outline-primary w-100" style="border-radius:12px;font-weight:700;">
                <i class="ph ph-pencil-simple me-2"></i>Edit Incident
            </a>
        </div>
        @endcan
    </div>
</div>

{{-- ── Motorists Involved ── --}}
<div class="mob-card">
    <div class="mob-section-title">Motorists Involved ({{ $incident->motorists->count() }})</div>

    @forelse($incident->motorists as $m)
    <div class="mob-list-item" style="cursor:default;align-items:flex-start;padding-top:.9rem;padding-bottom:.9rem;">
        <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#1d4ed8,#1e40af);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-right:.875rem;font-size:.9rem;font-weight:800;color:#fff;margin-top:.05rem;">
            {{ strtoupper(substr($m->motorist_name ?? ($m->violator->first_name ?? '?'), 0, 1)) }}
        </div>
        <div style="flex:1;min-width:0;">
            <div style="font-size:.875rem;font-weight:700;color:#0f172a;">
                @if($m->violator)
                    <a href="{{ route('officer.motorists.show', $m->violator) }}" style="color:#1d4ed8;text-decoration:none;">
                        {{ $m->violator->last_name }}, {{ $m->violator->first_name }}
                    </a>
                @else
                    {{ $m->motorist_name ?? '—' }}
                @endif
            </div>

            @if($m->motorist_license)
            <div style="font-size:.72rem;color:#94a3b8;margin-top:.1rem;">
                <i class="ph ph-identification-badge me-1"></i>{{ $m->motorist_license }}
            </div>
            @endif

            @if($m->chargeType)
            <div style="margin-top:.3rem;">
                <span style="display:inline-flex;align-items:center;background:#fef2f2;color:#b91c1c;border:1px solid #fca5a5;border-radius:20px;font-size:.65rem;font-weight:700;padding:.15rem .55rem;">
                    {{ $m->chargeType->name }}
                </span>
            </div>
            @endif

            @php
                $mPlate = $m->vehicle ? $m->vehicle->plate_number : $m->vehicle_plate;
                $mMake  = $m->vehicle ? $m->vehicle->make  : $m->vehicle_make;
                $mModel = $m->vehicle ? $m->vehicle->model : $m->vehicle_model;
                $mColor = $m->vehicle ? $m->vehicle->color : $m->vehicle_color;
            @endphp
            @if($mPlate || $mMake)
            <div style="display:flex;align-items:center;gap:.35rem;margin-top:.35rem;padding:.35rem .6rem;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;font-size:.72rem;color:#334155;">
                <i class="ph-fill ph-truck" style="color:#94a3b8;flex-shrink:0;"></i>
                <span style="font-weight:700;">{{ $mPlate ?? '—' }}</span>
                @if($mMake || $mModel) <span style="color:#94a3b8;">· {{ trim($mMake . ' ' . $mModel) }}</span> @endif
                @if($mColor) <span style="color:#94a3b8;">· {{ $mColor }}</span> @endif
            </div>
            @endif

            @if($m->notes)
            <div style="font-size:.72rem;color:#64748b;margin-top:.3rem;font-style:italic;">{{ $m->notes }}</div>
            @endif
        </div>
    </div>
    @empty
    <div class="mob-empty">
        <i class="ph ph-users mob-empty-icon"></i>
        <div class="mob-empty-text">No motorists linked</div>
    </div>
    @endforelse
</div>

{{-- ── Scene Photos ── --}}
@if($incident->media->isNotEmpty())
<div class="mob-card">
    <div class="mob-section-title">Scene Photos ({{ $incident->media->count() }})</div>
    <div class="mob-card-body pt-1">
        <div class="row g-2">
            @foreach($incident->media as $m)
            @if($m->isImage())
            <div class="col-6">
                <img src="{{ uploaded_file_url($m->file_path) }}"
                     alt="{{ $m->caption ?? 'Incident photo' }}"
                     class="mob-photo-thumb"
                     data-full="{{ uploaded_file_url($m->file_path) }}"
                     style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:10px;box-shadow:0 1px 4px rgba(0,0,0,.08);cursor:zoom-in;">
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection
