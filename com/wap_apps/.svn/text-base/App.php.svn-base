<?php
global $APP_PATH;
require_once $APP_PATH . APPLICATION . "/helper/AccessControlHelper.php";
class App extends Application{
	
	var $_mainLayout=""; 
	var $user = array();
	var $ACL;
	var $userHelper;

	function __construct(){
		parent::__construct();
		$this->setVar();
	
	}
	/**
	 * warning : do not put db query here.
	 */
	function setVar(){
		if(isset($_SESSION['lid'])) $this->lid = intval($_SESSION['lid']);
		else $this->lid = 1;
		if($this->lid=='')$this->lid=1;
	
		$this->ACL = new AccessControlHelper();
	}

	function main(){
		global $CONFIG,$LOCALE;
		global $FB;
		$this->assign('locale',$LOCALE[$this->lid]);
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['WAP_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WAP']);
		if($CONFIG['CLOSED_WEB']==true){
				sendRedirect($CONFIG['TEASER_DOMAIN']);
				die();
		}
		if($CONFIG['MAINTENANCE']==true){
			$this->assign('meta',$this->View->toString(TEMPLATE_DOMAIN_WAP . "/meta.html"));
			$this->assign('mainContent', $this->View->toString(TEMPLATE_DOMAIN_WAP . '/under-maintenance.html'));
			$this->mainLayout(TEMPLATE_DOMAIN_WAP . '/master.html');
		}else{
			
				$str = $this->run();
				$this->afterFilter();
				
				//encrypt URL
				
				$this->assign('nexturl',urlencode($_SERVER['REQUEST_URI']));
				$this->assign('web_base_path',$CONFIG['WAP_DOMAIN']);
				$this->assign('getUserData',$this->Request->encrypt_params(array("page"=>"contentDownload","act"=>"getUserData")));
				$this->assign('meta',$this->View->toString(TEMPLATE_DOMAIN_WAP . "/meta.html"));
				$this->assign('header',$this->View->toString(TEMPLATE_DOMAIN_WAP . "/header.html"));
				$this->assign('footer',$this->View->toString(TEMPLATE_DOMAIN_WAP . "/footer.html"));
				$this->assign('mainContent',$str);
				$this->beforeRender();
				$this->mainLayout(TEMPLATE_DOMAIN_WAP . '/master.html');
				
		}
	}
	
	function setWidgets($class=null,$path=null){
		GLOBAL $APP_PATH;
		
		if($class==null) return false;
			if( !is_file( $APP_PATH .WIDGET_DOMAIN_WAP. $path . $class .'.php' ) ){
			
				if( is_file( '../templates/'. WIDGET_DOMAIN_WAP . $path  . $class .'.html' ) ){
					return $this->View->toString(WIDGET_DOMAIN_WAP .$path. $class .'.html');
				}
			}else{
				require_once $APP_PATH . WIDGET_DOMAIN_WAP . $path. $class .'.php';
				$widgetsContent = new $class($this);
				return $widgetsContent->main();
			}
	}
	
	
	function useHelper($class=null,$path=null){
		GLOBAL $APP_PATH,$DEVELOPMENT_MODE;
		if($class==null) return false;
		if(file_exists($APP_PATH . HELPER_DOMAIN_WEB. $path. $class .'.php')){
			require_once $APP_PATH . HELPER_DOMAIN_WEB. $path. $class .'.php';
			$helper = new $class($this);
			return $helper;
		}else{
			if($DEVELOPMENT_MODE){
				print "please define : ".$APP_PATH . HELPER_DOMAIN_WEB. $path. $class .'.php';
				die();
			}
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
		global $APP_PATH,$CONFIG;
		
		//ini me-return variable $page dan $act
		if($this->Request->getParam("req")) $this->Request->decrypt_params($this->Request->getParam("req"));
		$page = $this->Request->getParam('page');
		$act = $this->Request->getParam('act');		
		if( $page != '' ){
			if( !is_file( $APP_PATH . MODULES_DOMAIN_WAP . $page . '.php' ) ){
				if( is_file( '../templates/'. TEMPLATE_DOMAIN_WEB . '/'. $page . '.html' ) ){
					return $this->View->toString(TEMPLATE_DOMAIN_WEB.'/'.$page.'.html');
				}else{
					sendRedirect("index.php");
					die();
				}
			}else{
				require_once MODULES_DOMAIN_WAP. $page.'.php';
				$content = new $page();
				$content->beforeFilter();
				if( $act != '' ){
					if( method_exists($content, $act) )	return $content->$act();
					else return $content->main();
				}else return $content->main();
			}
		}else{
			
			require_once MODULES_DOMAIN_WAP . $CONFIG['DEFAULT_MODULES'];
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
	
	function is_email_available($email){
		//VALIDATION EMAIL TO DB (cari di table smac_registration,smac_agency & smac_user adakah yang sama?)
		$sql = "SELECT a.email FROM
						(
						SELECT r.agency_email AS email FROM smac_web.smac_registration r WHERE n_status IN ('0','1') 
						UNION
						SELECT agency_email AS email FROM smac_web.smac_agency 
						UNION
						SELECT email FROM smac_web.smac_user
						) a
						WHERE
						a.email='".mysql_escape_string(strtolower($email))."';";
		
		$this->open(0);
		$rs = $this->fetch($sql);
		$this->close();		
		if($rs['email'] != ''){
			return false;
		}
		
		return true;
		
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