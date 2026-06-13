-- ============================================================
-- Location Hierarchy Schema (SQL Server / T-SQL)
-- Tables: country, state, city, zone, branch, location
-- ============================================================

-- 1. Country
CREATE TABLE country (
country_id INT NOT NULL IDENTITY(1,1),
country_name NVARCHAR(100) NOT NULL,
country_code NCHAR(2) NOT NULL,
is_active BIT NOT NULL DEFAULT 1,
created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
updated_at DATETIME2 NOT NULL DEFAULT GETDATE(),
CONSTRAINT pk_country PRIMARY KEY (country_id),
CONSTRAINT uq_country_code UNIQUE (country_code)
);
GO

-- 2. State
CREATE TABLE state (
state_id INT NOT NULL IDENTITY(1,1),
country_id INT NOT NULL,
state_name NVARCHAR(100) NOT NULL,
state_code NVARCHAR(10) NOT NULL,
is_active BIT NOT NULL DEFAULT 1,
created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
updated_at DATETIME2 NOT NULL DEFAULT GETDATE(),
CONSTRAINT pk_state PRIMARY KEY (state_id),
CONSTRAINT uq_state_code UNIQUE (country_id, state_code),
CONSTRAINT fk_state_country
FOREIGN KEY (country_id) REFERENCES country (country_id)
ON DELETE NO ACTION ON UPDATE NO ACTION
);
GO

-- 3. City
CREATE TABLE city (
city_id INT NOT NULL IDENTITY(1,1),
state_id INT NOT NULL,
city_name NVARCHAR(100) NOT NULL,
pincode NVARCHAR(20) NULL,
is_active BIT NOT NULL DEFAULT 1,
created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
updated_at DATETIME2 NOT NULL DEFAULT GETDATE(),
CONSTRAINT pk_city PRIMARY KEY (city_id),
CONSTRAINT fk_city_state
FOREIGN KEY (state_id) REFERENCES state (state_id)
ON DELETE NO ACTION ON UPDATE NO ACTION
);
GO

-- 4. Zone (business region, scoped to a country)
CREATE TABLE zone (
zone_id INT NOT NULL IDENTITY(1,1),
country_id INT NOT NULL,
zone_name NVARCHAR(100) NOT NULL,
zone_code NVARCHAR(20) NOT NULL,
region_type NVARCHAR(50) NULL, -- e.g. 'North', 'South', 'East', 'West'
is_active BIT NOT NULL DEFAULT 1,
created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
updated_at DATETIME2 NOT NULL DEFAULT GETDATE(),
CONSTRAINT pk_zone PRIMARY KEY (zone_id),
CONSTRAINT uq_zone_code UNIQUE (country_id, zone_code),
CONSTRAINT fk_zone_country
FOREIGN KEY (country_id) REFERENCES country (country_id)
ON DELETE NO ACTION ON UPDATE NO ACTION
);
GO

-- 5. Branch (operational unit inside a zone, located in a city)
CREATE TABLE branch (
branch_id INT NOT NULL IDENTITY(1,1),
zone_id INT NOT NULL,
city_id INT NOT NULL,
branch_name NVARCHAR(150) NOT NULL,
branch_code NVARCHAR(20) NOT NULL,
manager_name NVARCHAR(100) NULL,
contact_no NVARCHAR(20) NULL,
is_active BIT NOT NULL DEFAULT 1,
created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
updated_at DATETIME2 NOT NULL DEFAULT GETDATE(),
CONSTRAINT pk_branch PRIMARY KEY (branch_id),
CONSTRAINT uq_branch_code UNIQUE (branch_code),
CONSTRAINT fk_branch_zone
FOREIGN KEY (zone_id) REFERENCES zone (zone_id)
ON DELETE NO ACTION ON UPDATE NO ACTION,
CONSTRAINT fk_branch_city
FOREIGN KEY (city_id) REFERENCES city (city_id)
ON DELETE NO ACTION ON UPDATE NO ACTION
);
GO

-- 6. Location (physical address, leaf of the hierarchy)
CREATE TABLE location (
location_id INT NOT NULL IDENTITY(1,1),
branch_id INT NOT NULL,
city_id INT NOT NULL,
location_name NVARCHAR(150) NOT NULL,
address NVARCHAR(MAX) NULL,
pincode NVARCHAR(20) NULL,
latitude DECIMAL(10, 7) NULL,
longitude DECIMAL(10, 7) NULL,
is_active BIT NOT NULL DEFAULT 1,
created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
updated_at DATETIME2 NOT NULL DEFAULT GETDATE(),
CONSTRAINT pk_location PRIMARY KEY (location_id),
CONSTRAINT fk_location_branch
FOREIGN KEY (branch_id) REFERENCES branch (branch_id)
ON DELETE NO ACTION ON UPDATE NO ACTION,
CONSTRAINT fk_location_city
FOREIGN KEY (city_id) REFERENCES city (city_id)
ON DELETE NO ACTION ON UPDATE NO ACTION
);
GO

