<?php
class activityReportHelper{
		
	var $userID = null;
		
	function __construct($apps){
		$this->apps = $apps;		
	}
	
	function log($param=NULL,$id=NULL,$expLog=FALSE){
		global $CONFIG;
		$user = $this->apps->getUserOnline();
		if($user) $this->userID = $user->id;
		else $this->userID = 0;
	
		// if($this->userID==null) return false;
		$datenow = strtotime(date('Y-m-d H:i:s'));
		$dateNumNow = date('Y-m-d H:i:s');
		
		if($param!=NULL){
			$actionID=0;
			$userID = $this->userID;
			$actionValue = NULL;
			
			$sql ="SELECT id,point FROM tbl_activity_actions WHERE activityName = '{$param}' ";
			
			
			$qData = $this->apps->fetch($sql);

									
			
			if($qData) 	$actionID = $qData['id'];
			
			$score=$qData['point'];			
			
			
			$qData=NULL;
			if($id!=NULL) $actionValue = $id;
			if(array_key_exists('log_session',$CONFIG)){
				if($CONFIG['log_session']==true){
					$sessionSerial = urlencode64(mysql_escape_string(cleanXSS(serialize($_SESSION))));
					$qSession = "{$sessionSerial}";
				}else $qSession = "";
			}else $qSession = "";
			$sql = "INSERT IGNORE INTO tbl_activity_log 
					(id,user_id,date_ts,date_time,action_id,action_value,ipaddress,session) 
					VALUES 
					(NULL,{$userID},{$datenow},'{$dateNumNow}',{$actionID},'{$actionValue}','{$_SERVER['REMOTE_ADDR']}','{$qSession}')
					";
			
		// pr($sql);exit;
			//activity log : id 	user_id 	date_ts 	date_time 	action_id 	action_value
			if($actionID!=0 ){
		
			$this->apps->query($sql);

			}
		
		}
		
		if($expLog==TRUE){
	
		
		
		$actScore = intval($score);
		if($actScore==0)	return false;	
		$sql = "
		INSERT  IGNORE INTO tbl_exp_point 
		(id,user_id,date_time_ts,date_time,activity_id,score) 
		VALUES 
		(NULL,{$userID},{$datenow},'{$dateNumNow}',{$actionID},{$actScore})
		";
	
			if($userID!=0  && $actionID!=0 ){

				$this->apps->query($sql);

			
			}
		}
		
		
		
	}
	
	function setnotificationuser($notification=false,$cid=false,$fid=false,$type=""){
	
		if($notification==false) return false;		
		GLOBAL $logger;
		if($cid!=false){
			if($type=="inbox"){
				$sql ="SELECT fromid authorid,id FROM my_message WHERE  id={$cid} LIMIT 1";
			}else $sql ="SELECT authorid,id FROM beat_news_content WHERE  id={$cid} LIMIT 1";
			$qData = $this->apps->fetch($sql);
			$logger->log($sql);
			if(!$fid) $fid = $qData['authorid'];
			$contentid = intval($qData['id']);
		}else $contentid = intval($cid);
		
		$user = $this->apps->getUserOnline();
		if($user) $userID = $user->id;
		else $userID = 0;
		 
		if($fid!=$userID){
			if($fid){
				$sql =" INSERT INTO my_notification (userid,friendid,contentid,type,notification,datetimes) VALUES ('{$fid}','{$userID}',{$contentid},\"{$type}\",\"{$notification}\",NOW() ) ";
				$this->apps->query($sql);
				if($this->apps->getLastInsertId()){
					/*						
					$sql ="
						INSERT INTO my_message (fromid,recipientid,message,datetime,n_status,fromwho,parentid) 
						VALUES ({$userID},{$fid},'{$notification}',NOW(),1,1,0)
						";
				 
						$this->apps->query($sql);
						$parentid = $this->apps->getLastInsertId();
						if($parentid>0) {
							$sql ="
								UPDATE my_message set datetime=NOW()
								WHERE
								id={$parentid} LIMIT 1					
								";
							$this->apps->query($sql);
						}
					*/
					$this->apnspasser($fid,$notification,$contentid);
				}
			}
		}else{
			if($type=="inbox"){
				if($fid==$userID){
					$sql =" INSERT INTO my_notification (userid,friendid,contentid,type,notification,datetimes) VALUES ('{$fid}','{$userID}',{$contentid},\"{$type}\",\"{$notification}\",NOW() ) ";
					$this->apps->query($sql);
				}
			}
		}
		$logger->log($sql);
	}
	
