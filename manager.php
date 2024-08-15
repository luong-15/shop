<?php
require ("./connect.php");

$table_name = $_GET["table_name"] ?? '';
$name = '';
$img = '';
$price = '';
$insert_successful = false;
$update_id = '';
$delete_id = '';
$errors = [];

function format_price($price, $decimal_places = 0, $decimal_separator = '.', $thousands_separator = '.')
{
    return number_format($price, $decimal_places, $decimal_separator, $thousands_separator);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['select_table'])) {
        $table_name = trim($_POST['table_name']);
        $name = "";
        $img = "";
        $price = "";
        $insert_successful = false;
        $update_id = '';
    } else if (isset($_POST['add_product'])) {
        if ($table_name) {
            $name = trim($_POST["name"]);
            $img = trim($_POST["img"]);
            $price = trim($_POST["price"]);

            if (empty($name)) {
                $errors[] = "Please enter a product name.";
            }

            if (empty($img)) {
                $errors[] = "Please enter a product image URL.";
            }

            $price = str_replace(['.', ' '], '', $price);
            if (!is_numeric($price) || $price <= 0) {
                $errors[] = "Price must be a positive number.";
            } else {
                $price = format_price($price);
            }

            if ($errors) {
                echo "<ul style='color: red;'>";
                foreach ($errors as $error) {
                    echo "<li>$error</li>";
                }
                echo "</ul>";
            } else {
                $sql = "INSERT INTO " . $table_name . " (name, img, price) VALUES ('$name', '$img', '$price')";
                if (mysqli_query($conn, $sql)) {
                    $insert_successful = true;
                    echo "<p style='color: green;'>Product added successfully!</p>";
                    $name = "";
                    $img = "";
                    $price = "";
                } else {
                    echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
                }
            }
        }
    } else if (isset($_POST['update_product'])) {
        if (isset($_POST['update_id']) && $table_name) {
            $update_id = trim($_POST['update_id']);
            $name = trim($_POST['name']);
            $img = trim($_POST['img']);
            $price = trim($_POST['price']);

            if (empty($name)) {
                $errors[] = "Please enter a product name.";
            }

            if (empty($img)) {
                $errors[] = "Please enter a product image URL.";
            }

            $price = str_replace(['.', ' '], '', $price);
            if (!is_numeric($price) || $price <= 0) {
                $errors[] = "Price must be a positive number.";
            } else {
                $price = format_price($price);
            }

            if ($errors) {
                echo "<ul style='color: red;'>";
                foreach ($errors as $error) {
                    echo "<li>$error</li>";
                }
                echo "</ul>";
            } else {
                $sql = "UPDATE " . $table_name . " SET name='$name', img='$img', price='$price' WHERE id=$update_id";
                if (mysqli_query($conn, $sql)) {
                    echo "<p style='color: green;'>Product updated successfully!</p>";
                    $updateSucceSS = true;
                    echo "<script>var updateSuccess = true;</script>";
                    $update_id = '';
                    $name = '';
                    $img = '';
                    $price = '';
                } else {
                    echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
                }
            }
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'update') {
    $update_id = trim($_GET['update_id']);
    if ($update_id && $table_name) {
        $sql = "SELECT * FROM " . $table_name . " WHERE id = $update_id";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $name = $row['name'];
            $img = $row['img'];
            $price = $row['price'];
        } else {
            echo "<p style='color: red;'>Product not found.</p>";
            $name = '';
            $img = '';
            $price = '';
            $update_id = '';
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $delete_id = trim($_GET['delete_id']);
    if ($delete_id && $table_name) {
        $sql = "DELETE FROM " . $table_name . " WHERE id = $delete_id";
        if (mysqli_query($conn, $sql)) {
            echo "<p style='color: green;'>Product deleted successfully!</p>";
            $name = "";
            $img = "";
            $price = "";
            $delete_id = '';
        } else {
            echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Data Management</title>
</head>

<body>
    <h1>Data Management</h1>

    <form action="" method="POST">
        <label for="table_name">Select table:</label>
        <select name="table_name" id="table_name">
            <option value="">-- Please select --</option>
            <option value="shop.phone" <?php if ($table_name == 'shop.phone')
                echo 'selected' ?>>Điện thoại</option>
                <option value="shop.tv" <?php if ($table_name == 'shop.tv')
                echo 'selected' ?>>TV</option>
                <option value="shop.washing" <?php if ($table_name == 'shop.washing')
                echo 'selected' ?>>Máy giặt</option>
                <option value="shop.fridge" <?php if ($table_name == 'shop.fridge')
                echo 'selected' ?>>Tủ lạnh</option>
                <option value="shop.accessory" <?php if ($table_name == 'shop.accessory')
                echo 'selected' ?>>Phụ kiện</option>
            </select>
            <button type="submit" name="select_table">Select</button><br><br>
        </form>

        <?php
            if ($table_name) {
                echo '<table border="1">';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Name</th>';
                echo '<th>Image</th>';
                echo '<th>Price</th>';
                echo '<th>Action</th>';
                echo '</tr>';

                $sql = "SELECT * FROM " . $table_name;
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row["id"] . '</td>';
                        echo '<td>' . $row["name"] . '</td>';
                        echo '<td><img src="' . $row["img"] . '" style="width: 80px; height: 80px; object-fit: cover; object-position: 50% 50%;"></td>';
                        echo '<td>' . $row["price"] . '₫</td>';
                        echo '<td>';
                        echo '<a href="manager.php?action=update&table_name=' . $table_name . '&update_id=' . $row["id"] . '">Edit</a> | ';
                        echo '<a href="manager.php?action=delete&table_name=' . $table_name . '&delete_id=' . $row["id"] . '" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo "<tr>";
                    echo "<td colspan='5'>Không có dữ liệu nào trong " . $table_name . ".</td>";
                    echo "</tr>";
                }

                echo '</table>';
            }
            ?>

    <?php
    if ($insert_successful || ($update_id == '' && $delete_id == '')) { ?>
        <h2>Add product</h2>
        <form action="" method="POST">
            <input type="hidden" name="table_name" value="<?php echo $table_name; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required><br>

            <label for="img">Image:</label>
            <input type="text" id="img" name="img" value="<?php echo isset($img) ? $img : ''; ?>" required><br>

            <label for="price">Price:</label>
            <input type="text" id="price" name="price" value="<?php echo isset($price) ? $price : ''; ?>" required><br>

            <button type="submit" name="add_product">Add</button>
        </form>
    <?php } ?>

    <?php
    if ($update_id != '') { ?>
        <h2>Update product</h2>
        <form action="" method="POST">
            <input type="hidden" name="table_name" value="<?php echo $table_name; ?>">
            <input type="hidden" name="update_id" value="<?php echo $update_id; ?>">

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required><br>

            <label for="img">Image:</label>
            <input type="text" id="img" name="img" value="<?php echo $img; ?>" required><br>

            <label for="price">Price:</label>
            <input type="text" id="price" name="price" value="<?php echo $price; ?>" required><br>

            <button type="submit" name="update_product">Save</button>
        </form>
    <?php } ?>

    <script>
        function loadForm() {
            // let updateSuccess = 'false';
            if (updateSuccess) {
                const formElements = document.querySelectorAll('form input, form textarea');

                formElements.forEach(element => {
                    if (element.type === 'checkbox' || element.type === 'radio') {
                        element.checked = false;
                    } else {
                        element.value = '';
                    }
                });
            }
        }

        if (typeof updateSuccess !== 'undefined' && updateSuccess) {
            const selectedValue = document.getElementById('table_name').value;
            window.location.href = "manager.php?tavle_name=" + selectedValue;
        }
    </script>

</body>

</html>