<?php require "includes/head.php"; ?>
<?php require "includes/navbar.php"; ?>
    <!-----------section1-------->
    <section id="hero">
        <h4>Trade-in-offer</h4>
        <h2>Super value deals</h2>
        <h1 style="margin-top:1rem">On all products</h1>
        <p>Save more with upto 50% off</p>
        <button><a href="allProducts.php">Shop now</a></button>
    </section>
    <section id="shop">
        <h2>Shop by category</h2>
        <div class="circle-container">
        <?php $sql = " SELECT * FROM `tbl_categories` WHERE `category_status` = 'A' ORDER BY `category_id` DESC LIMIT 4" ; 
            $result = mysqli_query($con,$sql);
            if($result){
                if(mysqli_num_rows($result)>0){
                    while($row = mysqli_fetch_array($result)){
                      $categoryIcon = "admin/".$row['category_icon'];
                      $categoryPageUrl = "pagename.php?categoryID=".$row['category_id'];
                        ?>
                          <div style="cursor:pointer;" onclick="window.location.href='<?php echo $categoryPageUrl; ?>'" class="circle">
                            <?php if( $categoryIcon != "admin/" && file_exists( $categoryIcon )){
                              ?>
                              <img src="<?php echo $categoryIcon; ?>" alt="<?php echo $row['category_name']." Icon"; ?>">
                              <?php
                            }else{
                              ?>
                              <img src="mens sunglasses/mens sunglasses_05.jpg" alt="Image 1">
                              <?php
                            } ?>
                            
                        </div>
                        <?php
                    }
                }
            }
            
            ?>
           
            <!-- <div class="circle">
                <img src="womens sunglasses/women's sunglasses_09.jpg" alt="Image 2">
            </div>
            <div class="circle">
                <img src="womens eyeglasses/women's eyeglasses_06.jpg" alt="Image 3">
            </div> -->
        </div>
    </section>
  <?php
    $sql = " SELECT * FROM `tbl_categories` WHERE `category_status` = 'A' ORDER BY `category_id` DESC LIMIT 3"; 
   $result = mysqli_query($con,$sql);
   if($result){
       if(mysqli_num_rows($result)>0){
           while($row = mysqli_fetch_array($result)){
            $cateID = $row['category_id'];
            $cateTitle = $row['category_name'];

              ?>
              <section id="product1" class="section-p1">
                         <h2><?php echo $cateTitle; ?></h2>
                          <p>Stay Cool with Shades</p>
                          <div class="pro-container">
              <?php

               $sqlProd = "SELECT * FROM `tbl_products` WHERE `product_categoryID` = '$cateID' ORDER BY `product_id` DESC LIMIT 3";
                $resultProd = mysqli_query($con,$sqlProd);
                if($result){
                  if(mysqli_num_rows($resultProd)>0){
                    while($rowProd = mysqli_fetch_array($resultProd)){
                      $productImage = "admin/".$rowProd['product_image'];
                      $productPrice = $rowProd['product_price'];
                      $productDiscount = $rowProd['product_discount'];
                      $productDiscountPrice = calculateProductDiscount($rowProd['product_id']);
                      $pageUrl = "singleProduct.php?productID=".$rowProd['product_id'];
                      ?>
                        
                              <div style="cursor:pointer" onclick="window.location.href='<?php echo $pageUrl; ?>'" class="pro">
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
                                <div style="padding:0.4rem; color: grey;font-weight: bold;font-size:1.2rem" class="des">
                                  <!-- <h5><?php //echo $rowProd['product_price']; ?></h5> -->

                                  <?php if($productDiscountPrice > 0) {
                                      ?>
                                      <del>RS <?php echo $productPrice; ?> </del>
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
                
              
           }
       }
      }
   
  
  
  ?>
    
        
          <section id="banner" class="section-m1">
            <h4>Repair Services</h4>
            <h2>Up to <span>50% Off</span> - All types of Products</h2>
            <button><a href="allProducts.php">Explore more</a></button>
          </section>
          <?php require "includes/footer.php"; ?>
    <script src="script.js"></script>
    
    
</html>