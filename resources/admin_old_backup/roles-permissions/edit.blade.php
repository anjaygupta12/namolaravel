@extends('layouts.admin')

@section('title', 'Edit Role Permissions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Permissions for Role: {{ $role->name }}</h4>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-primary float-right">
                        <i class="fas fa-arrow-left"></i> Back to Roles
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.roles.update-permissions', $role->id) }}" method="POST">
                        @csrf

                        @foreach($permissions as $model => $modelPermissions)
                        <div class="permission-section mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0 font-weight-bold text-dark">
                                    <i class="fas fa-folder mr-2"></i>{{ $model ?: 'General' }} Permissions
                                </h5>
                                <button type="button" class="btn btn-sm btn-link text-primary toggle-group-btn" data-group="{{ Str::slug($model) }}">
                                    <i class="fas fa-sync-alt mr-1"></i> Toggle All
                                </button>
                            </div>

                            <div class="row">
                                @foreach($modelPermissions as $permission)
                                <div class="col-md-2 col-sm-4 col-6 mb-2">
                                    <div class="permission-item d-flex flex-column">
                                        <label class="permission-name mb-1 text-truncate" for="perm_{{ $permission->id }}" title="{{ $permission->name }}">
                                            {{ Str::title(str_replace('-', ' ', $permission->name)) }}
                                        </label>
                                        <div class="d-flex align-items-center">
                                            <div class="sleek-toggle">
                                                <input type="checkbox"
                                                       id="perm_{{ $permission->id }}"
                                                       name="permissions[]"
                                                       value="{{ $permission->id }}"
                                                       class="sleek-toggle-input"
                                                       data-group="{{ Str::slug($model) }}"
                                                       {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                                <label class="sleek-toggle-label" for="perm_{{ $permission->id }}"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-save mr-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .permission-section {
        padding: 0.5rem 0;
    }

    .permission-item {
        padding: 0.5rem;
    }

    .permission-name {
        font-weight: 500;
        font-size: 0.85rem;
        color: #343a40;
        margin-bottom: 0.25rem !important;
    }

    .permission-slug {
        font-size: 0.7rem;
        max-width: 60px;
        display: inline-block;
    }

    /* Sleek Toggle Switch */
    .sleek-toggle {
        position: relative;
        display: inline-block;
    }

    .sleek-toggle-input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .sleek-toggle-label {
        position: relative;
        display: inline-block;
        width: 42px;
        height: 22px;
        background-color: #e9ecef;
        border-radius: 11px;
        cursor: pointer;
        transition: background-color 0.2s ease;
        vertical-align: middle;
    }

    .sleek-toggle-label:before {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 18px;
        height: 18px;
        background-color: white;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease;
    }

    .sleek-toggle-input:checked + .sleek-toggle-label {
        background-color: #4dabf7;
    }

    .sleek-toggle-input:checked + .sleek-toggle-label:before {
        transform: translateX(20px);
    }

    .sleek-toggle-input:focus + .sleek-toggle-label {
        box-shadow: 0 0 0 2px rgba(77, 171, 247, 0.25);
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .col-md-2 {
            flex: 0 0 33.33333%;
            max-width: 33.33333%;
        }
    }

    @media (max-width: 768px) {
        .col-md-2 {
            flex: 0 0 50%;
            max-width: 50%;
        }

        .permission-slug {
            display: none;
        }
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Toggle all permissions in a group
    $('.toggle-group-btn').click(function() {
        const group = $(this).data('group');
        const checkboxes = $(`.sleek-toggle-input[data-group="${group}"]`);
        const allChecked = checkboxes.length === $(`.sleek-toggle-input[data-group="${group}"]:checked`).length;

        checkboxes.prop('checked', !allChecked).trigger('change');
    });

    // Toggle switch visual feedback
    $('.sleek-toggle-input').change(function() {
        const label = $(this).next('.sleek-toggle-label');
        label.css('background-color', $(this).is(':checked') ? '#4dabf7' : '#e9ecef');
    }).trigger('change');

    // Tooltips for permission names
    $('[title]').tooltip({
        placement: 'top',
        trigger: 'hover'
    });
});
</script>
@endsection
