<?php
error_reporting(0);
include_once("dbconnect.php");
$email = $_GET['email'];
$mobile = $_GET['mobile'];
$amount = $_GET['amount'];
$name = $_GET['name'];

$data = array(
    'id' =>  $_GET['billplz']['id'],
    'paid_at' => $_GET['billplz']['paid_at'] ,
    'paid' => $_GET['billplz']['paid'],
    'x_signature' => $_GET['billplz']['x_signature']
);

$paidstatus = $_GET['billplz']['paid'];
if ($paidstatus=="true"){
    $paidstatus = "Success";
    $status = "Paid";
}else{
    $paidstatus = "Failed";
    $status = "Failed";
}
$receiptid = $_GET['billplz']['id'];
$signing = '';
foreach ($data as $key => $value) {
    $signing.= 'billplz'.$key . $value;
    if ($key === 'paid') {
        break;
    } else {
        $signing .= '|';
    }
}
 
$signed= hash_hmac('sha256', $signing, 'S-LWgf_Yn4JftgXRFblO1C9g');
if ($signed === $data['x_signature']) {
    
    if ($paidstatus == "Success"){ 
        $sqpupdatecart = "UPDATE `tbl_carts` SET `cart_status`='paid',`receipt_id`='$receiptid' WHERE user_email='$email' AND cart_status IS NULL";
        $conn->query($sqpupdatecart);
        
        if ($result->num_rows > 0){
             while ($rows = $result->fetch_assoc()) {
            $sqlselectcart="SELECT * FROM tbl_carts WHERE receipt_id = '$receiptid'";
             $conn->query($updateproductsqty);
                }
            }
            $message = "Payment completed. Return back to the application by pressing the back button on the app task bar.";
            $amount = number_format((float)$amount, 2, '.', '');
            printTable($receiptid,$name,$email,$mobile,$amount,$paidstatus,$message);   
        
    }else{
        $message = "Payment incompleted. Return back to the application by pressing the back button on the app task bar and perform the payment again.";
        printTable('Failed',$name,$email,$mobile,$amount,$paidstatus,$message);
    }
}

function printTable($receiptid,$name,$email,$mobile,$amount,$paidstatus,$message){
   echo "
        <html>
    <head>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' href='https://www.w3schools.com/w3css/4/w3.css'>
    </head>
    <div class='w3-padding'>
        <h4>Thank you for your payment</h4>
        <p>The following is your receipt</p>
    </div>

    <div class='w3-container w3-padding w3-margin w3-card'>
        <h2><b>Paid By</b></h2>
        <p>Dear $name <br>$email <br>$mobile</p><hr>

        <table class='w3-table'>
            <tr><th>Receipt ID</th>
            <td>$receiptid</td></tr>

            <tr><th>Payment Status</th>
            <td>$paidstatus</td></tr>
        </table><hr>

        <div>
            <p style='text-align:right'>Amount: $amount</p>
        </div><hr>

        <div>
            <p style='font-size:70%'>Thank you for purchase.<br>You can check your receipt on you email.</p>
        </div>
    </div>

    <div class='w3-container w3-padding w3-margin w3-round w3-red'>$message</div></div>
</body></html>";
}