-- ============================================================
-- Indexes for common query patterns
-- ============================================================

CREATE TABLE branch_wallet (
wallet_id INT NOT NULL IDENTITY(1,1),
branch_id INT NOT NULL,
current_balance DECIMAL(12,2) NOT NULL DEFAULT 0.00,
total_credited DECIMAL(12,2) NOT NULL DEFAULT 0.00,
total_debited DECIMAL(12,2) NOT NULL DEFAULT 0.00,
last_updated DATETIME2 NOT NULL DEFAULT GETDATE(),
CONSTRAINT pk_branch_wallet PRIMARY KEY (wallet_id),
CONSTRAINT uq_branch_wallet UNIQUE (branch_id),
CONSTRAINT fk_bw_branch
FOREIGN KEY (branch_id) REFERENCES branch (branch_id)
ON DELETE NO ACTION ON UPDATE NO ACTION
);


CREATE TABLE pc_request (
request_id INT NOT NULL IDENTITY(1,1),
ticket_id INT NOT NULL,
branch_id INT NOT NULL,
wallet_id INT NOT NULL,
raised_by INT NOT NULL,
requested_amount DECIMAL(12,2) NOT NULL,
approved_amount DECIMAL(12,2) NULL,
reason NVARCHAR(500) NULL,
urgency NVARCHAR(10) NOT NULL DEFAULT 'normal',
accounts_status NVARCHAR(20) NOT NULL DEFAULT 'open',
mgmt_status NVARCHAR(20) NULL,
transfer_ref NVARCHAR(100) NULL,
transferred_at DATETIME2 NULL,
ticket_status NVARCHAR(20) NOT NULL DEFAULT 'open',
wallet_balance_at_raise DECIMAL(12,2) NOT NULL DEFAULT 0.00,
closed_at DATETIME2 NULL,
created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
updated_at DATETIME2 NOT NULL DEFAULT GETDATE(),
CONSTRAINT pk_pc_request PRIMARY KEY (request_id),
CONSTRAINT uq_pc_request_tkt UNIQUE (ticket_id),
CONSTRAINT fk_pcr_ticket
FOREIGN KEY (ticket_id) REFERENCES tickets (ticket_id),
CONSTRAINT fk_pcr_branch
FOREIGN KEY (branch_id) REFERENCES branch (branch_id),
CONSTRAINT fk_pcr_wallet
FOREIGN KEY (wallet_id) REFERENCES branch_wallet (wallet_id),
CONSTRAINT chk_pcr_acct_status
CHECK (accounts_status IN ('open','under_review','approved','rejected','on_hold')),
CONSTRAINT chk_pcr_mgmt_status
CHECK (mgmt_status IS NULL OR mgmt_status IN ('pending','approved','transferred','rejected')),
CONSTRAINT chk_pcr_ticket_status
CHECK (ticket_status IN ('open','closed')),
CONSTRAINT chk_pcr_urgency
CHECK (urgency IN ('normal','urgent'))
);



CREATE TABLE pc_bill_submission (
submission_id INT NOT NULL IDENTITY(1,1),
ticket_id INT NOT NULL,
branch_id INT NOT NULL,
wallet_id INT NOT NULL,
raised_by INT NOT NULL,
category NVARCHAR(30) NOT NULL DEFAULT 'misc',
description NVARCHAR(300) NULL,
total_bill_amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
approved_amount DECIMAL(12,2) NULL,
accounts_status NVARCHAR(20) NOT NULL DEFAULT 'open',
reviewed_by INT NULL,
reviewed_at DATETIME2 NULL,
rejection_reason NVARCHAR(300) NULL,
accounts_remarks NVARCHAR(500) NULL,
ticket_status NVARCHAR(20) NOT NULL DEFAULT 'open',
wallet_balance_at_raise DECIMAL(12,2) NOT NULL DEFAULT 0.00,
closed_at DATETIME2 NULL,
created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
updated_at DATETIME2 NOT NULL DEFAULT GETDATE(),
CONSTRAINT pk_pc_bill PRIMARY KEY (submission_id),
CONSTRAINT uq_pc_bill_tkt UNIQUE (ticket_id),
CONSTRAINT fk_pcb_ticket
FOREIGN KEY (ticket_id) REFERENCES tickets (ticket_id),
CONSTRAINT fk_pcb_branch
FOREIGN KEY (branch_id) REFERENCES branch (branch_id),
CONSTRAINT fk_pcb_wallet
FOREIGN KEY (wallet_id) REFERENCES branch_wallet (wallet_id),
CONSTRAINT chk_pcb_category
CHECK (category IN ('maintenance','food','stationery','travel','fuel','utilities','courier','repair','misc')),
CONSTRAINT chk_pcb_acct_status
CHECK (accounts_status IN ('open','under_review','approved','rejected','on_hold')),
CONSTRAINT chk_pcb_ticket_status
CHECK (ticket_status IN ('open','closed'))
);


