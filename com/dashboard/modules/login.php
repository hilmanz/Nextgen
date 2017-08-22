<?php
class login extends App{
		
	function beforeFilter(){
		$this->loginHelper = $this->useHelper('loginHelper');
		
	}
	
	function main(){
		$this->local();
	}
	
	function local(){
		
		global $CONFIG,$logger;
		
		$basedomain = $CONFIG['DASHBOARD_DOMAIN'];
		
		$this->assign('basedomain',$basedomain);
		
		//captcha 
		$_captcha = $this->_p('captcha');
		if(isset($_SESSION['codeBookCaptcha'])){
		$_valid = (md5($_captcha) == $_SESSION['codeBookCaptcha']) ? true : false;
		$_SESSION['codeBookCaptcha'] = "bed" . rand(00000000,99999999) . "bed";
		}else $_valid = false;
		if($_valid) {
		
			$res = $this->loginHelper->loginSession();
			
			if($res){
				
					$this->log('login','welcome');
					$this->assign("msg","login-in.. please wait");
					$this->assign("link","{$CONFIG['DASHBOARD_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
					sendRedirect("{$CONFIG['DASHBOARD_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
					return $this->out(TEMPLATE_DOMAIN_DASHBOARD . '/login_message.html');
					die();
			}
		}
		
		$this->assign("msg","failed to login..");
			// pr(TEMPLATE_DOMAIN_DASHBOARD .'login.html');
		print $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'login.html');
		exit;
	}
}
?>