-- =========================
-- DEPARTMENTS
-- =========================
INSERT INTO departments (Departmentid, DepartmentName) VALUES
(1, 'Human Resources'),
(2, 'Information Technology'),
(3, 'Finance'),
(4, 'Operations'),
(5, 'Customer Support'),
(6, 'Sales'),
(7, 'Marketing');



-- =========================
-- ROLES
-- =========================
INSERT INTO roles (RoleId, RoleName) VALUES
(1, 'Super Admin'),
(2, 'HR'),
(3, 'Manager'),
(4, 'Team Lead'),
(5, 'Employee'),
(6, 'Support Executive'),
(7, 'Accounts');



-- =========================
-- DESIGNATIONS
-- =========================
INSERT INTO designations (DesignationId, DesignationName) VALUES
('DES-0001', 'HR Executive'),
('DES-0002', 'HR Manager'),
('DES-0003', 'Laravel Developer'),
('DES-0004', 'React Developer'),
('DES-0005', 'Full Stack Developer'),
('DES-0006', 'Business Analyst'),
('DES-0007', 'Team Lead'),
('DES-0008', 'Finance Manager'),
('DES-0009', 'Accountant'),
('DES-0010', 'Support Executive'),
('DES-0011', 'Support Lead'),
('DES-0012', 'Sales Executive'),
('DES-0013', 'Marketing Executive');



-- =========================
-- EMPLOYEES WITH HIERARCHY
-- =========================
INSERT INTO employees
(
    UserID,
    UserCode,
    FullName,
    DepartmentId,
    Designation,
    RoleId,
    ManagerId
)
VALUES

-- SUPER ADMIN
(1, 'EMP001', 'Admin User', 2, 'DES-0007', 1, NULL),

-- HR DEPARTMENT
(2, 'EMP002', 'Naveen HR Manager', 1, 'DES-0002', 3, 1),
(3, 'EMP003', 'Arun HR Executive', 1, 'DES-0001', 2, 2),

-- IT DEPARTMENT
(4, 'EMP004', 'Karthik Team Lead', 2, 'DES-0007', 4, 1),
(5, 'EMP005', 'Sanjay Laravel Developer', 2, 'DES-0003', 5, 4),
(6, 'EMP006', 'Vignesh React Developer', 2, 'DES-0004', 5, 4),
(7, 'EMP007', 'Rahul Full Stack Developer', 2, 'DES-0005', 5, 4),
(8, 'EMP008', 'Priya Business Analyst', 2, 'DES-0006', 5, 4),

-- FINANCE DEPARTMENT
(9, 'EMP009', 'Keerthana Finance Manager', 3, 'DES-0008', 3, 1),
(10, 'EMP010', 'Ramesh Accountant', 3, 'DES-0009', 7, 9),

-- SUPPORT DEPARTMENT
(11, 'EMP011', 'Divya Support Lead', 5, 'DES-0011', 4, 1),
(12, 'EMP012', 'Suresh Support Executive', 5, 'DES-0010', 6, 11),

-- SALES DEPARTMENT
(13, 'EMP013', 'Ajay Sales Executive', 6, 'DES-0012', 5, 1),

-- MARKETING DEPARTMENT
(14, 'EMP014', 'Meena Marketing Executive', 7, 'DES-0013', 5, 1);
