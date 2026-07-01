# TARGETS MODULE - QUICK REFERENCE GUIDE

## 🎯 QUICK START

### Access Targets Module
```
URL: http://10.10.1.143:8000/targets
```

### Main Operations

| Action | URL | How |
|--------|-----|-----|
| View All | `/targets` | GET |
| Add New | `/targets/create` | Click "Add Target" button |
| Edit | `/targets/{id}/edit` | Click Edit button |
| Delete | `/targets/{id}` | Click Delete button |

---

## 📝 CREATE NEW TARGET - FORM FIELDS

```
┌─────────────────────────────────────┐
│   CREATE NEW TARGET FORM            │
├─────────────────────────────────────┤
│                                     │
│ Employee:  [Dropdown / Optional]    │
│ Branch:    [Dropdown / Optional]    │
│ Type:      [Day / Month] *Required  │
│ Amount:    [₹ Input] *Required      │
│ From:      [Date Picker] *Required  │
│ To:        [Date Picker] *Required  │
│ Notes:     [Text Area / Optional]   │
│                                     │
│ [Create Target]  [Cancel]           │
└─────────────────────────────────────┘
```

---

## 💾 DATABASE FIELDS

```
TABLE: targets

┌──────────────┬─────────────┬──────────┬─────────────────┐
│ Field        │ Type        │ Required │ Example         │
├──────────────┼─────────────┼──────────┼─────────────────┤
│ id           │ BIGINT      │ Yes (PK) │ 1               │
│ user_id      │ INT         │ No       │ 1001            │
│ branch_id    │ BIGINT      │ No       │ 5               │
│ target_type  │ ENUM        │ Yes      │ 'month'         │
│ target_amt   │ DECIMAL     │ Yes      │ 500000.00       │
│ effect_from  │ DATE        │ Yes      │ 2026-06-01      │
│ effect_to    │ DATE        │ Yes      │ 2026-06-30      │
│ description  │ VARCHAR     │ No       │ "Q2 Target"     │
│ created_by   │ VARCHAR     │ No       │ "Admin"         │
│ created_at   │ TIMESTAMP   │ Yes      │ AUTO            │
│ updated_at   │ TIMESTAMP   │ Yes      │ AUTO            │
│ deleted_at   │ TIMESTAMP   │ No       │ (Soft Delete)   │
└──────────────┴─────────────┴──────────┴─────────────────┘
```

---

## 🔍 FILTER OPTIONS

### On Targets Listing Page:
```
[Target Type ▼]  [Branch ▼]  [Employee Search]  [Filter]
  - All Types     - All         - Name or Code
  - Day Target    - Porur
  - Month Target  - T-Nagar
                  - OMR
```

---

## 📊 SAMPLE DATA SETUP

### Sample 1: Employee Monthly Target
```
Employee: John Doe (EMP-0001)
Branch: Porur
Type: Month
Amount: ₹500,000
From: 01-Jun-2026
To: 30-Jun-2026
Notes: June Sales Target
```

### Sample 2: Branch-wide Daily Target
```
Employee: [Leave empty]
Branch: T-Nagar
Type: Day
Amount: ₹100,000
From: 30-Jun-2026
To: 30-Jun-2026
Notes: Tuesday sales target
```

### Sample 3: Company-wide Target
```
Employee: [Leave empty]
Branch: [Leave empty]
Type: Month
Amount: ₹5,000,000
From: 01-Jun-2026
To: 30-Jun-2026
Notes: Total company target for June
```

---

## 🛠️ FILE LOCATIONS

```
📦 Targets Module Files

📂 database/
  └─ migrations/
     └─ 2026_06_30_162934_create_targets_table.php

📂 app/
  ├─ Models/
  │  └─ Target.php
  └─ Http/Controllers/
     └─ TargetController.php

📂 resources/views/
  └─ targets/
     ├─ index.blade.php
     ├─ create.blade.php
     └─ edit.blade.php

📂 routes/
  └─ web.php (line 105)
```

---

## 🔗 RELATIONSHIPS

```
Target Model:

target.employee() 
  → UserMaster (user_id → UserID)
  → Access: $target->employee->FullName

target.branch()
  → NewBranch (branch_id → id)
  → Access: $target->branch->branch_name
```

---

## ✅ VALIDATION RULES

