<?php require "includes/head.php"; 
if(!isset($_SESSION['errors']) || count($_SESSION['errors']) == 0){
  $_SESSION['errors'] = array();
}
if(isset($_GET['notificationID'])){
  $notificationID = $_GET['notificationID'];
  $sql = "UPDATE `tbl_notifications` SET `notification_status` = '1' WHERE `notification_id` = '$notificationID'";
  $result = mysqli_query($con,$sql);
}

?>
<?php require "includes/navbar.php"; 
$rateDescription = $rateStars = "";
if(isUserLogin() == false || $_SESSION["userType"] != "C"){
  header("Location: login.php");
  exit();
}
// $customerID = $_SESSION['userID'];
if (isset($_GET['order_id'])) {
  $orderID = $_GET['order_id'];
  
} 
if (isset($_GET['order_return_userID'])) {
  $orderReturnUserID = $_POST['order_return_userID'];
  
} 



// Retrieve order details and product information using a JOIN query
$sql = "SELECT `tbl_orders`.*, `tbl_users`.*, `tbl_products`.*, `tbl_order_details`.* 
        FROM `tbl_order_details` 
        INNER JOIN `tbl_products` ON `tbl_products`.`product_id` = `tbl_order_details`.`order_productID` 
        INNER JOIN `tbl_orders` ON `tbl_orders`.`order_id` = `tbl_order_details`.`order_orderID` 
        INNER JOIN `tbl_users` ON `tbl_orders`.`order_userID` = `tbl_users`.`user_id` 
        WHERE `tbl_order_details`.`order_orderID` = '$orderID' 
        ORDER BY `tbl_order_details`.`order_detail_id`";
        $result = mysqli_query($con, $sql);
        if($result){
          if(mysqli_num_rows($result) == 1){
            if($row = mysqli_fetch_array($result)){
              $totalPrice=0;
              $orderNo = $row['order_no'];
              $orderDate = $row['order_date'];
              $orderTotalCost = $row['order_totalCost'];
              $orderStatus = $row['order_status'];
              $userAddress = $row['user_address'];
              $productName = $row['product_name'];
              $producttotalCost = $row['order_totalCost'];
              $productQty = $row['order_productQty'];
              $productImage = $row['product_image'];
              $unitPrice = $row['product_price'];
              $discountPercent = $row['product_discount']; // assuming you have a discount percent column
              $discountAmount = ($unitPrice * $discountPercent) / 100;
              $discountPrice = $unitPrice - $discountAmount;
              $total = $productQty * $discountPrice;
              $totalPrice += $total;
        
              
            
            }
          }else{
            $_SESSION["errorMessage"] = "Access Denied...!";
            header("location:myOrders.php");
            exit();
          }
        }

if(isset($_GET['oStatus'])){
  $orderStatus = $_GET['oStatus'];
  //update order status where order id jo top sae receive ki hae "$orderID"
  $sql = "UPDATE `tbl_orders` SET `order_status` = '$orderStatus' WHERE `order_id` = '$orderID'";
  $result = mysqli_query($con,$sql);

  $notificationTitle= $_SESSION['userName']." has been cancelled order #: ".$orderNo;
                        $notificationFor = 'A';
                        $notificationForID = 1;
                        $notificationType = 'O';
                        $notificationTypeID = $orderID;
                        $notificationStatus = '0';
                        $notificationDate = date("Y-m-d h:i:s");
                        $sqlNotification="INSERT INTO `tbl_notifications` (`notification_title`,`notification_for`, `notification_forID`,`notification_typeID`, `notification_type`, `notification_status`,`notification_createdDate`) VALUES ('$notificationTitle','$notificationFor','$notificationForID','$notificationTypeID','$notificationType','$notificationStatus','$notificationDate')";
                        $resultNotification = mysqli_query($con,$sqlNotification);
                        

}
if(isset($_POST['returnProductBtn'])){
  if(empty($_POST['returnReason'])){
    array_push($_SESSION['errors'],"Return Reason is Required");
  }else{
    $returnReason = mysqli_real_escape_string($con,$_POST['returnReason']); 
  }
  if(isset($_SESSION['errors']) && count($_SESSION['errors']) == 0){
    $returnDate = date("Y-m-d h:i:s");
    $orderReturnUserID = $_POST['order_return_userID']; // Access the value using $_POST
    
    $sql = "INSERT INTO `tbl_orders_return` (`order_return_description`, `order_return_date`, `orderID`, `order_return_userID`) VALUES ('$returnReason', '$returnDate', '$orderID', '$orderReturnUserID')";
    $result = mysqli_query($con, $sql);
    $sql = "UPDATE `tbl_orders` SET `order_status` = 'Return' WHERE `order_id` = '$orderID'";
    $result = mysqli_query($con,$sql);

    // Send return order notification to admin
    $notificationTitle= $_SESSION['userName']." has been return order #: ".$orderNo;
    $notificationFor = 'A';
    $notificationForID = 1;
    $notificationType = 'O';
    $notificationTypeID = $orderID;
    $notificationStatus = '0';
    $notificationDate = date("Y-m-d h:i:s");
    $sqlNotification="INSERT INTO `tbl_notifications` (`notification_title`,`notification_for`, `notification_forID`,`notification_typeID`, `notification_type`, `notification_status`,`notification_createdDate`) VALUES ('$notificationTitle','$notificationFor','$notificationForID','$notificationTypeID','$notificationType','$notificationStatus','$notificationDate')";
    $resultNotification = mysqli_query($con,$sqlNotification);

    if($result){
      $_SESSION['successMessage'] = "Product Return Successfully";
      header("location:myOrders.php");
      exit();
    }
  } 
  }


