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
