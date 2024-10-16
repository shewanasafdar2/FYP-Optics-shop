
<body>
    <div class="search-bar">
        <form action="searchProduct.php" method="get">
            <input type="text" name="search" placeholder="Search...">
            <button type="submit" name="search_submit"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>

    <!-------------navbar---------->  
    <nav>
        <div class="logo">
            <a href="#"><img src="img/logo eye.png" alt=""></a>
        </div>
        <ul class="nav-links" id="navLinks">
            <li><a class="active" href="index.php"><i class="fa-solid fa-house"></i>Home</a></li>
            
            <?php $sql = " SELECT * FROM `tbl_categories` WHERE `category_status` = 'A' AND `category_showNav` = '1' ORDER BY `category_id` DESC"; 
            $result = mysqli_query($con,$sql);
            if($result){
                if(mysqli_num_rows($result)>0){
                    while($row = mysqli_fetch_array($result)){
                        $cID = $row['category_id'];
                        ?>
                         <!-- <li><a href="allProducts.php?categoryID=<?php echo $row['category_id']; ?>">
                         <?php echo $row['category_name']; ?></a></li> -->

                         <li class="dropdown">
                            <a href="allProducts.php?categoryID=<?php echo $cID; ?>" class="dropbtn"><?php echo $row['category_name']; ?></a>
                            <?php 
                            $sql2 = "SELECT * FROM `tbl_subcategories` WHERE `subcategory_categoryID` = '$cID' ORDER BY `subcategory_id` DESC";  
                            $result2 = mysqli_query($con,$sql2);
                            if($result2){
                                if(mysqli_num_rows($result2)>0){
                                    ?>
                                     <div class="dropdown-content">
                                        <?php while($row2 = mysqli_fetch_array($result2)){
                                            ?>
                                             <a href="allProducts.php?categoryID=<?php echo $cID; ?>&subCategoryID=<?php echo $row2['subcategory_id']; ?>"><?php echo $row2['subcategory_name'] ?></a>
                                             <div class="page-divider"></div>
                                            <?php
                                        } ?>
                                       
                                        
                                    </div>
                                    <?php
                                }
                            }
                            
                            ?>
                           
                        </li>
                        <?php
                    }
                }
            }
            
            ?>
            
            
           
            <li><a href="allProducts.php">All Products</a></li>
            <?php if(isUserLogin() === true && $_SESSION['userType'] == "C"){ 
                ?>

          
            <li class="dropdown">
                <a href="javascript:;" class="dropbtn"><i class="fa-solid fa-user"></i><?php echo $_SESSION['userName']; ?></a>
                <div class="dropdown-content">
                    <a href="myProfile.php"><i class="fa fa-user"></i>My Profile</a>
                    <div class="page-divider"></div>
                    <a href="changePassword.php"><i class="fa fa-lock"></i> Change Password</a>
                    <div class="page-divider"></div>
                    <a href="myOrders.php"><i class="fa fa-truck"></i> My Orders</a>
                    <div class="page-divider"></div>
                    <a href="myCart.php"><i class="fa-solid fa-cart-shopping"></i> My Cart</a>
                    <div class="page-divider"></div>
                    <a href="logout.php"><i class="fas fa-sign-out"></i> Logout</a>
                </div>
            </li>
            <li>
                 <?php
                $cartCount = 0;
                $sql = "SELECT SUM(cart_productQty) as cart_productQty FROM tbl_cart WHERE cart_userID = ".$_SESSION['userID'];
                $result = mysqli_query($con,$sql);
                if($result){
                $row = mysqli_fetch_array($result);
                $cartCount = $row['cart_productQty'];
             }
            ?>
                <a href="myCart.php">
                <?php if($cartCount > 0){ ?>
                <span class="notification-badge"><?php echo $cartCount; ?></span>
                <?php } ?>
                <i class="fa-solid fa-cart-shopping"  style="color:#939ca3;"></i></a>
            </li>
            <li class="dropdown">
                   <?php 
                $notificationFor = $_SESSION['userType'];
                $notificationForID = $_SESSION['userID'];


                $sql = "SELECT * FROM `tbl_notifications` WHERE `notification_for` = '$notificationFor' AND `notification_forID` = '$notificationForID' AND `notification_status` = '0' ORDER BY `notification_id` DESC";
                $result = mysqli_query($con,$sql);
                $totalNotifications = mysqli_num_rows($result);
                ?>

            <a href="javascript:;" class="dropbtn">
            <i class="fa-solid fa fa-bell" style="color:#939ca3;"></i>
            <?php if($totalNotifications>0){ ?>
            <span class="notification-badge"><?php echo $totalNotifications; ?></span>
            <?php } ?>
            </a>
                <div class="dropdown-content">
                    <?php if($totalNotifications>0){
                        while($row = mysqli_fetch_array($result)){
                            if($row['notification_type'] == "O"){
                                $notificationURL = "orderDetails.php?order_id=".$row['notification_typeID']."&&notificationID=".$row['notification_id'];
                            }else{
                                $notificationURL = "javascript:;";
                            }
                            ?>
                            <a href="<?php echo  $notificationURL; ?>"><?php echo $row['notification_title']; ?></a>
                            <div class="page-divider"></div>
                        
                            <?php
                        }
                    } ?>
                    
                    
                </div>
            </li>
                <?php
            }else{
                ?>
                <li><a href="login.php"><i class="fa-solid fa-user"></i>Login</a></li>
               
                <?php
            }?>
            
            
        </ul>
        
<a href="javascript:;" class="menu-btn" onclick="myFunction()">
    <i class="fa fa-bars"></i>
  </a>
    </nav>
    

</body>
<script>
function myFunction() {
  var x = document.getElementById("navLinks");
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
}
// 
</script>    
