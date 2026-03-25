@extends('layouts.mobile')
@section('title', 'Incidents')

@section('content')

{{-- ── Search & Filter ── --}}
<form method="GET" action="{{ route('officer.incidents.index') }}" class="mb-3">
    <div class="input-group mb-2" style="border-radius:12px;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,.07);">
        <span class="input-group-text" style="background:#fff;border-color:#e2e8f0;border-right:none;padding-left:.875rem;">
            <i class="ph ph-magnifying-glass" style="color:#94a3b8;"></i>
        </span>
        <input type="text" name="search" value="{{ $search }}"
               class="form-control mob-input"
               style="border-left:none;border-color:#e2e8f0;"
               placeholder="Incident # or location...">
        <button class="btn" type="submit"
                style="background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;border:none;padding:0 1rem;font-weight:700;font-size:.85rem;min-height:44px;">
            Search
        </button>
    </div>
    <div class="d-flex gap-2">
        <select name="status" class="form-select mob-select" style="font-size:.85rem;">
            <option value="">All statuses</option>
            <option value="open"         {{ $status === 'open'         ? 'selected' : '' }}>Open</option>
            <option value="under_review" {{ $status === 'under_review' ? 'selected' : '' }}>Under Review</option>
            <option value="closed"       {{ $status === 'closed'       ? 'selected' : '' }}>Closed</option>
        </select>
        <button type="submit" class="btn flex-shrink-0"
                style="background:#fff;color:#334155;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.85rem;font-weight:600;min-height:44px;padding:0 .9rem;box-shadow:0 1px 3px rgba(0,0,0,.04);">
            <i class="ph ph-funnel-simple me-1"></i>Filter
        </button>
    </div>
</form>

{{-- ── List ── --}}
@if($incidents->isEmpty())
    <div class="mob-card">
        <div class="mob-empty">
            <i class="ph ph-flag mob-empty-icon"></i>
            <div class="mob-empty-text">No incidents found</div>
            @if($search || $status)
            <div class="mob-empty-sub">
                <a href="{{ route('officer.incidents.index') }}" style="color:#dc2626;font-weight:600;">Clear filters</a>
            </div>
            @endif
        </div>
    </div>
@else
    <div class="mob-card mb-3">
        @foreach($incidents as $inc)
        @php $sc = ['open'=>'mob-badge-open','under_review'=>'mob-badge-review','closed'=>'mob-badge-closed'][$inc->status] ?? 'mob-badge-closed' @endphp
        <a href="{{ route('officer.incidents.show', $inc) }}" class="mob-list-item">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#dc2626,#b91c1c);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-right:.875rem;box-shadow:0 2px 8px rgba(220,38,38,.25);">
                <i class="ph-fill ph-flag" style="color:#fff;font-size:.85rem;"></i>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:.875rem;font-weight:800;color:#0f172a;">{{ $inc->incident_number }}</div>
                <div style="font-size:.72rem;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:.05rem;">
                    {{ $inc->date_of_incident ? \Carbon\Carbon::parse($inc->date_of_incident)->format('M d, Y') : '—' }}
                    @if($inc->location) · {{ Str::limit($inc->location, 24) }} @endif
                </div>
                <div style="font-size:.68rem;color:#c0cad8;margin-top:.05rem;">
                    {{ $inc->motorists_count }} motorist{{ $inc->motorists_count !== 1 ? 's' : '' }} involved
                </div>
            </div>
            <div class="d-flex flex-column align-items-end gap-1 ms-2 flex-shrink-0">
                <span class="mob-badge {{ $sc }}">{{ ucfirst(str_replace('_',' ',$inc->status)) }}</span>
                <i class="ph ph-caret-right" style="color:#d6d3d1;font-size:.75rem;"></i>
            </div>
        </a>
        @endforeach
    </div>

    @if($incidents->hasPages())
    <div class="d-flex justify-content-center mb-4">
        {{ $incidents->links() }}
    </div>
    @endif
@endif

{{-- FAB --}}
<a href="{{ route('officer.incidents.create') }}" class="mob-fab mob-fab--red" title="Record Incident">
    <i class="ph-bold ph-plus"></i>
</a>

@endsection
