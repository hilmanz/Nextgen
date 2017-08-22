<?php
global $APP_PATH;

class App extends Application{
	
	var $_mainLayout=""; 
	var $user = array();
	var $ACL;
	var $userHelper;
	var $permissionHelper=false;
	var $contentHelper;
	var $userpage;
	function __construct(){
		parent::__construct();
		
		$this->setVar();
	
	}
	/**
	 * warning : do not put db query here.
	 */
	function setVar(){
		global $CONFIG;
		if(isset($_SESSION['lid'])) $this->lid = intval($_SESSION['lid']);
		else $this->lid = 1;
		if($this->lid=='')$this->lid=1;
		
		$this->userpage = $CONFIG['DINAMIC_MODULE'];
		$this->assign('userpage',$this->userpage);
		
		$page= strip_tags($this->_g('page'));	
		$act= strip_tags($this->_g('act'));	
		// pr($page);exit;
		if($page!='login'&&$page!='landing'){
			if($this->isUserOnline()){
				$this->user = $this->getUserOnline();
				$this->userHelper = $this->useHelper('userHelper');
				$this->messageHelper = $this->useHelper('messageHelper');
				
			}else{			
				
				//if($CONFIG['BASE_DOMAIN']."login/".$act!=$CONFIG['LOGIN_PAGE']) {
			
					sendRedirect("{$CONFIG['LOGIN_PAGE']}");
					exit;
				//}
			}
		}
		
		
	}
	
	function main(){
		global $CONFIG,$LOCALE, $ENGINE_PATH;
		global $FB;	
		// pr('masuk');exit;
		if($this->isUserOnline()) $this->getMessageUser();
		$this->assign('locale',$LOCALE[$this->lid]);
		
		/** MOBILE REDIRECTION **/
		include_once  $ENGINE_PATH."Utility/Mobile_Detect.php";
		$detect = new Mobile_Detect();
	
		 

		if($CONFIG['CLOSED_WEB']==true){
			sendRedirect($CONFIG['TEASER_DOMAIN']);
			die();
		}
		
		if($CONFIG['MAINTENANCE']==true){
			$this->assign('meta',$this->View->toString(TEMPLATE_DOMAIN_WEB . "/meta.html"));
			$this->assign('mainContent', $this->View->toString(TEMPLATE_DOMAIN_WEB . '/under-maintenance.html'));
			$this->mainLayout(TEMPLATE_DOMAIN_WEB . '/master.html');
		} else {
		
			if($this->isUserOnline()){
			 
					$str = $this->run();
			 
			}else{			
				$str = $this->run();
			}
			
			$this->afterFilter();		
			
			$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
			$this->assign('pages',  strip_tags($this->_g('page')));
			$this->assign('act',  strip_tags($this->_g('act')));
			$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
			
			//encrypt URL
			$this->assign('nexturl',urlencode($_SERVER['REQUEST_URI']));
			$this->assign('getUserData',$this->Request->encrypt_params(array("page"=>"contentDownload","act"=>"getUserData")));
			
			$this->assign('isLogin',$this->isUserOnline());
			if($this->isUserOnline()) $this->assign('user',$this->user);
			
			$this->assign('currentpage',strip_tags($this->_g('page')));
			$this->assign('coorporate_base_path',$CONFIG['COORPORATE_DOMAIN']);
			$this->assign('meta',$this->View->toString(TEMPLATE_DOMAIN_WEB . "/meta.html"));
			
			$this->assign('header',$this->View->toString(TEMPLATE_DOMAIN_WEB . "/header.html"));
			$this->assign('footer',$this->View->toString(TEMPLATE_DOMAIN_WEB . "/footer.html"));
			$this->assign('fbID',$FB['appID']);
						
			$this->assign('mainContent',$str);
			$this->beforeRender();
			$this->mainLayout(TEMPLATE_DOMAIN_WEB . '/master.html');				
		}
	}
	
	function getMessageUser()
	{
	
		$message = $this->messageHelper->getinboxcount();
		$this->View->assign('messageCount',$message);
		// pr($message);
		
	}

	
	function setWidgets($class=null,$path=null){
		GLOBAL $APP_PATH;
		
		if($class==null) return false;
			if( !is_file( $APP_PATH .WIDGET_DOMAIN_WEB. $path . $class .'.php' ) ){
			
				if( is_file( '../templates/'. WIDGET_DOMAIN_WEB . $path  . $class .'.html' ) ){
					return $this->View->toString(WIDGET_DOMAIN_WEB .$path. $class .'.html');
				}
			}else{
			// pr($class);
				include_once $APP_PATH . WIDGET_DOMAIN_WEB . $path. $class .'.php';
				$widgetsContent = new $class($this);
				return $widgetsContent->main();
			}
	}
	
	
	function useHelper($class=null,$path=null){
		GLOBAL $APP_PATH,$DEVELOPMENT_MODE;
		if($class==null) return false;
		if(file_exists($APP_PATH . HELPER_DOMAIN_WEB. $path. $class .'.php')){
			include_once $APP_PATH . HELPER_DOMAIN_WEB. $path. $class .'.php';
			$helper = new $class($this);
			return $helper;
		}else{
			
				print "please define : ".$APP_PATH . HELPER_DOMAIN_WEB. $path. $class .'.php';
				die();
		
		}
	}
	
