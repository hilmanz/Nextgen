<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";

sendredirect('http://www.amild.com/amn/html');
die();
$app = LoadModule("ACSONG",&$req);
if($_SESSION['ref']!=NULL){
	$ref = $_SESSION['ref'];	
}else{
	$ref = $req->getParam("ref");
}
if($ref==NULL){
	$ref=0;	
}

$_SESSION['ref'] = $ref;
print $app->main();
//--------------------->
?>