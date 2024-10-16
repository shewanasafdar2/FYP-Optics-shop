<?php 
require "includes/connection.php";
require "includes/functions.php";
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 
  
require 'vendor/autoload.php';
if(!isset($_SESSION['errors']) || count($_SESSION['errors']) == 0){
    $_SESSION['errors'] = array();
}


if (isset($_POST['forgetPasswordBtn'])) 
{
    $email = mysqli_real_escape_string($con,$_POST['userEmail']);
    $otp = generateOTP(5);
    if (userEmailAlreadyExists($con,$email) == 1) {

    
        $sql = "UPDATE `tbl_users` SET `user_email_otp` = '$otp' WHERE `user_email` = '$email'";
        $result = mysqli_query($con,$sql);
        if ($result) {
            $mail = new PHPMailer;
            $mail->isSMTP();                            // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                     // Enable SMTP authentication
            $mail->Username = 'serviceprovider722@gmail.com';          // SMTP username
            $mail->Password = 'gpez rbtk bxej phoj'; // SMTP password
            $mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                          // TCP port to connect to
            $mail->setFrom('serviceprovider722@gmail.com', 'Optic Shop');
            $mail->addReplyTo('suport@serviceprovider.com', 'Support');
            $mail->addAddress($email);   // Add a recipient

            $mail->isHTML(true);  // Set email format to HTML

            $mail->Subject = "Reset Password ";
            $bodyContent= "Dear User Pleae click on given link below for reset your password." ;
            $bodyContent .="<br><a href='http://localhost/opticshop/resetPassword.php?verifyOTP=".$otp."' style='text-decoration: none; color: #ffffff; background-color: #4CAF50; border: none;margin:1rem 1rem; border-radius: 5px; padding: 10px 20px; font-size: 16px; cursor: pointer; display: inline-block;'><button style='border: none; background-color: transparent; color: #ffffff; cursor: pointer;'>Reset your password</button></a>";
            $mail->Body = $bodyContent;
            if($mail->send()) {

               $_SESSION['successMessage'] = "Please check your email and reset your password.";//Sets a success message
                header("location:login.php");//redirect to the view all services page
                exit();
            }else{
                 $_SESSION['errorMessage'] = "Email not sent Please try again.";//Sets a success message
                header("location:login.php");//redirect to login page
                exit();
            }
        }
    }else{
        $_SESSION['errorMessage'] = "Invalid Email, Please enter valid email.";//Sets a success message
        header("location:login.php");//redirect to login page
        exit();
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
            <!-- Your admin form goes here -->
            <h2>Forget Password</h2>
            <form action="forget.php" method="post">
           
                <label for="password">Your Email</label>
                <input type="email" require id="email" name="userEmail">
                
                <button type="submit" name="forgetPasswordBtn">Verify Email</button>
            </form>
        </div>
    </div>
</body>
</html>