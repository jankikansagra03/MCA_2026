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
    <form action="register_action.php" method="get" novalidate>
        Enter Fullname:
        <input type="text" name="fullname" id="fn"
               data-validation="required alpha min max" data-min="2" data-max="25">
        <span class="invalid-feedback" id="fn_err"></span>
        <br>
        Enter Mobile Number:
        <input type="text" name="mobile" id="mn"
               data-validation="required numeric min max" data-min="10" data-max="10">
        <span class="invalid-feedback" id="mn_err"></span>
        <br>
        <input type="submit" value="Register">
    </form>
</body>

</html>
