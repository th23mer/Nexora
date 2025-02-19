<?php
include "includes/head.php";

if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

if (isset($_GET['delete']) && isset($_SESSION['user_id'])) {
    $item_id = intval($_GET['delete']);
    delete_from_favorites($_SESSION['user_id'], $item_id);
    get_redirect("favorites.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_favorite'])) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['pending_favorite'] = intval($_POST['item_id']);
        get_redirect("login.php");
    } else {
        $item_id = intval($_POST['item_id']);
        add_to_favorites($_SESSION['user_id'], $item_id);
        echo "<script>alert('Product added to favorites');</script>";
    }
}

if (isset($_SESSION['pending_favorite']) && isset($_SESSION['user_id'])) {
    $item_id = $_SESSION['pending_favorite'];
    unset($_SESSION['pending_favorite']);
    add_to_favorites($_SESSION['user_id'], $item_id);
}
?>

<body>
  <div class="site-wrap">
    <?php include "includes/header.php"; ?>
    <div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0">
            <a href="index.php">Home</a> <span class="mx-2 mb-0">/</span>
            <strong class="text-black">Favorites</strong>
          </div>
        </div>
      </div>
    </div>
    <div class="site-section">
      <div class="container">
        <?php
        if (isset($_SESSION['user_id'])) {
            $data = get_favorites($_SESSION['user_id']);
            if (!empty($data)) {
        ?>
                <div class="row mb-5">
                    <form action="favorites.php" class="col-md-12" method="post">
                        <div class="site-blocks-table">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="product-thumbnail">Image</th>
                                        <th class="product-name">Product</th>
                                        <th class="product-price">Price</th>
                                        <th class="product-remove">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $item): ?>
                                        <tr>
                                            <td class="product-thumbnail">
                                                <img src="images/<?php echo htmlspecialchars($item['item_image']); ?>" alt="Image" class="img-fluid">
                                            </td>
                                            <td class="product-name">
                                                <h2 class="h5 text-black"><?php echo htmlspecialchars($item['item_title']); ?></h2>
                                            </td>
                                            <td>TND<?php echo number_format($item['item_price'], 2); ?></td>
                                            <td><a href="favorites.php?delete=<?php echo (int) $item['item_id']; ?>" class="btn btn-primary height-auto btn-sm">Delete</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
        <?php
            } else {
                echo "<h1 style='text-align: center; color:black;'>Your favorites is empty</h1>";
                echo "<div style='display: flex; justify-content: center; align-items: center; height: 30vh;'><img style='width:150px;' src='images/nofavorites.gif' alt=''>";
            }
        } else {
            echo "<h1 style='text-align: center; color:black;'>Please log in to view your favorites</h1>";
            echo "<div style='text-align: center;'><a href='login.php' class='btn btn-primary'>Login</a></div>";
        }
        ?>
      </div>
    </div>
    <?php include "includes/footer.php"; ?>
  </div>
</body>
</html>