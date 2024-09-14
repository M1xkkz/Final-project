<?php

include 'config.php';

// Query for ear_left
$sql_left = "SELECT frequency, dB_level FROM ear_left";
$result_left = $conn->query($sql_left);

$ear_left_data = array();
if ($result_left->num_rows > 0) {
    while($row = $result_left->fetch_assoc()) {
        $ear_left_data[] = $row;
    }
}

// Query for ear_right
$sql_right = "SELECT frequency, dB_level FROM ear_right";   
$result_right = $conn->query($sql_right);

$ear_right_data = array();
if ($result_right->num_rows > 0) {
    while($row = $result_right->fetch_assoc()) {
        $ear_right_data[] = $row;
    }
}

// print_r($ear_left_data);

$conn->close();

// Return data as JSON
echo json_encode(array("ear_left" => $ear_left_data, "ear_right" => $ear_right_data));
?>