	function apnspasser($fid=false,$notification=false,$contentid=false){
			GLOBAL $CONFIG;
			if($fid==false) return false;
			 
			if($notification==false) return false;
			
			GLOBAL $logger;
			$sql =" SELECT device_token FROM tbl_device_token_apns WHERE userid={$fid} ";
			$qData = $this->apps->fetch($sql,1);
			if($qData){

	                        // Development Credentials
        	                //$uname = "ZitLOOKcQKC3xhaMiMM-kA";
                	        //$pwd = "Hlv0MWdDSDuzxPYhPyShpw";
	
        	    // Production Credentials
				foreach($qData as $val){
					$uname = $CONFIG['apnsusername'];
					$pwd = $CONFIG['apnspassword'];
					
					$contents = array(); 
					$contents['badge'] = "+1"; 
					$contents['alert'] = $notification; 
					$contents['sound'] = "default"; 
					$contents['contentid'] = $contentid; 
				  
					$push = array("aps" => $contents, "device_tokens" =>$val['device_token']); 
					 
					$url = "https://go.urbanairship.com/api/push/";
								
					$data = $this->curlJSON($url,$push,$uname,$pwd);
					$logger->log(" urbanship cred : ". json_encode($push));
					$logger->log(" urbanship return : ". json_encode($data));
				}
				return true;
			}
			return false;
	}
	
	function curlJSON($url,$data,$uname,$pwd){
		$data_string = json_encode($data);
		// pr($data_string);exit;
		$ch = curl_init($url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
		curl_setopt($ch, CURLOPT_USERPWD, $uname.":".$pwd);		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));                                                                                                       
		$result = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		return array("response"=>$result,"info"=>$info);
	}
	 
	
	function activityTime(){
		$userID = $this->userID;
		$countTime =0;
		$datenow = strtotime(date('Y-m-d H:i:s'));
		$dateNumNow = date('Y-m-d H:i:s');
		$sql = "
		SELECT login_count FROM kana_member 
		WHERE id = {$userID} LIMIT 1";
		$this->apps->open();
		$qData = $this->apps->fetch($sql);
		$this->apps->close();
		$token_this_day = sha1($userID.$qData['login_count'].date('Ymd'));
		$qData = NULL;
		if($userID!=''){
		$sql = "
		SELECT ping_time_ts FROM tbl_activity_time 
		WHERE 
		user_id = {$userID} and 
		session_token='{$token_this_day}' 
		ORDER BY ping_time_ts DESC LIMIT 1";
		$this->apps->open();
		$qData = $this->apps->fetch($sql);
		$this->apps->close();
		if($qData){
		$countTime = intval(strtotime(date('Y-m-d H:i:s'))) - $qData['ping_time_ts'];
		
		}else  $countTime = 15;
		
		
		// print_r($sql);
		$qData = NULL;
		if( $countTime >= 15){
		
		//activity time : id 	user_id 	ping_time 	ping_time_ts 	session_token a unique session token
		$sql = "
		INSERT INTO tbl_activity_time 
		(id, user_id,ping_time,ping_time_ts,session_token) 
		VALUES 
		(NULL,{$userID},'{$dateNumNow}',{$datenow},'{$token_this_day}')
		";
		// echo $sql;
		// echo $sql;exit;
	
		$this->apps->query($sql);

		}
		}
		
	}
	
	
	function promo_ref($regID,$refID){
	//activity time : id 	user_id 	ping_time 	ping_time_ts 	session_token a unique session token
		$sql = "
		INSERT INTO referral 
		(refID, regID) 
		VALUES 
		('{$refID}',{$regID})
		";
		// echo $sql;
		// echo $sql;exit;

		$this->apps->query($sql);
	
	
	}
	
	
}
?>
