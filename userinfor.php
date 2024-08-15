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
    require ("./connect.php");
    if (!session_start()) {
        header("Location: login.php");
        exit;
    }

    echo '  
    <a><span class="icon"><i class="fa fa-user-circle" aria-hidden="true" style="
    width: 100%;    
    height: 100px;
    text-align: center;
    font-size: 12em;
    color: gray;">' . $_SESSION['username'] . '</i></span></a> ';

    $iduser = $_SESSION['iduser'];
    $username = $_SESSION['username'];
    $role = $_SESSION['role'];
    echo "Welcome, $iduser, $username, $role";
    ?>
    <br><br>
    <a href="index.php"><button>Cancel</button></a>
    <a href="logout.php"><button>Logout</button></a>
</body>

</html>