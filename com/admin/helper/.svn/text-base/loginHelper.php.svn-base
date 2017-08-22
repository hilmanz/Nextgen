<?php

class loginHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
	 
		$this->config = $CONFIG;
		if( $this->apps->session->getSession($this->config['SESSION_NAME'],"admin") ){
			
			$this->login = true;
		
		}
		$this->schema = "admin";
		$this->topclass = array(100,4,6);
	}
	
	function checkLogin(){
		
		return $this->login;
	}
		
	/* below function used as local login only: on development phase without MOP */
	
	function loginSession($web=false){
		$ok = false;
		// pr($this->apps->_p('login'));
		if($this->apps->_p('login')==1){
			$result = $this->goLogin($web);
			return $result;
			
		}		
		return false;
	}
	
	
	
	function goLogin($web=false){
		global $logger, $APP_PATH,$LOCALE;
		
		$mopfirst = false;/* jadiin true klo mop yang kemaren error uda jalan lagi */
		
		$username =  trim(strip_tags($this->apps->_request('username')));
		$password = trim(strip_tags($this->apps->_request('password')));
		 
			$sql = "SELECT sm.* FROM admin_users sm LEFT JOIN admin_roles pages ON pages.userid=sm.id WHERE sm.username=\"{$username}\"  LIMIT 1";
	 
		$rs = $this->apps->fetch($sql);
		$logger->log($sql);
		// pr($sql);exit;
		//check password and phonumber , each field must be same of input value (higher security)
 		 
			
			$hash = sha1($password.$rs['salt']);
			if($rs['n_status']!=1) {
				$result['result'] = false;
				$result['code'] = $rs['n_status'];
				if($rs['n_status']==9) $result['message'] = $LOCALE[1]['blockingreason'];
				else $result['message'] = $LOCALE[1]['notactivated'];				
				return $result;
			}
			if($rs['password']==$hash){
			
				$this->setdatasessionuser($rs);
				
				$logger->log('Able to login BEAT');
				$crdata = "";
				$crdmpst = intval($this->apps->_p('firstlogin'));
				if($crdmpst==1){
					$crdmp = $this->getcrdmp();
					if($crdmp){
						$crdata = $crdmp['name']."{".$crdmp['admin']."}";
					}
				}
				
				$this->login = true;
				$result['result'] = true;
				$result['code'] = $rs['n_status'];
				$result['message'] = " welcome ";
				$result['crdmp'] = $crdata;
				return $result;
				
			}else {
			
				$this->add_try_login($rs);
				$logger->log("Not able to login BEAT ");
				$result['result'] = false;
				$result['code'] = 2;
				$result['message'] = $LOCALE[1]['wrongloginpasswordandusername']; 
				return $result;
			}
		 
	}
	
	function getcrdmp(){
		$sql = "SELECT * FROM gm_admin LIMIT 1";
		$rs = $this->apps->fetch($sql);
		return $rs;
		
	}
	  
	
	function setdatasessionuser($rs=false){
		if(!$rs) return false;
		$rs['pageid'] = false;
		$rs['userid'] = false;

		$this->logger->log('can login');
		$id = intval($rs['id']);
		$leaderid = intval($rs['userid']);
		if($rs['login_count']!=0)$this->add_stat_login($id);
		$pagestat = $this->getPagesStat($id,$leaderid);
		$this->reset_try_login($rs);
		if($pagestat)	{
			 
			$rs = array_merge($rs,$pagestat);
		}
			
		// pr($rs);
		$this->apps->session->setSession($this->config['SESSION_NAME'],"admin",$rs);
	
	}
	
	function add_try_login($rs=false){
		
		if($rs==false) return false;	
	
		$sql ="UPDATE admin_users SET last_login=now(),try_to_login=try_to_login+1 WHERE id='{$rs['id']}' LIMIT 1";
		$res = $this->apps->query($sql);
		
		$sql = "SELECT try_to_login FROM admin_users WHERE id='{$rs['id']}' LIMIT 1";
		$res = $this->apps->fetch($sql);
		
		if($res){
			if($res['try_to_login']>4) {
				$sql ="UPDATE admin_users SET n_status=9 WHERE id='{$rs['id']}' LIMIT 1";
				$res = $this->apps->query($sql);
			}
		}
	}
	
	function reset_try_login($rs=false){
		
		if($rs==false) return false;	
	
		$sql ="UPDATE admin_users SET last_login=now(),try_to_login=0 WHERE id='{$rs['id']}' LIMIT 1";
		$res = $this->apps->query($sql);
				
	}
	
	function add_stat_login($user_id){
	
	
		// $sql ="UPDATE admin_users SET last_login=now(),login_count=0 WHERE id={$user_id} LIMIT 1";
		$sql ="UPDATE admin_users SET last_login=now(),login_count=login_count+1 WHERE id={$user_id} LIMIT 1";
		$rs = $this->apps->query($sql);

	
	}
	
	function getProfile(){
	
		$user = json_decode(urldecode64($this->apps->session->getSession($this->config['SESSION_NAME'],"admin")));
		
		return $user;
	
	}
	
	function getPagesStat($user_id=null,$leader_id=null){
		
		if($user_id==null) return false;
		// if($leader_id==null) return false;
		
		$pagedata['leaderdetail']  = false;
		$pagedata['plantypes'] = " OTHERS ";
		$sql = "
		SELECT pages.name, pages.id ,pages.type ,pages.img,pages.userid ,pagetype.name pagetypename, pages.brandid, pages.areaid, pages.brandsubid,pages.city
		FROM admin_roles pages
		LEFT JOIN admin_roles_type pagetype ON pagetype.id=pages.type
		WHERE userid IN ({$user_id}) ";
		
		$data = $this->apps->fetch($sql,1);
		
		$baplan = array(1);
		$cocreation = array(2);
		$brands = array(3,4,5,6);
		$otherplan = array(100);
		
		if($data) {
			foreach($data as $key => $val){
				$pagedata['leaderdetail'] = $val;							
			}
			// pr($pagedata);exit;
			$topclass = false;
			if(in_array($pagedata['leaderdetail']['type'],$this->topclass)) {
				$topclass=true;
				$pagedata['leaderdetail']['topclass'] = true;
			}else{
				$pagedata['leaderdetail']['topclass'] = false;
			}
			
		
			if(in_array($pagedata['leaderdetail']['type'],$baplan))$pagedata['plantypes'] = "BA";
			if(in_array($pagedata['leaderdetail']['type'],$cocreation))$pagedata['plantypes'] = "Co-Creation";
			if(in_array($pagedata['leaderdetail']['type'],$brands))$pagedata['plantypes'] = "Brand";
			if(in_array($pagedata['leaderdetail']['type'],$otherplan))$pagedata['plantypes'] = "Agency";
			
			if($topclass){
				$branddetail = $this->gethirarkidata(4,false,false,true);	
			}else{
				if($pagedata['leaderdetail']['brandid']==0) $branddetail = $this->gethirarkidata(4,false,true);		
				else $branddetail = $this->gethirarkidata($pagedata['leaderdetail']['brandid'],false,true);			
				
			}			
			$pagedata['branddetail'] = $branddetail;	
			
			if($topclass){
				$areadetail = $this->gethirarkidata(5,false,false,true);	
			}else{
				if($pagedata['leaderdetail']['areaid']==0) $areadetail = $this->gethirarkidata(5,false,false,true);	
				else $areadetail = $this->gethirarkidata($pagedata['leaderdetail']['areaid'],false,true);			
				
			}
			
			$pagedata['areadetail'] = $areadetail;	
			
			if($topclass){
					$pldetail = $this->gethirarkidata(2,false,false,true);			
			}else{
				$arrarea = false;
				if($areadetail){				
					foreach($areadetail as $val){
						$arrarea[$val['userid']] = $val['userid'];
					}
				}
				if($arrarea){
					$strarea = implode(',',$arrarea);
					$pldetail = $this->gethirarkidata($strarea,false,false,false,true);			
				}else $pldetail =  $this->gethirarkidata(2,false,false,true);	
								
				
			}
			
			$pagedata['pldetail'] = $pldetail;	
			
			if($topclass){
				
				$badetail =  $this->gethirarkidata(1,false,false,true);	
			}else{
				$arrpl = false;
				
				if($pldetail){				
					foreach($pldetail as $val){
						$arrpl[$val['userid']] = $val['userid'];
					}
				}
				
				if($arrpl){
					$strpl = implode(',',$arrpl);
					
					if($pagedata['leaderdetail']['type']==2) $badetail =  $this->gethirarkidata($user_id );	
					else $badetail = $this->gethirarkidata($strpl);	
// pr($strpl);exit;					
				}else {
					
					$badetail =  $this->gethirarkidata($user_id,false,true);	
				}
			}
			
			$pagedata['badetail'] = $badetail;	
			
			$miniondata = $this->gethirarkidata($user_id);
			$pagedata['miniondetail'] = $miniondata;	
			
			$masterdetail = $this->gethirarkidata($leader_id);			
			$pagedata['masterdetail'] = $masterdetail;	
			
			// pr($pagedata);exit;			
			
			if($pagedata['leaderdetail']['brandsubid']==0)	$brandsubdetail = $this->gethirarkidata(5,false,true);	
			else $brandsubdetail = $this->gethirarkidata($pagedata['leaderdetail']['brandsubid'],false,true);			
			if($brandsubdetail) $pagedata['branddetail'] = @array_merge($pagedata['branddetail'],$brandsubdetail);
			// exit;
		}
						
		return $pagedata;
	}
	
	
	function gethirarkidata($strid=false,$topclass=false,$owner=false,$usingtype=false,$usingareapl=false){
	
		$mesterdata = false;
		if($strid){
			if($strid==0) return false;
			
			if($topclass) $qAll = " WHERE  pages.type <> 1 ";
			else  $qAll = " WHERE userid IN ({$strid}) ";
			
			if($owner){
				 $qAll = " WHERE userid IN ({$strid}) ";
			}
			
			if($usingtype){
				 $qAll = " WHERE pages.type IN ({$strid}) ";
			}	
			if($usingareapl){
				 $qAll = " WHERE pages.areaid IN ({$strid}) AND pages.type=2 ";
			}	
			
			$sql ="
					SELECT CONCAT(sm.name,' ',sm.last_name,' (',pages.name,') ') name, pages.id ,pages.type ,pages.img,pages.userid ,pagetype.name pagetypename
					FROM admin_roles pages
					LEFT JOIN admin_roles_type pagetype ON pagetype.id=pages.type
					LEFT JOIN admin_users sm ON sm.id=pages.userid
					{$qAll} AND sm.n_status IN  (1,99)";
			//pr($sql);exit;
			$qData = $this->apps->fetch($sql,1);
			if($qData){
				foreach($qData as $key => $val){
					$mesterdata[$val['id']] =  $val;
				}
			
			}
		}
		return $mesterdata;
		
	}
	 
	
	function realeaselock(){
			$username = strip_tags($this->apps->_p('username'));
			$sql = "
					UPDATE admin_users SET n_status = 1,try_to_login=0
					WHERE username = '{$username}' LIMIT 1				
			";
			// pr($sql);
			return $this->apps->query($sql);
	}
	
}
