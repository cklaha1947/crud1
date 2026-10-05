<?php 
include "db.php";//$conn
if (!$conn) {
    die("DB Error: " . mysqli_connect_error());
}

//collect form data
$id = $_POST['id'];
$name = $_POST['name'];
$gender = $_POST['gender'];
$stream = $_POST['stream'];
$subject = isset($_POST['sub']) ? implode(",",$_POST['sub']) : "" ;


// Check if new image uploaded
$filename = $_FILES['simg']['name'];
if (!empty($filename)) {
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $valid_ext = ["jpg", "jpeg", "png", "gif"];

    if (in_array($extension, $valid_ext)) {
        $new_name = time() . "." . $extension;
        $path = "upload/" . $new_name;

        // Delete old image
        $imgQuery = mysqli_query($conn, "SELECT stdimg FROM students WHERE id = {$id}");
        if ($imgQuery && mysqli_num_rows($imgQuery) > 0) {
            $oldImg = mysqli_fetch_assoc($imgQuery)['stdimg'];
            if (!empty($oldImg) && file_exists("upload/$oldImg")) {
                unlink("upload/$oldImg");
            }
        }

        move_uploaded_file($_FILES['simg']['tmp_name'], $path);
    }
} else {
    // Keep old image
    $getOld = mysqli_query($conn, "SELECT stdimg FROM students WHERE id = {$id}");
    $new_name = mysqli_fetch_assoc($getOld)['stdimg'];
}

//update query 
$sql = "UPDATE students SET name='{$name}', gender='{$gender}', stream='{$stream}', subject='{$subject}', stdimg='{$new_name}' WHERE id={$id}";

if (mysqli_query($conn, $sql)) {
    echo 1;
} else {
    echo "DB Error: " . mysqli_error($conn);
}



?>