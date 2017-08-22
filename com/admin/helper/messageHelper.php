<?php

class messageHelper {
	
	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		if($this->apps->isUserOnline()) {
			if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
		}
		else $this->uid = 0;
		$this->dbshema = "athreesix";
		$this->adminid = array(26,69);
	}	
	
	function getCount(){
		$sendinboxcount = false;
		$sql =  "
				SELECT n_status
				FROM my_news_letter
				WHERE userid = {$this->uid} AND type='inbox' ";
				
		$qData = $this->apps->fetch($sql);	
		if($qData){
			if($qData['n_status']==1) $sendinboxcount = true;		 
		}	
		if(!$sendinboxcount) return false;
		$sql =  "
				SELECT COUNT( * ) hitung
				FROM my_message msg 
				WHERE recipientid = {$this->uid} AND n_status = 1 ";
				
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql);
		if($qData) return $qData;
		else return false;
	}
	
	function getMessage($start=0,$limit=10,$notification=true){
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==0) $start = intval($this->apps->_request('start'));
		if(!$notification) $qnotif = "AND msg.message NOT LIKE '%your post%' ";
		else $qnotif = "";
		$search = "";
		$startdate = "";
		$enddate = "";
		if ($this->apps->_p('search')) {
			if ($this->apps->_p('search')!="Search...") {
				$search = rtrim(strip_tags($this->apps->_p('search')));
				$search = ltrim($search);
				$realsearch = $search;
				if(strpos($search,' ')) $parseSearch = explode(' ', $search);
				else $parseSearch = false;
				
				if(is_array($parseSearch)) $search = $search.'|'.trim(implode('|',$parseSearch));
				else  $search = trim($search);
				$search = "AND (
							msg.message REGEXP  '{$search}' 
							OR smsend.name LIKE '{$realsearch}%' 
							OR smsend.last_name LIKE '{$realsearch}%' 
							OR smreci.name LIKE '{$realsearch}%' 
							OR smreci.last_name LIKE '{$realsearch}%' 
							
							) ";
			}
		}
		if ($this->apps->_p('startdate')) {
			$start_date = strip_tags($this->apps->_p('startdate'));
			$startdate = "AND DATE(msg.datetime) >= DATE('{$start_date}') ";
		}
		if ($this->apps->_p('enddate')) {
			$end_date = strip_tags($this->apps->_p('enddate'));
			$enddate = "AND DATE(msg.datetime) <= DATE('{$end_date}') ";
		}
		//msg.fromid ={$this->uid}  OR msg.recipientid ={$this->uid} 
		$sql =  "
			SELECT msg.parentid
			FROM my_message msg	
			LEFT JOIN social_member smsend	ON smsend.id = msg.fromid
			LEFT JOIN social_member smreci	ON smreci.id = msg.recipientid
			WHERE ( msg.fromid ={$this->uid}  OR msg.recipientid ={$this->uid} )  {$search} {$startdate} {$enddate}  {$qnotif}
			AND msg.n_status IN (1,2) 
			AND smsend.n_status = 1
			AND smreci.n_status = 1
			GROUP BY msg.parentid DESC
			ORDER BY msg.parentid DESC, msg.datetime DESC  
		";
		// pr($sql);
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		$arrparentid = false;
		
		if($qData){	
			foreach($qData as $val){
				$arrparentid[$val['parentid']] = $val['parentid'];
			}
		}
		
		if($arrparentid) $qParentid = " AND msg.parentid IN ( ".implode(',',$arrparentid)." ) ";
		else return false;
		
		$sql_total = "
			select count(a.id) total
			FROM (
				SELECT msg.*
				FROM my_message msg	
				LEFT JOIN social_member smsend	ON smsend.id = msg.fromid
				LEFT JOIN social_member smreci	ON smreci.id = msg.recipientid
				WHERE 1 {$search} {$startdate} {$enddate} {$qnotif} AND msg.n_status IN (1,2)  
				AND smsend.n_status = 1
				AND smreci.n_status = 1
				AND ( msg.fromid ={$this->uid}  OR msg.recipientid ={$this->uid} ) 
				{$qParentid}
				GROUP BY msg.parentid ORDER BY msg.datetime DESC
			) a
		";
		
		$total = $this->apps->fetch($sql_total);
		
		if(intval($total['total'])<=$limit) $start = 0;
		
		$sql =  "
			SELECT msg.* , MAX(msg.datetime) datetime, IF(msg.message LIKE 'notification%','notification','direct' ) messagetype
			FROM my_message msg	
			LEFT JOIN social_member smsend	ON smsend.id = msg.fromid
			LEFT JOIN social_member smreci	ON smreci.id = msg.recipientid
			WHERE 1 {$search} {$startdate} {$enddate}  {$qnotif}
			AND msg.n_status IN (1,2) 
			AND smsend.n_status = 1
			AND smreci.n_status = 1
				AND ( msg.fromid ={$this->uid}  OR msg.recipientid ={$this->uid} ) 
			{$qParentid}
			GROUP BY msg.parentid ORDER BY   msg.datetime DESC LIMIT {$start},{$limit} 
		";
		// pr($sql);
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		
		if($qData) {
			$sdata = false;
			$rdata = false;
			$socialData = false;
			$rsocialData = false;
			foreach($qData as $key => $val){
					
				$qData[$key]['message'] = html_entity_decode($qData[$key]['message']);
			 
				if($val['messagetype']!='direct') $qData[$key]['message']  =  strtoupper(substr($val['message'],1,1)).strtolower(substr($val['message'],2));
					$qData[$key]['totalreply']  = 0;
					// $qData[$key]['totalreply']  = false;
					// $qData[$key]['lastreply'] = array();
				$sdata[$val['fromid']] = $val['fromid'];
				$rdata[$val['recipientid']] = $val['recipientid'];
				 $reply = $this->replymessage(0,100,$val['id'],false);
				if($reply){
					$qData[$key]['totalreply'] = count($reply);
					$qData[$key]['lastreply'] = max($reply);
				}
				
			}
			if($sdata){
				$strsdata = implode(',',$sdata);
				$socialData= $this->getSocialData($strsdata);
				
			}
			if($rdata){
				$strsdata = implode(',',$rdata);
				$rsocialData= $this->getSocialData($strsdata);
				
			}
			if($socialData){
				foreach($qData as $key => $val){
					if(array_key_exists($val['fromid'],$socialData)) $qData[$key]['userdetail'] = $socialData[$val['fromid']];
					else  $qData[$key]['userdetail'] = false;
				}
			
			}
			if($rsocialData){
				foreach($qData as $key => $val){
					if(array_key_exists($val['recipientid'],$rsocialData)) $qData[$key]['recepientdetail'] = $rsocialData[$val['recipientid']];
					else  $qData[$key]['recepientdetail'] = false;
				}
			
			}
			
			// pr($qData);
			$result['result'] = $qData;
			$result['total'] = intval($total['total']);
			$totals = intval($total['total']);

			
			if($totals>$start) $nextstart = $start;
			else $nextstart = 0;
			
					
			if($start<=0)$countstart = $limit;
			else $countstart = $limit+$nextstart;
			
			$thenextpage = intval($limit+$nextstart);
			if($totals<=$thenextpage)	$thenextpage = 0;	
			$result['pages']['nextpage'] = $thenextpage;
			$result['pages']['prevpage'] = $countstart-$limit;
			
			
			$totalnotif = $this->getCount();
			if($totalnotif) $result['totalnotif'] = intval($totalnotif['hitung']);
			else  $result['totalnotif'] = 0;
			return $result;
		}
		return false;
	}
	
	function readmessage(){
		
		$id = intval($this->apps->_request('id'));
		if($id==0) return false;
			
		$sql =  "
		SELECT * , IF(msg.message LIKE 'notification%','notification','direct' ) messagetype
		FROM my_message msg	
		WHERE id={$id} AND n_status IN (1,2) 
		ORDER BY datetime DESC LIMIT 1";
		$qData = $this->apps->fetch($sql,1);
		$data = false;
		if($qData) {
	
			$sdata = false;
			$socialData = false;
			foreach($qData as $val){
				$sdata[$val['fromid']] = $val['fromid'];
				$messageid = $val['id'];
				$parentid = $val['parentid'];
			}
			
			$sql ="	UPDATE my_message set n_status=2
					WHERE
					parentid={$parentid} AND recipientid={$this->uid} LIMIT 1	
					";
			// pr($sql);
			$this->apps->query($sql);
			
			if($sdata){
				$strsdata = implode(',',$sdata);
				$socialData= $this->getSocialData($strsdata);
			}
			
			if($socialData){
				foreach($qData as $key => $val){
					if($val['messagetype']!='direct')  $qData[$key]['message']  =  strtoupper(substr($val['message'],1,1)).strtolower(substr($val['message'],2));
					if(array_key_exists($val['fromid'],$socialData)) $qData[$key]['userdetail'] = $socialData[$val['fromid']];
					else  $qData[$key]['userdetail'] = false;
				}
			
			}
			$data['message'] = $qData;
			$data['reply'] = $this->replymessage(0,100,$parentid,true);
			// pr($qData);exit;
			return $data;		
		}
		
		return false;
	}

	function replymessage($start=0,$limit=10,$id=false,$noread=true){	
		if($id==false)$id = intval($this->apps->_request('id'));
		if($start==0) $start = intval($this->apps->_p('start'));

		$sql =  "
		SELECT *
		FROM my_message msg	
		WHERE parentid={$id} AND id<>{$id} AND n_status IN (1,2)  AND fromwho <> 2
		ORDER BY datetime DESC LIMIT {$start},{$limit} ";
		$qData = $this->apps->fetch($sql,1);
	
		if($qData) {	
			$sdata = false;
			$socialData = false;
			foreach($qData as $key => $val){
				$qData[$key]['message'] = html_entity_decode($qData[$key]['message']);
				$sdata[$val['fromid']] = $val['fromid'];
				if($noread){
					$sql ="	UPDATE my_message set n_status=2
					WHERE
					id={$val['id']} AND recipientid={$this->uid} LIMIT 1	
					";
					$this->apps->query($sql);
				}
			}
			if($sdata){
				$strsdata = implode(',',$sdata);
				$socialData= $this->getSocialData($strsdata);
			}
			
			if($socialData){
				foreach($qData as $key => $val){
					//$qData[$key]['message']  =  strtoupper(substr($val['message'],1,1)).strtolower(substr($val['message'],2));
					if(array_key_exists($val['fromid'],$socialData)) $qData[$key]['userdetail'] = $socialData[$val['fromid']];
					else  $qData[$key]['userdetail'] = array();
				}
			
			}
			// pr($qData);exit;
			return $qData;		
		}
		
		return array();	
	}
	
	function createMessage($recipientid=false,$message=false,$fromwho=1,$parentid=0,$multimessages=false){
		$datetime = date("Y-m-d H:i:s");
				
		if($parentid==0)$parentid = intval($this->apps->_p('parentid'));
		if(!$recipientid)$recipientid = intval($this->apps->_p('recipientid'));
		if(!$message)$message = strip_tags($this->apps->_p('message'));
		$bugreport = intval($this->apps->_p('bugreport'));
		 
		if($message=='') return false;
		
		if($bugreport==1) {
				// $this->creatBugReport($recipientid,$message);
				$this->sendmessagetomail($recipientid,$this->apps->user->email,$message," [  BUG REPORT ] USER MESSAGE ",true) ;
				return true;
		}
		
		$torecepientid = false;
		if($parentid!=0){
			$sql ="SELECT fromid,parentid FROM my_message WHERE id ={$parentid}   LIMIT 1";
			$mData = $this->apps->fetch($sql);
			
			if($mData) {
				$parentid = $mData['parentid'];
				if(!$multimessages)$recipientid = $mData['fromid'];
				if(!$multimessages)$torecepientid = $mData['fromid'];
				else $torecepientid = $recipientid;
			}
			 
		}
		if($multimessages) $fromwho = 2;
		$sql ="
			INSERT INTO my_message (fromid,recipientid,message,datetime,n_status,fromwho,parentid) 
			VALUES ({$this->uid},{$recipientid},'{$message}','{$datetime}',1,'{$fromwho}',{$parentid})
			";
		// pr($sql);exit;
		$this->logger->log($sql);
		$this->apps->query($sql);
			
		if($this->apps->getLastInsertId()>0) {
			if($parentid==0) $newmess = false;
			else $newmess = true;
			if($parentid==0) {
				$parentid = $this->apps->getLastInsertId();
				
				$sql ="
					UPDATE my_message set parentid={$parentid}
					WHERE
					id={$parentid} LIMIT 1					
					";
				$this->apps->query($sql);
				
				
			}
			$sql ="
					UPDATE my_message set datetime=NOW()
					WHERE
					id={$parentid} LIMIT 1					
					";
				$this->apps->query($sql);
			
			
			
			if(!$newmess) {
				$sql ="SELECT name FROM social_member WHERE id ={$recipientid} LIMIT 1";
				$sData = $this->apps->fetch($sql);
			}else{
				$sql ="SELECT name FROM social_member WHERE id ={$torecepientid} LIMIT 1";
				$sData = $this->apps->fetch($sql);
			}
			
				if($sData) $recname = $sData['name'];
				else $recname = " Someone ";
			 		
			$notifmessage[1]= " {$this->apps->user->name} reply your message"; 	// reply to other user
			$notifmessage[2]=" you reply {$recname}'s message "; 				// reply from user
			$notifmessage[3]=" New message from {$this->apps->user->name}";		// new message to other user
			$notifmessage[4]=" You send message to {$recname}";					// new message user
			
			$this->logger->log(" recepient temperation : ".$torecepientid);
			if(!$newmess) {
			
				/* new message */
				$this->apps->notif($notifmessage[3],$parentid,$recipientid,"inbox"); 						// new message to other user
				/* send message */
				// $this->apps->notif($notifmessage[4],$parentid,$this->uid,"inbox"); 						// new message user
			}else{	
				 
					/* reply */
					if($torecepientid) $this->apps->notif($notifmessage[1],$parentid,$torecepientid,"inbox");	// reply to other user
					/* send message */
					// $this->apps->notif($notifmessage[4],$parentid,$this->uid,"inbox");						// reply from user
				 
			}
				
			if($bugreport==1) {
				// $this->creatBugReport($recipientid,$message);
				$this->sendmessagetomail($recipientid,$this->apps->user->email,$message," [  BUG REPORT ] USER MESSAGE ",true) ;
			}
			
			return $parentid;
		}
		return false;
	
	}
	
	function creatBugReport($recipientid=false,$message=false,$fromwho=1){
		$datetime = date("Y-m-d H:i:s");
		
		$parentid = intval($this->apps->_p('parentid'));
		if(!$recipientid)$recipientid = intval($this->apps->_p('recipientid'));
		if(!$message)$message = strip_tags($this->apps->_p('message'));
		$bugreport = intval($this->apps->_p('bugreport'));
		 
		if(!$bugreport) return false;
		$sql ="
			INSERT INTO tbl_bug_report (fromid,recipientid,message,datetime,n_status,fromwho,parentid) 
			VALUES ({$this->uid},{$recipientid},'{$message}','{$datetime}',1,'{$fromwho}',{$parentid})
			";
		// pr($sql);exit;
		$this->logger->log($sql);
		$this->apps->query($sql);
			
		if($this->apps->getLastInsertId()>0) {
			if($parentid==0) {
				$parentid = $this->apps->getLastInsertId();
				$sql ="
					UPDATE tbl_bug_report set parentid={$parentid}
					WHERE
					id={$parentid} LIMIT 1					
					";
				$this->apps->query($sql);
				
				
			}
			$sql ="
					UPDATE tbl_bug_report set datetime=NOW()
					WHERE
					id={$parentid} LIMIT 1					
					";
				$this->apps->query($sql);
						 
			return $parentid;
		}
		return false;
	
	}
	
	function sendmessagetomail($recipientid=false,$from=false,$msg=false,$subject=false,$bugreport=false){
			
			if($recipientid==false) return false;
			if($from==false) return false;
			if($msg==false) return false;
			global $CONFIG;
			if(!$bugreport){
				$sql = "SELECT email FROM social_member 
				WHERE id = '{$recipientid}' LIMIT 1";
				$qData = $this->apps->fetch($sql);
			}
			
			$newsHelper = $this->apps->useHelper('newsHelper');
			 
			// $to = "bummi@kana.co.id";
			if($bugreport) $to = $CONFIG['supportemail'];
			else {
				if($qData) $to = $qData['email'];
				else $to = '';
			}
			
			if($to=='') return false;
			
			$mailpin = $newsHelper->sendGlobalMail($to,$from,$msg,$subject,$bugreport);
			$this->logger->log($to);
			$this->logger->log(json_encode($mailpin));
			return true;
			
			return false;
	}
	
	function getSocialData($strsdata=false){
		global $CONFIG;
		if($strsdata==false) return false;
		$data =false;
	
		
		
		$sql ="
		SELECT sm.id,sm.name,sm.last_name,sm.img, ptype.id pagetypeid
		FROM social_member sm
		LEFT JOIN my_pages pages ON pages.ownerid = sm.id
		LEFT JOIN my_pages_type ptype ON ptype.id = pages.type		
		WHERE sm.id IN ({$strsdata}) ";		
		// pr($sql);
			$sQdata = $this->apps->fetch($sql,1);	
			// pr($sQdata);	
			if($sQdata){
				$arrmessagetype[1] = "direct";
				$arrmessagetype[2] = "direct";
				$arrmessagetype[3] = "brand";
				$arrmessagetype[4] = "brand";
				$arrmessagetype[5] = "brand";
				$arrmessagetype[100] = "challenge";
				foreach($sQdata as $key => $val){
				
							$sQdata[$key]['messagetype'] = @$arrmessagetype[$val['pagetypeid']];
							
							if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/{$val['img']}")) $val['img'] = false;
							if($val['img']) $sQdata[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/".$val['img'];
							else $sQdata[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/default.jpg";
							$data[$val['id']]=$sQdata[$key];			
				}
			}
			
		return $data;
	
	}	
	
	function uninboxmessage(){
			$cid = intval($this->apps->_p('cid'));
			$sql ="
					UPDATE my_message set n_status=3
					WHERE
					parentid={$cid} AND ( fromid={$this->uid} OR  recipientid ={$this->uid} )
					
					";
				// pr($sql);exit;
			if($this->apps->query($sql)) return true;
			else return false;
	
	}
	
	
	function getinboxcount($notification=true){
		
		$total = 0;
		if(!$notification) $qnotif = "AND msg.message NOT LIKE '%your post%' ";
		else $qnotif = "";
		$sql_total = "
			select count(a.id) total
			FROM (
				SELECT msg.*
				FROM my_message msg	
				LEFT JOIN social_member smsend	ON smsend.id = msg.fromid
				LEFT JOIN social_member smreci	ON smreci.id = msg.recipientid
				WHERE msg.recipientid ={$this->uid}  AND msg.n_status IN (1)  
				AND smsend.n_status = 1
				AND smreci.n_status = 1
				{$qnotif}
				GROUP BY msg.parentid ORDER BY msg.datetime DESC
			) a
		";
		$qData = $this->apps->fetch($sql_total);
		if($qData)$total = intval($qData['total']);
		
		return $total;
	}
	
}