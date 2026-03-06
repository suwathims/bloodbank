<?php
include __DIR__ . '/../includes/config.php';
header('Content-Type: application/json');
$labels = [];
$values = [];
$res = mysqli_query($conn, "SELECT blood_group, units_available FROM blood_stock ORDER BY blood_group");
if ($res) {
    while ($r = mysqli_fetch_assoc($res)) {
        $labels[] = $r['blood_group'];
        $values[] = intval($r['units_available']);
    }
}
echo json_encode(['labels'=>$labels,'values'=>$values]);
?>
