@extends('layouts.app')

@section('title', 'الحجز غير متاح')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-0 text-center">
                <div class="card-body py-5 px-4">
                    <div class="text-warning mb-3">
                        <i class="fas fa-pause-circle fa-3x"></i>
                    </div>
                    <h1 class="h4 mb-3">الحجز الإلكتروني غير متاح حالياً</h1>
                    <p class="text-muted mb-0">{{ $message }}</p>
                    @isset($platformOrganizationName)
                        <p class="mt-4 small text-secondary mb-0">{{ $platformOrganizationName }}</p>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
