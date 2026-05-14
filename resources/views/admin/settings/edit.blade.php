@extends('layouts.app')

@section('title', 'إعدادات المنصة')
@section('page-title', 'إعدادات المنصة')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <div class="page-header-icon">
            <i class="fas fa-sliders-h"></i>
        </div>
        <h5 class="mb-0 ms-2">إعدادات المنصة</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <p class="text-muted mb-4">
            تتحكم هذه الصفحة في سلوك النظام العام — الفواتير، والحجز الإلكتروني، واسم المنشأة الظاهر للمراجعين.
        </p>

        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

            @foreach($sections as $sectionKey => $section)
                <div class="border rounded overflow-hidden mb-4">
                    <div class="px-4 py-3 bg-light border-bottom d-flex align-items-center gap-2">
                        <i class="fas {{ $section['icon'] ?? 'fa-cog' }} text-primary"></i>
                        <div>
                            <h6 class="mb-0 fw-semibold">{{ $section['label'] ?? $sectionKey }}</h6>
                            @if(!empty($section['intro']))
                                <small class="text-muted">{{ $section['intro'] }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="p-4">
                        @foreach($section['settings'] ?? [] as $settingKey => $meta)
                            @php
                                $type = $meta['type'] ?? 'string';
                                $val = $values[$settingKey] ?? null;
                            @endphp

                            @if($type === 'boolean')
                                <div class="mb-4 pb-4 border-bottom">
                                    <input type="hidden" name="{{ $settingKey }}" value="0">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                               id="{{ $settingKey }}" name="{{ $settingKey }}" value="1"
                                               {{ old($settingKey, $val) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="{{ $settingKey }}">
                                            {{ $meta['label'] ?? $settingKey }}
                                        </label>
                                    </div>
                                    @if(!empty($meta['help']))
                                        <p class="text-muted small mb-0 mt-2 ms-1">{{ $meta['help'] }}</p>
                                    @endif
                                    @error($settingKey)
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            @elseif($type === 'text')
                                <div class="mb-4 pb-4 border-bottom">
                                    <label class="form-label fw-semibold" for="{{ $settingKey }}">{{ $meta['label'] ?? $settingKey }}</label>
                                    <textarea class="form-control @error($settingKey) is-invalid @enderror"
                                              id="{{ $settingKey }}"
                                              name="{{ $settingKey }}"
                                              rows="3"
                                              placeholder="{{ $meta['placeholder'] ?? '' }}">{{ old($settingKey, $val) }}</textarea>
                                    @if(!empty($meta['help']))
                                        <div class="form-text">{{ $meta['help'] }}</div>
                                    @endif
                                    @error($settingKey)
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @else
                                <div class="mb-4 pb-4 border-bottom">
                                    <label class="form-label fw-semibold" for="{{ $settingKey }}">{{ $meta['label'] ?? $settingKey }}</label>
                                    <input type="text"
                                           class="form-control @error($settingKey) is-invalid @enderror"
                                           id="{{ $settingKey }}"
                                           name="{{ $settingKey }}"
                                           value="{{ old($settingKey, $val) }}"
                                           placeholder="{{ $meta['placeholder'] ?? '' }}">
                                    @if(!empty($meta['help']))
                                        <div class="form-text">{{ $meta['help'] }}</div>
                                    @endif
                                    @error($settingKey)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> حفظ الإعدادات
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">العودة للوحة التحكم</a>
            </div>
        </form>
    </div>
</div>
@endsection
