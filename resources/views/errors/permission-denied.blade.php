@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="d-flex align-items-center justify-content-center" style="min-height: 70vh;">
            <div class="text-center">
                <div class="mb-4">
                    <i class="ti ti-lock" style="font-size: 80px; color: #dc3545;"></i>
                </div>
                <h1 class="fw-bold mb-2" style="font-size: 48px; color: #333;">403</h1>
                <h4 class="text-danger fw-bold mb-3">Permission Denied</h4>
                <p class="text-muted mb-4" style="font-size: 16px; max-width: 500px;">
                    You don't have permission to access this page. Please contact your administrator if you believe this is a mistake.
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="ti ti-home me-1"></i>Go to Dashboard
                    </a>
                    <a href="javascript:history.back()" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>Go Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
