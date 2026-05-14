@extends('layouts.app')

@section('title', 'تعديل الدور')
@section('page-title', 'تعديل الدور')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 main-card">
            <div class="card-header bg-gradient-primary text-white">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center">
                        <div class="page-header-icon me-3">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">تعديل الدور: {{ $role->name }}</h5>
                            <small class="opacity-75">تعديل معلومات الدور والصلاحيات</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.role-permissions.show', $role) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-eye me-2"></i> عرض
                        </a>
                        <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-right me-2"></i> رجوع
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                @if($usersWithRole->count() > 0)
                <div class="alert alert-info-modern mb-4">
                    <div class="d-flex align-items-start">
                        <div class="alert-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="alert-title">ملاحظة مهمة</div>
                            <div class="alert-text">
                                هذا الدور مرتبط بـ <strong>{{ $usersWithRole->count() }}</strong> مستخدم.
                                أي تعديل في الصلاحيات سيتم تطبيقه تلقائياً على جميع المستخدمين الذين لديهم هذا الدور.
                            </div>
                            @if($usersWithRole->count() <= 8)
                            <div class="affected-users mt-3">
                                <small class="text-muted fw-semibold d-block mb-2">
                                    <i class="fas fa-users me-1"></i>المستخدمون المتأثرون:
                                </small>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($usersWithRole as $user)
                                    <span class="user-chip">
                                        <i class="fas fa-user-circle me-1"></i>{{ $user->name }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('admin.role-permissions.update', $role) }}" id="editRoleForm">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information Section -->
                    <div class="section-block mb-4">
                        <div class="section-header">
                            <div class="section-icon bg-primary-subtle text-primary">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <h6 class="section-title">معلومات أساسية</h6>
                                <small class="section-subtitle">البيانات الأساسية للدور</small>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-tag text-primary me-1"></i>
                                        اسم الدور <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           class="form-control form-control-modern @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $role->name) }}" 
                                           required
                                           placeholder="مثال: مدير النظام">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-code text-info me-1"></i>
                                        المعرف (Slug) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="slug" 
                                           class="form-control form-control-modern @error('slug') is-invalid @enderror" 
                                           value="{{ old('slug', $role->slug) }}" 
                                           required
                                           pattern="[a-z_]+"
                                           placeholder="مثال: admin_role"
                                           {{ $role->is_system ? 'readonly' : '' }}>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>أحرف صغيرة وشرطات سفلية فقط
                                    </small>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-align-right text-muted me-1"></i>
                                        الوصف
                                    </label>
                                    <textarea name="description" 
                                              class="form-control form-control-modern @error('description') is-invalid @enderror" 
                                              rows="3"
                                              placeholder="اكتب وصفاً مختصراً للدور وصلاحياته...">{{ old('description', $role->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="system-toggle">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="is_system" 
                                               id="is_system" 
                                               value="1"
                                               {{ old('is_system', $role->is_system) ? 'checked' : '' }}
                                               {{ $role->is_system ? 'disabled' : '' }}>
                                        <label class="form-check-label" for="is_system">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fas fa-shield-alt text-warning"></i>
                                                <div>
                                                    <div class="fw-semibold">دور نظام</div>
                                                    <small class="text-muted">لا يمكن حذف أدوار النظام</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Section -->
                    <div class="section-block">
                        <div class="section-header">
                            <div class="section-icon bg-warning-subtle text-warning">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="section-title">الصلاحيات</h6>
                                <small class="section-subtitle">حدد الصلاحيات الممنوحة لهذا الدور</small>
                            </div>
                            <div class="selected-counter">
                                <span class="counter-label">المحدد</span>
                                <span class="counter-value" id="selectedCount">0</span>
                                <span class="counter-divider">/</span>
                                <span class="counter-total" id="totalCount">0</span>
                            </div>
                        </div>
                        <div class="section-body">
                            <!-- Controls Bar -->
                            <div class="permissions-controls mb-3">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-sm search-input">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-search text-muted"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control border-start-0" 
                                                   id="permissionSearch"
                                                   placeholder="ابحث في الصلاحيات...">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-md-end gap-2 flex-wrap">
                                            <button type="button" class="btn btn-sm btn-primary-soft" onclick="selectAllPermissions()">
                                                <i class="fas fa-check-double me-1"></i>تحديد الكل
                                            </button>
                                            <button type="button" class="btn btn-sm btn-secondary-soft" onclick="deselectAllPermissions()">
                                                <i class="fas fa-times me-1"></i>إلغاء التحديد
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info-soft" onclick="toggleAllCategories()" id="toggleCategoriesBtn">
                                                <i class="fas fa-compress me-1"></i>طي الكل
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Permissions Container -->
                            <div class="permissions-container" id="permissionsContainer">
                                @foreach($permissions as $category => $categoryPermissions)
                                @php
                                    $categoryId = 'category_' . md5($category);
                                    $selectedInCategory = $categoryPermissions->filter(fn($p) => in_array($p->id, $selectedPermissions))->count();
                                @endphp
                                <div class="permission-category" data-category="{{ $category }}">
                                    <div class="category-header">
                                        <div class="category-header-main">
                                            <div class="category-checkbox">
                                                <input class="form-check-input category-master" 
                                                       type="checkbox" 
                                                       id="{{ $categoryId }}_master"
                                                       data-category-id="{{ $categoryId }}">
                                            </div>
                                            <div class="category-info">
                                                <h6 class="category-title">
                                                    <i class="fas fa-folder-open me-2"></i>{{ $category }}
                                                </h6>
                                                <small class="category-meta">
                                                    <span class="category-count" data-category-id="{{ $categoryId }}">
                                                        <span class="selected">{{ $selectedInCategory }}</span>
                                                        من
                                                        <span class="total">{{ $categoryPermissions->count() }}</span>
                                                        صلاحية محددة
                                                    </span>
                                                </small>
                                            </div>
                                        </div>
                                        <button type="button" class="category-toggle" data-target="{{ $categoryId }}">
                                            <i class="fas fa-chevron-up"></i>
                                        </button>
                                    </div>
                                    <div class="category-permissions" id="{{ $categoryId }}">
                                        <div class="row g-2">
                                            @foreach($categoryPermissions as $permission)
                                            <div class="col-md-6 col-lg-4 permission-col" data-permission-name="{{ strtolower($permission->name) }} {{ strtolower($permission->description ?? '') }}">
                                                <label class="permission-item" for="permission_{{ $permission->id }}">
                                                    <input class="form-check-input permission-checkbox" 
                                                           type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $permission->id }}" 
                                                           id="permission_{{ $permission->id }}"
                                                           data-category-id="{{ $categoryId }}"
                                                           {{ in_array($permission->id, $selectedPermissions) ? 'checked' : '' }}>
                                                    <div class="permission-content">
                                                        <div class="permission-name">{{ $permission->name }}</div>
                                                        @if($permission->description)
                                                            <small class="permission-desc">{{ $permission->description }}</small>
                                                        @endif
                                                    </div>
                                                    <div class="permission-indicator">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- No results message -->
                            <div id="noResults" class="empty-state-search" style="display: none;">
                                <i class="fas fa-search fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">لا توجد صلاحيات مطابقة للبحث</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </a>
                        <button type="submit" class="btn btn-success btn-save">
                            <i class="fas fa-save me-2"></i>حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateCounter() {
        const total = document.querySelectorAll('.permission-checkbox').length;
        const selected = document.querySelectorAll('.permission-checkbox:checked').length;
        document.getElementById('selectedCount').textContent = selected;
        document.getElementById('totalCount').textContent = total;

        const counter = document.querySelector('.selected-counter');
        if (selected === 0) {
            counter.classList.remove('has-selection', 'all-selected');
        } else if (selected === total) {
            counter.classList.add('has-selection', 'all-selected');
        } else {
            counter.classList.add('has-selection');
            counter.classList.remove('all-selected');
        }
    }

    function updateCategoryCount(categoryId) {
        const checkboxes = document.querySelectorAll(`.permission-checkbox[data-category-id="${categoryId}"]`);
        const checked = document.querySelectorAll(`.permission-checkbox[data-category-id="${categoryId}"]:checked`);
        const countEl = document.querySelector(`.category-count[data-category-id="${categoryId}"] .selected`);
        const master = document.querySelector(`#${categoryId}_master`);
        
        if (countEl) countEl.textContent = checked.length;
        
        if (master) {
            if (checked.length === 0) {
                master.checked = false;
                master.indeterminate = false;
            } else if (checked.length === checkboxes.length) {
                master.checked = true;
                master.indeterminate = false;
            } else {
                master.checked = false;
                master.indeterminate = true;
            }
        }

        const category = master?.closest('.permission-category');
        if (category) {
            category.classList.toggle('has-selection', checked.length > 0);
            category.classList.toggle('all-selected', checked.length === checkboxes.length && checkboxes.length > 0);
        }
    }

    function selectAllPermissions() {
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = true);
        document.querySelectorAll('.category-master').forEach(cb => {
            cb.checked = true;
            cb.indeterminate = false;
            updateCategoryCount(cb.dataset.categoryId);
        });
        updateCounter();
    }

    function deselectAllPermissions() {
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
        document.querySelectorAll('.category-master').forEach(cb => {
            cb.checked = false;
            cb.indeterminate = false;
            updateCategoryCount(cb.dataset.categoryId);
        });
        updateCounter();
    }

    function toggleAllCategories() {
        const categories = document.querySelectorAll('.permission-category');
        const btn = document.getElementById('toggleCategoriesBtn');
        const isAllCollapsed = Array.from(categories).every(c => c.classList.contains('collapsed'));
        
        categories.forEach(c => {
            c.classList.toggle('collapsed', !isAllCollapsed);
        });

        if (isAllCollapsed) {
            btn.innerHTML = '<i class="fas fa-compress me-1"></i>طي الكل';
        } else {
            btn.innerHTML = '<i class="fas fa-expand me-1"></i>توسيع الكل';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.permission-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                updateCategoryCount(this.dataset.categoryId);
                updateCounter();
            });
        });

        document.querySelectorAll('.category-master').forEach(master => {
            master.addEventListener('change', function() {
                const categoryId = this.dataset.categoryId;
                const isChecked = this.checked;
                document.querySelectorAll(`.permission-checkbox[data-category-id="${categoryId}"]`)
                    .forEach(cb => cb.checked = isChecked);
                updateCategoryCount(categoryId);
                updateCounter();
            });
        });

        document.querySelectorAll('.category-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                const category = this.closest('.permission-category');
                category.classList.toggle('collapsed');
            });
        });

        document.querySelectorAll('.category-header-main').forEach(header => {
            header.addEventListener('click', function(e) {
                if (e.target.classList.contains('form-check-input') || e.target.closest('.category-checkbox')) {
                    return;
                }
                const category = this.closest('.permission-category');
                category.classList.toggle('collapsed');
            });
        });

        const searchInput = document.getElementById('permissionSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                let visibleCount = 0;
                let visibleCategories = 0;

                document.querySelectorAll('.permission-category').forEach(category => {
                    let categoryVisible = 0;
                    const categoryName = category.dataset.category.toLowerCase();
                    
                    category.querySelectorAll('.permission-col').forEach(col => {
                        const name = col.dataset.permissionName;
                        const matches = !query || name.includes(query) || categoryName.includes(query);
                        col.style.display = matches ? '' : 'none';
                        if (matches) categoryVisible++;
                    });

                    if (categoryVisible > 0) {
                        category.style.display = '';
                        visibleCategories++;
                        visibleCount += categoryVisible;
                        if (query) category.classList.remove('collapsed');
                    } else {
                        category.style.display = 'none';
                    }
                });

                document.getElementById('noResults').style.display = visibleCategories === 0 ? 'block' : 'none';
            });
        }

        document.querySelectorAll('.category-master').forEach(master => {
            updateCategoryCount(master.dataset.categoryId);
        });
        updateCounter();
    });
