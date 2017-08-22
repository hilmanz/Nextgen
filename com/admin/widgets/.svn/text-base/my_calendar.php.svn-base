<?php
class my_calendar{
	
	function __construct($apps=null){		
			$this->apps = $apps;
			global $LOCALE,$CONFIG;
			$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
			$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
			$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
			
	}

	function main(){
		$this->apps->assign('user',$this->apps->user);
		$data = $this->apps->contentHelper->getMyCalendar($this->apps->user->id);
	
		$this->apps->assign('my_calendar',$data);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/my-calendar.html");
	}


}


?>