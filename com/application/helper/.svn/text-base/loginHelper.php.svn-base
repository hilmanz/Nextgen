<?php

class loginHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
 
		$this->BEATServHelper = $this->apps->useHelper('BEATServHelper');
		$this->config = $CONFIG;
		if( $this->apps->session->getSession($this->config['SESSION_NAME'],"WEB") ){
			
			$this->login = true;
		
		}
		 
	}
	
	function checkLogin(){
		
		return $this->login;
	}
		
	 
	function goLogin($username){
		global $logger, $APP_PATH,$LOCALE;
		
		$sql = "SELECT * FROM social_member WHERE username='{$username}' LIMIT 1";
	
		$rs = $this->apps->fetch($sql);
		
		$logger->log($sql);
		// pr($sql);exit;
		//check password and phonumber , each field must be same of input value (higher security)
			$result =  new stdClass;
		 
			if($rs['n_status']!=1) {
				$result->status = false;
				$result->code = $rs['n_status'];
				if($rs['n_status']==9) $result->message  = $LOCALE[1]['blockingreason'];
				else $result->message = $LOCALE[1]['notactivated'];				
				return $result;
			}
			
			if($rs){
			
				if($rs['n_status']==1){
				
					$this->setdatasessionuser($rs);
					
					$logger->log('Able to login BEAT');
					 
					$this->login = true;
					$result->status = true;
					$result->code  = $rs['n_status'];
					$result->message  = " welcome ";
					
					return $result;
					
				} 
			}
				$this->login = false;
				$this->add_try_login($rs);
				$logger->log("Not able to login BEAT ");
				$result->status = false;
				$result->code = 2 ;
				$result->message  = $LOCALE[1]['wrongloginpasswordandusername']; 
				return $result;
			 
	 
	
	}
	
	function beatloginsession(){
				 
			$beatdata = $this->BEATServHelper->checkReNLogin();
	 
			if($beatdata){
						if($beatdata->status){
							// pr($beatdata);
							$checkProfile = $this->BEATServHelper->checkProfile(); 
						 
							
							if(isset($checkProfile->profile)){
									$branddetail = $this->BEATServHelper->branddetail(); 
									if(isset($branddetail->result)) $branddata = $branddetail->data;
									else $branddata = false;
									$resynch = $this->synchdatabeat($checkProfile->profile,$branddata);
									
									if($resynch['result']) return $this->goLogin($beatdata->user_mail);
								 
							}
						}
						// pr($beatdata);
						return $beatdata;
				}
			 
		
			return false;
	}

	function beatmoploginsession(){			 
			$emails = strip_tags($this->apps->_request('username'));
			$brand = strip_tags($this->apps->_request('brand'));
			$session = $this->apps->session->getSession($this->config['SESSION_NAME'],"MOPADMIN") ;	
			$sessionid = $session->SessionID;
			
			$beatdata = new stdClass;
			$branddata[0] =new stdClass;
			$branddata[1] =new stdClass;
			// recreate user profile matching with beat tables,  DST ONLY, tipe DST not EXISTS ON BEAT
			$beatdata->id = $sessionid;
			$beatdata->name = strip_tags($emails);
			$beatdata->last_name = '';
			$beatdata->nickname = '';	
			$beatdata->username = strip_tags($emails);		
 	   
			$beatdata->email =strip_tags($emails);	 
			$beatdata->user_mail = strip_tags($emails);	 
			$beatdata->sex = 'U';
			//page stat
		 

			$beatdata->role = 'dst';
			$beatdata->roletype ='1';	 
			$beatdata->img  = '';
		 
			$beatdata->cityname = 'dstcitynotfound';
			$created_date = date("Y-m-d H:i:s");
			$closed_date = date("Y-m-d H:i:s");
			$branddata[0]->id = $brand;
			$branddata[1]->id = $brand;
		  
			$resynch = $this->synchdatabeat($beatdata,$branddata);
			 
			if(isset($resynch['result'])) return $this->goLogin($beatdata->user_mail);		
			return false;
	}
	/*
	lagi masang permission session
	
	*/
	function loginSession( ){
		 
			if($this->goLoginDST()){
				 
				return true;
			}else{
				return false;
			} 
	 
		return false;
	}
	
	function goLoginDST(){
		global $logger, $APP_PATH;

		$username = trim($this->apps->_p('username'));
		$password = trim($this->apps->_p('password'));
		$brand = strip_tags($this->apps->_request('brand'));
		$sql = "SELECT * FROM social_member WHERE username='{$username}' LIMIT 1";
		//pr($password);
		
		$rs = $this->apps->fetch($sql);
		
		$logger->log($sql);
		
		$hash = sha1($password.$rs['salt']);
		// pr($hash);
		// pr($sql);exit;
		//check password and phonumber , each field must be same of input value (higher security)
		// pr($hash);
		// pr($rs['password']);
 		if($rs['password']==$hash){
			$this->setBrandTemp($brand,$rs['id']);  
			$this->setdatasessionuser($rs);
		
			$logger->log('can login');
			$this->login = true;
			return true;
		}else {
			$logger->log("cannot login, password or username not exists ");
			return false;
		}
	}
	function setBrandTemp($brandid=4,$userid=0){
				if($userid==0) return false;
				$sql = "
						UPDATE my_pages SET 
						brand={$brandid}
						WHERE ownerid = {$userid} LIMIT 1				
				";
				$res = $this->apps->query($sql);
				
	}
	function setdatasessionuser($rs=false){
		//pr($rs);exit;
		if(!$rs) return false;
	 

		$this->logger->log('can login');
		$id = intval($rs['id']);
 
		if($rs['login_count']!=0)$this->add_stat_login($id);
	 
		$this->reset_try_login($rs);
		 
		$this->apps->session->setSession($this->config['SESSION_NAME'],"WEB",$rs);
	
	}
	
	function add_try_login($rs=false){
		
		if($rs==false) return false;	
	
		$sql ="UPDATE social_member SET last_login=now(),try_to_login=try_to_login+1 WHERE id='{$rs['id']}' LIMIT 1";
		$res = $this->apps->query($sql);
		
		$sql = "SELECT try_to_login FROM social_member WHERE id='{$rs['id']}' LIMIT 1";
		$res = $this->apps->fetch($sql);
		
		if($res){
			if($res['try_to_login']>4) {
				$sql ="UPDATE social_member SET n_status=9 WHERE id='{$rs['id']}' LIMIT 1";
				$res = $this->apps->query($sql);
			}
		}
	}
	
	function reset_try_login($rs=false){
		
		if($rs==false) return false;	
	
		$sql ="UPDATE social_member SET last_login=now(),try_to_login=0 WHERE id='{$rs['id']}' LIMIT 1";
		$res = $this->apps->query($sql);
				
	}
	
	function add_stat_login($user_id){
	
	
		// $sql ="UPDATE social_member SET last_login=now(),login_count=0 WHERE id={$user_id} LIMIT 1";
		$sql ="UPDATE social_member SET last_login=now(),login_count=login_count+1 WHERE id={$user_id} LIMIT 1";
		$rs = $this->apps->query($sql);

	
	}
	
	function getProfile(){
	
		$user = json_decode(urldecode64($this->apps->session->getSession($this->config['SESSION_NAME'],"WEB")));
		
		return $user;
	
	}
	  
	 
	
	function realeaselock(){
			$username = strip_tags($this->apps->_p('username'));
			$sql = "
					UPDATE social_member SET n_status = 1,try_to_login=0
					WHERE username = '{$username}' LIMIT 1				
			";
			// pr($sql);
			return $this->apps->query($sql);
	}
	
	 
	 
	function synchdatabeat($beatdata =false,$branddata=false){
		 global $CONFIG;
		$this->logger->log('do register');
		
		
		$datas['msg'] = 'cannot register or updates ';
		$datas['result'] =false;
		if(!$beatdata) return $datas;
		
		$notsaved = "not save";
		$saved = "saved";
		// user stat
		$name = strip_tags($beatdata->name );
		$last_name = strip_tags($beatdata->last_name );
		$nickname = strip_tags($beatdata->nickname );		
		$username = strip_tags($beatdata->username );		
		$registerid = strip_tags($beatdata->id);		
	  
		$email = null;
		$email = trim(strip_tags($beatdata->email ));		 
		$deviceid = trim(strip_tags($beatdata->username ));		 
		$sex = trim(strip_tags($beatdata->sex ));
		//page stat
	 
 
		$rolesname = trim(strip_tags($beatdata->role ));
		$type =trim(strip_tags($beatdata->roletype ));		 
		$img = strip_tags($beatdata->img );
		$otherid = 0;
		$brandid = 0;
		$brandsubid = 0;
		$areaid = 0;
		$city = strip_tags($beatdata->cityname );
		$created_date = date("Y-m-d H:i:s");
		$closed_date = date("Y-m-d H:i:s");
		
		if($branddata){
			foreach($branddata as $key =>$val){
				if($key==0)$brandid = $val->id;
				else $brandsubid = $val->id;
			}
				
		}
		 
		$n_status = 1;
		
		if($email==''||$email==null){
			$this->logger->log('form registration not complete.');
			return   $datas['msg'] = "form registration not complete. email not found";
		}
			
	 
		$sql = "SELECT * FROM social_member WHERE email='{$email}' LIMIT 1 ";
		$qData = $this->apps->fetch($sql);
		// pr($qData);
		if($qData){
			if($email==''||$email==null){
				$this->logger->log('form registration not complete.');
				return $datas['msg'] =  "form registration not complete. email not found";
			}
			 $socialupdates = array();
			if($registerid)$socialupdates[] = "registerid='{$registerid}'";
			if($deviceid) $socialupdates[] = "deviceid='{$deviceid}'";
			if($last_name) $socialupdates[] = "last_name='{$last_name}'";
			if($last_name) $socialupdates[] = "name='{$name}'";
			if($last_name) $socialupdates[] = "sex='{$sex}'";
			if($nickname) $socialupdates[] = "nickname='{$nickname}'";
			$socialupdates[] = "n_status = 1,try_to_login=0  ";
		 
			if($socialupdates){
				$qUpdatesocialupdateData = implode(',',$socialupdates);
			
					
					$sql = "
						UPDATE social_member SET {$qUpdatesocialupdateData} 
							WHERE id = {$qData['id']} LIMIT 1				
						
					";
					// pr($sql); 
					$this->apps->query($sql);
			}
			$sql = "SELECT ownerid FROM my_pages WHERE ownerid={$qData['id']} LIMIT 1 ";
			$rqData = $this->apps->fetch($sql);
			
			if($rqData){
				$dataupdate = false;
				if($rolesname!='') $dataupdate[] = "name='{$rolesname}'";
				if($type!='') $dataupdate[] = "type='{$type}'";
				if($img!='') $dataupdate[] = "img='{$img}'";		 
				$dataupdate[] = "brand='{$brandid}'";
				$dataupdate[] = "brandsub='{$brandsubid}'";
				 
				if($city!='') $dataupdate[] = "city='{$city}'";
				$qUpdateData = "";
				if($dataupdate){
					$qUpdateData = implode(',',$dataupdate);
				}else return $datas['msg'] =  "form registration not complete. email not found";
				
				$sql = "
						UPDATE my_pages SET 
						{$qUpdateData} 
						WHERE ownerid = {$qData['id']} LIMIT 1				
				";
				$this->logger->log($sql);
				$this->apps->query($sql);
				
				$datas['msg'] = 'Update Success.';
						$datas['result'] = true;
						return  $datas;
			}else{
				$sql = "
					INSERT INTO my_pages (ownerid ,	name, 	description ,	type 	,img ,		brand 	,brandsub ,	city 	,created_date ,	closed_date,n_status) 
					VALUES ('{$qData['id']}','{$rolesname}','',{$type},'{$img}','{$brandid}','{$brandsubid}','{$city}',NOW(),DATE_ADD(NOW(),INTERVAL 5 YEAR),1)
				";
				// pr($sql);
				 $this->apps->query($sql);
					if($this->apps->getLastInsertId()>0)  {
						$datas['msg'] = 'Update Success.';
						$datas['result'] = true;
						return  $datas;
						 
					}
			
			}
		}else{
			if($email==''||$email==null){
				$this->logger->log('form registration not complete.');
				return  $datas['msg'] =  "form registration not complete. email not found";
			}
		
			$sql = "
				INSERT INTO social_member (deviceid,email,register_date,salt,n_status,name,nickname,username,last_name,sex,registerid) 
				VALUES ('{$deviceid}','{$email}',NOW(),'{$CONFIG['salt']}',1,'{$name}','{$nickname}','{$username}','{$last_name}','{$sex}','{$registerid}')			
			";
		
			$this->apps->query($sql);
			$lastID = $this->apps->getLastInsertId();
			if($lastID>0) {
				$sql = "
					INSERT INTO my_pages (ownerid ,	name, 	description ,	type 	,img ,brand 	,brandsub ,		city 	,created_date ,	closed_date,n_status) 
					VALUES ('{$lastID}','{$rolesname}','',{$type},'{$img}',{$brandid},{$brandsubid},'{$city}',NOW(),DATE_ADD(NOW(),INTERVAL 5 YEAR),1)
				";
				// pr($sql); 
				 $this->apps->query($sql);
					if($this->apps->getLastInsertId()>0)  {
						$datas['msg'] = 'Registration Complete.';
						$datas['result'] = true;
						return  $datas;
					}
			}		
		}
		$datas['msg'] =  "Failed";
		return  $datas;
	}
	
	
	
}