$rateDescription = $rateStars = "";
$rateFlag = 0;
$sqlRating = "SELECT * FROM tbl_ratings WHERE rating_orderID = '$orderID'";
$resultRating = mysqli_query($con,$sqlRating);
if($resultRating){
  if (mysqli_num_rows($resultRating) == 1) {
    if($rowRating = mysqli_fetch_array($resultRating)){
      $rateFlag = 1;
      $rateStars = $rowRating['rating_stars'];
      $rateDescription = $rowRating['rating_description'];
    }
  }
}

if (isset($_POST['rateNow'])) {
  if (empty($_POST['rateStars'])) {
      array_push($_SESSION['errors'],'Please Select Stars');
  }else{
      $rateStars = mysqli_real_escape_string($con,$_POST['rateStars']);
  }
  $rateDescription = mysqli_real_escape_string($con,$_POST['rateDescription']);

  if(count($_SESSION['errors']) == 0 || !isset($_SESSION['errors'])){
    $rateDate = date("Y-m-d h:i:s");
    $userID = $_SESSION['userID'];

    if($rateFlag == 1){
      $notificationTitle= $_SESSION['userName']." update rating to order #: ".$orderNo;
      
      $sql = "UPDATE tbl_ratings SET rating_stars = '$rateStars',rating_description = '$rateDescription',rating_date = '$rateDate' WHERE rating_orderID = '$orderID' AND rating_userID = '$userID'";
    }else{
      $notificationTitle= $_SESSION['userName']." give rating to order #: ".$orderNo;
      
      $sql = "INSERT INTO tbl_ratings (rating_orderID,rating_userID,rating_stars,rating_description,rating_date) VALUES ('$orderID','$userID','$rateStars','$rateDescription','$rateDate')";  
    }

    
    $result = mysqli_query($con,$sql);
    if ($result) {

      $notificationFor = 'A';
      $notificationForID = 1;
      $notificationType = 'O';
      $notificationTypeID = $orderID;
      $notificationStatus = '0';
      $notificationDate = date("Y-m-d h:i:s");
      $sqlNotification="INSERT INTO `tbl_notifications` (`notification_title`,`notification_for`, `notification_forID`,`notification_typeID`, `notification_type`, `notification_status`,`notification_createdDate`) VALUES ('$notificationTitle','$notificationFor','$notificationForID','$notificationTypeID','$notificationType','$notificationStatus','$notificationDate')";
      $resultNotification = mysqli_query($con,$sqlNotification);
      $_SESSION['successMessage'] = "Rating has been added Successfully";
      header("location:myOrders.php");
      exit();
    }
  }

}


  ?>
<body>
   
