<?php 
include "db.php";

//collect form data
$name = $_POST['name'];
$gender = $_POST['gender'];
$stream = $_POST['stream'];
$subject = isset($_POST['sub']) ? implode(",",$_POST['sub']) : "" ;


//image upload
$filename = $_FILES['simg']['name'];
$extension = pathinfo($filename, PATHINFO_EXTENSION);
$valid_extension = array("jpg","jpeg","png","gif");
if ( in_array($extension,$valid_extension) ) {
    $file_new_name = time()."_".rand(1000,9999).".".$extension;
    $path = "upload/".$file_new_name;
    move_uploaded_file($_FILES['simg']['tmp_name'], $path);
}

//insert query 
$sql = "INSERT INTO students(name,gender,stream,subject,stdimg) 
VALUES('$name','$gender','$stream','$subject','$file_new_name')";

if (mysqli_query($conn,$sql)) {
    echo 1;
} else {
    echo 0;
}




?>