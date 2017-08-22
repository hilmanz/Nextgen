<?php 
class FileUploader{
	var $upload_dir;
	var $callback;
	var $params;
	var $url;
	var $View;
	var $swf;
	var $id;
	var $err_msg;
	var $name;
	function FileUploader($s="Uploader"){
		$this->View = new BasicView();
		$this->name = $s;
	}
	function setServiceURI($str){
		$this->url = $str;
	}
	function setSWF($str){
		$this->swf = $str;
	}
	function setUploadDir($name){
		$this->upload_dir = $name;
	}
	function setParams($arr){
		$this->params = $arr;
	}
	function getFlashVars(){
		$flashvars = "";
		foreach($this->params as $name=>$val){
			$flashvars.=$name."=".urlencode($val)."&";
		}
		return $flashvars;
	}
	function html($id){
		$this->View->assign("FU_SWF",$this->swf);
		$this->View->assign("FU_FLASHVARS",$this->getFlashVars());
		$this->View->assign("FU_NAME",$this->name);
		$this->View->assign("target",$id);
		return $this->View->toString("utility/fileuploader.html");
	}
	function save($ext=".jpg"){
		global $UPLOAD_DIRS;
		if($_POST['channel']==null){$_POST['channel']=0;}
		$upload_dir= $UPLOAD_DIRS[$_POST['channel']];
		if(move_uploaded_file($_FILES['Filedata']['tmp_name'],$upload_dir.$_POST['target'].".".$ext)){
			return 1;
		}else{
			$this->err_msg = "Error -->".$upload_dir.$_POST['target'].".".$ext;
			return 0;
		}
	}
}
?>