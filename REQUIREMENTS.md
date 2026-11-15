# UrbanNest — Project Requirements, Architecture & Credential Directory

UrbanNest is a comprehensive web platform for paying guest (PG) accommodation discovery, roommate compatibility matching, and a resident thrift/resale marketplace (Urban Nation).

---

## 1. System Requirements & Prerequisites

| Requirement | Minimum / Recommended Version | Notes |
| :--- | :--- | :--- |
| **Operating System** | Windows 10/11, macOS, or Linux | Tested on Windows 11 64-bit |
| **PHP Runtime** | PHP 8.0 or higher | Tested on **PHP 8.2.12** (via XAMPP) |
| **Web Server** | PHP Built-in Server or Apache 2.4+ | Tested via PHP CLI & XAMPP Apache |
| **Database** | MySQL 8.0+ or MariaDB 10.4+ | Default Port: `3306` |
| **PHP Extensions** | `mysqli`, `session`, `json`, `fileinfo`, `mbstring` | Standard in XAMPP |
| **Web Browser** | Chrome, Edge, Firefox, or Safari | Modern evergreen browser with ES6 support |

---

## 2. Quickstart & How to Run the Project

### Step 1: Start MySQL Database
Ensure MySQL is running on port `3306` via XAMPP Control Panel or command line:
```powershell
# Verify MySQL process in PowerShell
Get-Process mysqld
```

### Step 2: Database Setup & Migration
- **Database Name**: `urbanest`
- **Initial Schema**: Located at `db/urbanest.sql`
- **Roommate Migration**: Located at `db/roommate_migration.sql`
- **Urban Nation Chat Migration**: Located at `db/urbannation_messages.sql`
- If setting up on a fresh machine:
  ```powershell
  & "C:\xampp\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS urbanest;"
  & "C:\xampp\mysql\bin\mysql.exe" -u root urbanest < "db/urbanest.sql"
  & "C:\xampp\mysql\bin\mysql.exe" -u root urbanest < "db/roommate_migration.sql"
  & "C:\xampp\mysql\bin\mysql.exe" -u root urbanest < "db/urbannation_messages.sql"
  ```

### Step 3: Launch the Web Server
Run the PHP development server from the project root directory:
```powershell
& "C:\xampp\php\php.exe" -S localhost:8000 -t .
```

New PG listings start as **pending** and only appear on the public site after an admin approves them under **Admin → PG Details**. Owners must also be approved before they can log in.

### Step 4: Open in Browser
Navigate to:
```
http://localhost:8000/
```

---

## 3. Master Credential Directory & Access Portals

All test accounts share the unified password: **`Test@123`**

Passwords are stored with PHP `password_hash()`. The seed data still ships legacy MD5 hashes; each account is upgraded automatically the first time it logs in.

