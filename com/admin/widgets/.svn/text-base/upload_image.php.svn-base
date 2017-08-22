<?php
class upload_image{
	
	function __construct($apps=null){
		$this->apps = $apps;
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		global $CONFIG;
		$path = $CONFIG['PUBLIC_ASSET']."user/photo/";
		$source_path = $path;
		if($this->apps->_p('upload')==1)$data = $this->apps->uploadHelper->uploadThisImage($_FILES['image'],$path);
		else $data = false;
		$this->apps->assign('upload_image',$data);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/upload-image.html");	
	}
}
?>