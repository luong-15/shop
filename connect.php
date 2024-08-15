<?php
$servername = "localhost";
$database = "shop";
$username = "root";
$password = "12345";

$conn = mysqli_connect($servername, $username, $password, $database);
mysqli_set_charset($conn, "utf8");

if (!$conn) {

    die("Connection failed: " . mysqli_connect_error());

}
// echo "Connected successfully";
// mysqli_close($conn);