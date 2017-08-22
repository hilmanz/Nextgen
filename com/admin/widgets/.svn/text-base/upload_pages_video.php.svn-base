<?php
class upload_pages_video{
	
	function __construct($apps=null){
		$this->apps = $apps;
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		global $CONFIG;
		$path = $CONFIG['PUBLIC_ASSET']."music/video/";
		$source_path = $path;
		if(intval($this->apps->_p('upload'))==1) {
			$file = $_FILES['video'];
			$size = ROUND($file['size']/1024);
			if($size > 4096) {
				$this->apps->assign("msg","The Uploaded File Was Too Large");
				$data = false;
			} else {
				if ($_FILES['video']['name']!=NULL) {
					$data = $this->apps->uploadHelper->uploadThisVideo($_FILES['video'],$path);
					$this->apps->assign("msg","Success Upload video");
				} else {
					$data = array('result'=>false,'arrVideo'=> false);
					$this->apps->assign("msg","Upload process failure");
				}
				$this->apps->contentHelper->addUploadVideo($data);
			}
		} else {
			$data = false;
		}
		$this->apps->assign('upload_video',$data);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/upload-pages-video.html");	
	}
}
?>