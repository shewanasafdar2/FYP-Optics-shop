<?php require "includes/head.php"; ?>
<?php require "includes/navbar.php"; 

$productName = $productStatus = $productPrice = $productDiscount = $productDiscountPrice =  $productDescription = $productImage = $productUpdatedDate = $productCategory = "";
if(isset($_GET['productID'])){
  $productID = $_GET['productID'];
   $sql = "SELECT `tbl_categories`.*,`tbl_products`.* FROM `tbl_products` INNER JOIN `tbl_categories` ON `tbl_categories`.`category_id`  = `tbl_products`.`product_categoryID` WHERE `tbl_products`.`product_id` = '$productID'";
  
  $result = mysqli_query($con,$sql);
  if($result){
    if(mysqli_num_rows($result) == 1){
      if($row = mysqli_fetch_array($result)){
        $productName = $row['product_name'];
        $productImage = "admin/".$row['product_image'];
        $productStatus = $row['product_status'];
        $productCategoryID =$row['product_categoryID'];
        $productCategory =$row['category_name'];

        $productPrice = $row['product_price'];
        $productDiscount = $row['product_discount'];
        $productDiscountPrice = calculateProductDiscount($productID);
        $productDescription = $row['product_description'];
      }
    }else{
      $_SESSION["errorMessage"] = "Access Denied...!";
      header("location:viewAllProducts.php");
      exit();
    }
  }
}


if(isset($_GET['cartQty'])){
  $cartQty = $_GET['cartQty'];
  $cartProdcutPrice = $productPrice - $productDiscountPrice;
  $userID = $_SESSION['userID'];
  $qty = checkProductExistINCart($userID,$productID);

  if($qty == 0){
    $NOW = date("Y-m-d h:i:s");
    $sql= "INSERT INTO `tbl_cart` (`cart_userID`, `cart_productID`, `cart_productPrice`, `cart_productQty`, `created_date`) VALUES ('$userID', '$productID', '$cartProdcutPrice', '$cartQty',  '$NOW()')";

  }else{
   $cartQty += $qty;
   
    $sql= "UPDATE `tbl_cart` SET `cart_productQty` = '$cartQty' WHERE `cart_userID` = '$userID' AND `cart_productID` = '$productID'";
  }

       $result = mysqli_query($con, $sql);

    if ($result) {
      $_SESSION['successMessage'] = "Product Add to Cart Successfully";
      header("location: myCart.php");
      exit();

}
}




?>
    <!-----------section1-------->
    <section id="prodetails" class="section-p1">
        <div class="firstimage">
                    <?php if( $productImage != "admin/" && file_exists( $productImage )){
                                            ?>
                                            <img src="<?php echo $productImage; ?>" alt="<?php echo $rowProd['product_name']." Image"; ?>">
                                            <?php
                                          }else{
                                            ?>
                                            <img src="men eyeglasses\mens eyeglasses_7.jpg" alt="Image 1">
                                            <?php
                                          } ?>
          <img <?php echo $productImage?> width="100%" alt="">
        </div>
        <div class="firstimage-details">
            <h6><?php echo $productCategory; ?></h6>
            <h4><?php echo $productName;   ?></h4>
            <h2>
                <?php if($productDiscountPrice > 0) {
                    ?>
                    <del>RS <?php echo $productPrice; ?>/-</del>
                    RS <?php echo $productPrice - $productDiscountPrice; ?>/- <span class="Discount">50% OFF</span>
                    <?php
                }else{
                    ?>
                    <span><?php echo $productPrice; ?> PKR</span>
                    <?php
                }?>
            </h2>
            <?php
            if(isUserLogin() == true && $_SESSION["userType"] == "C"){
              ?>
              
              <input type="number" id="quantity" name="quantity" min="0" value="1">
              <button style="cursor:pointer" onclick="addtocart();"><a href="javascript:;">Add To Cart</a></button>
            <?php
            } else{
              ?>
                <button style="cursor:pointer"><a href="login.php">Login First</a></button>
              <?php
            }
            ?>
            <span style="<?php if($productStatus == "IS"){ echo "background-color: #C6F7D0; padding:0.7rem; color: green; font-weight:bold;"; } else if($productStatus == "OS"){ echo "background-color: #FFC5C5;padding:0.7rem; color: red; font-weight:bold;"; } ?>">
            <?php if( $productStatus== "IS"){
                      echo "In-Stock";
                    } else if( $productStatus== "OS"){
                      echo "Out of Stock";
                    } ?>
            </span>
            <h4>Product Description</h4>
            <span><?php echo $productDescription; ?></span>
        </div>
    </section>
    <section id="banner" class="section-m1">
            <h4>Repair Services</h4>
            <h2>Up to <span>50% Off</span> - All types of Products</h2>
            <button>Explore More</button>
    </section>
    <?php require "includes/footer.php"; ?>
    <script src="script.js"></script>
    <script>
      function addtocart(){
        let quantity = document.getElementById("quantity").value;
        // alert(quantity);
        if(quantity == "" || quantity < 1 ){
          alert("Please enter valid quantity");
        }else{
          window.location.href= "singleProduct.php?productID=<?php echo $productID; ?>&cartQty="+quantity;
          
        }

      }
    </script>
</body>
</html>