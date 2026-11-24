<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/validator.js"></script>
</head>

<body>
    <form action="form_submit_oop.php" method="post" enctype="multipart/form-data" novalidate>
        Fullname: <input type="text" name="fname" id="fname"
                   data-validation="required alpha min max" data-min="2" data-max="50">
        <span class="invalid-feedback" id="fnameError"></span>
        <br>
        Email: <input type="email" name="email" id="email" data-validation="required email">
        <span class="invalid-feedback" id="emailError"></span>
        <br>
        password: <input type="password" name="pwd" id="pwd" data-validation="required strongPassword">
        <span class="invalid-feedback" id="pwdError"></span>
        <br>
        Mobile Number: <input type="text" name="mno" id="mno"
                        data-validation="required numeric min max" data-min="10" data-max="10">
        <span class="invalid-feedback" id="mnoError"></span>
        <br>
        <input type="file" name="file_upload" id="file_upload" multiple
               data-validation="file filesize" data-filesize="2048" data-max-items="3">
        <span class="invalid-feedback" id="file_uploadError"></span>
        <br>
        <input type="submit" value="Register" name="submit_btn">
    </form>
</body>

</html>
