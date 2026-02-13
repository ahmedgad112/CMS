{{--
    Single label-value row for list-card.
    Props: label, icon (optional FontAwesome class e.g. fas fa-phone)
--}}
@props(['label', 'icon' => null])

<li class="d-flex align-items-center gap-2 py-1">
    @if($icon)
        <i class="{{ $icon }} text-primary" style="width: 1.25rem; text-align: center;"></i>
    @endif
    <span class="text-muted">{{ $label }}:</span>
    <span class="text-dark ms-auto text-end">{{ $slot }}</span>
</li>
