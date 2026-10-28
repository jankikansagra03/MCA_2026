<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="form_submit_oop.php" method="post" enctype="multipart/form-data">
        Fullname: <input type="text" name="fname" id="">
        <br>
        Email: <input type="email" name="email" id="">
        <br>
        password: <input type="password" name="pwd" id="">
        <br>
        Mobile Number: <input type="text" name="mno" id="">
        <br>
        <input type="file" name="file_upload" id="" multiple>
        <br>
        <input type="submit" value="Register" name="submit_btn">
    </form>
</body>

</html>