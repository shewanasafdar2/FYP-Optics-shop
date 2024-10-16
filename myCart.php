<?php require "includes/head.php"?>
<?php require "includes/navbar.php"?>
<?php if(isUserLogin() == false || $_SESSION["userType"] != "C"){
    header("Location: login.php");
    exit();
} 
// Check if userID key exists in the session array
$customerID = $_SESSION['userID'];

$cartID = "";
if(isset($_GET['cartID'])){
    $cartID = $_GET['cartID'];
    $sql = "DELETE FROM `tbl_cart` WHERE `cart_id` = '$cartID'";
    $result = mysqli_query($con,$sql);
    if($result){
        $_SESSION['successMessage'] = "Item Deleted Successfully";
        header("Location: myCart.php");
        exit();
    }
}


if(isset($_GET['placeOrder'])){
    if($_GET['placeOrder'] == 1){
        $orderNo = generateOrderNo(7);
        if(checkCartProductsAgaisntUserID($customerID)  === true){
            $orderDate = date("Y-m-d h:i:s");
            $sql = "INSERT INTO `tbl_orders` (`order_userID` , `order_date`, `order_no`, `order_status`) VALUES ('$customerID','$orderDate','$orderNo','Pending')";
            $result = mysqli_query($con,$sql);
            if($result){
                $orderID = mysqli_insert_id($con);
                $sqlCart = "SELECT * FROM `tbl_cart`  WHERE `cart_userID` = '$customerID' ORDER BY `cart_id`";

                $resultcart = mysqli_query($con,$sqlCart);
                if(mysqli_num_rows($resultcart) >0){

                    $totalPrice = 0;
                    while($row = mysqli_fetch_assoc($resultcart)){
                        $cartProdctPrice = $row["cart_productPrice"];
                        $cartProdctID = $row["cart_productID"];

                        $cartProdctQty = $row['cart_productQty'];
                        $prodcutQtyPrice = $cartProdctPrice * $cartProdctQty;
                        $totalPrice += $prodcutQtyPrice ;

                        $sqlOrderDetail = "INSERT INTO `tbl_order_details` (`order_orderID`,`order_productID`,`order_productQty`,`order_productPrice`) VALUES ('$orderID','$cartProdctID','$cartProdctQty','$prodcutQtyPrice')";
                        $resultOrderDetail = mysqli_query($con,$sqlOrderDetail);
                    }
                    $sqlUpdateOrderPrice = "UPDATE `tbl_orders` SET `order_totalCost` = '$totalPrice' WHERE `order_id` = '$orderID'";
                    $resultUpdateOrderPrice = mysqli_query($con,$sqlUpdateOrderPrice);
                    if($resultUpdateOrderPrice){
                        $sqlDeleteCart = "DELETE FROM `tbl_cart` WHERE `cart_userID` = '$customerID'";
                        $resultDeleteCart = mysqli_query($con,$sqlDeleteCart);

                        $notificationTitle = $_SESSION['userName']." Placed New Order #: ".$orderNo;
                        $notificationFor = 'A';
                        $notificationForID = 1;
                        $notificationType = 'O';
                        $notificationTypeID = $orderID;
                        $notificationStatus = '0';
                        $notificationDate = date("Y-m-d h:i:s");
                        $sqlNotification="INSERT INTO `tbl_notifications` (`notification_title`,`notification_for`, `notification_forID`,`notification_typeID`, `notification_type`, `notification_status`,`notification_createdDate`) VALUES ('$notificationTitle','$notificationFor','$notificationForID','$notificationTypeID','$notificationType','$notificationStatus','$notificationDate')";
                        $resultNotification = mysqli_query($con,$sqlNotification);
                        $_SESSION['successMessage'] = 'Order Placed Successfully';
                        header("location:myOrders.php");
                        exit();
                    }
                }
            }

        }
        // 
    }
}

