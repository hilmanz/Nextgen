<?php ob_start();


include_once "common.php";
$CONFIG['MEDIUMSECURE']=true;
include_once $APP_PATH.ADMIN_APPS."/App.php";
include_once $ENGINE_PATH."Utility/Debugger.php";
$logger = new Debugger();
$logger->setAppName(ADMIN_APPS);
$logger->setDirectory($CONFIG['LOG_DIR']);
$app = new App();
$app->main();

print $app;
die();
?>
