<?php
    function user_exists($ip){
        $sql = "SELECT * FROM `users` WHERE users.ip = '$ip'";
        $result = $GLOBALS['conn']->query($sql);
        if ($result->num_rows > 0){
            return True;
        }
        return False;
    }
    function get_cart_size($user_ip){
        if (!user_exists($user_ip)) return 0;
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
    $username = "tatiakqf_admin";
    $pw = "Gottobe$&@me";
    $dbname = "tatiakqf_rusticmojo_db";
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
      <script>
          function add_to_cart(item_id) {
              document.getElementById("product" + item_id.toString()).style.background = "LightGreen";
              document.getElementById("add_cart_link_item_" + item_id.toString()).style.color = "inherit";
              document.getElementById("add_cart_link_item_" + item_id.toString()).text = "Added to cart \u2713";

              var a = document.getElementById("cart_symbol").innerHTML;
              var updated_cart_amount = parseInt(a.substr(1, a.length - 2)) + 1;
              document.getElementById("cart_symbol").innerHTML = "[" + updated_cart_amount + "]";
              document.getElementById("cart_symbol").class = "icon-shopping_cart";

              var xmlhttp = new XMLHttpRequest();
              var link = "actions/set_item_amount.php/?item=" + item_id + "&amount=1";
              xmlhttp.open("GET", link, true);
              xmlhttp.send();
          }
      </script>
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
	      <a class="navbar-brand" href="index.php">rusticmojo</a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="oi oi-menu"></span> Menu
	      </button>

	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	          <li class="nav-item active"><a href="index.php" class="nav-link">Home</a></li>
	          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Shop</a>
              <div class="dropdown-menu" aria-labelledby="dropdown04">
              	<a class="dropdown-item" href="shop.php">Shop</a>
                <a class="dropdown-item" href="cart.php">Cart</a>
                <a class="dropdown-item" href="checkout.php">Checkout</a>
              </div>
            </li>
	          <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
	          <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
	          <li class="nav-item cta cta-colored">
                  <a id="cart_symbol_amount" href="cart.php" class="nav-link">
                      <span id="cart_symbol" class="icon-shopping_cart">[<?php echo $cart_size; ?>]</span>
                  </a>
              </li>
	        </ul>
	      </div>
	    </div>
	  </nav>
    <!-- END nav -->

	<div class="hero-wrap js-fullheight" style="background-color:pink;">
      <div class="overlay" style="background-color:powderblue;"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center">
        	<h3 class="v">Mojo - Your new purse</h3>
        	<h3 class="vr">Since - 2019</h3>
          <div class="col-md-11 ftco-animate text-center">
            <h1>RusticMojo</h1>
            <h2><span>Wear Your Accessories</span></h2>
          </div>
          <div class="mouse">
				<a href="#" class="mouse-icon">
					<div class="mouse-wheel"><span class="ion-ios-arrow-down"></span></div>
				</a>
			</div>
        </div>
      </div>
    </div>

    <div class="goto-here"></div>

    <section class="ftco-section ftco-product">
    	<div class="container">
    		<div class="row justify-content-center mb-3 pb-3">
          <div class="col-md-12 heading-section text-center ftco-animate">
          	<h1 class="big">Trending</h1>
            <h2 class="mb-4">Trending</h2>
          </div>
        </div>
    		<div class="row">
    			<div class="col-md-12">
    				<div class="product-slider owl-carousel ftco-animate">
                        <?
                        $sql = "SELECT `uid`, `name`, `quantity`, `price`, `discount`, `image`, `trending`, `rating` FROM `inventory` WHERE inventory.trending = 1";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                ?>

                                <div class="item">
                                    <div class="product">
                                        <a href="product.php?item=<?php echo $row["uid"]; ?>" class="img-prod"><img class="img-fluid\" src="<?php echo $row["image"]; ?>" alt="Colorlib Template">
                                            <?if ($row["discount"] > 0){?>
                                                <span class="status">- <?php echo $row["discount"]; ?>%</span>
                                            <?}?>
                                        </a>
                                        <div class="text pt-3 px-3">
                                            <?if ($row["quantity"] < 1){?>
                                                <h3><a href="product.php?item=<?php echo $row["uid"]; ?>"><?php echo $row["name"] ?> (OUT OF STOCK)</a></h3>
                                            <?} else {?>
                                                <h3><a href="product.php?item=<?php echo $row["uid"]; ?>"><?php echo $row["name"]; ?></a></h3>
                                            <?}?>
                                            <div class="d-flex">
                                                <div class="pricing">
                                                    <?if ($row["discount"] > 0){?>
                                                        <p class="price"><span class="mr-2 price-dc">₹<?php echo $row["price"]; ?></span><span class="mr-2 price">₹ <?php echo $row["price"]*(100-$row["discount"])/100; ?></span></p>
                                                    <?}else {?>
                                                        <p class="price"><span class="mr-2 price">₹<?php echo $row["price"]; ?></span></p>
                                                    <?}?>
                                                </div>
                                                <div class="rating">
                                                    <p class="text-right">
                                                        <?for ($x = 0; $x < $row["rating"]; $x++){?>
                                                            <span class="ion-ios-star"></span>
                                                        <?}?>
                                                        <?for ($x = 0; $x < 5 - $row["rating"]; $x++){?>
                                                            <span class="ion-ios-star-outline"></span>
                                                        <?}?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?
                            }
                        }
                        else {
                            echo "0 results";
                        }
                        ?>

    				</div>
    			</div>
    		</div>
    	</div>
    </section>

    <section class="ftco-section ftco-no-pb ftco-no-pt bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-5 p-md-5 img img-2 d-flex justify-content-center align-items-center" style="background-color:powderblue;">
						<!-- <a href="https://vimeo.com/45830194" class="icon popup-vimeo d-flex justify-content-center align-items-center">
							<span class="icon-play"></span>
						</a> -->
                </div>
                <div class="col-md-7 py-5 wrap-about pb-md-5 ftco-animate">
                    <div class="heading-section-bold mb-5 mt-md-5">
                        <div class="ml-md-0">
                            <h2 class="mb-4">RusticMojo<br> <span>Accessories Shop</span></h2>
                        </div>
                    </div>
                    <div class="pb-md-5">
                        <p>Started in the peaceful town of Jodhpur, RusticMojo presents you with the highest quality hand-made women's accessories.</p>
                        <p>Carefully crafted, showcasing every minute detail, these accessories are all one of their kind, available only for a short time!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="products" class="ftco-section bg-light">
    	<div class="container">
            <div class="row justify-content-center mb-3 pb-3">
          <div class="col-md-12 heading-section text-center ftco-animate">
          	<h1 class="big">Products</h1>
            <h2 class="mb-4">Our Products</h2>
          </div>
        </div>
    	</div>
    	<div class="container-fluid">
    		<div class="row">
                <?
                    $sql = "SELECT `uid`, `name`, `quantity`, `price`, `discount`, `image`, `trending`, `rating` FROM `inventory` WHERE inventory.uid < 5";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {

                            $item_in_cart = False;

                            $sql = "SELECT `cart` FROM `users` WHERE users.ip = '$user_ip'";
                            $cart_exists = $conn->query($sql);

                            if ($cart_exists){
                                $cart_string = $cart_exists->fetch_assoc()["cart"];
                                if ($cart_string != ""){
                                    $item_list = explode(",", $cart_string);
                                    foreach ($item_list as $key => $value) {
                                        $data = explode(":", $value);
                                        list($item_id, $amount) = $data;
                                        if ($item_id == $row["uid"]){
                                            $item_in_cart = True;
                                        }
                                    }
                                }
                            }
                            $item_id = $row["uid"];
                ?>
                            <div class="col-sm col-md-6 col-lg ftco-animate">
                            <div class="product">
                                <a href="product.php?item=<?php echo $row["uid"]; ?>" class="img-prod"><img class="img-fluid" src="<?php echo $row["image"] ?>" alt="image unavailable">
                                <?
                                if ($item_in_cart)
                                    echo "<div id= product$item_id style=\"background-color:#90ee90;\" class=\"text py-3 px-3\">";
                                else
                                    echo "<div id= product$item_id class=\"text py-3 px-3\">";

                                    if ($row["quantity"] < 1){?>
                                        <h3><a href=\"product.php?item=<?php echo $row["uid"]; ?>\"> <?php echo $row["name"];?> (OUT OF STOCK)</a></h3>
                                    <?}
                                    else {?>
                                        <h3><a href="product.php?item=<?php echo $row["uid"]; ?>"> <?php echo $row["name"]; ?> </a></h3>
                                    <?}
                                ?>
                                    <div class="d-flex">
                                        <div class="pricing">
                                        <?
                                        if ($row["discount"] > 0){
                                            echo "<p class=\"price\"><span class=\"mr-2 price-dc\">₹" . $row["price"] . "</span><span class=\"mr-2 price\">₹" . $row["price"]*(100-$row["discount"])/100 . "</span></p>";
                                        }
                                        else {
                                            echo "<p class=\"price\"><span class=\"mr-2 price\">₹" . $row["price"] . "</span></p>";
                                        }
                                        ?>
                                        </div>
                                        <div class="rating">
                                            <p class="text-right">
                                            <?
                                                for ($x = 0; $x < $row["rating"]; $x++) {
                                                    echo "<span class=\"ion-ios-star\"></span>";
                                                }
                                                for ($x = 0; $x < 5 - $row["rating"]; $x++){
                                                    echo "<span class=\"ion-ios-star-outline\"></span>";
                                                }
                                            ?>
                                            </p>
                                        </div>
                                    </div>
                                <hr>
                                <?
                                    if ($item_in_cart){
                                        echo "<a class=\"add-to-cart\"><span>Added to cart &#10003;</i></span></a>";
                                    }
                                    else {
                                        echo "<a id=\"add_cart_link_item_$item_id\" onclick=\"add_to_cart($item_id)\" class=\"add-to-cart\"><span>Add to cart <i class=\"ion-ios-add ml-1\"></i></span></a>";
                                    }
                                ?>
                                </div>
                            </div>
                        </div>
                <?
                        }
                    }
                ?>
    		</div>
    	</div>
    </section>

    <!-- SALE BANNER -->
    <!-- <section class="ftco-section ftco-section-more img" style="background-color:#ffa500;">
    	<div class="container">
    		<div class="row justify-content-center mb-3 pb-3">
          <div class="col-md-12 heading-section ftco-animate">
          	<h2>Diwali Sale</h2>
          </div>
        </div>
    	</div>
    </section> -->

    <section class="ftco-section testimony-section bg-light">
      <div class="container">
				<div class="row justify-content-center mb-3 pb-3">
          <div class="col-md-12 heading-section text-center ftco-animate">
          	<h1 class="big">Testimony</h1>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-8 ftco-animate">
          	<div class="row ftco-animate">
		          <div class="col-md-12">
		            <div class="carousel-testimony owl-carousel ftco-owl">

                    <?
                        $sql = "SELECT `name`, `review`, `type`, `image` FROM `reviews`";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
        		                echo "<div class=\"item\">";
        	                       echo "<div class=\"testimony-wrap py-4 pb-5\">";
        		                      echo "<div class=\"user-img mb-4\" style=\"background-image: url(" . $row["image"] . ")\">";
        	                            echo "<span class=\"quote d-flex align-items-center justify-content-center\">";
        		                      echo "<i class=\"icon-quote-left\"></i>";
        		                    echo "</span>";
        		                  echo "</div>";
        		                  echo "<div class=\"text text-center\">";
        		                    echo "<p class=\"mb-4\">" . $row["review"] . "</p>";
        		                    echo "<p class=\"name\">" . $row["name"] . "</p>";
        		                    echo "<span class=\"position\">" .$row["type"] . "</span>";
        		                  echo "</div>";
        		                echo "</div>";
        		              echo "</div>";
                          }
                      }
                      $conn->close();
                    ?>
		            </div>
		          </div>
		        </div>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section ftco-counter img" id="section-counter" style="background-image: url(images/bg_4.jpg);">
    	<div class="container">
    		<div class="row justify-content-center py-5">
    			<div class="col-md-10">
		    		<div class="row">
		          <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
		            <div class="block-18 text-center">
		              <div class="text">
		                <strong class="number" data-number="10"><span>+</span></strong>
		                <span>Happy Customers</span>
		              </div>
		            </div>
		          </div>
		          <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
		            <div class="block-18 text-center">
		              <div class="text">
		                <strong class="number" data-number="2">1</strong>
		                <span>Branches</span>
		              </div>
		            </div>
		          </div>
		          <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
		            <div class="block-18 text-center">
		              <div class="text">
		                <strong class="number" data-number="3">1</strong>
		                <span>Partner</span>
		              </div>
		            </div>
		          </div>
		          <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
		            <div class="block-18 text-center">
		              <div class="text">
		                <strong class="number" data-number="4">2</strong>
		                <span>Awards</span>
		              </div>
		            </div>
		          </div>
		        </div>
	        </div>
        </div>
    	</div>
    </section>

    <section class="ftco-section bg-light ftco-services">
    	<div class="container">
    		<div class="row justify-content-center mb-3 pb-3">
          <div class="col-md-12 heading-section text-center ftco-animate">
            <h1 class="big">Services</h1>
            <h2>We want you to express yourself</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 text-center d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services">
              <div class="icon d-flex justify-content-center align-items-center mb-4">
            		<span class="flaticon-002-recommended"></span>
              </div>
              <div class="media-body">
                <h3 class="heading">Refund Policy</h3>
                <!-- <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p> -->
              </div>
            </div>
          </div>
          <div class="col-md-4 text-center d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services">
              <div class="icon d-flex justify-content-center align-items-center mb-4">
            		<span class="flaticon-001-box"></span>
              </div>
              <div class="media-body">
                <h3 class="heading">Premium Packaging</h3>
                <!-- <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p> -->
              </div>
            </div>
          </div>
          <div class="col-md-4 text-center d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services">
              <div class="icon d-flex justify-content-center align-items-center mb-4">
            		<span class="flaticon-003-medal"></span>
              </div>
              <div class="media-body">
                <h3 class="heading">Superior Quality</h3>
                <!-- <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p> -->
              </div>
            </div>
          </div>
        </div>
    	</div>
    </section>

    <footer class="ftco-footer bg-light ftco-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Modist</h2>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
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

  </body>
</html>
