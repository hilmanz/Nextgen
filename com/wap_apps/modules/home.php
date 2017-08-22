<?php
class home extends App{
	
	
	
	function beforeFilter(){
			global $LOCALE,$CONFIG;
			$this->assign('basedomain', $CONFIG['WAP_DOMAIN']);
			$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WAP']);
			$this->assign('baseurl', $CONFIG['BASE_DOMAIN']);
			$this->assign('locale',$LOCALE[$this->lid]);
			$this->contentHelper = $this->useHelper('contentHelper');
	}
	function main(){
		
		$promo = $this->contentHelper->getPromo(0,1);
		$this->assign('promo',$promo);
		$this->log('globalAction','wap-home');
		return $this->View->toString(TEMPLATE_DOMAIN_WAP .'application/home.html');
	}
	
	
	
}
?>