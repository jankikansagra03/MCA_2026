<?php
echo "hello";
ob_start();
?>
<h1>Hello this is contact page</h1>
<p>A form will be displayed here</p>
<?php
$page_content = ob_get_clean() 
require_once 'layout1.php';
