import os
import sys
import time
import requests
from selenium import webdriver
from selenium.webdriver.chrome.options import Options

sys.stdout.reconfigure(line_buffering=True)

def create_driver():
    options = Options()
    options.add_argument("--headless=new")
    options.add_argument("--disable-gpu")
    options.add_argument("--window-size=1280,850")
    options.add_argument("--no-sandbox")
    options.add_argument("--disable-dev-shm-usage")
    options.page_load_strategy = "eager"
    driver = webdriver.Chrome(options=options)
    driver.set_window_size(1280, 850)
    driver.set_page_load_timeout(12)
    return driver

def get_tenant_session():
    s = requests.Session()
    s.post("http://localhost:8000/signin.php", data={"email": "shivam@gmail.com", "password": "Test@123", "login": "login"}, allow_redirects=False)
    return s.cookies.get("PHPSESSID")

def get_owner_session():
    s = requests.Session()
    s.post("http://localhost:8000/owner/login.php", data={"email": "tejasnaik@gmail.com", "password": "Test@123", "login": "Sign In"}, allow_redirects=False)
    return s.cookies.get("PHPSESSID")

def get_admin_session():
    s = requests.Session()
    s.post("http://localhost:8000/admin/login.php", data={"username": "admin", "password": "Test@123", "login": "Sign In"}, allow_redirects=False)
    return s.cookies.get("PHPSESSID")

def set_cookie(driver, sess_id):
    driver.delete_all_cookies()
    driver.add_cookie({"name": "PHPSESSID", "value": sess_id, "path": "/"})

def capture(driver, url, filepath, delay=1.0):
    try:
        driver.get(url)
    except Exception as e:
        print(f"Warning: load timeout on {url}: {e}")
    time.sleep(delay)
    driver.save_screenshot(filepath)
    print(f"Captured: {os.path.basename(filepath)} ({os.path.getsize(filepath)} bytes)")

def main():
    out_dir = os.path.abspath("report_assets/screenshots")
    os.makedirs(out_dir, exist_ok=True)
    driver = create_driver()
    
    try:
        driver.get("http://localhost:8000/index.php")
        time.sleep(0.5)

        # 1. Public Pages
        capture(driver, "http://localhost:8000/index.php", os.path.join(out_dir, "01_homepage.png"))
        capture(driver, "http://localhost:8000/signin.php", os.path.join(out_dir, "02_user_login.png"))
        capture(driver, "http://localhost:8000/register.php", os.path.join(out_dir, "03_user_register.png"))
        capture(driver, "http://localhost:8000/pg.php", os.path.join(out_dir, "04_pg_directory.png"))
        capture(driver, "http://localhost:8000/pg-details.php?id=27", os.path.join(out_dir, "05_pg_details.png"))
        capture(driver, "http://localhost:8000/resellNation/index.php", os.path.join(out_dir, "06_thrift_store.png"))
        capture(driver, "http://localhost:8000/resellNation/search.php", os.path.join(out_dir, "07_thrift_search.png"))

        # 2. Tenant Pages
        tenant_sess = get_tenant_session()
        set_cookie(driver, tenant_sess)
        capture(driver, "http://localhost:8000/find-roommate.php", os.path.join(out_dir, "08_roommate_hub.png"))
        capture(driver, "http://localhost:8000/my-matches.php", os.path.join(out_dir, "09_roommate_matches.png"))
        capture(driver, "http://localhost:8000/need-room.php", os.path.join(out_dir, "10_need_room.png"))
        capture(driver, "http://localhost:8000/have-room.php", os.path.join(out_dir, "11_have_room.png"))
        capture(driver, "http://localhost:8000/chat.php?user=28", os.path.join(out_dir, "12_roommate_chat.png"))
        capture(driver, "http://localhost:8000/roommate-profile.php", os.path.join(out_dir, "13_roommate_profile.png"))
        capture(driver, "http://localhost:8000/resellNation/seller.php", os.path.join(out_dir, "14_thrift_sell.png"))

        # 3. Owner Pages
        driver.delete_all_cookies()
        capture(driver, "http://localhost:8000/owner/login.php", os.path.join(out_dir, "15_owner_login.png"))
        owner_sess = get_owner_session()
        set_cookie(driver, owner_sess)
        capture(driver, "http://localhost:8000/owner/dashboard.php", os.path.join(out_dir, "16_owner_dashboard.png"))
        capture(driver, "http://localhost:8000/owner/manage-pgdetails.php", os.path.join(out_dir, "17_owner_manage_pg.png"))
        capture(driver, "http://localhost:8000/owner/all-bookings.php", os.path.join(out_dir, "18_owner_bookings.png"))

        # 4. Admin Pages
        driver.delete_all_cookies()
        capture(driver, "http://localhost:8000/admin/login.php", os.path.join(out_dir, "19_admin_login.png"))
        admin_sess = get_admin_session()
        set_cookie(driver, admin_sess)
        capture(driver, "http://localhost:8000/admin/dashboard.php", os.path.join(out_dir, "20_admin_dashboard.png"))
        capture(driver, "http://localhost:8000/admin/manage-pgdetails.php", os.path.join(out_dir, "21_admin_manage_pg.png"))
        capture(driver, "http://localhost:8000/admin/manage-ownerdetails.php", os.path.join(out_dir, "22_admin_manage_owners.png"))
        capture(driver, "http://localhost:8000/admin/user-bookings.php", os.path.join(out_dir, "23_admin_bookings.png"))

        print("\nAll 23 actual application screenshots captured successfully!")

    finally:
        driver.quit()

if __name__ == "__main__":
    main()
