<?php require "includes/head.php";?>
<?php require "includes/navbar.php";?>
<?php
if(isUserLogin() == false || $_SESSION["userType"] != "C"){
    header("Location: login.php");
    exit();
}

$customerID = $_SESSION['userID'];

// Retrieve orders for the current user
$sql = "SELECT * FROM `tbl_orders` WHERE `order_userID` = '$customerID' ORDER BY `order_id`";


$result = mysqli_query($con, $sql);


?>
<body>
    
    <section id="product1">
        <h1>My Orders</h1>
    </section>
    <div class="container5">
        <?php
        if(isset($_SESSION['successMessage']) ){
            ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['successMessage']; ?>
            </div>
            <?php
            unset($_SESSION['successMessage']);
        }
        if(isset($_SESSION['errorMessage']) ){
            ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['errorMessage']; ?>
            </div>
            <?php
            unset($_SESSION['errorMessage']);
        }
        ?>
        <table>
            <thead>
                <tr>
                    <th>Sr#</th>
                    <th>Order NO </th>
                    <th>Order date</th>
                    <th>Order Status</th>
                   
                    <th>Total Price</th>
                    <th>Rating</th>
                    
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if(mysqli_num_rows($result) > 0){
                $sr = 1;
                while($row = mysqli_fetch_assoc($result)){
                    ?>
                <tr>
                            <td><?php echo $sr++; ?></td>
                            <td><?php echo $row['order_no']; ?></td>
                            <td><?php echo date("Y-m-d", strtotime($row['order_date'])); ?></td>
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
                            <td><?php echo $row['order_totalCost']; ?></td>
                            <td><?php echo getOrderRating( $row['order_id']); ?></td>
                            <td>
                                <button><a href="orderDetails.php?order_id=<?php echo $row['order_id']; ?>">Details</a>
                                </button>
                            </td>
                </tr>
                <?php
                }
            } else {
                echo "No orders found for this user.";
            }
            ?>
            </tbody>
        </table>
    </div>
    <section id="banner" class="section-m1">
        <h4>Repair Services</h4>
        <h2>Up to <span>50% Off</span> - All types of Products</h2>
        <button>Explore More</button>
      </section>
</body>
</html>