<?php
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
        $sql = "SELECT `price`, `discount` FROM `inventory` WHERE inventory.uid = $item_id LIMIT 1";
        $result = $GLOBALS['conn']->query($sql);
        $row = $result->fetch_assoc();
        $price = $row["price"]*(100-$row["discount"])/100;
        return $price;
    }
    function remove_item_from_cart($user_ip, $item_id){
        $sql = "SELECT `ip`, `cart` FROM `users` WHERE users.ip = '$user_ip' LIMIT 1";
        $result = $GLOBALS['conn']->query($sql);

        if ($result->num_rows > 0){
            $new_cart = "";
            $row = $result->fetch_assoc();
            $cart = $row["cart"];
            $items_in_cart = explode(",", $cart);
            foreach ($items_in_cart as $key => $value) {
                $data = explode(":", $value);
                list($id, $amount) = $data;

                if ($item_id != $id){
                    if ($new_cart == ""){
                        $new_cart = $id . ":" . $amount;
                    }
                    else {
                        $new_cart = $new_cart . "," . $id . ":" . $amount;
                    }
                }
            }
            $sql = "UPDATE `users` SET cart = \"$new_cart\" WHERE users.ip = '$user_ip'";
            $result = $GLOBALS['conn']->query($sql);
        }
    }

    $servername = "localhost";
    $username = "tatiakqf_admin";
    $pw = "Gottobe$&@me";
    $dbname = "tatiakqf_rusticmojo_db";
    $user_ip = $_SERVER['REMOTE_ADDR'];

    $conn = new mysqli($servername, $username, $pw, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $action = "";
    $item = 0;

    if(isset($_GET['item'])){
        $item = $_GET['item'];
        remove_item_from_cart($user_ip, $item);
        update_cart_total($user_ip);
    }

    $conn->close();

    header('Location: ' . $_SERVER["HTTP_REFERER"] );
    exit;
?>
