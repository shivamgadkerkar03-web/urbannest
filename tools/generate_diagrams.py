import os
import matplotlib.pyplot as plt
import matplotlib.patches as patches

out_dir = os.path.abspath("report_assets/diagrams")
os.makedirs(out_dir, exist_ok=True)

# Common styling
plt.rcParams['font.sans-serif'] = 'DejaVu Sans'
plt.rcParams['font.family'] = 'sans-serif'

# 1. Use Case Diagram
def generate_use_case():
    fig, ax = plt.subplots(figsize=(10, 8), dpi=300)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    # Boundary box
    rect = patches.FancyBboxPatch((28, 5), 44, 90, boxstyle="round,pad=1", edgecolor="#1e3a8a", facecolor="#f8fafc", linewidth=2)
    ax.add_patch(rect)
    ax.text(50, 92, "UrbanNest Web Application System Boundary", ha='center', va='center', fontsize=12, fontweight='bold', color='#1e3a8a')

    # Actors
    actors = [
        {"name": "Tenant / Seeker\n(Student/Professional)", "x": 12, "y": 70, "color": "#0284c7"},
        {"name": "PG Property\nOwner", "x": 12, "y": 30, "color": "#059669"},
        {"name": "System\nAdministrator", "x": 88, "y": 50, "color": "#dc2626"}
    ]
    for act in actors:
        circle = plt.Circle((act['x'], act['y'] + 5), 3, color=act['color'], fill=True)
        ax.add_patch(circle)
        ax.plot([act['x'], act['x']], [act['y'] + 2, act['y'] - 3], color=act['color'], lw=2.5) # body
        ax.plot([act['x'] - 3, act['x'] + 3], [act['y'], act['y']], color=act['color'], lw=2.5) # arms
        ax.plot([act['x'], act['x'] - 2.5], [act['y'] - 3, act['y'] - 7], color=act['color'], lw=2.5) # left leg
        ax.plot([act['x'], act['x'] + 2.5], [act['y'] - 3, act['y'] - 7], color=act['color'], lw=2.5) # right leg
        ax.text(act['x'], act['y'] - 11, act['name'], ha='center', va='top', fontsize=9, fontweight='bold', color='#1e293b')

    # Use cases
    use_cases = [
        {"name": "Search & Filter Accommodations", "y": 84, "actors": [0]},
        {"name": "Apply for PG Booking", "y": 75, "actors": [0]},
        {"name": "Manage Lifestyle Profile & Habits", "y": 66, "actors": [0]},
        {"name": "Algorithmic Roommate Matching", "y": 57, "actors": [0]},
        {"name": "Real-Time Roommate Chat", "y": 48, "actors": [0]},
        {"name": "Browse / Sell on Thrift Store", "y": 39, "actors": [0, 1]},
        {"name": "Add / Manage PG Properties", "y": 30, "actors": [1]},
        {"name": "Review Booking Requests", "y": 21, "actors": [1]},
        {"name": "Moderate Owners & Properties", "y": 70, "actors": [2]},
        {"name": "Manage Cities, States & CMS", "y": 55, "actors": [2]},
        {"name": "Platform User & Booking Oversight", "y": 40, "actors": [2]},
    ]

    for uc in use_cases:
        ellipse = patches.Ellipse((50, uc['y']), 34, 6.5, edgecolor='#3b82f6', facecolor='#ffffff', linewidth=1.5, zorder=3)
        ax.add_patch(ellipse)
        ax.text(50, uc['y'], uc['name'], ha='center', va='center', fontsize=8.5, fontweight='bold', color='#1e293b', zorder=4)
        for a_idx in uc['actors']:
            ax_pos = actors[a_idx]
            ax.plot([ax_pos['x'] + (4 if a_idx < 2 else -4), 33 if a_idx < 2 else 67], [ax_pos['y'], uc['y']], color='#94a3b8', linestyle='-', lw=1.2, zorder=2)

    plt.tight_layout()
    fig.savefig(os.path.join(out_dir, "fig_2_1_use_case_diagram.png"), bbox_inches='tight')
    plt.close()

