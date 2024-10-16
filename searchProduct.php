<?php require "includes/head.php";
require "includes/navbar.php";



if (isset($_GET['search_submit'])) {
    $search_query = $_GET['search'];

    $sql = "SELECT * FROM tbl_products WHERE product_name LIKE '%$search_query%'  OR product_image LIKE '%$search_query%' OR product_price LIKE '%$search_query%' OR product_discount LIKE '%$search_query%'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $product_id = $row['product_id'];
            $product_name = $row['product_name'];
            $product_image = $row['product_image'];
            $productPrice = $row['product_price'];
            $productDiscountPrice = $row['product_discount'];
            
            
            // Store the data in an array
            $search_results[] = array(
                'product_id' => $product_id,
                'product_name' => $product_name,
                'product_image' => $product_image,
                'product_price' => $productPrice,
                'product_discount' => $productDiscountPrice,
                
            );
        }
    } else {
        $no_results = true;
    }

   
}?>


<body>
<section id="product2" class="section-p1 container-6">


<?php if (isset($search_results)): ?>
        <!-- <h2>Search Results</h2> -->
        <div class="slider-wrap">
         <?php foreach ($search_results as $result): ?>
            <div style="cursor:pointer;" onclick="window.location.href='<?php echo $pageUrl; ?>'" class="pro">
                                <p><img src="<?= "admin/".$result['product_image']; ?>" alt=""></p>
                                <div style="padding:10px;" class="des">
                                  <h5>  <?= $result['product_name']; ?></h5>

                                
                                </div>
                    
                                <div style="color: grey;font-weight: bold;font-size:1.2rem; padding-bottom:20px;" class="des">
                                  

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
            <?php endforeach; ?>
                   
                </div>
           
             
              
    <?php elseif (isset($no_results)): ?>
        <h1>No results found for '<?= $_GET['search']; ?>'</h1>
    <?php endif; ?>
    </section>

</body>
</html>