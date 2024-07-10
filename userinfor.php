<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In for</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">

</head>
<body>
<?php
require("./conect.php");
if (!session_start()) {
    header("Location: login.php");
    header("Location: index.php");
    exit;
  }

 echo '  
    <a><span class="icon"><i class="fa fa-user-circle" aria-hidden="true" style="
    width: 100%;    
    height: 100px;
    text-align: center;
    font-size: 12em;
    color: gray;">'.$_SESSION['username'].'</i></span></a> ';

$username = $_SESSION['username'];
$iduser = $_SESSION['iduser'];
$sqlUserInfo = "SELECT iduser, username FROM shop.user WHERE username='$username'";
$resultUserInfo = $conn->query($sqlUserInfo);

if ($resultUserInfo->num_rows > 0) {
  $rowUserInfo = $resultUserInfo->fetch_assoc();
  $username = $rowUserInfo["username"];
  $iduser = $rowUserInfo["iduser"];
  echo "Welcome,$iduser, $username!";
} else {
  echo "User information not found.";
}
?>
<br><br>
<a href="index.php">Cancel</a><br>
<a href="logout.php">Logout</a>
</body>
</html>