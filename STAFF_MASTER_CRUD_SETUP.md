# Staff Master Module - Complete CRUD Setup with Permissions

## 🎯 Goal
Create a **Staff Master** module with:
- ✅ Create staff
- ✅ Read/View staff list
- ✅ Edit staff
- ✅ Delete staff
- ✅ Permission control (who can do what)

---

## 📋 STEP 1: Create Database Table

### Migration File
**File:** `database/migrations/2026_06_24_create_staff_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id('staff_id');
            $table->string('staff_name', 100);
            $table->string('email', 100)->unique();
            $table->string('phone', 20)->nullable();
            $table->string('position', 100);  // Position: Manager, Coordinator, etc
            $table->string('department', 100)->nullable();
            $table->date('joining_date')->nullable();
            $table->integer('salary')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
```

**Run Migration:**
```bash
php artisan migrate
```

---

## 🏗️ STEP 2: Create Model

**File:** `app/Models/Staff.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';
    protected $primaryKey = 'staff_id';
    protected $fillable = [
        'staff_name',
        'email',
        'phone',
        'position',
        'department',
        'joining_date',
        'salary',
        'is_active'
    ];
}
```

---

## 🛣️ STEP 3: Create Routes

**File:** `app/Modules/Master/routes.php` (add to existing file)

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;

Route::middleware(['auth.custom'])->group(function () {
    
    // Staff CRUD Routes
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
    
});
```

---

## 💾 STEP 4: Create Controller

**File:** `app/Http/Controllers/StaffController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use App\Helpers\RbacHelper;

class StaffController extends Controller
{
    // INDEX - Show all staff
    public function index()
    {
        // Permission check: read
        if (!session('is_admin') && !RbacHelper::canPerformAction('read', 'staff')) {
            abort(403, 'Unauthorized');
        }

        $staff = Staff::paginate(15);
        return view('staff.index', ['staff' => $staff]);
    }

    // CREATE - Show create form
    public function create()
    {
        // Permission check: create
        if (!session('is_admin') && !RbacHelper::canPerformAction('create', 'staff')) {
            abort(403, 'Unauthorized');
        }

        return view('staff.create');
    }

    // STORE - Save to database
    public function store(Request $request)
    {
        // Permission check: create
        if (!session('is_admin') && !RbacHelper::canPerformAction('create', 'staff')) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'staff_name' => 'required|string|max:100',
            'email' => 'required|email|unique:staff',
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:100',
            'department' => 'nullable|string|max:100',
            'joining_date' => 'nullable|date',
            'salary' => 'nullable|integer',
            'is_active' => 'nullable|boolean'
        ]);

        Staff::create($validated + ['is_active' => $request->is_active ?? 1]);

        return response()->json([
            'status' => true,
            'message' => 'Staff created successfully'
        ]);
    }

    // EDIT - Show edit form
    public function edit($id)
    {
        // Permission check: edit
        if (!session('is_admin') && !RbacHelper::canPerformAction('edit', 'staff')) {
            abort(403, 'Unauthorized');
        }

        $staff = Staff::findOrFail($id, 'staff_id');
        return view('staff.edit', ['staff' => $staff]);
    }

    // UPDATE - Update in database
    public function update(Request $request, $id)
    {
        // Permission check: edit
        if (!session('is_admin') && !RbacHelper::canPerformAction('edit', 'staff')) {
            abort(403, 'Unauthorized');
        }

        $staff = Staff::findOrFail($id, 'staff_id');

        $validated = $request->validate([
            'staff_name' => 'required|string|max:100',
            'email' => 'required|email|unique:staff,email,' . $id . ',staff_id',
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:100',
            'department' => 'nullable|string|max:100',
            'joining_date' => 'nullable|date',
            'salary' => 'nullable|integer',
            'is_active' => 'nullable|boolean'
        ]);

        $staff->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Staff updated successfully'
        ]);
    }

    // DESTROY - Delete from database
    public function destroy($id)
    {
        // Permission check: delete
        if (!session('is_admin') && !RbacHelper::canPerformAction('delete', 'staff')) {
            abort(403, 'Unauthorized');
        }

        Staff::findOrFail($id, 'staff_id')->delete();

        return response()->json([
            'status' => true,
            'message' => 'Staff deleted successfully'
        ]);
    }
}
```

---

## 🎨 STEP 5: Create Views

### A. Index View (List all staff)
**File:** `resources/views/staff/index.blade.php`

```blade
@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Staff Master</h1>
                
                @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'staff'))
                    <a href="{{ route('staff.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>Add Staff
                    </a>
                @endif
            </div>
        </div>

        @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('read', 'staff'))
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Staff Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>Department</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staff as $member)
                                    <tr>
                                        <td><strong>{{ $member->staff_name }}</strong></td>
                                        <td>{{ $member->email }}</td>
                                        <td>{{ $member->position }}</td>
                                        <td>{{ $member->department ?? '-' }}</td>
                                        <td>{{ $member->phone ?? '-' }}</td>
                                        <td>
                                            @if($member->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'staff'))
                                                    <a href="{{ route('staff.edit', $member->staff_id) }}" class="btn btn-warning">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                @endif
                                                
                                                @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('delete', 'staff'))
                                                    <button class="btn btn-danger" onclick="deleteStaff({{ $member->staff_id }}, '{{ $member->staff_name }}')">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No staff found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                {{ $staff->links() }}
            </div>
        @else
            <div class="alert alert-warning">
                <i class="ti ti-alert-circle"></i>
                You don't have permission to view staff records.
            </div>
        @endif
    </div>
