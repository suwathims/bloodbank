<?php include __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin'])) header('Location: ../login.php');

$donors = 0; $requests = 0; $stock_total = 0;
$r = mysqli_query($conn, "SELECT COUNT(*) as c FROM donors"); if ($r) { $donors = intval(mysqli_fetch_assoc($r)['c']); }
$r = mysqli_query($conn, "SELECT COUNT(*) as c FROM requests"); if ($r) { $requests = intval(mysqli_fetch_assoc($r)['c']); }
$r = mysqli_query($conn, "SELECT SUM(units_available) as s FROM blood_stock"); if ($r) { $stock_total = intval(mysqli_fetch_assoc($r)['s']); }

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<div class="container">
	<div class="admin-wrap">
		<aside class="sidebar">
			<a href="dashboard.php" class="active"><i class="fa fa-chart-pie"></i> Dashboard</a>
			<a href="donors.php"><i class="fa fa-user"></i> Donors</a>
			<a href="requests.php"><i class="fa fa-file-medical"></i> Requests</a>
			<a href="stock.php"><i class="fa fa-vial"></i> Stock</a>
			<a href="logout.php" style="margin-top:12px;color:#ef4444"><i class="fa fa-sign-out-alt"></i> Logout</a>
		</aside>

		<main class="dash-main">
			<h2 style="color:var(--brand)">Dashboard</h2>
			<div class="dash-cards" style="margin-top:12px">
				<div class="dash-card">
					<div style="font-size:14px;color:#6b7280">Total Donors</div>
					<div style="font-weight:700;font-size:22px"><?php echo $donors; ?></div>
				</div>
				<div class="dash-card">
					<div style="font-size:14px;color:#6b7280">Total Requests</div>
					<div style="font-weight:700;font-size:22px"><?php echo $requests; ?></div>
				</div>
				<div class="dash-card">
					<div style="font-size:14px;color:#6b7280">Available Units</div>
					<div style="font-weight:700;font-size:22px"><?php echo $stock_total; ?></div>
				</div>
			</div>

			<div style="margin-top:18px;display:grid;grid-template-columns:2fr 1fr;gap:16px">
				<section class="dash-card">
					<h4 style="margin-top:0">Blood Stock Overview</h4>
					<canvas id="stockChart" style="max-width:100%;height:220px"></canvas>
				</section>
				<section class="dash-card">
					<h4 style="margin-top:0">Quick Links</h4>
					<div style="display:flex;flex-direction:column;gap:8px;margin-top:8px">
						<a href="donors.php" class="btn-primary" style="text-align:center">View Donors</a>
						<a href="requests.php" class="btn-primary" style="text-align:center">View Requests</a>
						<a href="stock.php" class="btn-primary" style="text-align:center">Manage Stock</a>
					</div>
				</section>
			</div>

			<div style="margin-top:18px" class="dash-card">
				<h4 style="margin-top:0">Recent Requests</h4>
				<div style="overflow:auto">
					<table class="table" style="width:100%">
						<thead><tr><th>Patient</th><th>Blood</th><th>Units</th><th>Urgent</th><th>Date</th></tr></thead>
						<tbody>
						<?php
						$res = mysqli_query($conn, "SELECT patient_name, blood_group, units_needed, urgent, created_at FROM requests ORDER BY created_at DESC LIMIT 8");
						if ($res) while ($row = mysqli_fetch_assoc($res)) {
							echo '<tr'.(intval($row['urgent'])? ' style="background:rgba(239,68,68,0.06)"':'').'><td>'.htmlspecialchars($row['patient_name']).'</td><td>'.htmlspecialchars($row['blood_group']).'</td><td>'.intval($row['units_needed']).'</td><td>'.(intval($row['urgent'])?'<span style="color:#ef4444;font-weight:700">Yes</span>':'No').'</td><td>'.htmlspecialchars($row['created_at']).'</td></tr>';
						}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</main>
	</div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Build chart data from server via inline JSON
fetch('/bloodbank/admin/stock_data.php').then(r=>r.json()).then(data=>{
	const ctx = document.getElementById('stockChart').getContext('2d');
	new Chart(ctx, {type:'bar', data:{labels:data.labels, datasets:[{label:'Units',data:data.values,backgroundColor:'rgba(183,28,28,0.8)'}]}, options:{responsive:true}});
}).catch(()=>{
	// fallback: try to load from inline table by reading DOM (not implemented)
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>