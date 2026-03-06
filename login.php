<?php
include 'includes/config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email && $password) {
        $stmt = $conn->prepare('SELECT id, password, is_admin FROM users WHERE email=? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($user = $res->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                if (!empty($user['is_admin'])) {
                    $_SESSION['admin'] = true;
                    header('Location: admin/dashboard.php');
                    exit;
                }
                header('Location: index.php');
                exit;
            }
        }
        $error = 'Invalid email or password.';
    } else {
        $error = 'Enter email and password.';
    }
}
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container" style="max-width:420px;margin-top:40px">
    <div class="form-card">
        <h3 style="margin-top:0">Login</h3>
        <?php if ($error): ?><div style="color:#ef4444;margin-bottom:12px"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <form method="post" novalidate>
            <div class="field">
                <input type="email" name="email" placeholder=" " required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                <label>Email</label>
            </div>
            <div class="field" style="margin-top:12px">
                <input type="password" name="password" placeholder=" " required>
                <label>Password</label>
            </div>
            <div style="margin-top:14px">
                <button class="btn-primary" type="submit">Login</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>