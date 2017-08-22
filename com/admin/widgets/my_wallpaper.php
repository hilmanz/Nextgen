<?php
class my_wallpaper{
	
	function __construct($apps=null){
		$this->apps = $apps;
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
		$this->apps->assign('user',$this->apps->user);
	}

	function main(){
		$data = $this->apps->wallpaperHelper->getWallpaper();
		// pr($data);
		$this->apps->assign('my_wallpaper',$data);		
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/my-wallpaper.html");	
	}
}
?>