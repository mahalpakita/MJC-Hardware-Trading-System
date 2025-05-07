<?php 
require_once('redirect.php');
allowOnlyCashier();
?>

<?php
ob_start();

include '../includes/connection.php';

if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php"); // Redirect to the login page
    exit(); // Stop script execution
}
// Fetch user's branch
$stmt = mysqli_prepare($db, "SELECT BRANCH_ID FROM users WHERE ID = ?");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['MEMBER_ID']);
mysqli_stmt_execute($stmt);
$rowUser = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) or die('Error: BRANCH_ID not found.');
$branchID = $_SESSION['BRANCH_ID'] = $rowUser['BRANCH_ID'];

// Check existing cart session in pos_cart
$stmt = mysqli_prepare($db, "SELECT SESSION_ID FROM pos_cart WHERE BRANCH_ID = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $branchID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $_SESSION['cart_session_id'] = $row['SESSION_ID']; // Use existing cart session
} else {
    $_SESSION['cart_session_id'] = session_id(); // Create new session if none exists
}

$sessionID = $_SESSION['cart_session_id'];

// Fetch cart items from pos_cart
$stmt = mysqli_prepare($db, "SELECT * FROM pos_cart WHERE SESSION_ID = ?");
mysqli_stmt_bind_param($stmt, "s", $sessionID);
mysqli_stmt_execute($stmt);
$cartItems = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

if (!isset($_SESSION['pointofsale']) || empty($_SESSION['pointofsale'])) {
    // I-reload ang session mula sa database
    $_SESSION['pointofsale'] = [];

    $sessionID = $_SESSION['cart_session_id'];
    $stmt = mysqli_prepare($db, "SELECT * FROM pos_cart WHERE SESSION_ID = ? AND BRANCH_ID = ?");
    mysqli_stmt_bind_param($stmt, "si", $sessionID, $_SESSION['BRANCH_ID']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['pointofsale'][] = [
            'id'       => $row['PRODUCT_ID'],
            'name'     => $row['PRODUCT_NAME'],
            'price'    => floatval($row['PRICE']),  // ✅ Convert to float
            'quantity' => bcadd($row['QUANTITY'], '0', 2)  // ✅ Ensure 2 decimal places
        ];
    }    
}

// Fetch user type and include the appropriate sidebar
$stmt = mysqli_prepare($db, "SELECT TYPE_ID FROM users WHERE ID = ?");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['MEMBER_ID']);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) or die('User not found');

$sidebars = [1 => 'sidebar.php', 2 => 'sidebar_cashier.php', 3 => 'sidebar_manager.php'];
include '../includes/' . ($sidebars[$row['TYPE_ID']] ?? die('Unauthorized User Type'));

// Add product to cart
if (($_GET['action'] ?? '') === 'add' && isset($_GET['id'], $_POST['quantity'])) {
    $productId = intval($_GET['id']);
    $quantity = round(max(0.01, floatval($_POST['quantity'])), 2); // ✅ Ensure valid quantity, allowing 0.01

    // Fetch product details
    $stmt = mysqli_prepare($db, "SELECT NAME, PRICE FROM product WHERE PRODUCT_ID = ?");
    mysqli_stmt_bind_param($stmt, "i", $productId);
    mysqli_stmt_execute($stmt);
    $product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) or die('Error: Product not found.');

    $price = round(floatval($product['PRICE']), 2);
    $total = bcmul($price, $quantity, 2);

    // Check if the product already exists in pos_cart
    $stmt = mysqli_prepare($db, "SELECT QUANTITY, TOTAL FROM pos_cart WHERE SESSION_ID = ? AND PRODUCT_ID = ? AND BRANCH_ID = ?");
    mysqli_stmt_bind_param($stmt, "sii", $_SESSION['cart_session_id'], $productId, $_SESSION['BRANCH_ID']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $existingItem = mysqli_fetch_assoc($result);

    if ($existingItem) {
        // Update existing quantity and total price with rounding
        $newQuantity = round(floatval($existingItem['QUANTITY']) + $quantity, 2);
        $newTotal = bcadd($existingItem['TOTAL'], bcmul($quantity, $price, 2), 2);

        $stmt = mysqli_prepare($db, "UPDATE pos_cart SET QUANTITY = ?, TOTAL = ? WHERE SESSION_ID = ? AND PRODUCT_ID = ? AND BRANCH_ID = ?");
        mysqli_stmt_bind_param($stmt, "disii", $newQuantity, $newTotal, $_SESSION['cart_session_id'], $productId, $_SESSION['BRANCH_ID']);
        mysqli_stmt_execute($stmt);
    } else {
        // Insert new product if not in cart
        $query = "INSERT INTO pos_cart (SESSION_ID, PRODUCT_ID, PRODUCT_NAME, QUANTITY, PRICE, TOTAL, BRANCH_ID, ADDED_AT)
                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "sisdddi", $_SESSION['cart_session_id'], $productId, $product['NAME'], $quantity, $price, $total, $_SESSION['BRANCH_ID']);
        mysqli_stmt_execute($stmt);
    }
}


