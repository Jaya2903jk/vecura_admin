CREATE TABLE iou_requests (

    iou_id BIGINT PRIMARY KEY AUTO_INCREMENT,

    ticket_id BIGINT NOT NULL,

    employee_id BIGINT NOT NULL,

    branch_id BIGINT NULL,

    request_date DATE,

    requested_amount DECIMAL(12,2) DEFAULT 0,

    approved_amount DECIMAL(12,2) DEFAULT 0,

    paid_amount DECIMAL(12,2) DEFAULT 0,

    settlement_amount DECIMAL(12,2) DEFAULT 0,

    pending_balance DECIMAL(12,2) DEFAULT 0,

    purpose TEXT NULL,

    status ENUM(

        'pending',
        'approved',
        'paid',
        'settlement_pending',
        'settled',
        'rejected'

    ) DEFAULT 'pending',

    approved_by BIGINT NULL,

    approved_at DATETIME NULL,

    paid_by BIGINT NULL,

    paid_at DATETIME NULL,

    settlement_date DATETIME NULL,

    remarks TEXT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE iou_settlements (

    settlement_id BIGINT PRIMARY KEY AUTO_INCREMENT,

    iou_id BIGINT NOT NULL,

    ticket_id BIGINT NOT NULL,

    employee_id BIGINT NOT NULL,

    settlement_date DATE,

    actual_expense DECIMAL(12,2) DEFAULT 0,

    returned_amount DECIMAL(12,2) DEFAULT 0,

    extra_claim_amount DECIMAL(12,2) DEFAULT 0,

    remarks TEXT NULL,

    created_by BIGINT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE claim_requests (

    claim_id BIGINT PRIMARY KEY AUTO_INCREMENT,

    ticket_id BIGINT NOT NULL,

    iou_id BIGINT NULL,

    employee_id BIGINT NOT NULL,

    expense_date DATE,

    expense_type VARCHAR(255),

    expense_amount DECIMAL(12,2) DEFAULT 0,

    remarks TEXT NULL,

    status ENUM(
        'pending',
        'approved',
        'paid',
        'rejected'
    ) DEFAULT 'pending',

    approved_by BIGINT NULL,

    approved_at DATETIME NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE employee_balances (

    balance_id BIGINT PRIMARY KEY AUTO_INCREMENT,

    employee_id BIGINT NOT NULL,

    total_iou_amount DECIMAL(12,2) DEFAULT 0,

    total_settlement_amount DECIMAL(12,2) DEFAULT 0,

    total_claim_amount DECIMAL(12,2) DEFAULT 0,

    pending_balance DECIMAL(12,2) DEFAULT 0,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE money_transactions (

    transaction_id BIGINT PRIMARY KEY AUTO_INCREMENT,

    employee_id BIGINT NOT NULL,

    ticket_id BIGINT NULL,

    reference_id BIGINT NULL,

    type ENUM(

        'iou_request',
        'iou_approved',
        'iou_paid',
        'settlement',
        'claim',
        'refund'

    ),

    amount DECIMAL(12,2) DEFAULT 0,

    remarks TEXT NULL,

    created_by BIGINT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
