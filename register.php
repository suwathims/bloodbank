<?php include 'includes/config.php';

$errors = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $age = intval($_POST['age'] ?? 0);
    $gender = $_POST['gender'] ?? '';
    $blood = $_POST['blood'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($name === '') { $errors[] = 'Name is required.'; }
    if ($age <= 0) { $errors[] = 'Valid age is required.'; }
    if ($blood === '') { $errors[] = 'Select blood group.'; }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO donors (name, age, gender, blood_group, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sissss', $name, $age, $gender, $blood, $phone, $address);
        if ($stmt->execute()) {
            // increment stock if exists
            $conn->query("UPDATE blood_stock SET units_available = units_available + 1 WHERE blood_group='" . $conn->real_escape_string($blood) . "'");
            $success = true;
        } else {
            $errors[] = 'Database error: could not save donor.';
        }
    }
}
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container" style="max-width:720px;margin-top:28px">
    <div class="form-card">
        <h3 style="margin-top:0">Donor Registration</h3>
        <?php if ($success): ?>
            <div class="fade-in" style="text-align:center;padding:12px">
                <div style="font-size:48px;color:var(--brand)"><i class="fa fa-check-circle"></i></div>
                <div style="font-weight:700;margin-top:8px">Thank you — Registration successful</div>
                <div style="opacity:0.8;margin-top:6px">We appreciate your willingness to donate.</div>
            </div>
        <?php else: ?>
            <?php if (!empty($errors)): ?>
                <div style="color:#ef4444;margin-bottom:12px">
                    <?php foreach($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?>
                </div>
            <?php endif; ?>

            <form method="post" novalidate>
                <div class="form-row two">
                    <div class="field">
                        <input name="name" placeholder=" " required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                        <label>Full name</label>
                    </div>
                    <div class="field">
                        <input type="number" name="age" placeholder=" " required value="<?php echo htmlspecialchars($_POST['age'] ?? ''); ?>">
                        <label>Age</label>
                    </div>
                </div>
                <div class="form-row two" style="margin-top:8px">
                    <div class="field">
                        <select name="gender" required style="padding:14px 12px;border-radius:8px;border:1px solid #e6e9ee;background:transparent">
                            <option value="">Select gender</option>
                            <option value="Male" <?php if(($_POST['gender'] ?? '')==='Male') echo 'selected';?>>Male</option>
                            <option value="Female" <?php if(($_POST['gender'] ?? '')==='Female') echo 'selected';?>>Female</option>
                        </select>
                    </div>
                    <div class="field">
                        <select name="blood" required style="padding:14px 12px;border-radius:8px;border:1px solid #e6e9ee;background:transparent">
                            <option value="">Blood group</option>
                            <?php foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $g){ $sel = (($_POST['blood']??'')===$g)?'selected':''; echo "<option value=\"$g\" $sel>$g</option>"; } ?>
                        </select>
                    </div>
                </div>

                <div class="field" style="margin-top:12px">
                    <input name="phone" placeholder=" " value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    <label>Phone</label>
                </div>
                <div class="field" style="margin-top:12px">
                    <textarea name="address" placeholder=" "><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                    <label>Address</label>
                </div>

                <div style="margin-top:14px">
                    <button class="btn-primary" type="submit">Register as Donor</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>