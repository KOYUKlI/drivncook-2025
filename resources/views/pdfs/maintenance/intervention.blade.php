@extends('layouts.pdf')

@section('title', __('maintenance.maintenance') . ' – ' . ($log->truck->plate ?? $log->truck_id))
@section('period')
    {{ __('maintenance.opened_at') }}: {{ optional($log->opened_at ?? $log->started_at)->format('Y-m-d H:i') }}
@endsection

@section('content')
    <div class="section">
        <h1 class="brand">{{ __('maintenance.maintenance_details') }}</h1>
        <p class="muted small">{{ __('maintenance.truck') }}: <strong>{{ $log->truck->plate ?? $log->truck_id }}</strong></p>
    </div>

    <div class="grid section">
        <div class="col" style="width: 50%">
            <div class="kpi">
                <div class="label">{{ __('maintenance.status') }}</div>
                <div class="value">{{ $log->status ?? (empty($log->closed_at) ? 'open' : 'closed') }}</div>
            </div>
        </div>
        <div class="col" style="width: 50%">
            <div class="kpi">
                <div class="label">{{ __('maintenance.total_cost') }}</div>
                <div class="value">
                    @php $cents = (int)($log->cost_cents ?? 0); @endphp
                    € {{ number_format($cents / 100, 2, ',', ' ') }}
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">{{ __('maintenance.description') }}</h2>
        <div class="card">
            <p>{{ $log->description ?? '—' }}</p>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">{{ __('maintenance.resolution') }}</h2>
        <div class="card">
            <p>{{ $log->resolution ?? '—' }}</p>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">{{ __('maintenance.attachments') }}</h2>
        <table class="table-4col">
            <thead>
                <tr>
                    <th>{{ __('ui.labels.file') }}</th>
                    <th class="num">{{ __('maintenance.file_type') }}</th>
                    <th class="num">{{ __('maintenance.file_size') }}</th>
                    <th class="num">{{ __('maintenance.uploaded_by') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($log->attachments ?? []) as $att)
                    <tr>
                        <td>{{ $att->label ?? basename($att->path) }}</td>
                        <td class="num">{{ $att->mime_type ?? pathinfo($att->path, PATHINFO_EXTENSION) }}</td>
                        <td class="num">{{ method_exists($att, 'formatted_size') ? $att->formatted_size : (($att->size_bytes ?? $att->size ?? 0) . ' B') }}</td>
                        <td class="num">{{ optional($att->uploader)->name ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="muted">{{ __('maintenance.no_attachments') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
