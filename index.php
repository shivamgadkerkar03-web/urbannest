<?php
require_once __DIR__ . '/includes/security.php';
include ('includes/dbconnection.php');
require_once __DIR__ . '/includes/pg-card.php';

$states = mysqli_query($con, "SELECT StateName FROM tblstate ORDER BY StateName");
$featured = mysqli_query($con, "SELECT * FROM tblpg WHERE IsActive = 1 ORDER BY ID DESC LIMIT 6");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include __DIR__ . '/includes/head.php'; ?>
	<title>UrbanNest | PG Accommodation, Roommates &amp; Thrift</title>
	<style>
		.hero {
			position: relative;
		}

		.hero .carousel-item {
			height: clamp(420px, 72vh, 680px);
		}

		.hero .carousel-item img {
			width: 100%;
			height: 100%;
			object-fit: cover;
		}

		.hero .carousel-item::after {
			content: "";
			position: absolute;
			inset: 0;
			background: linear-gradient(180deg, rgba(20, 24, 36, .35) 0%, rgba(20, 24, 36, .7) 100%);
		}

		.hero .carousel-caption {
			z-index: 2;
			left: 0;
			right: 0;
			top: 50%;
			bottom: auto;
			transform: translateY(-60%);
			padding: 0 20px;
		}

		.hero h1 {
			color: #fff;
			font-size: clamp(28px, 5vw, 54px);
			font-weight: 700;
			max-width: 820px;
			margin: 0 auto 14px;
			line-height: 1.15;
		}

		.hero p {
			color: rgba(255, 255, 255, .88);
			font-size: clamp(15px, 1.6vw, 18px);
			max-width: 620px;
			margin: 0 auto 26px;
		}

		.search-panel {
			position: relative;
			z-index: 5;
			margin-top: -70px;
		}

		.search-panel .un-card {
			padding: 22px 24px;
		}

		.search-panel h2 {
			font-size: 18px;
			margin-bottom: 14px;
		}

		.service-card {
			height: 100%;
			padding: 28px 24px;
			border-radius: var(--un-radius);
			border: 1px solid var(--un-line);
			background: #fff;
			transition: box-shadow .2s ease, transform .2s ease;
		}

		.service-card:hover {
			box-shadow: var(--un-shadow);
			transform: translateY(-3px);
		}

		.service-card .icon {
			width: 52px;
			height: 52px;
			display: grid;
			place-items: center;
			border-radius: 14px;
			background: var(--un-red-soft);
			color: var(--un-red);
			font-size: 24px;
			margin-bottom: 16px;
		}

		.service-card h3 {
			font-size: 19px;
		}

		.service-card p {
			color: var(--un-muted);
			margin-bottom: 14px;
		}

		.owner-cta {
			background: linear-gradient(120deg, #1d2433 0%, #2b3550 100%);
			border-radius: 18px;
			padding: 44px 40px;
			color: rgba(255, 255, 255, .8);
		}

		@media (max-width: 767.98px) {
			.hero .carousel-control-prev,
			.hero .carousel-control-next {
				display: none;
			}
		}

		.owner-cta h2 {
			color: #fff;
			font-size: clamp(22px, 3vw, 30px);
		}
	</style>
</head>

<body>

	<?php include_once ('includes/header.php'); ?>

	<section class="hero">
		<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
			<div class="carousel-indicators">
				<button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
				<button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
				<button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
			</div>
			<div class="carousel-inner">
				<div class="carousel-item active">
					<img src="img/banner-bg.jpg" alt="City skyline">
					<div class="carousel-caption">
						<h1>Find Your Perfect PG Accommodation</h1>
						<p>Browse verified PGs, compare facilities and rent, and send a booking request in minutes.</p>
						<a href="pg.php" class="btn-explore">Explore PGs</a>
					</div>
				</div>
				<div class="carousel-item">
					<img src="img/friends.jpeg" alt="Friends sharing a flat">
					<div class="carousel-caption">
						<h1>Meet Roommates Who Match Your Lifestyle</h1>
						<p>Our compatibility score compares sleep schedules, cleanliness, food habits and more.</p>
						<a href="find-roommate.php" class="btn-explore">Find a Roommate</a>
					</div>
				</div>
				<div class="carousel-item">
					<img src="img/store.jpeg" alt="Second-hand furniture store">
					<div class="carousel-caption">
						<h1>Upgrade Your Space with Second-hand Finds</h1>
						<p>Buy and sell pre-loved furniture and essentials with fellow residents on Urban Nation.</p>
						<a href="resellNation/" class="btn-explore">Visit Urban Nation</a>
					</div>
				</div>
			</div>
			<button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Previous</span>
			</button>
			<button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Next</span>
			</button>
		</div>
	</section>

	<section class="search-panel">
		<div class="container">
			<div class="un-card shadow">
				<h2>Search PG accommodation</h2>
				<form class="row g-3 align-items-end" method="get" action="pg.php">
					<div class="col-md-4">
						<label class="form-label" for="searchState">State</label>
						<select id="searchState" name="location" class="form-select">
							<option value="">Any state</option>
							<?php while ($state = mysqli_fetch_assoc($states)) { ?>
								<option value="<?= h($state['StateName']) ?>"><?= h($state['StateName']) ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-3 col-6">
						<label class="form-label" for="searchType">PG for</label>
						<select id="searchType" name="type" class="form-select">
							<option value="">Anyone</option>
							<option value="Boys">Boys</option>
							<option value="Girls">Girls</option>
							<option value="Both">Both</option>
						</select>
					</div>
					<div class="col-md-3 col-6">
						<label class="form-label" for="searchBudget">Max rent (&#8377;)</label>
						<input id="searchBudget" type="number" name="budget" class="form-control" min="0" step="500" placeholder="e.g. 8000">
					</div>
					<div class="col-md-2 d-grid">
						<button class="btn btn-primary" type="submit"><span class="lnr lnr-magnifier me-1"></span> Search</button>
					</div>
				</form>
			</div>
		</div>
	</section>

	<section class="section-gap">
		<div class="container">
			<div class="row g-4">
				<div class="col-md-4">
					<div class="service-card">
						<div class="icon"><span class="lnr lnr-apartment"></span></div>
						<h3>PG Accommodation</h3>
						<p>Compare rent, meals and facilities across approved PGs and book directly with the owner.</p>
						<a href="pg.php">Browse PGs <span class="lnr lnr-arrow-right"></span></a>
					</div>
				</div>
				<div class="col-md-4">
					<div class="service-card">
						<div class="icon"><span class="lnr lnr-users"></span></div>
						<h3>Roommate Matching</h3>
						<p>Post a room or a requirement and get ranked matches based on lifestyle compatibility.</p>
						<a href="find-roommate.php">Find roommates <span class="lnr lnr-arrow-right"></span></a>
					</div>
				</div>
				<div class="col-md-4">
					<div class="service-card">
						<div class="icon"><span class="lnr lnr-cart"></span></div>
						<h3>Urban Nation Thrift</h3>
						<p>Sell what you no longer need and pick up affordable essentials from people nearby.</p>
						<a href="resellNation/">Shop thrift <span class="lnr lnr-arrow-right"></span></a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="pb-5">
		<div class="container">
			<div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-4">
				<div class="header-text">
					<h1 class="mb-1">Latest PG listings</h1>
					<p class="mb-0">Freshly approved accommodation across states and cities.</p>
				</div>
				<a class="btn btn-outline-primary" href="pg.php">View all</a>
			</div>
			<div class="row g-4">
				<?php if ($featured && mysqli_num_rows($featured) > 0) {
					while ($row = mysqli_fetch_assoc($featured)) { ?>
						<div class="col-lg-4 col-md-6"><?php render_pg_card($row); ?></div>
					<?php }
				} else { ?>
					<div class="col-12">
						<div class="empty-state"><span class="lnr lnr-apartment"></span>No PG listings yet. Please check back soon.</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>

	<section class="pb-5 mb-4">
		<div class="container">
			<div class="owner-cta d-flex flex-wrap justify-content-between align-items-center gap-3">
				<div>
					<h2 class="mb-1">Own a PG?</h2>
					<p class="mb-0">List your property for free and receive booking requests from verified tenants.</p>
				</div>
				<a class="btn-explore" href="owner/owner-registration.php">List your PG</a>
			</div>
		</div>
	</section>

	<?php include_once ('includes/footer.php'); ?>
</body>

</html>
