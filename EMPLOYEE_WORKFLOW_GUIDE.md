# 🎯 Complete Employee Workflow Implementation Guide

## Overview
This document describes the complete employee lifecycle management system with the following stages:

1. **Employee Creation** → 2. **Onboarding** → 3. **Education/Certificates/Documents** → 4. **Bond** → 5. **Relieving**

---

## 📊 Employee Lifecycle Flow

```
┌─────────────────────────────────────────────────────────────────────────┐
│                     EMPLOYEE LIFECYCLE MANAGEMENT                        │
└─────────────────────────────────────────────────────────────────────────┘

┌──────────────┐
│   CREATION   │  ✓ Auto-generate Employee Code (EMP-0001)
│              │  ✓ Auto-generate Username (firstname.lastname)
│              │  ✓ Auto-generate Password (Vecura@123)
│              │  ✓ Assign Manager, Department, Designation
│              │  ✓ Set Office Type (Branch/Corporate/Head/Regional)
└──────┬───────┘
       │
       ↓
┌──────────────┐
│ ONBOARDING   │  ✓ System Access Provided
│ (Immediate)  │  ✓ ID Card Issued
│              │  ✓ Equipment Provided
│              │  ✓ Orientation Completed
│              │  ✓ Training Started
│              │  ✓ Documentation Submitted
└──────┬───────┘
       │
       ↓
┌──────────────────────────┐
│  EDUCATION MANAGEMENT    │  ✓ Add Education Records (10th, 12th, Degree)
│  (During Employment)     │  ✓ Track Certificate Handover
│                          │  ✓ Mark as "Handed Over"
└──────┬───────────────────┘
       │
       ↓
┌──────────────────────────┐
│  CERTIFICATE MANAGEMENT  │  ✓ Add Professional Certificates
│  (During Employment)     │  ✓ Track Certificate Submission
│                          │  ✓ Mark as "Handed Over"
└──────┬───────────────────┘
       │
       ↓
┌──────────────────────────┐
│  DOCUMENT MANAGEMENT     │  ✓ Submit Original Documents
│  (During Employment)     │  ✓ Verify Documents (Degree, 10th, 12th)
│                          │  ✓ Track Verification Status
└──────┬───────────────────┘
       │
       ↓
┌──────────────────────────┐
│   BOND MANAGEMENT        │  ✓ Create Bond (1-3 years)
│  (During Employment)     │  ✓ Auto-calculate Bond End Date
│                          │  ✓ Mark Bond as Completed
└──────┬───────────────────┘
       │
       ↓
┌──────────────────────────┐
│   RELIEVING PROCESS      │  ✓ Initiate Resignation (Resignation Date)
│   (2-Month Notice)       │  ✓ Auto-calculate Notice Period End Date
│                          │  ✓ Auto-mark as "On Leave"
│                          │  ✓ Verify All Handovers Complete
│                          │  ✓ Clear Dues
│                          │  ✓ Return Equipment
│                          │  ✓ Complete Relieving
│                          │  ✓ Auto-mark as "Inactive"
└──────────────────────────┘
```

---

## 🔧 API Endpoints

### **1. EMPLOYEE CREATION**

```bash
POST /staff/store
Content-Type: multipart/form-data

PAYLOAD:
{
  "first_name": "Rajesh",
  "last_name": "Kumar",
  "email": "rajesh@company.com",
  "date_of_birth": "1990-05-15",
  "department_id": 52,
  "designation_code": "DES-0186",
  "office_type": "Branch Location",
  "branch_id": 1,
  "manager_id": 23901,
  "phone": "9876543210",
  "gender": "Male",
  "employee_category": "White Collar",
  "date_of_joining": "2026-06-25",
  "employee_status": "Active"
}

RESPONSE:
{
  "status": true,
  "message": "Employee created successfully",
  "employee": { /* employee data */ },
  "credentials": {
    "username": "rajesh.kumar",
    "password": "Vecura@123",
    "employee_code": "EMP-0001"
  }
}
```

---

### **2. EDUCATION MANAGEMENT**

