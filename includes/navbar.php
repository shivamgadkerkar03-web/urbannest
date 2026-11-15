<?php
/**
 * Site-wide navigation bar, shared by the main site and Urban Nation.
 *
 * Set $navBase before including when the page lives in a sub-folder
 * (e.g. '../' from resellNation/). Requires css/navbar.css.
 */
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/roommate-functions.php';

$navBase = $navBase ?? '';
$navScript = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$navPage = basename($navScript);
$navInStore = strpos($navScript, '/resellNation/') !== false;
$navUserId = current_user_id();
$navLoggedIn = $navUserId > 0;

$navUnread = 0;
$navStoreUnread = 0;
if ($navLoggedIn && isset($con) && $con instanceof mysqli) {
    if (function_exists('getUnreadMessageCount')) {
        $navUnread = (int) getUnreadMessageCount($con, $navUserId);
    }
    $marketHelpers = __DIR__ . '/../resellNation/includes/market.php';
    if (!function_exists('un_unread_total') && is_file($marketHelpers)) {
        require_once $marketHelpers;
    }
    if (function_exists('un_unread_total')) {
        $navStoreUnread = (int) un_unread_total($con, $navUserId);
    }
}

$navName = trim((string) ($_SESSION['FullName'] ?? ''));
$navFirst = $navName !== '' ? explode(' ', $navName)[0] : 'Account';
$navInitials = '';
foreach (array_slice(preg_split('/\s+/', $navName ?: 'U'), 0, 2) as $part) {
    $navInitials .= mb_strtoupper(mb_substr($part, 0, 1));
}

$navUrl = function (string $path) use ($navBase): string {
    return h($navBase . $path);
};
$navIs = function (array $pages, bool $store = false) use ($navPage, $navInStore): bool {
    return $navInStore === $store && in_array($navPage, $pages, true);
};
$navCls = function (bool $active, string $base = 'un-nav__link'): string {
    return $base . ($active ? ' is-active' : '');
};

