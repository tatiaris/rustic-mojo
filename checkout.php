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
                    <a class="dropdown-item" href="cart.php">Cart</a>
                    <a class="dropdown-item" href="checkout.php">Checkout</a>
                  </div>
                </li>
    	          <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
    	          <li class="nav-item"><a href="blog.php" class="nav-link">Blog</a></li>
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
            <h1 class="mb-0 bread">Checkout</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Checkout</span></p>
          </div>
        </div>
      </div>
    </div>

    <?
        $sql = "SELECT `total_cost`, `cart` FROM `users` WHERE users.ip = '$user_ip' LIMIT 1";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0){
            $row = $result->fetch_assoc();
            $subtotal = $row["total_cost"];
            $delivery_fee = 50;
            $final_cost = $subtotal + $delivery_fee;
            if ($row["cart"] != "") {
    ?>
            	<section class="ftco-section">
                  <div class="container">
                    <div class="row justify-content-center">
                      <div class="col-xl-8 ftco-animate">
						<form action="#" class="billing-form bg-light p-3 p-md-5">
                            <h3 class="mb-4 billing-heading">Shipping Details</h3>
            	          	<div class="row align-items-end">
                                <div class="col-md-6">
            	                <div class="form-group">
            	                	<label for="firstname">First Name *</label>
            	                  <input id="firstname" type="text" class="form-control" placeholder="">
            	                </div>
                            </div>
            	              <div class="col-md-6">
            	                <div class="form-group">
            	                	<label for="lastname">Last Name *</label>
            	                  <input id="lastname" type="text" class="form-control" placeholder="">
            	                </div>
                            </div>
                            <div class="w-100"></div>
            		            <div class="col-md-12">
            		            	<div class="form-group">
            		            		<label for="country">State / Country *</label>
            		            		<div class="select-wrap">
            		                  <!-- <div class="icon"><span class="ion-ios-arrow-down"></span></div> -->
            		                  <!-- <select name="" id="" class="form-control"> -->
            		                  	<opton id="country" value="">India</option>
            		                  <!-- </select> -->
            		                </div>
            		            	</div>
            		            </div>
            		            <div class="w-100"></div>
            		            <div class="col-md-6">
            		            	<div class="form-group">
            	                	<label for="streetaddress">Street Address *</label>
            	                  <input id="streetaddress" type="text" class="form-control" placeholder="House number and street name">
            	                </div>
            		            </div>
            		            <div class="col-md-6">
            		            	<div class="form-group">
            	                  <input id="address_line_2" type="text" class="form-control" placeholder="Appartment, suite, unit etc: (optional)">
            	                </div>
            		            </div>
            		            <div class="w-100"></div>
            		            <div class="col-md-6">
            		            	<div class="form-group">
            	                	<label for="towncity">Town / City *</label>
            	                  <input id="towncity" type="text" class="form-control" placeholder="">
            	                </div>
            		            </div>
            		            <div class="col-md-6">
            		            	<div class="form-group">
            		            		<label for="postcodezip">Postcode / ZIP *</label>
            	                  <input id="postcodezip" type="text" class="form-control" placeholder="">
            	                </div>
            		            </div>
            		            <div class="w-100"></div>
            		            <div class="col-md-6">
            	                <div class="form-group">
            	                	<label for="phone">Phone</label>
            	                  <input id="phone" type="text" class="form-control" placeholder="">
            	                </div>
            	              </div>
            	              <div class="col-md-6">
            	                <div class="form-group">
            	                	<label for="emailaddress">Email Address *</label>
            	                  <input id="emailaddress" type="text" class="form-control" placeholder="">
            	                </div>
                            </div>
                            <div class="w-100"></div>
                            <!-- <div class="col-md-12">
                            	<div class="form-group mt-4">
            						<div class="radio">
            						  <label class="mr-3"><input type="radio" name="optradio"> Create an Account? </label>
            						  <label><input type="radio" name="optradio"> Ship to different address</label>
            						</div>
            					</div>
                            </div> -->
            	            </div>
            	          </form><!-- END -->



            	          <div class="row mt-5 pt-3 d-flex">
            	          	<div class="col-md-6 d-flex">
            	          		<div class="cart-detail cart-total bg-light p-3 p-md-4">
            	          			<h3 class="billing-heading mb-4">Cart Total</h3>
            	          			<p class="d-flex">
                						<span>Subtotal</span>
                						<span>₹<?php echo $subtotal; ?></span>
                					</p>
                					<p class="d-flex">
                						<span>Delivery</span>
                						<span>₹<?php echo $delivery_fee; ?></span>
                					</p>
                					<hr>
                					<p class="d-flex total-price">
                						<span>Total</span>
                						<span id="final_cost">₹<?php echo $final_cost; ?></span>
                					</p>
            					</div>
            	          	</div>
            	          	<div class="col-md-6">
            	          		<div class="cart-detail bg-light p-3 p-md-4">
            	          			<h3 class="billing-heading mb-4">Payment Method</h3>
    									<div class="form-group">
    										<div class="col-md-12">
    											<!-- <div class="radio"> -->
    											   <!-- <label><input type="radio" name="optradio" class="mr-2">Paytm</label> -->
                                                   <label>Paytm</label>
                                                   <!-- <div class="img" style="background-image:url('images/paytm_icn.png')"></div> -->
    											<!-- </div> -->
    										</div>
    									</div>
    									<p><a id="place_order_btn" onclick="place_order()" class="btn btn-primary py-3 px-4" disabled>Place an order</a></p>
    								</div>
            	          	</div>
            	          </div>
                          <p id="notice_text" class="py-3 px-4"></p>
                      </div> <!-- .col-md-8 -->
                    </div>
                  </div>
                </section> <!-- .section -->
            <?} else {?>
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
            <?}
        }?>

        <footer class="ftco-footer bg-light ftco-section">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-md">
                        <div class="ftco-footer-widget mb-4">
                            <h2 class="ftco-heading-2">RusticMojo</h2>
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
  <!-- <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div> -->


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
    <script src="js/main.js"></script>

    <script>
        function is_form_filled(){
            if (document.getElementById("firstname").value != "" && document.getElementById("lastname").value != "" && document.getElementById("streetaddress").value != "" && document.getElementById("towncity").value != "" && document.getElementById("emailaddress").value != ""){
                if (document.getElementById("postcodezip").value != ""){
                    return true;
                }
            }
            return  false;
        }
        function place_order(){
            if (is_form_filled()){
                a = document.getElementById("final_cost").innerHTML;
                window.location.href = "final_checkout.php";
            } else {
                document.getElementById("notice_text").innerHTML = "* Please fill out the form completely.";
            }
        }
    </script>

    </body>
</html>
