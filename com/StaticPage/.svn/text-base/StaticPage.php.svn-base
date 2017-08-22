<?php
class StaticPage extends SQLData{
	var $strHTML;
	var $View;
	var $tag;
	var $title;
	var $id;
	function StaticPage($req){
		parent::SQLData();
		$this->View = new BasicView();
		$this->Request = $req;
	}
	
	/****** End-User FUNCTIONS ********************************************/
	function getPage($id){
		$rs = $this->fetch("SELECT * FROM gm_static_page 
											   WHERE id='".$id."'");
        
		if(!is_array($rs)){
			return "Page not found.";
		}
		$this->View->assign("rs",$rs);
		$this->id = $rs['id'];
		$this->tag = $rs['tag'];
		$this->title = $rs['title'];
		$strHTML = $this->View->toString("StaticPage/default.html");
        
		return $strHTML;
	}
	function getRootID($id){
		$rs = $this->fetch("SELECT * FROM gm_static_page WHERE id='".$id."'");
		if($rs['parentID']!=0){
			$rs = $this->getRootID($rs['parentID']);
		}
		return $rs;
	}
	function getRootIDByTag($tag){
		$rs = $this->fetch("SELECT * FROM gm_static_page WHERE tag='".$tag."' LIMIT 1");
		if($rs['parentID']!=0){
			$rs = $this->getRootID($rs['parentID']);
		}
		return $rs;
	}
	function getParentID($id){
		$rs = $this->fetch("SELECT parentID FROM gm_static_page WHERE id='".$id."'");
		return $rs['parentID'];
	}
	function getContentByTag($tag){
		return $this->fetch("SELECT * FROM gm_static_page WHERE tag LIKE '%".$tag."%' LIMIT 1");
	}
	function getXMLByTag($tag){
		$id = $this->Request->getParam("id");
		if($id!=NULL){
			$list = $this->fetch("SELECT * FROM gm_static_page WHERE tag LIKE '%".$tag."%' AND id <> '".$id."'",1);
		}else{
			$list = $this->fetch("SELECT * FROM gm_static_page WHERE tag LIKE '%".$tag."%'",1);
		}
		$this->View->assign("list",$list);
		$this->View->assign("tag",$tag);
		return $this->View->toString("StaticPage/xml/template.xml");
	}
	function getXMLByGroup($groupID){
		$id = $this->Request->getParam("id");
		if($id!=NULL){
			$list = $this->fetch("SELECT * FROM gm_static_page WHERE groupID = '".$groupID."' AND id <> '".$id."'",1);
		}else{
			$list = $this->fetch("SELECT * FROM gm_static_page WHERE groupID = '".$groupID."'",1);
		}
		$this->View->assign("list",$list);
		//$this->View->assign("tag",$list['tag']);
		return $this->View->toString("StaticPage/xml/template.xml");
	}
	function getContentByID($id){
		return $this->fetch("SELECT * FROM gm_static_page WHERE id='".$id."' LIMIT 1");
	}
	function getPageByTag($tag){
		//$this->View->assign("id",$id);
		$rs = $this->fetch("SELECT * FROM gm_static_page 
											   WHERE tag LIKE '%".$tag."%' LIMIT 1");
		if(!is_array($rs)){
			return "<p>Page not found.</p>";
		}
		$this->View->assign("rs",$rs);
		$this->tag = $rs['tag '];
		$this->title = $rs['title'];
		$strHTML = $this->View->toString("StaticPage/default.html");
		return $strHTML;
	}
    /*
	function getChildrens($id){
		return $this->fetch("SELECT * FROM gm_static_page WHERE parentID='".$id."'",1);
	}
     * 
     */
	function getPageByGroup($groupID){
		//$this->View->assign("id",$id);
		$rs = $this->fetch("SELECT * FROM gm_static_page 
											   WHERE groupID='".$groupID."' LIMIT 1");
		if(!is_array($rs)){
			return "<p>Page not found.</p>";
		}
		$this->View->assign("rs",$rs);
		$this->tag = $rs['tag '];
		$this->title = $rs['title'];
		$strHTML = $this->View->toString("StaticPage/default.html");
		return $strHTML;
	}
	/****** ADMIN FUNCTIONS ********************************************/
	function admin(){
		if($this->Request->getPost("r")=="add"){
			return $this->saveContent();
		}else if($this->Request->getPost("r")=="update"){
			return $this->updateContent();
		}else if($this->Request->getPost("r")=="add_group"){
			return $this->saveGroup();
		}else if($this->Request->getPost("r")=="update_group"){
			return $this->updateGroup();
		}else if($this->Request->getParam("r")=="new"){
			return $this->showNewPage();
		}else if($this->Request->getParam("r")=="edit"){
			return $this->showEditPage();
		}else if($this->Request->getParam("r")=="delete"){
			return $this->performDeletePage();
		}else if($this->Request->getParam("r")=="list"){
			return $this->showPageListByGroup();
		}else if($this->Request->getParam("r")=="edit_group"){
			return $this->showEditGroup();
		}else if($this->Request->getParam("r")=="new_group"){
			return $this->showNewGroupPage();
		}else if($this->Request->getParam("r")=="group"){
			return $this->showGroupList();
		}else if($this->Request->getParam("r")=="group"){
			return $this->showGroupList();
		}else{
			return $this->showManagePage();
		}
	}
	function performDeletePage(){
		$id = $this->Request->getParam("id");
		if($this->query("DELETE FROM gm_static_page WHERE id='".$id."'")){
			$msg = "The page has been deleted successfully !";	
		}else{
			$msg = "Cannot delete the page, please try again later!";
		}
		return $this->View->showMessage($msg,"?s=page");
	}
	function saveTags($tags){
		$foo = explode(",",$tags);
		$n = sizeof($foo);
		for($i=0;$i<$n;$i++){
			@$this->query("INSERT INTO gm_tags(tag) VALUES('".trim($foo[$i])."')");
		}
	}
	function getTags($start=0,$total=20){
		return $this->fetch("SELECT * FROM gm_tags LIMIT ".$start.",".$total,1);
	}
	function showPageListByGroup(){
		$groupID = $this->Request->getParam("groupID");
		$rs = $this->fetch("SELECT * FROM gm_static_page_group WHERE groupID='".$groupID."' LIMIT 1");
		$this->View->assign("rs",$rs);
		//view list
		$list = $this->fetch("SELECT * FROM gm_static_page a,gm_static_page_group b 
							   WHERE a.groupID = b.groupID AND a.groupID='".$groupID."' 
							   ORDER BY id",1);
		$this->View->assign("list",$list);
		$this->View->assign("ADMIN_CONTENT",
		$this->View->toString("StaticPage/admin/list_by_group.html"));
		return $this->View->toString("StaticPage/admin/manage.html");
	}
	function updateContent(){
		//save to db
		$groupID = $this->Request->getPost("groupID");
		$title = $this->Request->getPost("title");
		$brief = $this->Request->getPost("brief");
		$detail = $this->Request->getPost("detail");
		$detail = str_replace("../contents/images/","contents/images/",$detail);
		$status = 1;
		$id = $this->Request->getPost("id");
		$tag = $this->Request->getPost("tag");
		$this->saveTags($tag);
		if($this->query("UPDATE gm_static_page SET 
						 groupID='".$groupID."',title='".$title."',brief='".$brief."',
						 detail='".$detail."',status='".$status."',tag='".$tag."'
						 WHERE id='".$id."'")){
			$msg = "The page has been updated successfully !";
		}else{
			$msg = "cannot update the page, please try again !";
		}
		return $this->View->showMessage($msg,"?s=page");
	}
	function showEditPage(){
		$id = $this->Request->getParam("id");
		$rs = $this->fetch("SELECT * FROM gm_static_page WHERE id='".$id."' LIMIT 1");
		$rs['detail'] = str_replace("contents/images/","../contents/images/",$rs['detail']);
		$groups = $this->fetch("SELECT * FROM gm_static_page_group ORDER BY groupID",1);
		$this->View->assign("groups",$groups);
		$this->View->assign("rs",$rs);
		return $this->View->toString("StaticPage/admin/edit.html");
	}
	function showEditGroup(){
		$id = $this->Request->getParam("groupID");
		$rs = $this->fetch("SELECT * FROM gm_static_page_group
							WHERE groupID='".$id."' LIMIT 1");
		$this->View->assign("rs",$rs);
		return $this->View->toString("StaticPage/admin/group_edit.html");
	}
	function saveGroup(){
		$groupName = $this->Request->getPost("groupName");
		if($this->query("INSERT INTO gm_static_page_group(groupName) 
						 VALUES('$groupName')")){
			$msg = "The group has been created successfully !";
		}else{
			$msg = "Cannot create a group, please try again later !";
		}
		
		return $this->View->showMessage($msg,"?s=page&r=group");
	}
	function updateGroup(){
		$groupName = $this->Request->getPost("groupName");
		$groupID = $this->Request->getPost("groupID");
		if($this->query("UPDATE gm_static_page_group SET groupName='".$groupName."'
						 WHERE groupID='".$groupID."'")){
			$msg = "The group has been updated successfully !";
		}else{
			$msg = "Cannot update the group, please try again later !";
		}
		
		return $this->View->showMessage($msg,"?s=page&r=group");
	}
	
	function showGroupList(){
		$list = $this->fetch("SELECT * FROM gm_static_page_group ORDER BY groupName",1);
		$n = sizeof($list);
		for($i=0;$i<$n;$i++){
			$groupID = $list[$i]['groupID'];
			$foo = $this->fetch("SELECT COUNT(*) as total FROM gm_static_page WHERE groupID='".$groupID."'");
			$list[$i]['total'] = $foo['total'];
		}
		
		$this->View->assign("list",$list);
		$this->View->assign("ADMIN_CONTENT",
		$this->View->toString("StaticPage/admin/group_list.html"));
		return $this->View->toString("StaticPage/admin/manage.html");
		
	}
	function saveContent(){
		//upload file
		if(!is_dir("../contents/static/")){
			mkdir("../contents/static/");
		}
		if($_FILES['file']['tmp_name']!=NULL){
			$filename = date("Ymdhis").str_replace(" ","_",$_FILES['file']['name']);
			if(!copy($_FILES['file']['tmp_name'],"../contents/static/".$filename)){
				$msg = "Cannot upload the file, please try again !";
				return $this->View->showMessage($msg,"?s=page&r=new");
			}
		}
		//--->
		
		//save to db
		$groupID = $this->Request->getPost("groupID");
		$title = $this->Request->getPost("title");
		$brief = $this->Request->getPost("brief");
		$detail = $this->Request->getPost("detail");
		$parentID = $this->Request->getPost("parentID");
		$detail = str_replace("../contents/images/","contents/images/",$detail);
		$status = 1;
		$tag = $this->Request->getPost("tag");
		$this->saveTags($tag);
		if($this->query("INSERT INTO gm_static_page(groupID,title,brief,detail,posted,tag,img,status,parentID)
						 VALUES('".$groupID."','".$title."','".$brief."','".$detail."',NOW(),
						 		 '".$tag."','".$filename."','".$status."','".$parentID."')")){
			$msg = "New content has been added successfully !";			 
		}else{
			$msg = "Sorry, we cannot add new content, please try again !";
		}
		
		return $this->View->showMessage($msg,"?s=page");
	}
	function showNewGroupPage(){
		return $this->View->toString("StaticPage/admin/group_new.html");
	}
	function showNewPage(){
		$parentID = $this->Request->getParam("parentID");
		if($parentID!=NULL){
			$parent = $this->fetch("SELECT * FROM gm_static_page WHERE id='".$parentID."' LIMIT 1");
			$this->View->assign("parent",$parent);
		}
		$groups = $this->fetch("SELECT * FROM gm_static_page_group ORDER BY groupID",1);
		$this->View->assign("groups",$groups);
		
		//$this->View->assign("ADMIN_CONTENT",
		//$this->View->toString("StaticPage/admin/new.html"));
		return $this->View->toString("StaticPage/admin/new.html");
	}
	function toTree($list,$t=0){
		$foo = array();
		$n = sizeof($list);
		for($i=0;$i<$n;$i++){
			
			$list[$i]['title'] = str_pad($list[$i]['title'],
								 strlen($list[$i]['title'])+($t*36),"&nbsp;",
								 STR_PAD_LEFT);
			$list[$i]['level'] = $t+1;
			array_push($foo,$list[$i]);
			$child = $this->getChildrens($list[$i]['id']);
			//print $foo;
			$foo = array_merge($foo,$this->toTree($child,$t+1));
		}
		return $foo;
	}

    function setRootPageByTag($tag){
		$this->rootTag = $tag;
		$rs = $this->fetch("SELECT id FROM gm_static_page WHERE tag LIKE '%".$tag."%' AND parentID = '0' LIMIT 1");
		$this->rootID = $rs['id'];

	}
    function getChildrens($id,$f=true){
		if($f){
			$rs = $this->fetch("SELECT * FROM gm_static_page WHERE parentID='".$id."' AND status='1'",1);
		}else{
			$rs = $this->fetch("SELECT * FROM gm_static_page WHERE parentID='".$id."'",1);
		}

		for($i=0;$i<sizeof($rs);$i++){
			if(strlen($rs[$i]['detail'])==0){
				$rs[$i]['isEmpty']=1;
			}

		}
		return $rs;
	}
    
	function getPagesByParentID($id){
		return $this->fetch("SELECT * FROM gm_static_page a,gm_static_page_group b 
							  WHERE parentID='".$id."' AND a.groupID = b.groupID 
							  ORDER BY b.groupName,a.id",1);
	}
	function showManagePage(){
		//view list
		if($this->Request->getParam("tag")==NULL){
			$list = $this->fetch("SELECT * FROM gm_static_page a,gm_static_page_group b 
								WHERE parentID='0' AND a.groupID = b.groupID ORDER BY b.groupName,a.id",1);
		}else{
			$list = $this->fetch("SELECT * FROM gm_static_page a,gm_static_page_group b 
								WHERE parentID='0' AND a.groupID = b.groupID AND tag LIKE '%".$this->Request->getParam("tag")."%' 
								ORDER BY a.groupID,a.id",1);
		}		
		$this->View->assign("tags",$this->getTags());
		$this->View->assign("list",$this->toTree($list));
		$this->View->assign("ADMIN_CONTENT",
		$this->View->toString("StaticPage/admin/list.html"));
		return $this->View->toString("StaticPage/admin/manage.html");
	}
	
	function addon(){
		return array("CONTENT_TITLE"=>$this->title);
	}
	
}
?>