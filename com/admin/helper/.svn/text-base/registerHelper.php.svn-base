<?php
class registerHelper {
	
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
		$role = explode("_",strip_tags($this->apps->_p('role')));
		$ownerid = intval($this->apps->_p('ownerid'));
		$rolesname = $role[0].' '.strip_tags($this->apps->_p('area'));
		$description = strip_tags($this->apps->_p('description'));
		$type =$role[1];
		$img = strip_tags($this->apps->_p('img'));
		$otherid = intval($this->apps->_p('otherid'));
		$brandid = intval($this->apps->_p('brandid'));
		$brandsubid = intval($this->apps->_p('brandsubid'));
		$areaid = intval($this->apps->_p('areaid'));
		$city = strip_tags($this->apps->_p('city'));
		$created_date = date("Y-m-d H:i:s");
		$closed_date = date("Y-m-d H:i:s");
		$n_status = 1;
		
		if($email==''||$email==null){
			$this->logger->log('form registration not complete.');
			return   "form registration not complete. email not found";
		}
			
		$hashPass = sha1($password.$CONFIG['salt']);
		$sql = "SELECT * FROM social_member WHERE email='{$email}' LIMIT 1 ";
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
				UPDATE social_member SET name='{$name}',sex='{$sex}',nickname='{$nickname}',last_name='{$last_name}' , n_status = 1,try_to_login=0{$updatepass}
					WHERE id = {$qData['id']} LIMIT 1				
				
			";
			//pr($sql);exit;
			$this->apps->query($sql);
			$sql = "SELECT userid FROM my_pages WHERE userid={$qData['id']} LIMIT 1 ";
			$rqData = $this->apps->fetch($sql);
			
