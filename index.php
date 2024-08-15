<?php
require ("./connect.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiniShop</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="shortcut icon" href="" />

</head>

<body>
    <marquee>Hà Nội: 49 Thái Hà | 151 Lê Thanh Nghị và 63 Trần Thái Tông ● HCM: 158 - 160 Lý Thường Kiệt | 330-332 Võ
        Văn Tần ● Bắc Ninh: Số 4 Nguyễn Văn Cừ - Ninh Xá </marquee>

    <div class="header">

        <h1 class="logo">MiniShop</h1>

        <form action="search.php" method="GET">
            <input type="text" name="search_term" class="input" placeholder="Searth the products...">
        </form>

        <ul class="menu-header">
            <button class="menu-btn">Hỗ trợ</button>
            <button class="menu-btn">Điều khoản</button>
            <li>
                <a href="./index.php"><span class="icon"><i class="fa fa-home" aria-hidden="true"></i></span></a>
            </li>
            <?php
            if (isset($_SESSION['username']) && ($_SESSION['username'] != "")) {
                echo
                    '<li>
                    <a href="./userinfor.php"><span class="icon"><i class="fa fa-user-circle" aria-hidden="true"></i>' . $_SESSION['username'] . '</span></a>
                </li>';
            } else {
                ?>
                <li>
                    <a href="./login.php"><span class="icon"><i class="fa fa-user-circle" aria-hidden="true"></i></span></a>
                </li>
            <?php } ?>
            <li>
                <a href="./shoppingcart.php"><span onclick="toggleElements()" id="show_cart" class="icon"><i
                            class="fa fa-shopping-cart" aria-hidden="true"></i></span></a>
            </li>
            <?php
            if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] === 'admin') {
                    echo '<li>
                            <a href="./manager.php"><span class="icon"><i class="fa-solid fa-bars-progress"></i></span></a>
                        </li>';
                } else {
                    echo "";
                }
            }
            ?>
        </ul>
    </div>

    <div class="show-page">
        <div class="show-cart">

        </div>
        <div class="home-page">

            <ul class="menu-home">
                <li class="container"><button id="btn-phone" class="btn">Di động</button></li>
                <li class="container"><button id="btn-tv" class="btn">TV&AV</button></li>
                <li class="container"><button id="btn-washing" class="btn">Máy giặt</button></li>
                <li class="container"><button id="btn-fridge" class="btn">Tủ lạnh</button></li>
                <li class="container"><button id="btn-accessory" class="btn">Phụ kiện</button></li>
            </ul>

            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php
                    $sql = "SELECT * FROM shop.slides";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "
                        <div class='swiper-slide'>
                        <h6>" . $row['id_slides'] . "</h6>
                        <img src=" . $row['img_slides'] . ">
                        </div>";
                    }
                    ?>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev">
                    <i class="fa-solid fa-caret-up"></i>
                    <span>prev</span>
                </div>
                <div class="swiper-button-next">
                    <i class="fa-solid fa-caret-up"></i>
                    <span>next</span>
                </div>
            </div>
        </div>

        <div class="product-show">

            <?php

            function addToCart($conn, $iduser, $username, $productName, $quantity, $price)
            {
                $sql = "INSERT INTO shop.shoppingcart (iduser, username, name, quantity, price) 
                VALUES (?, ?, ?, ?, ?)";

                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'sssis', $iduser, $username, $productName, $quantity, $price);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

            }

            if (isset($_POST['add_to_cart'])) {
                $iduser = $_SESSION['iduser'];
                $username = $_SESSION['username'];
                $productName = $_POST['product_name'];
                $quantity = $_POST['quantity'] ?? 1;
                $price = $_POST['price'];

                if (!is_numeric($quantity) || $quantity < 1) {
                    $quantity = 1;
                }

                if (isset($iduser) && !is_null($iduser)) {
                    $sql = "SELECT * FROM shop.shoppingcart WHERE name = ? AND iduser = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "si", $productName, $iduser);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    mysqli_stmt_close($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $newQuantity = $row['quantity'] + $quantity;
                        $sql = "UPDATE shop.shoppingcart SET quantity = ? WHERE name = ? AND iduser = ?";
                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_bind_param($stmt, "isi", $newQuantity, $productName, $iduser);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                        echo "Số lượng sản phẩm đã được tăng lên!";
                    } else {
                        addToCart($conn, $iduser, $username, $productName, $quantity, $price);
                        echo "Sản phẩm đã được thêm vào giỏ hàng!";
                    }
                } else {
                    echo "<script>alert('Vui lòng đăng nhập để mua hàng!');</script>";
                }
            }
            ?>

            <?php
            $sql = "SELECT * FROM shop.phone";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "
        <div id='product-phone' class='product-card product-phone'>
        <h6>" . $row['id'] . "</h6>
        <img src=" . $row['img'] . ">
        <h3>" . $row['name'] . "</h3>
        <p id='product-price'>" . $row['price'] . "₫</p>
        <form method='post'>
            <input type='hidden' name='product_id' value='{$row['id']}'>
            <input type='hidden' name='product_name' value='{$row['name']}'>
            <input type='hidden' name='price' value='{$row['price']}'>
            <button class='btn-add-to-cart' name='add_to_cart' >Mua ngay <i class='fa-solid fa-cart-shopping'></i></button>
        </form>
        </div>
            ";
            }
            ?>

            <?php
            $sql = "SELECT * FROM shop.tv";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "
        <div id='product-tv' class='product-card product-tv'>
        <h6>" . $row['id'] . "</h6>
        <img src=" . $row['img'] . ">
        <h3>" . $row['name'] . "</h3>
        <p id='product-price'>" . $row['price'] . "₫</p>
        <form method='post'>
            <input type='hidden' name='product_id' value='{$row['id']}'>
            <input type='hidden' name='product_name' value='{$row['name']}'>
            <input type='hidden' name='price' value='{$row['price']}'>
            <button class='btn-add-to-cart' name='add_to_cart' >Mua ngay <i class='fa-solid fa-cart-shopping'></i></button>
        </form>
        </div>
        ";
            }
            ?>

            <?php
            $sql = "SELECT * FROM shop.washing";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "
        <div id='product-washing' class='product-card product-washing'>
        <h6>" . $row['id'] . "</h6>
        <img src=" . $row['img'] . ">
        <h3>" . $row['name'] . "</h3>
        <p id='product-price'>" . $row['price'] . "₫</p>
        <form method='post'>
            <input type='hidden' name='product_id' value='{$row['id']}'>
            <input type='hidden' name='product_name' value='{$row['name']}'>
            <input type='hidden' name='price' value='{$row['price']}'>
            <button class='btn-add-to-cart' name='add_to_cart' >Mua ngay <i class='fa-solid fa-cart-shopping'></i></button>
        </form>
        </div>
        ";
            }
            ?>

            <?php
            $sql = "SELECT * FROM shop.fridge";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "
        <div id='product-fridge' class='product-card product-fridge'>
        <h6>" . $row['id'] . "</h6>
        <img src=" . $row['img'] . ">
        <h3>" . $row['name'] . "</h3>
        <p id='product-price'>" . $row['price'] . "₫</p>
        <form method='post'>
            <input type='hidden' name='product_id' value='{$row['id']}'>
            <input type='hidden' name='product_name' value='{$row['name']}'>
            <input type='hidden' name='price' value='{$row['price']}'>
            <button class='btn-add-to-cart' name='add_to_cart' >Mua ngay <i class='fa-solid fa-cart-shopping'></i></button>
        </form>
        </div>
        ";
            }
            ?>

            <?php
            $sql = "SELECT * FROM shop.accessory";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "
        <div id='product-accessory' class='product-card product-accessory'>
        <h6>" . $row['id'] . "</h6>
        <img src=" . $row['img'] . ">
        <h3>" . $row['name'] . "</h3>
        <p id='product-price'>" . $row['price'] . "₫</p>
        <form method='post'>
            <input type='hidden' name='product_id' value='{$row['id']}'>
            <input type='hidden' name='product_name' value='{$row['name']}'>
            <input type='hidden' name='price' value='{$row['price']}'>
            <button class='btn-add-to-cart' name='add_to_cart' >Mua ngay <i class='fa-solid fa-cart-shopping'></i></button>
        </form>
        </div>
        ";
            }
            ?>
            <div id="notification-container"></div>
        </div>
    </div>
    <div class="show-popup" id="popup-ads">
        <img
            src="https://images.samsung.com/is/image/samsung/assets/vn/home/2024/Galaxy_A55-355G_Home_KV-banner_PC.jpg?imwidth=1366">
        <button class="close-show-popup" onclick="closeShowPopup()">[X]</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="./script.js"></script>
</body>

</html>