if (isset($_POST['addpos'])) {
    $productId = intval($_GET['id']);
    $quantity = round(max(0.01, floatval($_POST['quantity'])), 2);

    // Deduct stock only if there's enough available
    $stmt = mysqli_prepare($db, "UPDATE product SET QTY_STOCK = QTY_STOCK - ? WHERE PRODUCT_ID = ? AND QTY_STOCK >= ?");
    mysqli_stmt_bind_param($stmt, "dii", $quantity, $productId, $quantity);
    mysqli_stmt_execute($stmt);

    // Check if stock was actually updated
    if (mysqli_stmt_affected_rows($stmt) === 0) {
        die('Error: Not enough stock.');
    }
}

// Add product to session cart
if (isset($_POST['addpos'])) {
    $id = intval($_GET['id']);
    $name = $_POST['name'];
    $price = round(floatval($_POST['price']), 2);
    $quantity = bcadd($_POST['quantity'], '0', 2); // ✅ Ensures 2 decimal places

    $_SESSION['pointofsale'] = $_SESSION['pointofsale'] ?? [];
    foreach ($_SESSION['pointofsale'] as &$product) {
        if ($product['id'] == $id) {
            $product['quantity'] = bcadd($product['quantity'], $quantity, 2); // ✅ No floating-point errors
            header("Location: pos.php");
            exit();
        }
    }
    $_SESSION['pointofsale'][] = compact('id', 'name', 'price', 'quantity');
    header("Location: pos.php");
    exit();
}


