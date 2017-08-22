<?php
global $APP_PATH;
include_once $APP_PATH."/MOP/MOPClient_2.php";

class mopHelper {
	var $mopClient;

	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		$this->mopClient =  new MOPClient(null);
	
	}
	
	function mop(){
			global $CONFIG;
			
			
			
			if(	isset ($_SESSION['MOP_SESSION'])) return $_SESSION['MOP_SESSION'];
		
				$session_mop= $this->mopClient->checkReferral($_REQUEST['id']);
			
				if($session_mop!=-1){
				
				$this->mopClient->setSession($session_mop);
				
				$MOP_SESSION = 	$this->mopClient->getSession();
				
				$MOP_PROFILE = $this->mopClient->GetProfile2(0,$MOP_SESSION);
				$result = $this->mopSync($MOP_PROFILE);
				if($result){
					$_SESSION['MOP_SESSION'] = $MOP_PROFILE;
									
				}else $_SESSION['MOP_SESSION'] = false;
				return $_SESSION['MOP_SESSION'];
				
		
				}
			return false;

	}
	
	function sendActivityToMop($data=null){
		if($data==null) return false;
		if( $data['page']=='ajax') return false;
		if(!$data['page']) return false;
	
		$code = $this->get_code($data['page'],$data['act']);
		
		$user = $_SESSION['MOP_SESSION']['UserProfile'];
			
		if($code) $data['code'] = $code;
		else return false;
		
			$CPMOOCODE = $this->get_cpmoo_code($data['code']);
			$oldsessId = $_SESSION['mop_sess_id'];
			$PageRef = "1";
			$ActivityName = mysql_escape_string(strip_tags($data['page']));
			$ActivityValue = mysql_escape_string(strip_tags($data['act']));
		
			
			$sessId=$this->mopClient->checkReferral($oldsessId);
			if($sessId!=-1){
				
				$this->mopClient->setSession($sessId);
				
				$MOP_SESSION = 	$this->mopClient->getSession();
				
				$MOP_PROFILE = $this->mopClient->GetProfile2(0,$MOP_SESSION);
				
				if($MOP_PROFILE){
					$_SESSION['MOP_SESSION'] = $MOP_PROFILE;
									
				}else $_SESSION['MOP_SESSION'] = false;
				
				
				$sendAction = $this->mopClient->track($sessId,$PageRef,$ActivityName,$ActivityValue,$CPMOOCODE[$data['code']],$user);
				if($sendAction['Result']==1) return true;
				else return false;
			}else return false;
			
		
		
		
	}

	
	function get_cpmoo_code($code=null){
		if($code == null) return false;
		$cpmoo = false; 
		$sql = "SELECT CodeName ,	WebSessionLanguage ,Campaign 	,Phase 	,Audience,	MediaCategory 	,OfferCategory ,OfferCode ,	CPAOType ,	siteID 
		FROM cpmoo_code WHERE CodeName= '{$code}' LIMIT 1";		
		$qData = $this->apps->fetch($sql);
		
		if(!$qData) return false;
		$cpmoo[$qData['CodeName']] = $qData;		
		return $cpmoo;
	}
	
	function get_code($page=NULL,$act=NULL){
		$sql ="SELECT code FROM mop_referrence_code ";
		if($act!=NULL) $sql .="WHERE page LIKE '{$page}' AND act LIKE '{$act}' ";
		else  $sql .="WHERE page LIKE '{$page}' ";
		$sql .=" LIMIT 1";
	
		$qData = $this->apps->fetch($sql);
	
		if($qData)	return $qData['code'];
		else return null;
		
	}
	function setTimer(){
		global $CONFIG;
		//setcookie("mop_timer", true, time() + ($CONFIG['MOP_CHECK_TIME']*60) );
		$_SESSION["mop_timer"] = time() + ($CONFIG['MOP_CHECK_TIME'] * 60);
	}
	
	function checkTimer(){
		/*
		if($_COOKIE['mop_timer']){
			return true;
		}
		*/
		if($_SESSION['mop_timer'] > time()){
			return true;
		}else{
			$_SESSION['mop_timer'] = null;
			return false;
		}
	}
	
	function update_login_time($user_id){
		$sql = "UPDATE social_member SET last_login = NOW()
				WHERE id=".intval($user_id);
		
		
		$rs = $this->apps->query($sql);
	
		return $rs;
	}
	
	/**
	 * sync mop data with our data
	 * @param object
	 * @return json
	 */
	function mopSync($profile){
		
		$member = $this->getProfileByMop($profile["UserProfile"]["RegistrationID"]);
		if($member['n_status']==9){return null;}
		if($member['registerid']!=$profile["UserProfile"]["RegistrationID"]){
			
			//insert data
			if($this->insert_data_from_mop($profile)){
				
				$member = $this->getProfileByMop($profile["UserProfile"]["RegistrationID"]);
			}else{
				
				print mysql_error();
			}
		}else{
			//Data di social_member selalu update sesuai dengan MOP
			$this->update_data_from_mop($profile);
		}
		return json_encode($member);
	}
	
	function insert_data_from_mop($profile){
		
		$pro = $profile["UserProfile"];
		
		$birth = explode(' ',$pro['DateOfBirth']);
		$birthday = explode('/',$birth[0]);
		$birthday = $birthday[2].'-'.$birthday[0].'-'.$birthday[1]; 
		$streetName = is_array($pro['StreetName']) ? '' : $pro['StreetName'];
		$mobilePhone = is_array($pro['MobilePhone']) ? '' : $pro['MobilePhone'];		
		
		$avatar = ( strtolower($pro['Gender']) == 'm' )? 'default.jpg' : 'default.jpg';
		

		$n_status = ($pro['AVType'] == 1 || $pro['AVType'] == 3)? 1 : 0;
		
		$last_login = '0000-00-00';
		$freeBadge = false;
		if($n_status == 1){
			$last_login = "NOW()";
			$freeBadge = true;
		}
		
		$sql = "INSERT INTO 
				social_member (registerid,name,email,register_date,username,city,sex,birthday,img,last_name,StreetName,phone_number,last_login,n_status,login_count,Brand1_ID)
				VALUES ('".$pro['RegistrationID']."','".$pro['FirstName']."','".$pro['Email']."',NOW(),'".$pro['Login']."','".$pro['CityID']."','".$pro['Gender']."','".$birthday."','{$avatar}','".$pro['LastName']."','".$streetName."','".$mobilePhone."',$last_login,'$n_status','1','{$pro['Brand1_ID']}')
				ON DUPLICATE KEY UPDATE 
				registerid=VALUES(registerid)
				;";
		
		$rs = $this->apps->query($sql);

		return $rs;
	}
	
	function update_data_from_mop($profile){
		$pro = $profile["UserProfile"];		
	
		$sql = "SELECT n_status,last_login,login_count FROM social_member WHERE registerid='".$pro['RegistrationID']."' LIMIT 1;";
		$rs = $this->apps->fetch($sql);
		if(!$rs) return false;
		$n_status = intval($rs['n_status']);
		$last_login = "'0000-00-00'";
		$login_count = intval($rs['login_count']) + 1;
		
		if( intval($rs['n_status']) == 0 && (intval($pro['AVType']) == 1 || intval($pro['AVType']) == 3) ){
			$n_status = 1;
			$last_login = "NOW()";
	
		}
		
		if(intval($rs['n_status']) == 1){
			$last_login = "NOW()";
		}
		
		$birth = explode(' ',$pro['DateOfBirth']);
		$birthday = explode('/',$birth[0]);
		$birthday = $birthday[2].'-'.$birthday[0].'-'.$birthday[1]; 
		$streetName = is_array($pro['StreetName']) ? '' : $pro['StreetName'];
		$mobilePhone = is_array($pro['MobilePhone']) ? '' : $pro['MobilePhone'];
		
		//test no verified member
		//$n_status = 0;
		
		$sql = "UPDATE social_member SET 
				name='{$pro['FirstName']}',
				last_name='{$pro['LastName']}',
				email='{$pro['Email']}',
				username='{$pro['Login']}',
				city='{$pro['CityID']}',
				sex='{$pro['Gender']}',
				birthday='{$birthday}',
				StreetName='{$streetName}',
				phone_number='{$mobilePhone}',
				last_login={$last_login},
				n_status='{$n_status}',
				Brand1_ID='{$pro['Brand1_ID']}',
				login_count='{$login_count}'
				WHERE
				registerid='{$pro['RegistrationID']}'";
		
	
		$rs = $this->apps->query($sql);
		if(!$rs) return false;
		//echo $sql.'<hr/>';
		//echo mysql_error();exit;
			return $rs;
	}
	
	function getProfileByMop($register_id){

		$sql = "SELECT * FROM social_member WHERE registerid='{$register_id}' LIMIT 1";
		$rs = $this->apps->fetch($sql);
		if(!$rs) return false;
		return $rs;
	}
	function getProfile($id){
	
		//$sql = "SELECT * FROM social_member WHERE id='".$id."' LIMIT 1";
		$sql = "SELECT m.* FROM social_member m WHERE m.id='{$id}' LIMIT 1";
		$rs =  $this->apps->fetch($sql);
		if(!$rs) return false;
		return $rs;
	}
	
	
	
}
?>
