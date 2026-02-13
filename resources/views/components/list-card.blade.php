{{--
    Reusable list item card for mobile view.
    Props: title, titleUrl (optional), badge (optional), badgeVariant (primary|success|warning|danger|info)
    Slots: fields (list of label-value pairs), actions (buttons/links), default slot (custom content)
--}}
@props([
    'title' => null,
    'titleUrl' => null,
    'badge' => null,
    'badgeVariant' => 'primary',
])

<div class="card list-card border shadow-sm mb-3 list-card-hover">
    <div class="card-body">
        @if($title || $badge)
            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                <div class="flex-grow-1 min-width-0">
                    @if($title)
                        @if($titleUrl)
                            <a href="{{ $titleUrl }}" class="text-decoration-none fw-bold text-dark stretched-link">
                                {{ $title }}
                            </a>
                        @else
                            <span class="fw-bold text-dark">{{ $title }}</span>
                        @endif
                    @endif
                </div>
                @if($badge)
                    <span class="badge bg-{{ $badgeVariant }} text-nowrap">{{ $badge }}</span>
                @endif
            </div>
        @endif

        @if(isset($fields))
            <ul class="list-unstyled mb-0 small text-muted">
                {{ $fields }}
            </ul>
        @endif

        {{ $slot }}

        @if(isset($actions))
            <div class="list-card-actions mt-3 pt-3 border-top d-flex flex-wrap gap-2">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