# 2. Sequence Diagram
def generate_sequence():
    fig, ax = plt.subplots(figsize=(10, 7), dpi=300)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    entities = [
        {"name": "Tenant / Seeker", "x": 15},
        {"name": "Browser UI (Client)", "x": 38},
        {"name": "PHP Matching Engine", "x": 62},
        {"name": "MySQL Database", "x": 85}
    ]

    for ent in entities:
        rect = patches.FancyBboxPatch((ent['x'] - 10, 88), 20, 7, boxstyle="round,pad=0.5", edgecolor="#1e3a8a", facecolor="#dbeafe", linewidth=1.5)
        ax.add_patch(rect)
        ax.text(ent['x'], 91.5, ent['name'], ha='center', va='center', fontsize=9, fontweight='bold', color='#1e3a8a')
        ax.plot([ent['x'], ent['x']], [88, 10], color='#cbd5e1', linestyle='--', lw=1.5)

    steps = [
        (15, 38, 80, "1. Set Lifestyle Preferences (Sleep, Food, Smoke)", True),
        (38, 62, 72, "2. POST profile parameters & budget", True),
        (62, 85, 64, "3. Query roommate_profiles & preferences", True),
        (85, 62, 56, "4. Return candidate lifestyle vectors", False),
        (62, 62, 48, "5. Compute Multi-Attribute Compatibility %", False),
        (62, 38, 40, "6. Return Ranked Matches (Score Descending)", False),
        (38, 15, 32, "7. Render Ranked Profile Cards & Match %", False),
        (15, 38, 24, "8. Send Connection / Real-time Message", True),
        (38, 85, 16, "9. Insert message & trigger recipient notification", True),
    ]

    for x1, x2, y, txt, is_call in steps:
        if x1 == x2: # self call
            ax.annotate("", xy=(x1, y - 4), xytext=(x1, y), arrowprops=dict(arrowstyle="->", color="#e11d48", lw=1.8, connectionstyle="arc3,rad=-0.8"))
            ax.text(x1 + 3, y - 2, txt, ha='left', va='center', fontsize=8, fontweight='bold', color='#9f1239')
        else:
            style = "->"
            col = "#2563eb" if is_call else "#059669"
            ls = "-" if is_call else "--"
            ax.annotate("", xy=(x2, y), xytext=(x1, y), arrowprops=dict(arrowstyle=style, color=col, linestyle=ls, lw=1.6))
            ax.text((x1 + x2) / 2, y + 2, txt, ha='center', va='bottom', fontsize=8, fontweight='bold', color='#1e293b')

    plt.tight_layout()
    fig.savefig(os.path.join(out_dir, "fig_2_2_sequence_diagram.png"), bbox_inches='tight')
    plt.close()

