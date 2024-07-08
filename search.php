<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả</title>
    <!-- <link rel="stylesheet" href="./style.css"> -->

<style>
h2 {
    display: block;
    line-height: 1;
}

.product-show {
    display: flex;
    position: relative;
    padding-top: 30px;
    padding-bottom: 30px;
    flex-wrap: wrap;
}

.product-card {
    display: block;
    position: relative;
    width: 300px;
    height: 430px;
    border-radius: 10px;
    margin: 0px 30px 40px 30px;
    padding-top: 4px;
    text-align: center;
    justify-content: center;
    box-shadow: 5px 5px 10px black;
    transition: all ease-out 0.3s;
}

.product-card img {
    position: relative;
    width: 260px;
    height: 270px;
    object-fit: cover;
    object-position: 50% 50%;
    border-radius: 5px;
}

.product-card h3 {
    width: 295px;
    cursor: context-menu;
    position: relative;
    text-transform: uppercase;
    margin: 15px 6px 10px 6px;
    font-family: "Open Sans", sans-serif;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.product-card p {
    position: relative;
    cursor: context-menu;
    margin: 15px 0 15px 0;
    font-size: 18px;
}

.product-card button {
    cursor: pointer;
    position: relative;
    border: 1px solid black;
    border-radius: 20px;
    padding: 5px 10px 5px 10px;
    background: none;
    font-size: 16px;
    transition: ease-in-out 0.2s;
}

.product-card:hover {
    transform: scale(1.03);
}

.product-card button:hover {
    background-color: black;
    color: #fff;
    transform: scale(1.1);
}

</style>
</head>
<body>
<?php
$search_term = $_GET['search_term'];
echo "<h2>Kết quả tìm kiếm: $search_term</h2>";
?>
    
<div class="product-show">

<?php
require("./conect.php");

if (isset($_REQUEST['search_term'])) {
    $search_term = addslashes($_GET['search_term']);
    if (empty($search_term)) {
        echo "Không tìm thấy kết quả!";
    } 
    else {
        
$search_term = $_GET['search_term'];

$sql_tv = "SELECT * FROM shop.tv WHERE name_TV LIKE '%" . $search_term . "%'";
$sql_phone = "SELECT * FROM shop.phone WHERE name_phone LIKE '%" . $search_term . "%'";
$sql_washing = "SELECT * FROM shop.washing WHERE name_washing LIKE '%" . $search_term . "%'";
$sql_fridge = "SELECT * FROM shop.fridge WHERE name_fridge LIKE '%" . $search_term . "%'";
$sql_accessory = "SELECT * FROM shop.accessory WHERE name_accessory LIKE '%" . $search_term . "%'";

$result_tv = mysqli_query($conn, $sql_tv);
$result_phone = mysqli_query($conn, $sql_phone);
$result_washing = mysqli_query($conn, $sql_washing);
$result_fridge = mysqli_query($conn, $sql_fridge);
$result_accessory = mysqli_query($conn, $sql_accessory);
    
if (mysqli_num_rows($result_tv) > 0) {
    while ($row = mysqli_fetch_assoc($result_tv)) {
        echo"
        <div id='product-tv' class='product-card product-tv'>
            <img src=".$row['img_TV'].">
            <h3>".$row['name_TV']."</h3>
            <p>".$row['price']."</p>
            <button> Mua ngay <i class='fa-solid fa-cart-shopping'></i></button>
        </div>
        ";
    }
}

if (mysqli_num_rows($result_phone) > 0) {
    while ($row = mysqli_fetch_assoc($result_phone)) {
           echo"
        <div id='product-phone' class='product-card product-phone'>
            <img src=".$row['img_phone'].">
            <h3>".$row['name_phone']."</h3>
            <p>".$row['price']."</p>
            <button>Mua ngay <i class='fa-solid fa-cart-shopping'></i></button>
        </div>
        ";
    }
}

if (mysqli_num_rows($result_washing) > 0) {
    while ($row = mysqli_fetch_assoc($result_washing)) {
           echo"
        <div id='product-washing' class='product-card product-washing'>
            <img src=".$row['img_washing'].">
            <h3>".$row['name_washing']."</h3>
            <p>".$row['price']."</p>
            <button>Mua ngay <i class='fa-solid fa-cart-shopping'></i></button>
        </div>
        ";
    }
}

if (mysqli_num_rows($result_fridge) > 0) {
    while ($row = mysqli_fetch_assoc($result_fridge)) {
           echo"
        <div id='product-fridge' class='product-card product-fridge'>
            <img src=".$row['img_fridge'].">
            <h3>".$row['name_fridge']."</h3>
            <p>".$row['price']."</p>
            <button>Mua ngay <i class='fa-solid fa-cart-shopping'></i></button>
        </div>
        ";
    }
}

if (mysqli_num_rows($result_accessory) > 0) {
    while ($row = mysqli_fetch_assoc($result_accessory)) {
           echo"
        <div id='product_accessory' class='product-card product_accessory'>
            <img src=".$row['img_accessory'].">
            <h3>".$row['name_accessory']."</h3>
            <p>".$row['price']."</p>
            <button>Mua ngay <i class='fa-solid fa-cart-shopping'></i></button>
        </div>
        ";
    }
}
    }
// } else {
//     echo "Không tìm thấy kết quả!";
}
?>
</div>

</body>
</html>