```
user_id      : exists in User_Master table (optional)
branch_id    : exists in new_branches table (optional)
target_type  : must be 'day' or 'month' (REQUIRED)
target_amt   : numeric, min 0 (REQUIRED)
effect_from  : valid date (REQUIRED)
effect_to    : valid date, >= effect_from (REQUIRED)
description  : max 255 chars (optional)
```

---

## 📋 TABLE VIEW

```
┌──────────────┬─────────┬──────────┬─────────────────────────────────┬──────────────────┐
│ Employee     │ Code    │ Branch   │ Type    │ Amount      │ Period            │ Actions     │
├──────────────┼─────────┼──────────┼─────────┼─────────────┼───────────────────┼─────────────┤
│ John Doe     │ EMP001  │ Porur    │ 📅 Month│ ₹500,000.00 │ 01 Jun - 30 Jun   │ Edit Delete │
│ Jane Smith   │ EMP002  │ T-Nagar  │ 📆 Day  │ ₹25,000.00  │ 30 Jun - 30 Jun   │ Edit Delete │
│ All Emps     │ -       │ Porur    │ 📅 Month│ ₹2,000,000  │ 01 Jun - 30 Jun   │ Edit Delete │
│ All Emps     │ -       │ All      │ 📅 Month│ ₹5,000,000  │ 01 Jun - 30 Jun   │ Edit Delete │
└──────────────┴─────────┴──────────┴─────────┴─────────────┴───────────────────┴─────────────┘
```

---

## 🔐 PERMISSIONS

**Required:** `read,staff`

If user doesn't have permission → Access Denied

---

## 🚀 COMMON QUERIES

### Get All Targets for Employee:
```php
$targets = Target::where('user_id', 1001)->get();
```

### Get Branch Targets:
```php
$targets = Target::where('branch_id', 1)->get();
```

### Get Today's Targets:
```php
$today = now()->toDateString();
$targets = Target::where('effective_from', '<=', $today)
                  ->where('effective_to', '>=', $today)
                  ->get();
```

### Get with Details:
```php
$targets = Target::with(['employee', 'branch'])->get();
foreach($targets as $target) {
    echo $target->employee->FullName;  // Employee name
    echo $target->branch->branch_name; // Branch name
    echo $target->target_amount;       // Amount
}
```

---

## ⚡ TIPS & TRICKS

✅ **DO:**
- Create separate targets for each employee/branch
- Use date ranges that don't overlap
- Add clear descriptions for reporting
- Set realistic target amounts

❌ **DON'T:**
- Leave both employee and branch empty (unless company-wide)
- Set effective_to before effective_from
- Use negative amounts
- Create duplicate targets for same period

---

## 📞 COMMON ISSUES & SOLUTIONS

| Issue | Cause | Solution |
|-------|-------|----------|
| Can't see targets | No permission | Grant 'read,staff' permission |
| Save button disabled | Validation error | Check all required fields are filled |
| Employee not in dropdown | Invalid employee ID | Verify employee exists in User_Master |
| Branch not in dropdown | Inactive branch | Activate branch in system |
| Date validation fails | End date < Start date | Ensure end date is after start date |

---

## 📈 REPORTING

### Monthly Targets Report:
```sql
SELECT 
    um.FullName as Employee,
    nb.branch_name as Branch,
    SUM(target_amount) as Total_Target
FROM targets t
LEFT JOIN User_Master um ON t.user_id = um.UserID
LEFT JOIN new_branches nb ON t.branch_id = nb.id
WHERE target_type = 'month'
GROUP BY t.branch_id, t.user_id;
```

### Daily Targets Report:
```sql
SELECT 
    um.FullName as Employee,
    target_amount as Daily_Target,
    effective_from as Date
FROM targets t
LEFT JOIN User_Master um ON t.user_id = um.UserID
WHERE target_type = 'day'
ORDER BY effective_from DESC;
```

---

## 🎓 LEARNING PATH

1. **Level 1:** Create sample targets using web interface
2. **Level 2:** Query targets using Eloquent
3. **Level 3:** Create custom reports
4. **Level 4:** Build dashboard integration
5. **Level 5:** Automate target calculations

---

## 📞 SUPPORT

**For Issues:**
- Check TARGETS_MODULE_REPORT.md for detailed documentation
- Verify permissions are set correctly
- Check browser console for JavaScript errors
- Review Laravel logs: `storage/logs/laravel.log`

**Status:** ✅ PRODUCTION READY

**Last Updated:** 30-Jun-2026
