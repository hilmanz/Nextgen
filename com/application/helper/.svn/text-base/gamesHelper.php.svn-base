<?php 

class gamesHelper {

	function __construct($apps){
	
	
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->config = $CONFIG;
		$this->apps = $apps;
		$this->uid  = 0;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
		
		$this->dbshema = "marlborohunt";	
		
		$this->week = 7;
		$week = intval($this->apps->_request('weeks'));
		if($week!=0) $this->week = $week;
		
		$this->startweekcampaign = "2013-05-20";
		$this->datetimes = date("Y-m-d H:i:s");
		
		$this->level = array(1,2,3);
		
		$this->gamesarrayid =array(1,2,3,4,5,6,7);
		$this->singlelevelgame =array(4,7);
		$this->pointarr = array(0,10,20,30,40,50);	
		// pr($this->apps->_request('week'));
	}
	
	
	function getLastOldToken(){
	
	 
		
		$sql = " SELECT token FROM my_games WHERE userid = {$this->uid}  ORDER BY datetimes DESC LIMIT 1";
		
		$lastToken = $this->apps->fetch($sql);
		if($lastToken){
			//fresh start
				// pr($sql);
				// pr($lastToken);
			if($lastToken['token']==''){
				return $this->uid;
			}else{
			// has token old
				return $lastToken['token'];
			}
			
		}
			
		return false;	
		
	}
	
	function checkgamesscheme($gamesid=false){
		if(!$gamesid) return false;
		$sql = "SELECT id FROM tbl_games_references WHERE gamesid='{$gamesid}' LIMIT 1  ";
		$qData = $this->apps->fetch($sql);
		
		if($qData) return intval($qData['id']);
		return false;
	}
	
	function checkstatus()
	{
		$checkCode = false;
		$mytoken = false;

		/* parse win status of user games */
		$token = strip_tags($this->apps->_p('token'));
		$win = strip_tags($this->apps->_p('win'));
		$userid = strip_tags($this->apps->_p('userid'));
				
		$gamesid = strip_tags($this->apps->_p('gamesid'));
		$gamesid = $this->checkgamesscheme($gamesid);
		if(!in_array($gamesid,$this->gamesarrayid)) return false;
		$level = intval($this->apps->_p('level'));
		
		$point = intval($this->pointarr[$level]);
		
		if(!in_array($point,$this->pointarr)) return false;
		
		$getLastOldToken = $this->getLastOldToken();
		
		$salt = "gameapihelper";
			$this->logger->log('phase 1: check token '.$gamesid );
			$this->logger->log('phase 1b: check level '.$level );
		if(!$token) return false;
			$this->logger->log('phase 1: OK ');
		/* token matching with erwin */
		$mytoken = sha1($this->uid.date("YmdHi").$getLastOldToken."true{".$salt."}");
		$mytokentolerance = sha1($this->uid.date("YmdHi",strtotime(date("YmdHi")." -1 minute ")).$getLastOldToken."true{".$salt."}"); /* tolerance 1 minute */
			$this->logger->log('phase 2: check param information ');
		if($this->uid==0) return false;
			$this->logger->log('phase 2: id OK ');
		if($this->uid!=$userid) return false;
			$this->logger->log('phase 2: all OK ');
		/* check user dont have code in publicity code where code not exists in their inventory */
		$checkuserplaygames = $this->checkuserplaygames();
		$checkuserwinthislevel = $this->checkuserwinthislevel();
		
		
		
			$this->logger->log('phase 2b: token '.$token.' '.$mytoken.', tolerance: '.$mytokentolerance.' using token concat this = '.$this->uid.date("YmdHi").$getLastOldToken."true{".$salt."}");
		// if($token!=$mytoken) {
			// if($token!=$mytokentolerance) return false;
		// }
		
		/* give user log playing games */
			$this->logger->log('phase 3: log user current games ');
			$playinggamesid = intval($this->playinggames($mytoken));
			$this->logger->log('phase 3: OK ');
			
			$this->logger->log('phase 2b: token OK ');
		
			$this->logger->log('phase 4: check win ');
		if($win!="true") return false;
			$this->logger->log('phase 4: OK ');
		
			$this->logger->log('phase 5: check user play games ');
		if(!$checkuserplaygames) return false;
			$this->logger->log('phase 5: OK ');
		
		$this->logger->log('phase 6: check code public for games in inventory');
		$checkCode = $this->checkpublicexistsinventory();
		if(!$checkCode) return false;
			$this->logger->log('phase 6: OK ');
		
		if($checkCode['result']){
					$this->logger->log('phase 6: result OK ');
					/* save to inventory user if win */
					$this->logger->log('phase 7: save to inventory ');
				if($win=="true"){
					$this->logger->log('phase 7: OK ');
					if(!$checkuserwinthislevel)  return false;
					
					if(in_array($level,$this->level)) {
						$checkuserplaygames = $this->checkuserplaygames();
						if(!$checkuserplaygames) return false; 
						
						$saved = $this->savetoinventory($win,$checkCode['data'],$playinggamesid);
					}else $saved = true;
					if($saved) return $checkCode['data'];
				}
				
		}else{
				$this->logger->log('phase 6: result NOT OK ');
			$checkCode = false;
			/* if not found code in publicity code, create 1 code for this user */
				$this->logger->log('phase 7b: generate code ');
			$firstcreatecode = $this->generateCode();
			if(!$firstcreatecode) return false;		
				$this->logger->log('phase 7b: OK ');
			
				$this->logger->log('phase 8: check code in this inventory again ');
			$checkCode = $this->checkpublicexistsinventory();
			if(!$checkCode) return false;
				$this->logger->log('phase 8: OK ');
			if($checkCode['result']){
					$this->logger->log('phase 8: result OK ');
					/* save to inventory user if win */
				if($win=="true"){
					$this->logger->log('phase 9: save to inventory ');
					if(!$checkuserwinthislevel) return false;
					
					if(in_array($level,$this->level)) {	
						$checkuserplaygames = $this->checkuserplaygames();
						if(!$checkuserplaygames) return false;
						 						
						$saved = $this->savetoinventory($win,$checkCode['data'],$playinggamesid);
					}else $saved = true;
					$this->logger->log('phase 9: '.$saved);
					if($saved) return $checkCode['data'];
				}
			}else return false;
		}
		
		return false;
	}
	
