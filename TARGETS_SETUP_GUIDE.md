# TARGETS MODULE - SETUP GUIDE

## ✅ PREREQUISITES

- ✅ Laravel 12 installed
- ✅ Database connection configured
- ✅ User authentication working
- ✅ Permission system active

---

## 🚀 STEP-BY-STEP SETUP

### Step 1: Run Migration

```bash
php artisan migrate
```

**Expected Output:**
```
  2026_06_30_162934_create_targets_table ........................ 26.00ms DONE
```

**Verify Table Created:**
```sql
SHOW TABLES LIKE 'targets';
DESCRIBE targets;
```

---

### Step 2: Seed Sample Data (Optional)

**Option A: Using Seeder**
```bash
php artisan db:seed --class=TargetSeeder
```

**Option B: Add Data via Web Interface**
- Go to http://10.10.1.143:8000/targets
- Click "Add Target"
- Fill form and submit

**Option C: Direct SQL Insert**
```sql
INSERT INTO targets (user_id, branch_id, target_type, target_amount, 
                     effective_from, effective_to, created_by, created_at, updated_at)
VALUES (1001, 1, 'month', 500000, '2026-06-01', '2026-06-30', 'Admin', NOW(), NOW());
```

---

### Step 3: Verify File Structure

```
✓ database/migrations/2026_06_30_162934_create_targets_table.php
✓ app/Models/Target.php
✓ app/Http/Controllers/TargetController.php
✓ resources/views/targets/index.blade.php
✓ resources/views/targets/create.blade.php
✓ resources/views/targets/edit.blade.php
✓ routes/web.php (contains targets routes)
```

---

### Step 4: Check Routes

```bash
php artisan route:list | grep targets
```

**Expected Output:**
```
GET|HEAD   /targets .......................... targets.index
GET|HEAD   /targets/create ................... targets.create
POST       /targets .......................... targets.store
GET|HEAD   /targets/{target}/edit ........... targets.edit
PUT|PATCH  /targets/{target} ................ targets.update
DELETE     /targets/{target} ................ targets.destroy
```

---

### Step 5: Verify Permissions

Ensure user has `read,staff` permission:

```bash
# Check in database
SELECT * FROM role_permission WHERE permission_id IN (
    SELECT permission_id FROM permissions WHERE permission_slug = 'read,staff'
);
```

---

### Step 6: Test Module

**Test 1: Access List Page**
```
URL: http://10.10.1.143:8000/targets
Expected: Table with targets (or empty if no data seeded)
```

**Test 2: Create New Target**
```
URL: http://10.10.1.143:8000/targets/create
Expected: Form with all fields
```

**Test 3: Add Sample Target**
```
Fill form and click "Create Target"
Expected: Success message + redirect to list
```

**Test 4: Edit Target**
```
Click Edit on any target
Expected: Form pre-filled with target data
```

**Test 5: Delete Target**
```
Click Delete on any target
Expected: Success message after confirmation
```

---

## 📋 TROUBLESHOOTING

### Issue: Page Not Found (404)

**Cause:** Routes not loaded
**Solution:**
```bash
php artisan route:clear
php artisan cache:clear
php artisan optimize:clear
```

### Issue: Table Not Found

**Cause:** Migration not run
**Solution:**
```bash
php artisan migrate --force
```

### Issue: "Access Denied"

**Cause:** Missing permission
**Solution:**
```php
// Grant permission to user
DB::table('role_permission')->insert([
    'role_id' => 1,
    'permission_id' => (permission id for 'read,staff'),
]);
```

### Issue: Employee/Branch Dropdown Empty

**Cause:** No employees or branches in database
**Solution:**
- Ensure User_Master has records
- Ensure new_branches has active branches
- Refresh page

### Issue: JavaScript Errors in Console

**Cause:** Layout not loaded properly
**Solution:**
```bash
php artisan view:clear
php artisan cache:clear
```

---

## 🔍 VERIFICATION CHECKLIST

- [ ] Migration ran successfully
- [ ] `targets` table exists in database
- [ ] `TargetController` is accessible
- [ ] Routes are registered (`route:list`)
- [ ] Views are in place
- [ ] User has `read,staff` permission
- [ ] Can access `/targets` URL
- [ ] Can see create form at `/targets/create`
- [ ] Can add sample data
- [ ] Can edit target
- [ ] Can delete target

---

## 📊 DATABASE INITIALIZATION

### Check Database State

```sql
-- Check table exists
SHOW TABLES LIKE 'targets';

-- Check columns
DESCRIBE targets;

-- Check sample data
SELECT COUNT(*) as total_targets FROM targets;

-- Check soft deletes
SELECT COUNT(*) as deleted_targets FROM targets WHERE deleted_at IS NOT NULL;
```

### Expected Database State

```
Total Tables: 1 (targets)
Total Columns: 12
Total Indexes: 3 (PRIMARY, user_id, branch_id)
Soft Deletes: Enabled
Timestamps: enabled (created_at, updated_at)
```

