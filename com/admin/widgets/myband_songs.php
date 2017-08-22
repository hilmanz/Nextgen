<?php
class myband_songs{
	
	function __construct($apps=null){		
			$this->apps = $apps;
			global $LOCALE,$CONFIG;
			$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
			$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
			$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		/*$pages = strip_tags($this->apps->_request('page'));
		$start = intval($this->apps->_request('start'));
		if($pages=='my') $maxrecord = 9;
		else $maxrecord = 4;
		$this->apps->assign('my_favorite',$this->apps->contentHelper->getFavorite($start));*/
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/myband-songs.html");
	
	}


}


?>