<?php
class register extends App{
		
	function beforeFilter(){
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->permissionHelper = $this->useHelper('permissionHelper');

		$this->searchHelper = $this->useHelper('searchHelper');
		$this->registerHelper = $this->useHelper('registerHelper');
	
	}
	
	function main(){
	
		 $res = $this->registerHelper->userlists();

		$this->assign('users',$res); 
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/register-user-lists.html');
	}
	
	function hirarkies(){
	
		$res = $this->registerHelper->getalluserhirarkies();
		$this->assign('users',$res);
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/register-user-hirarkies.html');
	
	}
	
	function doregister(){
		
		$res = $this->registerHelper->userlists();
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/create-user.html');
	}

	function ajax(){
		$orderType = strip_tags($this->_p('orderType'));
		$orderBy = strip_tags($this->_p('orderBy'));
		$start = strip_tags($this->_p('start'));
		$search = strip_tags($this->_p('search'));
		if($search=="") $search=null;
		$limit = 20;
		$res = $this->registerHelper->userlists($orderBy,$orderType,$start,$limit,$search);
		print json_encode($res);exit;
	}
	
	function edit(){
		
		$res = $this->registerHelper->userlists();
		if($res){
			foreach($res as $key => $val){
				$this->assign($key,$val);
			}
		}
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/edit-user.html');
	}
	
	function unusers(){
		global $CONFIG;
	
		$res = $this->registerHelper->unusers();
		sendRedirect( $CONFIG['ADMIN_DOMAIN']."register");
		exit;
	}
	
	function permission(){
		$res = $this->permissionHelper->getUserListPermission();
		$this->assign('users',$res);
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/register-user-permission-lists.html');
	}
	
	function seepermission(){
		$pagetype = $this->_request('uid');
		$this->assign('pagetype',$pagetype);
		$res = $this->permissionHelper->getPermissionList();
		$this->assign('users',$res);
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/register-user-permission.html');
	}
	
	
	
	function addpermissionuser(){
	
		$modules = $this->permissionHelper->getModules();
		$this->assign('modules',$modules);
		$pagetype = $this->_request('uid');
		$this->assign('pagetype',$pagetype);
		
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/add-permission-user.html');
	}
	

	
	function modulepermissionlist(){
	
		$modules = $this->permissionHelper->getModules();
		$this->assign('modules',$modules);
		
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/module-permission-list.html');
	}
	
	function modulespermissionform(){
	
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/add-modules.html');
	}
	
	function addmodulespermission(){
		global $CONFIG;
		$res = $this->permissionHelper->addModules();
		sendRedirect( $CONFIG['ADMIN_DOMAIN']."register/modulepermissionlist");
		exit;
	}
		
	function addpermission(){
		global $CONFIG;
		$res = $this->permissionHelper->addPermission();
		$pagetype = $this->_request('pagetype');
		// pr($pagetype);exit;
		sendRedirect( $CONFIG['ADMIN_DOMAIN']."register/seepermission/{$pagetype}");
		exit;
	}
	
	function addthispermission(){
		
		$res = $this->permissionHelper->addPermission();
		print json_encode($res);
		exit;
	}


}
?>