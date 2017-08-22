<?php
class upload_pages_music{
	
	function __construct($apps=null){
		$this->apps = $apps;
		global $LOCALE,$CONFIG;
		$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
		global $CONFIG;
		$path = $CONFIG['PUBLIC_ASSET']."music/mp3/";
		$source_path = $path;
		if(intval($this->apps->_p('upload'))==1) {
			if ($_FILES['music']['name']!=NULL) {
				$data = $this->apps->uploadHelper->uploadThisMusic($_FILES['music'],$path);
				$this->apps->assign("msg","Success Upload music");
			} else {
				$data = array('result'=>false,'arrMusic'=> false);
				$this->apps->assign("msg","Upload process failure");
			}
			$this->apps->contentHelper->addUploadMusic($data);
		} else {
			$data = false;
		}
		$this->apps->assign('upload_music',$data);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/upload-pages-music.html");	
	}
}
?>