<?php
header("Access-Control-Allow-Origin: *");  
header("Content-Type: application/json; charset=UTF-8");

session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = uniqid();
}
$user_id = $_SESSION['user_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "treasure";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["message" => "Database connection failed: " . $conn->connect_error]));
}

// Check if the user has opened the chest before
$sql_check = "SELECT * FROM chest_openings WHERE user_id = '$user_id'";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows == 0) {
    // New opening
    $sql_count = "SELECT COUNT(*) as total FROM chest_openings";
    $result_count = $conn->query($sql_count);
    $row = $result_count->fetch_assoc();
    $opening_rank = $row['total'] + 1;

    if ($opening_rank == 1) {
        $message = "WINNER! 🎉";
    } elseif ($opening_rank == 2) {
        $message = "🎉 First Runner-Up! 🎉";
    } elseif ($opening_rank == 3) {
        $message = "🎉 Second Runner-Up! 🎉";
    } else {
        $message = "🎊 Congratulations! 🎊";
    }

    $message = $conn->real_escape_string($message);
    $sql_insert = "INSERT INTO chest_openings (user_id, opening_rank, message) VALUES ('$user_id', $opening_rank, '$message')";
    if ($conn->query($sql_insert) === TRUE) {
        echo json_encode(["message" => $message]);
    } else {
        die(json_encode(["message" => "Error inserting record: " . $conn->error]));
    }
} else {
    // Already opened
    $row = $result_check->fetch_assoc();
    echo json_encode(["message" => $row['message']]);
}

$conn->close();
?>