#### **Add Education Record**
```bash
POST /employee/{employeeId}/education/add
Content-Type: multipart/form-data

PAYLOAD:
{
  "education_level": "Degree",
  "institution_name": "IIT Delhi",
  "course_name": "B.Tech",
  "field_of_study": "Computer Science",
  "graduation_date": "2012-05-30",
  "grade_percentage": "85.5",
  "certificate_file": <file>
}

RESPONSE:
{
  "status": true,
  "message": "Education record added",
  "data": { /* education data */ }
}
```

#### **Get All Education Records**
```bash
GET /employee/{employeeId}/education/list

RESPONSE:
{
  "status": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "education_level": "Degree",
      "institution_name": "IIT Delhi",
      "certificate_handover_received": false,
      "handover_date": null,
      "received_by": null
    }
  ]
}
```

#### **Mark Education Certificate as Handed Over**
```bash
POST /employee/{employeeId}/education/{educationId}/handover
Content-Type: application/json

PAYLOAD:
{
  "received_by": "HR Manager Name"
}

RESPONSE:
{
  "status": true,
  "message": "Education certificate marked as handed over"
}
```

---

### **3. CERTIFICATE MANAGEMENT**

#### **Add Professional Certificate**
```bash
POST /employee/{employeeId}/certificate/add
Content-Type: multipart/form-data

PAYLOAD:
{
  "certificate_name": "AWS Solutions Architect",
  "issuing_organization": "Amazon Web Services",
  "issue_date": "2024-01-15",
  "expiry_date": "2027-01-15",
  "certificate_number": "AWS-123456",
  "description": "Professional certification",
  "file_path": <file>
}

RESPONSE:
{
  "status": true,
  "message": "Certificate added",
  "data": { /* certificate data */ }
}
```

#### **Get All Certificates**
```bash
GET /employee/{employeeId}/certificate/list

RESPONSE:
{
  "status": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "certificate_name": "AWS Solutions Architect",
      "is_original_submitted": false,
      "is_handover_received": false
    }
  ]
}
```

#### **Mark Certificate as Handed Over**
```bash
POST /employee/{employeeId}/certificate/{certificateId}/handover
Content-Type: application/json

PAYLOAD:
{
  "received_by": "HR Manager"
}

RESPONSE:
{
  "status": true,
  "message": "Certificate marked as handed over"
}
```

---

### **4. DOCUMENT MANAGEMENT**

#### **Submit Original Document**
```bash
POST /employee/{employeeId}/document/add
Content-Type: multipart/form-data

PAYLOAD:
{
  "document_type": "Degree",           // or 10th Certificate, 12th Certificate, etc.
  "document_number": "REG-2012-00123",
  "issue_date": "2012-05-30",
  "expiry_date": null,
  "file_path": <file>,
  "description": "Bachelor of Technology"
}

DOCUMENT TYPES:
- Degree
- 10th Certificate
- 12th Certificate
- Aadhar
- PAN
- Driving License
- Passport
- Medical Report
- Police Clearance
- Other

RESPONSE:
{
  "status": true,
  "message": "Document added",
  "data": { /* document data */ }
}
```

#### **Get All Documents**
```bash
GET /employee/{employeeId}/document/list

RESPONSE:
{
  "status": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "document_type": "Degree",
      "verification_status": "Pending",
      "verified_by": null,
      "verification_date": null
    }
  ]
}
```

#### **Verify Document**
```bash
POST /employee/{employeeId}/document/{documentId}/verify
Content-Type: application/json

PAYLOAD:
{
  "verified_by": "HR Verification Officer"
}

RESPONSE:
{
  "status": true,
  "message": "Document verified"
}
```

---

### **5. BOND MANAGEMENT**

#### **Create Bond**
```bash
POST /employee/{employeeId}/bond/create
Content-Type: multipart/form-data

PAYLOAD:
{
  "bond_duration_years": 2,              // 1-3 years
  "bond_start_date": "2026-06-25",      // Today
  "bond_amount": 100000,                 // Optional
  "bond_conditions": "Service bond for 2 years",
  "bond_document_file": <file>          // PDF preferred
}

RESPONSE:
{
  "status": true,
  "message": "Bond created",
  "data": {
    "id": 1,
    "bond_start_date": "2026-06-25",
    "bond_end_date": "2028-06-25",      // Auto-calculated
    "bond_duration_years": 2,
    "status": "Active",
    "days_remaining": 730
  }
}
```

