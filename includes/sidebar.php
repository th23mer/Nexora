<div>
    <h4>Filters</h4>
    <form method="GET" action="store.php">
        <div class="form-group">
            <label for="category">Category</label>
            <select name="category" id="category" class="form-control">
                <option value="">All Categories</option>
                <?php
                // Fetch distinct categories from the database
                $query = "SELECT DISTINCT item_cat AS category FROM item ORDER BY category ASC";
                $categories = query($query);

                // Check if categories exist
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        $cat_name = htmlspecialchars($category['category']); // Sanitize output
                        echo "<option value='$cat_name'>$cat_name</option>";
                    }
                } else {
                    echo "<option value=''>No Categories Available</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="min_price">Min Price (TND)</label>
            <input type="number" name="min_price" id="min_price" class="form-control" placeholder="0">
        </div>

        <div class="form-group">
            <label for="max_price">Max Price (TND)</label>
            <input type="number" name="max_price" id="max_price" class="form-control" placeholder="1000">
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="filter" value="new"> New Arrivals
            </label>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
    </form>
</div>