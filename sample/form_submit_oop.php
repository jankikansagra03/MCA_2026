<?php

include_once "DB_Classes/db_config.php";

if(isset($_POST['submit_btn'])) {
    $fn = $_POST['fname'];
    $em = $_POST['email'];
    $password = $_POST['pwd'];
    $mobile = $_POST['mno'];
    $file_name = uniqid() . $_FILES['file_upload']['name'];

    try {
        $registration = new Registration();
        $result = $registration->insertUser($fn, $em, $mobile, $password, $file_name);

        if ($result) {
            // Move profile picture to uploads folder
            if (!is_dir("uploads/profile_pictures")) {
                mkdir("uploads/profile_pictures", 0777, true);
            }
            move_uploaded_file($_FILES['file_upload']['tmp_name'], "uploads/profile_pictures/" . $file_name);
            echo "Registration Successful";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}