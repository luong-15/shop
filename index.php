<?php
require("./conect.php");

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiniShop</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="shortcut icon" href="" />

</head>
<body>
    <marquee>Hà Nội: 49 Thái Hà | 151 Lê Thanh Nghị và 63 Trần Thái Tông ● HCM: 158 - 160 Lý Thường Kiệt | 330-332 Võ Văn Tần ● Bắc Ninh: Số 4 Nguyễn Văn Cừ - Ninh Xá </marquee>

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
            if(isset($_SESSION['username'])&&($_SESSION['username']!="")){
                echo 
                '<li>
                    <a href="./userinfor.php"><span class="icon"><i class="fa fa-user-circle" aria-hidden="true"></i>'.$_SESSION['username'].'</span></a>
                </li>';
            }else {
            ?>
            <li>
                <a href="./login.php"><span class="icon"><i class="fa fa-user-circle" aria-hidden="true"></i></span></a>
            </li>
            <?php } ?>
            <li>
                <a ><span onclick="toggleElements()" id="show_cart" class="icon"><i class="fa fa-shopping-cart" aria-hidden="true"></i></span></a>
            </li>
        </ul>
    </div>

    <div class="show-page">
    <div class="show-cart">
    <div class="cart-container">
    <h2>Shopping Cart</h2>

    <table class="cart-table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
          <th>Remove</th>
        </tr>
      </thead>
      <tbody>
        </tbody>
    </table>

    <div class="cart-total">
      <p>Total: <span id="cart-total">0₫</span></p>
      <a href="checkout.php">Pay the bill</a>
    </div>

    <?php

$userId = isset($_SESSION['iduser']) ? $_SESSION['iduser'] : 0;

// Assuming cart data is stored in an array named 'cartItems'
$cartItems = // Get cart data from session, cookies, or other source

// Check if there are items in the cart
if (empty($cartItems)) {
    echo "Your cart is empty.";
    exit;
}

// Save cart data to database
function saveCartToDatabase($db, $userId, $cartItems) {
    // Start a transaction for data integrity
    $db->begin_transaction();

    try {
        // Clear existing cart data for the user (optional)
        $sql = "DELETE FROM cart_items WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();

        // Insert each cart item into the database
        foreach ($cartItems as $item) {
            $productId = $item['productId'];
            $quantity = $item['quantity'];

            $sql = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param('iii', $userId, $productId, $quantity);
            $stmt->execute();
        }

        // Commit the transaction if successful
        $db->commit();
        echo "Cart saved to database successfully!";
    } catch (Exception $e) {
        // Rollback on error
        $db->rollback();
        echo "Error saving cart: " . $e->getMessage();
        throw $e; // Re-throw for caller handling
    }
}

// Call the save function
saveCartToDatabase($db, $userId, $cartItems);

