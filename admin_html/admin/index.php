<?php
/**
* ADMINISTRATION PAGE
* @author Hapsoro Renaldy N <hapsoro.renaldy@winixmedia.com>
*/

include_once "common.php";
$CONFIG['MEDIUMSECURE'] = true; //for adminsite  only
// pr('asdasd');
include_once $ENGINE_PATH."Utility/Debugger.php";
$logger = new Debugger();
$logger->setAppName('admin');
$logger->setDirectory("../".$CONFIG['LOG_DIR']);


$admin = new Admin();

if($admin->auth->isLogin()){
	switch($admin->Request->getRequest("s")){
		case "admin":
			include_once $APP_PATH."Admin/Admin.php";
			$admin->execute(new AdminConfig(),"admin");
		break;
		case "builder":
			include_once $APP_PATH."Builder/Builder.php";
			$admin->execute(new Builder($admin->Request),"builder");
		break;
		default:
			//load auto modules
			if($admin->Request->getRequest("s")!=NULL){
				$plugin = $admin->loadPlugin($admin->Request,$admin->Request->getRequest("s"));
				if($plugin){
					$admin->execute($plugin,$admin->Request->getRequest("s"));
				}
			}else{
				//or load dashboard if there's no request specified.
				$admin->showDashboard();
			}
		break;
	}
}
//assign content to main template
$admin->show();
$admin->View->assign("mainContent",$admin->toString());
//output the populated main template
print $admin->View->toString($MAIN_TEMPLATE);
?>