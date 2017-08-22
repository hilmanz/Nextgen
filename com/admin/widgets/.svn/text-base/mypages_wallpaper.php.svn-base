<?php
class mypages_wallpaper{
	
	function __construct($apps=null){
		$this->apps = $apps;
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
		$this->apps->assign('user',$this->apps->user);
	}

	function main(){
		$data = $this->apps->wallpaperHelper->getPagesWallpaper();
		$pid = intval($this->apps->_request('pid'));
		$id_user = $this->apps->user->id;
		
		$this->apps->assign('pid',$pid);
		$this->apps->assign('id_user',$id_user);
		$this->apps->assign('mypages_wallpaper',$data);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/mypages-wallpaper.html");	
	}
}
?>