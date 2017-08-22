<?php
class upload_pages_image{
	
	function __construct($apps=null){
		$this->apps = $apps;
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		global $CONFIG;
		$path = $CONFIG['PUBLIC_ASSET']."music/images/";
		$source_path = $path;
		if(intval($this->apps->_p('upload'))==1) {
			if ($_FILES['image']['name']!=NULL) {
				$data = $this->apps->uploadHelper->uploadThisImage($_FILES['image'],$path);
				$this->apps->assign("msg","Success Upload Image");
			} else {
				$data = array('result'=>false,'arrImage'=> false);
				$this->apps->assign("msg","Upload process failure");
			}
			$this->apps->contentHelper->addUploadImage($data);
		} else {
			$data = false;
		}
		$this->apps->assign('upload_image',$data);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/upload-pages-image.html");	
	}
}
?>