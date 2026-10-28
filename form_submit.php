<?php

include_once "db_config.php";

if (isset($_POST['submit_btn'])) {
    $fn = $_POST['fname'];
    $em = $_POST['email'];
    $password = $_POST['pwd'];
    $mobile = $_POST['mno'];
    $file_name = uniqid() . $_FILES['file_upload']['name'];
    try {
        $insert_reg =  "INSERT INTO `registration`(`fullname`, `email`, `mobile`, `password`, `profile_picture`) VALUES ('$fn','$em',$mobile,'$password','$file_name')";

        if (mysqli_query($con, $insert_reg)) {
            //move profile picture to uploads folder

            if (!is_dir("uploads/profile_pictures")) {
                mkdir("uploads/profile_pictures");
            }
            move_uploaded_file($_FILES['file_upload']['tmp_name'], "uploads/profile_pictures/" . $file_name);
            echo "Registration Successful";
        } else {
            throw new Exception("Data Not Inserted");
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
