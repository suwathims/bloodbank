<?php
$host = "yamabiko.proxy.rlwy.net";
$user = "root";
$password = "kpbcYJsMPHNBpZbIaVZDPIATfthnbSlT";
$db = "railway";
$port = "52365";

$conn = mysqli_connect($host,$user,$password,$db,$port);

if(!$conn){
 die("Database connection failed: ".mysqli_connect_error());
}
?>
