<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $phone_number=$_POST['phone_number'];
    $company_registration_number = $_POST['company_registration_number'] ?? null;
    $driving_license_number = $_POST['driving_license_number'] ?? null;
    $address = $_POST['address'] ?? null;
    if($role == 'vendor'){
        $sql = "INSERT INTO vendor (name, phone_number, email, company_registration_number, address, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss",
            $name,
            $phone_number,
            $email,
            $company_registration_number,
            $address,
            $password);
    }
    else{
        $sql = "INSERT INTO users (name, phone_number, email, driving_license_number, address, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss",
            $name,
            $phone_number,
            $email,
            $driving_license_number,
            $address,
            $password);
        
    }
    

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<link rel="stylesheet" href="css/register.css">
<div class="login-container">
        <form action="" class="form-login" method="POST">
            <ul class="login-nav">
                <li class="login-nav__item">
                    <a href="login.php">Sign In</a>
                </li>
                <li class="login-nav__item active">
                    <a href="register.php">Sign Up</a>
                </li>
            </ul>
    
            <label for="login-input-user" class="login__label">
                Full-Name
            </label>
            <input id="login-input-user" class="login__input" type="text" name="username" required />
    
    
            <label for="login-input-user" class="login__label">
                E-mail
            </label>
            <input id="login-input-user" class="login__input" type="email" name="email" required />
            <label for="login-input-user" class="login__label">
                Role
            </label>
            <select name="role" id="role" class="login__input" required style="background-color:rgba(37, 35, 82, 0.72);">
                <option value="" disabled selected >Select Role</option>
                <option value="user" >User</option>
                <option value="vendor" >Vendor</option>
            </select>

            <div id="vendor-fields" style="display: none;margin-top: 30px;">

                <label for="company_registration_number" class="login__label">Company Registration Number:</label>
                <input type="text" name="company_registration_number" id="company_registration_number" class="login__input" ><br><br>
            </div>

            <div id="vendor-field" style="display: none;margin-top: 30px;">

            <label for="driving_license_number" class="login__label" style="margin-top: 30px;">Driving License Number:</label>
            <input type="text" name="driving_license_number" id="driving_license_number" class="login__input" >
            </div>

            <label for="address" class="login__label" style="margin-top: 30px;">Address:</label>
            <textarea name="address" id="address" class="login__input" required></textarea>

            <label for="phone_number" class="login__label">Phone Number:</label>
            <input type="text" name="phone_number" id="phone_number" minlength="10" maxlength="10" pattern="[6-9].{9}" required class="login__input">

            <label for="login-input-password" class="login__label">
                Password
            </label>
            <input id="login-input-password" class="login__input" type="password" name="password" required />
    
    
    
            <button class="login__submit" type="submit" >Sign up</button>
        </form>
        <script>
        // Show vendor-specific fields if the selected role is 'vendor'
        const roleSelect = document.getElementById('role');
        const vendorFields = document.getElementById('vendor-fields');
        const vendorField = document.getElementById('vendor-field');
        roleSelect.addEventListener('change', () => {
            vendorFields.style.display = roleSelect.value === 'vendor' ? 'block' : 'none';
            vendorField.style.display = roleSelect.value === 'user' ? 'block' : 'none';

        });
   
    </script>