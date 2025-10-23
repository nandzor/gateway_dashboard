# Database Schema Documentation

## Overview
This document provides comprehensive documentation for the database schema of the Gateway Dashboard application, including table structures, relationships, indexes, and constraints.

## Database Information
- **Database Type**: PostgreSQL
- **Version**: 13.0+
- **Port**: 5433
- **Database Name**: gateway
- **Character Set**: UTF-8
- **Collation**: en_US.UTF-8

## Table of Contents
1. [Core Tables](#core-tables)
2. [Relationship Tables](#relationship-tables)
3. [Transaction Tables](#transaction-tables)
4. [Pricing Tables](#pricing-tables)
5. [System Tables](#system-tables)
6. [Indexes](#indexes)
7. [Constraints](#constraints)
8. [Triggers](#triggers)
9. [Views](#views)
10. [Sequences](#sequences)

---

## Core Tables

### users
User authentication and authorization table.

```sql
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'viewer',
    is_active INTEGER NOT NULL DEFAULT 1,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

**Columns:**
- `id`: Primary key (auto-increment)
- `username`: Unique username for login
- `name`: Display name
- `email`: Email address (unique)
- `email_verified_at`: Email verification timestamp
- `password`: Hashed password
- `role`: User role (admin, operator, viewer)
- `is_active`: Active status (1=active, 0=inactive)
- `remember_token`: Remember me token
- `created_at`: Creation timestamp
- `updated_at`: Last update timestamp

**Indexes:**
- Primary key on `id`
- Unique index on `username`
- Unique index on `email`
- Index on `role`
- Index on `is_active`

### clients
Client information and credentials table.

```sql
CREATE TABLE clients (
    id BIGSERIAL PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    address TEXT NULL,
    contact VARCHAR(255) NULL,
    type INTEGER NOT NULL DEFAULT 1,
    ak VARCHAR(255) NOT NULL,
    sk VARCHAR(255) NOT NULL,
    avkey_iv VARCHAR(255) NULL,
    avkey_pass VARCHAR(255) NULL,
    service_module BIGINT NULL,
    is_active INTEGER NOT NULL DEFAULT 1,
    service_allow JSON NULL,
    white_list JSON NULL,
    module_40 JSON NULL,
    is_staging INTEGER NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (service_module) REFERENCES services(id)
);
```

**Columns:**
- `id`: Primary key (auto-increment)
- `client_name`: Client display name
- `address`: Client address
- `contact`: Contact information
- `type`: Client type (1=prepaid, 2=postpaid)
- `ak`: API Key
- `sk`: Secret Key
- `avkey_iv`: AVKey IV for encryption
- `avkey_pass`: AVKey password for encryption
- `service_module`: Default service module (FK to services)
- `is_active`: Active status (1=active, 0=inactive)
- `service_allow`: JSON array of allowed service IDs
- `white_list`: JSON array of whitelisted IP addresses
- `module_40`: JSON configuration for module 40
- `is_staging`: Staging status (1=staging, 0=production)
- `created_at`: Creation timestamp
- `updated_at`: Last update timestamp

**Indexes:**
- Primary key on `id`
- Index on `type`
- Index on `is_active`
- Index on `is_staging`
- Foreign key index on `service_module`

### services
Available services table.

```sql
CREATE TABLE services (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type INTEGER NOT NULL DEFAULT 1,
    is_active INTEGER NOT NULL DEFAULT 1,
    is_alert_zero INTEGER NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

**Columns:**
- `id`: Primary key (auto-increment)
- `name`: Service name
- `type`: Service type (1=internal, 2=external)
- `is_active`: Active status (1=active, 0=inactive)
- `is_alert_zero`: Zero balance alert flag (1=alert, 0=no alert)
- `created_at`: Creation timestamp
- `updated_at`: Last update timestamp

**Indexes:**
- Primary key on `id`
- Index on `type`
- Index on `is_active`

### currencies
Supported currencies table.

```sql
CREATE TABLE currencies (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(255) UNIQUE NOT NULL,
    symbol VARCHAR(255) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

**Columns:**
- `id`: Primary key (auto-increment)
- `name`: Currency name
- `code`: Currency code (e.g., USD, EUR, IDR)
- `symbol`: Currency symbol (e.g., $, €, Rp)
- `is_active`: Active status (true=active, false=inactive)
- `created_at`: Creation timestamp
- `updated_at`: Last update timestamp

**Indexes:**
- Primary key on `id`
- Unique index on `code`
- Index on `is_active`

---

## Relationship Tables

### service_assigns
Client-service many-to-many relationship table.

```sql
CREATE TABLE service_assigns (
    id BIGSERIAL PRIMARY KEY,
    client_id BIGINT NOT NULL,
    service_id BIGINT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    UNIQUE(client_id, service_id)
);
```

**Columns:**
- `id`: Primary key (auto-increment)
- `client_id`: Foreign key to clients table
- `service_id`: Foreign key to services table
- `created_at`: Creation timestamp
- `updated_at`: Last update timestamp

**Indexes:**
- Primary key on `id`
- Foreign key index on `client_id`
- Foreign key index on `service_id`
- Unique constraint on `(client_id, service_id)`

---

## Transaction Tables

### balances
Client balance tracking table.

```sql
CREATE TABLE balances (
    id BIGSERIAL PRIMARY KEY,
    client_id BIGINT NOT NULL,
    balance DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    quota DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);
```

**Columns:**
- `id`: Primary key (auto-increment)
- `client_id`: Foreign key to clients table
- `balance`: Current balance amount
- `quota`: Available quota amount
- `created_at`: Creation timestamp
- `updated_at`: Last update timestamp

**Indexes:**
- Primary key on `id`
- Foreign key index on `client_id`
- Index on `balance`

### balance_topups
Balance top-up transactions table.

```sql
CREATE TABLE balance_topups (
    id BIGSERIAL PRIMARY KEY,
    client_id BIGINT NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    payment_method VARCHAR(100) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    reference_number VARCHAR(255) NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);
```

**Columns:**
- `id`: Primary key (auto-increment)
- `client_id`: Foreign key to clients table
- `amount`: Top-up amount
- `payment_method`: Payment method used
- `status`: Transaction status (approved, pending, cancelled, rejected)
- `reference_number`: Reference number for tracking
- `notes`: Additional notes
- `created_at`: Creation timestamp
- `updated_at`: Last update timestamp

**Indexes:**
- Primary key on `id`
- Foreign key index on `client_id`
- Index on `status`
- Index on `payment_method`
- Index on `reference_number`

### histories
Transaction history and logs table.

```sql
CREATE TABLE histories (
    id BIGSERIAL PRIMARY KEY,
    client_id BIGINT NOT NULL,
    module_id BIGINT NOT NULL,
    trx_date TIMESTAMP NOT NULL,
    price DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    status VARCHAR(50) NOT NULL DEFAULT 'OK',
    charge VARCHAR(50) NOT NULL DEFAULT 'prepaid',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (module_id) REFERENCES services(id) ON DELETE CASCADE
);
```

**Columns:**
- `id`: Primary key (auto-increment)
- `client_id`: Foreign key to clients table
- `module_id`: Foreign key to services table
- `trx_date`: Transaction date and time
- `price`: Transaction price
- `status`: Transaction status (OK, FAIL, INVALID_REQUEST)
- `charge`: Charge type (prepaid, postpaid)
- `created_at`: Creation timestamp
- `updated_at`: Last update timestamp

**Indexes:**
- Primary key on `id`
- Foreign key index on `client_id`
- Foreign key index on `module_id`
- Index on `trx_date`
- Index on `status`
- Index on `charge`

---

## Pricing Tables

### price_masters
Default pricing for services table.

```sql
CREATE TABLE price_masters (
    id BIGSERIAL PRIMARY KEY,
    module_id BIGINT NOT NULL,
    price_default DECIMAL(15,3) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    note TEXT NULL,
    currency_id BIGINT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (module_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (currency_id) REFERENCES currencies(id) ON DELETE CASCADE
);
```

**Columns:**
- `id`: Primary key (auto-increment)
- `module_id`: Foreign key to services table
- `price_default`: Default price amount
- `is_active`: Active status (true=active, false=inactive)
- `note`: Additional notes about pricing
- `currency_id`: Foreign key to currencies table
- `created_at`: Creation timestamp
- `updated_at`: Last update timestamp

**Indexes:**
- Primary key on `id`
- Foreign key index on `module_id`
- Foreign key index on `currency_id`
- Index on `is_active`

### price_customs
Custom pricing per client table.

```sql
CREATE TABLE price_customs (
    id BIGSERIAL PRIMARY KEY,
    module_id BIGINT NOT NULL,
    client_id BIGINT NOT NULL,
    price_custom DECIMAL(15,3) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    currency_id BIGINT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (module_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (currency_id) REFERENCES currencies(id) ON DELETE CASCADE,
    UNIQUE(module_id, client_id, currency_id)
);
```

**Columns:**
- `id`: Primary key (auto-increment)
- `module_id`: Foreign key to services table
- `client_id`: Foreign key to clients table
- `price_custom`: Custom price amount
- `is_active`: Active status (true=active, false=inactive)
- `currency_id`: Foreign key to currencies table
- `created_at`: Creation timestamp
- `updated_at`: Last update timestamp

**Indexes:**
- Primary key on `id`
- Foreign key index on `module_id`
- Foreign key index on `client_id`
- Foreign key index on `currency_id`
- Index on `is_active`
- Unique constraint on `(module_id, client_id, currency_id)`

---

## System Tables

### menus
Navigation menu configuration table.

```sql
CREATE TABLE menus (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    icon VARCHAR(255) NULL,
    parent_id BIGINT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0,
    is_active INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (parent_id) REFERENCES menus(id) ON DELETE CASCADE
);
```

**Columns:**
- `id`: Primary key (auto-increment)
- `name`: Menu display name
- `url`: Menu URL
- `icon`: Menu icon class
- `parent_id`: Parent menu ID (for submenus)
- `sort_order`: Display order
- `is_active`: Active status (1=active, 0=inactive)
- `created_at`: Creation timestamp
- `updated_at`: Last update timestamp

**Indexes:**
- Primary key on `id`
- Foreign key index on `parent_id`
- Index on `sort_order`
- Index on `is_active`

### whitelist_ip_api
IP whitelist for API access table.

```sql
CREATE TABLE whitelist_ip_api (
    id BIGSERIAL PRIMARY KEY,
    client_id BIGINT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    description VARCHAR(255) NULL,
    is_active INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);
```

**Columns:**
- `id`: Primary key (auto-increment)
- `client_id`: Foreign key to clients table
- `ip_address`: Whitelisted IP address
- `description`: IP description
- `is_active`: Active status (1=active, 0=inactive)
- `created_at`: Creation timestamp
- `updated_at`: Last update timestamp

**Indexes:**
- Primary key on `id`
- Foreign key index on `client_id`
- Index on `ip_address`
- Index on `is_active`

---

## Indexes

### Performance Indexes
```sql
-- Users table indexes
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_is_active ON users(is_active);
CREATE INDEX idx_users_email ON users(email);

-- Clients table indexes
CREATE INDEX idx_clients_type ON clients(type);
CREATE INDEX idx_clients_is_active ON clients(is_active);
CREATE INDEX idx_clients_is_staging ON clients(is_staging);
CREATE INDEX idx_clients_service_module ON clients(service_module);

-- Services table indexes
CREATE INDEX idx_services_type ON services(type);
CREATE INDEX idx_services_is_active ON services(is_active);

-- Currencies table indexes
CREATE INDEX idx_currencies_code ON currencies(code);
CREATE INDEX idx_currencies_is_active ON currencies(is_active);

-- Balance table indexes
CREATE INDEX idx_balances_client_id ON balances(client_id);
CREATE INDEX idx_balances_balance ON balances(balance);

-- Balance topups table indexes
CREATE INDEX idx_balance_topups_client_id ON balance_topups(client_id);
CREATE INDEX idx_balance_topups_status ON balance_topups(status);
CREATE INDEX idx_balance_topups_payment_method ON balance_topups(payment_method);
CREATE INDEX idx_balance_topups_reference_number ON balance_topups(reference_number);

-- Histories table indexes
CREATE INDEX idx_histories_client_id ON histories(client_id);
CREATE INDEX idx_histories_module_id ON histories(module_id);
CREATE INDEX idx_histories_trx_date ON histories(trx_date);
CREATE INDEX idx_histories_status ON histories(status);
CREATE INDEX idx_histories_charge ON histories(charge);

-- Price masters table indexes
CREATE INDEX idx_price_masters_module_id ON price_masters(module_id);
CREATE INDEX idx_price_masters_currency_id ON price_masters(currency_id);
CREATE INDEX idx_price_masters_is_active ON price_masters(is_active);

-- Price customs table indexes
CREATE INDEX idx_price_customs_module_id ON price_customs(module_id);
CREATE INDEX idx_price_customs_client_id ON price_customs(client_id);
CREATE INDEX idx_price_customs_currency_id ON price_customs(currency_id);
CREATE INDEX idx_price_customs_is_active ON price_customs(is_active);

-- Service assigns table indexes
CREATE INDEX idx_service_assigns_client_id ON service_assigns(client_id);
CREATE INDEX idx_service_assigns_service_id ON service_assigns(service_id);

-- Menus table indexes
CREATE INDEX idx_menus_parent_id ON menus(parent_id);
CREATE INDEX idx_menus_sort_order ON menus(sort_order);
CREATE INDEX idx_menus_is_active ON menus(is_active);

-- Whitelist IP API table indexes
CREATE INDEX idx_whitelist_ip_api_client_id ON whitelist_ip_api(client_id);
CREATE INDEX idx_whitelist_ip_api_ip_address ON whitelist_ip_api(ip_address);
CREATE INDEX idx_whitelist_ip_api_is_active ON whitelist_ip_api(is_active);
```

### Composite Indexes
```sql
-- Composite indexes for common queries
CREATE INDEX idx_histories_client_date ON histories(client_id, trx_date);
CREATE INDEX idx_histories_module_date ON histories(module_id, trx_date);
CREATE INDEX idx_histories_status_date ON histories(status, trx_date);
CREATE INDEX idx_balance_topups_client_status ON balance_topups(client_id, status);
CREATE INDEX idx_price_customs_module_client ON price_customs(module_id, client_id);
```

---

## Constraints

### Primary Key Constraints
All tables have a primary key constraint on the `id` column:
```sql
ALTER TABLE table_name ADD CONSTRAINT pk_table_name PRIMARY KEY (id);
```

### Foreign Key Constraints
```sql
-- Clients table foreign keys
ALTER TABLE clients ADD CONSTRAINT fk_clients_service_module 
    FOREIGN KEY (service_module) REFERENCES services(id);

-- Service assigns table foreign keys
ALTER TABLE service_assigns ADD CONSTRAINT fk_service_assigns_client_id 
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE;
ALTER TABLE service_assigns ADD CONSTRAINT fk_service_assigns_service_id 
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE;

-- Balances table foreign keys
ALTER TABLE balances ADD CONSTRAINT fk_balances_client_id 
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE;

-- Balance topups table foreign keys
ALTER TABLE balance_topups ADD CONSTRAINT fk_balance_topups_client_id 
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE;

-- Histories table foreign keys
ALTER TABLE histories ADD CONSTRAINT fk_histories_client_id 
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE;
ALTER TABLE histories ADD CONSTRAINT fk_histories_module_id 
    FOREIGN KEY (module_id) REFERENCES services(id) ON DELETE CASCADE;

-- Price masters table foreign keys
ALTER TABLE price_masters ADD CONSTRAINT fk_price_masters_module_id 
    FOREIGN KEY (module_id) REFERENCES services(id) ON DELETE CASCADE;
ALTER TABLE price_masters ADD CONSTRAINT fk_price_masters_currency_id 
    FOREIGN KEY (currency_id) REFERENCES currencies(id) ON DELETE CASCADE;

-- Price customs table foreign keys
ALTER TABLE price_customs ADD CONSTRAINT fk_price_customs_module_id 
    FOREIGN KEY (module_id) REFERENCES services(id) ON DELETE CASCADE;
ALTER TABLE price_customs ADD CONSTRAINT fk_price_customs_client_id 
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE;
ALTER TABLE price_customs ADD CONSTRAINT fk_price_customs_currency_id 
    FOREIGN KEY (currency_id) REFERENCES currencies(id) ON DELETE CASCADE;

-- Menus table foreign keys
ALTER TABLE menus ADD CONSTRAINT fk_menus_parent_id 
    FOREIGN KEY (parent_id) REFERENCES menus(id) ON DELETE CASCADE;

-- Whitelist IP API table foreign keys
ALTER TABLE whitelist_ip_api ADD CONSTRAINT fk_whitelist_ip_api_client_id 
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE;
```

### Unique Constraints
```sql
-- Users table unique constraints
ALTER TABLE users ADD CONSTRAINT uk_users_username UNIQUE (username);
ALTER TABLE users ADD CONSTRAINT uk_users_email UNIQUE (email);

-- Currencies table unique constraints
ALTER TABLE currencies ADD CONSTRAINT uk_currencies_code UNIQUE (code);

-- Service assigns table unique constraints
ALTER TABLE service_assigns ADD CONSTRAINT uk_service_assigns_client_service 
    UNIQUE (client_id, service_id);

-- Price customs table unique constraints
ALTER TABLE price_customs ADD CONSTRAINT uk_price_customs_module_client_currency 
    UNIQUE (module_id, client_id, currency_id);
```

### Check Constraints
```sql
-- Users table check constraints
ALTER TABLE users ADD CONSTRAINT ck_users_role 
    CHECK (role IN ('admin', 'operator', 'viewer'));
ALTER TABLE users ADD CONSTRAINT ck_users_is_active 
    CHECK (is_active IN (0, 1));

-- Clients table check constraints
ALTER TABLE clients ADD CONSTRAINT ck_clients_type 
    CHECK (type IN (1, 2));
ALTER TABLE clients ADD CONSTRAINT ck_clients_is_active 
    CHECK (is_active IN (0, 1));
ALTER TABLE clients ADD CONSTRAINT ck_clients_is_staging 
    CHECK (is_staging IN (0, 1));

-- Services table check constraints
ALTER TABLE services ADD CONSTRAINT ck_services_type 
    CHECK (type IN (1, 2));
ALTER TABLE services ADD CONSTRAINT ck_services_is_active 
    CHECK (is_active IN (0, 1));
ALTER TABLE services ADD CONSTRAINT ck_services_is_alert_zero 
    CHECK (is_alert_zero IN (0, 1));

-- Balance topups table check constraints
ALTER TABLE balance_topups ADD CONSTRAINT ck_balance_topups_status 
    CHECK (status IN ('approved', 'pending', 'cancelled', 'rejected'));
ALTER TABLE balance_topups ADD CONSTRAINT ck_balance_topups_payment_method 
    CHECK (payment_method IN ('bank_transfer', 'credit_card', 'debit_card', 'ewallet', 'other'));

-- Histories table check constraints
ALTER TABLE histories ADD CONSTRAINT ck_histories_status 
    CHECK (status IN ('OK', 'FAIL', 'INVALID_REQUEST'));
ALTER TABLE histories ADD CONSTRAINT ck_histories_charge 
    CHECK (charge IN ('prepaid', 'postpaid'));

-- Menus table check constraints
ALTER TABLE menus ADD CONSTRAINT ck_menus_is_active 
    CHECK (is_active IN (0, 1));

-- Whitelist IP API table check constraints
ALTER TABLE whitelist_ip_api ADD CONSTRAINT ck_whitelist_ip_api_is_active 
    CHECK (is_active IN (0, 1));
```

---

## Triggers

### Audit Triggers
```sql
-- Create audit log table
CREATE TABLE audit_logs (
    id BIGSERIAL PRIMARY KEY,
    table_name VARCHAR(255) NOT NULL,
    record_id BIGINT NOT NULL,
    action VARCHAR(10) NOT NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    user_id BIGINT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create audit trigger function
CREATE OR REPLACE FUNCTION audit_trigger_function()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'DELETE' THEN
        INSERT INTO audit_logs (table_name, record_id, action, old_values, user_id)
        VALUES (TG_TABLE_NAME, OLD.id, TG_OP, row_to_json(OLD), current_setting('app.user_id', true)::bigint);
        RETURN OLD;
    ELSIF TG_OP = 'UPDATE' THEN
        INSERT INTO audit_logs (table_name, record_id, action, old_values, new_values, user_id)
        VALUES (TG_TABLE_NAME, NEW.id, TG_OP, row_to_json(OLD), row_to_json(NEW), current_setting('app.user_id', true)::bigint);
        RETURN NEW;
    ELSIF TG_OP = 'INSERT' THEN
        INSERT INTO audit_logs (table_name, record_id, action, new_values, user_id)
        VALUES (TG_TABLE_NAME, NEW.id, TG_OP, row_to_json(NEW), current_setting('app.user_id', true)::bigint);
        RETURN NEW;
    END IF;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

-- Create audit triggers for critical tables
CREATE TRIGGER audit_users AFTER INSERT OR UPDATE OR DELETE ON users
    FOR EACH ROW EXECUTE FUNCTION audit_trigger_function();

CREATE TRIGGER audit_clients AFTER INSERT OR UPDATE OR DELETE ON clients
    FOR EACH ROW EXECUTE FUNCTION audit_trigger_function();

CREATE TRIGGER audit_services AFTER INSERT OR UPDATE OR DELETE ON services
    FOR EACH ROW EXECUTE FUNCTION audit_trigger_function();

CREATE TRIGGER audit_currencies AFTER INSERT OR UPDATE OR DELETE ON currencies
    FOR EACH ROW EXECUTE FUNCTION audit_trigger_function();

CREATE TRIGGER audit_price_masters AFTER INSERT OR UPDATE OR DELETE ON price_masters
    FOR EACH ROW EXECUTE FUNCTION audit_trigger_function();

CREATE TRIGGER audit_price_customs AFTER INSERT OR UPDATE OR DELETE ON price_customs
    FOR EACH ROW EXECUTE FUNCTION audit_trigger_function();

CREATE TRIGGER audit_balance_topups AFTER INSERT OR UPDATE OR DELETE ON balance_topups
    FOR EACH ROW EXECUTE FUNCTION audit_trigger_function();
```

### Auto Balance Creation Trigger
```sql
-- Create function to auto-create balance for new clients
CREATE OR REPLACE FUNCTION create_client_balance()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO balances (client_id, balance, quota, created_at, updated_at)
    VALUES (NEW.id, 0.00, 0.00, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create trigger for auto balance creation
CREATE TRIGGER trigger_create_client_balance
    AFTER INSERT ON clients
    FOR EACH ROW
    EXECUTE FUNCTION create_client_balance();
```

---

## Views

### Active Records View
```sql
-- View for active users
CREATE VIEW v_active_users AS
SELECT id, username, name, email, role, created_at, updated_at
FROM users
WHERE is_active = 1;

-- View for active clients
CREATE VIEW v_active_clients AS
SELECT id, client_name, address, contact, type, is_staging, created_at, updated_at
FROM clients
WHERE is_active = 1;

-- View for active services
CREATE VIEW v_active_services AS
SELECT id, name, type, is_alert_zero, created_at, updated_at
FROM services
WHERE is_active = 1;

-- View for active currencies
CREATE VIEW v_active_currencies AS
SELECT id, name, code, symbol, created_at, updated_at
FROM currencies
WHERE is_active = TRUE;
```

### Client Service Assignment View
```sql
-- View for client service assignments with details
CREATE VIEW v_client_service_assignments AS
SELECT 
    sa.id,
    sa.client_id,
    c.client_name,
    c.type as client_type,
    sa.service_id,
    s.name as service_name,
    s.type as service_type,
    sa.created_at,
    sa.updated_at
FROM service_assigns sa
JOIN clients c ON sa.client_id = c.id
JOIN services s ON sa.service_id = s.id
WHERE c.is_active = 1 AND s.is_active = 1;
```

### Pricing Summary View
```sql
-- View for pricing summary
CREATE VIEW v_pricing_summary AS
SELECT 
    s.id as service_id,
    s.name as service_name,
    s.type as service_type,
    c.id as currency_id,
    c.name as currency_name,
    c.symbol as currency_symbol,
    pm.price_default as master_price,
    pm.is_active as master_active,
    COUNT(pc.id) as custom_prices_count,
    AVG(pc.price_custom) as avg_custom_price,
    MIN(pc.price_custom) as min_custom_price,
    MAX(pc.price_custom) as max_custom_price
FROM services s
CROSS JOIN currencies c
LEFT JOIN price_masters pm ON s.id = pm.module_id AND c.id = pm.currency_id
LEFT JOIN price_customs pc ON s.id = pc.module_id AND c.id = pc.currency_id
WHERE s.is_active = 1 AND c.is_active = TRUE
GROUP BY s.id, s.name, s.type, c.id, c.name, c.symbol, pm.price_default, pm.is_active;
```

### Transaction Summary View
```sql
-- View for transaction summary
CREATE VIEW v_transaction_summary AS
SELECT 
    DATE(trx_date) as transaction_date,
    client_id,
    c.client_name,
    module_id,
    s.name as service_name,
    COUNT(*) as transaction_count,
    SUM(price) as total_revenue,
    SUM(CASE WHEN status = 'OK' THEN 1 ELSE 0 END) as success_count,
    SUM(CASE WHEN status = 'FAIL' THEN 1 ELSE 0 END) as fail_count,
    SUM(CASE WHEN status = 'INVALID_REQUEST' THEN 1 ELSE 0 END) as invalid_count,
    ROUND(
        (SUM(CASE WHEN status = 'OK' THEN 1 ELSE 0 END)::DECIMAL / COUNT(*)) * 100, 
        2
    ) as success_rate
FROM histories h
JOIN clients c ON h.client_id = c.id
JOIN services s ON h.module_id = s.id
WHERE c.is_active = 1 AND s.is_active = 1
GROUP BY DATE(trx_date), client_id, c.client_name, module_id, s.name;
```

---

## Sequences

### Auto-increment Sequences
All tables use `BIGSERIAL` which automatically creates sequences:
```sql
-- Users sequence
CREATE SEQUENCE users_id_seq;

-- Clients sequence
CREATE SEQUENCE clients_id_seq;

-- Services sequence
CREATE SEQUENCE services_id_seq;

-- Currencies sequence
CREATE SEQUENCE currencies_id_seq;

-- Balances sequence
CREATE SEQUENCE balances_id_seq;

-- Balance topups sequence
CREATE SEQUENCE balance_topups_id_seq;

-- Histories sequence
CREATE SEQUENCE histories_id_seq;

-- Price masters sequence
CREATE SEQUENCE price_masters_id_seq;

-- Price customs sequence
CREATE SEQUENCE price_customs_id_seq;

-- Service assigns sequence
CREATE SEQUENCE service_assigns_id_seq;

-- Menus sequence
CREATE SEQUENCE menus_id_seq;

-- Whitelist IP API sequence
CREATE SEQUENCE whitelist_ip_api_id_seq;

-- Audit logs sequence
CREATE SEQUENCE audit_logs_id_seq;
```

---

## Data Types

### Custom Data Types
```sql
-- User role enum type
CREATE TYPE user_role AS ENUM ('admin', 'operator', 'viewer');

-- Client type enum type
CREATE TYPE client_type AS ENUM ('prepaid', 'postpaid');

-- Service type enum type
CREATE TYPE service_type AS ENUM ('internal', 'external');

-- Transaction status enum type
CREATE TYPE transaction_status AS ENUM ('OK', 'FAIL', 'INVALID_REQUEST');

-- Charge type enum type
CREATE TYPE charge_type AS ENUM ('prepaid', 'postpaid');

-- Balance topup status enum type
CREATE TYPE topup_status AS ENUM ('approved', 'pending', 'cancelled', 'rejected');

-- Payment method enum type
CREATE TYPE payment_method AS ENUM ('bank_transfer', 'credit_card', 'debit_card', 'ewallet', 'other');
```

---

## Performance Optimization

### Query Optimization
- **Indexes**: Comprehensive indexing strategy for all frequently queried columns
- **Composite Indexes**: Multi-column indexes for complex queries
- **Partial Indexes**: Indexes on filtered data (e.g., active records only)
- **Covering Indexes**: Indexes that include all required columns

### Partitioning
```sql
-- Partition histories table by date (monthly partitions)
CREATE TABLE histories (
    id BIGSERIAL,
    client_id BIGINT NOT NULL,
    module_id BIGINT NOT NULL,
    trx_date TIMESTAMP NOT NULL,
    price DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    status VARCHAR(50) NOT NULL DEFAULT 'OK',
    charge VARCHAR(50) NOT NULL DEFAULT 'prepaid',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id, trx_date)
) PARTITION BY RANGE (trx_date);

-- Create monthly partitions
CREATE TABLE histories_2024_01 PARTITION OF histories
    FOR VALUES FROM ('2024-01-01') TO ('2024-02-01');

CREATE TABLE histories_2024_02 PARTITION OF histories
    FOR VALUES FROM ('2024-02-01') TO ('2024-03-01');
```

### Maintenance
```sql
-- Update table statistics
ANALYZE;

-- Reindex tables
REINDEX TABLE histories;
REINDEX TABLE balance_topups;

-- Vacuum tables
VACUUM ANALYZE histories;
VACUUM ANALYZE balance_topups;
```

---

This comprehensive database schema documentation covers all tables, relationships, indexes, constraints, triggers, views, and performance optimizations in the Gateway Dashboard application.
