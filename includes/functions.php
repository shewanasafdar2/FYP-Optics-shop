<?php 
function isUserLogin(){
    if(isset($_SESSION['userID']) && $_SESSION['userID'] != "" &&
        isset($_SESSION['userName']) && $_SESSION['userName'] != "" &&
        isset($_SESSION['userEmail']) && $_SESSION['userEmail'] != "" &&
        isset($_SESSION['userType']) && $_SESSION['userType'] !=""){
            return true;

    }else{
        return false;
    }
}

function userEmailAlreadyExists($con, $email) {
    $sql = "SELECT * FROM `tbl_users` WHERE `user_email` = '$email'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        return true; // email exists
    } else {
        return false; // email does not exist
    }
}
function validateEmail($email) {
    $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    if (preg_match($pattern, $email)) {
        return true;
    }
    return false;
}
function changeUserOldPassword($userID, $oldPassword, $newPassword, $confirmPassword="") {
    global $con;
    // Check if the old password is correct
    if(!isset($_SESSION['userID'])){
        return false;
    }
    $userID = $_SESSION['userID'];
    $oldPassword= md5($oldPassword);
    
    $sql = "SELECT * FROM tbl_users WHERE user_id = '$userId'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($oldPassword != $row['user_password']) {
        return false;
    }

    // Check if the new password and confirm password match
    if ($newPassword != $confirmPassword) {
        return false;
    }

    // MD5 the new password
    $newPassword= md5($newPassword);

    // Update the password for the user
    $sql = "UPDATE tbl_users SET user_password = '$newPassword' WHERE user_id = '$userID'";
    mysqli_query($con,$sql);
    return true;
}

function calculateProductDiscount($productID){
    global $con;
    $sql = "SELECT product_discount,product_price FROM tbl_products WHERE product_id = '$productID'";
    $result = mysqli_query($con,$sql);
    $row = mysqli_fetch_assoc($result);
    $productDiscount = $row['product_discount'];
    $productPrice = $row['product_price'];
    $discount = $productDiscount/100;
    return $discount_price = $discount*$productPrice;
}


function checkProductExistINCart($userID,$productID){

    global $con;
    $sql = "SELECT   `cart_productQty`  FROM `tbl_cart` WHERE `cart_userID` = '$userID' AND `cart_productID` = '$productID'";

    $result = mysqli_query($con,$sql);
    if($result){
        if(mysqli_num_rows($result)>0){
            if($row = mysqli_fetch_array($result)){
                return $row['cart_productQty'];
            }
        }else{
            return 0;
        }
    }

}
function get_image_path($product_id, $product_image) {
    // Assuming images are stored in a folder named 'images' inside a folder named 'uploads'
    return 'uploads/images/' . $product_id . '/' . $product_image;
}
function generateOrderNo($size)
{
    $alpha_key = '';
    $keys = range('A', 'Z');

    for ($i = 0; $i < 2; $i++) {
        $alpha_key .= $keys[array_rand($keys)];
    }

    $length = $size - 2;

    $key = '';
    $keys = range(0, 9);

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }
    return $alpha_key . $key;
}


function checkCartProductsAgaisntUserID($userID){

    global $con;
    $sql = "SELECT   `cart_productQty`  FROM `tbl_cart` WHERE `cart_userID` = '$userID'";

    $result = mysqli_query($con,$sql);
    if($result){
        if(mysqli_num_rows($result)>0){
            return true;
        }else{
            return false;
        }
    }

}
function generateOTP($size)
{
    $alpha_key = '';
    $keys = range('A', 'Z');

    for ($i = 0; $i < 2; $i++) {
        $alpha_key .= $keys[array_rand($keys)];
    }

    $length = $size - 2;

    $key = '';
    $keys = range(0, 9);

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }
    return $alpha_key . $key;
}
function displayError($errorMessage) {
    echo "<div class='alert alert-danger'>$errorMessage</div>";
    exit();
}
function getOrderRating($orderID){
    global $con;
    $sql = "SELECT * FROM tbl_ratings WHERE rating_orderID = '$orderID'";
    $result = mysqli_query($con,$sql);
    if($result){
      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        if($row['rating_stars'] != ""){
            return $row['rating_stars']." <i class='fa fa-star ' style='color:yellow;'></i>";
        }else{
            return "N/A";
        }
      } else {
        return "N/A";
      }
    } else {
      return "N/A";
    }
}
?>

