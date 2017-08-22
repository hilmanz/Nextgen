<?php
class login extends App{
		
	function beforeFilter(){
		global $CONFIG,$logger;
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->loginHelper = $this->useHelper('loginHelper');
		$this->messageHelper  = $this->useHelper('messageHelper');
 
		$this->assign('basedomain',$basedomain);
	}
	
	function main(){ 
			global $CONFIG,$logger;
		
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign('basedomain',$basedomain);
			 
			$logger->log(strip_tags($this->_request('username')));
			if($this->_p('username')&&$this->_p('password')){
				 
				$res = $this->loginHelper->beatloginsession();
				pr($res);
				if($res){
				 
					if(isset($res->status)){	
								$this->log('login');		 
								
								$this->log('login','welcome');
								$this->assign("msg","login-in.. please wait");
								$this->assign("link","{$CONFIG['BASE_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
								sendRedirect("{$CONFIG['BASE_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
								return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
								die();
					} 
				}
			}
				 
		 
		sendRedirect("{$CONFIG['LOGIN_PAGE']}");
	 
		die();
	}
	
	function local(){

				global $CONFIG,$logger;
		
			$basedomain = $CONFIG['BASE_DOMAIN'];
			$this->assign('basedomain',$basedomain);
			 
			$logger->log(strip_tags($this->_request('username')));
			if($this->_p('username')&&$this->_p('password')){
				 
				$res = $this->loginHelper->loginSession();
			 
				if($res){
				 
				 	
								$this->log('login');		 
								
								$this->log('login','welcome');
								$this->assign("msg","login-in.. please wait");
								$this->assign("link","{$CONFIG['BASE_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
								sendRedirect("{$CONFIG['BASE_DOMAIN']}{$CONFIG['DINAMIC_MODULE']}");
								return $this->out(TEMPLATE_DOMAIN_WEB . '/login_message.html');
								die();
				 
				}
			}
				 
		 
	 
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/login.html');
	}

	function forgotpassword(){
		global $CONFIG,$logger,$EMAIL;
		
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign('basedomain',$basedomain);

		$forgot = strip_tags($this->_p('forgotpassword'));	
		
		if($forgot){
			$email = strip_tags($this->_p('email'));
			$checkuseremail = $this->passwordHelper->checkuseremail($email);

			if($checkuseremail){
				$hashemail="Beatr3s3t3m4il";

				$reset_link = $basedomain."login/reset_password/".sha1($hashemail.$email)."/543_".$checkuseremail['id']."_42";


				$to = $email;
				
				$subject = "Beat - Forgot Password";
				
				$message = '<html><body>';
				$message .= "<p>Hi {$checkuseremail['name']} {$checkuseremail['last_name']},</p><br><p>
				Click this link to reset your BEAT password: <a href='{$reset_link}'>reset link</a></p><br><p>
				Sincerely,<br>
				BEAT team</p>";
				$message .= '</body></html>';
			
				$from = $CONFIG['EMAIL_FROM_DEFAULT'];
			
				$headers = "From: " . $from . "\r\n";
				$headers .= "Reply-To: ". $from . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				mail($to,$subject,$message,$headers);

				$this->assign("msg","Your reset password has been sent to your email.");
			}else{
				$this->assign("msg","Your email is not registered.");
			}

		}

		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/forgotpassword.html');
	}

	function reset_password(){
		global $CONFIG,$logger,$EMAIL;
		
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign('basedomain',$basedomain);

		$hash = strip_tags($this->_g('hash'));	
		$email = explode('_',strip_tags($this->_g('email')));
		
		if($hash){
			$getuseremail = $this->passwordHelper->getuseremail($email[1]);
			$hashemail="Beatr3s3t3m4il";
				
			$newHash = sha1($hashemail.$getuseremail['email']);

			if($newHash==$hash){
				$newpassword = $this->passwordHelper->newpassword($email[1]);

				if($newpassword){

					$to = $getuseremail['email'];
					//$to = 'cendiqkrn@gmail.com';
					
					$subject = "Beat - Reset Password";
					
					$message = '<html><body>';
					$message .= "<p>Hi {$getuseremail['name']} {$getuseremail['last_name']},</p><br><p>
					Your BEAT password has been reset to {$newpassword}</p><br><p>
					Please login with this password and change it to something you will remember.</p><br><p>
					Sincerely,<br>
					BEAT team</p>";
					$message .= '</body></html>';
				
					$from = $CONFIG['EMAIL_FROM_DEFAULT'];
				
					$headers = "From: " . $from . "\r\n";
					$headers .= "Reply-To: ". $from . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

					mail($to,$subject,$message,$headers);

					$this->assign("msg","Please check your email to get a new password.");
					return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/login.html');
				}
			}
		}
	}
}
?>