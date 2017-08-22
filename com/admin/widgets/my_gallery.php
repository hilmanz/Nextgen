<?php
class my_gallery{
	
	function __construct($apps=null){
		$this->apps = $apps;
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
		$this->apps->assign('pages', strip_tags($this->apps->_g('page')));
	}

	function main(){
		$data = $this->apps->contentHelper->getMygallery();		
		$this->apps->assign('my_gallery',$data['result']);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/my-gallery.html");
	}
}
?>