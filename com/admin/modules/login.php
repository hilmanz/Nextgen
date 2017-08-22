<?php
class login extends App{
		
	function beforeFilter(){
		$this->loginHelper = $this->useHelper('loginHelper');
		$this->messageHelper  = $this->useHelper('messageHelper');
	
	}
	
	function main(){
		global $CONFIG,$logger;
		$basedomain = $CONFIG['ADMIN_DOMAIN'];
		$this->assign('basedomain',$basedomain);
		
		$sessionid = strip_tags($this->_g('id'));
		
		$_captcha = $this->_p('captcha');
		if(isset($_SESSION['codeBookCaptcha'])){
		$_valid = (md5($_captcha) == $_SESSION['codeBookCaptcha']) ? true : false;
		$_SESSION['codeBookCaptcha'] = "bed" . rand(00000000,99999999) . "bed";
		}else $_valid = false;
		if($_valid) {
	
			$res = $this->loginHelper->mopsessionlogin($sessionid);
			
			
			if($res){
					$this->log('login','welcome');
					$this->assign("msg","login-in.. please wait");
					$this->assign("link","{$CONFIG['ADMIN_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
					sendRedirect("{$CONFIG['ADMIN_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
					return $this->out(TEMPLATE_DOMAIN_ADMIN . '/login_message.html');
					die();
			}
		}

		sendRedirect("{$CONFIG['LOGIN_PAGE']}");
		//return $this->out(TEMPLATE_DOMAIN_ADMIN . '/login_message.html');
		die();
	}
	
	function local(){

		global $CONFIG,$logger;
		
		$basedomain = $CONFIG['ADMIN_DOMAIN'];
		$this->assign('basedomain',$basedomain);
		
			$res = $this->loginHelper->loginSession(true);
			
			if($res['result']){
			
			
		$_captcha = $this->_p('captcha');
		// pr($_captcha);
		if(isset($_SESSION['codeBookCaptcha'])){
		$_valid = (md5($_captcha) == $_SESSION['codeBookCaptcha']) ? true : false;
		$_SESSION['codeBookCaptcha'] = "bed" . rand(00000000,99999999) . "bed";
		}else $_valid = false;
		// pr($_valid); exit;
		if($_valid) {
		
				if(@$this->session->getSession($CONFIG['SESSION_NAME'],"admin")->login_count==0){
						$this->log('login','welcome');
						$this->assign("msg","login.. please wait");
						$this->assign("link","{$CONFIG['ADMIN_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
						sendRedirect("{$CONFIG['ADMIN_DOMAIN']}changepassword");
						return $this->out(TEMPLATE_DOMAIN_ADMIN . '/login_message.html');
						die();
				}
				$this->log('login','welcome');
				$this->assign("msg","login.. please wait");
				$this->assign("link","{$CONFIG['ADMIN_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
				sendRedirect("{$CONFIG['ADMIN_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
				return $this->out(TEMPLATE_DOMAIN_ADMIN . '/login_message.html');
				die();
				}else{
					$this->assign("msg",'wrong captcha');
				}
			}else{
				$this->assign("msg",json_encode($res['message']));
			}
					
	
		// $this->assign("msg","failed to login..");
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'/login.html');
	}
}
?>