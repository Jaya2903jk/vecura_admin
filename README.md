ALTER TABLE issueTicket
ADD type VARCHAR(50) NULL;

ALTER TABLE CustomerRefundComplaint
ADD ticketId int NULL;

ALTER TABLE issueTicket
ALTER COLUMN AttachFile NVARCHAR(MAX) NULL;

DELETE FROM issueTicket
WHERE ticketId IN (87295, 87297, 87300);

DELETE FROM CustomerRefundComplaint
WHERE complaintid IN (307, 308, 309,310,311);

CREATE TABLE ApprovalFlows (
flowId INT PRIMARY KEY IDENTITY(1,1), -- SQL Server identity
issueId INT, -- FK to IssueMaster
levelOrder INT, -- order of approval (1,2,3...)
roleId INT, -- role responsible at this level
levelName VARCHAR(50), -- optional descriptive name
CONSTRAINT FK_Issue FOREIGN KEY(issueId) REFERENCES IssueMasterTest(IssueId)
);

ALTER TABLE ApprovalFlows
ADD
status VARCHAR(20) DEFAULT 'Pending', -- Pending | Accepted | Verified | Approved
note NVARCHAR(MAX) NULL; -- optional comment or note

ALTER TABLE CustomerRefundComplaint
ADD CurrentLevel INT NOT NULL
CONSTRAINT DF_CustomerRefundComplaint_CurrentLevel DEFAULT 0;

ALTER TABLE CustomerRefundComplaint
ADD issue_master_id INT NOT NULL
CONSTRAINT DF_CustomerRefundComplaint_IssueMaster DEFAULT 0;

CREATE TABLE complaint_followups (
id BIGINT IDENTITY(1,1) PRIMARY KEY,
complaint_id BIGINT,
ticket_id BIGINT,
level INT,
assigned_to VARCHAR(50),
action_by VARCHAR(50),
remarks NVARCHAR(MAX),
status VARCHAR(50),
created_at DATETIME DEFAULT GETDATE()
);
CREATE TABLE notifications (
id BIGINT IDENTITY(1,1) PRIMARY KEY,
user_id BIGINT NOT NULL,
title NVARCHAR(255) NOT NULL,
message NVARCHAR(MAX) NULL,
type NVARCHAR(255) NOT NULL, -- ticket, complaint, followup, system
reference_id BIGINT NULL,
is_read BIT NOT NULL DEFAULT 0,
created_at DATETIME NULL,
updated_at DATETIME NULL
);

CREATE TABLE hr_manpower_requests_new (
manpowerRequestId INT IDENTITY(1,1) PRIMARY KEY,
ticketId INT NOT NULL,

    departmentId INT NOT NULL,

    -- ✅ New columns placed here
    categoryId INT NOT NULL,
    escalationTypeId INT NOT NULL,

    designation NVARCHAR(150),
    vacancies INT NOT NULL,
    jobDescription NVARCHAR(MAX),

    ageMin INT NULL,
    ageMax INT NULL,

    gender NVARCHAR(20),
    experience NVARCHAR(50),
    qualification NVARCHAR(150),

    skills NVARCHAR(MAX),
    workLocation NVARCHAR(150),

    requestType NVARCHAR(50),
    replacementFor NVARCHAR(150) NULL,

    approvalStatus NVARCHAR(30) DEFAULT 'Pending',
    recruitmentStatus NVARCHAR(50) DEFAULT 'Waiting HR',
    onboardingStatus NVARCHAR(50) DEFAULT 'Not Started',

    remarks NVARCHAR(MAX) NULL,

    attachmentPath NVARCHAR(255) NULL,
    originalFileName NVARCHAR(255) NULL,

    created_by INT,
    created_at DATETIME DEFAULT GETDATE(),
    updated_by INT NULL,
    updated_at DATETIME NULL

);
ALTER TABLE hr_manpower_requests_new
ADD assigned_hr_id INT NULL;

ALTER TABLE hr_manpower_requests_new
ADD meta_data NVARCHAR(MAX) NULL;

CREATE TABLE hr_manpower_assignment (
assignmentId INT IDENTITY(1,1) PRIMARY KEY,
manpowerRequestId INT NOT NULL,
assignedTo INT NOT NULL,
assignedBy INT NULL,
assignedDate DATETIME DEFAULT GETDATE(),
isSelfAssigned BIT DEFAULT 1
);

CREATE TABLE hr_manpower_candidates (
candidateId INT IDENTITY(1,1) PRIMARY KEY,
manpowerRequestId INT NOT NULL,

    candidateName NVARCHAR(150),
    mobile NVARCHAR(20),
    email NVARCHAR(150),

    interviewDate DATETIME NULL,

    status NVARCHAR(50) DEFAULT 'In Progress',
    -- In Progress / Sourcing / Interview Scheduled / Interview Date Fixed
    -- Interviewed / Selected / Joined / Query / Wrong / Hold

    remarks NVARCHAR(MAX),

    employeeId INT NULL, -- after joining

    createdBy INT,
    createdAt DATETIME DEFAULT GETDATE(),

    updatedBy INT NULL,
    updatedAt DATETIME NULL

);

CREATE TABLE hr_manpower_status_history (
historyId INT IDENTITY(1,1) PRIMARY KEY,

    manpowerRequestId INT NOT NULL,
    candidateId INT NULL,

    oldStatus NVARCHAR(50),
    newStatus NVARCHAR(50),

    remarks NVARCHAR(MAX),

    changedBy INT,
    changedAt DATETIME DEFAULT GETDATE()

);

CREATE TABLE MachineTable (
MachineId INT PRIMARY KEY,
MachineName VARCHAR(100),
MachineRelated VARCHAR(255),
Status VARCHAR(20), -- Active / Inactive
CreatedBy VARCHAR(100),
CreatedDate DATETIME
);
CREATE TABLE MachineIssuesTable (
machineIssueId INT IDENTITY(1,1) PRIMARY KEY,
IssuesName VARCHAR(255),
MachineId INT,
Type VARCHAR(50),
Status VARCHAR(20),
CreatedBy VARCHAR(100),
CreatedDate DATETIME DEFAULT GETDATE(),

);
