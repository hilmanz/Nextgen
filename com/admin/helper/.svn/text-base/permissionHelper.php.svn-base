<?php 

class permissionHelper {


	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;

		$this->apps = $apps;
		$this->uid = 0;
		if($this->apps->isUserOnline())  {
			if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
			
		}		
			
		
		$this->dbshema = "admin";
		
	}
	
	function getPermission(){
		global $LOCALE;
		$data['result'] = false;
		$data['message'] = $LOCALE[1]['unauthorizearea'];
		
		$pages = strip_tags($this->apps->_request('page'));
		$acts = strip_tags($this->apps->_request('act'));
		
		$pagetypes = intval(@$this->apps->user->leaderdetail->type);
		
		/* agency type */
		if($pagetypes==666) {
				$data['result'] = true;
				$data['message'] = " welcome ";
		}
		if($pagetypes==0) return $data;
		
		
		$sql  = "
		SELECT COUNT(*) total 
		FROM admin_pages_permission perm 
		LEFT JOIN admin_pages_modules moderation  ON perm.moduleid = moderation.id
		WHERE perm.pagetype =  {$pagetypes} AND perm.acts='{$acts}' AND moderation.classcall='{$pages}'
		AND perm.n_status = 1 AND  moderation.n_status = 1
		LIMIT 1
		";
	
		// pr($sql);
		$qData = $this->apps->fetch($sql);
		
		if($qData) {
			
			if($qData['total']>0) {
				$data['result'] = true;
				$data['message'] = " welcome ";
				return $data;
			}
			
				
		}
		return $data;
		
	}
	
	
	function getUserListPermission(){
		$sql  = "
		SELECT * FROM admin_roles_type ptypes ";
		$qData = $this->apps->fetch($sql,1);
		
		if($qData) return $qData;
		return false;
		
		
	}
	// admin_pages_permission
	// admin_pages_modules
	function getPermissionList(){
		
		$pagetype = intval($this->apps->_request('uid'));
		
		$sql  = "
		SELECT perm.*,moderation.module,moderation.classcall
		FROM admin_pages_permission perm 
		LEFT JOIN admin_pages_modules moderation  ON perm.moduleid = moderation.id
		WHERE perm.pagetype = {$pagetype}
		";
		// pr($sql);
		$uData = $this->apps->fetch($sql,1);
		$users = false;
		if($uData){
			
			foreach($uData as $val){
				$users[$val['moduleid']."_".$val['acts']]= $val['n_status'];
			}
		
		}
		$sql  = "
		SELECT perm.*,moderation.module,moderation.classcall
		FROM admin_pages_permission perm 
		LEFT JOIN admin_pages_modules moderation  ON perm.moduleid = moderation.id
		GROUP BY moduleid,acts
		";
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData) {
			foreach($qData as $key => $val){
					$qData[$key]['have'] = false;
					if($users){
						if(array_key_exists($val['moduleid']."_".$val['acts'],$users)){
								if($users[$val['moduleid']."_".$val['acts']]==1) $qData[$key]['have'] = true;
						}
					}
			}
			// pr($qData);
			return $qData;
		
		}
		return false;
		
	}
	
	
	function getModules(){
		$sql  = "
		SELECT * FROM admin_pages_modules  	";	
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if($qData) return $qData;
		return false;
		
	}
	
	function addModules(){
		
		if(intval($this->apps->_request('addmodule'))!=1) return false;
		$module = strip_tags($this->apps->_request('module'));
		$classcall = strip_tags($this->apps->_request('classcall'));
		$n_status = intval($this->apps->_request('n_status'));
		
		$sql  = "
		INSERT INTO admin_pages_modules ( module, 	classcall, 	n_status )
		VALUES ( '{$module}','{$classcall}' ,{$n_status} )
		ON DUPLICATE KEY UPDATE module= '{$module}' , classcall='{$classcall}', n_status={$n_status} 
		";
		// pr($sql);exit;
		$qData = $this->apps->query($sql);
		if($this->apps->getLastInsertId()>0) return true;
		return false;
	}
	
	function addPermission(){
		
		if(intval($this->apps->_request('addpermission'))!=1) return false;
		$pagetype = intval($this->apps->_request('pagetype'));
		$moduleid = intval($this->apps->_request('moduleid'));
		$acts = strip_tags($this->apps->_request('acts'));
		$n_status = intval($this->apps->_request('n_status'));
	
		$sql  = "
		INSERT INTO admin_pages_permission ( pagetype,	moduleid ,	acts ,	datetimes, 	n_status )
		VALUES ( '{$pagetype}','{$moduleid}' ,'{$acts}' ,NOW(), {$n_status} )
		ON DUPLICATE KEY UPDATE pagetype= '{$pagetype}' , moduleid='{$moduleid}', acts='{$acts}' , datetimes=NOW() , n_status={$n_status} 
		";
		// pr($sql);exit;
		$qData = $this->apps->query($sql);
		if($this->apps->getLastInsertId()>0) return true;
		return false;
	
	}
}
?>