# 3. Activity Diagram
def generate_activity():
    fig, ax = plt.subplots(figsize=(8, 10), dpi=300)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    # Initial state
    start = plt.Circle((50, 95), 2.5, color='#1e293b', fill=True)
    ax.add_patch(start)

    nodes = [
        {"name": "User Authentication & Role Detection", "y": 86, "shape": "rect"},
        {"name": "Select Service: PG Booking or Roommate Engine", "y": 76, "shape": "decision"},
        {"name": "Browse PGs & Filter by City/Budget", "x": 28, "y": 64, "shape": "rect"},
        {"name": "Configure Roommate Lifestyle Profile", "x": 72, "y": 64, "shape": "rect"},
        {"name": "Submit Booking Application", "x": 28, "y": 52, "shape": "rect"},
        {"name": "Execute Compatibility Matching Algorithm", "x": 72, "y": 52, "shape": "rect"},
        {"name": "Owner Reviews & Confirms Booking", "x": 28, "y": 40, "shape": "rect"},
        {"name": "Explore Ranked Roommate Matches", "x": 72, "y": 40, "shape": "rect"},
        {"name": "Initiate Connection Request & 1-on-1 Chat", "x": 72, "y": 28, "shape": "rect"},
        {"name": "Move-In Confirmation & Thrift Store Furnishing", "y": 16, "shape": "rect"},
    ]

    # Draw arrows and nodes
    ax.annotate("", xy=(50, 89.5), xytext=(50, 92.5), arrowprops=dict(arrowstyle="->", lw=1.5, color="#334155"))

    # Node 1
    r1 = patches.FancyBboxPatch((25, 83), 50, 6.5, boxstyle="round,pad=0.4", edgecolor="#2563eb", facecolor="#eff6ff", lw=1.5)
    ax.add_patch(r1)
    ax.text(50, 86.2, "User Authentication & Role Detection", ha='center', va='center', fontsize=8.5, fontweight='bold')

    ax.annotate("", xy=(50, 79), xytext=(50, 83), arrowprops=dict(arrowstyle="->", lw=1.5, color="#334155"))

    # Decision
    d1 = patches.Polygon([[50, 79], [62, 75], [50, 71], [38, 75]], edgecolor="#d97706", facecolor="#fef3c7", lw=1.5)
    ax.add_patch(d1)
    ax.text(50, 75, "Service Selection", ha='center', va='center', fontsize=8, fontweight='bold')

    # Branches
    ax.annotate("", xy=(28, 67.5), xytext=(38, 75), arrowprops=dict(arrowstyle="->", lw=1.5, color="#334155"))
    ax.text(31, 73, "PG Booking", fontsize=7.5, fontweight='bold', color="#1e3a8a")

    ax.annotate("", xy=(72, 67.5), xytext=(62, 75), arrowprops=dict(arrowstyle="->", lw=1.5, color="#334155"))
    ax.text(64, 73, "Roommates", fontsize=7.5, fontweight='bold', color="#1e3a8a")

    # PG Path
    r_pg1 = patches.FancyBboxPatch((8, 61), 40, 6.5, boxstyle="round,pad=0.4", edgecolor="#0284c7", facecolor="#f0f9ff", lw=1.5)
    ax.add_patch(r_pg1)
    ax.text(28, 64.2, "Browse PGs & Filter by City/Budget", ha='center', va='center', fontsize=8, fontweight='bold')

    ax.annotate("", xy=(28, 55.5), xytext=(28, 61), arrowprops=dict(arrowstyle="->", lw=1.5, color="#334155"))

    r_pg2 = patches.FancyBboxPatch((8, 49), 40, 6.5, boxstyle="round,pad=0.4", edgecolor="#0284c7", facecolor="#f0f9ff", lw=1.5)
    ax.add_patch(r_pg2)
    ax.text(28, 52.2, "Submit Booking Application", ha='center', va='center', fontsize=8, fontweight='bold')

    ax.annotate("", xy=(28, 43.5), xytext=(28, 49), arrowprops=dict(arrowstyle="->", lw=1.5, color="#334155"))

    r_pg3 = patches.FancyBboxPatch((8, 37), 40, 6.5, boxstyle="round,pad=0.4", edgecolor="#0284c7", facecolor="#f0f9ff", lw=1.5)
    ax.add_patch(r_pg3)
    ax.text(28, 40.2, "Owner Reviews & Confirms Booking", ha='center', va='center', fontsize=8, fontweight='bold')

    # Roommate Path
    r_rm1 = patches.FancyBboxPatch((52, 61), 40, 6.5, boxstyle="round,pad=0.4", edgecolor="#7c3aed", facecolor="#f5f3ff", lw=1.5)
    ax.add_patch(r_rm1)
    ax.text(72, 64.2, "Configure Roommate Lifestyle Profile", ha='center', va='center', fontsize=8, fontweight='bold')

    ax.annotate("", xy=(72, 55.5), xytext=(72, 61), arrowprops=dict(arrowstyle="->", lw=1.5, color="#334155"))

    r_rm2 = patches.FancyBboxPatch((52, 49), 40, 6.5, boxstyle="round,pad=0.4", edgecolor="#7c3aed", facecolor="#f5f3ff", lw=1.5)
    ax.add_patch(r_rm2)
    ax.text(72, 52.2, "Execute Compatibility Algorithm", ha='center', va='center', fontsize=8, fontweight='bold')

    ax.annotate("", xy=(72, 43.5), xytext=(72, 49), arrowprops=dict(arrowstyle="->", lw=1.5, color="#334155"))

    r_rm3 = patches.FancyBboxPatch((52, 37), 40, 6.5, boxstyle="round,pad=0.4", edgecolor="#7c3aed", facecolor="#f5f3ff", lw=1.5)
    ax.add_patch(r_rm3)
    ax.text(72, 40.2, "Explore Ranked Roommate Matches", ha='center', va='center', fontsize=8, fontweight='bold')

    ax.annotate("", xy=(72, 31.5), xytext=(72, 37), arrowprops=dict(arrowstyle="->", lw=1.5, color="#334155"))

    r_rm4 = patches.FancyBboxPatch((52, 25), 40, 6.5, boxstyle="round,pad=0.4", edgecolor="#7c3aed", facecolor="#f5f3ff", lw=1.5)
    ax.add_patch(r_rm4)
    ax.text(72, 28.2, "Connection Request & 1-on-1 Chat", ha='center', va='center', fontsize=8, fontweight='bold')

    # Merge Paths
    ax.annotate("", xy=(50, 19.5), xytext=(28, 37), arrowprops=dict(arrowstyle="->", lw=1.5, color="#334155"))
    ax.annotate("", xy=(50, 19.5), xytext=(72, 25), arrowprops=dict(arrowstyle="->", lw=1.5, color="#334155"))

    r_final = patches.FancyBboxPatch((20, 13), 60, 6.5, boxstyle="round,pad=0.4", edgecolor="#059669", facecolor="#ecfdf5", lw=1.5)
    ax.add_patch(r_final)
    ax.text(50, 16.2, "Confirmed Move-In & Campus Thrift Store Furnishing", ha='center', va='center', fontsize=8.5, fontweight='bold')

    # Final End State
    end_outer = plt.Circle((50, 6), 3, color='#1e293b', fill=False, lw=1.8)
    end_inner = plt.Circle((50, 6), 2, color='#1e293b', fill=True)
    ax.add_patch(end_outer)
    ax.add_patch(end_inner)
    ax.annotate("", xy=(50, 9), xytext=(50, 13), arrowprops=dict(arrowstyle="->", lw=1.5, color="#334155"))

    plt.tight_layout()
    fig.savefig(os.path.join(out_dir, "fig_2_3_activity_diagram.png"), bbox_inches='tight')
    plt.close()

