<?php
include_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin'])) { header('Location: ../login.php'); exit; }

// handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare('DELETE FROM donors WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: donors.php');
    exit;
}

$donors = [];
$res = mysqli_query($conn, 'SELECT * FROM donors ORDER BY created_at DESC');
if ($res) {
    while ($r = mysqli_fetch_assoc($res)) { $donors[] = $r; }
}
?>
<?php include_once __DIR__ . '/../includes/header.php'; ?>
<?php include_once __DIR__ . '/../includes/navbar.php'; ?>
<div class="container">
    <h2 style="color:var(--brand)">Donor List</h2>
    <div style="overflow:auto;margin-top:12px">
        <table class="table" style="width:100%">
            <thead><tr><th>#</th><th>Name</th><th>Age</th><th>Gender</th><th>Blood</th><th>Phone</th><th>Address</th><th>Joined</th><th>Action</th></tr></thead>
            <tbody>
                <?php if (empty($donors)): ?>
                    <tr><td colspan="9" style="text-align:center">No donors found.</td></tr>
                <?php else: foreach($donors as $d): ?>
                    <tr>
                        <td><?php echo isset($d['id']) ? $d['id'] : '—'; ?></td>
                        <td><?php echo htmlspecialchars($d['name'] ?? ''); ?></td>
                        <td><?php echo intval($d['age'] ?? 0); ?></td>
                        <td><?php echo htmlspecialchars($d['gender'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($d['blood_group'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($d['phone'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($d['address'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($d['created_at'] ?? ''); ?></td>
                        <td><?php if (isset($d['id'])): ?><a href="?delete=<?php echo $d['id']; ?>" style="color:#ef4444" onclick="return confirm('Delete this donor?');">Delete</a><?php endif; ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
