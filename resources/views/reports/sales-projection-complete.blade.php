@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">📊 Sales Projection - All</h1>
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

        <!-- Date & Summary -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">{{ \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->format('d-M-Y') }}</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>VeCura</span>
                            <strong>With GST</strong>
                            <strong>Total</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>New visit</span>
                            <span>{{ $reportData['summary']['total_visits'] }}</span>
                            <span>-</span>
                            <span>{{ $reportData['summary']['total_visits'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Regular visit</span>
                            <span>0</span>
                            <span>-</span>
                            <span>0</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2 mt-2">
                            <span><strong>Total visit</strong></span>
                            <span><strong>{{ $reportData['summary']['total_visits'] }}</strong></span>
                            <span><strong>-</strong></span>
                            <span><strong>{{ $reportData['summary']['total_visits'] }}</strong></span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>New Revenue</span>
                            <span>₹ {{ number_format($reportData['summary']['daily_sales'], 0) }}</span>
                            <span>₹ {{ number_format($reportData['summary']['daily_sales'] * 1.18, 0) }}</span>
                            <span>₹ {{ number_format($reportData['summary']['daily_sales'], 0) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Regular Revenue</span>
                            <span>0</span>
                            <span>0</span>
                            <span>0</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2 mt-2">
                            <span><strong>Total Revenue</strong></span>
                            <span><strong>₹ {{ number_format($reportData['summary']['daily_sales'], 0) }}</strong></span>
                            <span><strong>₹ {{ number_format($reportData['summary']['daily_sales'] * 1.18, 0) }}</strong></span>
                            <span><strong>₹ {{ number_format($reportData['summary']['daily_sales'], 0) }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Summary Table -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">📈 {{ \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->format('M-Y') }} - VeCura Sales Summary</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Metric</th>
                            <th class="text-end">Visits</th>
                            <th class="text-end">Conversion</th>
                            <th class="text-center">Conversion %</th>
                            <th class="text-end">Advance</th>
                            <th class="text-end">Target</th>
                            <th class="text-end">Achieved</th>
                            <th class="text-end">%</th>
                            <th class="text-end">Lagging</th>
                            <th class="text-end">To be Achieved</th>
                            <th class="text-center">Balance</th>
                            <th class="text-center">Projection %</th>
                            <th class="text-end">Projection</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>VeCura</strong></td>
                            <td class="text-end">{{ $reportData['summary']['total_visits'] }}</td>
                            <td class="text-end">{{ $reportData['summary']['total_joined'] }}</td>
                            <td class="text-center">{{ $reportData['summary']['conversion_percent'] }} %</td>
                            <td class="text-end">{{ $reportData['summary']['total_cc_appointments'] }}</td>
                            <td class="text-end">0</td>
                            <td class="text-end">₹ {{ number_format($reportData['summary']['daily_sales'], 0) }}</td>
                            <td class="text-end">? %</td>
                            <td class="text-end">₹ {{ number_format($reportData['summary']['daily_sales'], 0) }}</td>
                            <td class="text-end">0</td>
                            <td class="text-center">-₹ {{ number_format($reportData['summary']['daily_sales'], 0) }}</td>
                            <td class="text-center">? %</td>
                            <td class="text-end">₹ {{ number_format($reportData['summary']['daily_sales'], 0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Branch-wise Location Performance -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">📍 TAMILNADU - Location Wise Performance</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Location</th>
                            <th class="text-center">VeCura Sales</th>
                            <th class="text-end">Month Target</th>
                            <th class="text-end">Achieved</th>
                            <th class="text-end">%</th>
                            <th class="text-end">Lagging</th>
                            <th class="text-center">Proj. %</th>
                            <th class="text-end">Projection</th>
                            <th class="text-end">Visit</th>
                            <th class="text-end">Joined</th>
                            <th class="text-end">Day Target</th>
                            <th class="text-end">Day Sale</th>
                            <th class="text-center">%</th>
                            <th class="text-end">Appt.</th>
                            <th class="text-end">Visit</th>
                            <th class="text-end">Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['branch_data'] as $branch)
                        <tr>
                            <td><strong>{{ $branch['branch_name'] }}</strong></td>
                            <td class="text-center">{{ \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->format('M-Y') }}</td>
                            <td class="text-end">0</td>
                            <td class="text-end">₹ {{ number_format($branch['total_sales'], 0) }}</td>
                            <td class="text-end">0 %</td>
                            <td class="text-end">₹ {{ number_format($branch['total_sales'], 0) }}</td>
                            <td class="text-center">? %</td>
                            <td class="text-end">₹ {{ number_format($branch['total_sales'], 0) }}</td>
                            <td class="text-end">{{ $branch['visits'] }}</td>
                            <td class="text-end">{{ $branch['joined'] }}</td>
                            <td class="text-end">0</td>
                            <td class="text-end">₹ {{ number_format($branch['daily_sales'], 0) }}</td>
                            <td class="text-center">? %</td>
                            <td class="text-end">0</td>
                            <td class="text-end">0</td>
                            <td class="text-end">0</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sales by Type -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">💼 In-House Sales</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Branch</th>
                                    <th class="text-end">Day Target</th>
                                    <th class="text-end">Day Sales</th>
                                    <th class="text-center">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reportData['branch_data'] as $branch)
                                <tr>
                                    <td>{{ $branch['branch_name'] }}</td>
                                    <td class="text-end">0</td>
                                    <td class="text-end">₹ {{ number_format($branch['in_house_sales'], 0) }}</td>
                                    <td class="text-center">? %</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted">No data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">🥗 Nutritionist Sales</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Branch</th>
                                    <th class="text-end">Sales Amount</th>
                                    <th class="text-end">Consultations</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reportData['branch_data'] as $branch)
                                <tr>
                                    <td>{{ $branch['branch_name'] }}</td>
                                    <td class="text-end">₹ {{ number_format($branch['nutritionist_sales'], 0) }}</td>
                                    <td class="text-end">-</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center text-muted">No data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overall Summary Cards -->
        <div class="row">
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
                        <h6 class="text-muted">Total In-House Sales</h6>
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
                        <h6 class="text-muted">Consultant Sales</h6>
                        <h3 class="text-warning">₹ {{ number_format($reportData['summary']['consultant_sales'], 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="card mt-4">
            <div class="card-body text-center">
                <h4 class="mb-3">Total Revenue</h4>
                <h2 class="text-success mb-3">₹ {{ number_format($reportData['summary']['total_revenue'], 2) }}</h2>
                <div class="progress" style="height: 30px;">
                    <div class="progress-bar bg-success" style="width: 100%">100%</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
