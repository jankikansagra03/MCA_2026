<?php
ob_start();
$a = 10;
echo $a;
?>
<h1>Hello</h1>
<?php
$page_content = ob_get_clean();
include_once 'layout.php';
?>