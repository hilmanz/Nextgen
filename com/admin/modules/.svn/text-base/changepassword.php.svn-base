<?php
class changepassword extends App{	
	
	function beforeFilter(){
		global $CONFIG;
		$this->loginHelper = $this->useHelper('loginHelper');
	
		$this->assign('basedomain',$CONFIG['ADMIN_DOMAIN']);
	}
	
	function main(){
		
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'/apps/changepassword.html');		
	}
	
	function changeit(){
		global $CONFIG;
		$data = $this->userHelper->changepassword();
		if($data){
				$this->log('login','welcome');
				$this->assign("msg","login-in.. please wait");
				$this->assign("link","{$CONFIG['ADMIN_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
				sendRedirect("{$CONFIG['ADMIN_DOMAIN']}home");
				// pr('masuk');
				return $this->out(TEMPLATE_DOMAIN_ADMIN . '/login_message.html');
				exit;
		}else{
		
				$this->assign("msg","please assign correct credential password");
				sendRedirect("{$CONFIG['ADMIN_DOMAIN']}logout.php");
				// pr('masuk');
				return $this->out(TEMPLATE_DOMAIN_ADMIN . '/login_message.html');
				exit;
		}
	}
	
}
?>