#### **Get All Bonds**
```bash
GET /employee/{employeeId}/bond/list

RESPONSE:
{
  "status": true,
  "data": [
    {
      "id": 1,
      "bond_duration_years": 2,
      "bond_start_date": "2026-06-25",
      "bond_end_date": "2028-06-25",
      "status": "Active",
      "days_remaining": 730
    }
  ]
}
```

#### **Mark Bond as Completed**
```bash
POST /employee/{employeeId}/bond/{bondId}/complete
Content-Type: application/json

RESPONSE:
{
  "status": true,
  "message": "Bond marked as completed"
}
```

---

### **6. RELIEVING PROCESS (2-MONTH NOTICE)**

#### **Initiate Relieving**
```bash
POST /employee/{employeeId}/relieving/initiate
Content-Type: application/json

PAYLOAD:
{
  "resignation_date": "2026-06-25",
  "reason_for_resignation": "Better opportunity"
}

RESPONSE:
{
  "status": true,
  "message": "Relieving initiated with 2-month notice period",
  "data": {
    "resignation_date": "2026-06-25",
    "notice_period_end_date": "2026-08-24",    // 60 days later
    "relieving_date": "2026-08-24",
    "days_remaining": 60
  }
}

NOTE: Employee Status automatically changes to "On Leave"
```

#### **Complete Relieving**
```bash
POST /employee/{employeeId}/relieving/complete
Content-Type: application/json

PAYLOAD:
{
  "relieving_date": "2026-08-24",
  "all_dues_cleared": true,
  "equipment_returned": true,
  "final_remarks": "Employee relieved successfully"
}

RESPONSE:
{
  "status": true,
  "message": "Employee relieving completed",
  "checklist": {
    "certificates_handed_over": true,
    "education_handed_over": true,
    "documents_verified": true,
    "dues_cleared": true,
    "equipment_returned": true
  }
}

NOTE: Employee Status automatically changes to "Inactive"
```

---

### **7. GET COMPLETE EMPLOYEE STATUS**

```bash
GET /employee/{employeeId}/workflow/status

RESPONSE:
{
  "status": true,
  "employee": { /* full employee data */ },
  "onboarding": {
    "id": 1,
    "onboarding_status": "Completed",
    "system_access_provided": true,
    "id_card_issued": true,
    "equipment_provided": true,
    "orientation_completed": true,
    "training_started": true,
    "documentation_submitted": true
  },
  "offboarding": {
    "id": 1,
    "offboarding_status": null,    // null if not relieved yet
    "resignation_date": null,
    "notice_period_end_date": null,
    "relieving_date": null
  },
  "summary": {
    "onboarding_status": "Completed",
    "offboarding_status": null,
    "education_count": 3,
    "certificates_count": 2,
    "documents_count": 5,
    "bonds_count": 1,
    "pending_certificates": 1,
    "pending_education": 0,
    "pending_documents": 0
  }
}
```

---

## 📋 Database Tables

### **User_Master (Enhanced)**
```sql
- UserID (PK)
- UserName (auto: firstname.lastname)
- Password (bcrypt hashed)
- employee_code (EMP-0001)
- first_name, last_name
- date_of_birth
- department_id
- designation_code
- office_type
- branch_id (nullable)
- manager_id (references UserID)
- employee_status (Active/Inactive/On Leave)
```

### **employee_profiles**
```sql
- id (PK)
- user_id (FK)
- employee_category
- date_of_birth
- gender
- phone_number
- address
- city, state, postal_code
- emergency_contact_name
- emergency_contact_phone
- employee_type
- date_of_joining
```

### **employee_medical**
```sql
- id (PK)
- user_id (FK)
- blood_group
- medical_conditions
- allergies
- emergency_contact_name
```

### **employee_onboarding**
```sql
- id (PK)
- user_id (FK)
- system_access_provided (boolean)
- id_card_issued (boolean)
- equipment_provided (boolean)
- orientation_completed (boolean)
- training_started (boolean)
- documentation_submitted (boolean)
- onboarding_status (Pending/In Progress/Completed)
```

### **employee_education**
```sql
- id (PK)
- user_id (FK)
- education_level (10th/12th/Degree/etc)
- institution_name
- course_name
- field_of_study
- graduation_date
- grade_percentage
- certificate_file (file path)
- certificate_handover_received (boolean)
- handover_date
- received_by
- status (Active/Expired/Not Submitted)
```

