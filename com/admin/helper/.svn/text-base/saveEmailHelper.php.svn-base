<?php 

class saveEmailHelper {
	
	function __construct($apps){
		$this->apps = $apps;	
	}
	
	function saveEmail($email,$lang_id=1){
		global $LOCALE;
		if (filter_var($email, FILTER_VALIDATE_EMAIL)){
			$this->apps->open(0);
			$insert = "INSERT INTO axis_email_user (email, dateSave)
						VALUES ('".$email."', NOW())";
			$rs = $this->apps->query($insert);
			$this->apps->close();
			
			if($rs){
				$saveStatus = $LOCALE[$lang_id]['axislife']['email_reg_message_success'];
			}else {
				$saveStatus = $LOCALE[$lang_id]['axislife']['email_reg_message_registered'];;
			}
			
		}else{
			$saveStatus = $LOCALE[$lang_id]['axislife']['email_reg_wrong_format'];;
		}
		
		return $saveStatus;
	}
}	

?>

