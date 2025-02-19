<?php
include "includes/head.php";
require_once "includes/functions.php"; // Import functions.php
?>
<body>
  <div class="site-wrap">
    <?php include "includes/header.php"; ?>
    <div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0">
            <a href="index.php">Home</a> <span class="mx-2 mb-0">/</span>
            <strong class="text-black">Store</strong>
          </div>
        </div>
      </div>
    </div>
    <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-md-3">
            <?php include "includes/sidebar.php"; ?>
          </div>
          <div class="col-md-9">
            <div class="row">
              <?php
              $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
              $category = isset($_GET['category']) ? $_GET['category'] : '';
              $min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
              $max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : PHP_INT_MAX;

              if ($filter === 'new') {
                  $data = get_new_items(); 
              }if ($filter === 'sale') {
                  $data = get_sale_items(); 
              }elseif ($search = isset($_GET['search'])) {
                  $data = search(); 
              }else {
                  $data = searchFilter($category, $min_price, $max_price); // Apply filters
              }
              if (empty($data)) {
                  $data = all_products();
              }

              if (!empty($data)) {
                  foreach ($data as $product) { ?>
                      <div class="col-sm-6 col-lg-4 text-center item mb-4">
                          <a href="product.php?product_id=<?php echo htmlspecialchars($product['item_id']); ?>">
                              <img class="rounded mx-auto d-block" style="width:270px; height:270px;" src="images/<?php echo htmlspecialchars($product['item_image']); ?>" alt="Image">
                          </a>
                          <?php $title = strlen($product['item_title']) <= 20 ? $product['item_title'] : substr($product['item_title'], 0, 20) . "..."; ?>
                          <h3 class="text-dark">
                              <a href="product.php?product_id=<?php echo htmlspecialchars($product['item_id']); ?>">
                                  <?php echo htmlspecialchars($title); ?>
                              </a>
                          </h3>
                          <p class="price"><?php echo htmlspecialchars($product['item_price']); ?> TND</p>
                      </div>
                  <?php }
              } else { ?>
                  <div class="text-center col-12">
                      <img class="img-fluid" style="margin-top: -90px;" src="images/1.gif">
                      <h3>No products available.</h3>
                  </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <?php include "includes/footer.php"; ?>
  </div>
</body>
</html>