# Permission Checks - Implementation Guide

## Overview
Add permission checks to index pages to show/hide buttons based on user roles and permissions.

## Pattern to Use

```blade
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('ACTION', 'MODULE'))
    <!-- Show button/content -->
@endif
```

## Actions & Modules

**Actions:** read, create, edit, delete, approve

**Modules:** department, designation, branch, location, country, zone, state, city, employee, ticket, iou, pcrequest, staff, invoice, etc.

---

## Common Pages - Where to Add

### 1. Branch/New Branch Index Page
**File:** `resources/views/new-branch/index.blade.php`

```blade
<!-- Add Button -->
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'branch'))
    <a href="{{ route('new-branch.create') }}" class="btn btn-primary">
        <i class="ti ti-plus"></i> Add Branch
    </a>
@endif

<!-- Edit Button (in table row) -->
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'branch'))
    <a href="{{ route('new-branch.edit', $branch->id) }}" class="btn btn-sm btn-primary">
        Edit
    </a>
@endif

<!-- Delete Button (in table row) -->
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('delete', 'branch'))
    <button class="btn btn-sm btn-danger" onclick="deleteBranch({{ $branch->id }})">
        Delete
    </button>
@endif
```

### 2. Department Index Page
**File:** `resources/views/department/index.blade.php`

```blade
<!-- Create Button -->
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'department'))
    <a href="{{ route('department.create') }}" class="btn btn-primary">
        <i class="ti ti-plus"></i> Add Department
    </a>
@endif

<!-- Edit in Table -->
@foreach($departments as $dept)
    <tr>
        <td>{{ $dept->name }}</td>
        <td>
            @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'department'))
                <a href="{{ route('department.edit', $dept->id) }}" class="btn btn-sm btn-primary">Edit</a>
            @endif
            
            @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('delete', 'department'))
                <button class="btn btn-sm btn-danger" onclick="deleteDept({{ $dept->id }})">Delete</button>
            @endif
        </td>
    </tr>
@endforeach
```

### 3. Designation Index Page
**File:** `resources/views/designation/index.blade.php`

```blade
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'designation'))
    <a href="{{ route('designation.create') }}" class="btn btn-primary">Add Designation</a>
@endif
```

### 4. Location Index Page
**File:** `resources/views/location/index.blade.php`

```blade
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'location'))
    <a href="{{ route('location.create') }}" class="btn btn-primary">Add Location</a>
@endif
```

### 5. Zone Index Page
**File:** `resources/views/zone/index.blade.php`

```blade
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'zone'))
    <a href="{{ route('zone.create') }}" class="btn btn-primary">Add Zone</a>
@endif
```

### 6. Country Index Page
**File:** `resources/views/country/index.blade.php`

```blade
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'country'))
    <a href="{{ route('country.create') }}" class="btn btn-primary">Add Country</a>
@endif
```

### 7. State Index Page
**File:** `resources/views/state/index.blade.php`

```blade
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'state'))
    <a href="{{ route('state.create') }}" class="btn btn-primary">Add State</a>
@endif
```

### 8. City Index Page
**File:** `resources/views/city/index.blade.php`

```blade
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'city'))
    <a href="{{ route('city.create') }}" class="btn btn-primary">Add City</a>
@endif
```

### 9. Staff Index Page
**File:** `resources/views/staff/index.blade.php`

```blade
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'staff'))
    <a href="{{ route('staff.create') }}" class="btn btn-primary">Add Staff</a>
@endif

@foreach($staff as $item)
    <tr>
        <td>{{ $item->staff_name }}</td>
        <td>
            @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'staff'))
                <a href="{{ route('staff.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
            @endif
            
            @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('delete', 'staff'))
                <button class="btn btn-sm btn-danger">Delete</button>
            @endif
        </td>
    </tr>
@endforeach
```

### 10. Tickets Index Page
**File:** `resources/views/tickets.blade.php` (or similar)

```blade
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'ticket'))
    <a href="{{ route('tickets.create') }}" class="btn btn-primary">Create Ticket</a>
@endif

<!-- Approve Button (if user has approve permission) -->
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('approve', 'ticket'))
    <button onclick="approveTicket({{ $ticket->id }})" class="btn btn-success">Approve</button>
@endif
```