# 4. System Architecture Diagram
def generate_architecture():
    fig, ax = plt.subplots(figsize=(10, 8), dpi=300)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    layers = [
        {"title": "1. PRESENTATION TIER (CLIENT INTERFACE)", "y": 78, "h": 16, "color": "#eff6ff", "border": "#3b82f6",
         "items": ["Responsive HTML5 / CSS3 Layouts", "Bootstrap 5 Components", "jQuery & Asynchronous AJAX Chat", "Owl Carousel & Font Awesome"]},
        {"title": "2. APPLICATION & LOGIC TIER (PHP 8.2 BACKEND)", "y": 48, "h": 24, "color": "#f8fafc", "border": "#0284c7",
         "items": ["PG Booking Engine & Filtering", "Roommate Compatibility Algorithm", "Real-Time 1-on-1 Chat Service", "Urban Nation P2P Thrift Marketplace", "KYC & Owner Moderation Controller", "Authentication & Session Security"]},
        {"title": "3. DATA STORAGE & INFRASTRUCTURE TIER", "y": 14, "h": 28, "color": "#f0fdf4", "border": "#16a34a",
         "items": ["MySQL 8.0 Relational DB (14 Tables)", "ACID Transactions & Foreign Keys", "Image Upload Store (PG & Profiles)", "Apache 2.4 HTTP Server (XAMPP)"]}
    ]

    for l in layers:
        rect = patches.FancyBboxPatch((10, l['y']), 80, l['h'], boxstyle="round,pad=0.8", edgecolor=l['border'], facecolor=l['color'], lw=2)
        ax.add_patch(rect)
        ax.text(12, l['y'] + l['h'] - 3.5, l['title'], fontsize=10, fontweight='bold', color=l['border'])
        
        # Sub-boxes
        cols = 2
        for idx, itm in enumerate(l['items']):
            col_idx = idx % cols
            row_idx = idx // cols
            bx = 14 + col_idx * 38
            by = l['y'] + l['h'] - 8 - row_idx * 5.5
            sub_rect = patches.FancyBboxPatch((bx, by), 34, 4.2, boxstyle="round,pad=0.3", edgecolor="#94a3b8", facecolor="#ffffff", lw=1)
            ax.add_patch(sub_rect)
            ax.text(bx + 17, by + 2.1, itm, ha='center', va='center', fontsize=8, color="#1e293b")

    # Arrows between layers
    ax.annotate("HTTP / JSON (REST Requests)", xy=(50, 72), xytext=(50, 78), ha='center', va='center', fontsize=8.5, fontweight='bold', color="#0f172a",
                arrowprops=dict(arrowstyle="<->", lw=2, color="#0284c7"))
    ax.annotate("SQL Queries / Prepared Statements (MySQLi)", xy=(50, 42), xytext=(50, 48), ha='center', va='center', fontsize=8.5, fontweight='bold', color="#0f172a",
                arrowprops=dict(arrowstyle="<->", lw=2, color="#16a34a"))

    plt.tight_layout()
    fig.savefig(os.path.join(out_dir, "fig_5_1_system_architecture.png"), bbox_inches='tight')
    plt.close()

