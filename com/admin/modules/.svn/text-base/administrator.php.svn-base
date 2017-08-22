<?php
class administrator extends App{
		
	function beforeFilter(){
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']); 
		$this->permissionHelper = $this->useHelper('permissionHelper');
		$this->cpmooHelper = $this->useHelper('cpmooHelper');

		$this->searchHelper = $this->useHelper('searchHelper');
		$this->administrationHelper = $this->useHelper('administrationHelper');
		$roles = $this->administrationHelper->getRoles();
		$this->assign('roles',$roles); 
	}
	
	function main(){
	
		 $res = $this->administrationHelper->userlists();

		$this->assign('users',$res); 
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/administrator-home.html');
	}
	
	
	function users(){
	
		 $res = $this->administrationHelper->userlists();

		$this->assign('users',$res); 
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/administrator-user-lists.html');
	}
	 
	function roles(){
	
	 
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/administrator-roles-lists.html');
	}
	
	function addroles(){
		global $CONFIG;
		$addroles  =intval($this->_p('addroles'));
		if($addroles==1){
			$res = $this->administrationHelper->addRoles();
			print json_encode($res);exit;
		}else{
	 
			return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/create-user-roles.html');
		}
	}
	
	function doregister(){
		global $CONFIG;
		$doregistration  =intval($this->_p('register'));
		if($doregistration==1){
			$res = $this->administrationHelper->doRegister();
			print json_encode($res);exit;
		}else{
			
			return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/create-user.html');
		}
	}

	function ajax(){
		$orderType = strip_tags($this->_p('orderType'));
		$orderBy = strip_tags($this->_p('orderBy'));
		$start = strip_tags($this->_p('start'));
		$search = strip_tags($this->_p('search'));
		if($search=="") $search=null;
		$limit = 20;
		$res = $this->administrationHelper->userlists($orderBy,$orderType,$start,$limit,$search);
		print json_encode($res);exit;
	}
	
	function edit(){
		
		$res = $this->administrationHelper->userlists();
		if($res){
			foreach($res as $key => $val){
				$this->assign($key,$val);
			}
		}
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/edit-user.html');
	}
	
	function unusers(){
		global $CONFIG;
	
		$res = $this->administrationHelper->unusers();
		sendRedirect( $CONFIG['ADMIN_DOMAIN']."administrator/users");
		exit;
	}
	
	 
	
	function seepermission(){
		$pagetype = $this->_request('uid');
		$this->assign('pagetype',$pagetype);
		$res = $this->permissionHelper->getPermissionList();
		$this->assign('users',$res);
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/administrator-user-permission.html');
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
	 
		$res = $this->permissionHelper->addModules();
		print json_encode($res);
		exit; 
	}
		
	function addpermission(){
	 
		$res = $this->permissionHelper->addPermission();
		$pagetype = $this->_request('pagetype');
		if($res) $res = " success to create new permission ";
		else $res = " failed to create new permission ";
		print json_encode($res);
		exit;
		 
	}
	
	function addthispermission(){
		
		$res = $this->permissionHelper->addPermission();
		if($res) $res = " success to create new permission ";
		else $res = " failed to create new permission ";
		print json_encode($res);
		exit;
	}

	function cpmoopages(){
		
		$res = $this->cpmooHelper->
			$this->assign('users',$res);
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/administrator-user-permission.html');
	}
}
?>