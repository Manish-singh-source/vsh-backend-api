# VSH - Veena Smart Homes: Database Design & Implementation Guide

**Project:** Veena Smart Homes Property Management System  
**Building Structure:** 1-5 Wings (A-E) with Multiple Flats/Units  
**Document Version:** 1.0  
**Last Updated:** January 2026

---

## Table of Contents

1. [System Overview](#system-overview)
2. [Database Architecture](#database-architecture)
3. [Entity Relationships](#entity-relationships)
4. [Detailed Schema Documentation](#detailed-schema-documentation)
5. [Implementation Guidelines](#implementation-guidelines)
6. [API Endpoints Reference](#api-endpoints-reference)
7. [Security & Validation](#security--validation)

---

## System Overview

### Project Scope

**VSH** is a comprehensive property management and community engagement platform for residential complexes with the following capabilities:

- **User Management**: Multi-role system (Owner, Tenant, Admin, Staff, Super Admin)
- **Resident Directory**: Family members, tenant profiles, QR-based identification
- **Complaint Management**: Maintenance, security, utilities issue tracking
- **Event & Notice Management**: Community events, important notices, advertisements
- **Facility Management**: Equipment booking, service directory
- **Staff Management**: Task assignment, leave tracking
- **Security**: Visitor management, gate entry logs, gym access control
- **Communication**: Admin notifications, targeted messaging

### Building Structure

```
Veena Smart Homes
├── Wing A
│   ├── Flat 1
│   ├── Flat 2
│   └── ...
├── Wing B
├── Wing C
├── Wing D
└── Wing E
```

### User Roles & Permissions

| Role | Permissions | Primary Functions |
|------|-------------|-------------------|
| **Super Admin** | Full system access | User approval, staff management, billing |
| **Admin** | Community management | Notice posting, event creation, facility management |
| **Staff** | Operational tasks | Task completion, complaint resolution, visitor management |
| **Owner** | Flat/unit ownership | Complaint filing, family member addition, event participation |
| **Tenant** | Rental occupancy | Complaint filing, event participation, facility booking |

---

## Database Architecture

### Database Diagram Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                        CORE ENTITIES                             │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌──────────────┐                                                │
│  │    USERS     │◄─── Central user management                    │
│  │  (5 Roles)   │                                                │
│  └──────┬───────┘                                                │
│         │                                                         │
│    ┌────┼────────────────────────────────────────┐               │
│    │    │                                        │               │
│    ▼    ▼                                        ▼               │
│ ┌─────────────────┐    ┌──────────────┐    ┌──────────────┐    │
│ │ FAMILY_MEMBERS  │    │  COMPLAINTS  │    │  STAFF_TASKS │    │
│ │ (Residents)     │    │ (Maintenance)│    │ (Operations) │    │
│ └─────────────────┘    └──────────────┘    └──────────────┘    │
│                                                                   │
│  ┌──────────────────┐    ┌──────────────┐                       │
│  │ STAFF_LEAVE_RQST │    │  VISITORS    │                       │
│  │ (Leave mgmt)     │    │ (Security)   │                       │
│  └──────────────────┘    └──────────────┘                       │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                    COMMUNITY FEATURES                             │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│ ┌─────────────┐    ┌──────────┐    ┌────────────────┐           │
│ │    EVENTS   │    │ NOTICES  │    │ ADVERTISEMENTS │           │
│ │(Community)  │    │ (Inform) │    │  (Marketing)   │           │
│ └─────────────┘    └──────────┘    └────────────────┘           │
│                                                                   │
│ ┌──────────────────────────────────────────────────────┐        │
│ │  ADMIN_NOTIFICATIONS (Targeted Messaging)            │        │
│ └──────────────────────────────────────────────────────┘        │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                  FACILITIES & SERVICES                            │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│ ┌──────────────┐    ┌────────────┐    ┌──────────────┐          │
│ │  EQUIPMENTS  │    │  BOOKINGS  │    │  SERVICES    │          │
│ │(Gym, hall)   │◄───┤ (Booking)  │    │(Directory)   │          │
│ └──────────────┘    └────────────┘    └──────────────┘          │
│                                                                   │
│ ┌──────────────┐    ┌──────────────┐                            │
│ │  GYM_ENTRIES │    │ GATE_ENTRIES │                            │
│ │ (Access log) │    │ (Vehicle log)│                            │
│ └──────────────┘    └──────────────┘                            │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

---

## Entity Relationships

### Relationship Diagram (Text Format)

```
USERS (1) ──────────── (M) FAMILY_MEMBERS
           (owner_id)        │
                             └─ user_id [FK]
                             └─ approved_by [FK → users]

USERS (1) ──────────── (M) COMPLAINTS
           (user_id)        │
                            └─ user_id [FK]
                            └─ resolved_by [FK → users]

USERS (1) ──────────── (M) STAFF_TASKS
           (added_by)       │
           (assigned_to)    ├─ added_by [FK]
                            └─ assigned_to [FK]

USERS (1) ──────────── (M) STAFF_LEAVE_REQUESTS
           (staff_id)       │
           (approved_by)    ├─ staff_id [FK]
                            └─ approved_by [FK]

USERS (1) ──────────── (M) VISITORS
           (user_id)        │
           (staff_id)       ├─ user_id [FK]
                            └─ staff_id [FK]

USERS (1) ──────────── (M) EQUIPMENTS
           (added_by)       │
                            └─ added_by [FK]

EQUIPMENTS (1) ────────(M) BOOKINGS
               (equip_id)    │
                             ├─ equipment_id [FK]
                             ├─ user_id [FK → users]
                             └─ approved_by [FK → users]

USERS (1) ──────────── (M) EVENTS
           (added_by)       └─ added_by [FK]

USERS (1) ──────────── (M) NOTICES
           (added_by)       └─ added_by [FK]

USERS (1) ──────────── (M) ADVERTISEMENTS
           (added_by)       └─ added_by [FK]

USERS (1) ──────────── (M) SERVICES
           (added_by)       └─ added_by [FK]

USERS (1) ──────────── (M) ADMIN_NOTIFICATIONS
           (added_by)       └─ added_by [FK]

USERS (1) ──────────── (M) GYM_ENTRIES
           (user_id)        └─ user_id [FK]

USERS (1) ──────────── (M) GATE_ENTRIES
           (user_id)        │
           (staff_id)       ├─ user_id [FK]
                            └─ staff_id [FK]

USERS (1) ──────────── (1) USERS (Self-referencing)
           (owner_id)       └─ References parent owner
           (approved_by)    └─ References approving admin
```

### Cardinality Rules

| Relationship | Cardinality | Rule | Notes |
|-------------|------------|------|-------|
| User → Family Members | 1:M | One owner/tenant → Multiple family members | Cascading delete |
| User → Complaints | 1:M | Resident files complaints | Complaint tracks resolver |
| User → Tasks | 1:M | Staff gets task assignments | Tracks assigner & assignee |
| User → Leaves | 1:M | Staff requests leaves | Tracks requester & approver |
| User → Visitors | 1:M | Owner pre-registers visitors | Staff logs visits |
| Equipment → Bookings | 1:M | One equipment → Multiple bookings | Prevents conflicts |
| User → QR Codes | 1:1 | Unique identifier per person | For gate/gym access |
| Admin → Notifications | 1:M | Admin sends targeted messages | By role |

---

## Detailed Schema Documentation

### 1. USERS Table

**Purpose:** Central authentication and authorization system

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_code VARCHAR(255) UNIQUE NOT NULL,
    
    -- Basic Information
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    profile_image VARCHAR(255) NULLABLE,
    
    -- Role & Permissions
    role ENUM('owner', 'tenant', 'admin', 'staff', 'super_admin') 
         DEFAULT 'owner' NOT NULL,
    
    -- Residency Information
    wing_name VARCHAR(10) NULLABLE,      -- A, B, C, D, E
    flat_no VARCHAR(10) NULLABLE,        -- 101, 102, etc.
    
    -- Authentication
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULLABLE,
    
    -- Verification
    otp VARCHAR(6) NULLABLE,
    otp_expiry TIMESTAMP NULLABLE,
    is_verified BOOLEAN DEFAULT false,
    
    -- Identity
    qr_code_image LONGTEXT NULLABLE,
    
    -- Status Management
    status ENUM('active', 'inactive', 'blocked', 'suspended') 
           DEFAULT 'inactive' NOT NULL,
    is_tenant_added BOOLEAN DEFAULT false,
    
    -- Relationships
    owner_id BIGINT UNSIGNED NULLABLE,                -- For staff/tenant relations
    approved_by BIGINT UNSIGNED NULLABLE,             -- Approver reference
    approved_at TIMESTAMP NULLABLE,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,                    -- Soft delete
    
    -- Indexes
    INDEX idx_phone (phone),
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_wing_flat (wing_name, flat_no),
    INDEX idx_status (status),
    INDEX idx_owner_id (owner_id),
    INDEX idx_approved_by (approved_by),
    
    -- Foreign Keys
    CONSTRAINT fk_owner FOREIGN KEY (owner_id) 
        REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_approved_by FOREIGN KEY (approved_by) 
        REFERENCES users(id) ON DELETE SET NULL
);
```

**Key Fields:**
- `user_code`: Auto-generated unique identifier (format: USR-20260102-001)
- `qr_code_image`: Base64 encoded QR code for gate/gym access
- `wing_name`: A-E classification
- `is_verified`: Phone/email verification status
- `is_tenant_added`: Tenant profile completion flag

**Indexes:** 12 indexes for query optimization

---

### 2. FAMILY_MEMBERS Table

**Purpose:** Track family members and tenant occupants

```sql
CREATE TABLE family_members (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    
    -- Member Information
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    profile_image VARCHAR(255) NULLABLE,
    
    -- Relationship
    relation_with_user VARCHAR(100) NOT NULL,
    -- Values: spouse, child, parent, sibling, relative, guest
    
    -- Identity
    qr_code_image LONGTEXT NULLABLE,
    
    -- Approval Workflow
    status ENUM('active', 'inactive', 'blocked', 'suspended') 
           DEFAULT 'inactive' NOT NULL,
    approved_by BIGINT UNSIGNED NULLABLE,
    approved_at TIMESTAMP NULLABLE,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,
    
    -- Indexes & Constraints
    UNIQUE KEY uk_user_email (user_id, email),
    UNIQUE KEY uk_user_phone (user_id, phone),
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_approved_by (approved_by),
    
    CONSTRAINT fk_family_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_family_approved FOREIGN KEY (approved_by) 
        REFERENCES users(id) ON DELETE SET NULL
);
```

**Features:**
- Multiple family members per owner/tenant
- QR codes for access control
- Approval workflow for security
- Email & phone uniqueness per user

---

### 3. COMPLAINTS Table

**Purpose:** Issue tracking and resolution management

```sql
CREATE TABLE complaints (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    
    -- Complaint Details
    title VARCHAR(255) NOT NULL,
    description LONGTEXT NULLABLE,
    complaint_type ENUM(
        'maintenance', 'security', 'electrical', 
        'plumbing', 'common_area', 'amenities', 
        'parking', 'other'
    ) DEFAULT 'other' NOT NULL,
    
    -- Location
    flat_no VARCHAR(10) NULLABLE,
    
    -- Severity & Evidence
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium' NOT NULL,
    image LONGTEXT NULLABLE,
    
    -- Status Tracking
    status ENUM('pending', 'in_progress', 'resolved', 'reopened', 'cancelled') 
           DEFAULT 'pending' NOT NULL,
    
    -- Resolution
    resolved_by BIGINT UNSIGNED NULLABLE,
    resolved_at TIMESTAMP NULLABLE,
    resolution_notes LONGTEXT NULLABLE,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_priority (priority),
    INDEX idx_complaint_type (complaint_type),
    INDEX idx_flat_no (flat_no),
    INDEX idx_resolved_by (resolved_by),
    INDEX idx_created_at (created_at),
    
    CONSTRAINT fk_complaint_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_complaint_resolver FOREIGN KEY (resolved_by) 
        REFERENCES users(id) ON DELETE SET NULL
);
```

**Status Workflow:**
```
pending → in_progress → resolved
   ↑                       ↓
   └──────── reopened ─────┘
```

---

### 4. EVENTS Table

**Purpose:** Community event scheduling and promotion

```sql
CREATE TABLE events (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    
    -- Event Details
    title VARCHAR(255) NOT NULL,
    description LONGTEXT NULLABLE,
    venue VARCHAR(255) NOT NULL,
    
    -- Scheduling
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    
    -- Media & Classification
    image LONGTEXT NULLABLE,
    event_type ENUM('festival', 'meeting', 'activity', 'sport', 'other') 
               DEFAULT 'other' NOT NULL,
    
    -- Management
    status ENUM('active', 'inactive') DEFAULT 'inactive' NOT NULL,
    added_by BIGINT UNSIGNED NULLABLE,
    added_at TIMESTAMP NULLABLE,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,
    
    -- Indexes
    INDEX idx_event_type (event_type),
    INDEX idx_status (status),
    INDEX idx_start_date (start_date),
    INDEX idx_added_by (added_by),
    
    CONSTRAINT fk_event_creator FOREIGN KEY (added_by) 
        REFERENCES users(id) ON DELETE SET NULL
);
```

**Validation Rules:**
- `end_date >= start_date`
- `end_time > start_time` (same day)
- Maximum event duration: 365 days

---

### 5. NOTICES Table

**Purpose:** Important community announcements

```sql
CREATE TABLE notices (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    
    -- Notice Content
    title VARCHAR(255) NOT NULL,
    description LONGTEXT NOT NULL,
    image LONGTEXT NULLABLE,
    
    -- Classification
    notice_category ENUM('general', 'maintenance', 'event', 'other') 
                    DEFAULT 'other' NOT NULL,
    
    -- Scheduling
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    
    -- Visibility
    is_important BOOLEAN DEFAULT false,
    status ENUM('active', 'inactive') DEFAULT 'inactive' NOT NULL,
    
    -- Management
    added_by BIGINT UNSIGNED NULLABLE,
    added_at TIMESTAMP NULLABLE,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,
    
    -- Indexes
    INDEX idx_category (notice_category),
    INDEX idx_is_important (is_important),
    INDEX idx_status (status),
    INDEX idx_start_date (start_date),
    INDEX idx_added_by (added_by),
    
    CONSTRAINT fk_notice_creator FOREIGN KEY (added_by) 
        REFERENCES users(id) ON DELETE SET NULL
);
```

**Display Priority:**
- Important notices prioritized in UI
- Date-based filtering (active dates only)

---

### 6. ADVERTISEMENTS Table

**Purpose:** Marketing and promotional content

```sql
CREATE TABLE advertisements (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    
    -- Ad Content
    title VARCHAR(255) NOT NULL,
    description LONGTEXT NOT NULL,
    image LONGTEXT NULLABLE,
    redirect_url VARCHAR(2048) NULLABLE,
    
    -- Scheduling
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    
    -- Management
    is_important BOOLEAN DEFAULT false,
    status ENUM('active', 'inactive') DEFAULT 'inactive' NOT NULL,
    added_by BIGINT UNSIGNED NULLABLE,
    added_at TIMESTAMP NULLABLE,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,
    
    -- Indexes
    INDEX idx_status (status),
    INDEX idx_is_important (is_important),
    INDEX idx_start_date (start_date),
    INDEX idx_added_by (added_by),
    
    CONSTRAINT fk_ad_creator FOREIGN KEY (added_by) 
        REFERENCES users(id) ON DELETE SET NULL
);
```

---

### 7. SERVICES Table

**Purpose:** Service provider directory

```sql
CREATE TABLE services (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    
    -- Service Details
    name VARCHAR(255) NOT NULL,
    service_category ENUM(
        'parcel', 'vehicle', 'supermarket', 'grocery', 
        'garage', 'doctor', 'medical', 'other'
    ) DEFAULT 'other' NOT NULL,
    
    -- Contact & Hours
    phone VARCHAR(20) NULLABLE,
    opening_hours VARCHAR(255) NULLABLE,
    address LONGTEXT NULLABLE,
    
    -- Status
    status ENUM('active', 'inactive') DEFAULT 'inactive' NOT NULL,
    
    -- Management
    added_by BIGINT UNSIGNED NULLABLE,
    added_at TIMESTAMP NULLABLE,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,
    
    -- Indexes
    INDEX idx_category (service_category),
    INDEX idx_status (status),
    INDEX idx_added_by (added_by),
    
    CONSTRAINT fk_service_creator FOREIGN KEY (added_by) 
        REFERENCES users(id) ON DELETE SET NULL
);
```

**Example Services:**
- Parcel delivery points
- Parking & vehicle services
- Supermarket/grocery delivery
- Medical professionals
- Repair & maintenance

---

### 8. EQUIPMENTS Table

**Purpose:** Facility and amenity inventory

```sql
CREATE TABLE equipments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    
    -- Equipment Details
    name VARCHAR(255) NOT NULL,
    description LONGTEXT NULLABLE,
    image LONGTEXT NULLABLE,
    
    -- Location
    wing_name VARCHAR(10) NULLABLE,        -- A, B, C, D, E (optional)
    
    -- Booking
    is_bookable BOOLEAN DEFAULT false,
    
    -- Status Management
    status ENUM(
        'active', 'inactive', 'unavailable', 
        'damaged', 'under_maintenance'
    ) DEFAULT 'inactive' NOT NULL,
    
    -- Management
    added_by BIGINT UNSIGNED NULLABLE,
    added_at TIMESTAMP NULLABLE,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,
    
    -- Indexes
    INDEX idx_is_bookable (is_bookable),
    INDEX idx_status (status),
    INDEX idx_wing_name (wing_name),
    INDEX idx_added_by (added_by),
    
    CONSTRAINT fk_equipment_creator FOREIGN KEY (added_by) 
        REFERENCES users(id) ON DELETE SET NULL
);
```

**Examples:**
- Gym equipment
- Community halls
- Sports courts
- Common kitchens
- Parking spaces (reserved)

---

### 9. BOOKINGS Table

**Purpose:** Equipment reservation and allocation

```sql
CREATE TABLE bookings (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    equipment_id BIGINT UNSIGNED NOT NULL,
    
    -- Scheduling
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    
    -- Status & Approval
    status ENUM('pending', 'approved', 'rejected', 'cancelled') 
           DEFAULT 'pending' NOT NULL,
    approved_by BIGINT UNSIGNED NULLABLE,
    approved_at TIMESTAMP NULLABLE,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,
    
    -- Indexes (Prevent Double Booking)
    UNIQUE KEY uk_equipment_slot (
        equipment_id, start_date, end_date, start_time, end_time,
        status
    ),
    INDEX idx_user_id (user_id),
    INDEX idx_equipment_id (equipment_id),
    INDEX idx_status (status),
    INDEX idx_start_date (start_date),
    INDEX idx_approved_by (approved_by),
    
    CONSTRAINT fk_booking_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_booking_equipment FOREIGN KEY (equipment_id) 
        REFERENCES equipments(id) ON DELETE CASCADE,
    CONSTRAINT fk_booking_approver FOREIGN KEY (approved_by) 
        REFERENCES users(id) ON DELETE SET NULL
);
```

**Anti-Collision Logic:**
- Unique constraint prevents overlapping bookings
- Admin approval workflow
- Date/time validation

---

### 10. ADMIN_NOTIFICATIONS Table

**Purpose:** Targeted messaging system

```sql
CREATE TABLE admin_notifications (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    
    -- Message Content
    title VARCHAR(255) NOT NULL,
    message LONGTEXT NOT NULL,
    
    -- Recipients
    send_to ENUM(
        'all', 'owner', 'tenant', 'admin', 'staff', 'owners_and_staffs'
    ) DEFAULT 'all' NOT NULL,
    
    -- Status
    status ENUM('active', 'inactive') DEFAULT 'inactive' NOT NULL,
    
    -- Management
    added_by BIGINT UNSIGNED NULLABLE,
    added_at TIMESTAMP NULLABLE,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,
    
    -- Indexes
    INDEX idx_send_to (send_to),
    INDEX idx_status (status),
    INDEX idx_added_by (added_by),
    INDEX idx_created_at (created_at),
    
    CONSTRAINT fk_notification_creator FOREIGN KEY (added_by) 
        REFERENCES users(id) ON DELETE SET NULL
);
```

**Recipient Mapping:**
```
send_to = 'all'               → All users
send_to = 'owner'             → role = 'owner'
send_to = 'tenant'            → role = 'tenant'
send_to = 'admin'             → role = 'admin'
send_to = 'staff'             → role = 'staff'
send_to = 'owners_and_staffs' → role IN ('owner', 'staff', 'admin')
```

---

### 11. STAFF_TASKS Table

**Purpose:** Task management and assignment

```sql
CREATE TABLE staff_tasks (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    
    -- Task Details
    title VARCHAR(255) NOT NULL,
    description LONGTEXT NULLABLE,
    
    -- Scheduling
    due_at DATETIME NULLABLE,
    
    -- Priority & Status
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium' NOT NULL,
    status ENUM('pending', 'in_progress', 'completed', 'cancelled') 
           DEFAULT 'pending' NOT NULL,
    
    -- Assignment
    added_by BIGINT UNSIGNED NULLABLE,
    added_at TIMESTAMP NULLABLE,
    assigned_to BIGINT UNSIGNED NULLABLE,
    assigned_at TIMESTAMP NULLABLE,
    
    -- Completion
    completed_at TIMESTAMP NULLABLE,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,
    
    -- Indexes
    INDEX idx_assigned_to (assigned_to),
    INDEX idx_status (status),
    INDEX idx_priority (priority),
    INDEX idx_due_at (due_at),
    INDEX idx_added_by (added_by),
    
    CONSTRAINT fk_task_creator FOREIGN KEY (added_by) 
        REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_task_assignee FOREIGN KEY (assigned_to) 
        REFERENCES users(id) ON DELETE SET NULL
);
```

**Status Workflow:**
```
pending → in_progress → completed
    ↓
  cancelled
```

---

### 12. STAFF_LEAVE_REQUESTS Table

**Purpose:** Leave request and approval management

```sql
CREATE TABLE staff_leave_requests (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    staff_id BIGINT UNSIGNED NOT NULL,
    
    -- Leave Details
    leave_type ENUM(
        'sick', 'casual', 'paid', 'unpaid', 
        'emergency', 'annual', 'other'
    ) DEFAULT 'other' NOT NULL,
    
    -- Duration
    from_date DATE NOT NULL,
    to_date DATE NOT NULL,
    is_half_day BOOLEAN DEFAULT false,
    
    -- Reason
    reason LONGTEXT NULLABLE,
    
    -- Approval Workflow
    status ENUM('pending', 'approved', 'rejected') 
           DEFAULT 'pending' NOT NULL,
    approved_by BIGINT UNSIGNED NULLABLE,
    approved_at TIMESTAMP NULLABLE,
    rejection_reason LONGTEXT NULLABLE,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,
    
    -- Indexes
    INDEX idx_staff_id (staff_id),
    INDEX idx_status (status),
    INDEX idx_leave_type (leave_type),
    INDEX idx_from_date (from_date),
    INDEX idx_approved_by (approved_by),
    
    CONSTRAINT fk_leave_staff FOREIGN KEY (staff_id) 
        REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_leave_approver FOREIGN KEY (approved_by) 
        REFERENCES users(id) ON DELETE SET NULL
);
```

**Leave Types:**
- **Sick Leave**: Medical/health reasons
- **Casual Leave**: General absence
- **Paid Leave**: Compensated time off
- **Unpaid Leave**: Non-compensated
- **Emergency**: Urgent situations
- **Annual Leave**: Yearly vacation

---

### 13. VISITORS Table

**Purpose:** Visitor tracking and security management

```sql
CREATE TABLE visitors (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    staff_id BIGINT UNSIGNED NULLABLE,
    
    -- Visitor Information
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    
    -- Vehicle & Purpose
    vehicle_number VARCHAR(20) NULLABLE,
    purpose VARCHAR(255) NULLABLE,
    
    -- Visit Tracking
    visit_date DATE NOT NULL,
    check_in_at TIMESTAMP NULLABLE,
    check_out_at TIMESTAMP NULLABLE,
    
    -- Status
    status ENUM('expected', 'checked_in', 'checked_out', 'denied') 
           DEFAULT 'expected' NOT NULL,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_staff_id (staff_id),
    INDEX idx_phone (phone),
    INDEX idx_visit_date (visit_date),
    INDEX idx_status (status),
    
    CONSTRAINT fk_visitor_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_visitor_staff FOREIGN KEY (staff_id) 
        REFERENCES users(id) ON DELETE SET NULL
);
```

**Visitor Status Flow:**
```
expected → checked_in → checked_out
   ↓
 denied
```

---

### 14. GYM_ENTRIES Table

**Purpose:** Gym and facility access logging

```sql
CREATE TABLE gym_entries (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    
    -- Access Tracking
    check_in_at TIMESTAMP NOT NULL,
    check_out_at TIMESTAMP NULLABLE,
    
    -- Additional Info
    duration_minutes INT UNSIGNED NULLABLE,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_check_in_at (check_in_at),
    INDEX idx_check_in_date (DATE(check_in_at)),
    
    CONSTRAINT fk_gym_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE CASCADE
);
```

**Features:**
- QR code-based check-in/out
- Duration calculation
- Daily/monthly usage reports

---

### 15. GATE_ENTRIES Table

**Purpose:** Vehicle and personnel gate access logging

```sql
CREATE TABLE gate_entries (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    staff_id BIGINT UNSIGNED NULLABLE,
    
    -- Vehicle Information
    vehicle_number VARCHAR(20) NULLABLE,
    
    -- Access Type
    entry_type ENUM('entry', 'exit') DEFAULT 'entry' NOT NULL,
    
    -- Tracking
    entry_at TIMESTAMP NOT NULL,
    
    -- Purpose
    purpose VARCHAR(255) NULLABLE,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE,
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_staff_id (staff_id),
    INDEX idx_vehicle_number (vehicle_number),
    INDEX idx_entry_type (entry_type),
    INDEX idx_entry_date (DATE(entry_at)),
    INDEX idx_entry_at (entry_at),
    
    CONSTRAINT fk_gate_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_gate_staff FOREIGN KEY (staff_id) 
        REFERENCES users(id) ON DELETE SET NULL
);
```

**Access Control:**
- QR code validation for residents
- Pre-registered visitor list
- Vehicle tracking
- Time-based access reports

---

## Implementation Guidelines

### 1. User Registration & Verification Flow

```
Step 1: User Registration
├─ Generate user_code (format: USR-{date}-{seq})
├─ Hash password
├─ Create user with role = 'owner'
└─ Status = 'inactive'

Step 2: Phone/Email Verification
├─ Generate OTP (6-digit)
├─ Set otp_expiry (+10 minutes)
├─ Send SMS via API
└─ Verify within time limit

Step 3: Admin Approval (if required)
├─ Super Admin reviews user
├─ Set approved_by = admin_id
├─ Set approved_at = timestamp
└─ Update status = 'active'

Step 4: QR Code Generation
├─ Create QR code from user_code
├─ Encode as base64
└─ Store in qr_code_image
```

### 2. Complaint Resolution Process

```
Step 1: File Complaint
├─ User submits complaint
├─ Status = 'pending'
└─ created_at = now

Step 2: Acknowledge & Assign
├─ Admin marks status = 'in_progress'
├─ Assigned to relevant staff
└─ Staff notified

Step 3: Resolution
├─ Staff updates resolution_notes
├─ Attach resolution_images (if any)
├─ Status = 'resolved'
├─ resolved_at = timestamp
└─ resolved_by = staff_id

Step 4: Follow-up
├─ User can reopen if unsatisfied
└─ Status = 'reopened'
```

### 3. Equipment Booking Validation

```
Validation Rules:
✓ start_date <= end_date
✓ start_time < end_time (if same date)
✓ No overlapping bookings for equipment
✓ Equipment status = 'active'
✓ equipment.is_bookable = true
✓ User must be owner/tenant (role in ['owner', 'tenant'])

Conflict Detection Query:
SELECT COUNT(*) FROM bookings b
WHERE b.equipment_id = :equipment_id
  AND b.status IN ('pending', 'approved')
  AND (
    (b.start_date <= :end_date AND b.end_date >= :start_date)
  )
```

### 4. Data Integrity Rules

```sql
-- Prevent tenant from approving users
ALTER TABLE users ADD CONSTRAINT check_role_approval 
    CHECK (
        approved_by IS NULL OR 
        (SELECT role FROM users u WHERE u.id = approved_by) 
        IN ('admin', 'super_admin')
    );

-- Prevent invalid wing names
ALTER TABLE users ADD CONSTRAINT check_wing_valid 
    CHECK (wing_name IS NULL OR wing_name IN ('A', 'B', 'C', 'D', 'E'));

-- Prevent leave end_date before from_date
ALTER TABLE staff_leave_requests ADD CONSTRAINT check_leave_dates 
    CHECK (to_date >= from_date);

-- Prevent equipment booking conflicts
CREATE UNIQUE INDEX uk_equipment_availability ON bookings (
    equipment_id, 
    start_date, 
    end_date, 
    start_time, 
    end_time
) WHERE status IN ('pending', 'approved');
```

---

## API Endpoints Reference

### Authentication Endpoints

```
POST   /api/auth/register              (Create user)
POST   /api/auth/verify-otp            (Verify phone)
POST   /api/auth/login                 (User login)
POST   /api/auth/logout                (User logout)
POST   /api/auth/refresh-token         (Refresh JWT)
POST   /api/auth/resend-otp            (Resend OTP)
```

### User Management

```
GET    /api/users                      (List users - admin)
GET    /api/users/:id                  (Get user profile)
PUT    /api/users/:id                  (Update profile)
POST   /api/users/:id/approve          (Approve user - admin)
POST   /api/users/:id/block            (Block user - admin)
GET    /api/users/:id/qr-code          (Get QR code)
POST   /api/users/bulk-import          (Import users - admin)
```

### Family Members

```
POST   /api/family-members             (Add family member)
GET    /api/family-members             (List family)
PUT    /api/family-members/:id         (Update family member)
DELETE /api/family-members/:id         (Remove family member)
POST   /api/family-members/:id/approve (Approve member - admin)
```

### Complaints

```
POST   /api/complaints                 (File complaint)
GET    /api/complaints                 (List complaints)
GET    /api/complaints/:id             (Get complaint detail)
PUT    /api/complaints/:id             (Update complaint)
POST   /api/complaints/:id/resolve     (Resolve complaint - staff)
POST   /api/complaints/:id/reopen      (Reopen complaint - user)
GET    /api/complaints/stats/dashboard (Complaint analytics)
```

### Equipment & Bookings

```
GET    /api/equipments                 (List equipments)
POST   /api/equipments                 (Create equipment - admin)
PUT    /api/equipments/:id             (Update equipment - admin)
POST   /api/bookings                   (Request booking)
GET    /api/bookings                   (List bookings)
PUT    /api/bookings/:id               (Update booking status - admin)
GET    /api/bookings/availability      (Check equipment slots)
```

### Events & Notices

```
POST   /api/events                     (Create event - admin)
GET    /api/events                     (List events)
PUT    /api/events/:id                 (Update event - admin)
DELETE /api/events/:id                 (Delete event - admin)

POST   /api/notices                    (Create notice - admin)
GET    /api/notices                    (List notices)
PUT    /api/notices/:id                (Update notice - admin)
```

### Staff Management

```
POST   /api/staff/tasks                (Create task - admin)
GET    /api/staff/tasks                (List tasks)
PUT    /api/staff/tasks/:id            (Update task status)
POST   /api/staff/tasks/:id/complete   (Mark complete)

POST   /api/staff/leave-requests       (Request leave)
GET    /api/staff/leave-requests       (List requests)
PUT    /api/staff/leave-requests/:id   (Approve/reject - admin)
```

### Security & Access

```
POST   /api/visitors/register          (Pre-register visitor)
GET    /api/visitors                   (List visitors)
POST   /api/visitors/:id/check-in      (Check in visitor)
POST   /api/visitors/:id/check-out     (Check out visitor)

POST   /api/gym/check-in               (Gym entry via QR)
POST   /api/gym/check-out              (Gym exit via QR)
GET    /api/gym/logs                   (Gym usage logs)

POST   /api/gate/entry                 (Gate entry log)
POST   /api/gate/exit                  (Gate exit log)
GET    /api/gate/logs                  (Gate traffic logs)
```

### Admin & Notifications

```
POST   /api/notifications              (Send notification)
GET    /api/notifications              (List notifications)
POST   /api/services                   (Add service)
GET    /api/services                   (List services)
GET    /api/ads                        (List advertisements)
```

---

## Security & Validation

### Authentication Security

```php
// Password Requirements
- Minimum 8 characters
- At least 1 uppercase letter
- At least 1 lowercase letter
- At least 1 number
- At least 1 special character

// OTP Security
- 6-digit numeric code
- 10-minute validity
- Max 3 attempts before lock
- Rate limit: 1 OTP per 2 minutes

// JWT Token
- HS256 algorithm
- 15-minute expiry
- Refresh token: 7-day expiry
- Secure httpOnly cookies
```

### Data Validation Rules

```php
// User Creation
phone:   required|numeric|unique:users|regex:/^[6-9]\d{9}$/
email:   required|email|unique:users
wing:    nullable|in:A,B,C,D,E
flat_no: nullable|numeric|min:1|max:999

// Complaint Filing
title:       required|string|min:5|max:255
description: required|string|min:10|max:5000
priority:    in:low,medium,high
type:        in:maintenance,security,electrical,...
image:       nullable|image|max:5120

// Equipment Booking
start_date:  required|date|after_or_equal:today
end_date:    required|date|after_or_equal:start_date
start_time:  required|date_format:H:i
end_time:    required|date_format:H:i|after:start_time
```

### Authorization Matrix

| Action | Owner | Tenant | Admin | Staff | Super Admin |
|--------|-------|--------|-------|-------|-------------|
| File Complaint | ✓ | ✓ | ✓ | - | ✓ |
| View Own Complaints | ✓ | ✓ | - | - | ✓ |
| Resolve Complaint | - | - | ✓ | ✓ | ✓ |
| Manage Users | - | - | ✓ | - | ✓ |
| Approve Users | - | - | - | - | ✓ |
| Create Events | - | - | ✓ | - | ✓ |
| Post Notices | - | - | ✓ | - | ✓ |
| Assign Tasks | - | - | ✓ | - | ✓ |
| Complete Tasks | - | - | - | ✓ | ✓ |
| Book Equipment | ✓ | ✓ | - | - | - |
| Approve Booking | - | - | ✓ | - | ✓ |
| Manage Services | - | - | ✓ | - | ✓ |

### SQL Injection Prevention

```php
// Always use parameterized queries
// ✓ CORRECT
$users = DB::table('users')
    ->where('wing_name', $wing)
    ->where('status', 'active')
    ->get();

// ❌ WRONG
$users = DB::raw("SELECT * FROM users WHERE wing = '$wing'");
```

### File Upload Security

```php
// Image Upload Validation
$image = request()->file('image');

if ($image->isValid()) {
    // Check file size
    if ($image->getSize() > 5 * 1024 * 1024) {
        return error('Max 5MB allowed');
    }
    
    // Check MIME type
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($image->getMimeType(), $allowed)) {
        return error('Invalid image format');
    }
    
    // Store with hashed filename
    $path = $image->store('uploads/complaints');
}
```

---

## Quick Reference Summary

### Table Statistics

| Table | Purpose | Primary Keys | Relationships | Soft Delete |
|-------|---------|--------------|---------------|------------|
| users | Auth & profiles | id, user_code | 8 | Yes |
| family_members | Family tracking | id | 1:M users | Yes |
| complaints | Issue tracking | id | M:1 users | Yes |
| events | Community events | id | M:1 users | Yes |
| notices | Announcements | id | M:1 users | Yes |
| advertisements | Marketing | id | M:1 users | Yes |
| services | Directory | id | M:1 users | Yes |
| equipments | Inventory | id | M:1 users | Yes |
| bookings | Reservations | id | M:M users-equipments | Yes |
| admin_notifications | Messaging | id | M:1 users | Yes |
| staff_tasks | Operations | id | M:1 users | Yes |
| staff_leave_requests | Leave mgmt | id | M:1 users | Yes |
| visitors | Guest tracking | id | M:1 users | Yes |
| gym_entries | Access logs | id | M:1 users | Yes |
| gate_entries | Security logs | id | M:1 users | Yes |

### Migration Creation Order

1. users (foundation)
2. family_members
3. complaints
4. events
5. notices
6. advertisements
7. services
8. equipments
9. bookings
10. admin_notifications
11. staff_tasks
12. staff_leave_requests
13. visitors
14. gym_entries
15. gate_entries

---

**Document Prepared For:** VSH Development Team  
**Version:** 1.0  
**Last Updated:** January 2, 2026  
**Status:** Production Ready