</div>

<script>
function deleteStaff(id, name) {
    if (!confirm('Delete ' + name + '?')) return;

    fetch('/staff/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: data.message,
                timer: 2000
            });
            setTimeout(() => location.reload(), 2000);
        }
    });
}
</script>
@endsection
```

### B. Create View
**File:** `resources/views/staff/create.blade.php`

```blade
@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <h1>Add Staff</h1>
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body">
                        <form id="staffForm">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Staff Name <span class="text-danger">*</span></label>
                                    <input type="text" name="staff_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Position <span class="text-danger">*</span></label>
                                    <input type="text" name="position" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Department</label>
                                    <input type="text" name="department" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Joining Date</label>
                                    <input type="date" name="joining_date" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Salary</label>
                                    <input type="number" name="salary" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="is_active" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Save Staff</button>
                                <a href="{{ route('staff.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('staffForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    const response = await fetch('/staff', {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value }
    });
    
    const data = await response.json();
    if (data.status) {
        Swal.fire('Success', data.message, 'success');
        setTimeout(() => location.href = '/staff', 1500);
    }
});
</script>
@endsection
```

### C. Edit View
**File:** `resources/views/staff/edit.blade.php`

```blade
@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <h1>Edit Staff</h1>
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body">
                        <form id="staffForm">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Staff Name <span class="text-danger">*</span></label>
                                    <input type="text" name="staff_name" class="form-control" value="{{ $staff->staff_name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ $staff->email }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Position <span class="text-danger">*</span></label>
                                    <input type="text" name="position" class="form-control" value="{{ $staff->position }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $staff->phone ?? '' }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Department</label>
                                    <input type="text" name="department" class="form-control" value="{{ $staff->department ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Joining Date</label>
                                    <input type="date" name="joining_date" class="form-control" value="{{ $staff->joining_date ?? '' }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Salary</label>
                                    <input type="number" name="salary" class="form-control" value="{{ $staff->salary ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="is_active" class="form-control">
                                        <option value="1" {{ $staff->is_active ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !$staff->is_active ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Update Staff</button>
                                <a href="{{ route('staff.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('staffForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    const response = await fetch('/staff/{{ $staff->staff_id }}', {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value }
    });
    
    const data = await response.json();
    if (data.status) {
        Swal.fire('Success', data.message, 'success');
        setTimeout(() => location.href = '/staff', 1500);
    }
});
</script>
@endsection
```

---

## 🔐 STEP 6: Setup Permissions in RBAC

### Step 1: Add Module to Database
**URL:** Go to Admin Panel → Access Control → Manage Modules

```
Module Name: staff
Description: Staff Master
Route Prefix: staff
Icon: ti-users
Active: ✓
Click: [Add Module]
```

OR manually insert:
```sql
INSERT INTO modules (name, description, route_prefix, icon, is_active)
VALUES ('staff', 'Staff Master', 'staff', 'ti-users', 1);
```

### Step 2: Create Permissions
Permissions are auto-created when you add module. Check:

```sql
SELECT * FROM permissions WHERE module = 'staff';

