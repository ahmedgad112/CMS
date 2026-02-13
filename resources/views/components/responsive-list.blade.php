{{--
    Responsive list: table on medium+ screens, cards on small screens.
    Slots: table (full table markup), cards (card list markup).
    Optional: breakpoint = 'md' (default) or 'lg'
--}}
@props(['breakpoint' => 'md'])

@php
    $tableClass = $breakpoint === 'lg' ? 'd-none d-lg-block' : 'd-none d-md-block';
    $cardsClass = $breakpoint === 'lg' ? 'd-lg-none' : 'd-md-none';
@endphp

{{-- Table: visible from breakpoint up --}}
<div class="table-responsive {{ $tableClass }}">
    {{ $table ?? '' }}
</div>

{{-- Cards: visible below breakpoint --}}
<div class="responsive-list-cards {{ $cardsClass }}">
    {{ $cards ?? '' }}
</div>
