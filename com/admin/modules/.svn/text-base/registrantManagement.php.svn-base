<?php
class registrantManagement extends App{

	function beforeFilter(){
		global $LOCALE,$CONFIG;
		
		$this->listuserHelper = $this->useHelper('listuserHelper');
		
		$this->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));
		$this->assign('user',$this->user);
	}
	
	function main(){
		$registrantList = $this->listuserHelper->registrantList($start=null,$limit=10);
		$time['time'] = '%H:%M:%S';
		
		$this->assign('total',intval($registrantList));
		$this->assign('list',$registrantList['result']);
		$this->assign('time',$time);
		$this->assign('search',strip_tags($this->_p('search')));
		$this->assign('startdate',$this->_p('startdate'));
		$this->assign('enddate',$this->_p('enddate'));
		$this->log('surf','registrant_management');
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'/apps/registrant-management.html');
	}
	
	function ajaxconfirmed()
	{
		
		$ajax = $this->listuserHelper->ajax();
		// var_dump($ajax);
		
		if ($ajax){ 
			print json_encode(array('status'=>false));
		}else{ 
			print json_encode(array('status'=>true));
		}
		
		exit;
	}
	
}
?>