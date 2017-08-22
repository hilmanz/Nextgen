<?php
class acquisition{
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
		$this->apps->assign('user',$this->apps->user);
	}

	function main(){
		$start = intval($this->apps->_p('start'));
	 
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/acquisition-chart.html");
	}
	
}
?>