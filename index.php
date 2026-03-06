<?php include 'includes/config.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<?php
// Fetch stock
$stock = [];
$res = mysqli_query($conn, "SELECT blood_group, units_available FROM blood_stock ORDER BY blood_group");
if ($res) {
	while ($r = mysqli_fetch_assoc($res)) { $stock[] = $r; }
}

// Stats
$totalDonors = 0; $totalRequests = 0; $availableUnits = 0;
$r = mysqli_query($conn, "SELECT COUNT(*) as c FROM users"); if ($r) { $row = mysqli_fetch_assoc($r); $totalDonors = intval($row['c']); }
$r = mysqli_query($conn, "SELECT COUNT(*) as c FROM requests"); if ($r) { $row = mysqli_fetch_assoc($r); $totalRequests = intval($row['c']); }
$r = mysqli_query($conn, "SELECT SUM(units_available) as s FROM blood_stock"); if ($r) { $row = mysqli_fetch_assoc($r); $availableUnits = intval($row['s']); }
?>

<div class="container">
	<section class="hero fade-in">
		<div class="container">
			<div class="hero-inner">
				<div>
					<h1>Donate Blood, Save Lives</h1>
					<p>Join our community of donors and help patients in critical need. Your single donation can save multiple lives.</p>
					<div class="cta">
						<a class="btn-cta" href="/bloodbank/register.php">Donate Now</a>
						<a class="btn-cta alt" href="/bloodbank/request.php">Request Blood</a>
					</div>
				</div>
				<div style="flex:1">
					<div class="form-card">
						<h4 style="margin-top:0">Quick Request</h4>
						<form method="get" action="request.php">
							<div class="field"><input placeholder=" " name="blood_group"><label>Blood Group (e.g. A+)</label></div>
							<div class="field" style="margin-top:10px"><input placeholder=" " name="units" type="number"><label>Units</label></div>
							<div style="margin-top:12px"><button class="btn-primary">Search Availability</button></div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

	<h3 style="margin-top:18px">Available Blood Stock</h3>
	<div class="stock-grid">
		<?php if (empty($stock)): ?>
			<div class="stock-card">No stock data available.</div>
		<?php else: foreach($stock as $s): ?>
			<div class="stock-card">
				<div class="group"><?php echo htmlspecialchars($s['blood_group']); ?></div>
				<div class="units"><?php echo intval($s['units_available']); ?> Units</div>
			</div>
		<?php endforeach; endif; ?>
	</div>

	<div class="stats">
		<div class="stat">
			<div class="icon"><i class="fa fa-user-injured"></i></div>
			<div>
				<div class="value"><?php echo $totalDonors; ?></div>
				<div class="label">Total Donors</div>
			</div>
		</div>
		<div class="stat">
			<div class="icon"><i class="fa fa-hand-holding-medical"></i></div>
			<div>
				<div class="value"><?php echo $totalRequests; ?></div>
				<div class="label">Total Requests</div>
			</div>
		</div>
		<div class="stat">
			<div class="icon"><i class="fa fa-tint"></i></div>
			<div>
				<div class="value"><?php echo $availableUnits; ?></div>
				<div class="label">Available Units</div>
			</div>
		</div>
	</div>

</div>

<?php include 'includes/footer.php'; ?>