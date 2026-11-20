-- RENTAL ENTERPRISE PLATFORM
-- Phase 1: foundation + property core
-- MySQL 8.x
-- Financial transactions are intentionally deferred to Phase 2.

CREATE DATABASE IF NOT EXISTS rental_enterprise
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;

USE rental_enterprise;

CREATE TABLE organizations (
    id BINARY(16) NOT NULL,
    code VARCHAR(64) NOT NULL,
    name VARCHAR(255) NOT NULL,
    legal_name VARCHAR(255) NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'active',
    default_currency CHAR(3) NOT NULL DEFAULT 'VND',
    timezone VARCHAR(64) NOT NULL DEFAULT 'Asia/Ho_Chi_Minh',
    locale VARCHAR(16) NOT NULL DEFAULT 'vi-VN',
    created_at DATETIME(6) NOT NULL,
    updated_at DATETIME(6) NOT NULL,
    deleted_at DATETIME(6) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_organizations_code (code),
    KEY idx_organizations_status (status)
) ENGINE=InnoDB;

CREATE TABLE users (
    id BINARY(16) NOT NULL,
    email VARCHAR(320) NOT NULL,
    password_hash VARCHAR(255) NULL,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(32) NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'active',
    email_verified_at DATETIME(6) NULL,
    last_login_at DATETIME(6) NULL,
    created_at DATETIME(6) NOT NULL,
    updated_at DATETIME(6) NOT NULL,
    deleted_at DATETIME(6) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email),
    KEY idx_users_status (status)
) ENGINE=InnoDB;

