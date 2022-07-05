<?php
if (!isset($_POST)) {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die();
}

include_once("dbconnect.php");
$email = $_POST['email'];

if (isset($_POST['image'])){
    $base64image = $_POST['image'];
    $decoded_string = base64_decode($base64image);
    $path = '../../assets/profile/' . $email . '.jpg';
    $is_written = file_put_contents($path, $decoded_string);
    if ($is_written){
        $response = array('status' => 'success', 'data' => null);
    }else{
        $response = array('status' => 'failed', 'data' => null);
    }
    sendJsonResponse($response);
}

if (isset($_POST['newname'])){
    $newname = $_POST['newname'];
    $sqlupdatename = "UPDATE `tbl_users` SET `user_name`='$newname' WHERE user_email = '$email'";
    if ($conn->query($sqlupdatename) === TRUE) {
        $response = array('status' => 'success', 'data' => null);
    }else{
        $response = array('status' => 'failed', 'data' => null);    
    }
    sendJsonResponse($response);
}

if (isset($_POST['newphone'])){
    $newphone = $_POST['newphone'];
    $sqlupdatephone = "UPDATE `tbl_users` SET `user_phone`='$newphone' WHERE user_email = '$email'";
    if ($conn->query($sqlupdatephone) === TRUE) {
        $response = array('status' => 'success', 'data' => null);
    }else{
        $response = array('status' => 'failed', 'data' => null);    
    }
    sendJsonResponse($response);
}


function sendJsonResponse($sentArray)
{
    header('Content-Type: application/json');
    echo json_encode($sentArray);
}
?>