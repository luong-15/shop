<?php
require_once ('./connect.php');
session_start();

$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$iduser = isset($_SESSION['iduser']) ? $_SESSION['iduser'] : null;

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
}

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

if (empty($products)) {
    echo "<p>Cart is empty!</p>";
    echo "<a href = 'index.php'><button>Cancel</button></a>";
} else {
    echo "$iduser, $username";

    if (isset($_GET['action']) && $_GET['action'] == 'delete') {
        $entyid = $_GET['id'];

        $sql = "DELETE FROM shop.shoppingcart WHERE entyid = $entyid";
        $result = $conn->query($sql);

        if ($result) {
            header('Location: shoppingcart.php');
            echo "<script>alert('Product deletion successful!');</script>";
            exit;
        } else {
            echo "<p>Error when deleting product: " . $conn->error . "</p>";
        }
    }

    if (isset($_POST['updateCart'])) {
        foreach ($products as $product) {
            $entyid = $product['entyid'];
            $quantity = isset($_POST['quantity'][$entyid]) ? intval($_POST['quantity'][$entyid]) : 0;

            $sql = "UPDATE shop.shoppingcart SET quantity = $quantity WHERE entyid = $entyid";
            $conn->query($sql);
        }

        header('Location: shoppingcart.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shopping Cart</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        table {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 5px;
        }
    </style>
</head>

<body>
    <?php if (!empty($iduser) && !empty($products)): ?>
        <form method="post">
            <h1>Shopping Cart</h1>
            <table border="1">
                <tr>
                    <th>Name product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Delete</th>
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
                        <td>
                            <a href="shoppingcart.php?action=delete&id=<?php echo $product['entyid']; ?>">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table><br>
            <p>Total amount: <span id="totalPrice">0 ₫</span></p>
            <button type="submit" name="updateCart">Update</button>
        </form>
        <a href="bill.php"><button>Pay</button></a>
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