### **employee_certificates**
```sql
- id (PK)
- user_id (FK)
- certificate_name
- issuing_organization
- issue_date
- expiry_date
- certificate_number
- file_path
- is_original_submitted (boolean)
- is_handover_received (boolean)
- handover_date
- received_by
- status (Active/Expired/Revoked)
```

### **employee_documents**
```sql
- id (PK)
- user_id (FK)
- document_type (Degree/10th/12th/Aadhar/PAN/etc)
- document_number
- issue_date
- expiry_date
- file_path
- verification_status (Pending/Verified/Rejected)
- verified_by
- verification_date
- verification_notes
```

### **employee_bond**
```sql
- id (PK)
- user_id (FK)
- bond_duration_years
- bond_start_date
- bond_end_date (auto-calculated)
- bond_amount
- bond_conditions
- bond_document_file
- status (Active/Completed/Terminated)
- years_completed
```

### **employee_offboarding**
```sql
- id (PK)
- user_id (FK)
- resignation_date
- notice_period_days (fixed at 60)
- notice_period_end_date (auto-calculated)
- relieving_date
- reason_for_resignation
- relieving_certificate_submitted (boolean)
- experience_certificate_submitted (boolean)
- all_certificates_returned (boolean)
- all_dues_cleared (boolean)
- equipment_returned (boolean)
- offboarding_status (Pending/In Progress/Completed)
```

---

## 🔐 Default Credentials

```
Username: firstname.lastname (lowercase)
Password: Vecura@123

Example:
Username: rajesh.kumar
Password: Vecura@123
```

---

## ✅ Complete Workflow Checklist

### **Employee Creation Phase**
- [ ] Create employee with all details
- [ ] Auto-generated username & password
- [ ] Assign manager
- [ ] Assign department & designation
- [ ] Set office type (Branch/Corporate/Head/Regional)

### **Onboarding Phase**
- [ ] Complete all 6 onboarding items
- [ ] System access provided
- [ ] ID card issued
- [ ] Equipment provided
- [ ] Orientation completed
- [ ] Training started
- [ ] Documentation submitted

### **Employment Phase**
- [ ] Add education records (10th, 12th, Degree)
- [ ] Mark education certificates as handed over
- [ ] Add professional certificates
- [ ] Mark certificates as handed over
- [ ] Submit original documents (Degree, 10th, 12th)
- [ ] Verify all documents
- [ ] Create bond (1-3 years)
- [ ] Track bond period

### **Relieving Phase**
- [ ] Initiate relieving (2-month notice)
- [ ] Verify all certificates handed over
- [ ] Verify all documents verified
- [ ] Verify all education handed over
- [ ] Verify bond completed (if applicable)
- [ ] Verify dues cleared
- [ ] Verify equipment returned
- [ ] Complete relieving (employee marked as Inactive)

---

## 📚 Service Layer Methods

All business logic is in `EmployeeWorkflowService`:

```php
// Creation
- createEmployeeWithOnboarding($data)

// Onboarding
- completeOnboarding($employeeId)

// Education
- addEducation($employeeId, $data)
- markEducationHandover($educationId, $receivedBy)

// Certificates
- addCertificate($employeeId, $data)
- markCertificateHandover($certificateId, $receivedBy)

// Documents
- addDocument($employeeId, $data)
- verifyDocument($documentId, $verifiedBy)

// Bonds
- createBond($employeeId, $data)
- completeBond($bondId)

// Relieving
- initiateRelieving($employeeId, $data)
- completeRelieving($employeeId, $data)

// Status
- getEmployeeStatus($employeeId)
```

---

## 🚀 Implementation Status

✅ **COMPLETED:**
- Employee creation with auto-generation
- Onboarding workflow
- Education management
- Certificate management
- Document management
- Bond tracking
- Relieving process (2-month notice)
- Complete workflow status endpoint
- Service layer implementation
- Controller implementation
- Route definitions
- Model relationships
- Permission-based access control

---

## 📞 Next Steps

1. **Test the complete workflow** via API endpoints
2. **Create UI forms** for each workflow stage
3. **Add email notifications** for manager/HR
4. **Create reports** (onboarding %, relieving status, bond expiry alerts)
5. **Add webhooks** for third-party integrations

---

Generated: 2026-06-25
