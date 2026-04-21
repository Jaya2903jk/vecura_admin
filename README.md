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
