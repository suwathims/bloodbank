<?php include 'includes/config.php';

$errors = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $blood = $_POST['blood'] ?? '';
    $units = intval($_POST['units'] ?? 0);
    $hospital = trim($_POST['hospital'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $urgent = isset($_POST['urgent']) && $_POST['urgent'] === '1' ? 1 : 0;

    if ($name === '') { $errors[] = 'Patient name is required.'; }
    if ($blood === '') { $errors[] = 'Select blood group.'; }
    if ($units <= 0) { $errors[] = 'Enter required units.'; }

    if (empty($errors)) {
        $stmt = $conn->prepare('INSERT INTO requests (patient_name, blood_group, units_needed, hospital_name, contact, urgent, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
        $stmt->bind_param('ssisss', $name, $blood, $units, $hospital, $contact, $urgent);
        if ($stmt->execute()) { $success = true; }
        else { $errors[] = 'Could not submit request.'; }
    }
}
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container" style="max-width:720px;margin-top:28px">
    <div class="form-card">
        <h3 style="margin-top:0">Request Blood</h3>
        <?php if ($success): ?>
            <div class="fade-in" style="text-align:center;padding:12px">
                <div style="font-size:42px;color:var(--brand)"><i class="fa fa-paper-plane"></i></div>
                <div style="font-weight:700;margin-top:8px">Request submitted</div>
                <div style="opacity:0.85;margin-top:6px">We will notify matching donors shortly.</div>
            </div>
        <?php else: ?>
            <?php if (!empty($errors)): ?><div style="color:#ef4444;margin-bottom:12px"><?php foreach($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?></div><?php endif; ?>

            <form method="post" novalidate>
                <div class="field">
                    <input name="name" placeholder=" " required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                    <label>Patient name</label>
                </div>

                <div class="form-row two" style="margin-top:12px">
                    <div class="field">
                        <select name="blood" required style="padding:14px 12px;border-radius:8px;border:1px solid #e6e9ee;background:transparent">
                            <option value="">Blood group</option>
                            <?php foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $g){ $sel = (($_POST['blood']??'')===$g)?'selected':''; echo "<option value=\"$g\" $sel>$g</option>"; } ?>
                        </select>
                    </div>
                    <div class="field">
                        <input type="number" name="units" placeholder=" " required value="<?php echo htmlspecialchars($_POST['units'] ?? ''); ?>">
                        <label>Units needed</label>
                    </div>
                </div>

                <div class="field" style="margin-top:12px">
                    <input name="hospital" placeholder=" " value="<?php echo htmlspecialchars($_POST['hospital'] ?? ''); ?>">
                    <label>Hospital name</label>
                </div>

                <div class="form-row two" style="margin-top:12px;align-items:center">
                    <div>
                        <div style="display:flex;gap:8px;align-items:center">
                            <input type="checkbox" id="urgent" name="urgent" value="1" <?php if(($_POST['urgent']??'')==='1') echo 'checked';?>>
                            <label for="urgent" style="margin:0">Mark as urgent</label>
                        </div>
                        <div style="font-size:13px;color:#6b7280;margin-top:6px">Urgent requests are highlighted to donors and admins.</div>
                    </div>
                    <div>
                        <input name="contact" placeholder=" " value="<?php echo htmlspecialchars($_POST['contact'] ?? ''); ?>">
                        <label>Contact</label>
                    </div>
                </div>

                <div style="margin-top:14px">
                    <button class="btn-primary" type="submit">Submit Request</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>