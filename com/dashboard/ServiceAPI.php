<?php
global $APP_PATH;
class ServiceAPI extends API{

	var $access_info = array();
	
	function __construct(){
		parent::__construct();
		$this->setVar();
	}
	
	function setVar(){
	
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
		global $APP_PATH;
		$page = $this->Request->getParam('service');
		$act = $this->Request->getParam('m');
		$access_token = $this->Request->getParam('access_token');
		
		// if($this->is_valid_access_token($access_token)){
			if( $page != '' ){
				
				$service = $page."_service";
				if(is_file( $APP_PATH . APPLICATION . '/services/'. $service . '.php' ) ){	
					
					require_once 'services/'. $service . '.php';
					$content = new $service($this);
					if( $act != '' ){
						if(method_exists($content, $act) ){
							return $content->$act();
						}
					}
				}
			// }
		}
		return $this->error_404();
	}
	function is_valid_access_token($access_token){
		global $CONFIG;
		$info = read_access_token($access_token);
		
		if($info['api_key']!=null&&$info['user_id']!=null){
			if($info['api_key']==$CONFIG['SERVICE_KEY']){
				$this->access_info = $info;
				return true;
			}
		}else if($info['api_key']!=null&$this->Request->getParam('nouser')){
			if($info['api_key']==$CONFIG['SERVICE_KEY']){
				$this->access_info = $info;
				return true;
			}
		}else{}
	}
	function error_404(){
		return json_encode(array("status"=>404,"message"=>"method not found"));
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
	

}
?>