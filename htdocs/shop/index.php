<?php
session_start();

include('./includes/header.php');
include('./includes/config.php');
?>

<div class="container mt-3">
  <?php include('./includes/alert.php'); ?>

  <?php
  // Login status / greeting
  if (isset($_SESSION['user_id'])) {
      // prefer a stored name if available, otherwise fallback to email
      $displayName = $_SESSION['name'] ?? $_SESSION['email'] ?? 'User';
      $role = $_SESSION['role'] ?? 'customer'; // default to customer if not set

      // sanitize for output
      $displayNameSafe = htmlspecialchars($displayName);
      $roleSafe = htmlspecialchars(ucfirst($role)); // show "Customer" or "Admin"

      echo "<div class='alert alert-secondary'>Hello, <strong>{$displayNameSafe}</strong> <span class='text-muted'>({$roleSafe})</span></div>";
  } else {
      echo '<div class="alert alert-info">You are browsing as a <strong>guest</strong>. <a href="/shop/user/login.php">Login</a> or <a href="/shop/user/register.php">Register</a></div>';
  }
  ?>
</div>

<?php
// Legacy cart preview block has been superseded by /user/cart.php.
// Product list
$sql = "SELECT i.item_id AS itemId, i.description, i.image_path, i.sell_price 
        FROM item i 
        INNER JOIN stock s USING (item_id)
        ORDER BY i.item_id ASC";

$results = mysqli_query($conn, $sql);

if ($results) {
    $products_item = '<ul class="products list-unstyled container d-flex flex-wrap gap-3">';

    while ($row = mysqli_fetch_assoc($results)) {
        $image = !empty($row['image_path']) ? $row['image_path'] : './item/images/default.png';
        $desc = htmlspecialchars($row['description']);
        $price = number_format((float)$row['sell_price'], 2);
        $itemId = (int)$row['itemId'];

        $products_item .= <<<EOT
        <li class="product card" style="width: 220px;">
            <form method="POST" action="user/add_to_cart.php">
                <div class="product-content p-2">
                    <h5 class="product-title">{$desc}</h5>
                    <div class="product-thumb text-center mb-2">
                        <img src="{$image}" alt="{$desc}" style="max-width:100%; height:100px; object-fit:cover;" />
                    </div>
                    <div class="product-info text-center mb-2">
                        <div class="fw-bold">₱{$price}</div>
                        <fieldset class="mb-2">
                            <label>
                                <span>Quantity</span>
                                <input type="number" class="form-control form-control-sm" style="width:70px; display:inline-block; margin-left:8px;" name="item_qty" value="1" />
                            </label>
                        </fieldset>
                        <input type="hidden" name="product_id" value="{$itemId}" />
                        <input type="hidden" name="type" value="product" />
                        <div class="d-grid"><button type="submit" class="btn btn-outline-primary btn-sm add_to_cart">Add</button></div>
                    </div>
                </div>
            </form>
        </li>
EOT;
    }

    $products_item .= '</ul>';
    echo $products_item;
}

include('./includes/footer.php');
?>
