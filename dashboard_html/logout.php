<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/Debugger.php";
$logger = new Debugger();
$logger->setAppName('applogin');
$logger->setDirectory('../logs/');
$application = new Application();
$application->log('logout','bye bye');

$application->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
$application->assign("msg","Logout.. please wait");
$application->assign("link","logout.php");
	if(isset($_SESSION['lid'])) $lid = $_SESSION['lid'];
	else $lid = 1;
	if($lid=='')$lid=1;

	$application->assign('locale',$LOCALE[$lid]);
// pr($application);
$application->assign('meta',$application->View->toString(TEMPLATE_DOMAIN_DASHBOARD . "/meta.html"));
$application->assign('header',$application->View->toString(TEMPLATE_DOMAIN_DASHBOARD . "/header.html"));
$application->assign('footer',$application->View->toString(TEMPLATE_DOMAIN_DASHBOARD . "/footer.html"));
$application->assign('mainContent',$application->View->toString(TEMPLATE_DOMAIN_DASHBOARD . '/login_message.html'));
session_destroy();
sendRedirect($CONFIG['DASHBOARD_DOMAIN']);
print $application->out(TEMPLATE_DOMAIN_DASHBOARD . '/master.html');

die();

?>