	function getgametask(){
		return false; // tanya kia notif buat game apa fungsi nya
		$gamesid = intval($this->apps->_p('gamesid'));
			$gamesid = $this->checkgamesscheme($gamesid);
		if(!in_array($gamesid,$this->gamesarrayid)) return false;
		$typeofgames[1] = 6; // cross out
		$typeofgames[2] = 22; // wallbreaker
		$typeofgames[4] = 25; // word hunt
		$typeofgames[5] = 24; // doubt crasher
		$typeofgames[6] = 26;// move forward
		$typeofgames[7] = 27;// word master
		$sql =" SELECT * FROM {$this->dbshema}_news_content WHERE articleType={$typeofgames[$gamesid]} AND n_status=1 LIMIT 1";
		$qData = $this->apps->fetch($sql);
		// pr($qData); 
		$this->logger->log($sql);
		if(!$qData) return false;
		return $qData;
		
	}
	
	function playinggames($token=false,$images=false){
		
		$datetime = date("Y-m-d H:i:s");
		$data['status'] = false;
		$data['code'] = 0;
		$data['msg'] ="cannot save games";
		$token = strip_tags($this->apps->_p('token'));
		$gamesid = strip_tags($this->apps->_p('gamesid'));
			$gamesid = $this->checkgamesscheme($gamesid);
		$multiplayer = intval($this->apps->_p('multiplayer'));
		// $userid = intval($this->apps->_p('userid'));
		$userid = $this->uid;
		$registrantmail = strip_tags($this->apps->_p('registrantmail'));
		// $dstmail = strip_tags($this->apps->_p('usermail'));
		$dstmail = $this->apps->user->email;
		$win = intval($this->apps->_p('win'));
		$playtime = strip_tags($this->apps->_p('playtime'));
		if(!in_array($gamesid,$this->gamesarrayid))  {
			$data['msg'] ="games not found ";
			return  $data;
		}
		$level = intval($this->apps->_p('level'));
		$point = intval($this->pointarr[$level]);
		
		if(!in_array($point,$this->pointarr))  {
			$data['msg'] =" point mismatch";
			return  $data;
		}
		
		
		$getLastOldToken = $this->getLastOldToken();
		
		$salt = "gameapihelper";
		 
		// if(!$token)  {
			// $data['msg'] =" token not found";
			// return  $data;
		// }
	 
		/* token matching    */
		$mytoken = sha1($this->uid.date("YmdHi").$getLastOldToken."true{".$salt."}");
		$mytokentolerance = sha1($this->uid.date("YmdHi",strtotime(date("YmdHi")." -1 minute ")).$getLastOldToken."true{".$salt."}"); /* tolerance 1 minute */
	
		$this->logger->log('phase 2b: token '.$token.' '.$mytoken.', tolerance: '.$mytokentolerance.' using token concat this = '.$this->uid.date("YmdHi").$getLastOldToken."true{".$salt."}");
		
		// if($token!=$mytoken) {
			// if($token!=$mytokentolerance) {
				// $data['msg'] =' token mismatch .... 2b: token -'.$token.'- againsts -'.$mytoken.'- , tolerance: '.$mytokentolerance ;
				// return  $data;
			// }
		// }
				
		if(!$this->uid)  {
			$data['msg'] ="  user data not found ";
			return  $data;
		}
		
		
		$sql = " INSERT INTO my_games 
		( 	gamesid ,	userid 	,point 	,datetimes, 	n_status ,token,win,dstmail,registrantmail,images,multiplayer) 
		VALUES ('{$gamesid}',{$userid},{$point},'{$playtime}',1,'{$token}',{$win},'{$dstmail}','{$registrantmail}','{$images}','{$multiplayer}')";		
		
		$this->apps->query($sql);
		// pr($sql);
		$lastid = intval($this->apps->getLastInsertId());
		if($lastid){
			$data['status'] = true;
			$data['code'] = 1;
			$data['msg'] ="game saved";
			return $data;
		}else return  $data;
		
	
	}
	
