<?php require "includes/head.php";

if(!isset($_SESSION['errors']) || count($_SESSION['errors']) == 0){
    $_SESSION['errors'] = array();
}
$email = $password = $userName = $confirmPassword = "";
if(isset($_POST['registerBtn'])){
    if(empty($_POST['email'])){
        array_push($_SESSION['errors'],"Email is Required");
    }else{
        $email = mysqli_real_escape_string($con,$_POST['email']); 
        if (!validateEmail($email)) {
            array_push($_SESSION['errors'], "Invalid Email Address");
        } elseif (userEmailAlreadyExists($con, $email)) {
            array_push($_SESSION['errors'], "Email already exists");
        }
    }
    if(empty($_POST['userName'])){
        array_push($_SESSION['errors'],"FullName is Required");
    }else{
        $userName = mysqli_real_escape_string($con,$_POST['userName']); 
    }

    if(empty($_POST['password'])){
        array_push($_SESSION['errors'],"Password is Required");
    }else{
        $password = mysqli_real_escape_string($con,$_POST['password']); 
        
    }
    if (empty($_POST['confirmPassword'])) {
        array_push($_SESSION['errors'], "Confirm Password is Required");
    } else {
        $confirmPassword = mysqli_real_escape_string($con, $_POST['confirmPassword']);
    }
    if ($password != $confirmPassword) {
        array_push($_SESSION['errors'], "Password and Confirm Password do not match");
    } else {
        $password = md5($password);
    }
    if(isset($_SESSION['errors']) && count($_SESSION['errors']) == 0){
        $userCreatedDate = date("Y-m-d h:i:s");
        $sql = "INSERT INTO `tbl_users` (`user_name`,`user_email`,`user_password`,`user_createdDate`) VALUES ('$userName','$email','$password','$userCreatedDate')";
        $result = mysqli_query($con,$sql);
        if($result){
            $_SESSION['successMessage'] = "Registration  Successfully";
            header("location:login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>register</title>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="img/logo eye.png" alt="Logo">
        </div>
        <div class="register-form">
            <!-- Your registration form goes here -->
            <h2>Register</h2>
            <form action="register.php" method="POST">
            <?php 
                if(isset($_SESSION['errors']) && count($_SESSION['errors']) > 0){
                    foreach($_SESSION['errors'] as $error){
                    ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                    <?php
                    }
                    unset($_SESSION['errors']);
                }
                
                ?>
                <label for="userName">FullName</label>
                <input type="text" id="userName" name="userName">
                <label for="email">Email</label>
                <input type="email" id="email" name="email">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <label for="confirmPassword"> Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword">
                <button type="submit" name="registerBtn">Register</button>
            </form>
            <div class="login">
                <p>Already have an account? <a href="login.php">Login</a></p>
              </div>
        </div>
    </div>
</body>
</html>