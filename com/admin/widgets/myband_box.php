<?php
class myband_box {
	
	function __construct($apps=null){
		$this->apps = $apps;	
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
		
		$this->apps->assign('user',$this->apps->user);
	}

	function main(){
		$data = $this->apps->bandHelper->getBandProfile();
		if(!$data)	return false;
		
		$genre = $this->apps->bandHelper->genre();
		$memberlist = $this->apps->bandHelper->getMember();
		$city = $this->apps->contentHelper->getCity();
		
		foreach ($memberlist['result'] as $val) {
			$memberPages[] = $val;
		}
		//pr($genre);
		$this->apps->View->assign('bandprofile',$data);
		$this->apps->View->assign('memberlist',$memberPages);
		$this->apps->View->assign('city',$city);
		$this->apps->View->assign('genre_band',$genre['band']);
		$this->apps->View->assign('genre_dj',$genre['dj']);
		$this->apps->View->assign('user',$this->apps->user);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/myband-box.html");	
	}
}
?>