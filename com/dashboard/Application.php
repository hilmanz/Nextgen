<?php
include_once $APP_PATH.APPLICATION."/helper/SessionHelper.php";
include_once $ENGINE_PATH."Utility/Paginate.php";
require $APP_PATH.APPLICATION."/helper/activityReportHelper.php";
/**
 * codebook versi 2.0 activity tracker
 * Enter description here ...
 * @author duf
 * develope bummi
 */
class Application extends SQLData{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $subdomain = "";
	function __construct(){
		global $CONFIG;
		$this->config = $CONFIG;
		$this->session = new SessionHelper(APPLICATION.'_Session');
		$this->Request = new RequestManager();
		$this->View = new BasicView();
		$this->track = new activityReportHelper($this);
		if($this->Request->getRequest('subdomain')!=NULL){
			$_SESSION['subdomain'] = $this->Request->getRequest('subdomain');
			$this->subdomain = $_SESSION['subdomain'];
		}
	}
	
	function isUserOnline(){
		global $LOCALE;
		if($this->session->getSession($this->config['SESSION_NAME'],"dashboard")) return true;
		return false;
		
	}
	
	function getUserOnline(){
		if($this->session->getSession($this->config['SESSION_NAME'],"dashboard")) return $this->session->getSession($this->config['SESSION_NAME'],"dashboard");
		else return false;
		
	}

	function log($param=NULL,$id=NULL,$expLog=TRUE){
		$this->track->log($param,$id,$expLog);
	}
	
	function mainLayout($val=null){
		if($val==null){
			return $this->_mainLayout;
		}else{
			$this->_mainLayout = $val;
		}
	}
	function main(){
		
	}
	function admin(){
		
	}
	function param($name){
		return $this->Request->getParam($name);
	}
	function assign($name,$val){
		$this->View->assign($name,$val);
	}
	function post($name){
		return $this->Request->getPost($name);
	}
	function out($tpl){
		return $this->View->toString($tpl);
	}
	function __toString(){
	    return $this->out($this->_mainLayout);
	}
	function getList($sql,$start,$total,$base_url){
		//paging
		$paging = new Paginate();
		$sql1 = $sql." LIMIT ".$start.",".$total;
		$sql2 = eregi_replace("SELECT (.*) FROM","SELECT COUNT(*) as total FROM",$sql);
		$sql2 = eregi_replace("ORDER BY(.*)","",$sql2);
		$this->open();
		$list = $this->fetch($sql,1);
		$rs = $this->fetch($sql2);
		$this->close();
		$this->assign("list",$list);
		$this->assign("pages",$paging->generate($start, $total, $rs['total']));
		
	}
	
	function object2array($object) {
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
	//helper methods
	function _get($name){
		return $this->Request->getParam($name);
	}
	function _post($name){
		return $this->Request->getPost($name);
	}
	function _request($name){
		return $this->Request->getRequest($name);
	}
	/**
	 * lazy alias of _post
	 */
	function _p($name){
		return $this->_post($name);
	}
	/**
	 * lazy alias of _get
	 */
	function _g($name){
		return $this->_get($name);
	}
	/*
	 * basic clean method.
	 * this method will clean the string so it safe to distribute via url
	 */
	function clean($str){
		$str = htmlspecialchars(mysql_escape_string($str));
		return $str;
	}
	/**
	 * retrieve the account id / owner of the subdomain
	 */
	function get_subdomain_owner(){
		$subdomain = $this->Request->getRequest("subdomain");
		if(strlen($subdomain)>0){
			$this->open(0);
			$sql = "SELECT * FROM smac_web.smac_subdomain 
								WHERE subdomain='".$subdomain."' LIMIT 1";
			
			$rs = $this->fetch($sql);
			$account_id = $rs['account_id'];
			if($account_id<1){
				$account_id = 1;
			}
			$this->close();
		}else{
			$account_id=1;
		}
		return $account_id;
	}
	protected function beforeRender(){}
	protected function afterFilter(){}
	protected function beforeFilter(){}
}
?>