	/*
	 *	Mengatur setiap paramater di alihkan ke class yang mengaturnya
	 *
	 *	Urutan paramater:
	 *	- page			(nama class) 
	 *	- act				(nama method)
	 *	- optional		(paramater selanjutnya optional, tergantung kebutuhan)
	 */
	function run(){
		global $APP_PATH,$CONFIG,$ENGINE_PATH;
		
		//ini me-return variable $page dan $act
		if($this->Request->getParam("req")) $this->Request->decrypt_params($this->Request->getParam("req"));
		$page = $this->Request->getParam('page');		
		$act = $this->Request->getParam('act');
		
		/* unverified go here */
		
		if($this->isUserOnline()) {

			$users = $this->getUserOnline();
			if($users){
				if($users->n_status==0&&!in_array($page,$CONFIG['access-unverified'])) {				
					$this->View->assign('basedomain', $CONFIG['BASE_DOMAIN']);
					$this->View->assign('pages',  strip_tags($this->_g('page')));
					$this->View->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);					
					return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/unverified-pages.html');
				}
			}
		}
		
		if( $page != '' ){
			
			if( !is_file( $APP_PATH . MODULES_DOMAIN_WEB . $page . '.php' ) ){
		
				if( is_file( '../templates/'. TEMPLATE_DOMAIN_WEB . '/'. $page . '.html' ) ){
						if($page!='login'&&$page!='landing'){
							if($this->isUserOnline()){
								return $this->View->toString(TEMPLATE_DOMAIN_WEB.'/'.$page.'.html');
							}else{
								
			
									sendRedirect("{$CONFIG['LOGIN_PAGE']}");
									exit;
								
							}
						}else{
								
			
									sendRedirect("{$CONFIG['LOGIN_PAGE']}");
									exit;
							
						}
						
				}else{
					sendRedirect($CONFIG['BASE_DOMAIN']."index.php");
					die();
				}
			}else{
					
				include_once MODULES_DOMAIN_WEB. $page.'.php';
				
				$content = new $page();
				
				$content->beforeFilter();
				if( $act != '' ){
					if( method_exists($content, $act) )	return $content->$act();
					else {
						if($page!='login'&&$page!='landing'){
							if($this->isUserOnline()){
								return $content->main();
							}else{
								
			
									sendRedirect("{$CONFIG['LOGIN_PAGE']}");
									exit;
								
							}
						}else{
								
			
									sendRedirect("{$CONFIG['LOGIN_PAGE']}");
									exit;
							
						}
					}
				}else {
						if($page!='login'&&$page!='landing'){
							if($this->isUserOnline()){
								return $content->main();
							}else{
								
			
									sendRedirect("{$CONFIG['LOGIN_PAGE']}");
									exit;
								
							}
						}else{
								
			
									sendRedirect("{$CONFIG['LOGIN_PAGE']}");
									exit;
								
						}
				}
			}			
		} else {			
			include_once MODULES_DOMAIN_WEB . $CONFIG['DEFAULT_MODULES'];
			$content = new home();
			$content->beforeFilter();
			return $content->main();
		}
	}
	
	function birthday($birthday){
		$birth = explode(' ',$birthday);
		list($year,$month,$day) = explode("-",$birth[0]);
		$year_diff  = date("Y") - $year;
		$month_diff = date("m") - $month;
		$day_diff   = date("d") - $day;
		if ($day_diff < 0 || $month_diff < 0)
		  $year_diff--;
		return $year_diff;
	}
	
	function is_valid_email($email) {
	  $result = TRUE;
	  if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
		$result = FALSE;
	  }
	  return $result;
	}
	
	function is_admin(){
	
		$sql = "SELECT count(*) as total 
			FROM tbl_front_admin
			WHERE
			user_id='".mysql_escape_string(intval($_SESSION['user_id']))."' 
			AND fb_id='".mysql_escape_string(intval($_SESSION['user_login_id']))."'
			LIMIT 1
			;";
		
		$this->open(0);
		$checkAdmin = $this->fetch($sql);
		$this->close();	
		// print_r($sql);			
		if($checkAdmin) {
		$is_admin = ($checkAdmin['total']>=1) ? true : false ;
		}else $is_admin = false;
		
		return $is_admin;
	}
	function objectToArray($object) {
		//print_r($object);exit;
		
		 if (is_object($object)) {
		    foreach ($object as $key => $value) {
		        $array[$key] = $value;
		    }
		}
		else {
		    $array = $object;
		}
		return $array;
		
	}
	
}
?>