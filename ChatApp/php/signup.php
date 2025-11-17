<?php
session_start();
include_once "config.php";

$fname = mysqli_real_escape_string($conn, $_POST['fname']);
$lname = mysqli_real_escape_string($conn, $_POST['lname']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password)){

    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        $sql = mysqli_query($conn, "SELECT email FROM users WHERE email = '{$email}'");
        if(mysqli_num_rows($sql) > 0){
            echo "$email - This email already exists!";
            exit;
        }

        if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
            $img_type = $_FILES['image']['type'];
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];

            if(in_array($img_type, $allowed_types)){
                $img_data = file_get_contents($_FILES['image']['tmp_name']);
                $img_data = mysqli_real_escape_string($conn, $img_data);

                $random_id = rand(time(), 100000000);
                $enc_pass = md5($password);
                $status = "Active now";

                $insert_query = mysqli_query($conn, 
                    "INSERT INTO users (unique_id, fname, lname, email, password, img, status)
                     VALUES ({$random_id}, '{$fname}', '{$lname}', '{$email}', '{$enc_pass}', '{$img_data}', '{$status}')"
                );

                if($insert_query){
                    $_SESSION['unique_id'] = $random_id;
                    echo "success";
                } else {
                    echo "Something went wrong. Please try again!";
                }
            } else {
                echo "Please upload a valid image file (jpg, jpeg, png)!";
            }
        } else {
            echo "Image file is required!";
        }

    } else {
        echo "$email is not a valid email!";
    }

} else {
    echo "All fields are required!";
}
?>
