# 🧪 Employee Workflow Testing Guide

## Quick Start - Complete Employee Lifecycle Test

### **Step 1: Create an Employee**

```bash
curl -X POST http://127.0.0.1:8000/staff/store \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: {csrf_token}" \
  -d '{
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
  }'
```

**Expected Response:**
```json
{
  "status": true,
  "message": "Employee created successfully",
  "credentials": {
    "username": "rajesh.kumar",
    "password": "Vecura@123",
    "employee_code": "EMP-0001"
  }
}
```

---

### **Step 2: Verify Employee Status (After Creation)**

```bash
curl -X GET http://127.0.0.1:8000/employee/1/workflow/status \
  -H "Authorization: Bearer {token}"
```

**Expected Response:**
```json
{
  "status": true,
  "onboarding": {
    "onboarding_status": "Pending"
  },
  "offboarding": null,
  "summary": {
    "education_count": 0,
    "certificates_count": 0,
    "documents_count": 0,
    "bonds_count": 0
  }
}
```

---

### **Step 3: Add Education Records**

#### **Add 10th Standard Certificate**
```bash
curl -X POST http://127.0.0.1:8000/employee/1/education/add \
  -H "Content-Type: multipart/form-data" \
  -F "education_level=10th Certificate" \
  -F "institution_name=Government School" \
  -F "graduation_date=2006-05-30" \
  -F "grade_percentage=78.5"
```

#### **Add 12th Standard Certificate**
```bash
curl -X POST http://127.0.0.1:8000/employee/1/education/add \
  -H "Content-Type: multipart/form-data" \
  -F "education_level=12th Certificate" \
  -F "institution_name=Senior Secondary School" \
  -F "graduation_date=2008-05-30" \
  -F "grade_percentage=82.3"
```

#### **Add Degree**
```bash
curl -X POST http://127.0.0.1:8000/employee/1/education/add \
  -H "Content-Type: multipart/form-data" \
  -F "education_level=Degree" \
  -F "institution_name=IIT Delhi" \
  -F "course_name=B.Tech" \
  -F "field_of_study=Computer Science" \
  -F "graduation_date=2012-05-30" \
  -F "grade_percentage=85.5"
```

**Expected Response:**
```json
{
  "status": true,
  "message": "Education record added",
  "data": {
    "id": 1,
    "user_id": 1,
    "education_level": "Degree"
  }
}
```

---

### **Step 4: Mark Education as Handed Over**

```bash
curl -X POST http://127.0.0.1:8000/employee/1/education/1/handover \
  -H "Content-Type: application/json" \
  -d '{"received_by": "HR Manager"}'
```

---

### **Step 5: Add Professional Certificates**

```bash
curl -X POST http://127.0.0.1:8000/employee/1/certificate/add \
  -H "Content-Type: application/json" \
  -d '{
    "certificate_name": "AWS Solutions Architect",
    "issuing_organization": "Amazon Web Services",
    "issue_date": "2024-01-15",
    "expiry_date": "2027-01-15",
    "certificate_number": "AWS-123456"
  }'
```

**Get All Certificates:**
```bash
curl -X GET http://127.0.0.1:8000/employee/1/certificate/list
```

---

### **Step 6: Submit Original Documents**

#### **Submit Degree**
```bash
curl -X POST http://127.0.0.1:8000/employee/1/document/add \
  -H "Content-Type: multipart/form-data" \
  -F "document_type=Degree" \
  -F "document_number=REG-2012-00123" \
  -F "issue_date=2012-05-30" \
  -F "file_path=@degree.pdf"
```

#### **Submit 10th Certificate**
```bash
curl -X POST http://127.0.0.1:8000/employee/1/document/add \
  -H "Content-Type: multipart/form-data" \
  -F "document_type=10th Certificate" \
  -F "document_number=10TH-2006-00456" \
  -F "issue_date=2006-05-30" \
  -F "file_path=@10th.pdf"
```

#### **Submit 12th Certificate**
```bash
curl -X POST http://127.0.0.1:8000/employee/1/document/add \
  -H "Content-Type: multipart/form-data" \
  -F "document_type=12th Certificate" \
  -F "document_number=12TH-2008-00789" \
  -F "issue_date=2008-05-30" \
  -F "file_path=@12th.pdf"
```

**Get All Documents:**
```bash
curl -X GET http://127.0.0.1:8000/employee/1/document/list
```

---

### **Step 7: Verify Documents**

```bash
curl -X POST http://127.0.0.1:8000/employee/1/document/1/verify \
  -H "Content-Type: application/json" \
  -d '{"verified_by": "HR Verification Officer"}'
```

---

### **Step 8: Create Bond**

```bash
curl -X POST http://127.0.0.1:8000/employee/1/bond/create \
  -H "Content-Type: application/json" \
  -d '{
    "bond_duration_years": 2,
    "bond_start_date": "2026-06-25",
    "bond_amount": 100000,
    "bond_conditions": "Service bond for 2 years"
  }'
```

**Expected Response:**
```json
{
  "status": true,
  "message": "Bond created",
  "data": {
    "bond_duration_years": 2,
    "bond_start_date": "2026-06-25",
    "bond_end_date": "2028-06-25",
    "status": "Active",
    "days_remaining": 730
  }
}
```

