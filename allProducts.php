<?php require "includes/head.php"?>
<?php require "includes/navbar.php"?>
<?php
$categoryName = $categoryID = $subCategoryID= "";
$whereClasue = "WHERE (`product_status` = 'IS' OR `product_status` = 'OS')";
$subcategoryName = "";

if(isset($_GET['categoryID'])){
  $categoryID = $_GET['categoryID'];

  
  $sql = "SELECT * FROM `tbl_categories` WHERE `category_id` = '$categoryID' AND `category_status` = 'A'";
  $result = mysqli_query($con,$sql);
  if($result){
    if(mysqli_num_rows($result) == 1){
      $whereClasue.=" AND `product_categoryID` = '$categoryID' ";
      $sqlProduct = "SELECT * FROM `tbl_products` ".$whereClasue." ORDER BY `product_id` DESC";
      if($row = mysqli_fetch_array($result)){

        if(isset($_GET['subCategoryID'])){
          $subCategoryID = $_GET['subCategoryID'];
          $sql2 = "SELECT * FROM `tbl_subcategories` WHERE `subcategory_id` = '$subCategoryID' AND `subcategory_status` = 'A'";
          $result2 = mysqli_query($con,$sql2);
          if($result2){
            if(mysqli_num_rows($result2) == 1){
              if($row2 = mysqli_fetch_array($result2)){
                $subcategoryName = $row2['subcategory_name'];
                $subcategoryIcon = $row2['subcategory_icon'];
              }
            }
          }
          $whereClasue.=" AND `product_subCategoryID` = '$subCategoryID'";
          $sqlProduct = "SELECT * FROM `tbl_products` ".$whereClasue." ORDER BY `product_id` DESC";
        }
        $categoryName = $row['category_name'];
        $categoryIcon = $row['category_icon'];
        
        $categoryStatus = $row['category_status'];
       
      }
    }else{
      $_SESSION["errorMessage"] = "Access Denied...!";
      header("location:viewAllProducts.php");
    }
  }else{
    $_SESSION["errorMessage"] = "Access Denied...!";
    header("location:viewAllProducts.php");
  }
}else{
  
  $sqlProduct = "SELECT * FROM `tbl_products` ".$whereClasue." ORDER BY `product_id` DESC";
}
  
  ?>
    <!-----------section1-------->
    <section id="Page-header">
        <h1>
            Products
        </h1>
        <p>Available for all mens, womens and kids</p>
    </section>
    <section id="product2" class="section-p1 container-6">
            <h2>
            <?php 
                if($categoryName != ""){
                    echo $categoryName;
                    if($subcategoryName != ""){
                      echo " <small>(".$subcategoryName.")</small>";
                    }
                }
                else{
                    echo "All Products";
                }
            ?>
            </h2>
            <p>Stay Cool with Shades</p>
            
        <div class="slider-wrap">
                <!-- <div class="image-list">
                    <img src="mens sunglasses/mens sunglasses_01.jpg" alt="" class="image-item">
                </div> -->
            <?php
            // echo $sqlProduct;
                $resultProd = mysqli_query($con,$sqlProduct);
                if($result){
                  if(mysqli_num_rows($resultProd)>0){
                    while($rowProd = mysqli_fetch_array($resultProd)){
                      $productImage = "admin/".$rowProd['product_image'];
                      $productPrice = $rowProd['product_price'];
                      $productStatus = $rowProd['product_status'];

                      $productDiscount = $rowProd['product_discount'];
                      $productDiscountPrice = calculateProductDiscount($rowProd['product_id']);
                      $pageUrl = "singleProduct.php?productID=".$rowProd['product_id'];
                      ?>
                        
                              <div style="cursor:pointer;" onclick="window.location.href='<?php echo $pageUrl; ?>'" class="pro">
                                  <!-- <img src="men eyeglasses\mens eyeglasses_7.jpg" alt="">
                                    -->

                                    <?php if( $productImage != "admin/" && file_exists( $productImage )){
                                            ?>
                                            <img src="<?php echo $productImage; ?>" alt="<?php echo $rowProd['product_name']." Image"; ?>">
                                            <?php
                                          }else{
                                            ?>
                                            <img src="men eyeglasses\mens eyeglasses_7.jpg" alt="Image 1">
                                            <?php
                                          } ?>
                                   <div style="padding:10px;" class="des">
                                  <h5><?php echo $rowProd['product_name']; ?></h5>

                                
                                </div>
                                <div style="color: grey;font-weight: bold;font-size:1.2rem; padding-bottom:20px;" class="des">
                                  <!-- <h5><?php //echo $rowProd['product_price']; ?></h5> -->

                                  <?php if($productDiscountPrice > 0) {
                                      ?>
                                      <del> RS <?php echo $productPrice; ?></del>
                                      RS <?php echo $productPrice - $productDiscountPrice; ?> 
                                      <?php
                                  }else{
                                      ?>
                                      <span><?php echo $productPrice; ?> PKR</span>
                                      <?php
                                  }?>
                                  <!-- <a href="singleProduct.php?productID=<?php //echo $rowProd['product_id']; ?>">View Details</a> -->
                                </div>

                                
                              </div> 
                         
                        <?php
                    }
                    ?>
                    </div>
                    </section>
                    <?php
                    
                  }
                }
                ?>
                
             
              
        </div>
    </section>
    
    <section id="banner" class="section-m1">
            <h4>Repair Services</h4>
            <h2>Up to <span>50% Off</span> - All types of <?php 
                if($categoryName != ""){
                    echo $categoryName;
                }
                else{
                    echo "All Products";
                }
            ?></h2>
            <button>Explore More</button>
          </section>
          <?php require "includes/footer.php"; ?>
    <script src="script.js"></script>
</body>
</html>