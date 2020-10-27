<?php
    function user_exists($ip){
        $sql = "SELECT * FROM `users` WHERE users.ip = '$ip'";
        $result = $GLOBALS['conn']->query($sql);
        if ($result->num_rows > 0){
            return True;
        }
        return False;
    }
    function create_user($ip, $cart, $amount){
        $sql = "INSERT INTO `users`(`ip`, `cart`, `total_cost`) VALUES ('$ip', '$cart:$amount', 0)";
        $result = $GLOBALS['conn']->query($sql);
    }
    function update_cart_total($user_ip){
        $new_cart_total = 0;
        $sql = "SELECT `cart` FROM `users` WHERE users.ip = '$user_ip' LIMIT 1";
        $result = $GLOBALS['conn']->query($sql);
        $cart = $result->fetch_assoc()["cart"];
        if ($cart != ""){
            $items_in_cart = explode(",", $cart);
            foreach ($items_in_cart as $key => $value) {
                list($id, $amount) = explode(":", $value);
                $cost_of_item = get_item_cost($id) * $amount;
                $new_cart_total += $cost_of_item;
            }
        } else {
            $new_cart_total = 0;
        }

        $sql = "UPDATE `users` SET total_cost = \"$new_cart_total\" WHERE users.ip = '$user_ip'";
        $result = $GLOBALS['conn']->query($sql);
    }

    function get_item_cost($item_id){
        $sql = "SELECT `price` FROM `inventory` WHERE inventory.uid = $item_id LIMIT 1";
        $result = $GLOBALS['conn']->query($sql);
        $price = $result->fetch_assoc()["price"];
        return $price;
    }

    function contains_item($user_ip, $item_id){
        $sql = "SELECT `cart` FROM `users` WHERE users.ip = '$user_ip' LIMIT 1";
        $result = $GLOBALS['conn']->query($sql);
        $cart = $result->fetch_assoc()["cart"];
        if ($cart == ""){
            return False;
        }
        $items_in_cart = explode(",", $cart);
        foreach ($items_in_cart as $key => $value) {
            $data = explode(":", $value);
            list($id, $amount) = $data;
            if ($id == $item_id){
                return True;
            }
        }
        return False;
    }
    function set_item_amount($user_ip, $to_set_id, $set_amount){
        $sql = "SELECT `cart` FROM `users` WHERE users.ip = '$user_ip' LIMIT 1";
        $result = $GLOBALS['conn']->query($sql);

        $in_cart = contains_item($user_ip, $to_set_id);
        $new_cart = "";

        if ($result->num_rows > 0){
            if ($in_cart){
                $row = $result->fetch_assoc();
                $cart = $row["cart"];
                $items_in_cart = explode(",", $cart);
                foreach ($items_in_cart as $key => $value) {
                    $data = explode(":", $value);
                    list($id, $amount) = $data;

                    if ($id == $to_set_id){
                        $amount = $set_amount;
                    }
                    if ($new_cart == ""){
                        $new_cart = $id . ":" . $amount;
                    }
                    else {
                        $new_cart = $new_cart . "," . $id . ":" . $amount;
                    }
                }
            } else {
                $row = $result->fetch_assoc();
                $cart = $row["cart"];
                if ($cart == ""){
                    $new_cart = "$to_set_id:$set_amount";
                } else {
                    $new_cart = "$cart,$to_set_id:$set_amount";
                }
            }
            $sql = "UPDATE `users` SET cart = \"$new_cart\" WHERE users.ip = '$user_ip'";
            $result = $GLOBALS['conn']->query($sql);
        }
    }

    $servername = "localhost";
    $username = "root";
    $pw = "";
    $dbname = "rusticmojo_db";
    $user_ip = $_SERVER['REMOTE_ADDR'];

    $conn = new mysqli($servername, $username, $pw, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $item = 0;
    $set_amount = 0;

    if(isset($_GET['item']) && isset($_GET['amount'])) {
        $item = $_GET['item'];
        $set_amount = $_GET['amount'];
        if (user_exists($user_ip)){
            set_item_amount($user_ip, $item, $set_amount);
            update_cart_total($user_ip);
        } else {
            create_user($user_ip, $item, 1);
        }
    }

    $conn->close();

    header("location:javascript://history.go(-1)");
    exit;
?>
