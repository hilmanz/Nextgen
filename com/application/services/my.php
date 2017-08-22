<?php
class my  extends ServiceAPI{
	
	function beforeFilter(){
		 
		$this->userHelper  = $this->useHelper('userHelper');
		 
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);		
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));
		$this->assign('tokenize',gettokenize(5000*60,$this->user->id));
	}
	
	function profile(){
		$user = $this->userHelper->getUserProfile(); 
 
		$data['profile'] = $user;
		  
		return $data;		
		
	}
	 
	
	function branddetail(){
		$datas['result'] = false;
		$datas['data'] = array();
		
		$data = $this->userHelper->getBrand();
		if($data){
			$datas['result'] = true;
			$datas['data'] = $data;
		}
		return $datas;
	}
	 
}
?>