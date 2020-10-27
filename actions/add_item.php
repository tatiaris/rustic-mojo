<?php
    $servername = "localhost";
    $username = "root";
    $pw = "";
    $dbname = "rusticmojo_db";

    $conn = new mysqli($servername, $username, $pw, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
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

    function get_ip(){
        return $_SERVER['REMOTE_ADDR'];
    }

    function create_user($ip, $cart, $amount){
        $sql = "INSERT INTO `users`(`ip`, `cart`) VALUES ('$ip', '$cart:$amount')";
        $result = $GLOBALS['conn']->query($sql);
    }

    function add_item_to_cart($item_id, $amount){
        $user_ip = strval(get_ip());
        $sql = "SELECT `ip`, `cart` FROM `users` WHERE users.ip = '$user_ip' LIMIT 1";
        $result = $GLOBALS['conn']->query($sql);

        if ($result->num_rows > 0){
            $new_cart = "";
            $item_found = False;
            $row = $result->fetch_assoc();
            $cart = $row["cart"];
            if ($cart == ""){
                $new_cart = $item_id . ":1";
            }
            else {
                $items_in_cart = explode(",", $cart);
                foreach ($items_in_cart as $key => $value) {
                    $data = explode(":", $value);
                    list($id, $amount) = $data;

                    if ($item_id == $id){
                        $item_found = True;
                        $amount = $amount + 1;
                    }
                    if ($new_cart == ""){
                        $new_cart = "$id:$amount";
                    }
                    else {
                        $new_cart = "$new_cart,$id:$amount";
                    }
                }
                if (!$item_found){
                    $new_cart = "$new_cart,$item_id:$amount";
                }
            }
            $sql = "UPDATE `users` SET cart = \"$new_cart\" WHERE users.ip = '$user_ip'";
            $result = $GLOBALS['conn']->query($sql);
        }
        else {
            create_user(get_ip(), strval($item_id), $amount);
        }
    }
    $action = "";
    $item = 0;

    if(isset($_GET['item']) && isset($_GET['amount'])){
        $item = $_GET['item'];
        $amount = $_GET['amount'];
        add_item_to_cart($item, $amount);
        update_cart_total($user_ip);
    }

    $conn->close();
    header("location:javascript://history.go(-1)");
    exit;
?>