### 11. IOU Index Page
**File:** `resources/views/iou/index.blade.php` (or similar)

```blade
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'iou'))
    <a href="{{ route('iou.create') }}" class="btn btn-primary">Create IOU Request</a>
@endif

@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('approve', 'iou'))
    <button onclick="approveIOU({{ $iou->id }})" class="btn btn-success">Approve</button>
@endif
```

### 12. PC Request Index Page
**File:** `resources/views/pc-request/index.blade.php` (or similar)

```blade
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'pcrequest'))
    <a href="{{ route('pc-request.create') }}" class="btn btn-primary">New PC Request</a>
@endif

@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('approve', 'pcrequest'))
    <button onclick="approvePCRequest({{ $request->id }})" class="btn btn-success">Approve</button>
@endif
```

---

## Complete Example - Full Index Page

```blade
@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <h4>Branch Management</h4>
        </div>

        <!-- Add Button (Create Permission) -->
        @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'branch'))
            <div class="mb-3">
                <a href="{{ route('new-branch.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus"></i> Add Branch
                </a>
            </div>
        @endif

        <!-- Table (Read Permission) -->
        @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('read', 'branch'))
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($branches as $branch)
                                <tr>
                                    <td>{{ $branch->name }}</td>
                                    <td>{{ $branch->location }}</td>
                                    <td>
                                        <!-- Edit Button -->
                                        @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'branch'))
                                            <a href="{{ route('new-branch.edit', $branch->id) }}" class="btn btn-sm btn-primary">
                                                <i class="ti ti-pencil"></i> Edit
                                            </a>
                                        @endif

                                        <!-- Delete Button -->
                                        @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('delete', 'branch'))
                                            <button class="btn btn-sm btn-danger" onclick="deleteBranch({{ $branch->id }})">
                                                <i class="ti ti-trash"></i> Delete
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                You don't have permission to view branches.
            </div>
        @endif
    </div>
</div>
@endsection
```

---

## Quick Copy-Paste Snippets

### Create Button
```blade
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'MODULE_NAME'))
    <a href="{{ route('MODULE_NAME.create') }}" class="btn btn-primary">Add</a>
@endif
```

### Edit Button
```blade
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'MODULE_NAME'))
    <a href="{{ route('MODULE_NAME.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
@endif
```

### Delete Button
```blade
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('delete', 'MODULE_NAME'))
    <button class="btn btn-sm btn-danger" onclick="deleteItem({{ $item->id }})">Delete</button>
@endif
```

### Approve Button
```blade
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('approve', 'MODULE_NAME'))
    <button class="btn btn-sm btn-success" onclick="approveItem({{ $item->id }})">Approve</button>
@endif
```

### Full Read Check (Hide entire section if no read permission)
```blade
@if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('read', 'MODULE_NAME'))
    <!-- Show table/content -->
@else
    <div class="alert alert-warning">No permission to view this data</div>
@endif
```

---

## How It Works

1. **Admin** → All buttons visible (session('is_admin') = true)
2. **Employee with permission** → Only permitted buttons visible
3. **Employee without permission** → Buttons hidden completely
4. **No read permission** → Entire table hidden

## Testing

1. Create a role without "create:branch" permission
2. Assign that role to an employee
3. Login as that employee
4. Visit branch page → "Add Branch" button should be hidden
5. Try accessing `/branch/create` directly → 403 Forbidden (middleware blocks)

---

## Files to Update

Priority order:
1. ✅ `resources/views/new-branch/index.blade.php` (already started)
2. `resources/views/department/index.blade.php`
3. `resources/views/designation/index.blade.php`
4. `resources/views/location/index.blade.php`
5. `resources/views/zone/index.blade.php`
6. `resources/views/country/index.blade.php`
7. `resources/views/state/index.blade.php`
8. `resources/views/city/index.blade.php`
9. `resources/views/staff/index.blade.php`
10. Ticket management pages
11. IOU pages
12. PC Request pages
13. Any other module pages

All use the same pattern - just change the MODULE_NAME!
