<?php require "includes/head.php"; 
if(isUserLogin() === false){
  header("Location: login.php");
  exit();
}

$oldPassword = $newPassword = $confirmPassword = $userUpdatedDate = "";
if(isset($_SESSION['userID'])){
  $userID = $_SESSION['userID'];
  $sql = "SELECT * FROM `tbl_users` WHERE `user_id` = '$userID'";
  $result = mysqli_query($con,$sql);
  if($result){
    if(mysqli_num_rows($result) == 1){
      if($row = mysqli_fetch_array($result)){
        $oldPassword = $row['user_password'];
      }
    }else{
      $_SESSION["errorMessage"] = "Access Denied...!";
      header("location:changePassoword.php");
      exit();
    }
  }
}
if(!isset($_SESSION['errors']) || count($_SESSION['errors']) == 0){
  $_SESSION['errors'] = array();
}

if(isset($_POST['updatePasswordBtn'])){
  if(empty($_POST['oldPassword'])){
      array_push($_SESSION['errors'],"Old Password is Required");
  }else{
    $oldPasswordInput = md5($_POST['oldPassword']);
    if($oldPasswordInput!= $oldPassword) {
      array_push($_SESSION['errors'], "Old Password is incorrect");
    }
  }
  if(empty($_POST['newPassword'])){
    array_push($_SESSION['errors'],"New Password is Required");
}
else{
    $newPassword = mysqli_real_escape_string($con,$_POST['newPassword']); 
}
if(empty($_POST['confirmPassword'])){
    array_push($_SESSION['errors'],"confirm Password is Required");
}
else{
    $confirmPassword = mysqli_real_escape_string($con,$_POST['confirmPassword']); 
}
if($newPassword != $confirmPassword){
    array_push($_SESSION['errors'],"New Password and Confirm Password do not match");
}else{
    $newPassword = md5($newPassword);
}



  if(isset($_SESSION['errors']) && count($_SESSION['errors']) == 0){
     $userUpdatedDate = date("Y-m-d h:i:s");
     $sql = "UPDATE tbl_users SET user_password = '$newPassword', user_updatedDate = '$userUpdatedDate' WHERE user_id = '$userID'";
      $result = mysqli_query($con,$sql);
      if($result){
          $_SESSION['successMessage'] = "Password Updated Successfully";
          header("location:logout.php");
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
    <title>Change Password</title>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="img/logo eye.png" alt="Logo">
        </div>
        <div class="forget-form">
            <!-- Your admin form goes here -->
            <h2>Change Password</h2>
          <form action="changePassword.php?userID=<?php echo $userID; ?>" method="post" enctype="multipart/form-data">
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
            <label for="oldPassword">Old Password</label>
            <input type="password" id="oldPassword" name="oldPassword">
            <label for="newPassword">New Password</label>
            <input type="password" id="newPassword" name="newPassword">
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" name="confirmPassword">
            <button type="submit" name="updatePasswordBtn">Update</button>
          </form>
        </div>
    </div>
</body>
</html>