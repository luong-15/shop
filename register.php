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
  require ('./connect.php');
  if (isset($_REQUEST['username'])) {
    $username = mysqli_real_escape_string($conn, $_REQUEST['username']);
    $password = mysqli_real_escape_string($conn, $_REQUEST['password']);

    $check_query = "SELECT * FROM shop.user WHERE username = '$username'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
      echo "<div class='form' style='background-color: #FFF;text-align: center;font-size: 22px;'>
          <h3>Username already exists.</h3>
          <br/>Click here to <a href='register.php'>Register</a></div>";
    } else {
      $query = "INSERT into shop.user (username, password) VALUES ('$username', '$password')";
      $result = mysqli_query($conn, $query);

      if ($result) {
        echo "<div class='form' style='background-color: #FFF;text-align: center;font-size: 22px;'>
              <h3>You are registered successfully.</h3>
              <br/>Click here to <a href='login.php'>Login</a></div>";
      }
    }
  } else {
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

    </form>
  <?php } ?>

</body>

</html>