<?php
require_once __DIR__ . '/security.php';
if (!isset($con) || !($con instanceof mysqli)) {
	include __DIR__ . '/dbconnection.php';
}

$footerPages = ['aboutus' => null, 'contactus' => null];
$footerRes = mysqli_query($con, "SELECT PageType, PageTitle, PageDescription FROM tblpages WHERE PageType IN ('aboutus', 'contactus')");
if ($footerRes) {
	while ($footerRow = mysqli_fetch_assoc($footerRes)) {
		$footerPages[$footerRow['PageType']] = $footerRow;
	}
}
?>
<footer class="site-footer">
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-5 col-md-12">
				<a class="footer-brand d-inline-block" href="index.php"><img src="img/pglogo.png" alt="UrbanNest"></a>
				<div class="footer-about">
					<?php if (!empty($footerPages['aboutus']['PageDescription'])) { ?>
						<?php // Admin-managed content from the CMS editor ?>
						<?= $footerPages['aboutus']['PageDescription'] ?>
					<?php } else { ?>
						<p>UrbanNest helps students and working professionals find PG accommodation, compatible roommates and pre-loved essentials in one place.</p>
					<?php } ?>
				</div>
			</div>

			<div class="col-lg-3 col-md-6 col-6">
				<h6>Explore</h6>
				<ul class="footer-links">
					<li><a href="pg.php">Find a PG</a></li>
					<li><a href="find-roommate.php">Find a Roommate</a></li>
					<li><a href="resellNation/">Urban Nation Store</a></li>
					<li><a href="owner/login.php">List Your PG</a></li>
					<li><a href="about-us.php">About Us</a></li>
				</ul>
			</div>

			<div class="col-lg-4 col-md-6 col-6">
				<h6><?= h($footerPages['contactus']['PageTitle'] ?? 'Contact Us') ?></h6>
				<div class="footer-contact">
					<?php if (!empty($footerPages['contactus']['PageDescription'])) { ?>
						<?= $footerPages['contactus']['PageDescription'] ?>
					<?php } ?>
				</div>
				<a class="d-inline-block mt-2" href="contact.php">Send us a message <span class="lnr lnr-arrow-right"></span></a>
			</div>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="container d-flex flex-wrap justify-content-between gap-2">
			<span>&copy; <?= date('Y') ?> UrbanNest. All rights reserved.</span>
			<span>PG accommodation &middot; Roommates &middot; Thrift</span>
		</div>
	</div>
</footer>
