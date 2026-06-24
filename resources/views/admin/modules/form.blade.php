@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <h1>{{ isset($module) ? 'Edit Module' : 'Create Module' }}</h1>
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ isset($module) ? route('modules.update', $module->id) : route('modules.store') }}">
                            @csrf
                            @if(isset($module))
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Module Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ isset($module) ? $module->name : old('name') }}"
                                    {{ isset($module) ? 'readonly' : 'required' }}>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">e.g., customer_master, purchase_order</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                                    value="{{ isset($module) ? $module->description : old('description') }}" required>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">e.g., Customer Master, Purchase Order</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Route Prefix <span class="text-danger">*</span></label>
                                <input type="text" name="route_prefix" class="form-control @error('route_prefix') is-invalid @enderror"
                                    value="{{ isset($module) ? $module->route_prefix : old('route_prefix') }}" required>
                                @error('route_prefix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">e.g., customer_master, purchase (routes: customer_master.index, customer_master.create)</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Icon (Tabler Icon)</label>
                                <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror"
                                    value="{{ isset($module) ? $module->icon : old('icon', 'ti-world-cog') }}">
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">e.g., ti-users, ti-briefcase, ti-building (from <a href="https://tabler-icons.io" target="_blank">tabler-icons.io</a>)</small>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" value="1"
                                        {{ (isset($module) && $module->is_active) || !isset($module) ? 'checked' : '' }}>
                                    <label class="form-check-label">Active (Show in sidebar)</label>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($module) ? 'Update Module' : 'Create Module' }}
                                </button>
                                <a href="{{ route('modules.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
