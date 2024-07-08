
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./login.css">
  </head>
  <body>
<?php
require("./conect.php");
session_start();

if (isset($_POST['username'])){
$username = stripslashes($_REQUEST['username']);
$username = mysqli_real_escape_string($conn,$username);
$password = stripslashes($_REQUEST['password']);
$password = mysqli_real_escape_string($conn,$password);
$query = "SELECT * FROM shop.user WHERE username='$username' and password='$password'";
$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
$rows = mysqli_num_rows($result);
  if($rows > 0){
$_SESSION['username'] = $username;
header("Location: index.php");
exit();
   }else{
echo "<div class='form' style='background-color: #FFF;text-align: center;font-size: 22px;'>
<h3>Username/password is incorrect.</h3>
<br/>Click here to <a href='login.php'>Login</a></div>";
}
}else{
?>
<form action="" method="post">
    <div class="ring">
      <i style="--clr:#00ff0a;"></i>
      <i style="--clr:#ff0057;"></i>
      <i style="--clr:#fffd44;"></i>
      <div class="login">
        <h2>Login</h2>
        <div class="inputBx">
          <input type="text" name="username" placeholder="Username" required="">
        </div>
        <div class="inputBx">
          <input type="password" name="password" placeholder="Password" required="">
        </div>
        <div class="inputBx">
          <input type="submit" value="Login" name="login">
        </div>
      <div class="links">
        <a href="./index.php" class="fa fa-home" aria-hidden="true"></a>
        <a href="./register.php" type="submit">Register</a>
      </div>
    </div>
</form>
<?php } ?>

</body>
</html>