CREATE TABLE organization_users (
    id BINARY(16) NOT NULL,
    organization_id BINARY(16) NOT NULL,
    user_id BINARY(16) NOT NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'active',
    joined_at DATETIME(6) NOT NULL,
    created_at DATETIME(6) NOT NULL,
    updated_at DATETIME(6) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_org_users (organization_id, user_id),
    KEY idx_org_users_user (user_id),
    CONSTRAINT fk_org_users_org FOREIGN KEY (organization_id) REFERENCES organizations(id),
    CONSTRAINT fk_org_users_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE roles (
    id BINARY(16) NOT NULL,
    organization_id BINARY(16) NULL,
    code VARCHAR(64) NOT NULL,
    name VARCHAR(255) NOT NULL,
    is_system BOOLEAN NOT NULL DEFAULT FALSE,
    created_at DATETIME(6) NOT NULL,
    updated_at DATETIME(6) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_roles_org_code (organization_id, code),
    KEY idx_roles_org (organization_id),
    CONSTRAINT fk_roles_org FOREIGN KEY (organization_id) REFERENCES organizations(id)
) ENGINE=InnoDB;

CREATE TABLE organization_user_roles (
    organization_user_id BINARY(16) NOT NULL,
    role_id BINARY(16) NOT NULL,
    PRIMARY KEY (organization_user_id, role_id),
    CONSTRAINT fk_our_org_user FOREIGN KEY (organization_user_id) REFERENCES organization_users(id),
    CONSTRAINT fk_our_role FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB;

CREATE TABLE portfolios (
    id BINARY(16) NOT NULL,
    organization_id BINARY(16) NOT NULL,
    code VARCHAR(64) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'active',
    created_at DATETIME(6) NOT NULL,
    updated_at DATETIME(6) NOT NULL,
    deleted_at DATETIME(6) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_portfolios_org_code (organization_id, code),
    KEY idx_portfolios_org_status (organization_id, status),
    CONSTRAINT fk_portfolios_org FOREIGN KEY (organization_id) REFERENCES organizations(id)
) ENGINE=InnoDB;

CREATE TABLE properties (
    id BINARY(16) NOT NULL,
    organization_id BINARY(16) NOT NULL,
    portfolio_id BINARY(16) NULL,
    code VARCHAR(64) NOT NULL,
    name VARCHAR(255) NOT NULL,
    property_type VARCHAR(64) NOT NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'active',
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255) NULL,
    ward VARCHAR(128) NULL,
    district VARCHAR(128) NULL,
    province VARCHAR(128) NULL,
    country_code CHAR(2) NOT NULL DEFAULT 'VN',
    latitude DECIMAL(10,7) NULL,
    longitude DECIMAL(10,7) NULL,
    created_at DATETIME(6) NOT NULL,
    updated_at DATETIME(6) NOT NULL,
    deleted_at DATETIME(6) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_properties_org_code (organization_id, code),
    KEY idx_properties_org_portfolio (organization_id, portfolio_id),
    KEY idx_properties_org_status (organization_id, status),
    CONSTRAINT fk_properties_org FOREIGN KEY (organization_id) REFERENCES organizations(id),
    CONSTRAINT fk_properties_portfolio FOREIGN KEY (portfolio_id) REFERENCES portfolios(id)
) ENGINE=InnoDB;

CREATE TABLE sites (
    id BINARY(16) NOT NULL,
    organization_id BINARY(16) NOT NULL,
    property_id BINARY(16) NOT NULL,
    code VARCHAR(64) NOT NULL,
    name VARCHAR(255) NOT NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'active',
    created_at DATETIME(6) NOT NULL,
    updated_at DATETIME(6) NOT NULL,
    deleted_at DATETIME(6) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_sites_org_code (organization_id, code),
    KEY idx_sites_property (property_id),
    CONSTRAINT fk_sites_org FOREIGN KEY (organization_id) REFERENCES organizations(id),
    CONSTRAINT fk_sites_property FOREIGN KEY (property_id) REFERENCES properties(id)
) ENGINE=InnoDB;

CREATE TABLE buildings (
    id BINARY(16) NOT NULL,
    organization_id BINARY(16) NOT NULL,
    property_id BINARY(16) NOT NULL,
    site_id BINARY(16) NULL,
    code VARCHAR(64) NOT NULL,
    name VARCHAR(255) NOT NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'active',
    created_at DATETIME(6) NOT NULL,
    updated_at DATETIME(6) NOT NULL,
    deleted_at DATETIME(6) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_buildings_org_code (organization_id, code),
    KEY idx_buildings_property (property_id),
    KEY idx_buildings_site (site_id),
    CONSTRAINT fk_buildings_org FOREIGN KEY (organization_id) REFERENCES organizations(id),
    CONSTRAINT fk_buildings_property FOREIGN KEY (property_id) REFERENCES properties(id),
    CONSTRAINT fk_buildings_site FOREIGN KEY (site_id) REFERENCES sites(id)
) ENGINE=InnoDB;

CREATE TABLE floors (
    id BINARY(16) NOT NULL,
    organization_id BINARY(16) NOT NULL,
    building_id BINARY(16) NOT NULL,
    floor_number INT NOT NULL,
    name VARCHAR(255) NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'active',
    created_at DATETIME(6) NOT NULL,
    updated_at DATETIME(6) NOT NULL,
    deleted_at DATETIME(6) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_floors_building_number (building_id, floor_number),
    KEY idx_floors_org_building (organization_id, building_id),
    CONSTRAINT fk_floors_org FOREIGN KEY (organization_id) REFERENCES organizations(id),
    CONSTRAINT fk_floors_building FOREIGN KEY (building_id) REFERENCES buildings(id)
) ENGINE=InnoDB;

CREATE TABLE unit_types (
    id BINARY(16) NOT NULL,
    organization_id BINARY(16) NOT NULL,
    code VARCHAR(64) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    area_sqm DECIMAL(12,2) NULL,
    max_occupants INT NULL,
    base_rent DECIMAL(19,4) NULL,
    currency CHAR(3) NOT NULL DEFAULT 'VND',
    status VARCHAR(32) NOT NULL DEFAULT 'active',
    created_at DATETIME(6) NOT NULL,
    updated_at DATETIME(6) NOT NULL,
    deleted_at DATETIME(6) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_unit_types_org_code (organization_id, code),
    CONSTRAINT fk_unit_types_org FOREIGN KEY (organization_id) REFERENCES organizations(id)
) ENGINE=InnoDB;

CREATE TABLE units (
    id BINARY(16) NOT NULL,
    organization_id BINARY(16) NOT NULL,
    property_id BINARY(16) NOT NULL,
    building_id BINARY(16) NULL,
    floor_id BINARY(16) NULL,
    unit_type_id BINARY(16) NULL,
    code VARCHAR(64) NOT NULL,
    name VARCHAR(255) NOT NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'available',
    area_sqm DECIMAL(12,2) NULL,
    base_rent DECIMAL(19,4) NULL,
    currency CHAR(3) NOT NULL DEFAULT 'VND',
    created_at DATETIME(6) NOT NULL,
    updated_at DATETIME(6) NOT NULL,
    deleted_at DATETIME(6) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_units_org_code (organization_id, code),
    KEY idx_units_property_status (organization_id, property_id, status),
    KEY idx_units_building_floor (building_id, floor_id),
    CONSTRAINT fk_units_org FOREIGN KEY (organization_id) REFERENCES organizations(id),
    CONSTRAINT fk_units_property FOREIGN KEY (property_id) REFERENCES properties(id),
    CONSTRAINT fk_units_building FOREIGN KEY (building_id) REFERENCES buildings(id),
    CONSTRAINT fk_units_floor FOREIGN KEY (floor_id) REFERENCES floors(id),
    CONSTRAINT fk_units_type FOREIGN KEY (unit_type_id) REFERENCES unit_types(id)
) ENGINE=InnoDB;

CREATE TABLE tenants (
    id BINARY(16) NOT NULL,
    organization_id BINARY(16) NOT NULL,
    tenant_code VARCHAR(64) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(32) NULL,
    email VARCHAR(320) NULL,
    date_of_birth DATE NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'active',
    created_at DATETIME(6) NOT NULL,
    updated_at DATETIME(6) NOT NULL,
    deleted_at DATETIME(6) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_tenants_org_code (organization_id, tenant_code),
    KEY idx_tenants_org_status (organization_id, status),
    KEY idx_tenants_org_phone (organization_id, phone),
    CONSTRAINT fk_tenants_org FOREIGN KEY (organization_id) REFERENCES organizations(id)
) ENGINE=InnoDB;

CREATE TABLE leases (
    id BINARY(16) NOT NULL,
    organization_id BINARY(16) NOT NULL,
    lease_number VARCHAR(64) NOT NULL,
    unit_id BINARY(16) NOT NULL,
    tenant_id BINARY(16) NOT NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'draft',
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    monthly_rent DECIMAL(19,4) NOT NULL,
    deposit_amount DECIMAL(19,4) NOT NULL DEFAULT 0,
    currency CHAR(3) NOT NULL DEFAULT 'VND',
    signed_at DATETIME(6) NULL,
    terminated_at DATETIME(6) NULL,
    termination_reason VARCHAR(500) NULL,
    created_at DATETIME(6) NOT NULL,
    updated_at DATETIME(6) NOT NULL,
    deleted_at DATETIME(6) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_leases_org_number (organization_id, lease_number),
    KEY idx_leases_unit_dates (unit_id, start_date, end_date),
    KEY idx_leases_tenant_status (tenant_id, status),
    KEY idx_leases_org_status_dates (organization_id, status, start_date, end_date),
    CONSTRAINT fk_leases_org FOREIGN KEY (organization_id) REFERENCES organizations(id),
    CONSTRAINT fk_leases_unit FOREIGN KEY (unit_id) REFERENCES units(id),
    CONSTRAINT fk_leases_tenant FOREIGN KEY (tenant_id) REFERENCES tenants(id),
    CONSTRAINT chk_leases_dates CHECK (end_date >= start_date),
    CONSTRAINT chk_leases_rent CHECK (monthly_rent >= 0),
    CONSTRAINT chk_leases_deposit CHECK (deposit_amount >= 0)
) ENGINE=InnoDB;

CREATE TABLE audit_logs (
    id BINARY(16) NOT NULL,
    organization_id BINARY(16) NULL,
    actor_user_id BINARY(16) NULL,
    action VARCHAR(128) NOT NULL,
    entity_type VARCHAR(128) NOT NULL,
    entity_id BINARY(16) NULL,
    before_json JSON NULL,
    after_json JSON NULL,
    request_id VARCHAR(128) NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(1000) NULL,
    occurred_at DATETIME(6) NOT NULL,
    PRIMARY KEY (id),
    KEY idx_audit_org_time (organization_id, occurred_at),
    KEY idx_audit_entity (entity_type, entity_id),
    KEY idx_audit_actor_time (actor_user_id, occurred_at),
    KEY idx_audit_request (request_id),
    CONSTRAINT fk_audit_org FOREIGN KEY (organization_id) REFERENCES organizations(id),
    CONSTRAINT fk_audit_actor FOREIGN KEY (actor_user_id) REFERENCES users(id)
) ENGINE=InnoDB;