<style type="text/css">
                
                /* Rating Star Widgets Style */
                .rating-stars ul {
                  list-style-type:none;
                  padding:0;
                  
                  -moz-user-select:none;
                  -webkit-user-select:none;
                }
                .rating-stars ul > li.star {
                  display:inline-block;
                  
                }
            
                /* Idle State of the stars */
                .rating-stars ul > li.star > i.fa {
                  font-size:1.5em; /* Change the size of the stars */
                  color:#ccc; /* Color on idle state */
                }
            
                /* Hover state of the stars */
                .rating-stars ul > li.star.hover > i.fa {
                  color:yellow;
                }
            
                /* Selected state of the stars */
                .rating-stars ul > li.star.selected > i.fa {
                  color:#FF912C;
                }
            
              </style>
  <div class="sec">      
    <div class="container8">
        <section id="product2">
            <h2 style="font-size: 2rem; margin-bottom: 1rem;">Order Details</h2>
            
            </section>
        <div class="order-info">
            <table>
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Order Date</th>
                        <th>Order Status</th>
                        <th>Delivery Address</th>
                        
                      
                    </tr>
                </thead>
                
                <tr>
                  
                    <td><?php echo $orderNo; ?></td>
                    <td><?php echo date("d-m-Y",strtotime($orderDate)); ?></td>
                    <td>
                      <?php if($row['order_status'] == 'Pending') { ?>
                    <a href="#" style="list-style:none;text-decoration:none; background-color: #ffc107;color:white;padding:0.4rem;border-radius:1rem;"><?php echo $row['order_status'] ?></a>
                    <?php } elseif($row['order_status'] == 'Return') { ?>
                    <a href="#" style="list-style:none;text-decoration:none; background-color: #17a2b8;color:white;padding:0.4rem;border-radius:1rem;"><?php echo $row['order_status'] ?></a>
                    <?php } elseif($row['order_status'] == 'Delivered') { ?>
                    <a href="#" style="list-style:none;text-decoration:none; background-color: #28a745;color:white;padding:0.4rem;border-radius:1rem;"><?php echo $row['order_status'] ?></a>
                    <?php } elseif($row['order_status'] == 'Cancel') { ?>
                    <a href="#" style="list-style:none;text-decoration:none; background-color: #dc3545 ;color:white;padding:0.4rem;border-radius:1rem;"><?php echo $row['order_status'] ?></a>
                    <?php } ?></td>      
                    <td>
                    <?php echo $userAddress;?>
                    </td>
                    
                </tr>
                
            </table>
        </div>
        <div class="order-summary">
            <section id="product2">
                <h2 style="font-size: 2rem; margin-bottom: 1rem;">Order Summary</h2>
                
                </section>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
         
          
          <tr>
            <td><?php echo $productName; ?></td>
          <td><img src="<?php echo 'admin/' . $productImage ?>" alt="" width="65"></td>
              
            <td><?php echo $productQty; ?></td>
            <td>Rs <?php echo $discountPrice; ?></td>
            <td>Rs <?php echo $total; ?></td>
          </tr>
         
          <tr class="subtotal">
            <td colspan="4">Subtotal:</td>
            <td>Rs <?php echo $totalPrice;?></td>
          </tr>
          <tr class="shipping">
            <td colspan="4">Shipping:</td>
            <td>Free</td>
          </tr>
          <tr class="total">
            <td colspan="4">Total:</td>
            <td>Rs <?php echo $totalPrice;?></td>
          </tr>

          <?php if($orderStatus == "Pending"){
            ?>
            <tr>
              <td colspan="5">
                <button class="btn btn-danger" onclick="window.location.href='orderDetails.php?order_id=<?php echo $orderID; ?>&oStatus=Cancel'">Cancel</button>
              </td>
            </tr>
            <?php
          }else if($orderStatus == "Delivered"){
            ?>
            <tr>
              <td colspan="5">
                <button class="btn btn-danger return-btn" >Return</button>
              </td>
            </tr>
           
            <?php
          } else if($orderStatus == "Return"){
            $sqlReturn = "SELECT * FROM `tbl_orders_return` WHERE `orderID` = '$orderID'";
                  $resultReturn = mysqli_query($con,$sqlReturn);
                  $returnDate = '';
                  $returnReason = '';
                  if($resultReturn){
                    if(mysqli_num_rows($resultReturn) == 1){
                      if($rowReturn = mysqli_fetch_array($resultReturn)){
       
                        $returnReason = $rowReturn['order_return_description'];
                        $returnDate = $rowReturn['order_return_date'];
                      }
                    }
                  }

            ?>
            <tr>
              <th>Description</th>
              <td colspan="4">
                <?php echo $returnReason; ?>
              </td>
            </tr>
            <tr>
              <th>Date</th>
              <td colspan="4">
              <?php echo date("d-m-Y",strtotime($returnDate)); ?>
              </td>
            </tr>
            <?php
          } ?>
        </tbody>
      </table>
    </div>
  </body>

<!-- <?php