---

### **Step 9: Initiate Relieving (2-Month Notice)**

```bash
curl -X POST http://127.0.0.1:8000/employee/1/relieving/initiate \
  -H "Content-Type: application/json" \
  -d '{
    "resignation_date": "2026-06-25",
    "reason_for_resignation": "Better opportunity"
  }'
```

**Expected Response:**
```json
{
  "status": true,
  "message": "Relieving initiated with 2-month notice period",
  "data": {
    "resignation_date": "2026-06-25",
    "notice_period_end_date": "2026-08-24",
    "relieving_date": "2026-08-24",
    "days_remaining": 60
  }
}
```

**Note:** Employee Status automatically changes to "On Leave"

---

### **Step 10: Complete Relieving**

```bash
curl -X POST http://127.0.0.1:8000/employee/1/relieving/complete \
  -H "Content-Type: application/json" \
  -d '{
    "relieving_date": "2026-08-24",
    "all_dues_cleared": true,
    "equipment_returned": true,
    "final_remarks": "Employee relieved successfully"
  }'
```

**Expected Response:**
```json
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
```

**Note:** Employee Status automatically changes to "Inactive"

---

### **Step 11: Get Final Employee Status**

```bash
curl -X GET http://127.0.0.1:8000/employee/1/workflow/status
```

**Expected Response:**
```json
{
  "status": true,
  "employee": {
    "UserID": 1,
    "FullName": "Rajesh Kumar",
    "employee_code": "EMP-0001",
    "employee_status": "Inactive"
  },
  "onboarding": {
    "onboarding_status": "Pending"
  },
  "offboarding": {
    "offboarding_status": "Completed",
    "resignation_date": "2026-06-25",
    "notice_period_end_date": "2026-08-24",
    "relieving_date": "2026-08-24"
  },
  "summary": {
    "education_count": 3,
    "certificates_count": 1,
    "documents_count": 3,
    "bonds_count": 1,
    "pending_certificates": 0,
    "pending_education": 0,
    "pending_documents": 0
  }
}
```

---

## 🧮 Testing Checklist

### **Employee Creation Phase** ✅
- [ ] Create employee with all details
- [ ] Verify auto-generated username: `firstname.lastname`
- [ ] Verify auto-generated password: `Vecura@123`
- [ ] Verify auto-generated employee code: `EMP-0001`
- [ ] Verify manager assigned
- [ ] Verify department & designation assigned
- [ ] Verify office_type set correctly

### **Education Phase** ✅
- [ ] Add 10th certificate
- [ ] Add 12th certificate
- [ ] Add degree
- [ ] Mark each education as handed over
- [ ] Verify education count in summary

### **Document Phase** ✅
- [ ] Submit degree document
- [ ] Submit 10th certificate document
- [ ] Submit 12th certificate document
- [ ] Verify all documents (status: "Verified")
- [ ] Verify document count in summary

### **Certificate Phase** ✅
- [ ] Add professional certificate
- [ ] Verify certificate count in summary
- [ ] Mark certificate as handed over

### **Bond Phase** ✅
- [ ] Create 2-year bond
- [ ] Verify bond_end_date auto-calculated (2028-06-25)
- [ ] Verify bond status: "Active"
- [ ] Verify days_remaining calculated correctly

### **Relieving Phase** ✅
- [ ] Initiate relieving with resignation date
- [ ] Verify notice period: 60 days
- [ ] Verify notice_period_end_date: resignation_date + 60 days
- [ ] Verify employee status changed to "On Leave"
- [ ] Complete relieving with dues cleared & equipment returned
- [ ] Verify employee status changed to "Inactive"
- [ ] Verify offboarding_status: "Completed"

### **Final Status** ✅
- [ ] Get employee workflow status
- [ ] Verify all counts match (3 education, 1 certificate, 3 documents, 1 bond)
- [ ] Verify employee marked as "Inactive"
- [ ] Verify offboarding completed with all items handed over/verified

---

## 🔍 Common Test Cases

### **Test Case 1: Corporate Office Employee (No Branch)**
```json
{
  "first_name": "Priya",
  "last_name": "Singh",
  "office_type": "Corporate Office",
  "branch_id": null  // Should be ignored/disabled
}
```

### **Test Case 2: Bond Expiry Alert**
- Create bond: 2024-01-01 to 2025-01-01
- Test: days_remaining calculation for alerts

### **Test Case 3: Multi-Education Tracking**
- Add education: 10th, 12th, Degree
- Handover only 2 of them
- Verify pending count = 1

### **Test Case 4: Document Verification Workflow**
- Add 3 documents (Degree, 10th, 12th)
- Verify only 2
- Initiate relieving (should still show 1 pending)
- Verify remaining 1
- Complete relieving

### **Test Case 5: Notice Period Calculation**
- Resignation date: 2026-06-25
- Notice period should end: 2026-08-24 (exactly 60 days)

---

## 📊 Performance Tips

- Use **employee_id** from creation response for subsequent calls
- Batch document uploads (use multipart/form-data)
- Cache employee status between operations
- Use GET for read operations before completing workflows

---

Generated: 2026-06-25
