<footer class="section-p1">
       <div class="col">
        <img src="img/logo eye.png" alt="">
        <h4>About Us</h4>
        <p>At ZAAS VISION optics shop, we think clear vision is key to a happy and full life. That’s why we offer top-quality glasses, contact lenses, and eye exams to suit your needs. Whether you’re looking for stylish new glasses, the latest contact lenses, or a detailed eye check-up, we’ve got everything you need.</p>

       </div>
      <div class="col">
        <h4>Contact Us</h4>
        <p><strong>Adress:</strong>Shop#23 Kamla Chowk Rawalpindi</p>
        <p><strong>Phone:</strong>+92 3320474394</p>
        <p><strong>Timing:</strong>4:00 - 10:00</p>
      </div>
      <div class="col">
        <h4>Quick Links</h4>
        <div class="col1">
        <li><a href="index.php">Home</a></li>
        <?php $sql = " SELECT * FROM `tbl_categories` WHERE `category_status` = 'A' AND `category_showNav` = '1' ORDER BY `category_id` DESC"; 
            $result = mysqli_query($con,$sql);
            if($result){
                if(mysqli_num_rows($result)>0){
                    while($row = mysqli_fetch_array($result)){
                        ?>
                         <li><a href="allProducts.php?categoryID=<?php echo $row['category_id']; ?>">
                         <?php echo $row['category_name']; ?></a></li>
                        <?php
                    }
                }
            }
            
            ?>
        </div>
        <a href="#" id="back-to-top-btn"><i class="fas fa-arrow-up"></i></a>
      </div>
      <div class="col">
       <h4>Follow us</h4>
       <div class="icon">
        <i class="fa-brands fa-facebook-f"></i>
        <i class="fa-brands fa-instagram"></i>
       </div>
      </div>
    </footer>
    <div class="copyright">
      <p>&copy; All Rights Reserved By Online Optics Shop Developed by Wajiha Shewana Areeba</p>
    </div>