if (mysqli_num_rows($result) == 0) {
  echo "No order details found.";
}
?> -->


        
    </div>
        <div class="row ">
                <div class="col-md-12">
                   <section id="product2">
                   <h2 style="font-size: 2.6rem; margin-bottom: 1rem;">Rate this Order</h2>
                
                   </section>
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
                   <?php if($orderStatus == "Delivered" || $orderStatus == "Return"){ ?>
                    <form action="" method="post" role="form" class="">
                      <div class="row">
                        <div class="col-md-12 form-group"  style="margin-bottom:0.8rem">
                          <input type="hidden" id="rateStars" name="rateStars" value=<?php echo $rateStars; ?>>
                          
                          <!-- Rating Stars Box -->
                          <div class='rating-stars text-center'>
                            <ul id='stars'>
                              <li class='star <?php if ($rateStars >= "1" ) {echo "selected";} ?>' title='Poor' data-value='1'>
                                <i class='fa fa-star'></i>
                              </li>
                              <li class='star <?php if ($rateStars >= "2") {echo "selected";} ?>' title='Fair' data-value='2'>
                                <i class='fa fa-star'></i>
                              </li>
                              <li class='star <?php if ($rateStars >= "3") {echo "selected";} ?>' title='Good' data-value='3'>
                                <i class='fa fa-star'></i>
                              </li>
                              <li class='star <?php if ($rateStars >= "4") {echo "selected";} ?>' title='Excellent' data-value='4'>
                                <i class='fa fa-star'></i>
                              </li>
                              <li class='star <?php if ($rateStars >= "5") {echo "selected";} ?>' title='WOW!!!' data-value='5'>
                                <i class='fa fa-star'></i>
                              </li>
                            </ul>
                          </div>
                          
                        </div>
                      </div>
                      
                      <div class="form-group mt-3">
                        <textarea  class="form-control w-100" name="rateDescription" style="height:150px; display:block; width:100%; padding:10px;" placeholder="Message"><?php echo $rateDescription; ?></textarea>
                      </div>
                      
                      <div class="text-center mt-3 pb-3" style="margin-top:0.6rem"><button type="submit" class="get-started-btn m-0 text-white w-100" name="rateNow">Rate Now</button></div>
                    </form>
                  <?php }else if($orderStatus == "Cancel"){
                    echo "You Cancelled";
                  }else if($orderStatus == "Pending"){
                    echo "Your order is in Pending, after Delivery you can rate this order.";
                  } ?>
                </div>
              </div>
        </div>
</div>   
    
    <section id="banner" class="section-m1">
        <h4>Repair Services</h4>
        <h2>Up to <span>50% Off</span> - All types of Products</h2>
        <button>Explore More</button>
      </section>

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){
  
        /* 1. Visualizing things on Hover - See next part for action on click */
        $('#stars li').on('mouseover', function(){
          var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
         
          // Now highlight all the stars that's not after the current hovered star
          $(this).parent().children('li.star').each(function(e){
            if (e < onStar) {
              $(this).addClass('hover');
            }
            else {
              $(this).removeClass('hover');
            }
          });
          
        }).on('mouseout', function(){
          $(this).parent().children('li.star').each(function(e){
            $(this).removeClass('hover');
          });
        });
        
        
        /* 2. Action to perform on click */
        $('#stars li').on('click', function(){
          var onStar = parseInt($(this).data('value'), 10); // The star currently selected
          var stars = $(this).parent().children('li.star');

          $("#rateStars").val(onStar);
          
          for (i = 0; i < stars.length; i++) {
            $(stars[i]).removeClass('selected');
          }
          
          for (i = 0; i < onStar; i++) {
            $(stars[i]).addClass('selected');
          }
          
          
        });
        
        
      });


      
  </script>
<div class="popup-container" id="popup-container">
  <div class="popup-content">
    <h2 style="font-size:1.5rem; text-align:center ">Return Order</h2>
    <form action="orderDetails.php?order_id=<?php echo $orderID; ?>" method="post"enctype="multipart/form-data">
    <input type="hidden" name="order_return_userID" value="<?php echo $_SESSION['userID']; ?>">
      <div class="form-group">
        <textarea name="returnReason" id="returnReason" value="<?php echo $returnReason; ?>" placeholder="Enter Return Reason" required cols="30" rows="10" style="width:100%;padding:10px;"></textarea>
       
        <div class="col-md-12 p-2 mb-2">
          <button type="submit" name="returnProductBtn" class="btn btn-primary float-end">Submit</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
<script>
  // Get the popup container and content elements
  const popupContainer = document.getElementById('popup-container');
  const popupContent = document.querySelector('.popup-content');

  // Get the "Proceed to Checkout" button element
  const returnBtn = document.querySelector('.return-btn');

  // Add an event listener to the "Proceed to Checkout" button
  returnBtn.addEventListener('click', () => {
    // Show the popup container
    popupContainer.style.display = 'block';
  });

  // Add an event listener to the close button
  document.querySelector('.close-btn').addEventListener('click', () => {
    // Hide the popup container
    popupContainer.style.display = 'none';
  });
</script>
</body>
</html>