<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Twitter/tmhOAuth.php";
include_once $ENGINE_PATH."Utility/Twitter/tmhUtilities.php";
include_once $APP_PATH.APPLICATION."/helper/FacebookHelper.php";
/**
 * facebook service for axis ramadhan app
 * the service gives the following methods : 
 * - retrieving the online people
 * - retrieving the pictures
 * - retrieving the seat order
 * - send / recieve chat message
 * - recieve points
 * @author duf
 *
 */
class facebook_service extends API{
	var $user_id;
	var $helper;
	function init(){
		global $FB;
		$this->user_id = $this->access_info['user_id'];
		$this->helper = new FacebookHelper($this->Request);
	}
	function foo(){
		//$response = json_encode(array("status"=>1));
		return $this->toJson(1,'message',array("foo"=>"bar","name"=>"hello"));	
	}
	function init_user(){
		$fb_id = $this->Request->getParam("fb_id");
		
	}
	function like(){
		$this->init();
		$fb_id = $this->Request->getParam("fb_id");
		$q = $this->helper->flag_for_like($fb_id,true);
		if($q){
			return $this->toJson(1,'liked',array());
		}else{
			return $this->toJson(0,'failed',array());
		}
	}
	function ping(){
		
	}
}
?>