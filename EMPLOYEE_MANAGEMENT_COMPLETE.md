# Employee Management System - Complete Implementation v2

## Overview
A comprehensive employee management system with auto-approved document uploads, bond tracking, 2-month notice period relief, and RBAC-protected operations.

## Key Features

### 1. Employee Creation & Profile
- **Auto-incrementing codes**: EMP-0001, EMP-0002, etc.
- **Auto-generated username**: firstname.lastname (lowercase)
- **Default password**: Vecura@123 (bcrypt hashed)
- **Full corporate details on Overview tab:**
  - Personal info: Code, Name, Email, DOB, Phone
  - Employment info: Department, Designation, Manager, Office Type, Status

### 2. Document Management (Auto-Approved)
**Educational Documents:**
- Types: Degree, 10th Certificate, 12th Certificate, Diploma, Certificate Course, Other
- Auto-approved on upload (no verification workflow)
- File view/download links in table
- Storage: `storage/documents/education/`

**Official Documents:**
- Types: Aadhar, PAN, Passport, Driving License, Voter ID, Birth Certificate, Medical Report, Police Clearance, Experience Letter, Relieving Letter, Other
- Auto-approved on upload
- Includes issue & expiry dates
- File view/download links in table
- Storage: `storage/documents/official/`

### 3. Bond Management
**Create Bond:**
- Duration in years (auto-calculates end date)
- Start date
- Optional: Amount, conditions, PDF document
- Status: Active → Completed → (optional: Cancelled)

**Bond Tracking:**
- View all bonds per employee
- Mark as "Completed" when conditions fulfilled
- Shows duration, dates, amount, status

### 4. Relieving / Exit Workflow (2-Month Notice)
**Initiate Relieving:**
- Set resignation date
- System auto-calculates 2-month notice completion date
- Optional: Reason for resignation

**Complete Relieving:**
- Relieving date
- Exit checklist:
  - All dues cleared (Yes/No)
  - Equipment returned (Yes/No)
- Final remarks
- Auto-updates employee status to "Terminated"

## Tab Structure

| Tab | Content | Features |
|-----|---------|----------|
| **Overview** | Personal & Employment details | Full corporate info display |
| **Education Docs** | Degree, certificates | Upload + View/Download links |
| **Official Docs** | Aadhar, PAN, Passport, etc. | Upload + View/Download links |
| **Bond** | Bond records | Create + Track + Complete |
| **Relieving** | Exit process | Initiate + Complete + Exit Checklist |

## API Routes

### Employee CRUD
```
GET    /staff                           - List all employees
GET    /staff/generate-code             - Get next auto-increment code
POST   /staff/store                     - Create employee
GET    /staff/{id}/details              - View employee profile (5 tabs)
PUT    /staff/{id}                      - Update employee basic info
```

### Role Management
```
POST   /staff/{employeeId}/role         - Assign role to employee
DELETE /staff/{employeeId}/role/{roleId}- Remove role
```

### Documents (Auto-Approved)
```
POST   /employee/{employeeId}/educational-document/add   - Upload education doc
GET    /employee/{employeeId}/educational-document/list  - List education docs
POST   /employee/{employeeId}/document/add               - Upload official doc
GET    /employee/{employeeId}/document/list              - List official docs
```

### Bond Management
```
POST   /employee/{employeeId}/bond/create                - Create bond
GET    /employee/{employeeId}/bond/list                  - Get all bonds
POST   /employee/{employeeId}/bond/{bondId}/complete     - Complete bond
```

### Relieving Workflow
```
POST   /employee/{employeeId}/relieving/initiate         - Start relieving (2-month notice)
POST   /employee/{employeeId}/relieving/complete         - Finalize relieving
```

### Status
```
GET    /employee/{employeeId}/workflow/status            - Get full employee status
```

## Database Tables

### Core Tables
```
user_master
├── UserID (PK), UserName, FullName, EmailId, Password
├── employee_code (AUTO: EMP-XXXX)
├── date_of_birth, office_type (office), manager_id (FK)
├── UserStatus, employee_status (Active/Terminated)
└── timestamps

employee_profiles
├── user_id (FK)
├── phone_number, address, blood_group
└── timestamps

employee_medical
├── user_id (FK)
├── allergies, medical_history
└── timestamps
```

### Document Tables
```
employee_educational_documents
├── user_id (FK), document_type (Enum)
├── document_number, issue_date
├── file_path (auto-approved, no verification)
└── timestamps

employee_documents (official)
├── user_id (FK), document_type (Enum)
├── document_number, issue_date, expiry_date
├── file_path (auto-approved)
└── timestamps
```

