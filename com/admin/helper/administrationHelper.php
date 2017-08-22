<?php
class administrationHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps=false){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		
		$this->config = $CONFIG;
		
	
	}
	 
	function registerPhase(){
		$ok = false;
		global $CONFIG;
		
		if($this->apps->_p('register')==1){
		
			$this->logger->log('can register');
			$reg = $this->doRegister();
		
			return $reg;
			  
		}
		$this->logger->log('can not register');
		return false;
	}
	
	function doRegister(){
		global $CONFIG;
		$this->logger->log('do register');
		
		$updatepass ='';
	 
		 $notsaved = "not save";
		 $saved = "saved";
		// user stat
		$name = strip_tags($this->apps->_p('name'));
		$last_name = strip_tags($this->apps->_p('lastname'));
		$nickname = strip_tags($this->apps->_p('nickname'));
		
		$password = trim(strip_tags($this->apps->_p('password')));
		$repassword = trim($this->apps->_p('repassword'));
		$nickname = strip_tags($this->apps->_p('name'));
		//if edit
		$edit = $this->apps->_p('edit');
		$email = null;
		$email = trim(strip_tags($this->apps->_p('email')));
		if($edit!=1){
			
			$username = strip_tags($this->apps->_p('username'));
		}
		$sex = trim(strip_tags($this->apps->_p('sex')));
		
		//page stat
		// 	ownerid 	name 	description 	type 	img 	otherid	brandid 	brandsubid 	areaid 	city 	created_date 	closed_date 	n_statu
		$role = strip_tags($this->apps->_p('role'));
		$rolesname ='';
		 $description = strip_tags($this->apps->_p('description'));
		$type =$role;
		if($type!=0){
			$sql =" select name from admin_roles_type WHERE n_status = 1 AND id ='{$type}' LIMIT 1";
			$qData = $this->apps->fetch($sql);
			if($qData){
				$rolesname = $qData['name'];
			}
		}
		$img = strip_tags($this->apps->_p('img'));
		 $created_date = date("Y-m-d H:i:s");
		$closed_date = date("Y-m-d H:i:s");
		$n_status = 1;
		
		if($email==''||$email==null){
			$this->logger->log('form registration not complete.');
			return   "form registration not complete. email not found";
		}
			
		$hashPass = sha1($password.$CONFIG['salt']);
		$sql = "SELECT * FROM admin_users WHERE email='{$email}' LIMIT 1 ";
		$qData = $this->apps->fetch($sql);
		
		if($qData){
			if($email==''||$email==null){
				$this->logger->log('form registration not complete.');
				return  "form registration not complete. email not found";
			}
			if($password!=''||$password!=null){
				if($password!=$repassword) {
					$this->logger->log('pass and re pass not match');
					return 'Please make sure confirm password is same as the password.';
				}else{
					$updatepass=",password='{$hashPass}'";
				}
			}

			$sql = "
				UPDATE admin_users SET name='{$name}',sex='{$sex}',nickname='{$nickname}',last_name='{$last_name}' , n_status = 1,try_to_login=0{$updatepass}
					WHERE id = {$qData['id']} LIMIT 1				
				
			";
			//pr($sql);exit;
			$this->apps->query($sql);
			$sql = "SELECT userid FROM admin_roles WHERE userid={$qData['id']} LIMIT 1 ";
			$rqData = $this->apps->fetch($sql);
			
			if($rqData){
				$dataupdate = false;
				if($rolesname!='') $dataupdate[] = "name='{$rolesname}'";
				if($type!='') $dataupdate[] = "type='{$type}'";
				if($img!='') $dataupdate[] = "img='{$img}'"; 
				$qUpdateData = "";
				if($dataupdate){
					$qUpdateData = implode(',',$dataupdate);
				}else return $saved;
				



				$sql = "
						UPDATE admin_roles SET 
						{$qUpdateData} 
						WHERE userid = {$qData['id']} LIMIT 1				
				";
				$this->logger->log($sql);
				$this->apps->query($sql);
				
			 
				return 'Update Success.';
			}else{
				$sql = "
					INSERT INTO admin_roles (userid ,	name, 	description ,	type 	,img	,created_date ,	closed_date,n_status) 
					VALUES ('{$qData['id']}','{$rolesname}','',{$type},'{$img}' ,NOW(),DATE_ADD(NOW(),INTERVAL 5 YEAR),1)
				";
				// pr($sql);
				 $this->apps->query($sql);
					if($this->apps->getLastInsertId()>0)  {
							 
						return 'Update Success.';
					}
			
			}
		}else{
			if($email==''||$email==null||$password==''){
				$this->logger->log('form registration not complete.');
				return   "form registration not complete. email not found";
			}
			if($password!=$repassword) {
				$this->logger->log('pass and re pass not match');
				return 'Please make sure confirm password is same as the password.';
			}

			$sql = "
				INSERT INTO admin_users (password,email,register_date,salt,n_status,name,nickname,username,last_name,sex) 
				VALUES ('{$hashPass}','{$email}',NOW(),'{$CONFIG['salt']}',1,'{$name}','{$nickname}','{$username}','{$last_name}','{$sex}')			
			";
			$this->apps->query($sql);
			$lastID = $this->apps->getLastInsertId();
			if($lastID>0) {
				$sql = "
					INSERT INTO admin_roles (userid ,	name, 	description ,	type 	,img  	,created_date ,	closed_date,n_status) 
					VALUES ('{$lastID}','{$rolesname}','',{$type},'{$img}' ,NOW(),DATE_ADD(NOW(),INTERVAL 5 YEAR),1)
				";
				// pr($sql);exit;
				 $this->apps->query($sql);
					if($this->apps->getLastInsertId()>0)  {
				 
						return  'Registration Complete.';
					}
			}		
		}
 		return  "Failed";
	
	}
	 
	
	
	function userlists($orderBy='name',$orderType='ASC',$start=0,$limit=20,$search=null){
		$searchFilter="";
		if($search){
			$searchFilter="AND (sm.name LIKE '%{$search}%' OR sm.email LIKE '%{$search}%')";
		}

		//create user list per hirarki
		$uid = intval($this->apps->_request('uid'));
		if($uid!=0) $qUsers = " AND sm.id = {$uid} ";
		else  $qUsers = "";
		mysql_query('SET CHARACTER SET utf8');
		$sql  = "
			SELECT 
			sm.id,sm.name, sm.last_name ,sm.email,sm.username,sm.password,sm.birthday ,sm.nickname,sm.sex,
			ptype.name pagename,ptype.id pagetype 
			FROM admin_users sm
			LEFT JOIN admin_roles pages ON pages.userid = sm.id
			LEFT JOIN admin_roles_type ptype ON ptype.id = pages.type 
			 WHERE sm.n_status IN (1,9) {$searchFilter}
			{$qUsers}
			ORDER BY {$orderBy} {$orderType}
			LIMIT {$start},{$limit} ";
		// pr($sql);exit;
		if($uid!=0) {
			$qData = $this->apps->fetch($sql);
			return $qData;
		}else{
		 	$qData = $this->apps->fetch($sql,1);

		 	$sql  = "
					SELECT 
					COUNT(sm.id) AS total
					FROM admin_users sm
					LEFT JOIN admin_roles pages ON pages.ownerid = sm.id
					LEFT JOIN admin_roles_type ptype ON ptype.id = pages.type 
					WHERE sm.n_status IN (1,9) {$searchFilter}";
				//pr($qData);	
		 	$total_row = $this->apps->fetch($sql);
		 	//pr($qData);exit;
		 	return array('data'=>$qData,'total'=>$total_row['total']);
		}
		
	}
	
	function unusers(){
		//create user list per hirarki
		$uid = intval($this->apps->_request('uid'));
		$sql = "
				UPDATE admin_users SET n_status = 3
				WHERE id = {$uid} LIMIT 1				
			";
			
		$this->apps->query($sql);
		
		return true;
	}
	
	 function getRoles(){
		$sql =" select * from admin_roles_type WHERE n_status = 1 ";
		$qData = $this->apps->fetch($sql,1);
		if($qData) return $qData;
		else return false;
	 }
	
	function addRoles(){
	
		$rolename  = strip_tags($this->apps->_request('rolename'));
		$definition   = strip_tags($this->apps->_request('definition'));
		$n_status   = strip_tags($this->apps->_request('n_status'));
	
		$sql = "
				INSERT INTO admin_roles_type ( name ,	definition ,	n_status ) VALUES ('{$rolename}','{$definition}','{$n_status}')
			";
			
		$this->apps->query($sql);
		if($this->apps->getLastInsertId()>0)  {
				 
						return  "role's {$rolename} created";
		}
		return  false;
	}
	
	
	
	
	
}
