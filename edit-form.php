<?php
include 'db.php';  //$conn
$id = $_POST['id'];
$sql = "SELECT * FROM students WHERE id = {$id}";
$result = mysqli_query($conn, $sql);
$output = "";
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $subjects = explode(",", $row['subject']);

    $output .= "
        <form id='updateForm'  enctype='multipart/form-data'>
        <input type='hidden' name='id' value='{$row["id"]}'>
        <p>Name</p>
        <p><label><input type='text' name='name' value='{$row["name"]}'></label></p>
        <p>Gender</p>
        <p><label><input " . ($row['gender'] == 'Male' ? "checked" : "") . " type='radio' name='gender' value='Male'>Male</label></p>
        <p><label><input " . ($row['gender'] == 'Female' ? "checked" : "") . " type='radio' name='gender' value='Female'>Female</label></p>
        <p><label><input " . ($row['gender'] == 'Other' ? "checked" : "") . " type='radio' name='gender' value='Other'>Other</label></p>
        <p>Stream</p>
        <select name='stream'>
            <option value=''>--Select--</option>
            <option " . ($row['stream'] == 'BCA' ? 'selected' : '') . " value='BCA'>BCA</option>
            <option " . ($row['stream'] == 'BBA' ? 'selected' : '') . " value='BBA'>BBA</option>
            <option " . ($row['stream'] == 'MCA' ? 'selected' : '') . " value='MCA'>MCA</option>
            <option " . ($row['stream'] == 'B.Tech' ? 'selected' : '') . " value='B.Tech'>B.Tech</option>
        </select>
        <p>Subject</p>
        <p><label><input " . (in_array('C', $subjects) ? 'checked' : '')  . " type='checkbox' name='sub[]' value='C'>C</label></p>
        <p><label><input " . (in_array('C++', $subjects) ? 'checked' : '')  . " type='checkbox' name='sub[]' value='C++'>C++</label></p>
        <p><label><input " . (in_array('Java', $subjects) ? 'checked' : '')  . " type='checkbox' name='sub[]' value='Java'>Java</label></p>
        <p><label><input " . (in_array('Python', $subjects) ? 'checked' : '')  . " type='checkbox' name='sub[]' value='Python'>Python</label></p>
        <p>Image</p>
        <p><input type='file' name='simg'></p>
        <img src='upload/{$row['stdimg']}' style='width:100px;' />
        <p><input type='submit' name='Update' value='Update'></p>
    </form>
    ";
} else {
    $output .= "No records found";
}
echo $output;
