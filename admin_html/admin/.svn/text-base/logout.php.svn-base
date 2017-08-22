<?php
session_start();
include_once "common.php";
$admin = new Admin();

$admin->log->sendActivity('logout','see ya');
$admin = null;
session_destroy();
header("Location:index.php");
?>