### Bond & Relieving Tables
```
employee_bonds
├── user_id (FK)
├── bond_duration_years, bond_start_date, bond_end_date
├── bond_amount, bond_conditions, bond_document_file
├── bond_status (Active/Completed/Cancelled)
└── timestamps

employee_relieving
├── user_id (FK)
├── resignation_date, notice_completion_date, relieving_date
├── reason_for_resignation
├── all_dues_cleared, equipment_returned, final_remarks
├── relieving_status (Pending/In Progress/Completed/Cancelled)
└── timestamps
```

## Workflow Examples

### Complete Employee Lifecycle

**1. Employee Creation (HR)**
```
POST /staff/store
{
  "full_name": "John Doe",
  "email": "john@company.com",
  "date_of_birth": "1990-05-15",
  "department_id": 5,
  "designation_code": "MGR001",
  "office_type": "Corporate Office"
}
→ Auto-generates: EMP-0001, username: john.doe, password: Vecura@123
```

**2. Upload Documents (Employee)**
```
POST /employee/1/educational-document/add
- Degree certificate → AUTO-APPROVED ✓

POST /employee/1/document/add
- Aadhar card → AUTO-APPROVED ✓
- PAN card → AUTO-APPROVED ✓
```

**3. Create Bond (HR)**
```
POST /employee/1/bond/create
{
  "bond_duration_years": 2,
  "bond_start_date": "2026-06-26",
  "bond_amount": 50000,
  "bond_conditions": "Must work for 2 years..."
}
→ Auto-calculates end date: 2028-06-26
```

**4. Initiate Relieving (Employee)**
```
POST /employee/1/relieving/initiate
{
  "resignation_date": "2026-07-01",
  "reason_for_resignation": "Career growth opportunity"
}
→ Auto-calculates notice completion: 2026-09-01 (2 months)
```

**5. Complete Relieving (HR)**
```
POST /employee/1/relieving/complete
{
  "relieving_date": "2026-09-01",
  "all_dues_cleared": true,
  "equipment_returned": true,
  "final_remarks": "All formalities completed"
}
→ Auto-updates: employee_status = 'Terminated'
```

## Security & RBAC

All routes protected with `check.permission` middleware:
- `create,staff` - Create employees, upload documents, create bonds
- `read,staff` - View employees, list documents
- `edit,staff` - Update employees, complete bonds, manage relieving

## File Upload & Storage

**Education Documents**
- Path: `storage/documents/education/emp_{id}_edu_doc_{timestamp}.{ext}`
- Max: 5MB
- Types: PDF, JPG, PNG

**Official Documents**
- Path: `storage/documents/official/emp_{id}_doc_{timestamp}.{ext}`
- Max: 5MB
- Types: PDF, JPG, PNG

**Bond Documents**
- Path: `storage/bonds/emp_{id}_bond_{timestamp}.pdf`
- Max: 5MB
- Type: PDF only

## Key Implementation Details

### Auto-Approval System
- Documents marked as "approved" immediately on upload
- No verification workflow needed
- View/Download links available in document tables

### 2-Month Notice Period
- Resignation date → Auto-calculates notice completion (+2 months)
- Tracks both resignation and final relieving date
- Separates notice period tracking from actual relieving

### Bond Duration Calculation
- Takes years input + start date
- Auto-calculates end date using Carbon::addYears()
- End date = Start date + N years

### Employee Status Updates
- `employee_status` column tracks: Active/Terminated
- Auto-set to "Terminated" when relieving is completed
- Separate from `UserStatus` (login/auth status)

## Testing Checklist

- [ ] Create employee with auto-generated code
- [ ] Verify username is generated as firstname.lastname
- [ ] Default password Vecura@123 works
- [ ] Upload education document - verify appears auto-approved
- [ ] Upload official document - verify appears auto-approved
- [ ] Download/view document links work
- [ ] Create bond - verify end date calculated correctly
- [ ] Mark bond complete - status changes
- [ ] Initiate relieving - notice date is +2 months from resignation
- [ ] Complete relieving - employee_status becomes Terminated
- [ ] RBAC permissions enforce all routes
- [ ] Only authorized users can edit/create

## Changes from v1

✅ Restored bond and relieving workflows
✅ Removed document verification workflow (auto-approved)
✅ Added file view/download links
✅ Enhanced Overview with full details
✅ Added 2-month notice period tracking
✅ Added exit checklist to relieving
✅ Auto-set terminated status
✅ Simplified routes (removed verify endpoints)
