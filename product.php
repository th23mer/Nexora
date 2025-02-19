<?php
include "includes/head.php";

// Check if the product ID is set in the URL
if (!isset($_GET['product_id'])) {
    get_redirect("store.php");
}

// Fetch the product data
$data = get_item();
if (empty($data)) {
    get_redirect("store.php");
}

// Handle adding to favorites
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['favorite'])) {
    if (!isset($_SESSION['user_id'])) {
        // Store the item ID in a session for redirection after login
        $_SESSION['pending_favorite'] = intval($_POST['item_id']);
        get_redirect("login.php");
    } else {
        $item_id = intval($_POST['item_id']);
        add_to_favorites($_SESSION['user_id'], $item_id);
        get_redirect("favorites.php");
        // echo "<script>alert('Product added to favorites');</script>";
    }
}
?>

<body>
  <div class="site-wrap">
    <?php include "includes/header.php"; ?>
    <div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0">
            <a href="index.php">Home</a> / 
            <a href="store.php">Store</a> / 
            <strong class="text-black"><?php echo htmlspecialchars($data[0]['item_title']); ?></strong>
          </div>
        </div>
      </div>
    </div>
    <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-md-5 mr-auto">
            <div class="border text-center">
              <img src="images/<?php echo htmlspecialchars($data[0]['item_image']); ?>" alt="Product Image" class="img-fluid p-5">
            </div>
          </div>
          <div class="col-md-6">
            <h2 class="text-black"><?php echo htmlspecialchars($data[0]['item_title']); ?></h2>
            <p><?php echo htmlspecialchars($data[0]['item_details']); ?></p>
            <p><strong class="text-primary h4"><?php echo htmlspecialchars($data[0]['item_price']); ?> TND</strong></p>
            <?php if ($data[0]['item_quantity'] > 0): ?>
              <h6 style="color: rgb(58, 211, 58);">In Stock</h6>
            <?php else: ?>
              <h6 style="color: red;">Out of Stock</h6>
            <?php endif; ?>

            <!-- Add to Favorites Form -->
            <form action="product.php?product_id=<?php echo htmlspecialchars($data[0]['item_id']); ?>" method="POST">
              <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($data[0]['item_id']); ?>">
              <button name="favorite" type="submit" class="btn btn-primary btn-lg">
                <?php if (isset($_SESSION['user_id'])): ?>
                  Add to Favorites
                <?php else: ?>
                  Login to Add to Favorites
                <?php endif; ?>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php include "includes/footer.php"; ?>
  </div>
</body>
</html>