	function checkuserwinthislevel(){
	
		$datetime = date("Y-m-d H:i:s");
		
		$gamesid = intval($this->apps->_p('gamesid'));
			$gamesid = $this->checkgamesscheme($gamesid);
		if(!in_array($gamesid,$this->gamesarrayid)) return false;
		
		$level = intval($this->apps->_p('level'));
		$point = intval($this->pointarr[$level]);
		if(!in_array($point,$this->pointarr)) return false;
		
		//check user has win the game at this level
		$sql = " 
		SELECT COUNT(*) total 
		FROM my_games 
		WHERE 
		gamesid={$gamesid}  
		AND point={$point} 
		AND win=1 
		AND userid={$this->uid} 
		AND DATE(datetimes)=DATE('{$datetime}') 
		LIMIT 1 ";
		
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		if(!array_key_exists('total',$qData)) return false;
		if (in_array($gamesid,$this->singlelevelgame)){
			if($qData['total']<1) return true;
		}else{
			if($qData['total']<3) return true;
			// if($qData['total']<=14) return true; /* changes 3 times per games point*/
		}
		
		return false;
	}
	
	
	function checkuserplaygames(){
	
		$datetime = date("Y-m-d H:i:s");
		
		$gamesid = strip_tags($this->apps->_p('gamesid'));
			$gamesid = $this->checkgamesscheme($gamesid);
		if(!in_array($gamesid,$this->gamesarrayid)) return false;
		
		$level = intval($this->apps->_p('level'));
		$point = intval($this->pointarr[$level]);
		if(!in_array($point,$this->pointarr)) return false;
		
		$sql ="
			SELECT COUNT(*) total 
			FROM my_badges b
			LEFT JOIN badges_source_type t ON t.id = b.sourceType
			WHERE 
			userid={$this->uid} 
			AND DATE(redeem_date)=DATE('{$datetime}')  
			AND t.name = 'games {$gamesid}' 
			LIMIT 1";
		
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		if(!array_key_exists('total',$qData)) return false;
		if (!in_array($gamesid,$this->singlelevelgame)){
			if($qData['total']<3) return true;
 
		}else{
			if($qData['total']<1) return true;
		}
		
		return false;
	}
	
	 
	
	function checkuserplayingthislevel(){
		
		$datetime = date("Y-m-d H:i:s");
		$gamesid = strip_tags($this->apps->_p('gamesid'));
			$gamesid = $this->checkgamesscheme($gamesid);
		$level = intval($this->apps->_p('level'));
		$point = intval($this->pointarr[$level]);
		if(!in_array($point,$this->pointarr)) return false;
		
		$sql ="SELECT win FROM my_games WHERE DATE(datetimes)=DATE('{$datetime}') AND userid={$this->uid} AND gamesid={$gamesid} AND point={$point} AND win=1 LIMIT 1";
		$this->logger->log(" check level games : ".$sql);
		$data = $this->apps->fetch($sql);
		$this->logger->log(" check level games result : ".json_encode($data));
		if($data)  return false;
		else return true;
	}
	
