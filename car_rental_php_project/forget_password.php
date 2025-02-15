<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user&& isset($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $password, $email);
        $stmt->execute();
        header("Location: login.php");


    }

    $sql = "SELECT * FROM vendor WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $vendor = $result->fetch_assoc();

    if ($vendor && isset($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "UPDATE vendor SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $password, $email);
        $stmt->execute();
        header("Location: login.php");
 
    }
}

?>

<link rel="stylesheet" href="css/login.css">

<div class="login-container">
        <form action="" class="form-login" method="POST">
            <ul class="login-nav">
                <li class="login-nav__item active">
                    <a href="login.php">Forget Password</a>
                </li>
              
            </ul>
    
            <label for="login-input-user" class="login__label">
                E-mail
            </label>
            <input id="login-input-user" class="login__input" type="email" name="email" value="<?php echo $user['email']; ?>" />
            <?php
            if ($user) {
                ?>
            <label for="login-input-password" class="login__label">
                New Password
            </label>
            <input id="login-input-password" class="login__input" type="password" name="password" />
           <?php }
    ?>
            <button class="login__submit" type="submit" >Check Email</button>
        </form>
        <a href="login.php" class="login__forgot">Back to login</a>
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
 