# 5. ER Diagram
def generate_er_diagram():
    fig, ax = plt.subplots(figsize=(11, 8.5), dpi=300)
    ax.set_xlim(0, 110)
    ax.set_ylim(0, 100)
    ax.axis('off')

    tables = [
        {"name": "tbluser", "x": 10, "y": 72, "w": 25, "fields": ["PK: ID", "FullName", "Email", "MobileNumber", "Password (MD5)", "RegDate"]},
        {"name": "tblowner", "x": 10, "y": 36, "w": 25, "fields": ["PK: ID", "FullName", "Email", "MobileNumber", "Password", "status (KYC)"]},
        {"name": "tblpg", "x": 44, "y": 36, "w": 26, "fields": ["PK: ID", "FK: OwnerID", "PGTitle", "CityName", "RPmonth", "StayPeriod"]},
        {"name": "tblbookpg", "x": 44, "y": 72, "w": 26, "fields": ["PK: ID", "FK: UserID", "FK: Pgid", "BookingNumber", "CheckinDate", "status"]},
        {"name": "roommate_profiles", "x": 10, "y": 2, "w": 25, "fields": ["PK: id", "FK: user_id", "cleanliness", "sleep_schedule", "smoking", "food_preference"]},
        {"name": "roommate_preferences", "x": 44, "y": 2, "w": 26, "fields": ["PK: id", "FK: user_id", "preferred_city", "budget_min", "budget_max", "gender_preference"]},
        {"name": "roommate_messages", "x": 78, "y": 36, "w": 26, "fields": ["PK: id", "FK: conversation_id", "FK: sender_id", "message", "is_read", "created_at"]},
        {"name": "product (Thrift)", "x": 78, "y": 72, "w": 26, "fields": ["PK: id", "FK: user_id", "product_name", "category", "price", "image"]},
    ]

    for t in tables:
        h = 5 + len(t['fields']) * 3.2
        rect = patches.FancyBboxPatch((t['x'], t['y']), t['w'], h, boxstyle="round,pad=0.4", edgecolor="#1e3a8a", facecolor="#f8fafc", lw=1.5)
        ax.add_patch(rect)
        # Header banner
        header = patches.FancyBboxPatch((t['x'], t['y'] + h - 5), t['w'], 5, boxstyle="round,pad=0.2", edgecolor="#1e3a8a", facecolor="#1e3a8a", lw=1)
        ax.add_patch(header)
        ax.text(t['x'] + t['w'] / 2, t['y'] + h - 2.5, t['name'], ha='center', va='center', fontsize=9, fontweight='bold', color="#ffffff")
        
        # Fields
        for f_idx, fld in enumerate(t['fields']):
            fy = t['y'] + h - 8 - f_idx * 3.2
            prefix = "● "
            color = "#b91c1c" if "PK:" in fld else ("#0369a1" if "FK:" in fld else "#334155")
            ax.text(t['x'] + 2, fy, prefix + fld, fontsize=7.5, color=color, fontweight='bold' if "PK:" in fld or "FK:" in fld else 'normal')

    # Connector lines
    def connect(t1, t2, x1, y1, x2, y2, label="1 : N"):
        ax.plot([x1, x2], [y1, y2], color="#475569", lw=1.5, linestyle=":")
        ax.text((x1 + x2)/2, (y1 + y2)/2, label, fontsize=7, fontweight='bold', color="#b91c1c", bbox=dict(boxstyle="round,pad=0.2", facecolor="#ffffff", edgecolor="#cbd5e1"))

    connect(tables[0], tables[3], 35, 82, 44, 82, "1 : N") # tbluser -> tblbookpg
    connect(tables[2], tables[3], 57, 58, 57, 72, "1 : N") # tblpg -> tblbookpg
    connect(tables[1], tables[2], 35, 46, 44, 46, "1 : N") # tblowner -> tblpg
    connect(tables[0], tables[4], 22, 72, 22, 23, "1 : 1") # tbluser -> roommate_profiles
    connect(tables[0], tables[5], 25, 72, 44, 18, "1 : 1") # tbluser -> roommate_preferences
    connect(tables[0], tables[7], 35, 88, 78, 88, "1 : N") # tbluser -> product

    plt.tight_layout()
    fig.savefig(os.path.join(out_dir, "fig_5_2_er_diagram.png"), bbox_inches='tight')
    plt.close()

