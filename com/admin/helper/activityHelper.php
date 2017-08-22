<?php 
class activityHelper {

	var $uid;
	var $osDetect;
	
	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;

		$this->apps = $apps;
		if($this->apps->isUserOnline())  {
			if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
			
		}		
		$this->lid = 1;
		$this->server = intval($CONFIG['VIEW_ON']);

		$this->schemadb = "beat";
		
	}
	
	
	
	function getA360Pagesactivity($start=0,$limit=2,$user=false){
		
		$bandMember = $this->apps->bandHelper->getMember();
		
		$qUser = "";
		if($bandMember) {
			foreach ($bandMember['result'] as $k => $v) {
				$arrMemberId[$v['id']] = $v['id'];
			}
			$strMemberId = implode(',',$arrMemberId);
			$qUser = " AND user_id IN ({$strMemberId}) ";
		} else return false;
		
		return $this->getA360activity($start,10,false,$qUser,false);
		
	}
	
	function getnotificationuser($limit=10,$start=null,$type=false){
		
		$total = 0;
		if($start==null)$start = intval($this->apps->_request('start'));
		$sql ="SELECT * FROM my_news_letter WHERE userid={$this->uid} AND type IN ('comment','emoticon','tags') AND n_status = 0 ";
		$allownotif = $this->apps->fetch($sql,1);
		$qAllowNotif = " AND type NOT IN ('inbox') ";
		if($allownotif){
			$allowarr = false;
			foreach($allownotif as $val){
				$allowarr['inbox'] = "'inbox'";
				$allowarr[$val['type']] = "'".$val['type']."'";
			}
			if($allowarr) {
				$impallowtype = implode(',',$allowarr);
				$qAllowNotif = " AND type NOT IN ({$impallowtype})";
			}
		}
		
		if($type=='inbox'){
			$qAllowNotif = " AND type  = 'inbox' ";
		}
		
		$sql = " SELECT count(*) total FROM my_notification WHERE userid={$this->uid} AND n_status=0 AND datetimes >= DATE_SUB(NOW(), INTERVAL 2 WEEK) {$qAllowNotif} ORDER BY datetimes DESC  ";
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql);
		if($qData) $currentnotif = intval($qData['total']);
		$sql = " SELECT count(*) total FROM my_notification WHERE userid={$this->uid} AND n_status IN (0,1,2) AND datetimes >= DATE_SUB(NOW(), INTERVAL 2 WEEK)  {$qAllowNotif}  ORDER BY datetimes DESC  ";
		$qData = $this->apps->fetch($sql);
		if($qData) $total = intval($qData['total']);
		$sql = " SELECT id,notification,contentid,type,friendid,datetimes,n_status FROM my_notification WHERE userid={$this->uid}  AND datetimes >= DATE_SUB(NOW(), INTERVAL 2 WEEK) {$qAllowNotif}  ORDER BY datetimes DESC LIMIT {$start},{$limit} ";
		$this->logger->log($sql);
		$notificationdata = $this->apps->fetch($sql,1);
		
			
		if($notificationdata){
		
				
			foreach($notificationdata as $key => $val){
			
				$sdata[$val['friendid']] = $val['friendid'];
				
			}
			if($sdata){
				 
					$socialData= $this->getSocialData($sdata);
					
			}
				
			if($socialData){
				foreach($notificationdata as $key => $val){
					 
					if(array_key_exists($val['friendid'],$socialData)) $notificationdata[$key]['frienddetail'] = $socialData[$val['friendid']];
					else  $notificationdata[$key]['frienddetail'] = array();
				}
			
			}
			
			$data['total'] = intval($currentnotif);
			$data['maxnotif'] = intval($total);
			$data['content'] = $notificationdata;
		}else {
			$data['total'] = 0;
			$data['maxnotif'] = 0;
			$data['content'] = array();
		}
		
		$totals = $total;

		
		if($totals>$start) $nextstart = $start;
		else $nextstart = 0;
		
				
		if($start<=0)$countstart = $limit;
		else $countstart = $limit+$nextstart;
		
		$thenextpage = intval($limit+$nextstart);
		if($totals<=$thenextpage)	$thenextpage = 0;	
		$data['pages']['nextpage'] = $thenextpage;
		$data['pages']['prevpage'] = $countstart-$limit;
		
		return $data;
		
	}
	
	
	
	function readnotifuser(){
		$nid = intval($this->apps->_p('nid'));
		
		if($nid==0)$qNid = "";
		else $qNid = " AND  id={$nid} LIMIT 1 ";
		
		$sql = " UPDATE my_notification SET n_status=2 WHERE userid={$this->uid} AND type IN ('comment','emoticon','tags','addfriends','inbox') {$qNid}  ";		
		$qData = $this->apps->query($sql);
		if($qData) return true;
		else return false;
	}
	
	function getA360activity($start=0,$limit=5,$user=false,$thisBand=false,$followeronly=false,$n_status=3,$push=false){
		GLOBAL $LOCALE,$CONFIG;
		return $this->getnotificationuser($limit,$start);
		exit;
		$uid = intval($this->apps->_request('uid'));
		
		if($uid==0) $uid = $this->apps->user->id;
						
		$data['total'] = false;
		$data['content'] = false;
		
		$activityIDarr = false;
		$theactivity = false;
		$qData = false;
		
		$articleActivity = "'add comments','add favorite','attending','uploads'";
		$socialActivity = "'add friends','update profile'";
	
		$theactivity = $this->apps->activityHelper->getactivitytype($articleActivity);	
		/* get article of user */
		
		if($theactivity) {		
			$articleActivitID = implode(',',$theactivity['id']);
		}else $articleActivitID = false;
		/* get activity social id */	
		$socialactivitydata = $this->apps->activityHelper->getactivitytype($socialActivity);
		if($socialactivitydata) {
			$socialActivityID = implode(',',$socialactivitydata['id']);
		}else $socialActivityID = false;
		
		$activityIDarr = array($articleActivitID,$socialActivityID);
		
		if($activityIDarr) {
			foreach($activityIDarr as $val){
				if($val!='')$activityIDNewArr[]=$val;
			}
			if(!$activityIDNewArr)return false;
			$qUser = " ";
			$activityId = implode(',',$activityIDNewArr);
			
			
			if($followeronly){
				$arrFriends = false;
				$friendid = false;
				$friendsData = $this->apps->userHelper->getFriends(false);
			
				if($friendsData){
					if($friendsData['result']){
						foreach($friendsData['data'] as $userfriends){
							
									$arrFriends[$userfriends['id']] = $userfriends;
						}
						// pr($arrFriends);exit;
						if($arrFriends){
							foreach($arrFriends as $key => $val){
									$friendid[$val['id']] = intval($val['id']);
							}
						}
						$friendid[$uid] = $uid;
							// pr($friendid );
						if(is_array($friendid)){
							$strFriendid = implode(',',$friendid);
							$qUser = " AND user_id IN ({$strFriendid}) ";
							
						}
					}
				}
				
			}else $qUser = " AND user_id = {$uid} ";					
					
		
			
			$qfromid = "";
			$maxid = 0;
			
			$sql =" SELECT last_log_id FROM social_member WHERE id = {$uid} LIMIT 1";
			$lastlogid = $this->apps->fetch($sql);
			
			$maxid = intval($lastlogid['last_log_id']);
			if($maxid!=0) $qfromid = " AND id > {$maxid} ";
			
				// pr($qfromid);
			$sql = "SELECT count(*) total FROM tbl_activity_log WHERE n_status NOT IN ({$n_status}) AND action_id IN ({$activityId})  {$qUser} {$qfromid} ";		
			$total = $this->apps->fetch($sql);
			
			// if(!$total) return false;
			if(!$push) $qfromid = "";
			$sql = "SELECT *,IF ( action_id IN ({$socialActivityID}) , 'social' , 'content' ) typeofnotification 
					FROM tbl_activity_log WHERE n_status NOT IN ({$n_status}) AND  action_id IN ({$activityId})  {$qUser} {$qfromid} 
					ORDER BY date_time DESC LIMIT {$start},{$limit}";
					
			// pr($sql);
			$qData = $this->apps->fetch($sql,1);
			
			// pr($qData);
			if(!$qData) return false;
			
			$contentid = false;
			$otheruserid = false;
			$notifid = false;
			foreach($qData as $key => $val){
				//get userid
				$userid[] = $val['user_id'] ;
				//get action value
				$actionid[] = $val['action_id'];
				
				// id notif
				$notifid[] = $val['id'];
				//get content id
				$arrActionValue = false;
				
				if($val['typeofnotification'] == 'social' )	$otheruserid[] = intval($val['action_value']);
				else $contentid[] = intval($val['action_value']);
			}
			
			if($contentid) $arrContent = $this->getContentData($contentid);
			else $arrContent = false;
			
			if($otheruserid) $arrOtherUser = $this->getsocialData($otheruserid);
			else $arrOtherUser = false;
			
			if($userid) $arrUserid = $this->getsocialData($userid);
			else $arrUserid = false;
					
			if($actionid){
				$stractionid = implode(',',$actionid);
				
				//get content
				$sql = "SELECT * FROM tbl_activity_actions WHERE id IN ({$stractionid}) LIMIT {$limit}";
				$qSData = $this->apps->fetch($sql,1);
				if($qSData){
					foreach($qSData as $val){
						$arrActionid[$val['id']] = $val;
					}
				}else $arrActionid = false;			
			}
			//merge it

			foreach($qData as $key => $val){
				
					if($val['typeofnotification'] == 'social' ){
						if($arrOtherUser ){
							if(array_key_exists($val['action_value'],$arrOtherUser)) $qData[$key]['contentdetail'] = $arrOtherUser[$val['action_value']];	
							else $qData[$key]['contentdetail'] = false;
						}else $qData[$key]['contentdetail'] = false;
						$qData[$key]['contentType'] = 'social';
					}else{
						if($arrContent ){
							if(array_key_exists($val['action_value'],$arrContent)) $qData[$key]['contentdetail'] = $arrContent[$val['action_value']];	
							else $qData[$key]['contentdetail'] = false;
						}else $qData[$key]['contentdetail'] = false;
						$qData[$key]['contentType'] = 'content';
					}
					if($arrUserid){	
						if(array_key_exists($val['user_id'],$arrUserid))$qData[$key]['userdetail'] = $arrUserid[$val['user_id']];	
						else $qData[$key]['userdetail'] = false;
					}else $qData[$key]['userdetail'] = false;
					
					if($arrActionid){
						if(array_key_exists($val['action_id'],$arrActionid))$qData[$key]['actiondetail'] = $arrActionid[$val['action_id']];	
						else $qData[$key]['actiondetail'] = false;
					}else $qData[$key]['actiondetail'] = false;
				
				
			}
			$notificationdata = false;
			//can be used on views
			foreach($qData as $key => $val){
				// if($val['userdetail']) $notification[] =  $val['userdetail']['name'];
				// else  $notification[] = 'some one';
				if($val['actiondetail']) {
					if(array_key_exists($val['actiondetail']['activityName'],$LOCALE[$this->lid])) $activities = $LOCALE[$this->lid][$val['actiondetail']['activityName']]['news'];
					else $activities = false;
					$notification['activities'] = $activities;
					
				}else  $notification[] = 'has do something with';
				
				if($val['contentdetail']) {
					if($val['contentType']=='social') $notification['subjective'] = $val['contentdetail']['name'];
					else $notification['subjective'] = $val['contentdetail']['title'];
				}else  $notification['subjective'] = str_replace('_',' ',$val['action_value']);
				
				// $qData[$key]['notification'] = implode(' ',$notification);
				
				// $qData[$key]['notification'] = $notification;
				$notificationdata[$key]['notification'] = $val['userdetail']['name']." ".$notification['activities']." ".$notification['subjective'];
				$notification = null;
			}
			
			
				// pr($qData);
			if($qData){
				$data['total'] = intval($total['total']);
				$data['content'] = $notificationdata;
				
				if($push){
							
						$this->readnotification($notifid);
				}
				// pr($qData);
				return $data;
			}else return false;	
		}else return false;	
	}
	
	
	function readnotification($notifid=false){
		global $CONFIG;
		$data['maxid'] = intval(max($notifid));
		
		$sql =" UPDATE social_member SET last_log_id = {$data['maxid']} WHERE id = {$this->uid}  LIMIT 1";
		 $this->apps->query($sql);
	
		
		return true;
	}
	
	
	function getsocialData($userid=null){
			if($userid==null) return false;
			$struserid = implode(',',$userid);
			global $CONFIG;
			//get content
			$sql = "SELECT id,name,nickname,img,small_img,'friends' as type,last_name FROM social_member WHERE id IN ({$struserid}) LIMIT 10";
			// pr($sql);
			$qSData = $this->apps->fetch($sql,1);
			if($qSData){
				foreach($qSData as $key => $val){
					if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/{$val['img']}")) $qSData[$key]['img'] = false;
					if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/tiny_{$val['img']}")) $qSData[$key]['img'] = "tiny_{$val['img']}";
					
					if($val['img']) $qSData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/".$val['img'];
					else $qSData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/default.jpg";
					
					$arrUserid[$val['id']] =  $qSData[$key];
					
				}
			}else $arrUserid = false;			
			return $arrUserid;
	}
	
	function getContentData($contentid){
			if($contentid==null) return false;
			$strcontentid = implode(',',$contentid);
			
			//get content
			$sql = "SELECT anc.*,anct.type FROM {$this->schemadb}_news_content anc 
			LEFT JOIN {$this->schemadb}_news_content_type anct ON anct.id=anc.articleType
			WHERE anc.id IN ({$strcontentid}) LIMIT 10";
			// pr($sql);
			$qSData = $this->apps->fetch($sql,1);
			if($qSData){
				foreach($qSData as $val){
					$arrContent[$val['id']] = $val;
				}
			}else $arrContent = false;
			if($arrContent){
				$arrContent = $this->apps->contentHelper->getStatistictArticle($arrContent);
			}
			
			return $arrContent;
	}
	
	
	function getactivitytype($dataactivity=null,$id=false){
		if($dataactivity==null)return false;
		if($id) $qAppender = " id IN ({$dataactivity}) ";
		else $qAppender = " activityName IN ({$dataactivity})  ";
		$theactivity = false;
		/* get activity  id */	
		$sql = " SELECT * FROM tbl_activity_actions WHERE {$qAppender} ";

		$qData = $this->apps->fetch($sql,1);
			
		if($qData) {
			foreach($qData as $val){
				$theactivity['id'][$val['id']]=$val['id'];
				$theactivity['name'][$val['id']]=$val['activityName'];
				
			}
		}
		return $theactivity;
	}
}
?>

