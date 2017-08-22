<?php
class home extends App{
	
	
	function beforeFilter(){
	
		$this->uCategoryHelper = $this->useHelper("uCategoryHelper");
		$this->contentHelper = $this->useHelper("contentHelper");
		$this->userHelper = $this->useHelper("userHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['DASHBOARD_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
				
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
		$this->assign('badetail',$this->userHelper->getsba());
		$loadCity = $this->contentHelper->loadCity();
		
		$this->assign('getCity',$loadCity);
		$this->assign('pages','home');
			$this->assign('acts', $this->_g('act'));
	}
	
	function main(){

			$this->assign('uid',strip_tags($this->_g('uid')));
			$this->assign('areaid',strip_tags($this->_g('areaid')));
			$this->assign('startdate',strip_tags($this->_g('startdate')));
			$this->assign('enddate',strip_tags($this->_g('enddate')));
			
			$totalquery =  $this->userHelper->getgamestrackall();
			// pr($totalquery);
			$this->assign('totalentourage',intval($totalquery['totalunique']));
			$this->assign('totalplay',intval($totalquery['totalplay'])); 
			$this->assign('totalwin',intval($totalquery['totalwin']));
			$this->assign('totallose',intval($totalquery['totallose']));
			
			$games = $this->userHelper->getgamestrack();
			$this->assign('games',$games);
		
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		
			if(strip_tags($this->_g('download'))=='report'){
			
				$this->callsheader();
			
			}
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/break-doubt.html');
		
	}
	
	function entourage(){
			$this->assign('uid',strip_tags($this->_g('uid')));
			$this->assign('areaid',strip_tags($this->_g('areaid')));
			$this->assign('startdate',strip_tags($this->_g('startdate')));
			$this->assign('enddate',strip_tags($this->_g('enddate')));
			
			$totalquery =  $this->userHelper->getgamestrackall();
			// pr($totalquery);
			$this->assign('totalentourage',intval($totalquery['totalunique']));
			$this->assign('totalplay',intval($totalquery['totalplay'])); 
			$this->assign('totalwin',intval($totalquery['totalwin']));
			$this->assign('totallose',intval($totalquery['totallose']));
			
			$games = $this->userHelper->getgamestrackentourage();
			$this->assign('games',$games);
		if(strip_tags($this->_g('page'))=='home') $this->log('surf','home');
		return $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/find-what-missing.html');		
	}
	
	 
	function callsheader($file='download-data'){
		 
		
		$filename = $file.date('Ymd_gia').".xls";
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename");  
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); 
		print $this->View->toString(TEMPLATE_DOMAIN_DASHBOARD .'apps/downloaddata.html');
		exit;
	
	}
	
}
?>