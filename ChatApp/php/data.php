<?php
while($row = mysqli_fetch_assoc($query)){
    $sql2 = "SELECT * FROM messages 
             WHERE (incoming_msg_id = {$row['unique_id']} OR outgoing_msg_id = {$row['unique_id']}) 
             AND (outgoing_msg_id = {$outgoing_id} OR incoming_msg_id = {$outgoing_id}) 
             ORDER BY msg_id DESC LIMIT 1";
    $query2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($query2);

    $result = (mysqli_num_rows($query2) > 0) ? $row2['msg'] : "No message available";
    $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;

    $you = (isset($row2['outgoing_msg_id']) && $outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "";
    $offline = ($row['status'] == "Offline now") ? "offline" : "";
    $hid_me = ($outgoing_id == $row['unique_id']) ? "hide" : "";

    if(!empty($row['img'])){
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($row['img']);
        $img_src = "data:{$mime};base64," . base64_encode($row['img']);
    } else {
        $img_src = "images/default.png";
    }

    $output .= '<a href="chat.php?user_id='. $row['unique_id'] .'">
                <div class="content">
                    <img src="'. $img_src .'" alt="">
                    <div class="details">
                        <span>'. htmlspecialchars($row['fname']. " " . $row['lname']) .'</span>
                        <p>'. htmlspecialchars($you . $msg) .'</p>
                    </div>
                </div>
                <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                </a>';
}
?>
