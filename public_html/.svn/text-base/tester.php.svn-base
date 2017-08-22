<?php
include_once "common.php";
include_once $APP_PATH.APPLICATION."/App.php";
include_once $ENGINE_PATH."Utility/Debugger.php";
$logger = new Debugger();
$logger->setAppName(APPLICATION);
$logger->setDirectory($CONFIG['LOG_DIR']);
require_once('../../api/simpletest/autorun.php');
$_SESSION['thistester'] = true;

class tester  extends UnitTestCase {
	
	function testUserLogin(){
		GLOBAL $CONFIG,$ENGINE_PATH;
		$app = new App();
		$login = $app->isUserOnline();	
		pr("<h1>testUserLogin</h1>");
		if($login) pr("user is login");
		else pr("user not login");
		$this->assertTrue($login);		
	
	}

	function testGetArticleFeatured(){
		GLOBAL $CONFIG,$ENGINE_PATH;
		$app = new App();

		$contentHelper = $app->useHelper('contentHelper');
		//$contenttype=0,$topcontent=array(2),$this->apps->_g('page')
		
		$app->Request->requests['GET']['page']='music';
		$articlelist = $contentHelper->getArticleFeatured();
		pr("<h1>testGetArticleFeatured</h1>");
		pr($articlelist);
		$this->assertTrue($articlelist);		
	}
	function testGetBanner(){
		GLOBAL $CONFIG,$ENGINE_PATH;
		$app = new App();

		$contentHelper = $app->useHelper('contentHelper');
		//$page="home",$type="slider_header",$featured=0,$limit=4
		$banner = $contentHelper->getBanner("home","slider_header",0,4);
		pr("<h1>testGetBanner</h1>");
		pr($banner);
		$this->assertTrue($banner);		
	}
	
	function testSaveFavorite(){
		GLOBAL $CONFIG,$ENGINE_PATH;
		$app = new App();

		$contentHelper = $app->useHelper('contentHelper');
		//$this->apps->_p('cid')
		$app->Request->requests['POST']['cid']=5877; //already in used
		$saveFavorite = $contentHelper->saveFavorite();
		pr("<h1>testSaveFavorite</h1>");
		pr($saveFavorite);
		$this->assertTrue($saveFavorite);		
	}
	
	function testAddComment(){
		GLOBAL $CONFIG,$ENGINE_PATH;
		$app = new App();

		$contentHelper = $app->useHelper('contentHelper');
		// if($cid==null) $cid = intval($this->apps->_p('cid'));
		// if($comment==null) $comment = $this->apps->_p('comment');
		$app->Request->requests['POST']['cid']=5877;
		$app->Request->requests['POST']['comment']=" ini komen terbaru ya"; 
		$addComment = $contentHelper->addComment();
		pr("<h1>testAddComment</h1>");
		pr(" Article ID  : ".$app->Request->requests['POST']['cid']);
		pr(" COMMENT :  ".$app->Request->requests['POST']['comment']);
		$this->assertTrue($addComment);		
	}
	
}
exit;
?>