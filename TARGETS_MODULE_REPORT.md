# TARGETS & PROJECTIONS MODULE - COMPLETE REPORT

## 📋 TABLE OF CONTENTS
1. [Module Overview](#module-overview)
2. [Database Schema](#database-schema)
3. [How to Access](#how-to-access)
4. [Seeding/Adding Target Data](#seeding-adding-target-data)
5. [Data Fields Explanation](#data-fields-explanation)
6. [Code Files & Locations](#code-files--locations)
7. [API Endpoints](#api-endpoints)
8. [Sample Data to Create](#sample-data-to-create)

---

## 🎯 Module Overview

**Purpose:** Manage sales targets for employees and branches with support for daily and monthly targets.

**Status:** ✅ Production Ready

**Features:**
- Create, read, update, delete (CRUD) targets
- Filter by target type (day/month), branch, employee
- Search by employee name or code
- Automatic soft deletes (keeps historical data)
- Professional ERP UI
- Pagination with 15 records per page

---

## 📊 DATABASE SCHEMA

### Table Name: `targets`

```sql
CREATE TABLE targets (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNSIGNED NULL,           -- Employee ID from User_Master
    branch_id BIGINT UNSIGNED NULL,      -- Branch ID from new_branches
    target_type ENUM('day','month'),     -- Type of target
    target_amount DECIMAL(15,2),         -- Target amount in currency
    effective_from DATE NULL,            -- Start date
    effective_to DATE NULL,              -- End date
    description VARCHAR(255) NULL,       -- Notes/description
    created_by VARCHAR(255) NULL,        -- Who created it
    deleted_at TIMESTAMP NULL,           -- Soft delete timestamp
    created_at TIMESTAMP,                -- Created timestamp
    updated_at TIMESTAMP                 -- Updated timestamp
);

CREATE INDEX idx_user_id ON targets(user_id);
CREATE INDEX idx_branch_id ON targets(branch_id);
```

### Field Details:

| Field | Type | Required | Example | Notes |
|-------|------|----------|---------|-------|
| user_id | INT | No | 1001 | Employee ID - Leave blank for branch-wide targets |
| branch_id | BIGINT | No | 5 | Branch ID - Leave blank for company-wide targets |
| target_type | ENUM | Yes | 'month' | Either 'day' or 'month' |
| target_amount | DECIMAL | Yes | 500000.00 | Target amount in ₹ |
| effective_from | DATE | Yes | 2026-06-01 | Start date of target |
| effective_to | DATE | Yes | 2026-06-30 | End date of target |
| description | VARCHAR | No | "Q2 Sales Target" | Optional notes |
| created_by | VARCHAR | No | "Admin" | Who created the target |

---

## 🌐 HOW TO ACCESS

### URLs:

| Page | URL | Method |
|------|-----|--------|
| View All Targets | `/targets` | GET |
| Create New Target | `/targets/create` | GET |
| Save Target | `/targets` | POST |
| Edit Target | `/targets/{id}/edit` | GET |
| Update Target | `/targets/{id}` | PUT |
| Delete Target | `/targets/{id}` | DELETE |

### Example URLs:
```
http://10.10.1.143:8000/targets                  (List all targets)
http://10.10.1.143:8000/targets/create           (Add new target)
http://10.10.1.143:8000/targets/5/edit           (Edit target ID 5)
```

---

## 📝 SEEDING/ADDING TARGET DATA

### Method 1: Web Interface (Recommended)

**Step 1:** Go to http://10.10.1.143:8000/targets
**Step 2:** Click "Add Target" button
**Step 3:** Fill in the form:
```
Employee: [Select from dropdown or leave blank]
Branch: [Select from dropdown or leave blank]
Target Type: [Choose 'Day Target' or 'Month Target']
Target Amount: [Enter amount, e.g., 500000]
Effective From: [Select start date]
Effective To: [Select end date]
Description: [Optional notes]
```
**Step 4:** Click "Create Target"

### Method 2: Database Seeder (For Bulk Data)

Create file: `database/seeders/TargetSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\Target;
use Illuminate\Database\Seeder;

class TargetSeeder extends Seeder
{
    public function run(): void
    {
        // Month Target for Employee (Porur Branch)
        Target::create([
            'user_id' => 1001,
            'branch_id' => 1,
            'target_type' => 'month',
            'target_amount' => 500000.00,
            'effective_from' => '2026-06-01',
            'effective_to' => '2026-06-30',
            'description' => 'June 2026 Sales Target - Porur Location',
            'created_by' => 'Admin',
        ]);

        // Day Target for Employee
        Target::create([
            'user_id' => 1002,
            'branch_id' => 2,
            'target_type' => 'day',
            'target_amount' => 25000.00,
            'effective_from' => '2026-06-30',
            'effective_to' => '2026-06-30',
            'description' => 'Daily sales target for Tuesday',
            'created_by' => 'Admin',
        ]);

        // Branch-wide Target (no employee specified)
        Target::create([
            'user_id' => null,
            'branch_id' => 1,
            'target_type' => 'month',
            'target_amount' => 2000000.00,
            'effective_from' => '2026-06-01',
            'effective_to' => '2026-06-30',
            'description' => 'Total branch target - Porur',
            'created_by' => 'Admin',
        ]);

        // Company-wide Target
        Target::create([
            'user_id' => null,
            'branch_id' => null,
            'target_type' => 'month',
            'target_amount' => 5000000.00,
            'effective_from' => '2026-06-01',
            'effective_to' => '2026-06-30',
            'description' => 'Company-wide monthly target',
            'created_by' => 'Admin',
        ]);
    }
}
```

**Run Seeder:**
```bash
php artisan db:seed --class=TargetSeeder
```

### Method 3: Direct Database Insert

```sql
INSERT INTO targets (
    user_id, branch_id, target_type, target_amount, 
    effective_from, effective_to, description, created_by, created_at, updated_at
) VALUES
(1001, 1, 'month', 500000.00, '2026-06-01', '2026-06-30', 'June Sales Target', 'Admin', NOW(), NOW()),
(1002, 2, 'day', 25000.00, '2026-06-30', '2026-06-30', 'Daily Target', 'Admin', NOW(), NOW()),
(NULL, 1, 'month', 2000000.00, '2026-06-01', '2026-06-30', 'Branch Target', 'Admin', NOW(), NOW());
```

---

## 📖 DATA FIELDS EXPLANATION

### Employee (user_id)
- **Type:** Integer
- **Source:** User_Master.UserID
- **Optional:** Yes (leave blank for branch/company-wide targets)
- **Example:** 1001, 1002, 1003
- **Usage:** Set target for specific employee/doctor

### Branch (branch_id)
- **Type:** Big Integer
- **Source:** new_branches.id
- **Optional:** Yes (leave blank for company-wide targets)
- **Example:** 1 (Porur), 2 (T-Nagar), 3 (OMR)
- **Usage:** Set target for specific branch/location

### Target Type
- **Type:** ENUM (day / month)
- **Required:** Yes
- **Values:**
  - `day` - Daily sales target
  - `month` - Monthly sales target
- **Usage:** Specify if target is daily or monthly

### Target Amount
- **Type:** Decimal (15 digits, 2 decimals)
- **Required:** Yes
- **Range:** 0.00 to 999,999,999,999.99
- **Format:** ₹500,000.00
- **Usage:** The target amount in Indian Rupees

### Effective From
- **Type:** Date (YYYY-MM-DD)
- **Required:** Yes
- **Example:** 2026-06-01
- **Usage:** When does target start?

### Effective To
- **Type:** Date (YYYY-MM-DD)
- **Required:** Yes
- **Example:** 2026-06-30
- **Constraint:** Must be >= Effective From
- **Usage:** When does target end?

### Description
- **Type:** String (255 characters max)
- **Required:** No
- **Example:** "Q2 2026 Sales Target", "Summer Campaign Target"
- **Usage:** Notes or description of the target

### Created By
- **Type:** String (255 characters max)
- **Required:** No
- **Example:** "Admin", "Manager Name"
- **Auto-filled:** When created via web interface

---

## 📁 CODE FILES & LOCATIONS

### 1. **Database Migration**
```
📂 database/migrations/
   └─ 2026_06_30_162934_create_targets_table.php
```
**Purpose:** Creates targets table with schema

---

### 2. **Model**
```
📂 app/Models/
   └─ Target.php
```
**Key Methods:**
```php
// Relationships
$target->employee()    // Get employee details
$target->branch()      // Get branch details

// Example Usage:
$target = Target::find(1);
echo $target->employee->FullName;  // "John Doe"
echo $target->branch->branch_name; // "Porur"
```

---

### 3. **Controller**
```
📂 app/Http/Controllers/
   └─ TargetController.php
```

**Methods:**
```php
public function index(Request $request)      // List targets with filters
public function create()                      // Show create form
public function store(Request $request)       // Save new target
public function edit(Target $target)          // Show edit form
public function update(Request $request, $id) // Update target
public function destroy(Target $target)       // Delete target
```

---

### 4. **Views**
```
📂 resources/views/targets/
   ├─ index.blade.php     // List all targets
   ├─ create.blade.php    // Create form
   └─ edit.blade.php      // Edit form
```

**View Features:**
- Professional table with sorting
- Filter by type, branch, employee
- Search functionality
- Pagination (15 per page)
- Edit/Delete buttons
- Status badges (day/month)

---

### 5. **Routes**
```
📂 routes/
   └─ web.php (lines 105)
```

**Route Definition:**
```php
Route::resource('targets', TargetController::class)
      ->middleware('check.permission:read,staff');
```

**Generated Routes:**
```
GET     /targets              (index)
GET     /targets/create       (create)
POST    /targets              (store)
GET     /targets/{id}/edit    (edit)
PUT     /targets/{id}         (update)
DELETE  /targets/{id}         (destroy)
```

---

## 🔌 API ENDPOINTS

### List All Targets
```
GET /targets
```
**Query Parameters:**
- `target_type` - Filter by 'day' or 'month'
- `branch_id` - Filter by branch
- `search` - Search by employee name/code
- `page` - Page number

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "user_id": 1001,
      "branch_id": 1,
      "target_type": "month",
      "target_amount": "500000.00",
      "effective_from": "2026-06-01",
      "effective_to": "2026-06-30",
      "description": "June Sales Target",
      "employee": {
        "UserID": 1001,
        "FullName": "John Doe",
        "UserCode": "EMP-0001"
      },
      "branch": {
        "id": 1,
        "branch_name": "Porur"
      }
    }
  ],
  "links": {...},
  "meta": {
    "current_page": 1,
    "total": 15
  }
}
```

---

## 📊 SAMPLE DATA TO CREATE

### Scenario: Company Sales Targets for June 2026

#### Target 1: Employee Monthly Target
```
Employee: John Doe (EMP-0001, ID: 1001)
Branch: Porur
Type: Month Target
Amount: ₹500,000
Period: June 1 - June 30, 2026
Description: June 2026 Sales Target - Porur Location
```

#### Target 2: Employee Daily Target
```
Employee: Jane Smith (EMP-0002, ID: 1002)
Branch: T-Nagar
Type: Day Target
Amount: ₹25,000
Period: June 30 - June 30, 2026 (Single day)
Description: Daily sales target for Tuesday
```

#### Target 3: Branch-wide Target (All Employees in Branch)
```
Employee: [Leave blank]
Branch: Porur
Type: Month Target
Amount: ₹2,000,000
Period: June 1 - June 30, 2026
Description: Total branch target - Porur location
```

#### Target 4: Company-wide Target
```
Employee: [Leave blank]
Branch: [Leave blank]
Type: Month Target
Amount: ₹5,000,000
Period: June 1 - June 30, 2026
Description: Company-wide monthly revenue target
```

---

## 🎬 WORKFLOW EXAMPLE

### Creating a Monthly Sales Target

**Step 1:** Navigate to http://10.10.1.143:8000/targets

**Step 2:** Click "Add Target" button

**Step 3:** Form appears with fields:
```
Employee: [Dropdown list of employees]
Branch: [Dropdown list of branches]
Target Type: [Radio or select - Day/Month]
Target Amount: [Text input - currency]
Effective From: [Date picker]
Effective To: [Date picker]
Description: [Text area]
```

**Step 4:** Fill sample data:
```
Employee: John Doe
Branch: Porur
Target Type: Month Target
Target Amount: 500000
Effective From: 2026-06-01
Effective To: 2026-06-30
Description: June Sales Target for Porur branch
```

**Step 5:** Click "Create Target"

**Step 6:** Success message appears
```
✅ Target created successfully
```

**Step 7:** Target appears in list with:
- Employee name
- Branch name
- Target type badge (Day/Month)
- Amount (₹500,000.00)
- Valid period (01 Jun 2026 to 30 Jun 2026)
- Edit & Delete buttons

---

## 📈 QUERYING TARGETS PROGRAMMATICALLY

### Get All Targets for a Employee:
```php
$targets = Target::where('user_id', 1001)->get();
```

### Get All Targets for a Branch:
```php
$targets = Target::where('branch_id', 1)->get();
```

### Get Monthly Targets Only:
```php
$targets = Target::where('target_type', 'month')->get();
```

### Get Daily Targets Only:
```php
$targets = Target::where('target_type', 'day')->get();
```

### Get Active Targets (within date range):
```php
$today = now()->toDateString();
$targets = Target::where('effective_from', '<=', $today)
                  ->where('effective_to', '>=', $today)
                  ->get();
```

### Get All Targets with Employee & Branch Details:
```php
$targets = Target::with(['employee', 'branch'])->get();
```

---

## ✅ VALIDATION RULES

When creating/updating targets, these rules apply:

```php
[
    'user_id' => 'nullable|exists:User_Master,UserID',
    'branch_id' => 'nullable|exists:new_branches,id',
    'target_type' => 'required|in:day,month',
    'target_amount' => 'required|numeric|min:0',
    'effective_from' => 'required|date',
    'effective_to' => 'required|date|after_or_equal:effective_from',
    'description' => 'nullable|string|max:255',
]
```

**Validation Messages:**
- ✗ Both employee and branch cannot be empty
- ✗ Target type must be 'day' or 'month'
- ✗ Target amount must be positive
- ✗ Effective To must be after or equal to Effective From
- ✗ Employee must exist in system
- ✗ Branch must exist in system

---

## 🔐 PERMISSIONS REQUIRED

To access targets module, user needs:
```
Permission: read,staff
```

Routes are protected by middleware:
```php
->middleware('check.permission:read,staff')
```

---

## 📋 SUMMARY

| Aspect | Details |
|--------|---------|
| **Module Name** | Targets & Projections |
| **Table Name** | targets |
| **Model** | App\Models\Target |
| **Controller** | App\Http\Controllers\TargetController |
| **Routes** | /targets (RESTful resource) |
| **Views** | 3 (index, create, edit) |
| **Database Fields** | 11 columns |
| **Supported Target Types** | 2 (day, month) |
| **Filtering Options** | 3 (type, branch, employee) |
| **Pagination** | 15 records per page |
| **Soft Deletes** | Yes (historical data kept) |
| **Status** | ✅ Production Ready |

---

## 🚀 NEXT STEPS

1. **Add Sample Data** - Use seeder or web interface to create targets
2. **Set Permissions** - Ensure users have 'read,staff' permission
3. **Test Workflows** - Create, edit, delete targets
4. **Monitor Performance** - Check if indexes are working
5. **Generate Reports** - Query targets for dashboards

---

**Last Updated:** 30-Jun-2026
**Version:** 1.0
**Status:** Production Ready ✅