CREATE TABLE pc_bill_item (
item_id INT NOT NULL IDENTITY(1,1),
submission_id INT NOT NULL,
bill_description NVARCHAR(200) NULL,
bill_number NVARCHAR(50) NULL,
bill_date DATE NOT NULL,
amount DECIMAL(12,2) NOT NULL,
attachment_path NVARCHAR(500) NULL,
item_status NVARCHAR(20) NOT NULL DEFAULT 'pending',
created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
CONSTRAINT pk_pc_bill_item PRIMARY KEY (item_id),
CONSTRAINT fk_pcbi_submission
FOREIGN KEY (submission_id) REFERENCES pc_bill_submission (submission_id)
ON DELETE NO ACTION ON UPDATE NO ACTION,
CONSTRAINT chk_pcbi_status
CHECK (item_status IN ('pending','approved','rejected'))
);


CREATE TABLE wallet_transaction (
txn_id INT NOT NULL IDENTITY(1,1),
wallet_id INT NOT NULL,
branch_id INT NOT NULL,
direction NCHAR(1) NOT NULL,
source_type NVARCHAR(20) NOT NULL,
source_id INT NOT NULL,
amount DECIMAL(12,2) NOT NULL,
balance_before DECIMAL(12,2) NOT NULL,
balance_after DECIMAL(12,2) NOT NULL,
narration NVARCHAR(300) NULL,
created_by INT NOT NULL,
created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
CONSTRAINT pk_wallet_txn PRIMARY KEY (txn_id),
CONSTRAINT fk_wt_wallet
FOREIGN KEY (wallet_id) REFERENCES branch_wallet (wallet_id)
ON DELETE NO ACTION ON UPDATE NO ACTION,
CONSTRAINT fk_wt_branch
FOREIGN KEY (branch_id) REFERENCES branch (branch_id)
ON DELETE NO ACTION ON UPDATE NO ACTION,
CONSTRAINT chk_wt_direction
CHECK (direction IN ('C','D')),
CONSTRAINT chk_wt_source
CHECK (source_type IN ('pc_request','pc_bill_submission'))
);


-- 1. wallet_id nullable
ALTER TABLE pc_request ALTER COLUMN wallet_id INT NULL;

-- 2. Drop old constraints
ALTER TABLE pc_request DROP CONSTRAINT chk_pcr_acct_status;
ALTER TABLE pc_request DROP CONSTRAINT chk_pcr_mgmt_status;
ALTER TABLE pc_request DROP CONSTRAINT fk_pcr_ticket;

-- 3. Add updated constraints
ALTER TABLE pc_request ADD CONSTRAINT chk_pcr_acct_status
CHECK (accounts_status IN ('open','under_review','approved','transferred','rejected','on_hold','closed'));

ALTER TABLE pc_request ADD CONSTRAINT chk_pcr_mgmt_status
CHECK (mgmt_status IS NULL OR mgmt_status IN ('pending','approved','transferred','rejected'));

-- 4. Fix FK to issueTicket
ALTER TABLE pc_request ADD CONSTRAINT fk_pcr_ticket
FOREIGN KEY (ticket_id) REFERENCES issueTicket (ticketId)
ON DELETE NO ACTION ON UPDATE NO ACTION;


CREATE TABLE facility_issue_category (
    id          INT IDENTITY(1,1) PRIMARY KEY,
    name        NVARCHAR(255) NOT NULL,
    description NVARCHAR(500) NULL,
    status      TINYINT NOT NULL DEFAULT 1,
    created_at  DATETIME2 NULL,
    updated_at  DATETIME2 NULL
);
