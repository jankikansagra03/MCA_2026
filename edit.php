<?php
include_once "db_config.php";
$id = $_GET['uid'];
echo $id;

$query = "select * from registration where `id`=$id";
$result = mysqli_query($con, $query);

$users = mysqli_fetch_assoc($result);
?>
<form action="form_submit.php" method="post" enctype="multipart/form-data">
    Fullname: <input type="text" name="fname" id="" value="<?php echo $users['fullname'] ?> ">
    <br>
    Email: <input type="email" name="email" id="" value="<?= $users['email'] ?>" readonly>
    <br>
    password: <input type="password" name="pwd" id="" value="<?= $users['password'] ?>">
    <br>
    Mobile Number: <input type="text" name="mno" id="" value="<?= $users['mobile'] ?>">
    <br>
    <?= $users['profile_picture'] ?>
    <img src="uploads/profile_pictures/<?= $users['profile_picture'] ?>" alt="">
    <input type="file" name="file_upload" id="" multiple>
    <br>
    <input type="submit" value="Register" name="submit_btn">
</form>