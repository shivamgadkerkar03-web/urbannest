<?php
// Ensure session is active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<style>
    .un-header {
        position: sticky !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        z-index: 1050 !important;
        background: #ffffff !important;
        box-shadow: 0 2px 14px rgba(0, 0, 0, 0.08) !important;
    }

    .un-header .navbar-brand img {
        max-height: 44px;
        width: auto;
    }

    .un-header .nav-link {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #2b2b2b !important;
        padding: 12px 10px !important;
        transition: color 0.2s ease;
    }

    .un-header .nav-link:hover,
    .un-header .nav-link.active {
        color: #ff3333 !important;
    }

    .un-header .badge-nation {
        background: #ff3333;
        color: #fff;
        font-size: 10px;
        padding: 3px 6px;
        border-radius: 4px;
        margin-left: 4px;
        vertical-align: middle;
    }

    .un-header .btn-sell {
        background: #ff3333;
        color: #fff !important;
        border: none;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        padding: 8px 18px;
        transition: background 0.2s;
        text-decoration: none;
    }

    .un-header .btn-sell:hover {
        background: #cc0000;
        color: #fff !important;
    }

    .un-header .dropdown-menu {
        font-size: 13px;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 8px 0;
    }

    .un-header .dropdown-item {
        padding: 8px 16px;
        font-weight: 500;
    }

    .un-header .dropdown-item:hover {
        background: #fff5f5;
        color: #ff3333;
    }
</style>

<header class="un-header">
    <nav class="navbar navbar-expand-lg bg-white py-2">
        <div class="container-fluid px-lg-4">
            <a class="navbar-brand d-flex align-items-center" href="./index.php">
                <img src="../img/pglogo.png" alt="UrbanNest Logo">
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#unNavbar" aria-controls="unNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="unNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pg.php">Find PG</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Find Roommate
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../find-roommate.php">Browse Roommates</a></li>
                            <li><a class="dropdown-item" href="../have-room.php">I Have a Room</a></li>
                            <li><a class="dropdown-item" href="../need-room.php">I Need a Room</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="./index.php">
                            Urban Nation <span class="badge-nation">Thrift</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search.php">Browse Store</a>
                    </li>

                    <?php if (empty($_SESSION['pgasuid'])) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../owner/login.php">Owner</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../signin.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../register.php">Register</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                My Account
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="../user-profile.php">My Profile</a></li>
                                <li><a class="dropdown-item" href="./myadd.php">My Listed Products</a></li>
                                <li><a class="dropdown-item" href="../my-matches.php">My Roommate Matches</a></li>
                                <li><a class="dropdown-item" href="../my-bookings.php">My PG Bookings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="../change-password.php">Change Password</a></li>
                                <li><a class="dropdown-item text-danger" href="../logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php } ?>

                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a href="seller.php" class="btn-sell d-inline-flex align-items-center">
                            <span class="me-1" style="font-size:14px;">+</span> Sell Item
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
