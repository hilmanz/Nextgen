<?php
class synch  extends ServiceAPI{
	
	function beforeFilter(){
		$this->BEATServHelper = $this->useHelper('BEATServHelper');
	 
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);		
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));
		$this->assign('tokenize',gettokenize(5000*60,$this->user->id));
	}
	
	 
	function offlinebadges(){
				 
		return $this->BEATServHelper->offlinebadges();
	
		
	}
}
?>