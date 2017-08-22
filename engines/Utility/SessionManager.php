<?php
class SessionManager extends RequestManager{
	var $id;
	var $type;
	var $COOKIE = "COOKIE";
	var $SESSION = "SESSION";
	var $sessions = array(array());
	function SessionManager($id,$type="SESSION"){
		$this->id = $id;
		$this->type = $type;
		parent::RequestManager();
		$this->retrieveSessions();
	}
	function retrieveSessions(){
		if($this->type=="SESSION"){
			$this->sessions = $this->getSession(base64_encode($this->id."_".date("Ymd")."_".$this->type));
			if(sizeof($this->sessions)==0){
				//create session
				$this->sessions['created_date'] = date("Y/m/d");
				$this->createSession();
			}
		}else{
			$this->sessions = $this->getCookie(base64_encode($this->id."_".date("Ymd")."_".$this->type));
			if($this->sessions==NULL||!is_array($this->sessions)){
				//create session
				$this->sessions['created_date'] = date("Y/m/d");
				$this->createCookie();
			}
		}
	}
	function createSession(){
		
		$_SESSION[base64_encode($this->id."_".date("Ymd")."_".$this->type)] = $this->sessions;
	}
	function createCookie(){
		$_COOKIE[base64_encode($this->id."_".date("Ymd")."_".$this->type)] = $this->sessions;
	}
	function addVariable($name,$value){
		$this->sessions[$name] = $value;
		if($this->type=="SESSION"){
			$this->createSession();
		}else{
			$this->createCookie();
		}
	}
	function getVariable($name){
		return @$this->sessions[$name];
	}
	function getActiveSessions(){
		return $_SESSION;
	}
}
?>