Result should show:
id | name   | module | description
───┼────────┼────────┼──────────────────────
1  | read   | staff  | read on staff
2  | create | staff  | create on staff
3  | edit   | staff  | edit on staff
4  | delete | staff  | delete on staff
5  | approve| staff  | approve on staff
```

### Step 3: Assign Permissions to Role
**URL:** http://127.0.0.1:8000/rbac/manage-roles

```
Click: [Manage Roles & Permissions]
Select Role: "HR Manager" (or any role)

Click: [Permissions] Button
Modal Opens:

Add Permission:
  Module: [staff ▼]
  Action: [read ▼]
  Click: [Add Permission]

Add More:
  Module: [staff ▼]
  Action: [create ▼]
  Click: [Add Permission]
  
  Module: [staff ▼]
  Action: [edit ▼]
  Click: [Add Permission]
  
  (Optional: add delete if you want)

Result: HR Manager now has read, create, edit permissions on staff
```

### Step 4: What HR Manager Can Do
```
Login as HR Manager:

Sidebar: Staff Master menu shows (has read permission)
  ✓ See all staff list
  ✓ See [Add Staff] button (has create)
  ✓ See [Edit] button per row (has edit)
  ✗ Cannot delete (if no delete permission)

If they try to delete:
  Button hidden in UI
  If they bypass via DevTools → 403 Forbidden error
```

---

## 📊 Permission Matrix for Staff Module

| User Role | read | create | edit | delete | Actions |
|-----------|------|--------|------|--------|---------|
| Admin | ✓ | ✓ | ✓ | ✓ | Do anything |
| HR Manager | ✓ | ✓ | ✓ | ✗ | View, Add, Edit (NOT delete) |
| HR Coordinator | ✓ | ✗ | ✗ | ✗ | View only |
| Employee | ✗ | ✗ | ✗ | ✗ | No access |

---

## ✅ Complete Workflow Example

### Scenario: HR Manager Creates New Staff

```
1. HR Manager logs in

2. Sidebar shows: Masters → Staff Master (has read permission)

3. Click Staff Master → Opens /staff (index page)
   ✓ See list of 5 staff members
   ✓ See [Add Staff] button (has create permission)
   
4. Click [Add Staff] → Opens /staff/create form
   Fill form:
     Staff Name: "John Doe"
     Email: john@company.com
     Position: Coordinator
     Department: HR
     Phone: 9876543210
     Joining Date: 2026-07-01
     Salary: 25000
     Status: Active
   
5. Click [Save Staff] → AJAX POST to /staff
   Backend checks: Has 'create:staff' permission? ✓ YES
   Database inserts: New staff record created
   Response: "Staff created successfully"
   
6. Toast notification shows success
   Page redirects to /staff list
   
7. New staff shows in table
   ✓ Edit button visible (has edit permission)
   ✗ Delete button hidden (no delete permission)

8. HR Manager clicks [Edit] → Opens /staff/{id}/edit
   Updates: Staff Name from "John Doe" to "John Doe Jr"
   Click [Update Staff] → AJAX PUT to /staff/{id}
   Backend checks: Has 'edit:staff' permission? ✓ YES
   Database updates: Staff record modified
   Response: "Staff updated successfully"

9. If HR Manager tries to delete:
   No delete button visible (no permission)
   Even if tries via DevTools/API:
   Backend checks: Has 'delete:staff' permission? ✗ NO
   Response: 403 Forbidden
   Action blocked!
```

---

## 📁 Summary - What to Create/Do

```
1. DATABASE
   ✓ Create migration: create_staff_table.php
   ✓ Run: php artisan migrate

2. MODEL
   ✓ Create: app/Models/Staff.php

3. CONTROLLER
   ✓ Create: app/Http/Controllers/StaffController.php
   ✓ Add index, create, store, edit, update, destroy methods
   ✓ Add permission checks in each method

4. ROUTES
   ✓ Add to: app/Modules/Master/routes.php
   ✓ Define: GET/POST/PUT/DELETE routes

5. VIEWS
   ✓ Create: resources/views/staff/index.blade.php (list)
   ✓ Create: resources/views/staff/create.blade.php (form)
   ✓ Create: resources/views/staff/edit.blade.php (edit)

6. RBAC SETUP
   ✓ Add module: "staff" to modules table
   ✓ Permissions auto-created: read, create, edit, delete, approve
   ✓ Assign permissions to role via UI: /rbac/manage-roles

RESULT: Complete CRUD system with permission control! ✅
```

---

**Status:** ✅ Ready to Implement  
**Time to Code:** ~30 minutes  
**Complexity:** Easy (Copy-paste most of this)  
**Date:** 2026-06-24
