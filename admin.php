<?php 
require "includes/connection.php";
require "includes/functions.php";
if(isUserLogin() === true && $_SESSION['userType'] == "A"){
    header("location:admin");
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
    }

    if(empty($_POST['password'])){
        array_push($_SESSION['errors'],"Password is Required");
    }else{
        $password = mysqli_real_escape_string($con,$_POST['password']); 
        $password= md5($password);
    }


    if(isset($_SESSION['errors']) && count($_SESSION['errors']) == 0){
        $sql = "SELECT * FROM `tbl_users` WHERE `user_email` = '$email' AND `user_password` = '$password' AND `user_type` = 'A'";
        $result = mysqli_query($con,$sql);
        if($result){
            if(mysqli_num_rows($result) == 1){
                if($row= mysqli_fetch_array($result)){
                    $_SESSION['userID'] = $row['user_id'];
                    $_SESSION['userName'] = $row['user_name'];
                    $_SESSION['userEmail'] = $row['user_email'];
                    $_SESSION['userType'] = $row['user_type'];
                    $_SESSION['userImage'] = $row['user_image'];

                    header("location:admin");
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
    <title>admin</title>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="img/logo eye.png" alt="Logo">
        </div>
        <div class="admin-form">
            <!-- Your admin form goes here -->
            <h2>Welcome Admin</h2>
            <form action="admin.php" method="POST">
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
                ?>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" >
                <label for="password">Password</label>
                <input type="password" id="password" name="password" >
                <button type="submit" name="loginBtn">Login</button>
            </form>
        </div>
    </div>
</body>
</html>