?>
<body>

    <section class="cart-section">
    <?php 
               
                if(isset($_SESSION['successMessage']) ){
                    ?>
                   <div class="col-md-3">
                        <div class="alert alert-success">
                            <?php echo $_SESSION['successMessage']; unset($_SESSION['successMessage']); ?>
                        </div>
                    </div>
                    <?php
                    unset($_SESSION['successMessage']);
                }
                
                ?>
        <div class="container6">
            <div class="row">
                <?php 
                if(isset($_SESSION['successMessage'])){
                    ?>
                    <div class="col-md-3">
                        <div class="alert alert-success">
                            <?php echo $_SESSION['successMessage']; unset($_SESSION['successMessage']); ?>
                        </div>
                    </div>
                    <?php 
                }
                $sql = "SELECT `tbl_products`.*,`tbl_cart`.* FROM `tbl_cart` INNER JOIN `tbl_products` ON `tbl_products`.`product_id` = `tbl_cart`.`cart_productID` WHERE `tbl_cart`.`cart_userID` = '$customerID' ORDER BY `tbl_cart`.`cart_id`";

                $result = mysqli_query($con,$sql);
                if(mysqli_num_rows($result) >0){

                    $totalPrice = 0;
                    
                ?>
                <div class="col-md-7">
                    <h5 style="font-size: 1.7rem;">My Cart</h5>
                    
                    <div class="cart-item">
                        <?php while($row = mysqli_fetch_assoc($result)){
                        $productImage = "admin/".$row['product_image'];
                        $productPrice = $row['product_price'];
                        $productDiscount = $row['product_discount'];
                        $productDiscountPrice = calculateProductDiscount($row['product_id']);
                        $pageUrl = "singleProduct.php?productID=".$row['product_id'];
                        $cartProdctPrice = $row["cart_productPrice"];
                        $cartProdctQty = $row['cart_productQty'];
                        $prodcutQtyPrice = $cartProdctPrice * $cartProdctQty;
                        $totalPrice += $prodcutQtyPrice ;
                        
                        
                        ?>
                            
                                    <div class="row">
                                        <div class="col-md-7">
                                        <?php if( $productImage != "admin/" && file_exists( $productImage )){
                                            ?>
                                            <img src="<?php echo $productImage; ?>" alt="<?php echo $row['product_name']." Image"; ?>" width="65">
                                            <?php
                                          }else{
                                            ?>
                                            <img src="men eyeglasses\mens eyeglasses_7.jpg" alt="Shopping item" width="65px">
                                            <?php
                                          } ?>
                                            
                                        </div>
                                        <div class="col-md-7" style="margin-right: 10rem;">
                                            <h5><?php echo $row['product_name']; ?></h5>
                                            <!-- <p>EyeGlasses101</p> -->
                                        </div>
                                        <div class="col-md-7" style="margin-right: 1.3rem;">
                                            <h5><?php echo $row['cart_productQty']; ?></h5>
                                        </div>
                                        <div class="col-md-7" style="margin-right: 1.3rem;">
                                            <h5> Rs <?php echo $row['cart_productPrice'] * $row['cart_productQty']; ?></h5>
                                        </div>
                                    
                                            <a href="myCart.php?cartID=<?php echo $row['cart_id']; ?>"><i class="fas fa-trash" style="color:red;"></i></a>
                                        
                                    </div>
                        <?php 

                        } ?>
                    </div>
                    
                    <button class="cash-on-delivery-btn">
                        <i class="fas fa-long-arrow-alt-left ms-2" style="margin-right: 0.7rem; color: white;"></i>Continue Shopping
                    </button>
                </div>
                <div class="col-md-5">
                    <div class="price-details-card">
                        <h3 style="font-size: 1.4rem;">Price details</h3>
                        <hr>
                        <div class="price-detail">
                            <p>Subtotal</p>
                            <p>Rs <?php echo $totalPrice; ?></p>
                        </div>
                        <div class="price-detail">
                            <p>Shipping</p>
                            <p>Free</p>
                        </div>
                        <div class="price-detail">
                            <p>Cart Total</p>
                            <p>Rs <?php echo $totalPrice; ?></p>
                        </div>
                        <a class="checkout-btn" href="myCart.php?placeOrder=1">
                            Proceed To Checkout <i class="fas fa-long-arrow-alt-right ms-2"></i>
                        </a>
                    </div>
                </div>
                <?php }else{
                    ?>
                    <div class="col-md-12">
                        <div class="alert alert-info">No Item Found in cart</div>
                    </div>
                    <?php
                } ?>

            </div>
        </div>
    </section>
    <!-- Add a popup container to your HTML -->
<div class="popup-container" id="popup-container">
  <div class="popup-content">
    <div class="icon"><i class="fa-solid fa-check"></i></div>
  
    <h2 style="font-size:1.5rem; text-align:center ">Order Confirmed!</h2>
    <p style="font-size:1rem;text-align:center ">Your order has been confirmed successfully!</p>
    <p style="text-align:center; font-size:0.9rem; margin-top:-1rem ">Thank you for choosing us.</p>
    <button class="close-btn">Close</button>
  </div>
</div>
    
</body>


</html>