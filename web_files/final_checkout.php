<?php
    $servername = "localhost";
    $username = "tatiakqf_admin";
    $pw = "Gottobe$&@me";
    $dbname = "tatiakqf_rusticmojo_db";
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $conn = new mysqli($servername, $username, $pw, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT `total_cost`, `cart` FROM `users` WHERE users.ip = '$user_ip' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0){
        $row = $result->fetch_assoc();
        $subtotal = $row["total_cost"];
        $delivery_fee = 0;
        if ($subtotal != 0) $delivery_fee = 50;
        $amount = $subtotal + $delivery_fee;
?>

<html>
<head>
<title>Merchant Check Out Page</title>
<meta name="GENERATOR" content="Evrsoft First Page">
</head>
<body>
	<h1>Please wait...</h1>
    <form style="display:none" method="post" action="paytm/pgRedirect.php">
        <table border="1">
            <tbody>
                <tr>
                    <td>1</td>
                    <td><label>ORDER_ID::*</label></td>
                    <td><input id="ORDER_ID" type="hidden" tabindex="1" maxlength="20" size="20"
                        name="ORDER_ID" autocomplete="off"
                        value="<?php echo "ORDS" . rand(10000,99999999)?>">
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td><label>CUSTID ::*</label></td>
                    <td><input id="CUST_ID" type="hidden" tabindex="2" maxlength="12" size="12" name="CUST_ID" autocomplete="off" value="<?php echo "sess" . rand(10000, 999999999); ?>"></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td><label>INDUSTRY_TYPE_ID ::*</label></td>
                    <td><input id="INDUSTRY_TYPE_ID" type="hidden" tabindex="4" maxlength="12" size="12" name="INDUSTRY_TYPE_ID" autocomplete="off" value="Retail"></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td><label>Channel ::*</label></td>
                    <td><input id="CHANNEL_ID" tabindex="4" maxlength="12"
                        size="12" name="CHANNEL_ID" type="hidden" autocomplete="off" value="WEB">
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td><label>txnAmount*</label></td>
                    <td><input title="TXN_AMOUNT" tabindex="10"
                        type="hidden" name="TXN_AMOUNT"
                        value="<?php echo $amount; ?>">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><input id="submit_btn" value="CheckOut" type="submit" onclick=""></td>
                </tr>
            </tbody>
        </table>
    </form>
</body>
<script>
    document.getElementById('submit_btn').click();
</script>
</html>
<?}?>