</script>
@endpush

@push('styles')
<style>
    .main-card {
        border-radius: 16px;
        overflow: hidden;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%) !important;
    }

    .page-header-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        backdrop-filter: blur(10px);
    }

    /* Alert Modern */
    .alert-info-modern {
        background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
        border: 1px solid #5eead4;
        border-right: 4px solid #0d9488;
        border-radius: 12px;
        padding: 1.25rem;
    }

    .alert-icon {
        width: 42px;
        height: 42px;
        background: #0d9488;
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
        margin-left: 1rem;
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
    }

    .alert-title {
        font-weight: 700;
        color: #115e59;
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }

    .alert-text {
        color: #115e59;
        font-size: 0.92rem;
        line-height: 1.6;
    }

    .user-chip {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        background: white;
        border: 1px solid #5eead4;
        color: #115e59;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    }

    /* Section Blocks */
    .section-block {
        background: #fafbfc;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: white;
        border-bottom: 1px solid #e5e7eb;
    }

    .section-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .section-title {
        margin: 0;
        font-weight: 700;
        color: #1f2937;
        font-size: 1rem;
    }

    .section-subtitle {
        color: #6b7280;
        font-size: 0.82rem;
    }

    .section-body {
        padding: 1.25rem;
    }

    /* Form Controls */
    .form-control-modern {
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.625rem 0.875rem;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .form-control-modern:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1);
    }

    .form-control-modern[readonly] {
        background: #f3f4f6;
        cursor: not-allowed;
    }

    /* System Toggle */
    .system-toggle {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 10px;
        transition: all 0.2s;
    }

    .system-toggle:has(input:checked) {
        background: #fef3c7;
        border-color: #f59e0b;
    }

    .system-toggle .form-check-input {
        width: 1.35rem;
        height: 1.35rem;
        margin: 0;
        cursor: pointer;
    }

    .system-toggle .form-check-label {
        cursor: pointer;
        flex: 1;
        margin: 0;
    }

    /* Selected Counter */
    .selected-counter {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #f3f4f6;
        padding: 0.5rem 1rem;
        border-radius: 24px;
        border: 1.5px solid #e5e7eb;
        font-weight: 600;
        transition: all 0.3s;
    }

    .selected-counter.has-selection {
        background: linear-gradient(135deg, #ccfbf1 0%, #a7f3d0 100%);
        border-color: #0d9488;
    }

    .selected-counter.all-selected {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        border-color: #16a34a;
    }

    .counter-label {
        font-size: 0.78rem;
        color: #6b7280;
        font-weight: 500;
    }

    .counter-value {
        font-size: 1.1rem;
        color: #0d9488;
        font-weight: 700;
    }

    .all-selected .counter-value {
        color: #16a34a;
    }

    .counter-divider {
        color: #9ca3af;
    }

    .counter-total {
        color: #6b7280;
        font-weight: 600;
    }

    /* Permissions Controls */
    .permissions-controls {
        background: white;
        padding: 0.875rem;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
    }

    .search-input .input-group-text {
        border: 1.5px solid #e5e7eb;
        border-left: none;
    }

    .search-input .form-control {
        border: 1.5px solid #e5e7eb;
        border-right: none;
    }

    .search-input .form-control:focus {
        box-shadow: none;
        border-color: #0d9488;
    }

    .search-input .form-control:focus + .input-group-text,
    .search-input:focus-within .input-group-text {
        border-color: #0d9488;
    }

    /* Soft Buttons */
    .btn-primary-soft {
        background: #ccfbf1;
        color: #0f766e;
        border: 1px solid #a7f3d0;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-primary-soft:hover {
        background: #0d9488;
        color: white;
        border-color: #0d9488;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
    }

    .btn-secondary-soft {
        background: #f3f4f6;
        color: #4b5563;
        border: 1px solid #e5e7eb;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-secondary-soft:hover {
        background: #6b7280;
        color: white;
        border-color: #6b7280;
        transform: translateY(-1px);
    }

    .btn-info-soft {
        background: #cffafe;
        color: #0e7490;
        border: 1px solid #a5f3fc;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-info-soft:hover {
        background: #06b6d4;
        color: white;
        border-color: #06b6d4;
        transform: translateY(-1px);
    }

    /* Permission Category */
    .permission-category {
        background: white;
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1rem;
        transition: all 0.3s;
    }

    .permission-category.has-selection {
        border-color: #5eead4;
        box-shadow: 0 2px 8px rgba(13, 148, 136, 0.08);
    }

    .permission-category.all-selected {
        border-color: #86efac;
        box-shadow: 0 2px 8px rgba(22, 163, 74, 0.1);
    }

    .category-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.875rem 1rem;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-bottom: 1px solid #e5e7eb;
        transition: all 0.2s;
    }

    .permission-category.has-selection .category-header {
        background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
    }

    .permission-category.all-selected .category-header {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    }

    .category-header-main {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        cursor: pointer;
        flex: 1;
    }

    .category-checkbox {
        display: flex;
        align-items: center;
    }

    .category-checkbox .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        margin: 0;
        cursor: pointer;
        border-width: 2px;
    }

    .category-info {
        flex: 1;
    }

    .category-title {
        margin: 0 0 0.15rem 0;
        font-size: 0.98rem;
        font-weight: 700;
        color: #1f2937;
    }

    .category-meta {
        color: #6b7280;
        font-size: 0.78rem;
    }

    .category-count .selected {
        color: #0d9488;
        font-weight: 700;
    }

    .all-selected .category-count .selected {
        color: #16a34a;
    }

    .category-toggle {
        background: white;
        border: 1px solid #e5e7eb;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s;
    }

    .category-toggle:hover {
        background: #0d9488;
        color: white;
        border-color: #0d9488;
    }

    .category-toggle i {
        transition: transform 0.3s;
    }

    .permission-category.collapsed .category-toggle i {
        transform: rotate(180deg);
    }

    .category-permissions {
        padding: 1rem;
        max-height: 1000px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .permission-category.collapsed .category-permissions {
        max-height: 0;
        padding-top: 0;
        padding-bottom: 0;
    }

    /* Permission Item */
    .permission-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 0.875rem;
        background: #f9fafb;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        margin: 0;
        position: relative;
        height: 100%;
    }

    .permission-item:hover {
        background: white;
        border-color: #5eead4;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .permission-item:has(.permission-checkbox:checked) {
        background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
        border-color: #0d9488;
    }

    .permission-checkbox {
        width: 1.15rem;
        height: 1.15rem;
        margin: 0;
        cursor: pointer;
        flex-shrink: 0;
        border-width: 2px;
    }

    .permission-content {
        flex: 1;
        min-width: 0;
    }

    .permission-name {
        font-weight: 600;
        color: #1f2937;
        font-size: 0.9rem;
        line-height: 1.3;
    }

    .permission-desc {
        color: #6b7280;
        font-size: 0.78rem;
        display: block;
        margin-top: 0.15rem;
    }

    .permission-indicator {
        width: 24px;
        height: 24px;
        background: #16a34a;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        opacity: 0;
        transform: scale(0);
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .permission-item:has(.permission-checkbox:checked) .permission-indicator {
        opacity: 1;
        transform: scale(1);
    }

    /* Empty State */
    .empty-state-search {
        text-align: center;
        padding: 3rem 1rem;
        background: #f9fafb;
        border-radius: 12px;
        border: 2px dashed #e5e7eb;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 0.75rem;
        padding: 1.25rem 0 0 0;
        margin-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
        flex-wrap: wrap;
    }

    .btn-save {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        border: none;
        color: white;
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        transition: all 0.2s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(22, 163, 74, 0.4);
        color: white;
    }

    .btn-outline-secondary {
        padding: 0.625rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 767.98px) {
        .section-header {
            flex-wrap: wrap;
        }

        .selected-counter {
            width: 100%;
            justify-content: center;
            margin-top: 0.5rem;
        }

        .permissions-controls .col-md-6 {
            margin-bottom: 0.5rem;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .form-actions .btn {
            width: 100%;
        }

        .category-header {
            flex-wrap: wrap;
        }
    }

    /* Form Check Custom */
    .form-check-input:checked {
        background-color: #0d9488;
        border-color: #0d9488;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 148, 136, 0.25);
    }

    .form-check-input:indeterminate {
        background-color: #0d9488;
        border-color: #0d9488;
    }
</style>
@endpush
@endsection
