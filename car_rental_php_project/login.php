<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user) {
        // Verify Password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = 'user';
            header("Location: index.php"); // Redirect customer
            exit;
        }
    }
    
    // Check in Vendors Table
    $sql = "SELECT * FROM vendor  WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user) {
        // Verify Password
        if (password_verify($password, $user['password'])) {
            $_SESSION['vendor_id'] = $user['vendor_id'];
            $_SESSION['role'] = 'vendor';
            header("Location: vendor_side/vendor_dashboard.php"); // Redirect vendor
            exit;
        }
    }

    // check in admin table
    $sql = "select * from admin where email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    
    if ($admin) {
        // Verify Password
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            header("Location: admin/home.php"); // Redirect vendor
            exit;
        }
    }
    else {
        $_SESSION['error'] = "Invalid email or password!";
        header("Location: login.php"); // Redirect back to login
        exit;
    }


}
?>
<link rel="stylesheet" href="css/login.css">

<div class="login-container">
        <form action="" class="form-login" method="POST">
            <ul class="login-nav">
                <li class="login-nav__item active">
                    <a href="login.php">Sign In</a>
                </li>
                <li class="login-nav__item">
                    <a href="register.php">Sign Up</a>
                </li>
            </ul>
    
            <label for="login-input-user" class="login__label">
                E-mail
            </label>
            <input id="login-input-user" class="login__input" type="email" name="email" />
    
    
            <label for="login-input-password" class="login__label">
                Password
            </label>
            <input id="login-input-password" class="login__input" type="password" name="password" />

    
            <label for="login-sign-up" class="login__label--checkbox">
                <input id="login-sign-up" type="checkbox" class="login__input--checkbox" />
                Keep me Signed in
            </label>
            <button class="login__submit" type="submit" >Sign in</button>
        </form>
        <a href="forget_password.php" class="login__forgot">Forget password ?</a>
        <a href="index.php" class="login__forgot">Home page without login ?</a>
    </div>
    <script>
        function showAlert(message) {
            alert(message);
        }
    </script>
    <?php
    if (isset($_SESSION['error'])) {
        echo "<script>showAlert('{$_SESSION['error']}');</script>";
        unset($_SESSION['error']); // Remove the error after displaying
    }
    ?>
 