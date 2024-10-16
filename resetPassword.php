<?php 
    require "includes/connection.php";
require "includes/functions.php";
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 
  
require 'vendor/autoload.php';
if (isUserLogin() === true && ($_SESSION['userType'] == "SP" || $_SESSION['userType'] == "C"))// checks if the user is already logged in. If true, redirect to index.php.
{
    header("location:index.php");
    exit();
}


if (isset($_GET['verifyOTP']))
 {
    $verifyOTP = $_GET['verifyOTP'];
    $sql = "SELECT * FROM `tbl_users` WHERE `user_email_otp` = '$verifyOTP'";
    $result = mysqli_query($con,$sql);
    if ($result) {
        if (mysqli_num_rows($result) != 1) {
            $_SESSION['errorMessage'] = "Something going worng please try later";
            header("location:login.php");
            exit();
        }
    }
}else{
    $_SESSION['errorMessage'] = "Something going worng please try later";
    header("location:login.php");
    exit();
}

//Initializes the 'errors' session variable if it isn't already set
if (!isset($_SESSION["errors"]) || count($_SESSION['errors']) == 0)
{
    $_SESSION['errors'] = array();
}

//form processing starts here
$newPassword = $confirmPassword = "";

if (isset($_POST['resetPasswordBtn']))//Checks if the login button was pressed
 {
    if (empty($_POST['newPassword']))//Validates if the email  fields is filled.
     {
        array_push($_SESSION['errors'],"New Password is Required");
    }
    else
    {
        $newPassword = mysqli_real_escape_string($con,$_POST['newPassword']);
    }


    if (empty($_POST['confirmPassword']))
     {
        array_push($_SESSION['errors'],"Confirm Password is Required");//Validates if the  password fields is filled.
    }
    else
    {
        $confirmPassword = mysqli_real_escape_string($con,$_POST['confirmPassword']);
    }

    if ($newPassword == $confirmPassword) {
        $newPassword = md5($newPassword);
    }else{
        array_push($_SESSION['errors'],"Password Not Matched");//Validates if the  password fields is filled.

    }
      
    if (isset($_SESSION['errors']) && count($_SESSION['errors']) == 0)//no error , get entered
     {
        $updateDate = date("Y-m-d h:i:s");
         // Executes the SQL query to check if the user exists in the database.
        $sql = "UPDATE `tbl_users` SET `user_password` = '$newPassword',`user_email_otp` = '',`user_updatedDate` ='$updateDate' WHERE `user_email_otp` = '$verifyOTP' ";
        $result = mysqli_query($con,$sql);//to run the above query 
        if ($result) 
        {  
            $_SESSION['successMessage'] = "Password reset successfully, please login with new Password.";//Sets a success message
            header("location:login.php");//redirect to the view all services page
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
    <title>forget</title>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="img/logo eye.png" alt="Logo">
        </div>
        <div class="forget-form">
            <!-- Your admin form goes here -->
            <h2>Change Password</h2>
            <?php if (isset($_SESSION['successMessage'])) { ?>
                        <div class="alert alert-success"><!-- Displays the success message -->
                            <?php echo $_SESSION['successMessage']; unset($_SESSION['successMessage']); ?><!-- Outputs and then unsets the success message. -->
                        </div>
                        <?php } ?>


                        <?php if (isset($_SESSION['errorMessage']))
                         { ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['errorMessage']; unset($_SESSION['errorMessage']); ?>
                        </div>
                        <?php } ?>
                        
                    <?php if(isset($_SESSION['errors']) && count($_SESSION['errors'])>0)
                    {
                        $errors = $_SESSION['errors'];
                        foreach($errors as $error)
                        {
                            ?>
                            <div class="alert alert-danger text-white">
                                <?php echo $error; ?>
                            </div>
                            <?php
                        }
                        unset($_SESSION['errors']);
                    } ?>

            <form action="resetPassword.php?verifyOTP=<?php echo $verifyOTP; ?>" method="post">
           
                <label for="password">New Password</label>
                <input type="password" id="newPassword" name="newPassword">
                <label for="password">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword">
                <button type="submit" name="resetPasswordBtn">Reset</button>
            </form>
        </div>
    </div>
</body>
</html>