---

## 🎯 BASIC WORKFLOWS

### Workflow 1: Add Employee Target

```
1. Go to /targets
2. Click "Add Target"
3. Select Employee: John Doe
4. Select Branch: Porur
5. Type: Month
6. Amount: 500000
7. From: 2026-06-01
8. To: 2026-06-30
9. Notes: June Sales Target
10. Click "Create Target"
✅ Success
```

### Workflow 2: Add Branch Target

```
1. Go to /targets
2. Click "Add Target"
3. Employee: [Leave blank]
4. Select Branch: Porur
5. Type: Month
6. Amount: 2000000
7. From: 2026-06-01
8. To: 2026-06-30
9. Notes: Branch Total Target
10. Click "Create Target"
✅ Success
```

### Workflow 3: Add Company Target

```
1. Go to /targets
2. Click "Add Target"
3. Employee: [Leave blank]
4. Branch: [Leave blank]
5. Type: Month
6. Amount: 5000000
7. From: 2026-06-01
8. To: 2026-06-30
9. Notes: Company Total
10. Click "Create Target"
✅ Success
```

---

## 🔧 ADVANCED SETUP (Optional)

### Add Custom Scopes to Model

Add to `app/Models/Target.php`:

```php
public function scopeActive($query)
{
    $today = now()->toDateString();
    return $query->where('effective_from', '<=', $today)
                 ->where('effective_to', '>=', $today);
}

public function scopeForEmployee($query, $employeeId)
{
    return $query->where('user_id', $employeeId);
}

public function scopeForBranch($query, $branchId)
{
    return $query->where('branch_id', $branchId);
}

public function scopeDaily($query)
{
    return $query->where('target_type', 'day');
}

public function scopeMonthly($query)
{
    return $query->where('target_type', 'month');
}
```

**Usage:**
```php
$activeTargets = Target::active()->get();
$employeeTargets = Target::forEmployee(1001)->get();
$branchDailyTargets = Target::forBranch(1)->daily()->get();
```

### Add Repository Layer

Create `app/Repositories/TargetRepository.php`:

```php
<?php

namespace App\Repositories;

use App\Models\Target;

class TargetRepository
{
    public function all()
    {
        return Target::with(['employee', 'branch'])->latest()->get();
    }

    public function paginate($perPage = 15)
    {
        return Target::with(['employee', 'branch'])
                     ->latest()
                     ->paginate($perPage);
    }

    public function create($data)
    {
        return Target::create($data);
    }

    public function update(Target $target, $data)
    {
        return $target->update($data);
    }

    public function delete(Target $target)
    {
        return $target->delete();
    }

    public function forEmployee($employeeId)
    {
        return Target::where('user_id', $employeeId)
                     ->with(['branch'])
                     ->get();
    }

    public function forBranch($branchId)
    {
        return Target::where('branch_id', $branchId)
                     ->with(['employee'])
                     ->get();
    }

    public function active()
    {
        $today = now()->toDateString();
        return Target::where('effective_from', '<=', $today)
                     ->where('effective_to', '>=', $today)
                     ->with(['employee', 'branch'])
                     ->get();
    }
}
```

---

## 📈 PERFORMANCE OPTIMIZATION

### Add Indexes

```sql
-- Indexes are auto-created by migration, but you can add more:

CREATE INDEX idx_target_type ON targets(target_type);
CREATE INDEX idx_effective_from ON targets(effective_from);
CREATE INDEX idx_effective_to ON targets(effective_to);
CREATE INDEX idx_deleted_at ON targets(deleted_at);
```

### Query Optimization

```php
// ❌ Bad: N+1 Query Problem
foreach(Target::all() as $target) {
    echo $target->employee->FullName; // Query for each!
}

// ✅ Good: Use eager loading
foreach(Target::with(['employee', 'branch'])->get() as $target) {
    echo $target->employee->FullName; // Loaded in one query!
}
```

---

## 🎓 FINAL VERIFICATION

Run this command to verify everything:

```bash
php artisan tinker

# In Tinker:
>>> App\Models\Target::count()  // Should show number of targets
>>> Target::with(['employee', 'branch'])->first() // Should show target with relations
>>> exit
```

---

## ✅ SETUP COMPLETE!

Your Targets & Projections module is now ready to use!

**Access it at:** http://10.10.1.143:8000/targets

**Documentation:**
- Full Details: `TARGETS_MODULE_REPORT.md`
- Quick Reference: `TARGETS_QUICK_REFERENCE.md`
- This Guide: `TARGETS_SETUP_GUIDE.md`

---

## 📞 SUPPORT

If you encounter any issues:
1. Check the troubleshooting section above
2. Review Laravel logs: `storage/logs/laravel.log`
3. Clear caches: `php artisan optimize:clear`
4. Verify database: `php artisan tinker`

**Status:** ✅ SETUP COMPLETE
**Version:** 1.0
**Date:** 30-Jun-2026