	function savetoinventory($win=false,$code=false,$playinggamesid=0){	
		
		if(!$win) return false;
		if(!$code) return false;
		if($playinggamesid==0) return false;
		if(!$this->checkuserplayingthislevel()) return false;
		
		$point = 1;
		$gamesid = strip_tags($this->apps->_p('gamesid'));
			$gamesid = $this->checkgamesscheme($gamesid);
		$level = intval($this->apps->_p('level'));
		$gamepoint = intval($this->pointarr[$level]);
		if(!in_array($gamepoint,$this->pointarr)) return false;
				 
		$win = strip_tags($this->apps->_p('win'));
		$this->logger->log(" win : ".$win);
		if($win!="true") return false;
		$this->logger->log($gamesid);
		if(!in_array($gamesid,$this->gamesarrayid)) return false;
		$gamenames =$gamesid;
		 /* userid 	codeid 	codepublicityid 	n_status 	histories */
		$sql = "SELECT id FROM badges_source_type WHERE name = 'games {$gamesid}' LIMIT 1";
		$sourceType = $this->apps->fetch($sql);
		if(!$sourceType) return false;
		$datetimes = date("Y-m-d H:i:s");
		
		$sql = " INSERT INTO my_badges 
		(userid, badgesid, codeid, n_status, sourceType,redeem_date) 
		VALUES ({$this->uid},{$code['badgesid']},{$code['id']},1,'{$sourceType['id']}','{$datetimes}')";	
		
		$this->logger->log($sql);
		
		$this->apps->query($sql);
		if($this->apps->getLastInsertId()){
			
			$sql = " UPDATE my_games SET win  = 1 WHERE id = {$playinggamesid} AND point = {$gamepoint} AND gamesid ={$gamesid} LIMIT 1";
			$this->apps->query($sql);
			
			
			return true;
		}else return false;
		return false;
	}
	
	function checkpublicexistsinventory(){
		
		
		$data['result'] = false;
		$data['data'] = false;
	
		$gamesid = strip_tags($this->apps->_p('gamesid'));
			$gamesid = $this->checkgamesscheme($gamesid);
		if(!in_array($gamesid,$this->gamesarrayid)) return false;
		
		$sql ="
			SELECT * FROM  badges_code public
			WHERE NOT EXISTS 
			(SELECT * FROM my_badges WHERE codeid = public.id AND userid={$this->uid} ) 
			AND code_channel='games' 
			AND code_sub_channel='games_{$gamesid}' 
			AND n_status = 1 
			LIMIT 1";
		
		$qData = $this->apps->fetch($sql);
					
		if($qData) {
			/* randcode , add proba in here if want to use of fontend */
			$masterbBadges = $this->apps->badgeHelper->masterbBadges();
			$randcodeidmekans = $this->apps->badgeHelper->randomcodegen($masterbBadges);
			$this->logger->log("before : ");
			if($randcodeidmekans!=false) $qData['badgesid']=$randcodeidmekans;
			$this->logger->log("after : ".$qData['badgesid']);
			$data['result'] = true;
			$data['data'] = $qData;
		
		}
		return $data;
		
	
	}
	
	function generateCode()
	{
	
		$gamesid = strip_tags($this->apps->_p('gamesid'));
			$gamesid = $this->checkgamesscheme($gamesid);
		if(!in_array($gamesid,$this->gamesarrayid)) return false;
		$location = 'GAMES CODES';
		$channel = "games";
		$subchannel = "games_{$gamesid}";
		 
	 	$iscommonbadges=1;
		$datetime = date("Y-m-d H:i:s");
		$getres = false;		
		
	 
		 
			$letters  = "ABCDEFGHJKMNPQRSTUVWXYZ23456789";
			$maskcode = substr(str_shuffle($letters), 0, 10);
			

			$sql = "INSERT INTO {$this->config['DATABASE_WEB']}.badges_code 
					(code, code_type, code_sub_channel, code_channel, created_date,  n_status)
					VALUES 
					('{$maskcode}', {$iscommonbadges}, '{$subchannel}', '{$channel}',   '{$datetime}', 1 )";
			// pr($sql);
			 
			 $this->apps->query($sql);
			if($this->apps->getLastInsertId()){
				$getres[$maskcode] = 1;
			}else $getres[$maskcode] = 0;
			
	 
		
		if($getres){
			$success = 0;
			$failed = 0;
			foreach($getres as $key => $val){
				if($val==1) $success++;
				else $failed++;			
			}
				
		 
			return true;
		}
		
				
		return true;
		
	}
	  
	
}

?>

