<div class="site-navbar py-2">

    <div class="search-wrap">
        <div class="container">
            <a href="#" class="search-close js-search-close"><span class="icon-close2"></span></a>
            <form action="store.php" method="GET">
                <input type="text" name="search" class="form-control" placeholder="Search keyword and hit enter...">
            </form>
        </div>
    </div>
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div class="logo">
                <div class="site-logo">
                    <a href="index.php" class="js-logo-clone">Nexora</a>
                </div>
            </div>
            <div class="main-nav d-none d-lg-block">
                <nav class="site-navigation text-right text-md-center" role="navigation">
                    <ul class="site-menu js-clone-nav d-none d-lg-block">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="store.php?store=all">Store</a></li>
                        <li class="has-children">
                            <a href="#">Categories</a>
                            <ul class="dropdown">
                                <?php
                                // Fetch distinct categories from the database
                                $query = "SELECT DISTINCT item_cat AS category FROM item ORDER BY category ASC";
                                $categories = query($query);

                                // Check if categories exist
                                if (!empty($categories)) {
                                    foreach ($categories as $category) {
                                        $cat_name = htmlspecialchars($category['category']); // Sanitize output
                                        echo "<li><a href='store.php?cat=$cat_name'>$cat_name</a></li>";
                                    }
                                } else {
                                    echo "<li><a href='#'>No Categories Available</a></li>";
                                }
                                ?>
                            </ul>
                        </li>
                        <li><a href="about.php">About</a></li>
                    </ul>
                </nav>
            </div>
            <div class="icons">
                <a href="#" class="icons-btn d-inline-block js-search-open"><span class="icon-search"></span></a>
                <a href="favorites.php" class="icons-btn d-inline-block bag">
                    <span class="icon-heart"></span>
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        // Fetch the count of favorites for the current user
                        $user_id = $_SESSION['user_id'];
                        $query = "SELECT COUNT(*) AS favorite_count FROM favorites WHERE user_id = '$user_id'";
                        $result = query($query);

                        if (!empty($result)) {
                            $favorite_count = $result[0]['favorite_count'];
                        } else {
                            $favorite_count = 0;
                        }
                    } else {
                        // If the user is not logged in, set the count to 0
                        $favorite_count = 0;
                    }
                    ?>

                    <!-- Display the number of favorites -->
                    <span class="number">
                        <?php echo htmlspecialchars($favorite_count); ?>
                    </span>
                </a>
                <?php
                if (!isset($_SESSION['user_id'])) {
                    echo "<a href='login.php' class=' icons-btn d-inline-block '><span class='icon-sign-in'></span></a>";
                } else {
                    $check_user_id = check_user($_SESSION['user_id']);
                    if ($check_user_id == 1) {
                        echo "<a href='logout.php' class=' icons-btn d-inline-block '><span class='icon-sign-out'></span></a>";
                    } else {
                        post_redirect("logout.php");
                    }
                }
                ?>

                <a href="" class="site-menu-toggle js-menu-toggle ml-3 d-inline-block d-lg-none"><span class="icon-menu"></span></a>
            </div>
        </div>
    </div>
</div>