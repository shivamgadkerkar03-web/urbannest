# UrbanNest — Student PG Accommodation, Roommate Finder & Thrift Marketplace

**UrbanNest** is a comprehensive multi-portal web platform engineered for students and young professionals. It integrates:
1. **PG Accommodation Finder**: Search, filter, and apply for verified paying guest accommodations.
2. **Roommate Compatibility Engine**: Discover compatible co-living partners using lifestyle preferences and mutual messaging.
3. **Urban Nation (Thrift Marketplace)**: Buy, sell, and negotiate pre-loved student furniture, electronics, and essentials with direct in-app messaging.
4. **Owner Portal**: Manage PG properties, room types, pricing, and approve tenant booking requests.
5. **Admin Portal**: Moderate listings, manage state/city directories, verify PG owners, and oversee platform activity.

---

## 📋 System Prerequisites

| Component | Minimum Version | Recommended / Tested With |
| :--- | :--- | :--- |
| **Operating System** | Windows 10/11, macOS, Linux | Windows 11 (64-bit) |
| **PHP** | PHP 8.0+ | **PHP 8.2.12** (XAMPP) |
| **Database** | MySQL 5.7+ / MariaDB 10.4+ | MariaDB 10.4.32 / MySQL 8.0 |
| **Web Server** | PHP Built-in Server or Apache | PHP Built-in CLI Server (`php -S`) or XAMPP Apache |
| **PHP Extensions** | `mysqli`, `session`, `json`, `mbstring`, `fileinfo` | Enabled by default in XAMPP |
| **Browser** | Chrome, Edge, Firefox, Safari | Any modern web browser with JavaScript enabled |

---

## 🚀 How to Run the Project (Step-by-Step)

### Step 1: Start MySQL Database

1. Open the **XAMPP Control Panel**.
2. Click **Start** next to **MySQL** (default port `3306`).
   - *Alternative (PowerShell)*:
     ```powershell
     Start-Process "C:\xampp\mysql\bin\mysqld.exe"
     ```

---

### Step 2: Database Setup & Import

Open your terminal / PowerShell or phpMyAdmin (`http://localhost/phpmyadmin`):

1. **Create the database**:
   ```sql
   CREATE DATABASE IF NOT EXISTS urbanest;
   ```

2. **Import the SQL files** in sequence:
   ```powershell
   # Using MySQL CLI:
   & "C:\xampp\mysql\bin\mysql.exe" -u root urbanest < "c:\Users\shiva\OneDrive\Desktop\urbannest\db\urbanest.sql"
   & "C:\xampp\mysql\bin\mysql.exe" -u root urbanest < "c:\Users\shiva\OneDrive\Desktop\urbannest\db\roommate_migration.sql"
   ```
   *(Note: Default database configuration in `includes/dbconnection.php` connects to `localhost`, user `root`, no password, database `urbanest`)*.

---

### Step 3: Start the Web Application

Open a terminal or PowerShell in the project root directory (`c:\Users\shiva\OneDrive\Desktop\urbannest`):

```powershell
# Start the built-in PHP development server on port 8000
& "C:\xampp\php\php.exe" -S localhost:8000 -t .
```

> **Tip:** If port `8000` is already in use by another application, launch on port `8080`:
> ```powershell
> & "C:\xampp\php\php.exe" -S localhost:8080 -t .
> ```

---

### Step 4: Open in Web Browser

Visit:
```
http://localhost:8000/
```

---

## 🔑 Portals & Test Credentials

All pre-seeded test accounts use the unified password: **`Test@123`**

