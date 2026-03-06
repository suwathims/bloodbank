<?php
$host = "xxxxx.railway.app";
$user = "root";
$password = "kpbcYJsMPHNBpZbIaVZDPIATfthnbSlT";
$db = "railway";
$port = "3306";

$conn = mysqli_connect($host,$user,$password,$db,$port);

if(!$conn){
 die("Database connection failed: ".mysqli_connect_error());
}
?>
