<?php
include_once("db_config.php");

$id = $_GET['id'];
echo $id;

$q = "select * from registration where id=$id";
$user_data = mysqli_fetch_assoc(mysqli_query($con, $q));

echo "ID:" . $user_data['id'] . "<br/>";
echo "Name:" . $user_data['fullname'] . "<br/>";
echo "Email:" . $user_data['email'] . "<br/>";
echo "Password:" . $user_data['password'] . "<br/>";
echo "Mobile:" . $user_data['mobile'] . "<br/>";
echo "Profile Picture:" . $user_data['profile_picture'] . "<br/>";
