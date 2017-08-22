<?php
class playlist_songs{
	
	function __construct($apps=null){
		$this->apps = $apps;
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		//$pages = strip_tags($this->apps->_request('page'));
		$playlist = $this->apps->contentHelper->getListSongs();
		$this->apps->assign('playlist',$playlist['result']);
		$this->apps->assign('start',intval($this->apps->_request('start')));
		$this->apps->assign('total',intval($playlist['total']));
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/playlist-songs.html");	
	}
}
?>