<?php
    function get_cart_size($user_ip){
        $sql = "SELECT `cart` FROM `users` WHERE users.ip = '$user_ip' LIMIT 1";
        $result = $GLOBALS['conn']->query($sql);

        if ($result->num_rows > 0){
            $row = $result->fetch_assoc();
            if ($row["cart"] == "") {
                return 0;
            } else {
                return count(explode(",", $row["cart"]));
            }
        }
    }

    $servername = "localhost";
    $username = "root";
    $pw = "";
    $dbname = "rusticmojo_db";
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $conn = new mysqli($servername, $username, $pw, $dbname);
    $cart_size = get_cart_size($user_ip);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>RusticMojo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">

    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">


    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light ftco-navbar-light-2" id="ftco-navbar">
	    <div class="container">
	      <a class="navbar-brand" href="index.php">RusticMojo</a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="oi oi-menu"></span> Menu
	      </button>

	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	          <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
	          <li class="nav-item dropdown active">
              <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Shop</a>
              <div class="dropdown-menu" aria-labelledby="dropdown04">
              	<a class="dropdown-item" href="shop.php">Shop</a>
                <a class="dropdown-item" href="product-single.php">Single Product</a>
                <a class="dropdown-item" href="cart.php">Cart</a>
                <a class="dropdown-item" href="checkout.php">Checkout</a>
              </div>
            </li>
	          <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
	          <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
	          <li class="nav-item cta cta-colored"><a href="cart.php" class="nav-link"><span class="icon-shopping_cart"></span>[<?php echo $cart_size; ?>]</a></li>

	        </ul>
	      </div>
	    </div>
	  </nav>
    <!-- END nav -->

		<div class="hero-wrap hero-bread" style="background-image: url('images/bg_6.jpg');">
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
            <h1 class="mb-0 bread">My Cart</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Cart</span></p>
          </div>
        </div>
      </div>
    </div>
    <?
        $sql = "SELECT `total_cost`, `cart` FROM `users` WHERE users.ip = '$user_ip' LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $subtotal = $row["total_cost"];
            $delivery_fee = 50;
            $final_cost = $subtotal + $delivery_fee;
            if ($row["cart"] != "") {
    ?>
        		<section class="ftco-section ftco-cart">
        			<div class="container">
        				<div class="row">
            			<div class="col-md-12 ftco-animate">
            				<div class="cart-list">
        	    				<table class="table">
        						    <thead class="thead-primary">
        						      <tr class="text-center">
        						        <th>&nbsp;</th>
        						        <th>&nbsp;</th>
        						        <th>Product</th>
        						        <th>Price</th>
        						        <th>Quantity</th>
        						        <th>Total</th>
        						      </tr>
        						    </thead>
        						    <tbody>
                                        <?
                                            $items = explode(",", $row["cart"]);
                                            foreach ($items as $key => $value) {
                                                list($id, $amount) = explode(":", $value);

                                                $sql = "SELECT `name`, `image`, `description`, `price`, `discount` FROM `inventory` WHERE inventory.uid = '$id' LIMIT 1";
                                                $item = $conn->query($sql)->fetch_assoc();

                                                $image_url = $item["image"];
                                                $name = $item["name"];
                                                $description = $item["description"];
                                                $price = $item["price"]*(100-$item["discount"])/100;
                                        ?>
                                                <tr id="entire_item_<?php echo $id; ?>" class="text-center">
                      						        <td class="product-remove"><a onclick="remove_item_from_cart(<?php echo $id; ?>)"><span class="ion-ios-close"></span></a></td>

                      						        <td class="image-prod"><div class="img" style="background-image:url(<?php echo $image_url; ?>);"></div></td>

                      						        <td class="product-name">
                      						        	<h3><?php echo $name; ?></h3>
                      						        	<p><?php echo $description; ?></p>
                      						        </td>

                      						        <td class="price">₹<?php echo $price; ?></td>

                      						        <td class="quantity">
                                                        <div class="input-group mb-3">
                                                            <input onchange="update_item_total(<?php echo $id; ?>, <?php echo $price; ?>)" id = "item_amount_<?php echo $id; ?>" type="text" name="amount" class="quantity form-control input-number" value="<?php echo $amount; ?>">
                                                        </div>
                                                    </td>

                                                    <td id="total_item_cost_<?php echo $id; ?>" class="total">₹<?php echo $price*$amount ?></td>
                                                </tr>
                                                <?
                                            }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            		<div class="row justify-content-end">
            			<div class="col col-lg-5 col-md-6 mt-5 cart-wrap ftco-animate">
            				<div class="cart-total mb-3">
            					<h3>Cart Totals</h3>
            					<p class="d-flex">
            						<span>Subtotal</span>
            						<span id="subtotal">₹<?php echo $subtotal; ?></span>
            					</p>
            					<p class="d-flex">
            						<span>Delivery</span>
            						<span>₹<?php echo $delivery_fee; ?> </span>
            					</p>
            					<hr>
            					<p class="d-flex total-price">
            						<span>Total</span>
            						<span id="final_cost">₹<?php echo $final_cost; ?></span>
            					</p>
            				</div>
            				<p class="text-center"><a href="checkout.php" class="btn btn-primary py-3 px-4">Proceed to Checkout</a></p>
            			</div>
            		</div>
    			</div>
    		</section>
            <?
            }
            else {
                ?>
                <section class="ftco-section bg-light">
                    <div class="container">
                        <div class="row justify-content-center mb-3 pb-3">
                            <div class="col-md-12 heading-section text-center ftco-animate">
                          	<h1 class="big">Cart Empty</h1>
                                <h2 class="mb-4" href="index.php">Add Items to cart from the <a href="index.php" style="color:red;">shop</a></h2>
                            </div>
                        </div>
                    </div>
                </section>
                <?
            }
        }
    $conn->close();
    ?>

    <!-- <section class="ftco-section bg-light">
    	<div class="container">
				<div class="row justify-content-center mb-3 pb-3">
          <div class="col-md-12 heading-section text-center ftco-animate">
          	<h1 class="big">Products</h1>
            <h2 class="mb-4">Related Products</h2>
          </div>
        </div>
    	</div>
    	<div class="container-fluid">
    		<div class="row">
    			<div class="col-sm col-md-6 col-lg ftco-animate">
    				<div class="product">
    					<a href="#" class="img-prod"><img class="img-fluid" src="images/product-1.jpg" alt="Colorlib Template"></a>
    					<div class="text py-3 px-3">
    						<h3><a href="#">Young Woman Wearing Dress</a></h3>
    						<div class="d-flex">
    							<div class="pricing">
		    						<p class="price"><span>₹120.00</span></p>
		    					</div>
		    					<div class="rating">
	    							<p class="text-right">
	    								<span class="ion-ios-star-outline"></span>
	    								<span class="ion-ios-star-outline"></span>
	    								<span class="ion-ios-star-outline"></span>
	    								<span class="ion-ios-star-outline"></span>
	    								<span class="ion-ios-star-outline"></span>
	    							</p>
	    						</div>
	    					</div>
	    					<hr>
    						<p class="bottom-area d-flex">
    							<a href="#" class="add-to-cart"><span>Add to cart <i class="ion-ios-add ml-1"></i></span></a>
    							<a href="#" class="ml-auto"><span><i class="ion-ios-heart-empty"></i></span></a>
    						</p>
    					</div>
    				</div>
    			</div>
    			<div class="col-sm col-md-6 col-lg ftco-animate">
    				<div class="product">
    					<a href="#" class="img-prod"><img class="img-fluid" src="images/product-2.jpg" alt="Colorlib Template"></a>
    					<div class="text py-3 px-3">
    						<h3><a href="#">Young Woman Wearing Dress</a></h3>
    						<div class="d-flex">
    							<div class="pricing">
		    						<p class="price"><span>₹120.00</span></p>
		    					</div>
		    					<div class="rating">
	    							<p class="text-right">
	    								<span class="ion-ios-star-outline"></span>
	    								<span class="ion-ios-star-outline"></span>
	    								<span class="ion-ios-star-outline"></span>
	    								<span class="ion-ios-star-outline"></span>
	    								<span class="ion-ios-star-outline"></span>
	    							</p>
	    						</div>
	    					</div>
	    					<hr>
    						<p class="bottom-area d-flex">
    							<a href="#" class="add-to-cart"><span>Add to cart <i class="ion-ios-add ml-1"></i></span></a>
    							<a href="#" class="ml-auto"><span><i class="ion-ios-heart-empty"></i></span></a>
    						</p>
    					</div>
    				</div>
    			</div>
    			<div class="col-sm col-md-6 col-lg ftco-animate">
    				<div class="product">
    					<a href="#" class="img-prod"><img class="img-fluid" src="images/product-3.jpg" alt="Colorlib Template"></a>
    					<div class="text py-3 px-3">
    						<h3><a href="#">Young Woman Wearing Dress</a></h3>
    						<div class="d-flex">
    							<div class="pricing">
		    						<p class="price"><span>₹120.00</span></p>
		    					</div>
		    					<div class="rating">
	    							<p class="text-right">
	    								<span class="ion-ios-star-outline"></span>
	    								<span class="ion-ios-star-outline"></span>
	    								<span class="ion-ios-star-outline"></span>
	    								<span class="ion-ios-star-outline"></span>
	    								<span class="ion-ios-star-outline"></span>
	    							</p>
	    						</div>
	    					</div>
	    					<hr>
    						<p class="bottom-area d-flex">
    							<a href="#" class="add-to-cart"><span>Add to cart <i class="ion-ios-add ml-1"></i></span></a>
    							<a href="#" class="ml-auto"><span><i class="ion-ios-heart-empty"></i></span></a>
    						</p>
    					</div>
    				</div>
    			</div>
    			<div class="col-sm col-md-6 col-lg ftco-animate">
    				<div class="product">
    					<a href="#" class="img-prod"><img class="img-fluid" src="images/product-4.jpg" alt="Colorlib Template"></a>
    					<div class="text py-3 px-3">
    						<h3><a href="#">Young Woman Wearing Dress</a></h3>
    						<div class="d-flex">
    							<div class="pricing">
		    						<p class="price"><span>₹120.00</span></p>
		    					</div>
		    					<div class="rating">
	    							<p class="text-right">
	    								<span class="ion-ios-star-outline"></span>
	    								<span class="ion-ios-star-outline"></span>
	    								<span class="ion-ios-star-outline"></span>
	    								<span class="ion-ios-star-outline"></span>
	    								<span class="ion-ios-star-outline"></span>
	    							</p>
	    						</div>
	    					</div>
	    					<hr>
    						<p class="bottom-area d-flex">
    							<a href="#" class="add-to-cart"><span>Add to cart <i class="ion-ios-add ml-1"></i></span></a>
    							<a href="#" class="ml-auto"><span><i class="ion-ios-heart-empty"></i></span></a>
    						</p>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </section> -->

    <footer class="ftco-footer bg-light ftco-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Modist</h2>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4 ml-md-5">
              <h2 class="ftco-heading-2">Menu</h2>
              <ul class="list-unstyled">
                <li><a href="#" class="py-2 d-block">Shop</a></li>
                <li><a href="#" class="py-2 d-block">About</a></li>
                <li><a href="#" class="py-2 d-block">Journal</a></li>
                <li><a href="#" class="py-2 d-block">Contact Us</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md-4">
             <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Help</h2>
              <div class="d-flex">
	              <ul class="list-unstyled mr-l-5 pr-l-3 mr-4">
	                <li><a href="#" class="py-2 d-block">Shipping Information</a></li>
	                <li><a href="#" class="py-2 d-block">Returns &amp; Exchange</a></li>
	                <li><a href="#" class="py-2 d-block">Terms &amp; Conditions</a></li>
	                <li><a href="#" class="py-2 d-block">Privacy Policy</a></li>
	              </ul>
	              <ul class="list-unstyled">
	                <li><a href="#" class="py-2 d-block">FAQs</a></li>
	                <li><a href="#" class="py-2 d-block">Contact</a></li>
	              </ul>
	            </div>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
            	<h2 class="ftco-heading-2">Have a Questions?</h2>
            	<div class="block-23 mb-3">
	              <ul>
                      <li><span class="icon icon-map-marker"></span><span class="text">Jodhpur,  Rajasthan, India</span></li>
                      <li><a href="#"><span class="icon icon-phone"></span><span class="text">+91 99280 77850</span></a></li>
                      <li><a href="#"><span class="icon icon-envelope"></span><span class="text">rusticmojo@gmail.com</span></a></li>
	              </ul>
	            </div>
            </div>
          </div>
        </div>
      </div>
    </footer>



  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>

  <script>
      function update_item_total(item_id, price) {
          amount = parseInt(document.getElementById("item_amount_" + item_id).value);
          var new_total = parseFloat(price)*parseFloat(amount);
          document.getElementById("total_item_cost_" + item_id.toString()).innerHTML = "₹" + new_total;
          var xmlhttp = new XMLHttpRequest();
          var link = "actions/set_item_amount.php?item=" + item_id + "&amount=" + amount;
          xmlhttp.open("GET", link, true);
          xmlhttp.send();
          update_cart_details();
      }
      function remove_item_from_cart(item_id){
          elem = document.getElementById("entire_item_" + item_id);
          elem.parentNode.removeChild(elem);

          var xmlhttp = new XMLHttpRequest();
          var link = "actions/remove_item.php?item=" + item_id;
          xmlhttp.open("GET", link, true);
          xmlhttp.send();
          update_cart_details();

          total_list = document.getElementsByClassName("total");
          if (total_list.length < 1) window.location.reload();
      }
      function get_subtotal(total_list){
          total_cost = 0;
          for (var i = 0; i < total_list.length; i++){
              item_cost_str = total_list[i].innerHTML;
              item_cost = parseFloat(item_cost_str.substr(1, item_cost_str.length));
              total_cost += item_cost;
          }
          return total_cost;
      }
      function update_cart_details(){
          total_list = document.getElementsByClassName("total");
          subtotal = get_subtotal(total_list);
          document.getElementById("subtotal").innerHTML = "₹" + subtotal;
          document.getElementById("final_cost").innerHTML = "₹" + (50 + subtotal);
      }
  </script>

  </body>
</html>
