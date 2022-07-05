<?php
if (!isset($_POST)) {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die();
}

include_once("dbconnect.php");

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$password = sha1($_POST['password']);
$otp = rand(10000, 99999);
$encoded_string = $_POST['image'];

$sqlinsert = "INSERT INTO tbl_users (user_email,user_name,user_password,user_phone,user_address,otp) VALUES('$email','$name','$password','$phone','$address',$otp)";

if ($conn->query($sqlinsert) === TRUE) {
    $response = array('status' => 'success', 'data' => null);
    $decoded_string = base64_decode($encoded_string);
    $filename = mysqli_insert_id($conn);
    $path = '../images/profile/' . $filename . '.png';
    $is_written = file_put_contents($path, $decoded_string);

    sendMail($email,$otp,$pass);
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

function sendMail($email,$otp,$pass){
    $to = $email;
    $subject = "MyTutor";
    $message = "
    <html>
    <head>
    <title></title>
    </head>
    <body>
    <h3>Thank you for your registration - DO NOT REPLY TO THIS EMAIL</h3>
    <p></p><br><br>
        <a href='https://yuelle.com/qingyun/mobile_mytutor/php/verify.php?email=$email&otp=$otp'>Click here to verify your account</a><br><br>
    </p>
    <table>
    <tr>
    <th>Your Email</th>
    <th>Key/Password</th>
    </tr>
    <tr>
    <td>$email</td>
    <td>$pass</td>
    </tr>
    </table>
    <br>
    <p>TERMS AND CONDITION <br>Single license for the person who made the purchase. The publication and it resources are protected by Copyright law. No part of this publication may be reproduced, 
        shared, distributed, or transmitted in any form or by any means, including, photocopying, recording, or other electronic or mechanical methods with 
        the prior written permission of the author. By downloading this copy you are agreeing to the terms and conditions and can be subjected to law if violated and permanent ban from accessing the resource</p>
    </body>
    </html>
    ";
    
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    
    // More headers
    $headers .= 'From: <qingyun@yuelle.com>' . "\r\n";
    
    mail($to,$subject,$message,$headers);
}
?>