// Close the database connection
$db->close();
?>
  </div>
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
                    $id_slides = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo"
                        <div class='swiper-slide'>
                        <h6>".$id_slides++."</h6>
                        <img src=".$row['img_slides'].">
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
    $username = $_SESSION['username'];
    $iduser = $_SESSION['iduser'];
    $sqlUserInfo = "SELECT iduser, username FROM shop.user WHERE username='$username'";
    $resultUserInfo = $conn->query($sqlUserInfo);
 
    if ($resultUserInfo->num_rows > 0) {
    $rowUserInfo = $resultUserInfo->fetch_assoc();
    $username = $rowUserInfo["username"];
    $iduser = $rowUserInfo["iduser"];
    echo "id = $iduser";
    } else {
    echo "User information not found.";
    }
    ?>

    <?php 
    $sql = "SELECT * FROM shop.phone";
    $result = mysqli_query( $conn, $sql );
    while ( $row = mysqli_fetch_assoc( $result ) ) {
        echo "
        <div id='product-phone' class='product-card product-phone'>
        <h6>" . $row['id_phone'] . "</h6>
        <img src=".$row['img_phone'].">
        <h3>" . $row['name_phone'] . "</h3>
        <p id='product-price'>".$row['price']."₫</p>
        <button class='btn-add-to-cart' data-product-id='" . $row['id_phone'] . "' data-product-name='" . $row['name_phone'] ."' data-product-price='".$row['price']."'>Mua ngay <i class='fa-solid fa-cart-shopping'></i></button>
        </div>
        ";
    }
    ?>  

    <?php 
    $sql = "SELECT * FROM shop.tv";
    $result = mysqli_query( $conn, $sql );
    while ( $row = mysqli_fetch_assoc( $result ) ) {
        echo"
        <div id='product-tv' class='product-card product-tv'>
        <h6>".$row['id_TV']."</h6>
        <img src=".$row['img_TV'].">
        <h3>".$row['name_TV']."</h3>
        <p id='product-price'>".$row['price']."₫</p>
        <button class='btn-add-to-cart' data-product-id='" . $row['id_TV'] . "' data-product-name='" . $row['name_TV'] ."' data-product-price='".$row['price']."'>Mua ngay <i class='fa-solid fa-cart-shopping'></i></button>
        </div>
        ";
    }
    ?>  

    <?php 
    $sql = "SELECT * FROM shop.washing";
    $result = mysqli_query( $conn, $sql );
    while ( $row = mysqli_fetch_assoc( $result ) ) {
        echo"
        <div id='product-washing' class='product-card product-washing'>
        <h6>".$row['id_washing']."</h6>
        <img src=".$row['img_washing'].">
        <h3>".$row['name_washing']."</h3>
        <p id='product-price'>".$row['price']."₫</p>
        <button class='btn-add-to-cart' data-product-id='" . $row['id_washing'] . "' data-product-name='" . $row['name_washing'] ."' data-product-price='".$row['price']."'>Mua ngay <i class='fa-solid fa-cart-shopping'></i></button>
        </div>
        ";
    }
    ?>  

    <?php 
    $sql = "SELECT * FROM shop.fridge";
    $result = mysqli_query( $conn, $sql );
    while ( $row = mysqli_fetch_assoc( $result ) ) {
        echo"
        <div id='product-fridge' class='product-card product-fridge'>
        <h6>".$row['id_fridge']."</h6>
        <img src=".$row['img_fridge'].">
        <h3>".$row['name_fridge']."</h3>
        <p id='product-price'>".$row['price']."₫</p>
        <button class='btn-add-to-cart' data-product-id='" . $row['id_fridge'] . "' data-product-name='" . $row['name_fridge'] ."' data-product-price='".$row['price']."'>Mua ngay <i class='fa-solid fa-cart-shopping'></i></button>
        </div>
        ";
    }
    ?>  

    <?php 
    $sql = "SELECT * FROM shop.accessory";
    $result = mysqli_query( $conn, $sql );
    while ( $row = mysqli_fetch_assoc( $result ) ) {
        echo"
        <div id='product-accessory' class='product-card product-accessory'>
        <h6>".$row['id_accessory']."</h6>
        <img src=".$row['img_accessory'].">
        <h3>".$row['name_accessory']."</h3>
        <p id='product-price'>".$row['price']."₫</p>
        <button class='btn-add-to-cart' data-product-id='" . $row['id_accessory'] . "' data-product-name='" . $row['name_accessory'] ."' data-product-price='".$row['price']."'>Mua ngay <i class='fa-solid fa-cart-shopping'></i></button>
        </div>
        ";
    }
    ?>  
    <div id="notification-container"></div>
    </div>
    </div>
    <div class="show-popup" id="popup-ads">
        <img src="https://images.samsung.com/is/image/samsung/assets/vn/home/2024/Galaxy_A55-355G_Home_KV-banner_PC.jpg?imwidth=1366">
        <button class="close-show-popup" onclick="closeShowPopup()">[X]</button>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="./script.js"></script>
</body>
</html>