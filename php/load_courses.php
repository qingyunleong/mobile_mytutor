<?php
if (!isset($_POST)) {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    die();
}

include_once("dbconnect.php");

$results_per_page = 10;
$pageno = (int)$_POST['pageno'];
$page_first_result = ($pageno - 1) * $results_per_page;

$search = $_POST['search'];
$sqlsubjects = "SELECT * FROM tbl_subjects INNER JOIN tbl_tutors ON tbl_subjects.tutor_id = tbl_tutors.tutor_id WHERE subject_name LIKE '%$search%' ORDER BY subject_id DESC";

$result = $conn->query($sqlsubjects);

$number_of_result = $result->num_rows;
$number_of_page = ceil($number_of_result / $results_per_page);
$sqlsubjects = $sqlsubjects . " LIMIT $page_first_result , $results_per_page";

$result = $conn->query($sqlsubjects);

if ($result->num_rows > 0) {
    $subjects["subjects"] = array();
    while ($row = $result->fetch_assoc()) {
        $coursesList = array();
        $coursesList['subject_id'] = $row['subject_id'];
        $coursesList['subject_name'] = $row['subject_name'];
        $coursesList['subject_description'] = $row['subject_description'];
        $coursesList['subject_price'] = $row['subject_price'];
        $coursesList['subject_sessions'] = $row['subject_sessions'];
        $coursesList['subject_rating'] = $row['subject_rating'];

        $coursesList['tutor_id'] = $row['tutor_id'];
        $coursesList['tutor_email'] = $row['tutor_email'];
        $coursesList['tutor_phone'] = $row['tutor_phone'];
        $coursesList['tutor_name'] = $row['tutor_name'];
        $coursesList['tutor_description'] = $row['tutor_description'];

        array_push($subjects["subjects"], $coursesList);
    }
    $response = array(
        'status' => 'success', 'pageno' => "$pageno", 'numofpage' => "$number_of_page", 'data' => $subjects
    );
    sendJsonResponse($response);
} else {
    $response = array('status' => 'failed', 'pageno' => "$pageno", 'numofpage' => "$number_of_page", 'data' => null);
    sendJsonResponse($response);
}

function sendJsonResponse($sentArray)
{
    header('Content-Type: application/json');
    echo json_encode($sentArray);
}
