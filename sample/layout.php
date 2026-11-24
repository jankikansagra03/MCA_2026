<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="row">
        <div class="col-12 bg-primary">
            <h1>Navbar</h1>
        </div>
    </div>
    <div class="row">
        <?php
        if (isset($page_content)) {
            echo $page_content;
        }
        ?>
    </div>
    <div class="row">

        <div class="col-12 bg-success">
            <h1>Footer</h1>
        </div>
    </div>
</body>

</html>