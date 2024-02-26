<?php
$conn = mysqli_connect("localhost", "root", "", "data_trial");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
