<?php

session_start(); // Bắt đầu phiên

// Kiểm tra xem nút "Thêm vào giỏ hàng" đã được nhấn hay chưa
if (isset($_POST['btn-add-to-card'])) {
  $btnaddtocard = $_POST['btn-add-to-card'];

  // Thêm sản phẩm vào giỏ hàng (sử dụng mảng kết hợp trong phiên)
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  if (array_key_exists($btnaddtocard, $_SESSION['cart'])) {
    $_SESSION['cart'][$btnaddtocard]['quantity']++;
  } else {
    $_SESSION['cart'][$btnaddtocard] = array('id' => $btnaddtocard, 'quantity' => 1);
  }

  // Thông báo thêm sản phẩm thành công
  echo "Đã thêm sản phẩm vào giỏ hàng!";
}

// Hiển thị giỏ hàng
if (isset($_SESSION['cart'])) {
  echo "<h3>Giỏ hàng</h3>";
  echo "<table border='1'>";
  echo "<tr><th>ID sản phẩm</th><th>Tên sản phẩm</th><th>Số lượng</th><th>Giá</th></tr>";

  foreach ($_SESSION['cart'] as $product) {
    $btnaddtocard = $product['id'];
    $quantity = $product['quantity'];
    
    // Lấy tên và giá sản phẩm từ database dựa trên ID
    $product_name = "Sản phẩm 1"; // Lấy tên sản phẩm từ database
    $product_price = 100000; // Lấy giá sản phẩm từ database

    echo "<tr>";
    echo "<td>$btnaddtocard</td>";
    echo "<td>$product_name</td>";
    echo "<td>$quantity</td>";
    echo "<td>" . number_format($product_price * $quantity, 0, ',', '.') . "</td>";
    echo "</tr>";
  }

  echo "</table>";
} else {
  echo "Giỏ hàng trống!";
}

?>