			if($rqData){
				$dataupdate = false;
				if($rolesname!='') $dataupdate[] = "name='{$rolesname}'";
				if($type!='') $dataupdate[] = "type='{$type}'";
				if($img!='') $dataupdate[] = "img='{$img}'";
				if($otherid!='') $dataupdate[] = "otherid='{$otherid}'";
				$dataupdate[] = "brandid='{$brandid}'";
				$dataupdate[] = "brandsubid='{$brandsubid}'";
				if($areaid!='') $dataupdate[] = "areaid='{$areaid}'";
				if($city!='') $dataupdate[] = "city='{$city}'";
				$qUpdateData = "";
				if($dataupdate){
					$qUpdateData = implode(',',$dataupdate);
				}else return $saved;
				



				$sql = "
						UPDATE my_pages SET 
						{$qUpdateData} 
						WHERE userid = {$qData['id']} LIMIT 1				
				";
				$this->logger->log($sql);
				$this->apps->query($sql);
				
				// $this->createrolefriends($qData['id'],$otherid,$brandid,$brandsubid,$areaid);
				return 'Update Success.';
			}else{
				$sql = "
					INSERT INTO my_pages (userid ,	name, 	description ,	type 	,img	,created_date ,	closed_date,n_status) 
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
				INSERT INTO social_member (password,email,register_date,salt,n_status,name,nickname,username,last_name,sex) 
				VALUES ('{$hashPass}','{$email}',NOW(),'{$CONFIG['salt']}',1,'{$name}','{$nickname}','{$username}','{$last_name}','{$sex}')			
			";
			$this->apps->query($sql);
			$lastID = $this->apps->getLastInsertId();
			if($lastID>0) {
				$sql = "
					INSERT INTO my_pages (userid ,	name, 	description ,	type 	,img  	,created_date ,	closed_date,n_status) 
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
	

	function getLeader($type=2){
		$qmasterquery = " ";
		if($type==3)$qmasterquery = " AND masterrole = 1 ";
		if($type==5)$qmasterquery = " AND masterrole = 1 ";
		$sql  = "
			SELECT sm.id,pages.name pagename,sm.name, sm.last_name 
			FROM social_member sm
			LEFT JOIN my_pages pages ON pages.ownerid = sm.id
			WHERE type={$type} {$qmasterquery} ";
		
		$qData = $this->apps->fetch($sql,1);
		
		return $qData;
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
			FROM social_member sm
			LEFT JOIN my_pages pages ON pages.userid = sm.id
			LEFT JOIN my_pages_type ptype ON ptype.id = pages.type 
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
					FROM social_member sm
					LEFT JOIN my_pages pages ON pages.ownerid = sm.id
					LEFT JOIN my_pages_type ptype ON ptype.id = pages.type
					LEFT JOIN beat_city_reference cref ON cref.id = pages.city
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
				UPDATE social_member SET n_status = 3
				WHERE id = {$uid} LIMIT 1				
			";
			
		$this->apps->query($sql);
		
		return true;
	}
	
	function createrolefriends($uid=false,$otherid=false,$brandid=false,$brandsubid=false,$areaid=false){
	
		return false;
		
		if($uid == false) return false;
		
		$qSelect = false;
		if($otherid != false) $qSelect[] = " otherid={$otherid} ";
		if($brandid != false) $qSelect[] = " brandid={$brandid} ";
		if($brandsubid != false) $qSelect[] = " brandsubid={$brandsubid} ";
		if($areaid != false) $qSelect[] = " areaid={$areaid} ";
	
		if(!$qSelect) return false;
		$strQSelect  = implode(' OR ',$qSelect);
		$sql = " 
			SELECT ownerid 
			FROM my_pages
			WHERE 1
			AND ( {$strQSelect} )
			";
		
		$qData = $this->apps->fetch($sql,1);
		$friendid = false;
		if(!$qData) return false;
			$this->logger->log(json_encode($sql));
		foreach($qData as $val){
			$friendid[$val['ownerid']] = $val['ownerid'];
		}
		
		if(!$friendid)return false;
		$qCirlce = false;
		$this->logger->log(json_encode($friendid));
		foreach($friendid as $val){
			if($val!=false){
				$qCirlce[]= " ( {$uid},{$val},1,0,NOW(),1 ) ";
				$qCirlce[]= " ( {$val},{$uid},1,0,NOW(),1 ) ";		
			}
		}
		if(!$qCirlce) return false;
		$strQCircle = implode(',',$qCirlce);
		$sql = " 
		INSERT IGNORE INTO my_circle ( userid ,	friendid ,	ftype, 	groupid ,	date_time ,	n_status ) 
		VALUES {$strQCircle}
		";
		$this->logger->log($sql);
		// exit;
		$this->apps->query($sql);
		
		
	}
	
	
	function findmyfrienddefault($userid=false){
		if($userid==false) return false;
		
		//get list of areas with brand and type sba
		$sql = "
		SELECT brandid,type,city 
		FROM my_pages 
		WHERE   
		type IN (1,2)  
		AND brandid <>0 AND city <> 0
		AND ownerid = {$userid}
			GROUP BY brandid,type,city 
			";
		$listdefault = $this->apps->fetch($sql,1);
		if(!$listdefault) return false;
		$insertfriends = false;
		foreach($listdefault as $val){
				
				$sql = "SELECT ownerid FROM my_pages WHERE  masterrole<>1 AND brandid= {$val['brandid']} AND type IN  (1,2)   AND city ={$val['city']} ";
			 
				$sbadata = $this->apps->fetch($sql,1);
				
			
				$datacircle = array();
				foreach($sbadata as $arrcircle){
					foreach($sbadata as $sbaval){
						if($arrcircle['ownerid']!=$sbaval['ownerid']){
							$datacircle[$arrcircle['ownerid'].$sbaval['ownerid']]['userid']= $arrcircle['ownerid'];		 
							$datacircle[$arrcircle['ownerid'].$sbaval['ownerid']]['friendid']= $sbaval['ownerid'];		 
						}
					}
				}
				
				foreach($datacircle as $circle){
					$sql = "
						INSERT IGNORE INTO  my_circle 
						(userid,friendid,ftype,groupid,date_time,n_status)
						VALUES
						({$circle['userid']},{$circle['friendid']},1,0,NOW(),1),
						({$circle['friendid']},{$circle['userid']},1,0,NOW(),1)
					";
					$insertfriends[$circle['userid']][$circle['friendid']] = $this->apps->query($sql);
					// $insertfriends[$circle['userid']][$circle['friendid']] = $sql;
					
				}
				
		}
		
		return $insertfriends;
	
	}
	
	function findtoclassfrienddefault($userid=false){
		if($userid==false) return false;
		//get list of areas with brand and type sba
	 
				$sql = "
				SELECT ownerid FROM my_pages WHERE   
				type IN (4,6,100)  ";
			 
				$topclassdata = $this->apps->fetch($sql,1);
				
				$sql = "
				SELECT ownerid FROM my_pages WHERE masterrole=1 ";
			 
				$sbadata = $this->apps->fetch($sql,1);
				
				$datacircle = array();
				foreach($topclassdata as $arrcircle){
					foreach($sbadata as $sbaval){
						if($arrcircle['ownerid']!=$sbaval['ownerid']){
							$datacircle[$arrcircle['ownerid'].$sbaval['ownerid']]['userid']= $arrcircle['ownerid'];		 
							$datacircle[$arrcircle['ownerid'].$sbaval['ownerid']]['friendid']= $sbaval['ownerid'];		 
						}
					}
				}
				
				foreach($datacircle as $circle){
					$sql = "
						INSERT IGNORE INTO  my_circle 
						(userid,friendid,ftype,groupid,date_time,n_status)
						VALUES
						({$circle['userid']},{$circle['friendid']},1,0,NOW(),1),
						({$circle['friendid']},{$circle['userid']},1,0,NOW(),1)
					";
					$insertfriends[$circle['userid']][$circle['friendid']] = $this->apps->query($sql);
					// $insertfriends[$circle['userid']][$circle['friendid']] = $sql;
					
				}
				
		
		
		return $insertfriends;
	
	 }
	
}
