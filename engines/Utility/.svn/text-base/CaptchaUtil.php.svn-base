<?php
require_once('reCaptcha/recaptchalib.php');
class CaptchaUtil{
	var $_public_key;
	var $_private_key;
	function CaptchaUtil($public_key,$private_key){
		$this->_public_key = $public_key;
		$this->_private_key = $private_key;
	}
	/**
	 * generate the captcha code
	 * @return string
	 */
	function getHtml(){
		return recaptcha_get_html($this->getPublicKey(),null,true);
	}
	/**
	 * 
	 * verify the captcha
	 * @return boolean
	 */
	function verify(){
	 $privatekey = $this->getPrivateKey();
  	 $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

      if (!$resp->is_valid) {
        // What happens when the CAPTCHA was entered incorrectly
        //die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
        // "(reCAPTCHA said: " . $resp->error . ")");
        return false;
      } else {
         // Your code here to handle a successful verification
         return true;
       }
	}
	function getPublicKey(){
		return $this->_public_key;
	}
	function getPrivateKey(){
		return $this->_private_key;
	}
}
?>