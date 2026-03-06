<?php
include_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin'])) { header('Location: ../login.php'); exit; }

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare('DELETE FROM requests WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: requests.php'); exit;
}

$reqs = [];
$res = mysqli_query($conn, 'SELECT * FROM requests ORDER BY created_at DESC');
if ($res) while ($r = mysqli_fetch_assoc($res)) { $reqs[] = $r; }
?>
<?php include_once __DIR__ . '/../includes/header.php'; ?>
<?php include_once __DIR__ . '/../includes/navbar.php'; ?>
<div class="container">
    <h2 style="color:var(--brand)">Blood Requests</h2>
    <div style="overflow:auto;margin-top:12px">
        <table class="table" style="width:100%">
            <thead><tr><th>#</th><th>Patient</th><th>Blood</th><th>Units</th><th>Hospital</th><th>Contact</th><th>Urgent</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
                <?php if (empty($reqs)): ?>
                    <tr><td colspan="9" style="text-align:center">No requests yet.</td></tr>
                <?php else: foreach($reqs as $r): ?>
                    <tr<?php echo (isset($r['urgent']) && intval($r['urgent'])) ? ' style="background:rgba(239,68,68,0.06)"' : ''; ?>>
                        <td><?php echo isset($r['id']) ? $r['id'] : '—'; ?></td>
                        <td><?php echo htmlspecialchars($r['patient_name'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($r['blood_group'] ?? ''); ?></td>
                        <td><?php echo intval($r['units_needed'] ?? 0); ?></td>
                        <td><?php echo htmlspecialchars($r['hospital_name'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($r['contact'] ?? ''); ?></td>
                        <td><?php echo (isset($r['urgent']) && intval($r['urgent'])) ? '<span style="color:#ef4444;font-weight:700">Yes</span>' : 'No'; ?></td>
                        <td><?php echo htmlspecialchars($r['created_at'] ?? ''); ?></td>
                        <td><?php if (isset($r['id'])): ?><a href="?delete=<?php echo $r['id']; ?>" style="color:#ef4444" onclick="return confirm('Delete request?');">Delete</a><?php endif; ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
