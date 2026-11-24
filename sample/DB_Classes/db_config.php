<?php
include_once "db_config_oop.php";

$db = new DBConnection("MCA_2026");
$con = $db->getConnection();

include_once "Registration.php";