### 1. Tenant / Student Portal
- **Login URL**: [`http://localhost:8000/signin.php`](http://localhost:8000/signin.php)
- **Registration URL**: [`http://localhost:8000/register.php`](http://localhost:8000/register.php)

| User Name | Email | Password | Role / Details |
| :--- | :--- | :--- | :--- |
| **Chandrakant Tamoskar** | `chandu@gmail.com` | `Test@123` | Active resident, roommate profile, product seller |
| **Shivam Gadkerkar** | `shivam@gmail.com` | `Test@123` | Active resident, connected roommate, marketplace buyer |
| **Apoorva** | `apoorvatalwar1408@gmail.com` | `Test@123` | Active resident, connected roommate |

---

### 2. PG Owner Portal
- **Login URL**: [`http://localhost:8000/owner/login.php`](http://localhost:8000/owner/login.php)
- **Registration URL**: [`http://localhost:8000/owner/owner-registration.php`](http://localhost:8000/owner/owner-registration.php)

| Email | Password | Owner Name | Mobile | Status |
| :--- | :--- | :--- | :--- | :--- |
| `tejasnaik@gmail.com` | `Test@123` | Tejas Naik | 9764413130 | Approved (`status = 1`) |

*Capabilities*: Add new PG listings, upload property photos, update room pricing and amenities, approve/cancel incoming tenant bookings.

---

### 3. Administrator Portal
- **Login URL**: [`http://localhost:8000/admin/login.php`](http://localhost:8000/admin/login.php)

| Username | Password | Email | Role |
| :--- | :--- | :--- | :--- |
| `admin` | `Test@123` | `admin@gmail.com` | System Superadmin |

*Capabilities*: Approve/reject PG owners, review & publish pending PG listings, manage states and cities, review all user bookings and registered accounts.

---

## 🧭 Key Routes & Page Directory

| Feature | URL | Description |
| :--- | :--- | :--- |
| **Home** | `/index.php` | Main landing page with search bar and featured PGs |
| **Browse PGs** | `/pg.php` | Search and filter accommodations by State, City, and Budget |
| **PG Details** | `/pg-details.php?pid=<id>` | Detailed view with room types, amenities, and booking request form |
| **Apply for PG** | `/apply-forpg.php` | Submit room booking application |
| **Find Roommate** | `/find-roommate.php` | Roommate search and lifestyle compatibility cards |
| **Have a Room** | `/have-room.php` | Post an available room for co-living |
| **Need a Room** | `/need-room.php` | Post a roommate seeker request with budget and preferences |
| **Messages (Chat)**| `/messages.php` | Direct real-time messaging between connected roommates |
| **Urban Nation** | `/resellNation/index.php` | Student thrift marketplace homepage |
| **Thrift Search** | `/resellNation/search.php` | Browse pre-loved items by category (Furniture, Electronics, Lifestyle) |
| **Product & Chat**| `/resellNation/product.php?id=<id>` | View product details and launch **Chat with Seller** |
| **Sell Item** | `/resellNation/seller.php` | Post furniture or electronics for sale |
| **My Ads** | `/resellNation/myadd.php` | Manage, edit, or delete personal thrift listings |

---

## 💬 How to Test Real-Time Chat

### Testing Roommate Messaging:
1. Open an incognito or separate browser window.
2. In Window 1, log in as **Chandrakant** (`chandu@gmail.com` / `Test@123`).
3. In Window 2, log in as **Shivam** (`shivam@gmail.com` / `Test@123`).
4. Both navigate to **Messages** (`/messages.php`).
5. Open the conversation thread with each other and send messages. They will render immediately via AJAX polling.

### Testing Urban Nation Marketplace Chat:
1. In Window 1, log in as **Shivam** (`shivam@gmail.com` / `Test@123`).
2. Go to **Urban Nation** -> click on the **HP Victus Gaming Laptop** (or any item).
3. Click **Chat with Seller** -> type a message and press **Send**.
4. In Window 2, logged in as the seller **Chandrakant** (`chandu@gmail.com`), open the same product and click **Chat with Seller**; the buyer's message thread will be visible and interactive.

---

## 🛠️ Project Directory Structure

```text
urbannest/
├── admin/                      # Administrator backend portal
│   ├── dashboard.php           # Admin metrics & analytics
│   ├── login.php               # Admin login screen
│   ├── manage-ownerdetails.php # Owner approval management
│   ├── manage-pgdetails.php    # PG listings moderation
│   ├── manage-city.php         # City directory management
│   └── user-bookings.php       # Global booking records
├── css/                        # Global frontend stylesheets
├── db/                         # Database schema and seed dumps
│   ├── urbanest.sql            # Core database schema & initial data
│   └── roommate_migration.sql  # Roommate profiles, matches, & messages schema
├── img/                        # Public images, placeholders, and icons
├── includes/                   # Reusable backend components
│   ├── dbconnection.php        # MySQLi database connection handler
│   ├── header.php              # Global navigation bar
│   ├── footer.php              # Global footer
│   ├── roommate-functions.php  # Roommate matching and chat helper routines
│   └── security.php            # CSRF tokens & session sanitization
├── js/                         # JavaScript libraries and AJAX helpers
├── owner/                      # PG Owner self-service portal
│   ├── dashboard.php           # Owner dashboard
│   ├── login.php               # Owner login screen
│   ├── add-pgdetails.php       # Add new PG listing with room photos
│   └── all-bookings.php        # Tenant booking approvals
├── resellNation/               # Urban Nation (Resident Resale & Thrift module)
│   ├── index.php               # Marketplace landing page
│   ├── search.php              # Catalog search with category filters
│   ├── product.php             # Product details & buyer-seller chat modal
│   ├── seller.php              # Create item listing form
│   ├── myadd.php               # Edit and manage seller listings
│   ├── php/chat.php            # AJAX endpoint for marketplace messages
│   └── php/sendMesssage.php    # AJAX endpoint for message dispatch
├── roommate-uploads/           # Storage directory for roommate profile pictures
├── chat.php                    # Roommate direct message chat interface
├── find-roommate.php           # Roommate discovery directory
├── have-room.php               # List an open room for rent
├── need-room.php               # Submit room-seeker lifestyle profile
├── messages.php                # Roommate conversation threads inbox
├── pg.php                      # Accommodation catalog
├── pg-details.php              # Property inspection & booking request
├── register.php                # Tenant registration
├── signin.php                  # Tenant sign-in
└── README.md                   # This quickstart & reference guide
```

---

## ❓ Troubleshooting & FAQs

### 1. `Connection Fail: No connection could be made because the target machine actively refused it`
- **Cause**: MySQL service is not running.
- **Fix**: Open XAMPP Control Panel and start the **MySQL** module. Ensure port `3306` is listening.

### 2. `Invalid Details` during Login
- Check that you are using the correct login page for your role:
  - Students/Tenants log in at `/signin.php`
  - PG Owners log in at `/owner/login.php`
  - Administrators log in at `/admin/login.php`
- All test accounts use password: `Test@123` (case-sensitive).

### 3. Port 8000 is already in use
- If `localhost:8000` is occupied, specify another port such as `8080`:
  ```powershell
  & "C:\xampp\php\php.exe" -S localhost:8080 -t .
  ```

