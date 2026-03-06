<?php
include 'includes/config.php';
if ($conn->query("ALTER TABLE requests ADD COLUMN urgent TINYINT(1) DEFAULT 0")) {
    echo "✅ Column 'urgent' added to requests table successfully!";
} else {
    echo "⚠️ Column may already exist or error: " . $conn->error;
}
?>