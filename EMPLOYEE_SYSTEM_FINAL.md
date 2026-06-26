# Employee Management System - Complete Implementation

## Overview
A complete employee management system with RBAC-protected routes, document upload/verification workflow, and role-based access control.

## Core Features

### 1. Employee Creation
- **Auto-incrementing employee codes** (EMP-0001, EMP-0002, etc.)
- Default password: `Vecura@123` (bcrypt hashed)
- Auto-generated username: `firstname.lastname` (lowercase)
- Required fields: Full Name, Email, Date of Birth, Department, Designation
- Optional fields: Phone, Address, Office Type (Branch Location, Corporate Office, Head Office, Regional Office)
- Manager assignment for hierarchy support

### 2. Employee Profile Management
- **3-Tab Interface:**
  - **Overview Tab**: Personal & Employment information display
  - **Education Documents Tab**: Degree, 10th Certificate, 12th Certificate uploads
  - **Official Documents Tab**: Aadhar, PAN, Passport, Driving License, etc. uploads

- **Document Upload Workflow:**
  - Employee uploads document → Status: "Pending"
  - Admin verifies → Status: "Verified" or "Rejected"
  - Each document stores: document_type, document_number, issue_date, file_path, verification_status

### 3. Database Schema

#### Core Tables
```
user_master (employees)
├── UserID, UserName, FullName, EmailId, Password
├── employee_code (auto-increment: EMP-XXXX)
├── date_of_birth, UserStatus, manager_id (FK to UserID)
└── office_type (Enum: Branch Location, Corporate Office, Head Office, Regional Office)

employee_profiles
├── user_id (FK)
├── phone_number, address, etc.
└── timestamps

employee_medical
├── user_id (FK)
├── blood_group, allergies, medical_history
└── timestamps

employee_educational_documents
├── user_id (FK)
├── document_type (Enum: Degree, 10th Certificate, 12th Certificate, Diploma, Certificate Course, Other)
├── document_number, issue_date
├── file_path (storage/documents/education/)
├── verification_status (Enum: Pending, Verified, Rejected)
├── verified_by, verification_date, verification_notes
└── timestamps

employee_documents (official)
├── user_id (FK)
├── document_type (Enum: Aadhar, PAN, Passport, Driving License, Voter ID, Birth Certificate, Medical Report, Police Clearance, Experience Letter, Relieving Letter, Other)
├── document_number, issue_date, expiry_date
├── file_path (storage/documents/official/)
├── verification_status (Enum: Pending, Verified, Rejected)
├── verified_by, verification_date, verification_notes
└── timestamps
```

### 4. Routes & API Endpoints

#### Employee CRUD
```
GET    /staff                           - List all employees
GET    /staff/generate-code             - Get next auto-increment code
POST   /staff/store                     - Create employee
GET    /staff/{id}/details              - View employee profile (3-tab form)
PUT    /staff/{id}                      - Update employee basic info
```

#### Role Management
```
POST   /staff/{employeeId}/role         - Assign role to employee
DELETE /staff/{employeeId}/role/{roleId}- Remove role from employee
```

#### Educational Documents
```
POST   /employee/{employeeId}/educational-document/add     - Upload educational document
GET    /employee/{employeeId}/educational-document/list    - Get all educational documents
POST   /employee/{employeeId}/educational-document/{docId}/verify - Verify (admin only)
```

#### Official Documents
```
POST   /employee/{employeeId}/document/add                 - Upload official document
GET    /employee/{employeeId}/document/list                - Get all official documents
POST   /employee/{employeeId}/document/{documentId}/verify - Verify (admin only)
```

#### Status & Workflow
```
GET    /employee/{employeeId}/workflow/status              - Get complete employee workflow status
```

### 5. RBAC Permission Middleware

All routes protected with `check.permission:create,staff` or `check.permission:read,staff`

**Required Permissions:**
- `create,staff` - Create employees, upload documents
- `read,staff` - View employees, list documents
- `edit,staff` - Update employees, verify documents, assign roles
- `delete,staff` - Remove employees (if implemented)

### 6. File Upload & Storage

**Educational Documents:**
- Path: `storage/documents/education/`
- File naming: `emp_{employeeId}_edu_doc_{timestamp}.{ext}`
- Max size: 5MB
- Allowed types: PDF, JPG, JPEG, PNG

**Official Documents:**
- Path: `storage/documents/official/`
- File naming: `emp_{employeeId}_doc_{timestamp}.{ext}`
- Max size: 5MB
- Allowed types: PDF, JPG, JPEG, PNG

### 7. Controllers

**StaffController**
- index() - List employees with RBAC filter
- generateEmployeeCode() - Return next code via AJAX
- store() - Create employee (UserMaster + profiles + medical)
- details() - Load employee-details.blade.php
- update() - Update basic employee info
- assignRole() - Assign role to employee
- removeRole() - Remove role from employee

**EmployeeWorkflowController**
- addEducationalDocument() - Upload educational document
- getEducationalDocuments() - Retrieve all educational documents
- verifyEducationalDocument() - Verify document (admin only)
- addDocument() - Upload official document
- getDocuments() - Retrieve all official documents
- verifyDocument() - Verify document (admin only)
- getEmployeeStatus() - Get complete workflow status

### 8. Models

```
UserMaster
├── relationships: departments(), designation, manager(), documents(), educationalDocuments()
├── attributes: employee_code, date_of_birth, office_type
└── fillable: FullName, EmailId, UserName, Password, etc.

EmployeeEducationalDocument
├── fillable: user_id, document_type, document_number, issue_date, file_path, verification_status
└── employee() - belongsTo(UserMaster)

EmployeeDocument
├── fillable: user_id, document_type, document_number, issue_date, expiry_date, file_path, verification_status
├── employee() - belongsTo(UserMaster)
└── getDocumentTypeBadge() - UI helper for styling
```

## Workflow Example

### Employee Onboarding Flow
1. **HR Creates Employee**
   - POST /staff/store
   - Auto-generates EMP-XXXX code
   - Sets default password: Vecura@123
   - Creates user_master, employee_profile, employee_medical records

2. **Employee Uploads Documents**
   - Navigates to `/staff/{id}/details`
   - Clicks "Education Documents" tab
   - Uploads Degree/10th/12th certificates
   - Status shows "Pending"

3. **Admin Verifies Documents**
   - Admin views employee details
   - Reviews uploaded documents
   - Clicks "Verify" button
   - Document status changes to "Verified"
   - Timestamp & admin name recorded

## Security

✅ **RBAC Protected** - All routes require specific permissions
✅ **Password Hashed** - Bcrypt hashing for all passwords
✅ **File Upload Validation** - Type, size, mime validation
✅ **Database Transactions** - Multi-table inserts wrapped in transactions
✅ **Permission Middleware** - check.permission guards all endpoints
✅ **Input Validation** - Form request validation on all operations

## What's NOT Included

❌ Bond tracking
❌ Relieving/offboarding workflows
❌ Certificate handover tracking
❌ Education record tracking (only documents)
❌ Onboarding checklist

These were explicitly removed per user requirement: "bond and relieving, I don't need them"

## Testing Checklist

- [ ] Create employee with auto-generated code
- [ ] Verify default password works (Vecura@123)
- [ ] Upload educational document (Degree, 10th, 12th)
- [ ] Upload official document (Aadhar, PAN, Passport)
- [ ] Verify document as admin
- [ ] Check permission middleware blocks unauthorized access
- [ ] Test employee listing with RBAC filters
- [ ] Test update employee basic info
- [ ] Assign/remove roles from employee
- [ ] Verify file storage paths created correctly
