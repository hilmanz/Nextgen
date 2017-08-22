<?php
session_start();
include_once "../engines/Gummy.php";
include_once "../engines/functions.php";
include_once "../com/Application.php";

include_once "../com/API.php";
include_once "../com/API_Module.php";
$MAIN_TEMPLATE = "sample/default.html";
$req = new RequestManager();
$system = new System();
if($system->isMaintenance()){
	sendRedirect("maintenance.php");
	die();
}
?>