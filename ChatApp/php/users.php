<?php
session_start();
include_once "config.php";

// redirect if not logged in
if(!isset($_SESSION['unique_id'])){
    header("Location: login.php");
    exit;
}

$outgoing_id = $_SESSION['unique_id'];
$sql = "SELECT * FROM users WHERE NOT unique_id = {$outgoing_id} ORDER BY user_id DESC";
$query = mysqli_query($conn, $sql);
$output = "";

if($query && mysqli_num_rows($query) > 0){
    include_once "data.php";
} else {
    $output .= "No users are available to chat";
}

echo $output;
?>
