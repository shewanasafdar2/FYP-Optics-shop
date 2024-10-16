<?php 
require "includes/connection.php";
require "includes/functions.php";

if(isUserLogin() === true && $_SESSION['userType'] == "C"){
    header("location:index.php");
    exit();
}

if(!isset($_SESSION['errors']) || count($_SESSION['errors']) == 0){
    $_SESSION['errors'] = array();
}
$email = $password = "";
if(isset($_POST['loginBtn'])){
    if(empty($_POST['email'])){
        array_push($_SESSION['errors'],"Email is Required");
    }else{
        $email = mysqli_real_escape_string($con,$_POST['email']); 
        if (!validateEmail($email)) {
            array_push($_SESSION['errors'], "Invalid Email Address");
        }
    }

    if(empty($_POST['password'])){
        array_push($_SESSION['errors'],"Password is Required");
    }else{
        $password = mysqli_real_escape_string($con,$_POST['password']); 
        $password= md5($password);
    }


    if(isset($_SESSION['errors']) && count($_SESSION['errors']) == 0){
        $sql = "SELECT * FROM `tbl_users` WHERE `user_email` = '$email' AND `user_password` = '$password' AND `user_type` = 'C'";
        $result = mysqli_query($con,$sql);
        if($result){
            if(mysqli_num_rows($result) == 1){
                if($row= mysqli_fetch_array($result)){
                    $_SESSION['userID'] = $row['user_id'];
                    $_SESSION['userName'] = $row['user_name'];
                    $_SESSION['userEmail'] = $row['user_email'];
                    $_SESSION['userType'] = $row['user_type'];
                    $_SESSION['userImage'] = $row['user_image'];
                    header("location:index.php");
                    exit();

                }

            }else{
                array_push($_SESSION['errors'],"Invalid Email or Password");
            }
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
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="img/logo eye.png" alt="">
        </div>
        <div class="login-form">
            <!-- Your logo form goes here -->
            <h2>Login</h2>
            <form action="login.php" method="POST">
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
                if(isset($_SESSION['successMessage']) ){
                    ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['successMessage']; ?>
                    </div>
                    <?php
                    unset($_SESSION['successMessage']);
                }
                if(isset($_SESSION['errorMessage']) ){
                    ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['errorMessage']; ?>
                    </div>
                    <?php
                    unset($_SESSION['errorMessage']);
                }
                
                ?>
                <label for="email">Email</label>
                <input type="text" id="email" name="email">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <button type="submit" name="loginBtn">Login</button>
                <div class="forget">
                <a href="forget.php">Forgot Password?</a>
                </div>
            </form>
            <div class="login">
                <p>Don't have an account?<a href="register.php">Register</a></p>
              </div>
        </div>
    </div>
</body>
</html>