<?php
// simple script to reset the admin password without needing phpmyadmin
include 'includes/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new = $_POST['password'] ?? '';
    if ($new) {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('UPDATE users SET password=? WHERE email=?');
        $email = 'admin@bloodbank.local';
        $stmt->bind_param('ss', $hash, $email);
        if ($stmt->execute()) {
            echo '<p style="color:green">Password updated for ' . htmlspecialchars($email) . '</p>';
        } else {
            echo '<p style="color:red">Update failed.</p>';
        }
    }
}
?>
<!DOCTYPE html>
<html><head><title>Reset Admin</title></head><body>
<h2>Reset Admin Password</h2>
<form method="post">
<label>New password: <input type="password" name="password"></label>
<button type="submit">Set password</button>
</form>
<p>After resetting you can delete this file for security.</p>
</body></html>