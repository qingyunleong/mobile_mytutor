<?php
$servername = "localhost";
$username = "yuelleco_qingyun";
$password = "Qingyun411";
$dbname = "yuelleco_mytutor_db";

$conn = new mysqli($servername, $username, $password, $dbname); if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
?>