# 6. Data Flow Diagram (DFD Level 1)
def generate_dfd():
    fig, ax = plt.subplots(figsize=(10, 8), dpi=300)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    # External entities
    entities = [
        {"name": "Tenant / Seeker", "x": 10, "y": 70, "w": 18, "h": 10},
        {"name": "Property Owner", "x": 10, "y": 25, "w": 18, "h": 10},
        {"name": "System Admin", "x": 82, "y": 50, "w": 16, "h": 10}
    ]
    for ent in entities:
        rect = patches.Rectangle((ent['x'], ent['y']), ent['w'], ent['h'], edgecolor="#0f172a", facecolor="#e2e8f0", lw=2)
        ax.add_patch(rect)
        ax.text(ent['x'] + ent['w'] / 2, ent['y'] + ent['h'] / 2, ent['name'], ha='center', va='center', fontsize=8.5, fontweight='bold')

    # Processes
    processes = [
        {"name": "1.0\nPG Search &\nBooking Process", "x": 42, "y": 80},
        {"name": "2.0\nRoommate\nMatching Engine", "x": 42, "y": 55},
        {"name": "3.0\nCampus Thrift\nMarketplace", "x": 42, "y": 30},
        {"name": "4.0\nOwner & System\nAdministration", "x": 65, "y": 15}
    ]
    for p in processes:
        circle = plt.Circle((p['x'], p['y']), 7, edgecolor="#1e3a8a", facecolor="#dbeafe", lw=2)
        ax.add_patch(circle)
        ax.text(p['x'], p['y'], p['name'], ha='center', va='center', fontsize=7.5, fontweight='bold', color="#1e3a8a")

    # Data stores
    stores = [
        {"name": "D1: tblpg & tblbookpg", "x": 75, "y": 82},
        {"name": "D2: roommate_* Tables", "x": 75, "y": 57},
        {"name": "D3: product (Thrift)", "x": 75, "y": 32},
        {"name": "D4: tblowner & tbladmin", "x": 30, "y": 12}
    ]
    for s in stores:
        ax.plot([s['x'], s['x'] + 22], [s['y'] + 4, s['y'] + 4], color="#334155", lw=1.8)
        ax.plot([s['x'], s['x'] + 22], [s['y'] - 4, s['y'] - 4], color="#334155", lw=1.8)
        ax.text(s['x'] + 11, s['y'], s['name'], ha='center', va='center', fontsize=7.5, fontweight='bold', color="#0f172a")

    # Data flows
    flows = [
        (28, 75, 35, 80, "Search / Booking Req"),
        (49, 80, 75, 82, "Store Booking"),
        (28, 70, 35, 57, "Lifestyle Prefs"),
        (49, 55, 75, 57, "Match Calculations"),
        (28, 65, 35, 33, "Buy / Inquire Item"),
        (49, 30, 75, 32, "List Secondhand Item"),
        (28, 28, 35, 80, "PG Details & Photos"),
        (49, 15, 30, 14, "Owner Verification Data"),
        (82, 53, 49, 81, "Manage Listings"),
        (82, 48, 49, 17, "Approve Owners")
    ]
    for x1, y1, x2, y2, lbl in flows:
        ax.annotate("", xy=(x2, y2), xytext=(x1, y1), arrowprops=dict(arrowstyle="->", lw=1.2, color="#475569"))

    plt.tight_layout()
    fig.savefig(os.path.join(out_dir, "fig_5_4_dfd_level_1.png"), bbox_inches='tight')
    plt.close()

if __name__ == "__main__":
    generate_use_case()
    generate_sequence()
    generate_activity()
    generate_architecture()
    generate_er_diagram()
    generate_dfd()
    print("All 6 professional engineering diagrams generated successfully!")

