<?php
include_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin'])) { header('Location: ../login.php'); exit; }

// handle updates/add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['group']) && isset($_POST['units'])) {
        $grp = trim($_POST['group']);
        $units = intval($_POST['units']);
        if ($grp !== '' && $units >= 0) {
            // insert or update
            $stmt = $conn->prepare('INSERT INTO blood_stock (blood_group, units_available) VALUES (?, ?) ON DUPLICATE KEY UPDATE units_available=?');
            $stmt->bind_param('sii', $grp, $units, $units);
            $stmt->execute();
        }
    }
    header('Location: stock.php');
    exit;
}

// fetch stock
$stock = [];
$res = mysqli_query($conn, 'SELECT * FROM blood_stock ORDER BY blood_group');
if ($res) while ($r = mysqli_fetch_assoc($res)) { $stock[] = $r; }
?>
<?php include_once __DIR__ . '/../includes/header.php'; ?>
<?php include_once __DIR__ . '/../includes/navbar.php'; ?>
<div class="container">
    <h2 style="color:var(--brand)">Manage Blood Stock</h2>
    <div style="overflow:auto;margin-top:12px">
        <table class="table" style="width:100%">
            <thead><tr><th>Blood Group</th><th>Units Available</th></tr></thead>
            <tbody>
                <?php foreach($stock as $s): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($s['blood_group']); ?></td>
                        <td><?php echo intval($s['units_available']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px;max-width:400px">
        <form method="post">
            <div class="field">
                <input placeholder=" " name="group" required>
                <label>Blood group</label>
            </div>
            <div class="field" style="margin-top:12px">
                <input type="number" placeholder=" " name="units" min="0" required>
                <label>Units available</label>
            </div>
            <div style="margin-top:14px">
                <button class="btn-primary" type="submit">Update / Add</button>
            </div>
        </form>
    </div>
</div>
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
