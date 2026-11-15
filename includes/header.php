<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>

<style>
    .default-header {
        position: sticky !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        z-index: 9999 !important;
        background: #fff !important;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08) !important;
    }

    .main-menu {
        background: #fff !important;
        padding: 6px 0;
    }

    #logo img {
        max-height: 44px;
        width: auto;
        display: block;
    }

    #nav-menu-container {
        display: flex;
        align-items: center;
    }

    .nav-menu {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .nav-menu > li {
        margin-left: 12px !important;
        float: none !important;
    }

    .nav-menu a {
        padding: 14px 6px !important;
        font-size: 11px !important;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    /* Prevent gap or overlap on pages with banner-area */
    .banner-area {
        margin-top: 0 !important;
    }

    .dropdown-divider {
        border-top: 1px solid rgba(0, 0, 0, 0.17);
        opacity: 1;
        margin-top: 8px;
        margin-bottom: 8px;
    }

    /* Unread badge in nav */
    .nav-unread-badge {
        background: #ff3333;
        color: #fff;
        border-radius: 50%;
        font-size: 10px;
        padding: 2px 5px;
        margin-left: 3px;
        vertical-align: middle;
        font-weight: 700;
        display: inline-block;
    }
</style>
<header class="default-header">
    <div class="main-menu">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div id="logo">
                    <a href="index.php"><img src="img/pglogo.png" alt="" title="" /></a>
                </div>
                <nav id="nav-menu-container">
                    <ul class="nav-menu">
                        <li class="menu-active"><a href="index.php">home</a></li>
                        <li><a href="pg.php">Find PG</a></li>
                        <li class="menu-has-children">
                            <a href="find-roommate.php">Find Roommate</a>
                            <ul>
                                <li><a href="find-roommate.php">Browse Roommates</a></li>
                                <li><a href="have-room.php">I Have a Room</a></li>
                                <li><a href="need-room.php">I Need a Room</a></li>
                            </ul>
                        </li>
                        <li><a href="about-us.php">about</a></li>

                        <?php
                        if (empty($_SESSION['pgasuid'])) { ?>
                            <li><a href="owner/login.php">Owner</a></li>
                            <li><a href="register.php">register</a></li>
                            <li><a href="signin.php">Login</a></li>
                        <?php } ?>

                        <li><a href="./resellNation">Urban Nation</a></li>
                        <?php if (!empty($_SESSION['pgasuid'])) {
                            // Get unread message count for nav badge
                            $nav_uid = (int)$_SESSION['pgasuid'];
                            $nav_unread = 0;
                            if (function_exists('getUnreadMessageCount')) {
                                $nav_unread = getUnreadMessageCount($con, $nav_uid);
                            }
                        ?>
                            <li><a href="my-matches.php">My Matches</a></li>
                            <li><a href="messages.php">Messages<?php if ($nav_unread > 0): ?><span class="nav-unread-badge"><?= $nav_unread ?></span><?php endif; ?></a></li>
                            <li class="menu-has-children"><a href="">My Account</a>
                                <ul>
                                    <li><a href="user-profile.php">My Profile</a></li>
                                    <li><a href="roommate-profile.php">Roommate Profile</a></li>
                                    <li><a href="my-bookings.php">My Bookings</a></li>
                                    <div class="dropdown-divider"></div>
                                    <li><a href="change-password.php">Change Password</a></li>
                                    <li><a href="logout.php">Logout</a></li>
                                </ul>
                            </li>
                        <?php } ?>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>

<script>
    function cToast(msg) {
        iziToast.info({
            title: msg,
            message: '',
        });
    }
</script>
