<?php
// Database connection
$connection = mysqli_connect("localhost", "root", "", "nexora");

// Redirect function for POST requests
function post_redirect($url) {
    ob_start();
    header('Location: ' . $url);
    ob_end_flush();
    die();
}

// Redirect function for GET requests
function get_redirect($url) {
    echo "<script> 
        window.location.href = '" . $url . "'; 
    </script>";
}

// Query function to fetch data from the database
function query($query) {
    global $connection;
    $run = mysqli_query($connection, $query);
    if ($run && mysqli_num_rows($run) > 0) {
        $data = [];
        while ($row = mysqli_fetch_assoc($run)) {
            $data[] = $row;
        }
        return $data;
    } else {
        return [];
    }
}

// Function to execute single queries (e.g., INSERT, UPDATE, DELETE)
function single_query($query) {
    global $connection;
    if (mysqli_query($connection, $query)) {
        return true;
    } else {
        die("Query failed: " . mysqli_error($connection));
    }
}

// Login function
function login() {
    if (isset($_POST['login'])) {
        $userEmail = trim(strtolower($_POST['userEmail']));
        $password = trim($_POST['password']);
        if (empty($userEmail) || empty($password)) {
            $_SESSION['message'] = "empty_err";
            post_redirect("login.php");
        }
        $query = "SELECT email, user_id, user_password FROM user WHERE email = '$userEmail'";
        $data = query($query);
        if (empty($data)) {
            $_SESSION['message'] = "loginErr";
            post_redirect("login.php");
        } elseif ($password == $data[0]['user_password'] && $userEmail == $data[0]['email']) {
            $_SESSION['user_id'] = $data[0]['user_id'];
            post_redirect("index.php");
        } else {
            $_SESSION['message'] = "loginErr";
            post_redirect("login.php");
        }
    }
}

// Signup function
function singUp() {
    if (isset($_POST['singUp'])) {
        $email = trim(strtolower($_POST['email']));
        $fname = trim($_POST['Fname']);
        $lname = trim($_POST['Lname']);
        $address = trim($_POST['address']);
        $passwd = trim($_POST['passwd']);
        if (empty($email) || empty($passwd) || empty($address) || empty($fname) || empty($lname)) {
            $_SESSION['message'] = "empty_err";
            post_redirect("signUp.php");
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['message'] = "signup_err_email";
            post_redirect("signUp.php");
        } elseif (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,30}$/', $passwd)) {
            $_SESSION['message'] = "signup_err_password";
            post_redirect("signUp.php");
        }
        $query = "SELECT email FROM user WHERE email = '$email'";
        $data = query($query);
        if (!empty($data)) {
            $_SESSION['message'] = "usedEmail";
            post_redirect("signUp.php");
        }
        $query = "INSERT INTO user (email, user_fname, user_lname, user_address, user_password) VALUES ('$email', '$fname', '$lname', '$address', '$passwd')";
        if (single_query($query)) {
            $query = "SELECT user_id FROM user WHERE email = '$email'";
            $data = query($query);
            $_SESSION['user_id'] = $data[0]['user_id'];
            post_redirect("index.php");
        } else {
            $_SESSION['message'] = "wentWrong";
            post_redirect("signUp.php");
        }
    }
}

// Message display function
function message() {
    if (isset($_SESSION['message'])) {
        switch ($_SESSION['message']) {
            case "signup_err_password":
                echo "<div class='alert alert-danger' role='alert'>Please enter a valid password!</div>";
                break;
            case "loginErr":
                echo "<div class='alert alert-danger' role='alert'>Invalid email or password!</div>";
                break;
            case "usedEmail":
                echo "<div class='alert alert-danger' role='alert'>This email is already registered!</div>";
                break;
            case "wentWrong":
                echo "<div class='alert alert-danger' role='alert'>Something went wrong!</div>";
                break;
            case "empty_err":
                echo "<div class='alert alert-danger' role='alert'>Please fill in all fields!</div>";
                break;
            case "signup_err_email":
                echo "<div class='alert alert-danger' role='alert'>Please enter a valid email!</div>";
                break;
        }
        unset($_SESSION['message']);
    }
}

// Search function
function search() {
    if (isset($_GET['search'])) {
        $search_text = $_GET['search'];
        if (!empty($search_text)) {
            $query = "SELECT * FROM item WHERE item_tags LIKE '%$search_text%' OR item_title LIKE '%$search_text%'";
            return query($query);
        }
    } elseif (isset($_GET['cat'])) {
        $cat = $_GET['cat'];
        $query = "SELECT * FROM item WHERE item_cat = '$cat' ORDER BY RAND()";
        return query($query);
    } elseif (isset($_GET['store'])) {
        return all_products();
    }
    return [];
}

// Fetch all products
function all_products() {
    $query = "SELECT * FROM item ORDER BY RAND()";
    return query($query);
}

// Fetch a single product by ID
function get_item() {
    if (isset($_GET['product_id'])) {
        $id = intval($_GET['product_id']);
        $query = "SELECT * FROM item WHERE item_id = '$id'";
        return query($query);
    }
    return [];
}

// Fetch user details by ID
function get_user($id) {
    $query = "SELECT user_id, user_fname, user_lname, email, user_address FROM user WHERE user_id = '$id'";
    return query($query);
}

// Check if a user exists
function check_user($id) {
    $query = "SELECT user_id FROM user WHERE user_id = '$id'";
    return !empty(query($query));
}

// Add a product to favorites
function add_to_favorites($user_id, $item_id) {
    global $connection;
    if (empty($user_id) || empty($item_id)) {
        return false;
    }
    $query = "INSERT INTO favorites (user_id, item_id) VALUES ('$user_id', '$item_id') ON DUPLICATE KEY UPDATE user_id=user_id";
    return single_query($query);
}

// Fetch a user's favorites
function get_favorites($user_id) {
    global $connection;
    if (empty($user_id)) {
        return [];
    }
    $query = "SELECT i.* FROM favorites f JOIN item i ON f.item_id = i.item_id WHERE f.user_id = '$user_id'";
    return query($query);
}

// Delete a product from favorites
function delete_from_favorites($user_id, $item_id) {
    global $connection;
    if (empty($user_id) || empty($item_id)) {
        return false;
    }
    $query = "DELETE FROM favorites WHERE user_id = '$user_id' AND item_id = '$item_id'";
    return single_query($query);
}

// Delete all favorites for a user
function delete_all_favorites($user_id) {
    global $connection;
    if (empty($user_id)) {
        return false;
    }
    $query = "DELETE FROM favorites WHERE user_id = '$user_id'";
    return single_query($query);
}

// Fetch new products (added in the last 30 days)
function get_new_items() {
    $query = "SELECT * FROM item WHERE item_created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ORDER BY item_created_at DESC";
    return query($query);
}
function get_sale_items() {
    $query = "SELECT * FROM item WHERE item_tags LIKE '%sale%' ORDER BY RAND()";
    return query($query);
}