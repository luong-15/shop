<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./login.css">
</head>
<body>

<?php
require('./conect.php');
if (isset($_REQUEST['username'])){
	// $username = stripslashes($_REQUEST['username']);
	$username = mysqli_real_escape_string($conn,$_REQUEST['username']);
	// $password = stripslashes($_REQUEST['password']);
	$password = mysqli_real_escape_string($conn,$_REQUEST['password']);
    $query = "INSERT into shop.user (username, password) VALUES ('$username', '$password')" ;
    $result = mysqli_query($conn,$query);
    // $iduser = mysqli_insert_id($conn);
        if($result){
            echo "<div class='form' style='background-color: #FFF;text-align: center;font-size: 22px;'>
<h3>You are registered successfully.</h3>
<br/>Click here to <a href='login.php'>Login</a></div>";
        }
    }else{
?>
<form name="registration" action="" method="post">
    <div class="ring">
      <i style="--clr:#00ff0a;"></i>
      <i style="--clr:#ff0057;"></i>
      <i style="--clr:#fffd44;"></i>
      <div class="login">
        <h2>Registration</h2>
        <div class="inputBx">
          <input type="text" name="username" placeholder="Username" required="">
        </div>
        <div class="inputBx">
          <input type="password" name="password" placeholder="Password" required="">
        </div>
        <div class="inputBx">
          <input type="submit" name="submit" value="Register">
        </div>
      <div class="links">
        <a href="./index.php" class="fa fa-home" aria-hidden="true"></a>
        <a href="./login.php" type="submit">Login</a>
      </div>
    </div>
<!-- <div class="form">
<h1>Registration</h1>
<input type="text" name="username" placeholder="Username" required />
<input type="password" name="password" placeholder="Password" required />
<input type="submit" name="submit" value="Register" />
</div> -->
</form>
<?php } ?>

</body>
</html>