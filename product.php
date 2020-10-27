<?php
if(isset($_GET['item'])){
    $servername = "localhost";
    $username = "root";
    $pw = "";
    $dbname = "rusticmojo_db";
    $user_ip = $_SERVER['REMOTE_ADDR'];

    $conn = new mysqli($servername, $username, $pw, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $item_id = $_GET['item'];

    $sql = "SELECT `uid`, `name`, `quantity`, `price`, `discount`, `image`, `trending`, `rating`, `description` FROM `inventory` WHERE inventory.uid = $item_id LIMIT 1";
    $result = $conn->query($sql)->fetch_assoc();
    $name = $result["name"];
    $image = $result["image"];
    $price = $result["price"];
    $discount = $result["discount"];
    $description = $result["description"];

    $conn->close();
}
else {
    header("Location: ./error.php");
    die();
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
            function add_item_to_cart(item_id){
                q = parseInt(document.getElementById("quantity").value)
                var xmlhttp = new XMLHttpRequest();
                var link = "actions/set_item_amount.php?item=" + item_id + "&amount=" + q;
                xmlhttp.open("GET", link, true);
                xmlhttp.send();
                document.getElementById("add_to_cart_btn").innerHTML = "Added to Cart \u2713";
                document.getElementById("add_to_cart_btn").style.background = "LightGreen";
                document.getElementById("add_to_cart_btn").style.border = "LightGreen";
                document.getElementById("add_to_cart_btn").onclick = "#";
                var elem = document.getElementById('add_remove_btns');
                elem.parentNode.removeChild(elem);
            }
            function add_one(){
                q = parseInt(document.getElementById("quantity").value) + 1;
                document.getElementById("quantity").value = q;
            }
            function remove_one(item_id){
                q = parseInt(document.getElementById("quantity").value) - 1;
                document.getElementById("quantity").value = q;
            }
        </script>
        <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light ftco-navbar-light-2" id="ftco-navbar">
            <div class="container">
                <a class="navbar-brand" href="index.php">Modist</a>
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
                        <li class="nav-item cta cta-colored"><a href="cart.php" class="nav-link"><span class="icon-shopping_cart"></span>[0]</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    <!-- END nav -->

    <div class="hero-wrap hero-bread" style="background-image: url('images/bg_6.jpg');">
        <div class="container">
            <div class="row no-gutters slider-text align-items-center justify-content-center">
                <div class="col-md-9 ftco-animate text-center">
                    <h1 class="mb-0 bread"><?php echo $name; ?></h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span class="mr-2"><a href="index.php">Shop</a></span> <span><?php echo $name; ?></span></p>
                </div>
            </div>
        </div>
    </div>

    <section class="ftco-section bg-light">
        <div class="container">
    		<div class="row">
    			<div class="col-lg-6 mb-5 ftco-animate">
    				<a href="<?php echo $image; ?>" class="image-popup"><img src="<?php echo $image; ?>" class="img-fluid" alt="Colorlib Template"></a>
    			</div>
    			<div class="col-lg-6 product-details pl-md-5 ftco-animate">
    				<h3><?php echo $name; ?></h3>
    				<p class="price"><span>₹<?php echo $price; ?></span></p>
    				<p><?php echo $description; ?></p>
                    <div class="row mt-4">
                        <div id="add_remove_btns" class="input-group col-md-6 d-flex mb-3">
                            <span class="input-group-btn mr-2">
                                <button type="button" onclick = "remove_one()" class="quantity-left-minus btn"  data-type="minus" data-field="">
                                    <i class="ion-ios-remove"></i>
                                </button>
                            </span>
                            <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="100">
                            <span class="input-group-btn ml-2">
                                <button type="button" onclick = "add_one()" class="quantity-right-plus btn" data-type="plus" data-field="">
                                    <i class="ion-ios-add"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <p><a id="add_to_cart_btn" onclick="add_item_to_cart(<?php echo $item_id ?>)" class="btn btn-primary py-3 px-5">Add to Cart</a></p>
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
  <script src="js/main.js"></script>

  <script>
		₹(document).ready(function(){

		var quantitiy=0;
		   ₹('.quantity-right-plus').click(function(e){

		        // Stop acting like a button
		        // e.preventDefault();
		        // Get the field name
		        var quantity = parseInt(₹('#quantity').val());

		        // If is not undefined

		            ₹('#quantity').val(quantity + 1);


		            // Increment

		    });

		     ₹('.quantity-left-minus').click(function(e){
		        // Stop acting like a button
		        e.preventDefault();
		        // Get the field name
		        var quantity = parseInt(₹('#quantity').val());

		        // If is not undefined

		            // Increment
		            if(quantity>0){
		            ₹('#quantity').val(quantity - 1);
		            }
		    });

		});
	</script>

  </body>
</html>