// Remove product from cart
if (($_GET['action'] ?? '') === 'delete' && isset($_GET['id'])) {
    $productId = intval($_GET['id']);
    $sessionID = $_SESSION['cart_session_id'];

    // Retrieve total quantity before deletion
    $stmt = mysqli_prepare($db, "SELECT SUM(QUANTITY) AS total_qty FROM pos_cart WHERE SESSION_ID = ? AND PRODUCT_ID = ?");
    mysqli_stmt_bind_param($stmt, "si", $sessionID, $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $cartItem = mysqli_fetch_assoc($result);

    if ($cartItem && $cartItem['total_qty'] > 0) {
        // Restore stock
        $stmt = mysqli_prepare($db, "UPDATE product SET QTY_STOCK = QTY_STOCK + ? WHERE PRODUCT_ID = ?");
        mysqli_stmt_bind_param($stmt, "di", $cartItem['total_qty'], $productId);
        mysqli_stmt_execute($stmt);
    }

    // Delete item from cart
    $stmt = mysqli_prepare($db, "DELETE FROM pos_cart WHERE SESSION_ID = ? AND PRODUCT_ID = ?");
    mysqli_stmt_bind_param($stmt, "si", $sessionID, $productId);
    mysqli_stmt_execute($stmt);

    // Remove from session cart
    $_SESSION['pointofsale'] = array_values(array_filter($_SESSION['pointofsale'], fn($p) => $p['id'] != $productId));

    header("Location: pos.php");
    exit();
}
if (($_GET['action'] ?? '') === 'decrease' && isset($_GET['id'])) {
    $productId = intval($_GET['id']);
    $sessionID = $_SESSION['cart_session_id'];

    // Get the current quantity of the product in the cart
    $stmt = mysqli_prepare($db, "SELECT QUANTITY FROM pos_cart WHERE SESSION_ID = ? AND PRODUCT_ID = ?");
    mysqli_stmt_bind_param($stmt, "si", $sessionID, $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $cartItem = mysqli_fetch_assoc($result);

    if ($cartItem && $cartItem['QUANTITY'] > 1) {
        // Reduce quantity by 1
        $newQuantity = $cartItem['QUANTITY'] - 1;

        // Update quantity in pos_cart
        $stmt = mysqli_prepare($db, "UPDATE pos_cart SET QUANTITY = ? WHERE SESSION_ID = ? AND PRODUCT_ID = ?");
        mysqli_stmt_bind_param($stmt, "isi", $newQuantity, $sessionID, $productId);
        mysqli_stmt_execute($stmt);

        // Return 1 quantity back to stock
        $stmt = mysqli_prepare($db, "UPDATE product SET QTY_STOCK = QTY_STOCK + 1 WHERE PRODUCT_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $productId);
        mysqli_stmt_execute($stmt);
    } else {
        // If only 1 left, remove from cart and restore stock
        $stmt = mysqli_prepare($db, "DELETE FROM pos_cart WHERE SESSION_ID = ? AND PRODUCT_ID = ?");
        mysqli_stmt_bind_param($stmt, "si", $sessionID, $productId);
        mysqli_stmt_execute($stmt);

        $stmt = mysqli_prepare($db, "UPDATE product SET QTY_STOCK = QTY_STOCK + 1 WHERE PRODUCT_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $productId);
        mysqli_stmt_execute($stmt);
    }

    // Update session cart
    foreach ($_SESSION['pointofsale'] as &$product) {
        if ($product['id'] == $productId) {
            $product['quantity'] -= 1;
            if ($product['quantity'] <= 0) {
                $_SESSION['pointofsale'] = array_values(array_filter($_SESSION['pointofsale'], fn($p) => $p['id'] != $productId));
            }
            break;
        }
    }

    header("Location: pos.php");
    exit();
}

if (($_GET['action'] ?? '') === 'increase' && isset($_GET['id'])) {
    $productId = intval($_GET['id']);
    $sessionID = $_SESSION['cart_session_id'];

    // Check available stock
    $stmt = mysqli_prepare($db, "SELECT QTY_STOCK FROM product WHERE PRODUCT_ID = ?");
    mysqli_stmt_bind_param($stmt, "i", $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);

    if ($product && $product['QTY_STOCK'] > 0) {
        // Increase quantity by 1 in pos_cart
        $stmt = mysqli_prepare($db, "UPDATE pos_cart SET QUANTITY = QUANTITY + 1 WHERE SESSION_ID = ? AND PRODUCT_ID = ?");
        mysqli_stmt_bind_param($stmt, "si", $sessionID, $productId);
        mysqli_stmt_execute($stmt);

        // Deduct 1 from stock
        $stmt = mysqli_prepare($db, "UPDATE product SET QTY_STOCK = QTY_STOCK - 1 WHERE PRODUCT_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $productId);
        mysqli_stmt_execute($stmt);

        // Update session cart
        foreach ($_SESSION['pointofsale'] as &$product) {
            if ($product['id'] == $productId) {
                $product['quantity'] += 1;
                break;
            }
        }
    }

    header("Location: pos.php");
    exit();
}

?>
<link rel="stylesheet" href="cart.css" />
<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <h4 class="m-2 font-weight-bold text-primary text-left">Point of Sale</h4>
            </div>
            <div class="card-body">
                <form role="form" method="post" action="pos_transac.php?action=add">
                    <input type="hidden" name="employee" value="<?php echo $_SESSION['FIRST_NAME']; ?>">
                    <input type="hidden" name="role" value="<?php echo $_SESSION['JOB_TITLE']; ?>">
                    
                    <div class="table-responsive">
                        
                    <table class="table">    
    <tr>  
        <th>Product</th>
        <th>Description</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Total</th>
        <th>Action</th>
    </tr>  

    <?php  
    $total = 0;
    if (!empty($cartItems)):  
        foreach ($cartItems as $item):  
            // Fetch product description from database
            $stmt = mysqli_prepare($db, "SELECT DESCRIPTION FROM product WHERE PRODUCT_ID = ?");
            mysqli_stmt_bind_param($stmt, "i", $item['PRODUCT_ID']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $productData = mysqli_fetch_assoc($result);
            $description = $productData['DESCRIPTION'] ?? 'No description available';
    ?>  

<tr>  
    <td>
        <input type="hidden" name="product_id[]" value="<?php echo $item['PRODUCT_ID']; ?>">
        <input type="hidden" name="name[]" value="<?php echo htmlspecialchars($item['PRODUCT_NAME']); ?>">
        <?php echo htmlspecialchars($item['PRODUCT_NAME']); ?>
    </td>
    <td>
        <?php echo htmlspecialchars($description); ?>
    </td>
    <td>
        <input type="hidden" name="quantity[]" value="<?php echo $item['QUANTITY']; ?>">

        <div class="d-flex align-items-center">
            <!-- Minus Button -->
            <a href="pos.php?action=decrease&id=<?php echo $item['PRODUCT_ID']; ?>">
                <button class="btn btn-danger btn-sm mx-2" type="button">-</button>
            </a>

            <!-- Quantity Display -->
            <span class="fw-bold mx-2"><?php echo intval($item['QUANTITY']); ?></span>

            <!-- Plus Button -->
            <a href="pos.php?action=increase&id=<?php echo $item['PRODUCT_ID']; ?>">
                <button class="btn btn-success btn-sm mx-2" type="button">+</button>
            </a>
        </div>
    </td>

    <td>
        <input type="hidden" name="price[]" value="<?php echo $item['PRICE']; ?>">
        ₱ <?php echo formatPrice($item['PRICE']); ?>
    </td>  
    <td>
        <input type="hidden" name="total[]" value="<?php echo $item['QUANTITY'] * $item['PRICE']; ?>">
        ₱ <?php echo formatPrice($item['QUANTITY'] * $item['PRICE']); ?>
    </td> 
    <td>
        <!-- Delete Button -->
        <a href="pos.php?action=delete&id=<?php echo $item['PRODUCT_ID']; ?>">
            <div class="btn bg-gradient-danger btn-danger">
                <i class="fas fa-fw fa-trash"></i>
            </div>
        </a>
    </td>
</tr>


    </tr>
    <?php  
        $total += round(floatval($item['TOTAL']), 2);
        endforeach;  
    else:
        echo "<tr><td colspan='5'>No items in cart.</td></tr>";
    endif;
    ?>
</table>
</div>
  
<?php include 'posside.php'; ?>
</div>
</div>
<div class="col-md-6">
    <div class="card shadow mb-0">
        <div class="card-header py-2 d-flex justify-content-between align-items-center">
            <h4 class="m-2 font-weight-bold text-primary">Product</h4>
            <input type="text" id="searchAll" class="form-control w-50" placeholder="Search products...">
        </div>
        <div class="card-body" id="productSection">
            <?php
            function formatPrice($price) {
                if (fmod($price, 1) == 0) {
                    return number_format($price, 0); 
                } elseif (fmod($price, 0.10) == 0) {
                    return number_format($price, 1); 
                } else {
                    return number_format($price, 2); 
                }
            }

            $queryUser = "SELECT BRANCH_ID FROM users WHERE ID = " . intval($_SESSION['MEMBER_ID']);
            $resultUser = mysqli_query($db, $queryUser) or die(mysqli_error($db));
            $userBranch = mysqli_fetch_assoc($resultUser)['BRANCH_ID'];

            function displayProducts($db, $userBranch) {
                $query = "SELECT * FROM product 
                          WHERE BRANCH_ID = '$userBranch' 
                          GROUP BY PRODUCT_ID 
                          ORDER BY (QTY_STOCK = 0) ASC, PRODUCT_ID ASC";

                $result = mysqli_query($db, $query);

                echo '<div class="row" id="productList">';
                while ($product = mysqli_fetch_assoc($result)) {
                    $stock = intval($product['QTY_STOCK']);
                    echo '<div class="col-sm-4 col-md-3 product-item">
                    <form method="post" action="pos.php?action=add&id=' . $product['PRODUCT_ID'] . '">
                        <div class="products ' . ($product['QTY_STOCK'] == 0 ? 'out-of-stock' : '') . '">
                            <h6 class="text-dark product-name">' . htmlspecialchars($product['NAME']) . '</h6>
                            <h6>₱ ' . number_format($product['PRICE'], 2) . '</h6>
                            <p class="product-description">' . htmlspecialchars($product['DESCRIPTION']) . '</p>
                            <p><strong>Stock:</strong> ' . ($stock > 0 ? $stock : '<span class="text-danger">Out of Stock</span>') . '</p>';
            
                    if ($stock > 0) {
                        echo '<input type="number" name="quantity" class="form-control quantity-input" 
                                      value="1" min="1" max="' . $stock . '" step="1" required />
                              <input type="hidden" name="name" value="' . htmlspecialchars($product['NAME']) . '" />
                              <input type="hidden" name="price" value="' . $product['PRICE'] . '" />
                              <small class="text-danger quantity-error" style="display: none;">Must be within stock.</small>
                              <input type="submit" name="addpos" class="btn btn-primary add-to-cart-btn" value="Add" disabled />';
                    }  
                    echo '</div></form></div>';
                }
                echo '</div>';
            }
            displayProducts($db, $userBranch);
            ?>
        </div>
    </div>
</div>

<script>
document.getElementById("searchAll").addEventListener("keyup", function() {
    let input = this.value.toLowerCase();
    document.querySelectorAll(".product-item").forEach(product => {
        let productName = product.querySelector(".product-name").textContent.toLowerCase();
        let productDescription = product.querySelector(".product-description").textContent.toLowerCase();

        // Show product if input matches name OR description
        if (productName.includes(input) || productDescription.includes(input)) {
            product.style.display = "block";
        } else {
            product.style.display = "none";
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".quantity-input").forEach(input => {
        let addButton = input.closest("form").querySelector(".add-to-cart-btn");
        let maxStock = parseInt(input.getAttribute("max"));

        function validateInput() {
            let value = input.value.trim();
            
            // Prevent leading zero
            if (value.startsWith("0")) {
                input.value = value.replace(/^0+/, ""); // Remove leading zeros
            }

            let intValue = parseInt(input.value);
            let isValid = !isNaN(intValue) && intValue >= 1 && intValue <= maxStock;
            
            addButton.disabled = !isValid; 
        }

        input.addEventListener("input", validateInput);
        input.addEventListener("keydown", function (event) {
            const allowedKeys = ["Backspace", "Delete", "ArrowLeft", "ArrowRight", "ArrowUp", "ArrowDown", "Tab", "Enter"];
            
            if (!/[0-9]/.test(event.key) && !allowedKeys.includes(event.key)) {
                event.preventDefault();
            }

            if (event.key === "Enter") {
                event.preventDefault();
                validateInput();
                if (!addButton.disabled) {
                    addButton.click(); 
                }
            }
        });

        validateInput();
    });
});
</script>



<?php include '../includes/footer.php'; ?>
<?php ob_end_flush(); // Send the output buffer to the browser ?>