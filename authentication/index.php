<?php
/**
 * Authentication App Gateway
 */
include_once "common.php";
include_once "../com/CredentialApp/App.php";
//error_reporting(1);
$view = new BasicView();
$app = new CredentialApp(&$req);
print $app->main();

die();
?>