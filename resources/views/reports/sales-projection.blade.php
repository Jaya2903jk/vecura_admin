@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">📊 Sales Projection & Target Report</h1>
            <div class="btn-group">
                <a href="{{ route('sales-report.export-pdf', request()->query()) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="{{ route('sales-report.export-excel', request()->query()) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Month</label>
                        <input type="month" name="month_year" class="form-control" value="{{ $monthYear }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Target Type</label>
                        <select name="target_type" class="form-select">
                            <option value="">All</option>
                            <option value="day" {{ $targetType == 'day' ? 'selected' : '' }}>Day</option>
                            <option value="month" {{ $targetType == 'month' ? 'selected' : '' }}>Month</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Branch</label>
                        <select name="branch_id" class="form-select">
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->branch_id }}" {{ $branchId == $branch->branch_id ? 'selected' : '' }}>
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Employee</label>
                        <select name="employee_id" class="form-select">
                            <option value="">All Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->UserID }}" {{ $employeeId == $emp->UserID ? 'selected' : '' }}>
                                    {{ $emp->FullName }} ({{ $emp->UserCode }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Total Daily Sales</h6>
                        <h3 class="text-primary">₹ {{ number_format($reportData['summary']['daily_sales'], 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="text-muted">In-House Sales</h6>
                        <h3 class="text-success">₹ {{ number_format($reportData['summary']['in_house_sales'], 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Nutritionist Sales</h6>
                        <h3 class="text-info">₹ {{ number_format($reportData['summary']['nutritionist_sales'], 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Total Revenue</h6>
                        <h3 class="text-warning">₹ {{ number_format($reportData['summary']['total_revenue'], 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Branch-wise Report -->
        @if(count($reportData['branch_data']) > 0)
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">📍 Branch-wise Performance</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Branch</th>
                            <th class="text-end">Daily Sales</th>
                            <th class="text-end">In-House</th>
                            <th class="text-end">Nutritionist</th>
                            <th class="text-end">Consultant</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Visits</th>
                            <th class="text-end">Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['branch_data'] as $branch)
                        <tr>
                            <td><strong>{{ $branch['branch_name'] }}</strong></td>
                            <td class="text-end">₹{{ number_format($branch['daily_sales'], 2) }}</td>
                            <td class="text-end">₹{{ number_format($branch['in_house_sales'], 2) }}</td>
                            <td class="text-end">₹{{ number_format($branch['nutritionist_sales'], 2) }}</td>
                            <td class="text-end">₹{{ number_format($branch['consultant_sales'], 2) }}</td>
                            <td class="text-end"><strong>₹{{ number_format($branch['total_sales'], 2) }}</strong></td>
                            <td class="text-end">{{ $branch['visits'] }}</td>
                            <td class="text-end">{{ $branch['joined'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Summary Statistics -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">📈 Key Metrics</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Total Visits:</strong></td>
                                <td class="text-end">{{ $reportData['summary']['total_visits'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Joined:</strong></td>
                                <td class="text-end">{{ $reportData['summary']['total_joined'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Conversion Rate:</strong></td>
                                <td class="text-end">{{ $reportData['summary']['conversion_percent'] }}%</td>
                            </tr>
                            <tr>
                                <td><strong>CC Appointments:</strong></td>
                                <td class="text-end">
                                    <span class="badge bg-info">{{ $reportData['summary']['total_cc_appointments'] }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">📊 Report Period</h5>
                    </div>
                    <div class="card-body text-center">
                        <h4>{{ \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->format('F Y') }}</h4>
                        <p class="text-muted">Generated: {{ now()->format('d M Y H:i A') }}</p>
                        <hr>
                        <div class="progress mb-3" style="height: 30px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ min(100, $reportData['summary']['conversion_percent']) }}%">
                                {{ $reportData['summary']['conversion_percent'] }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Actions -->
        <div class="mt-4 text-center">
            <a href="{{ route('sales-report.export-pdf', request()->query()) }}" class="btn btn-lg btn-danger">
                <i class="fas fa-file-pdf"></i> Export as PDF
            </a>
            <a href="{{ route('sales-report.export-excel', request()->query()) }}" class="btn btn-lg btn-success">
                <i class="fas fa-file-excel"></i> Export as Excel
            </a>
        </div>
    </div>
</div>
@endsection
