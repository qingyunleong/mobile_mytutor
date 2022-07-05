<?php
if (!isset($_POST)) {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die();
}
include_once("dbconnect.php");
$user_email = $_POST['user_email'];

$sqlloadcart = "SELECT tbl_carts.cart_id, tbl_carts.subject_id, tbl_carts.cart_qty, tbl_subjects.subject_name, tbl_subjects.subject_price, tbl_subjects.subject_sessions FROM tbl_carts INNER JOIN tbl_subjects ON tbl_carts.subject_id = tbl_subjects.subject_id WHERE tbl_carts.user_email = '$user_email' AND tbl_carts.cart_status IS NULL";
$result = $conn->query($sqlloadcart);
$number_of_result = $result->num_rows;

if ($result->num_rows > 0) {
    $total_payable = 0;
    $carts["cart"] = array();
    while ($rows = $result->fetch_assoc()) {
        
        $cartList = array();
        $cartList['cart_id'] = $rows['cart_id'];
        $cartList['cart_qty'] = $rows['cart_qty'];
        $cartList['subject_id'] = $rows['subject_id'];
        $cartList['subject_name'] = $rows['subject_name'];
        $cartList['subject_sessions'] = $rows['subject_sessions'];
        $subprice = $rows['subject_price'];
        $cartList['subject_price'] = number_format((float)$subprice, 2, '.', '');
        $price = $rows['cart_qty'] * $subprice;
        $total_payable = $total_payable + $price;
        $cartList['pricetotal'] = number_format((float)$price, 2, '.', ''); 
        array_push($carts["cart"],$cartList);
    }
    $response = array('status' => 'success', 'data' => $carts, 'total' => $total_payable);
    sendJsonResponse($response);
} else {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
}

function sendJsonResponse($sentArray)
{
    header('Content-Type: application/json');
    echo json_encode($sentArray);
}

?>