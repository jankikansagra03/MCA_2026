<?php
require_once 'db_config_oop.php';

$db = new Database();
$db->createDatabase("MCA_2026_2");
$db->selectDatabase("MCA_2026_2");
