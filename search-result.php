<?php
// Search now lives on pg.php; keep this URL working for old links and forms.
$query = array_intersect_key($_REQUEST, array_flip(['location', 'type', 'budget', 'q', 'pgname']));
if (isset($query['pgname'])) {
	$query['q'] = $query['pgname'];
	unset($query['pgname']);
}
header('Location: pg.php' . ($query ? '?' . http_build_query($query) : ''));
exit;
