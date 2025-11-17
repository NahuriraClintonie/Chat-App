<?php
session_start();
include_once "config.php";

if(isset($_GET['user_id'])){
    $user_id = intval($_GET['user_id']);
    $sql = $conn->prepare("SELECT img FROM users WHERE unique_id = ?");
    $sql->bind_param("i", $user_id);
    $sql->execute();
    $sql->bind_result($img);
    $sql->fetch();
    $sql->close();

    if($img){
        header("Content-type: image/jpeg");
        echo $img; // output raw BLOB
    } else {
        // fallback image
        header("Location: php/images/default.png");
    }
}
?>