### A. System Administrator Portal
- **Login URL**: [http://localhost:8000/admin/login.php](http://localhost:8000/admin/login.php)
- **Username**: `admin`
- **Password**: `Test@123`
- **Associated Email**: `admin@gmail.com`
- **Capabilities**:
  - Approve or reject PG Owner registrations
  - Add, edit, and delete States and Cities
  - Review and manage all PG listings
  - Manage all booking requests across properties
  - Monitor registered users

---

### B. PG Owner Portal
- **Login URL**: [http://localhost:8000/owner/login.php](http://localhost:8000/owner/login.php)
- **Registration URL**: [http://localhost:8000/owner/owner-registration.php](http://localhost:8000/owner/owner-registration.php)
- **Email**: `tejasnaik@gmail.com`
- **Password**: `Test@123`
- **Owner Name**: Tejas Naik
- **Mobile**: `9764413130`
- **Account Status**: Approved (`status = 1`)
- **Capabilities**:
  - Add new PG accommodations with photo uploads
  - Manage room inventory, pricing, and amenities
  - Review incoming tenant booking requests (Confirm / Cancel)
  - View booking history

---

### C. Tenant / User Accounts
- **Login URL**: [http://localhost:8000/signin.php](http://localhost:8000/signin.php)
- **Registration URL**: [http://localhost:8000/register.php](http://localhost:8000/register.php)
- **Forgot Password URL**: [http://localhost:8000/forgot-password.php](http://localhost:8000/forgot-password.php)

#### Test Account 1:
- **Email**: `shivam@gmail.com`
- **Password**: `Test@123`
- **Full Name**: Shivam Gadkerkar
- **Mobile**: `9823939192`

#### Test Account 2:
- **Email**: `chandu@gmail.com`
- **Password**: `Test@123`
- **Full Name**: Chandrakant Tamoskar
- **Mobile**: `8999989824`

**User Capabilities**:
- Browse and filter PG accommodations by State, City, and Budget
- Submit PG booking applications
- Create roommate profile (cleanliness, sleep schedule, smoking/drinking, dietary habits)
- Match with compatible roommates with algorithmic compatibility score (%)
- Send roommate requests and real-time chat
- Browse, buy, and sell pre-loved furniture/electronics on Urban Nation

---

### D. Database Credentials
- **Host**: `localhost`
- **Port**: `3306`
- **Database Name**: `urbanest`
- **Username**: `root`
- **Password**: *(empty / none)*
- **Config File**: `includes/dbconnection.php`
- **XAMPP phpMyAdmin**: [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)

---we 

## 4. Key Modules & URL Directory

| Module | URL | Description |
| :--- | :--- | :--- |
| **Homepage** | `http://localhost:8000/` | Main portal hero, search, featured PGs |
| **Find PG** | `http://localhost:8000/pg.php` | Browse and filter accommodations (state, type, budget, keyword) |
| **PG Details** | `http://localhost:8000/pg-details.php?pid=<id>` | Rooms, pricing, amenities, booking button |
| **Book PG** | `http://localhost:8000/apply-forpg.php` | Application submission form |
| **Find Roommate** | `http://localhost:8000/find-roommate.php` | Compatibility engine, roommate directory |
| **Have a Room** | `http://localhost:8000/have-room.php` | Post available room for co-living |
| **Need a Room** | `http://localhost:8000/need-room.php` | Post room-seeker profile |
| **Roommate Matches** | `http://localhost:8000/my-matches.php` | Ranked compatibility matches (%) |
| **Roommate Messages** | `http://localhost:8000/messages.php` | Direct message threads between seekers |
| **Urban Nation Store** | `http://localhost:8000/resellNation/` | Thrift store landing page |
| **Thrift Search** | `http://localhost:8000/resellNation/search.php` | Public product catalog & category filters |
| **Product Details** | `http://localhost:8000/resellNation/product.php?id=<id>` | Product image, price, specs, seller chat |
| **List Thrift Item** | `http://localhost:8000/resellNation/seller.php` | Post furniture/electronics for sale |
| **My Listed Items** | `http://localhost:8000/resellNation/myadd.php` | Manage and edit personal listings |
| **About Us** | `http://localhost:8000/about-us.php` | Organization mission and vision |
| **Contact** | `http://localhost:8000/contact.php` | Support inquiry form & details |

---

## 5. Project Directory Structure

```text
urbannest/
├── admin/                      # Admin Panel module
│   ├── dashboard.php           # Admin overview & metrics
│   ├── login.php               # Admin login
│   ├── manage-ownerdetails.php # Owner approval & verification
│   ├── manage-pgdetails.php    # PG listings moderation
│   ├── manage-city.php         # City management
│   ├── manage-state.php        # State management
│   └── user-bookings.php       # All system bookings
├── css/                        # Global stylesheets (main.css, bootstrap, etc.)
├── db/                         # Database dumps
│   ├── urbanest.sql            # Master database dump
│   ├── roommate_migration.sql  # Roommate engine tables & constraints
│   └── urbannation_messages.sql # Urban Nation buyer/seller chat messages
├── img/                        # Branding images, logo (pglogo.png), placeholders
├── includes/                   # Reusable components
│   ├── dbconnection.php        # Global MySQL connection configuration
│   ├── header.php              # Sticky responsive navigation header
│   ├── footer.php              # Global footer
│   ├── roommate-functions.php  # Roommate database access helpers
│   └── roommate-compatibility.php # Roommate matching algorithm
├── js/                         # Vendor & application scripts
├── owner/                      # PG Owner portal
│   ├── dashboard.php           # Owner dashboard
│   ├── login.php               # Owner signin
│   ├── owner-registration.php  # New owner registration
│   ├── add-pgdetails.php       # Create new PG listing
│   └── all-bookings.php        # Booking request approvals
├── resellNation/               # Urban Nation (Thrift Store module)
│   ├── index.php               # Urban Nation landing page
│   ├── search.php              # Public thrift product catalog & chat
│   ├── product.php             # Individual product page & inquiry
│   ├── seller.php              # Post item for sale form
│   ├── myadd.php               # Manage seller listings
│   ├── includes/header2.php    # Urban Nation responsive navbar
│   ├── includes/footer2.php    # Urban Nation dark footer
│   ├── php/                    # AJAX backend (chat.php, sendMesssage.php, etc.)
│   └── upload/                 # Uploaded product images
├── roommate-uploads/           # Uploaded profile photos for roommates
├── about-us.php                # Public About Us page
├── contact.php                 # Public Contact page
├── find-roommate.php           # Public Roommate Finder
├── have-room.php               # Post room availability
├── need-room.php               # Post roommate seeker profile
├── index.php                   # Public Homepage
├── pg.php                      # Public PG Accommodation directory
├── register.php                # User signup
├── signin.php                  # User signin (modern centered card)
└── REQUIREMENTS.md             # This requirements & credential reference
```

---

## 6. Database Tables Reference

| Table | Purpose |
| :--- | :--- |
| `tbladmin` | System administrator credentials and login details |
| `tblowner` | Registered PG owners, verification documents, and approval status |
| `tbluser` | Registered tenants/students, profile info, and chat data (`comm`) |
| `tblpg` | PG accommodations, address, rent, amenities, and owner relation |
| `tblcity` | City directory linked to states |
| `tblstate` | State directory |
| `tblbookpg` | Tenant booking applications, check-in dates, and status |
| `tblpages` | Dynamic CMS content (About Us, Contact Us) |
| `product` | Urban Nation thrift listings (title, category, price, seller, image) |
| `roommate_profiles` | User lifestyle attributes (cleanliness, sleep, smoking, food) |
| `roommate_listings` | "Have a Room" listings posted by users |
| `roommate_preferences` | "Need a Room" preferences posted by seekers |
| `roommate_requests` | Direct roommate connection requests (pending/accepted/rejected) |
| `roommate_conversations`| Roommate chat conversations |
| `roommate_messages` | Message records in roommate conversations |
| `roommate_blocks` | User blocklist records |

---

## 7. Troubleshooting & FAQs

### Q: Why did the login show "Invalid Details"?
- Verify that you are logging into the correct portal for your account:
  - Admin accounts must log in at `/admin/login.php`.
  - Owner accounts must log in at `/owner/login.php`.
  - Student / Tenant accounts must log in at `/signin.php`.
- Ensure password is typed as `Test@123` (case-sensitive).

### Q: Why does the server show port 8000 already in use?
- If port `8000` is occupied, start on port `8080`:
  ```powershell
  & "C:\xampp\php\php.exe" -S localhost:8080 -t .
  ```
  Access at `http://localhost:8080/`.

### Q: How to restart the server if closed?
- Run the following command in PowerShell:
  ```powershell
  & "C:\xampp\php\php.exe" -S localhost:8000 -t .
  ```

