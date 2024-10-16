<?php require "includes/connection.php";

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
  header("location: login.php");
  exit();
}
$userName = $userEmail = $userImage = $userContact = $userAddress = $userUpdatedDate = "";
$userID = $_SESSION['userID'];

// Retrieve the user data from the database
$sql = "SELECT * FROM `tbl_users` WHERE `user_id` = '$userID'";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) == 1) {
  $row = mysqli_fetch_array($result);
  $userName = $row['user_name'];
  $userEmail = $row['user_email'];
  $userAddress = isset($row['user_address']) ? $row['user_address'] : '';
  $userContact = $row['user_contact'];
  $userImage = isset($row['user_image']) ? $row['user_image'] : '';
  $userImageOld = $row['user_image'];
} else {
  $_SESSION["errorMessage"] = "Access Denied...!";
  header("location: login.php");
  exit();
}

// Update profile form submission
if (isset($_POST['updateProfileBtn'])) {
  $userName = mysqli_real_escape_string($con, $_POST['userName']);
  $userEmail = mysqli_real_escape_string($con, $_POST['userEmail']);
  $userAddress = mysqli_real_escape_string($con, $_POST['userAddress']);
  $userContact = mysqli_real_escape_string($con, $_POST['userContact']);
  $userImage = mysqli_real_escape_string($con, $_POST['userImage']);
  

  // Check for errors
//   $_SESSION['errors'] = array();
  if (empty($userName)) {
    array_push($_SESSION['errors'], "UserName is required");
  }
//   $_SESSION['errors'] = array();
  if (empty($userEmail)) {
    array_push($_SESSION['errors'], "Email is required");
  }
//   $_SESSION['errors'] = array();
  if (empty($userAddress)) {
    array_push($_SESSION['errors'], "Address is required");
  }
//   $_SESSION['errors'] = array();
  if (empty($userContact)) {
    array_push($_SESSION['errors'], "Contact number is required");
  }

  // Check if avatar file is uploaded
  if (isset($_FILES["userImage"]) && $_FILES["userImage"]["name"] != "") {
    $target_dir = "img/";
    $timestamp = time();
    $target_file = $target_dir. $timestamp. '-'. basename($_FILES["userImage"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["userImage"]["tmp_name"]);

    if ($check!== false) {
      if (file_exists($target_file)) {
        array_push($_SESSION['errors'], "Sorry, file already exists");
      }

      // Check file size
      if ($_FILES["userImage"]["size"] > 50000000000) {
        array_push($_SESSION['errors'], "File is too large");
      }

      if ($imageFileType!= "jpg" && $imageFileType!= "png" && $imageFileType!= "jpeg") {
        array_push($_SESSION['errors'], "Sorry, only JPG, JPEG, & PNG files are allowed.");
      }

      if (isset($_SESSION['errors']) && count($_SESSION['errors']) == 0) {
        if (move_uploaded_file($_FILES["userImage"]["tmp_name"], $target_file)) {
          if ($userImage!= "" && file_exists($userImage)) {
            unlink($userImage);
          }
          $userImage = $target_file;
        } else {
          array_push($_SESSION['errors'], "Sorry, there was an error uploading your file.");
        }
      }
    } else {
      array_push($_SESSION['errors'], "Please upload an image file only");
    }
  } else {
    $userImage = $userImageOld;
  }

  // Update user data in the database
  if (isset($_SESSION['errors']) && count($_SESSION['errors']) == 0) {
    $userUpdatedDate = date("Y-m-d h:i:s");
    $sql = "UPDATE `tbl_users` SET `user_name` = '$userName', `user_email` = '$userEmail', `user_address` = '$userAddress', `user_contact` = '$userContact', `user_image` = '$userImage',`user_updatedDate` = '$userUpdatedDate' WHERE `user_id` = '$userID'";
    $result = mysqli_query($con, $sql);

    if ($result) {
      $_SESSION['successMessage'] = "Profile updated successfully";
      header("location: myProfile.php");
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
    <title>My Profile</title>
</head>

<body>
    <div class="container">
        <div class="logo-container">
            <img src="img/logo eye.png" alt="Logo">
        </div>
        <div class="register-form">
            <!-- Your registration form goes here -->
            <h2>My Profile</h2>
            <form action="myProfile.php?userID=<?php echo $userID; ?>" method="post" enctype="multipart/form-data">
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
                <input type="text" id="userName" name="userName" value="<?php echo $userName; ?>">
                <label for="email">Email</label>
                <input type="email" id="userEmail" name="userEmail" value="<?php echo $userEmail; ?>">
                <label for="Address">Address</label>
                <input type="text" id="userAddress" name="userAddress" value="<?php echo $userAddress; ?>">
                <label for="contact">Contact</label>
                <input type="tel" id="userContact" name="userContact" value="<?php echo $userContact; ?>">
                <label for="avator">Choose Avator</label>
                <input type="file" id="userImage" name="userImage">     
                <button type="submit" name="updateProfileBtn">Update</button>
            </form>
            
        </div>
    </div>
</body>
</html>