$navActiveHome      = $navIs(['index.php', '']);
$navActivePg        = $navIs(['pg.php', 'pg-details.php', 'search-result.php']);
$navActiveRoommates = $navIs(['find-roommate.php', 'have-room.php', 'need-room.php', 'roommate-details.php', 'my-matches.php']);
$navActiveStore     = $navInStore;
$navActiveAbout     = $navIs(['about-us.php']);
$navActiveContact   = $navIs(['contact.php']);
$navActiveMessages  = $navIs(['messages.php', 'chat.php']);
$navActiveAccount   = $navIs(['user-profile.php', 'roommate-profile.php', 'my-bookings.php', 'booking-details.php', 'change-password.php']);
?>
<header class="un-nav" id="siteHeader">
    <nav class="navbar navbar-expand-lg" aria-label="Main navigation">
        <div class="container">
            <a class="un-nav__brand" href="<?= $navUrl('index.php') ?>" aria-label="UrbanNest home">
                <img src="<?= $navUrl('img/pglogo.png') ?>" alt="UrbanNest">
            </a>

            <div class="un-nav__mobile-actions d-lg-none">
                <?php if ($navLoggedIn) { ?>
                    <a class="un-nav__icon-btn" href="<?= $navUrl('messages.php') ?>" aria-label="Messages">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a8 8 0 0 1-11.8 7L4 20l1.1-4.6A8 8 0 1 1 21 12Z"/></svg>
                        <?php if ($navUnread > 0) { ?><span class="un-nav__dot"><?= $navUnread > 9 ? '9+' : $navUnread ?></span><?php } ?>
                    </a>
                <?php } ?>
                <button class="un-nav__toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNav"
                    aria-controls="siteNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span></span><span></span><span></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="siteNav">
                <ul class="un-nav__links">
                    <li><a class="<?= $navCls($navActiveHome) ?>" href="<?= $navUrl('index.php') ?>">Home</a></li>
                    <li><a class="<?= $navCls($navActivePg) ?>" href="<?= $navUrl('pg.php') ?>">Find a PG</a></li>
                    <li class="dropdown">
                        <a class="<?= $navCls($navActiveRoommates) ?> dropdown-toggle" href="<?= $navUrl('find-roommate.php') ?>"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">Roommates</a>
                        <div class="dropdown-menu un-nav__menu">
                            <a class="dropdown-item<?= $navIs(['find-roommate.php', 'roommate-details.php']) ? ' active' : '' ?>" href="<?= $navUrl('find-roommate.php') ?>">
                                <strong>Browse roommates</strong><small>Listings and room seekers near you</small>
                            </a>
                            <a class="dropdown-item<?= $navIs(['have-room.php']) ? ' active' : '' ?>" href="<?= $navUrl('have-room.php') ?>">
                                <strong>I have a room</strong><small>Post your room and find a flatmate</small>
                            </a>
                            <a class="dropdown-item<?= $navIs(['need-room.php']) ? ' active' : '' ?>" href="<?= $navUrl('need-room.php') ?>">
                                <strong>I need a room</strong><small>Tell people what you are looking for</small>
                            </a>
                            <?php if ($navLoggedIn) { ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item<?= $navIs(['my-matches.php']) ? ' active' : '' ?>" href="<?= $navUrl('my-matches.php') ?>">
                                    <strong>My matches</strong><small>Requests, connections and listings</small>
                                </a>
                            <?php } ?>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a class="<?= $navCls($navActiveStore) ?> dropdown-toggle" href="<?= $navUrl('resellNation/') ?>"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Urban Nation<?php if ($navStoreUnread > 0) { ?><span class="un-nav__count"><?= $navStoreUnread ?></span><?php } ?>
                        </a>
                        <div class="dropdown-menu un-nav__menu">
                            <a class="dropdown-item<?= $navIs(['index.php'], true) ? ' active' : '' ?>" href="<?= $navUrl('resellNation/') ?>">
                                <strong>Thrift store</strong><small>Pre-loved furniture and essentials</small>
                            </a>
                            <a class="dropdown-item<?= $navIs(['search.php', 'product.php'], true) ? ' active' : '' ?>" href="<?= $navUrl('resellNation/search.php') ?>">
                                <strong>Browse items<?php if ($navStoreUnread > 0) { ?> &amp; chats<?php } ?></strong><small>Search listings and message sellers</small>
                            </a>
                            <a class="dropdown-item<?= $navIs(['seller.php'], true) ? ' active' : '' ?>" href="<?= $navUrl('resellNation/seller.php') ?>">
                                <strong>Sell an item</strong><small>List something in a minute</small>
                            </a>
                            <?php if ($navLoggedIn) { ?>
                                <a class="dropdown-item<?= $navIs(['myadd.php'], true) ? ' active' : '' ?>" href="<?= $navUrl('resellNation/myadd.php') ?>">
                                    <strong>My listings</strong><small>Edit or remove your ads</small>
                                </a>
                            <?php } ?>
                        </div>
                    </li>
                    <li><a class="<?= $navCls($navActiveAbout) ?>" href="<?= $navUrl('about-us.php') ?>">About</a></li>
                    <li><a class="<?= $navCls($navActiveContact) ?>" href="<?= $navUrl('contact.php') ?>">Contact</a></li>
                </ul>

                <div class="un-nav__actions">
                    <?php if ($navLoggedIn) { ?>
                        <a class="un-nav__icon-btn d-none d-lg-inline-flex<?= $navActiveMessages ? ' is-active' : '' ?>" href="<?= $navUrl('messages.php') ?>"
                            aria-label="Messages" title="Messages">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a8 8 0 0 1-11.8 7L4 20l1.1-4.6A8 8 0 1 1 21 12Z"/></svg>
                            <?php if ($navUnread > 0) { ?><span class="un-nav__dot"><?= $navUnread > 9 ? '9+' : $navUnread ?></span><?php } ?>
                        </a>
                        <div class="dropdown">
                            <button class="un-nav__account<?= $navActiveAccount ? ' is-active' : '' ?>" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="un-nav__avatar"><?= h($navInitials) ?></span>
                                <span class="un-nav__name"><?= h($navFirst) ?></span>
                                <svg class="un-nav__chev" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end un-nav__menu un-nav__menu--account">
                                <div class="un-nav__menu-head">
                                    <span class="un-nav__avatar un-nav__avatar--lg"><?= h($navInitials) ?></span>
                                    <div><strong><?= h($navName ?: 'My account') ?></strong><small>Signed in</small></div>
                                </div>
                                <a class="dropdown-item<?= $navIs(['user-profile.php']) ? ' active' : '' ?>" href="<?= $navUrl('user-profile.php') ?>">My profile</a>
                                <a class="dropdown-item<?= $navIs(['my-bookings.php', 'booking-details.php']) ? ' active' : '' ?>" href="<?= $navUrl('my-bookings.php') ?>">My PG bookings</a>
                                <a class="dropdown-item<?= $navIs(['roommate-profile.php']) ? ' active' : '' ?>" href="<?= $navUrl('roommate-profile.php') ?>">Roommate profile</a>
                                <a class="dropdown-item<?= $navIs(['my-matches.php']) ? ' active' : '' ?>" href="<?= $navUrl('my-matches.php') ?>">My matches</a>
                                <a class="dropdown-item<?= $navIs(['myadd.php'], true) ? ' active' : '' ?>" href="<?= $navUrl('resellNation/myadd.php') ?>">My store listings</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item<?= $navIs(['change-password.php']) ? ' active' : '' ?>" href="<?= $navUrl('change-password.php') ?>">Change password</a>
                                <a class="dropdown-item un-nav__logout" href="<?= $navUrl('logout.php') ?>">Log out</a>
                            </div>
                        </div>
                    <?php } else { ?>
                        <a class="un-nav__owner" href="<?= $navUrl('owner/login.php') ?>">For PG owners</a>
                        <a class="un-nav__btn un-nav__btn--ghost<?= $navIs(['signin.php']) ? ' is-active' : '' ?>" href="<?= $navUrl('signin.php') ?>">Log in</a>
                        <a class="un-nav__btn un-nav__btn--solid" href="<?= $navUrl('register.php') ?>">Sign up</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>
</header>
<script>
    (function () {
        var header = document.getElementById('siteHeader');
        if (!header) {
            return;
        }
        var onScroll = function () {
            header.classList.toggle('is-scrolled', window.scrollY > 8);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    })();

    function cToast(msg) {
        if (window.iziToast) {
            iziToast.info({ title: msg, message: '', position: 'topRight' });
        } else {
            alert(msg);
        }
    }
</script>
