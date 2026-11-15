<?php
// Bookings are handled on the PG details page so every booking goes through the same checks.
require_once __DIR__ . '/includes/security.php';

$pid = (int) ($_REQUEST['pid'] ?? $_REQUEST['id'] ?? 0);
redirect_to($pid > 0 ? 'pg-details.php?pid=' . $pid : 'pg.php');
