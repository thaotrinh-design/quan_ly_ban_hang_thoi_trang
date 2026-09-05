@props(['history'])

@if($history->count() > 0)
<div class="order-timeline mt-4">
    <h5><i class="fa-solid fa-clock-rotate-left me-1"></i> Lịch sử trạng thái</h5>

    <div class="timeline-container" style="position: relative; padding-left: 30px;">
        @foreach($history as $index => $entry)
        <div class="timeline-item mb-3" style="position: relative;">
            @if($index < $history->count() - 1)
            <div class="timeline-line" style="position: absolute; left: -22px; top: 20px; bottom: -12px; width: 2px; background: #dee2e6;"></div>
            @endif

            <div class="timeline-dot" style="position: absolute; left: -28px; top: 4px; width: 14px; height: 14px; border-radius: 50%;
                background: {{ match($entry->status) {
                    'pending' => '#ffc107',
                    'processing' => '#0dcaf0',
                    'shipping' => '#0d6efd',
                    'completed' => '#198754',
                    'cancelled' => '#dc3545',
                    default => '#6c757d'
                }}; border: 2px solid white; box-shadow: 0 0 0 2px {{ match($entry->status) {
                    'pending' => '#ffc107',
                    'processing' => '#0dcaf0',
                    'shipping' => '#0d6efd',
                    'completed' => '#198754',
                    'cancelled' => '#dc3545',
                    default => '#6c757d'
                }};">
            </div>

            <div class="card {{ $index === 0 ? 'border-primary' : '' }}">
                <div class="card-body py-2 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge {{ match($entry->status) {
                                'pending' => 'bg-warning',
                                'processing' => 'bg-info',
                                'shipping' => 'bg-primary',
                                'completed' => 'bg-success',
                                'cancelled' => 'bg-danger',
                                default => 'bg-secondary'
                            }} me-2">
                                {{ $entry->status_label }}
                            </span>
                            @if($entry->note)
                                <small class="text-muted">{{ $entry->note }}</small>
                            @endif
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block">{{ $entry->created_at->format('d/m/Y H:i') }}</small>
                            @if($entry->changed_by)
                                <small class="text-muted">by {{ $entry->changed_by }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="alert alert-info mt-4">
    <i class="fa-solid fa-info-circle me-1"></i> Chưa có lịch sử trạng thái.
</div>
@endif
