<?php
require_once('./connect.php');
session_start();
$username = $_SESSION['username'];
$iduser = $_SESSION['iduser'];

if (!isset($iduser)) {
    echo "You are not logged in. Please log in! <tr>";
    echo "<a href = 'login.php'><button>Login</button></a>";
    exit;
} else {
    $sql = "SELECT * FROM shop.shoppingcart WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>
<body>
<?php if (!empty($iduser) && !empty($products)): ?>
        <form method="post">
            <h1>Bill</h1>
            <p><b>Tên khách hàng:</b> <?php echo $username; ?></p>
            <label for="address">Địa chỉ:</label>
        <input type="text" id="address" name="address" required>
        <br>
        <label for="payment">Phương thức thanh toán:</label>
        <select name="payment" id="payment">
            <option value="tien-mat">Tiền mặt</option>
            <option value="chuyen-khoan">Chuyển khoản</option>
            </select><br><br>
            <table border="1">
                <tr>
                    <th>Name product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['name']; ?></td>
                        <td>
                            <input type="number" name="quantity[<?php echo $product['entyid']; ?>]"
                                value="<?php echo $product['quantity']; ?>" min="1" class="quantity-input">
                        </td>
                        <td data-price="<?php echo $product['price']; ?>" class="price">
                            <?php echo $product['price']; ?>
                        </td>
                        <td class="Subtotal"></td>
                    </tr>
                <?php endforeach; ?>
            </table> 
            <p>Total amount: <span id="totalPrice">0 ₫</span></p>
            <button type="submit" name="checkout">Confirm</button><br><br>
        </form>
        <a href="shoppingcart.php"><button>Back</button></a>
        <a href="index.php"><button>Cancel</button></a>
    <?php endif; ?>

    <script>
        $(document).ready(function () {
            updateSubtotal();
            calculateTotalPrice();

            $('.quantity-input').on('input', function () {
                updateSubtotal(this);
                calculateTotalPrice();
            });

            function updateSubtotal() {
                $('.Subtotal').each(function (index) {
                    var row = $(this).closest('tr');
                    var quantityInput = $(row).find('.quantity-input');
                    var priceCell = $(row).find('.price');
                    var SubtotalCell = $(this);

                    var quantity = parseInt(quantityInput.val());
                    var price = parseFloat(priceCell.data('price').replace(/\./g, ''));
                    var Subtotal = quantity * price;

                    SubtotalCell.text(`${formatNumber(Subtotal)} ₫`);

                });
            }

            function calculateTotalPrice() {
                var totalPrice = 0;
                $('.Subtotal').each(function () {
                    var subtotal = parseFloat($(this).text().replace(/\₫/g, '').replace(/\./g, ''));
                    totalPrice += subtotal;
                });

                $('#totalPrice').text(`${formatNumber(totalPrice)} ₫`);
            }

            function formatNumber(number) {
                let numberString = number.toFixed(0);
                let digits = numberString.split('');
                digits.reverse();
                for (let i = 3; i < digits.length; i += 4) {
                    digits.splice(i, 0, '.');
                }
                let formattedNumberString = digits.join('');
                formattedNumberString = formattedNumberString.split('').reverse().join('');

                return formattedNumberString;
            }
        });
    </script>
</body>
</html>