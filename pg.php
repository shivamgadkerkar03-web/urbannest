<?php
require_once __DIR__ . '/includes/security.php';
include ('includes/dbconnection.php');
require_once __DIR__ . '/includes/pg-card.php';

$filterState  = trim((string) ($_GET['location'] ?? ''));
$filterType   = trim((string) ($_GET['type'] ?? ''));
$filterBudget = (int) ($_GET['budget'] ?? 0);
$filterName   = trim((string) ($_GET['q'] ?? ''));
if (!in_array($filterType, ['', 'Boys', 'Girls', 'Both'], true)) {
	$filterType = '';
}

$sql = "SELECT * FROM tblpg WHERE IsActive = 1";
$types = '';
$params = [];
if ($filterState !== '') {
	$sql .= " AND StateName = ?";
	$types .= 's';
	$params[] = $filterState;
}
if ($filterType !== '') {
	$sql .= " AND Type = ?";
	$types .= 's';
	$params[] = $filterType;
}
if ($filterBudget > 0) {
	$sql .= " AND CAST(RPmonth AS UNSIGNED) <= ?";
	$types .= 'i';
	$params[] = $filterBudget;
}
if ($filterName !== '') {
	$sql .= " AND (PGTitle LIKE ? OR CityName LIKE ? OR Address LIKE ?)";
	$like = '%' . addcslashes($filterName, '%_\\') . '%';
	$types .= 'sss';
	array_push($params, $like, $like, $like);
}
$sql .= " ORDER BY ID DESC";

$stmt = mysqli_prepare($con, $sql);
if ($types !== '') {
	mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$listings = mysqli_stmt_get_result($stmt);
$total = mysqli_num_rows($listings);

$states = mysqli_query($con, "SELECT StateName FROM tblstate ORDER BY StateName");
$isFiltered = $filterState !== '' || $filterType !== '' || $filterBudget > 0 || $filterName !== '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include __DIR__ . '/includes/head.php'; ?>
	<title>Find a PG | UrbanNest</title>
</head>

<body>

	<?php include_once('includes/header.php'); ?>

	<section class="banner-area relative">
		<div class="overlay overlay-bg"></div>
		<div class="container">
			<div class="row text-center justify-content-center">
				<div class="about-content col-lg-12">
					<p class="text-white link-nav"><a href="index.php">Home</a>
						<span class="lnr lnr-arrow-right"></span> <span>PG Accommodation</span></p>
					<h1 class="text-white">PG Accommodation</h1>
				</div>
			</div>
		</div>
	</section>

	<section class="section-gap">
		<div class="container">
			<form class="un-card mb-4" method="get" action="pg.php">
				<div class="row g-3 align-items-end">
					<div class="col-lg-3 col-md-6">
						<label class="form-label" for="q">Search</label>
						<input id="q" type="text" name="q" class="form-control" maxlength="100"
							placeholder="PG name, city or area" value="<?= h($filterName) ?>">
					</div>
					<div class="col-lg-3 col-md-6">
						<label class="form-label" for="location">State</label>
						<select id="location" name="location" class="form-select">
							<option value="">Any state</option>
							<?php while ($state = mysqli_fetch_assoc($states)) { ?>
								<option value="<?= h($state['StateName']) ?>" <?= $filterState === $state['StateName'] ? 'selected' : '' ?>><?= h($state['StateName']) ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-lg-2 col-6">
						<label class="form-label" for="type">PG for</label>
						<select id="type" name="type" class="form-select">
							<option value="">Anyone</option>
							<?php foreach (['Boys', 'Girls', 'Both'] as $t) { ?>
								<option value="<?= $t ?>" <?= $filterType === $t ? 'selected' : '' ?>><?= $t ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-lg-2 col-6">
						<label class="form-label" for="budget">Max rent (&#8377;)</label>
						<input id="budget" type="number" name="budget" class="form-control" min="0" step="500"
							value="<?= $filterBudget > 0 ? $filterBudget : '' ?>" placeholder="Any">
					</div>
					<div class="col-lg-2 d-flex gap-2">
						<button class="btn btn-primary flex-fill" type="submit">Filter</button>
						<?php if ($isFiltered) { ?><a class="btn btn-light" href="pg.php" title="Clear filters">&times;</a><?php } ?>
					</div>
				</div>
			</form>

			<p class="text-muted mb-3"><?= $total ?> PG<?= $total === 1 ? '' : 's' ?> found<?= $filterState !== '' ? ' in ' . h($filterState) : '' ?></p>

			<div class="row g-4">
				<?php if ($total > 0) {
					while ($row = mysqli_fetch_assoc($listings)) { ?>
						<div class="col-lg-4 col-md-6"><?php render_pg_card($row); ?></div>
					<?php }
				} else { ?>
					<div class="col-12">
						<div class="empty-state un-card">
							<span class="lnr lnr-apartment"></span>
							No PGs match your search.
							<?php if ($isFiltered) { ?><div class="mt-2"><a href="pg.php">Clear filters</a></div><?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>

	<?php include_once('includes/footer.php'); ?>
</body>

</html>
