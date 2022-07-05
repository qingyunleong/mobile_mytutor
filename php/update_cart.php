<?php
if (!isset($_POST)) {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die();
}

include_once("dbconnect.php");
$cart_id = addslashes($_POST['cart_id']);
$operation = addslashes($_POST['operation']);

if ($operation =="+"){
    $updatecart = "UPDATE `tbl_carts` SET `cart_qty`= (cart_qty+1) WHERE cart_id = '$cart_id'";    
}

if ($operation =="-"){
    $updatecart = "UPDATE `tbl_carts` SET `cart_qty`= if(cart_qty>1,(cart_qty-1),cart_qty) WHERE cart_id = '$cart_id'";    
}

if ($conn->query($updatecart)){
    $response = array('status' => 'success', 'data' => $updatecart);    
}else{
    $response = array('status' => 'failed', 'data' => $updatecart);
}

sendJsonResponse($response);

function sendJsonResponse($sentArray)
{
    header('Content-Type: application/json');
    echo json_encode($sentArray);
}

?>