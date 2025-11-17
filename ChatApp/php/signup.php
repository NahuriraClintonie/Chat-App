<?php
session_start();
include_once "config.php";

// sanitize text inputs
$fname = mysqli_real_escape_string($conn, $_POST['fname']);
$lname = mysqli_real_escape_string($conn, $_POST['lname']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password']; // keep raw for hashing

if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password)) {

    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {

        // check if email already exists
        $sql = mysqli_query($conn, "SELECT email FROM users WHERE email = '{$email}'");
        if(mysqli_num_rows($sql) > 0){
            echo "$email - This email already exists!";
            exit;
        }

        // check if image is uploaded
        if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
            $img_type = $_FILES['image']['type'];

            if(in_array($img_type, $allowed_types)) {

                $img_data = file_get_contents($_FILES['image']['tmp_name']); // raw binary
                $random_id = rand(time(), 100000000);
                $enc_pass = md5($password); // or use password_hash($password, PASSWORD_BCRYPT)
                $status = "Active now";

                // Use prepared statement to store BLOB correctly
                $stmt = $conn->prepare("INSERT INTO users (unique_id, fname, lname, email, password, img, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                if($stmt === false){
                    echo "Database error: " . $conn->error;
                    exit;
                }

                $stmt->bind_param("issssss", $random_id, $fname, $lname, $email, $enc_pass, $img_data, $status);
                $stmt->send_long_data(5, $img_data); // img is the 6th param (0-indexed)
                $execute = $stmt->execute();

                if($execute){
                    $_SESSION['unique_id'] = $random_id;
                    echo "success";
                } else {
                    echo "Something went wrong. Please try again!";
                }

                $stmt->close();

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
