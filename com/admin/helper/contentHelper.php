<?php 
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Mobile_Detect.php";
class contentHelper {

	var $uid;
	var $osDetect;

	
	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;

		$this->apps = $apps;
		if($this->apps->isUserOnline())  {
			if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
			// if(is_object($this->apps->page)) $this->pageid = intval($this->apps->page->id);
			
		}else $this->uid = 0;
		if(isset($_SESSION['lid'])) $this->lid = intval($_SESSION['lid']);
		else $this->lid = 1;
		if($this->lid=='')$this->lid=1;
		$this->server = intval($CONFIG['VIEW_ON']);
		$this->osDetect = new Mobile_Detect;
		
		
		$this->dbshema = "beat";
		
		$this->executor=array(1,2);
		$this->planner=array(2,3,4);
		$this->approver=array(3,4,6,100);
		$this->agency=array(100);
		
		$this->modzeropage=array('plan');	
		
		$this->moderation = 1;
		$this->plantype[0]="";
		$this->plantype[1]="BA";
		$this->plantype[2]="Co-Creation";
		$this->plantype[3]="Brand";
		$this->plantype[4]="Brand";
		$this->plantype[5]="Brand";
		$this->plantype[100]="Brand";
		
		$this->cocreationtypeid = "2";
		$this->brandtypeid = "3,4,5";
		
	}
	
		

	
	function getAuthorProfile($otherid=null,$typeAuthor='admin'){
		if($otherid==null) return false;
		global $CONFIG;
		$sql = "SELECT name, id AS authorid, '' as last_name,'' as pagestype,image as img FROM gm_member WHERE id IN ({$otherid}) LIMIT 10 ";
		if($typeAuthor == 'social' ) $sql = "SELECT name, id AS authorid, last_name,'' as pagestype,img  FROM social_member WHERE id IN ({$otherid}) ";
		if($typeAuthor == 'page' ) $sql = "SELECT name, id AS authorid , '' as last_name,type as pagestype,img FROM my_pages WHERE id IN ({$otherid}) ";
		// pr($sql);
		$data = $this->apps->fetch($sql,1);
		if(!$data) return false;
		
		foreach($data as $key => $val){
		
			if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/tiny_{$val['img']}")) $val['img'] = false;
			if($val['img']) $data[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/tiny_".$val['img'];
			else $data[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/default.jpg";
					
			$data[$key]['name'] =  ucwords(strtolower($data[$key]['name']." ".$data[$key]['last_name']));
			$data[$key]['last_name'] =  ucwords($data[$key]['last_name']);
			
			$arrData[$val['authorid']] = $data[$key];
			
		}
		
		$sql = "
		SELECT 
		pages.name, pages.id ,
		pages.type ,pages.img,
		pages.ownerid ,pagetype.name pagetypename,
		CONCAT(smbrand.name,' ',smbrand.last_name) brandid ,pages.brandsubid,
		CONCAT(smarea.name,' ',smarea.last_name) areaid,pages.otherid
		FROM my_pages pages
		LEFT JOIN my_pages_type pagetype ON pagetype.id=pages.type
		LEFT JOIN social_member smbrand ON smbrand.id=pages.brandid
		LEFT JOIN social_member smarea ON smarea.id=pages.areaid
		WHERE ownerid IN ({$otherid}) ";
	
		$data = $this->apps->fetch($sql,1);
		
		foreach($data as $key => $val){
			if(array_key_exists($val['type'],$this->plantype))$data[$key]['plantype'] = $this->plantype[$val['type']];
			else $data[$key]['plantype'] =  0;
			$pagedata[$val['ownerid']] = $data[$key];
		}
		
		foreach($arrData as $key => $val){
			if(array_key_exists($val['authorid'],$pagedata)) $arrData[$key]['pagesdetail'] = $pagedata[$val['authorid']];
			else $arrData[$key]['pagesdetail'] = false;
			
		}
		
		if(!isset($arrData)) return false;
		return $arrData;
	}
	
	
	
	function getFavoriteUrl($cid=null,$content=2){
		if($cid==null) return false;
		$sql="
		SELECT count(*) total, contentid,url FROM social_bookmark sb 
		WHERE content={$content}
		AND contentid IN ({$cid}) 
		GROUP BY contentid ";
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		foreach($qData as $val){
			$arrData[$val['contentid']] = $val['total'];
		}	
		if($arrData) return $arrData;
		return false;
	}
	
	
	function saveFavorite(){
	
		$cid = intval($this->apps->_p('cid'));
		$likes =1;
		$uid = intval($this->uid);
		if($cid!=0 && $uid!=0){
			$sql ="
					INSERT INTO {$this->dbshema}_news_content_favorite (userid,contentid,likes,date,n_status) 
					VALUES ({$uid},{$cid},{$likes},NOW(),1)
					";
			$this->apps->query($sql);
			$this->logger->log($sql);
			if($this->apps->getLastInsertId()>0) return true;
			
		}
		return false;
	}
	
	
	
	
	function getFavorite($cid=null){
		global $CONFIG;
		if($cid==null) $cid = intval($this->apps->_p('cid'));
		if($cid){
			$cidin = " AND contentid IN ({$cid}) ";
		}
			$sql ="
					SELECT  contentid,userid,likes FROM {$this->dbshema}_news_content_favorite WHERE n_status=  1 {$cidin}  
					";
		
				$qData = $this->apps->fetch($sql,1);
				
				if($qData) {
					$this->logger->log("have favorite");
					foreach($qData as $val){
						
						$arrUserid[$val['userid']] = $val['userid'];	
					}
									
					$users = implode(",",$arrUserid);
					
					
					$sql = "SELECT * FROM social_member WHERE id IN ({$users})  AND n_status = 1 ";
					$qDataUser = $this->apps->fetch($sql,1);
					if($qDataUser){
								
						foreach($qDataUser as $val){
							$userDetail[$val['id']]['name'] = $val['name'];
							$userDetail[$val['id']]['img'] = $val['img'];
							if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/tiny_{$val['img']}")) $val['img'] = false;
							if($val['img']) $userDetail[$val['id']]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/tiny_".$val['img'];
							else $userDetail[$val['id']]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/default.jpg";
				
							
						}
						
						foreach($qData as $key => $val ){						
							if(array_key_exists($val['userid'],$userDetail)) $qData[$key]['userdetail'] = $userDetail[$val['userid']];
							else $qData[$key]['userdetail'] = false;
							$data[$val['contentid']][$val['userid']]= $qData[$key];
						}
						
					
						if($data){
							foreach($data as $key => $val){
								$favoriteData[$key]['total']=count($val);								
								$favoriteData[$key]['users']=$val;
								if(array_key_exists($this->uid,$data[$key])) $favoriteData[$key]['mylikes']=$data[$key][$this->uid];
								else $favoriteData[$key]['mylikes']=false;
								
								
							}
						}
						
							
							return $favoriteData;
						}
				}
		return false;
			
			
	}	
	
	function addComment($cid=null,$comment=null){
	
		if($cid==null) $cid = intval($this->apps->_p('cid'));
		if($comment==null) $comment = strip_tags($this->apps->_p('comment'));
		// $this->logger->log('pre comment '.$cid.' comment: '.$comment );
		if(!$this->uid) return false;
		$uid = intval($this->uid);
			// $this->logger->log('pre comment found uid '.$uid );
		if($cid&&$comment){
			// $this->logger->log('do comment ' );
			if($comment=="") return false;
			// $this->logger->log('save comment '.$comment);
			global $CONFIG;
				//bot system halt
				$sql = "
				SELECT id,date,count(id) total 
				FROM {$this->dbshema}_news_content_comment 
				WHERE userid={$uid} 
				ORDER BY id DESC LIMIT 1";
				
				$lastInsert = $this->apps->fetch($sql);
				// $this->logger->log($lastInsert['total']);
				if($lastInsert['total']==0) $divTime = $CONFIG['DELAYTIME']+1;
				else $divTime = strtotime(date("Y-m-d H:i:s")) - strtotime($lastInsert['date']); 
				if($CONFIG['DELAYTIME']==0) $divTime = $CONFIG['DELAYTIME']+1;
				// $this->logger->log(date("Y-m-d H:i:s").' .... '.$lastInsert['date']);
				if($divTime<=$CONFIG['DELAYTIME']) return false; 
				
				$sql ="
						INSERT INTO {$this->dbshema}_news_content_comment (userid,contentid,comment,date,n_status) 
						VALUES ({$uid},{$cid},'{$comment}',NOW(),1)
						";
				$this->apps->query($sql);
				$this->logger->log($sql);
				if($this->apps->getLastInsertId()>0) {
					
					
					return true;
				}
				
		} else $this->logger->log($cid.'|'.$comment);
		// $this->logger->log('failed do comment ');
		return false;	
	}
	
	function getallusercommentoncontent($contentid=false){
				if($contentid==false) return false;
				
				$sql ="	SELECT userid
						FROM {$this->dbshema}_news_content_comment 
						WHERE n_status = 1 AND contentid={$contentid}
						GROUP BY userid ";
				$qData = $this->apps->fetch($sql,1);
				$data = false;
				if($qData) return $qData;
				else return false;
	}
	
	function setWinnerChallenge(){
		global $CONFIG;
		
		if(intval($this->apps->_p('cid'))==null) return false;
		if(intval($this->apps->_p('cid_user'))==null) return false;
		if(intval($this->apps->_p('userid'))==null) return false;
		$cid = intval($this->apps->_p('cid'));
		$cid_user = intval($this->apps->_p('cid_user'));
		$userid = intval($this->apps->_p('userid'));
		
		if(!$this->uid) return false;
		$uid = intval($this->uid);	
		
		if($cid_user && $userid){
			// GET NILAI POINT
			$point = $this->PointChallenge(null,$cid,"getpoint");
			
			//bot system halt
			$sql = "SELECT id,datetimes,count(id) total FROM my_challenge WHERE userid = {$userid} ORDER BY id DESC LIMIT 1";
			$lastInsert = $this->apps->fetch($sql);
			$this->logger->log($lastInsert['total']);
			if($lastInsert['total']==0) $divTime = $CONFIG['DELAYTIME']+1;
			else $divTime = strtotime(date("Y-m-d H:i:s")) - strtotime($lastInsert['datetimes']);
			if($CONFIG['DELAYTIME']==0) $divTime = $CONFIG['DELAYTIME']+1;
			$this->logger->log(date("Y-m-d H:i:s").' .... '.$lastInsert['datetimes']);
			if($divTime<=$CONFIG['DELAYTIME']) return false;
			
			$sql ="
				INSERT INTO my_challenge (userid,contentid,brandid,datetimes,point,n_status) 
				VALUES ({$userid},{$cid_user},'{$uid}',NOW(),'{$point['prize']}',1)
			";
			// pr($sql);exit;
			$this->apps->query($sql);
			$this->logger->log($sql);
			if($this->apps->getLastInsertId()>0) return true;
			
		} else $this->logger->log($cid.'|'.$uid);
		return false;
	}
	
	function cekChallengeWinner($userid=null,$cid=null){
		global $CONFIG;
		
		if($userid==null) return false;
		if($cid==null) return false;
		if(!$this->uid) return false;
		$uid = intval($this->uid);
		
		$data = false;
		if($userid && $cid){
			$sql = "SELECT * FROM my_challenge WHERE userid = {$userid} AND contentid = {$cid} AND n_status = 1 ORDER BY id DESC LIMIT 1";			
			$data = $this->apps->fetch($sql);
			if ($data) $data = true;
			else $data = false;
		}
		
		return $data;
	}
	
	function getComment($cid=null,$all=false,$start=0,$limit=50,$summary=false){
		// return $cid;
		global $CONFIG;
		if($cid==null) $cid = intval($this->apps->_request('id'));
		
		if(!$summary) if(intval($this->apps->_request('start'))>=0)$start = intval($this->apps->_request('start'));
	
		if($cid){			
			if($all==true) $qAllRecord = "";
			else  $qAllRecord = " LIMIT {$start},{$limit} ";
			if($all==true) $qFieldRecord = " count(*) total , contentid ";
			else  $qFieldRecord = " * ";
			if($all==true) $qGroupRecord = " GROUP BY contentid ";
			else  $qGroupRecord = "  ";
			
			$sql ="	SELECT {$qFieldRecord} 
					FROM {$this->dbshema}_news_content_comment 
					WHERE contentid IN ({$cid}) AND n_status = 1
					{$qGroupRecord}
					ORDER BY date DESC {$qAllRecord}
					";
			// pr($sql);
			$qData = $this->apps->fetch($sql,1);
			
			$this->logger->log($sql);
			if($qData) {
			
				if($all==true) {
					foreach($qData as $val){
						$arrComment[$val['contentid']] = $val['total'];
					}
					return $arrComment;
				}
				
				
				foreach($qData as $val){
					$arrUserid[] = $val['userid'];				
				}
				
				$users = implode(",",$arrUserid);
				
				
				$sql = "SELECT * FROM social_member WHERE id IN ({$users})   ";
				$qDataUser = $this->apps->fetch($sql,1);
				if($qDataUser){
					// $userRate = $this->getUserFavorite($cid,$users);
					$userRate = false;
					
					foreach($qDataUser as $val){
						$userDetail[$val['id']]['name'] = ucwords($val['name']." ".$val['last_name']);
						$userDetail[$val['id']]['img'] = $val['img'];
						if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/{$val['img']}")) $val['img'] = false;
						if($val['img']) $userDetail[$val['id']]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/".$val['img'];
						else $userDetail[$val['id']]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/default.jpg";
			 
					}
					
					foreach($qData as $key => $val){
						/* html entity decode */
						$qData[$key]['comment'] = html_entity_decode($qData[$key]['comment']);
						$arrComment[$val['contentid']][$key] = $qData[$key];
						
						if(array_key_exists($val['userid'],$userDetail)){
							$arrComment[$val['contentid']][$key]['name'] = $userDetail[$val['userid']]['name'] ;
							$arrComment[$val['contentid']][$key]['img'] = $userDetail[$val['userid']]['img'] ;
							$arrComment[$val['contentid']][$key]['image_full_path'] = $userDetail[$val['userid']]['image_full_path'] ;
							
							if($userRate){
								if(array_key_exists($val['contentid'],$userRate)) {
									if(array_key_exists($val['userid'],$userRate[$val['contentid']]))$arrComment[$val['contentid']][$key]['favorite'] = $userRate[$val['contentid']][$val['userid']] ; 
									else $arrComment[$val['contentid']][$key]['favorite'] = 0;
								}else $arrComment[$val['contentid']][$key]['favorite'] = 0;
							}else  $arrComment[$val['contentid']][$key]['favorite'] = 0;
						}
					}
				
					$qData = null;
					// pr($arrComment);exit;
					return $arrComment;
				}
			}			
		}
		return false;	
	}
	
	function getCategoryVenue(){
		global $CONFIG;				
		
		$sql = "SELECT * FROM {$this->dbshema}_venue_category WHERE id <> 0 ORDER BY name ";
		$qData = $this->apps->fetch($sql,1);
		if($qData){
			return $qData;
		}
		return false;
	}
	
	function getChallengeHashtag($start=0,$limit=5,$tags=null,$type=null){
		global $CONFIG;
		
		if($tags==null) return false;
		if($type==null) return false;
		if(intval($this->apps->_request('start'))>=0)$start = intval($this->apps->_request('start'));
		$tags = strip_tags($tags);
		$limit = intval($limit);
		
		$result['result'] = false;
		$result['total'] = 0;
		
		$tags = rtrim($tags);
		$tags = ltrim($tags);
		
		if(strpos($tags,' ')) $parseTags = explode(' ', $tags);
		else $parseTags = false;
		
		if(is_array($parseTags)) $tags = $tags.'|'.trim(implode('|',$parseTags));
		else  $tags = trim($tags);
	
		if($tags){
			//GET TOTAL ARTICLE BY HASHTAG
			$sql = "SELECT count(*) total FROM {$this->dbshema}_news_content anc  WHERE articleType = {$type} AND (title REGEXP '{$tags}' OR brief REGEXP '{$tags}') AND n_status=1";
			$total = $this->apps->fetch($sql);
			if(intval($total['total'])<=$limit) $start = 0;
			
			//GET ARTICLE BY HASHTAG
			$sql = "
				SELECT * FROM {$this->dbshema}_news_content anc	WHERE articleType = {$type} AND (title REGEXP '{$tags}' OR brief REGEXP '{$tags}') AND n_status=1 ORDER BY posted_date DESC , id DESC 
				LIMIT {$start},{$limit}
			";
			//pr($sql);exit;
			$rqData = $this->apps->fetch($sql,1);
		
			if($rqData) {
				//CEK DETAIL IMAGE FROM FOLDER
				//IF IS ARTICLE, IMAGE BANNER DO NOT SHOWN
				foreach($rqData as $key => $val){
					$rqData[$key]['imagepath'] = false;
					if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$rqData[$key]['imagepath'] = "event";
					if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$rqData[$key]['imagepath'] = "banner";
					if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$rqData[$key]['imagepath'] = "article";					
					
					if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}")) 	$rqData[$key]['banner'] = false;
					else $rqData[$key]['banner'] = true;
				
					//CHECK FILE SMALL
					if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}{$rqData[$key]['imagepath']}/small_{$val['image']}")) $rqData[$key]['image'] = "small_{$val['image']}";
					
					//PARSEURL FOR VIDEO THUMB
					$video_thumbnail = false;
					if($val['url']!='')	{
						//PARSER URL AND GET PARAM DATA
						$parseUrl = parse_url($val['url']);
						if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
						else $parseQuery = false;
						if($parseQuery) {
							if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
						} 
						$rqData[$key]['video_thumbnail'] = $video_thumbnail;
					}else $rqData[$key]['video_thumbnail'] = false;
					
					if($rqData[$key]['imagepath']) $rqData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/".$rqData[$key]['imagepath']."/".$rqData[$key]['image'];
					else $rqData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/article/default.jpg";
				}
				
				if($rqData) $qData=	$this->getStatistictArticle($rqData);
				else $qData = false;
			}else $qData = false;	
		}		
		
		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
		return $result;
	}
	
	function getAttending($cid=null){
		if($cid==null) $cid = intval($this->apps->_p('cid'));
		if($cid){
			$cidin = " AND contestid IN ({$cid}) ";
		}
			$sql ="
					SELECT count(*) total,contestid FROM my_contest WHERE n_status=  1 {$cidin}  GROUP BY contestid
					";
		
				$qData = $this->apps->fetch($sql,1);
				if($qData) {
					$this->logger->log("have attending");
					foreach($qData as $val){
						$attendingData[$val['contestid']]=$val['total'];
					}
					
						return $attendingData;
				}
		return false;
	}
	
	function getBanner($page="home",$type="slider_header",$featured=0,$limit=4){
		global $CONFIG;
		$sql ="SELECT * FROM {$this->dbshema}_news_content_banner_type WHERE type ='{$type}' AND n_status=1 LIMIT 1 "; 
		
		$this->logger->log($sql);
		$bannerType = $this->apps->fetch($sql);	
		if(!$bannerType) return false;
		$sql ="SELECT * FROM {$this->dbshema}_news_content_page WHERE pagename = '{$page}' AND n_status = 1 LIMIT 1";		
		$this->logger->log($sql);
		$bannerPage = $this->apps->fetch($sql);
		if(!$bannerPage) return false;	
	 
		$sql = "SELECT * FROM {$this->dbshema}_news_content_banner WHERE page LIKE '%{$bannerPage['id']}%' AND type IN ({$bannerType['id']}) AND n_status IN ({$this->server}) ";
		$this->logger->log($sql);
		$banner = $this->apps->fetch($sql,1);		
		
		if(!$banner) return false;
		foreach($banner as $val){
			$arrBannerID[] = $val['parentid'];
			$banners[$val['parentid']] = $val;
		}
	
		$bannerId = implode(",",$arrBannerID) ;
		
		$sql = "	
		SELECT anc.id,anc.content,anc.brief,anc.title,anc.image,anc.posted_date ,anc.categoryid,ancc.category,anc.articleType,anc.slider_image,anc.thumbnail_image,anct.content_name,anct.type typeofarticlename,anc.url
		FROM {$this->dbshema}_news_content anc
		LEFT JOIN {$this->dbshema}_news_content_category ancc ON ancc.id = anc.categoryid
		LEFT JOIN {$this->dbshema}_news_content_type anct ON anct.id = anc.articleType
		WHERE anc.id IN ({$bannerId}) AND anc.n_status IN ({$this->server})
		ORDER BY posted_date DESC  LIMIT {$limit}
		";
		
		$this->logger->log($sql);
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		foreach($qData as $key => $val){
			if(array_key_exists($val['id'],$banners)) $qData[$key]['banner'] = $banners[$val['id']];			
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/thumb_{$val['slider_image']}")) $qData[$key]['banner_thumb'] = true;
			else  $qData[$key]['banner_thumb'] = false;
				// pr("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/thumb_{$val['slider_image']}");
			//parseurl for video thumb
				$video_thumbnail = false;
				if($val['articleType']==3&&$val['url']!='')	{				
					//parser url and get param data
						$parseUrl = parse_url($val['url']);
						if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
						else $parseQuery = false;
						if($parseQuery) {
							if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
						} 
						$qData[$key]['video_thumbnail'] = $video_thumbnail;
				}else $qData[$key]['video_thumbnail'] = false;		
			
		}
		
		return $qData;
	}	
	
	function getCity($province=NULL, $type=NULL, $cityID=NULL){
		if($cityID){
			$filter = 'AND id = '.$cityID;
			$default = "SELECT * FROM {$this->dbshema}_city_reference WHERE  provinceid<>0  AND city <> '(NOT SPECIFIED)' AND id ={$cityID} ORDER BY city";
			$qDefault = $this->apps->fetch($default);
		}else{
			$filter = '';
		}
		
		if ($province) {
			$filterprov = " provinceid = {$province}";
		} else {
			$filterprov = "";
		}
		
		$sql ="SELECT * FROM {$this->dbshema}_city_reference WHERE provinceid <> 0 AND city <> '(NOT SPECIFIED)' {$filterprov} {$filter}  ORDER BY city";
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		$this->logger->log($sql);
		
		if($type=='topup'){
			array_unshift($qData, $qDefault);
		}		
		
		if(!$qData) return false;
		return $qData;
	}
	
	function getTypeContent(){
		$sql_type ="SELECT id,content_name type FROM {$this->dbshema}_news_content_type WHERE id IN ('3','4','5') ORDER BY id";
		$qData = $this->apps->fetch($sql_type,1);
		
		if(!$qData) return false;
		return $qData;
	}
	
	function getEventArticleType(){
		$sql_type ="SELECT * FROM {$this->dbshema}_news_content_type WHERE content = 4 ORDER BY id";
		$qData = $this->apps->fetch($sql_type,1);
		
		if(!$qData) return false;
		return $qData;
	}
	
	function getProvince($type=null,$id=null){
		if($id){
			$filter = 'WHERE id <> '.$id;
			$default = "SELECT * FROM {$this->dbshema}_province_reference WHERE id = ".$id;
			$qDefault = $this->apps->fetch($default);
		}else{
			$filter = '  ';
		}
		// pr($id);
		if($type=='topup'){$filterProvince = 'AND id IN (1,2,3,4,5,6,7,8,9,10,11,12)';}
		else if($type=='coverage'){$filterProvince = 'AND id IN (1,2,3,4,5,6,8,9,10,13,14,16,19,21,24,30)';}
		else if($type=='coveragemap'){$filterProvince = 'WHERE id IN (1,2,3,4,5,6,7,8,9,10,11,12,16,17,25,26,27,28,29,30)';}
		else{$filterProvince = '';}
	
		$sql ="SELECT * FROM {$this->dbshema}_province_reference {$filter} {$filterProvince}";
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		
		if($type=='coverage' || $type=='topup'){
			array_unshift($qData, $qDefault);
		}
		
		
		if(!$qData) return false;
		return $qData;
	}
	
	function getProvinceVenue($type=null,$id=null){
		$sql ="SELECT provinceName FROM {$this->dbshema}_venue_master WHERE n_status = 1 AND provinceName <> '' GROUP BY provinceName ORDER BY provinceName";
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		
		if(!$qData) return false;
		return $qData;
	}
	
	function getCityVenue($type=null,$id=null){
		$sql ="SELECT city FROM {$this->dbshema}_venue_master WHERE n_status = 1 AND city <> '' GROUP BY city ORDER BY city";
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		
		if(!$qData) return false;
		return $qData;
	}

	function getListProvinceVenue($type=null,$id=null){
		$sql ="SELECT province FROM {$this->dbshema}_province_reference WHERE id <> 0 ORDER BY id ASC";
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		
		if(!$qData) return false;

		foreach ($qData as $key => $value) {
			$qData[$key]['province']=ucwords(strtolower($value['province']));
		}

		return $qData;
	}
	
	function getListCityVenue($provinceName=null){
		$provinceName = strip_tags($provinceName);
		$sql="SELECT id FROM {$this->dbshema}_province_reference
				WHERE province='{$provinceName}' LIMIT 1";
		$this->logger->log($sql);
		$provinceID = $this->apps->fetch($sql);

		if(!$provinceID) return false;

		$provinceID = intval($provinceID['id']);
		$sql ="SELECT city FROM {$this->dbshema}_city_reference WHERE provinceid = {$provinceID}";
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		
		if(!$qData) return false;

		foreach ($qData as $key => $value) {
			$qData[$key]['city']=ucwords(strtolower($value['city']));
		}

		return $qData;
	}
	
	// add by putra featured article
	function getArticleFeatured() {
	
		global $CONFIG;
		
		$brandid = 5;
		$brandarrdetail = false;
		$branddetail = @$this->apps->user->branddetail;
		if($branddetail){
			foreach($branddetail as $val){
					$brandarrdetail[$val->ownerid] = $val->ownerid;
			}
		}
			
		if($brandarrdetail){
			$brandid = implode(',',$brandarrdetail);
		}
		
		$sql = "SELECT id,title,brief,image,thumbnail_image,slider_image,posted_date,file,url,fromwho,tags,authorid,topcontent,cityid ,articleType,can_save,content
		FROM {$this->dbshema}_news_content 
		WHERE 
		articleType IN (4) 
		AND topcontent = 1 
		AND n_status=1 
		AND authorid IN ({$brandid})
		ORDER BY posted_date DESC ,id DESC  LIMIT 1";
		 
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		//CEK DETAIL IMAGE FROM FOLDER
		//IF IS ARTICLE, IMAGE BANNER DO NOT SHOWN
		foreach($qData as $key => $val){
			$qData[$key]['imagepath'] = false;
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";	
		
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}")) $qData[$key]['banner'] = false;
			else $qData[$key]['banner'] = true;	
			
			//CHECK FILE
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $qData[$key]['hasfile'] = true;
			else $qData[$key]['hasfile'] = false;				
			
			//PARSEURL FOR VIDEO THUMB
			if($val['articleType']==3&&$val['url']!='')	{
				//PARSER URL AND GET PARAM DATA
				$parseUrl = parse_url($val['url']);
				if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
				else $parseQuery = false;
				if($parseQuery) {
					if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
					else $video_thumbnail= false;
				}else $video_thumbnail = false;
				$qData[$key]['video_thumbnail'] = $video_thumbnail;
			}else $qData[$key]['video_thumbnail'] = false;		
			
			if($qData[$key]['imagepath']) $qData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/".$qData[$key]['imagepath']."/".$qData[$key]['image'];
			else $qData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/article/default.jpg";
		}
		
		if($qData) $qData =	$this->getStatistictArticle($qData);
		else return false;
		return $qData;
	}
	
	function getArticleContent($start=null,$limit=10,$contenttype=0,$topcontent=array(0,3),$articletype=false,$groupby=false,$author=false,$allcontent=false,$datetimes=false,$usingbadetail=true,$usingbranddetail=true,$checkin=true,$readarticle=false) {
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$uid = intval($this->apps->_request('uid'));
		if($uid==0)	{
			$uidType = $this->uid;
			$uid = $this->uid;
		}
		
		$contenttype = intval($contenttype);
		$limit = intval($limit);
		$topcontent = implode(',',$topcontent);
		
		//RUN FILTER ENGINE, KEYWORDSEARCH , CONTENTSEARCH 
		$filter = $this->apps->searchHelper->filterEngine($limit,$groupby,$author);
		$typeid = strip_tags($this->checkPage($contenttype,$articletype));		
		//pr($);exit;
		if(!$typeid) return false;
		
		$search = "";
		$startdate = "";
		$enddate = "";
		$authorid = "";		
	
		if ($this->apps->_p('search')) {
			if ($this->apps->_p('search')!="Search...") {
				$search = rtrim(strip_tags($this->apps->_p('search')));
				$search = ltrim($search);
				
				if(strpos($search,' ')) $parseSearch = explode(' ', $search);
				else $parseSearch = false;
				
				if(is_array($parseSearch)) $search = $search.'|'.trim(implode('|',$parseSearch));
				else  $search = trim($search);
				
				$search = " AND (anc.title REGEXP  '{$search}' OR anc.brief REGEXP  '{$search}' OR anc.content REGEXP  '{$search}') ";
			}
		}
		if ($this->apps->_p('startdate')) {
			$start_date = $this->apps->_p('startdate');
			$startdate = " AND DATE(anc.posted_date) >= DATE('{$start_date}') ";
		}
		if ($this->apps->_p('enddate')) {
			$end_date = $this->apps->_p('enddate');
			$enddate = " AND DATE(anc.posted_date) <= DATE('{$end_date}') ";
		}
		$authorid = " AND anc.authorid = {$uid}";
		if($contenttype==3) $qStatus = "  AND anc.n_status IN (1,0) ";
		else $qStatus = "  AND anc.n_status = 1 ";
		$qHirarki ="";
		
		if ($this->apps->_p('publishedtype')) {
			$publishedtype = intval($this->apps->_p('publishedtype'));
			$qStatus = " AND anc.n_status =  {$publishedtype}  ";
		}
		
		if($filter['uidsearch']!='') $qHirarki = " {$filter['uidsearch']} ";
		else {
			$leadertype = intval($this->apps->user->leaderdetail->type);
			if($leadertype){
				
						if(in_array($articletype,$this->modzeropage)){
						
							$auhtorarrid[$uid] = $uid;
							
								$auhtorminion = @$this->apps->user->branddetail;
								if($auhtorminion){
									foreach($auhtorminion as $val){
											$auhtorarrid[$val->ownerid] = $val->ownerid;
									}
								}
							
							
							$auhtorminion = @$this->apps->user->areadetail;
							if($auhtorminion){
								foreach($auhtorminion as $val){
										$auhtorarrid[$val->ownerid] = $val->ownerid;
								}
							}		
							
							$auhtorminion = @$this->apps->user->pldetail;
							if($auhtorminion){
								foreach($auhtorminion as $val){
										$auhtorarrid[$val->ownerid] = $val->ownerid;
								}
							}	
							
							$auhtorminion = @$this->apps->user->badetail;
							// pr($auhtorminion);
							if($auhtorminion){
								foreach($auhtorminion as $val){
										$auhtorarrid[$val->ownerid] = $val->ownerid;
								}
							}	
							
						
							if(is_array($auhtorarrid)) 	{
								// pr($minionarr);
								$authorids = implode(',',$auhtorarrid);
							}else $authorids = $uid;
							
							$qHirarki = " AND ( anc.authorid IN ({$authorids}) OR EXISTS ( SELECT contentid FROM {$this->dbshema}_news_content_tags WHERE friendid IN ({$authorids}) AND friendtype=1  AND contentid=anc.id) ) ";
						// $qHirarki = "";
						}
				 
			}
		}
		
		$qCheckCheckin = "";
		
		$typeofsort = "DESC";
		$qCategories = " ";
		$qSort = "  anc.posted_date DESC   ";

		if($articletype=='plan'){
			$typeofsort = "ASC";
			$brands = strip_tags($this->apps->_request('category'));
			$month = intval($this->apps->_request('month'));
			$leadertype = intval($this->apps->user->leaderdetail->type);
			$qStatus = "  AND anc.n_status IN (0,1,2) ";		
			if($leadertype==4) {
				if($datetimes)  $qStatus = "  AND anc.n_status IN (0) ";		
			}
			if($leadertype==1) $qStatus = "  AND anc.n_status IN (1) ";	
			if($datetimes) $qStatus .= "  AND anc.posted_date >= DATE_SUB(NOW() , INTERVAL 1 DAY )  ";		
			else $qStatus .= "  AND DATE(anc.posted_date) >= DATE_SUB( DATE(NOW()), INTERVAL 15 DAY ) ";		
			if($month!=0)	$qStatus .= " AND MONTH(anc.posted_date) = {$month} ";
		
			if($brands=='cocreation') $qCategories = " AND pages.type IN ({$this->cocreationtypeid}) ";
			if($brands=='brands') $qCategories = " AND pages.type IN ({$this->brandtypeid}) ";
			if($brands=='ba') $qCategories = " AND pages.type IN ( 1 ) ";
			if(!$usingbadetail) $qCategories = " AND pages.type NOT IN ( 1,100 ) ";
			if(!$usingbadetail&&!$usingbranddetail) $qCategories = " AND pages.type NOT IN ( 1,100,{$this->brandtypeid} ) ";
			if($datetimes) $qSort = "  anc.posted_date {$typeofsort}, anc.id {$typeofsort} ";
			else  $qSort = " anc.posted_date {$typeofsort} , anc.id DESC ";
			
			if(!$checkin){
				/*
					$qCheckCheckin = " AND NOT EXISTS ( 
							SELECT contentid FROM my_checkin mc 
							WHERE mc.contentid=anc.id 
							AND mc.join_date <= DATE_SUB(NOW() , INTERVAL 1 DAY ) 
					) ";
				*/
					$qCheckCheckin = " 
						AND NOT EXISTS ( 
							SELECT contentid FROM my_checkin mc 
							WHERE mc.contentid=anc.id   
						) 
							
						";
			}
			// pr($leadertype);
			if($leadertype==1){
				/* sba friend of sba shown */
				$qHirarki = " AND ( anc.authorid IN ({$uid}) OR EXISTS ( SELECT contentid FROM {$this->dbshema}_news_content_tags WHERE friendid IN ({$uid}) AND friendtype=1  AND contentid=anc.id )) ";
				/* sba friend of sba not shown */
				/*
				$qHirarki = " AND ( anc.authorid IN ({$uid}) OR EXISTS ( SELECT contentid FROM {$this->dbshema}_news_content_tags WHERE friendid IN ({$uid}) AND friendtype=0  AND contentid=anc.id )) ";
				*/
			}
		}
		
		if($typeid=='4'){
			 
				$brandid = 5;
				$brandarrdetail = false;
				$branddetail = @$this->apps->user->branddetail;
				if($branddetail){
					foreach($branddetail as $val){
							$brandarrdetail[$val->ownerid] = $val->ownerid;
					}
				}
					
				if($brandarrdetail){
					$brandid = implode(',',$brandarrdetail);
				}

				if($this->apps->user->leaderdetail->type == 666){
					$brandid = "4,5";
				}
				$qHirarki = " AND anc.authorid IN ({$brandid})  ";
				//pr($qHirarki);exit;
				 
			 
		}
		
		if($allcontent == true )$qArticleType = "";
		else $qArticleType = " anc.articleType IN ({$typeid}) AND ";
		if($typeid=='3') $qCheckCheckin = " OR EXISTS (SELECT contentid FROM my_checkin WHERE contentid=anc.id AND anc.n_status = 1 AND userid IN ({$uid}) ) ";
		
		if($readarticle) {
		
			$sql = "SELECT count(*) total 
			FROM {$this->dbshema}_news_content anc 
			LEFT JOIN my_pages pages ON anc.authorid=pages.ownerid 
			WHERE  {$qArticleType} anc.topcontent IN ({$topcontent}) {$search} {$startdate} {$enddate} {$filter['keywordsearch']} {$filter['categorysearch']} {$filter['citysearch']} {$filter['postedsearch']} {$filter['fromwhosearch']} {$qStatus} {$qHirarki} {$qCategories} {$qCheckCheckin} AND NOT EXISTS ( SELECT action_value FROM tbl_activity_log log WHERE anc.id=log.action_value  AND action_id = 2 AND user_id={$this->uid} LIMIT 1 )
			{$filter['groupbyfilter']}";
			$totalnotif = $this->apps->fetch($sql);
		
		}
		
		//GET TOTAL ARTICLE

		$sql = "SELECT count(*) total 
		FROM {$this->dbshema}_news_content anc 
		LEFT JOIN my_pages pages ON anc.authorid=pages.ownerid 
		WHERE  {$qArticleType} anc.topcontent IN ({$topcontent}) {$search} {$startdate} {$enddate} {$filter['keywordsearch']} {$filter['categorysearch']} {$filter['citysearch']} {$filter['postedsearch']} {$filter['fromwhosearch']} {$qStatus} {$qHirarki} {$qCategories} {$qCheckCheckin}
		{$filter['groupbyfilter']}		";
		$total = $this->apps->fetch($sql);
		
		if(intval($total['total'])<=$limit) $start = 0;
		
		$sql = "
			SELECT 			anc.id,anc.title,anc.brief,anc.image,anc.thumbnail_image,anc.slider_image,anc.posted_date,anc.expired_date,anc.file,anc.url,anc.fromwho,anc.tags,anc.authorid,anc.topcontent,anc.cityid ,anc.articleType,anc.can_save,anc.n_status,pages.type pagetypes
			FROM {$this->dbshema}_news_content anc
			LEFT JOIN my_pages pages ON anc.authorid=pages.ownerid 
			{$filter['subqueryfilter']} 
			WHERE {$qArticleType} anc.topcontent IN ({$topcontent}) {$search} {$startdate} {$enddate} {$filter['keywordsearch']} {$filter['categorysearch']} {$filter['citysearch']} {$filter['postedsearch']} {$filter['fromwhosearch']} {$qStatus} {$qHirarki} {$qCategories}  {$qCheckCheckin}
			{$filter['groupbyfilter']} ORDER BY {$filter['suborderfilter']}  {$qSort}
			LIMIT {$start},{$limit}";
		//pr($sql);exit;
		// pr($usingbadetail);
		$rqData = $this->apps->fetch($sql,1);
		$this->logger->log($sql);
		if($rqData) {
			//CEK DETAIL IMAGE FROM FOLDER
			//IF IS ARTICLE, IMAGE BANNER DO NOT SHOWN
			foreach($rqData as $key => $val){
				$rqData[$key]['imagepath'] = false;
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$rqData[$key]['imagepath'] = "event";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$rqData[$key]['imagepath'] = "banner";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$rqData[$key]['imagepath'] = "article";					
				
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}")) 	$rqData[$key]['banner'] = false;
				else $rqData[$key]['banner'] = true;
			
				//CHECK FILE SMALL
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}{$rqData[$key]['imagepath']}/small_{$val['image']}")) $rqData[$key]['image'] = "small_{$val['image']}";
					
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}{$rqData[$key]['imagepath']}/tiny_{$val['image']}")) $rqData[$key]['image'] = "tiny_{$val['image']}";
				
				
				//PARSEURL FOR VIDEO THUMB
				$video_thumbnail = false;
				if($val['url']!='')	{
					//PARSER URL AND GET PARAM DATA
					$parseUrl = parse_url($val['url']);
					if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
					else $parseQuery = false;
					if($parseQuery) {
						if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
					} 
					$rqData[$key]['video_thumbnail'] = $video_thumbnail;
				}else $rqData[$key]['video_thumbnail'] = false;
				
				if($rqData[$key]['imagepath']) $rqData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/".$rqData[$key]['imagepath']."/".$rqData[$key]['image'];
				else $rqData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/article/default.jpg";
				
				$rqData[$key]['title'] = html_entity_decode($rqData[$key]['title']);
				$rqData[$key]['brief'] = html_entity_decode($rqData[$key]['brief']);
		 
				
			}
			
			if($rqData) $qData=	$this->getStatistictArticle($rqData);
			else $qData = false;
		}else $qData = false;		
	
		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
		if($readarticle) $result['totalnotif'] = intval($totalnotif['total']);
		else  $result['totalnotif'] = 0;
		$totals = intval($total['total']);

		
		if($totals>$start) $nextstart = $start;
		else $nextstart = 0;
		
				
		if($start<=0)$countstart = $limit;
		else $countstart = $limit+$nextstart;
		
		$thenextpage = intval($limit+$nextstart);
		if($totals<=$thenextpage)	$thenextpage = 0;	
		$result['pages']['nextpage'] = $thenextpage;
		$result['pages']['prevpage'] = $countstart-$limit;
		
		return $result;
	}
	
	function getCommentModeration($start=null,$limit=10,$type=null) {
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));		
		$limit = intval($limit);
		$userid = $this->uid;
		
		// GET CONTENT POST
		$sql_content = "SELECT id,title FROM {$this->dbshema}_news_content WHERE  articleType = {$type} AND n_status = 1";
		$qData_content = $this->apps->fetch($sql_content,1);
		$arrPost = false;
		if ($qData_content) {
			foreach ($qData_content as $key => $val) {
				$arrPost[$key] = $val['id'];
			}
		}
		
		if(!$arrPost) return false;
		$cidStr = implode(",",$arrPost);
		
		$search = "";
		$startdate = "";
		$enddate = "";
		
		$qStatus = " AND n_status = 1 ";
		
		if ($this->apps->_g('page')=='moderation') {
			if ($this->apps->_p('search')) {
				if ($this->apps->_p('search')!="Search...") {
					$search = rtrim($this->apps->_p('search'));
					$search = ltrim($search);
					
					if(strpos($search,' ')) $parseSearch = explode(' ', $search);
					else $parseSearch = false;
					
					if(is_array($parseSearch)) $search = $search.'|'.trim(implode('|',$parseSearch));
					else  $search = trim($search);
					
					$search = "AND comment REGEXP '{$search}'";
				}
			}
			if ($this->apps->_p('startdate')) {
				$start_date = $this->apps->_p('startdate');
				$startdate = "AND DATE(date) >= DATE('{$start_date}') ";
			}
			if ($this->apps->_p('enddate')) {
				$end_date = $this->apps->_p('enddate');
				$enddate = "AND DATE(date) <= DATE('{$end_date}') ";
			}
			
				
			if ($this->apps->_p('publishedtype')) {
				$publishedtype = intval($this->apps->_p('publishedtype'));
				$qStatus = " AND n_status   =  {$publishedtype}  ";
			}
		
		}
		
		//GET TOTAL LIST COMMENT
		$sql_total = "SELECT count(*) total FROM {$this->dbshema}_news_content_comment WHERE contentid IN ({$cidStr}) {$search} {$startdate} {$enddate} {$qStatus} ";
		$total = $this->apps->fetch($sql_total);
		
		if(intval($total['total'])<=$limit) $start = 0;
		
		// GET COMMENT POST
		$sql_comment = "SELECT * FROM {$this->dbshema}_news_content_comment WHERE contentid IN ({$cidStr}) {$search} {$startdate} {$enddate} {$qStatus} ORDER BY date DESC LIMIT {$start},{$limit}";
		
		$qData = $this->apps->fetch($sql_comment,1);
		$arrUser = false;
		if ($qData) {
			foreach ($qData as $key => $val) {
				$arrUser[$key] = $val['userid'];
			}
		}
		
		if(!$arrUser) return false;
		$users = implode(",",$arrUser);
		
		$sql = "SELECT * FROM social_member WHERE id IN ({$users})  AND n_status = 1 ";
		$qDataUser = $this->apps->fetch($sql,1);
		if($qDataUser){
			foreach($qDataUser as $val){
				$userDetail[$val['id']]['name'] = $val['name'];
				$userDetail[$val['id']]['img'] = $val['img'];
				if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}user/photo/{$val['img']}")) $val['img'] = false;
				if($val['img']) $userDetail[$val['id']]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/".$val['img'];
				else $userDetail[$val['id']]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/user/photo/default.jpg";
			}
			
			foreach($qData as $key => $val){
				$arrComment[$key] = $val;
				if(array_key_exists($val['userid'],$userDetail)){
					$arrComment[$key]['name'] = $userDetail[$val['userid']]['name'] ;
					$arrComment[$key]['img'] = $userDetail[$val['userid']]['img'] ;
					$arrComment[$key]['image_full_path'] = $userDetail[$val['userid']]['image_full_path'] ;
				}
			}
		}
		
		$result['result'] = $arrComment;
		$result['total'] = intval($total['total']);
		return $result;
	}
	
	function getStatistictArticle($rqData=null){
		
		if($rqData==null) return false;
		global $CONFIG;
		/* path to page */
				
		$socialArrAuthor = false;
	
		$adminProfile = false;
		$socialProfile = false;
		
		$qData = false;
		$cidArr = false;
		$cityData = false;
		$arrCityID = false;
		foreach($rqData as $key => $val){
		
			$cidArr[] = $val['id'];
			if(array_key_exists('cityid',$val)) $arrCityID[$val['cityid']] = intval($val['cityid']);
			
			//get profile array
			$socialArrAuthor[$val['authorid']] = $val['authorid'];
			
					
			$qData[$key] = $val;
			$qData[$key]['ts'] = strtotime($val['posted_date']);
			$qData[$key]['user'] = false;
			// $qData[$key]['comment'] = false;
			$qData[$key]['commentcount'] = 0;
			$qData[$key]['favorite'] = 0;
			$qData[$key]['views'] = 0;
			$qData[$key]['author'] = false;
			$qData[$key]['attending'] = 0;
			$qData[$key]['gallery'] = array();
			$qData[$key]['totalgallery'] = 0;
			$qData[$key]['profilepath'] = false;
			$qData[$key]['cityname'] = false;
			$qData[$key]['friendtags'] = false;
			$qData[$key]['commentlist'] = false;
			$qData[$key]['rating'] = false;
		}		
		
		if(!$cidArr) return false;
		$cidStr = implode(",",$cidArr);		
		
		if(!$arrCityID) return false;
		$cityArr = implode(",",$arrCityID);		
		
		//get profiler
		if($socialArrAuthor){
			$socialStr = implode(",",$socialArrAuthor);
			$socialProfile = $this->getAuthorProfile($socialStr,'social');
		}
		
		if($cityArr){
			$cityData = $this->checkCity($cityArr);
		}
		
		
		//merge profiler
		foreach($qData as $key => $val){
				//user profile
				if($socialProfile) if(array_key_exists($val['authorid'],$socialProfile)) $qData[$key]['author'] = $socialProfile[$val['authorid']];		
				//city data
				if($cityData)  if(array_key_exists($val['cityid'],$cityData)) $qData[$key]['cityname'] = $cityData[$val['cityid']];
				
				$qData[$key]['profilepath'] = "friends";	
				
		}
		
		// favorite or like data
		$favoriteData = $this->getFavorite($cidStr);
		if($favoriteData){
			
			foreach($qData as $key => $val){
				if(array_key_exists($val['id'],$favoriteData)) $qData[$key]['favorite'] = $favoriteData[$val['id']]['total'];			
				
				if(array_key_exists($val['id'],$favoriteData)) {
						foreach($favoriteData[$val['id']]['users'] as $valfav){
							$userfavorites[] = $valfav;
						}					
						$qData[$key]['users']['favorites'] = $userfavorites;
						$userfavorites = false;
				}
				
				// if(array_key_exists($val['id'],$favoriteData)) 	$qData[$key]['users']['favorites'] =$favoriteData[$val['id']]['users'] ;
				
				if(array_key_exists($val['id'],$favoriteData)) $qData[$key]['users']['mylikes'] = $favoriteData[$val['id']]['mylikes'];	
		
			}
			
		}
		
		//comment di list article 
		$commentsData = $this->getComment($cidStr,true);
		
		
		if($commentsData){
			// $commentlimits = count($cidArr);
		
			
			foreach($qData as $key => $val){
					$commentsDataComment = $this->getComment($val['id'],false,0,2,true);
						if(array_key_exists($val['id'],$commentsData)) $qData[$key]['commentcount'] = $commentsData[$val['id']];
						if($commentsDataComment) {							
							if(array_key_exists($val['id'],$commentsDataComment)) {
								foreach($commentsDataComment[$val['id']] as $valcom){
									$commentarray[] =$valcom;
								}
								$qData[$key]['commentlist']=$commentarray;
								$commentarray = false;
								
								/* $qData[$key]['comment'] = $commentsData[$val['id']];
								if(array_key_exists($val['id'],$commentsDataComment)) $qData[$key]['commentlist'] = $commentsDataComment[$val['id']]; */
								
							}
						}
				
			}
			// pr($qData);
		}
		
		// gallery or repository data
		$gallerydata = $this->getContentRepository($cidStr);
		if($gallerydata){
			 // pr($gallerydata);
			foreach($qData as $key => $val){
				
				if(array_key_exists($val['id'],$gallerydata)) $qData[$key]['gallery'] = $gallerydata[$val['id']]['data'];
				if(array_key_exists($val['id'],$gallerydata)) {
					if(array_key_exists('total',$gallerydata[$val['id']])){
						if($val['imagepath']) $total = 1+$gallerydata[$val['id']]['total'];
						else {
							if(array_key_exists($val['id'],$gallerydata)) $qData[$key]['image_full_path'] = $gallerydata[$val['id']]['data'][0]['image_full_path'];
							$total = $gallerydata[$val['id']]['total'];
						}
					}else $total = 0;
					$qData[$key]['totalgallery'] = intval($total);				
				}else {
					if($val['imagepath']) $total = 1;
					else $total = 0;
					$qData[$key]['totalgallery'] = intval($total);
				}
				
			}
			
			
		}
		 // $coverdata = array();
		foreach($qData as $key => $val){
				$coverdata["id"]=$val['id'];
				$coverdata["title"]=$val['title'];
				$coverdata["brief"]=$val['brief']; 
				if(array_key_exists('content',$val))$coverdata["content"]=$val['content'];
				else $coverdata["content"]= "";
				$coverdata["typealbum"]="1"; 
				$coverdata["gallerytype"]="0"; 
				$coverdata["files"]=$val['image']; 
				$coverdata["fromwho"]=$val['fromwho']; 
				$coverdata["otherid"]=$val['id']; 
				$coverdata["authorid"]=$val['authorid']; 
				if(array_key_exists('created_date',$val))$coverdata["created_date"]=$val['created_date'];
				else $coverdata["created_date"]= "";
				if(array_key_exists('n_status',$val))$coverdata["n_status"]=$val['n_status'];
				else $coverdata["n_status"]= ""; 
				$coverdata["image_full_path"]=$val['image_full_path'];
				if(!$gallerydata)$qData[$key]['totalgallery'] = 1;	
				// pr('test');
				array_push($qData[$key]['gallery'],$coverdata);
			 
				// pr($coverdata);
		}
		// pr($qData);
		// get views
		$getTotalViewsArticle = $this->getTotalViewsArticle($cidStr);
		if($getTotalViewsArticle){
			
			foreach($qData as $key => $val){
				if(array_key_exists($val['id'],$getTotalViewsArticle)) $qData[$key]['views'] = $getTotalViewsArticle[$val['id']];
				
			
			}
		
		}
		
		//get views
		$getCheckin = $this->getCheckin($cidStr);
		if($getCheckin){
			
			foreach($qData as $key => $val){
				if(array_key_exists($val['id'],$getCheckin)) $qData[$key]['rating'] = $getCheckin[$val['id']];
				
			
			}
		
		}
		
		//get attending
		$attendingData = $this->getAttending($cidStr);
		if($attendingData){
			
			foreach($qData as $key => $val){
				if(array_key_exists($val['id'],$attendingData)) $qData[$key]['attending'] = $attendingData[$val['id']];
				
			
			}
		
		}	
		
		//get friend tags
		$friendtagsData = $this->getFriendTagged($cidStr,0,false);
		if($friendtagsData){
			
			foreach($qData as $key => $val){
				if(array_key_exists($val['id'],$friendtagsData)) $qData[$key]['friendtags'] = $friendtagsData[$val['id']];
				
			
			}
		
		}
		if($qData) {
			return $qData;
		} else {
		return false;
		}
	}
	
	function checkCity($strCity=null){
			if($strCity==null) return false;
			$sql ="SELECT * FROM {$this->dbshema}_city_reference WHERE id IN ({$strCity}) LIMIT 20 ";
			// pr($sql);
			$qData = $this->apps->fetch($sql,1);
			if(!$qData)return false;
			$rqData = false;
			foreach($qData as $val){
				$rqData[$val['id']] = $val['city'];
			}	
			
			return $rqData;
			
	}
	
	function checkPage($contenttype=0,$articletype=false){
	
		if($articletype==false) $articletype = strip_tags($this->apps->_g('page'));
		
		$sql = "SELECT * FROM {$this->dbshema}_news_content_type WHERE type = '{$articletype}' AND content={$contenttype} LIMIT 1";
		$arrType = $this->apps->fetch($sql,1);
		
		if(!$arrType) return false;
		foreach($arrType as $val){
			$arrtypeid[] = $val['id'];
		}
		
		$typeid = false;
		if($arrtypeid) $typeid = implode(',',$arrtypeid);
		else return false;
		return $typeid;
	}
	
	function getDetailArticle($start=null,$limit=1,$contenttype=false) {		
		global $CONFIG;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$category = intval($this->apps->_request('cid'));
		$id = intval($this->apps->_request('id'));
		$limit = intval($limit);
	
		if($category!=0) $qCategory = " AND categoryid={$category} ";
		else $qCategory = "";
		
		if($id!=0) $qid = " AND acontent.id={$id} ";
		else $qid = ""; 
		
		if($contenttype){
			$contenttype = intval($contenttype);
			$qType = " AND articleType = {$contenttype} ";
		}else $qType = "";
		// $typeid = strip_tags($this->checkPage($contenttype));		
		
		//get total
		$sql = "
		SELECT count(*) total  
		FROM {$this->dbshema}_news_content acontent
		LEFT JOIN {$this->dbshema}_news_content_category acategory ON acontent.categoryid = acategory.id
		WHERE  n_status IN (0,1)  {$qid}  {$qCategory} {$qType} ";
	// pr($sql);
		$totaldata = $this->apps->fetch($sql);
		if(!$totaldata) return false;
		if($totaldata['total']<=0) return false;
		
		$sql = "
		SELECT acontent.*, acategory.point ,acategory.category,anct.type  
		FROM {$this->dbshema}_news_content acontent
		LEFT JOIN {$this->dbshema}_news_content_type anct ON acontent.articleType = anct.id
		LEFT JOIN {$this->dbshema}_news_content_category acategory ON acontent.categoryid = acategory.id
		WHERE  n_status IN (0,1)  {$qid}  {$qCategory} {$qType} 
		ORDER BY posted_date DESC LIMIT {$start},{$limit}";
		// pr($sql);
		$rqData = $this->apps->fetch($sql,1);
		if($rqData){
			//cek detail image from folder
				//if is article, image banner do not shown
			foreach($rqData as $key => $val){
				$rqData[$key]['session_userid'] = intval($this->apps->user->id);
				$rqData[$key]['session_pageid'] = intval($this->apps->user->pageid);
				$untags = $val['tags'];
				$rqData[$key]['un_tags'] = $untags;	
				$rqData[$key]['imagepath'] = false;
				
				
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$rqData[$key]['imagepath'] = "event";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$rqData[$key]['imagepath'] = "banner";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$rqData[$key]['imagepath'] = "article";	
				// pr(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"));
				//check file
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $rqData[$key]['hasfile'] = true;
				else $rqData[$key]['hasfile'] = false;	
				
				//parseurl for video thumb
				$video_thumbnail = false;
				if($val['url']!='')	{
					//parser url and get param data
						$parseUrl = parse_url($val['url']);
						if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
						else $parseQuery = false;
						if($parseQuery) {
							if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
						} 
						$rqData[$key]['video_thumbnail'] = $video_thumbnail;
				}else $rqData[$key]['video_thumbnail'] = false;				

				if($rqData[$key]['imagepath']) $rqData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/".$rqData[$key]['imagepath']."/".$rqData[$key]['image'];
				else $rqData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/article/default.jpg";		
				
				$rqData[$key]['engagementtype'] = $this->getbaengagementstat($val['id'],$val['authorid']);
					
				$rqData[$key]['title'] = html_entity_decode($rqData[$key]['title']);
				$rqData[$key]['brief'] = html_entity_decode($rqData[$key]['brief']);
			}
		}
		if($rqData) $qData=	$this->getStatistictArticle($rqData);
		else $qData = false;
		
		if(!$qData) return false;
		if($this->uid && $qData){
		
				$sql ="
				INSERT INTO {$this->dbshema}_news_content_rank (categoryid ,	point, 	userid ,	date) 
				VALUES ({$qData[0]['categoryid']},{$qData[0]['point']},{$this->uid},NOW())
				
				";
				$this->apps->query($sql);
				
				// job buat bot tracking user preference
				// $sql ="
				// INSERT INTO job_content_preference (user_id ,	content_id, 	n_status) 
				// VALUES ({$this->uid},{$qData['id']},0)
				
				// ";
				
				// $this->apps->query($sql);
		
	
		}
		
		
		if(!$qData) return false;
		
		$result['result'] = $qData;
		$result['total'] = $totaldata['total'];		
		 //pr($result);
		return $result;
	}
	
	
	function getbaengagementstat($cid=false,$authorid=false){
		if(!$authorid) return false;
		$sql ="SELECT count(*) total FROM my_checkin checkin WHERE checkin.contentid={$cid} AND checkin.userid IN ({$authorid}) AND checkin.n_status=1";
		
		$qData = $this->apps->fetch($sql);
		// pr($sql);
		if($qData){
			if($qData['total']>0)	return true;
			else return false;
		}
		return false;
		
	}
	
	function getTotalViewsArticle($cid=null){
		if($cid==null) return false;
		
		$sql = "SELECT COUNT(*) total, action_value as cid FROM tbl_activity_log WHERE action_id=2 AND action_value IN ({$cid}) GROUP BY cid";
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		$arrViewArticle = false;
		foreach($qData as $key => $val){
			$arrViewArticle[$val['cid']] = $val['total'];
		}
		if($arrViewArticle){
			return $arrViewArticle;
		}else return false;
		
	}
	
	function getContentRepository($strId=null,$gallerytype=0,$limit=10){
	
		if($strId==null) return false;
		global $CONFIG;
		$gallerytype = intval($gallerytype);
		$limit = intval($limit);
				
		$sql = "SELECT * FROM  {$this->dbshema}_news_content_repo WHERE otherid IN ({$strId}) AND n_status=1 ORDER BY created_date DESC LIMIT {$limit} ";
		// pr($sql);
		$rqData = $this->apps->fetch($sql,1);
		
		if(!$rqData) return false;
		$qData = false;
		$total = 0;
		foreach($rqData as $key => $val){
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['files']}")) {
				// pr($total);
				$rqData[$key]['image_full_path'] = "{$CONFIG['ADMIN_DOMAIN_PATH']}public_assets/article/{$val['files']}";	
				
				@$qData[$val['otherid']]['total']++;
				// pr($total);
			}else 	{
				$rqData[$key]['image_full_path'] =  $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/article/default.jpg";	
			}
				
			$qData[$val['otherid']]['data'][] = $rqData[$key];
			
		}
		// pr($qData);
		if(!$qData) return false;
		
		return $qData;
	
	}
	
	function getListSongs($start=null,$limit=9) {
		global $CONFIG;
		
		$pid = intval($this->apps->_request('pid'));
		if(!$pid) $pid = intval($this->apps->user->pageid);
		if($pid!=0 || $pid!=null) {		
			if($start==null)$start = intval($this->apps->_request('start'));
			$limit = intval($limit);
			$pages = $this->apps->_g('page');
			$userid = $this->uid;
			if ($pages=='my') {
				//GET IDCONTENT PLAYLIST
				$sql_playlist = "SELECT id,otherid FROM my_playlist where myid = {$userid} AND n_status=1 ORDER BY datetime DESC";
				$dataPlaylist = $this->apps->fetch($sql_playlist,1);
				if ($dataPlaylist) {
					foreach($dataPlaylist as $key => $val){
						$idcontent[] = $val['otherid'];
					}
					if(!$idcontent) return false;
					$arrIdContent = implode(",",$idcontent);
				} else return false;
				
				//GET TOTAL PLAYLIST
				$sql_total = "SELECT count(*) total FROM {$this->dbshema}_news_content WHERE id IN ({$arrIdContent}) AND n_status = 1";
				$total = $this->apps->fetch($sql_total);
				
				if(!$total) return false;
				if($start>intval($total['total'])) return false;
				if(intval($total['total'])<=$limit) $start = 0;
				
				//GET DATA PLAYLIST
				$sql = " SELECT * FROM {$this->dbshema}_news_content WHERE id IN ({$arrIdContent}) AND n_status = 1 LIMIT {$start},{$limit}";
			} elseif ($pages=='myband' || $pages=='mydj' ) {
				$type = $pages=='myband' ? 1 : 4;
				
				//GET TOTAL SONGS
				$sql_total = "SELECT count(*) total FROM {$this->dbshema}_news_content WHERE fromwho = 2 AND articleType = 3 AND file <> '' AND authorid = {$pid} AND n_status = 1";
				$total = $this->apps->fetch($sql_total);
				
				if(!$total) return false;
				if($start>intval($total['total'])) return false;
				if(intval($total['total'])<=$limit) $start = 0;
				
				//GET DATA SONGS
				$sql = "SELECT * FROM {$this->dbshema}_news_content WHERE fromwho = 2 AND articleType = 3 AND file <> '' AND authorid = {$pid} AND n_status = 1 ORDER BY posted_date DESC LIMIT {$start},{$limit}";
			} else return false;
			$rqData = $this->apps->fetch($sql,1);
			
			$no=1;
			if($rqData) {
				foreach ($rqData as $k => $v) {
					$v['no'] = $no++;
					if ($v['filesize']) {
						$durasi = $v['filesize']/1000;
						$v['filesize'] = sprintf("%02d:%02d", ($durasi /60), $durasi %60 );
					} else $v['filesize'] = "";
					$rqData[$k] = $v;
					
					if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$v['file']}")) $rqData[$k]['hasfile'] = true;
					else $rqData[$k]['hasfile'] = false;				
				}
			}
			
			if($rqData) $qData=	$this->getStatistictArticle($rqData);
			else $qData = false;
			
			if(!$qData) return false;
			$arrPlaylist['result'] = $qData;
			$arrPlaylist['total'] = $total['total'];
			return $arrPlaylist;
		}
		return false;
	}
	
	function getMygallery($start=null,$limit=3,$userid=NULL) {
		global $CONFIG;
		if($start==null) $start = intval($this->apps->_g('start'));
		
		if(strip_tags($this->apps->_g('page'))=='my') $userid = $this->uid;
		else $userid = intval($this->apps->_g('uid'));
				
		//get total gallery
		$sql_total = "
			SELECT count(*) total FROM `my_images` mm
			WHERE mm.userid = '{$userid}'
			AND mm.n_status = 1
		";
		$total = $this->apps->fetch($sql_total);
		
		if(!$total) return false;
		if($start>intval($total['total'])) return false;
		if(intval($total['total'])<=$limit) $start = 0;
		
		$sql = "
			SELECT mm.*,nc.title,nc.brief,nc.image,nc.file,nc.articleType,ct.content as typecontent,ct.type as typeofarticle,nc.fromwho,nc.authorid,nc.posted_date , nc.url
			FROM `my_images` mm
			LEFT JOIN {$this->dbshema}_news_content nc ON mm.contentid = nc.id
			LEFT JOIN {$this->dbshema}_news_content_type ct ON nc.articleType = ct.id
			WHERE mm.userid = '{$userid}' AND mm.type = 0
			AND mm.n_status = 1 ORDER BY mm.date DESC LIMIT {$start},{$limit}
		";
		
		$rqData = $this->apps->fetch($sql,1);
		if(!$rqData) return false;
		foreach ($rqData as $key => $val) {
			if ($val['typecontent']==0) {
				$val['typecontent'] = "article";
			} elseif($val['typecontent']==2) {
				$val['typecontent'] = "banner";
			} elseif($val['typecontent']==4) {
				$val['typecontent'] = "event";
			} else {
				$val['typecontent'] = "";
			}
			$val['id_image'] = $val['id'];
			$val['id'] = $val['contentid'];
			
			$rqData[$key] = $val;
			
			$rqData[$key]['imagepath'] = false;
								
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$rqData[$key]['imagepath'] = "event";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$rqData[$key]['imagepath'] = "banner";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$rqData[$key]['imagepath'] = "article";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$rqData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$rqData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$rqData[$key]['image'] = "small_{$val['image']}";	
			
			$video_thumbnail = false;
			if($val['url']!='')	{
				//parser url and get param data
				$parseUrl = parse_url($val['url']);
				if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
				else $parseQuery = false;
				if($parseQuery) {
					if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
				} 
				$rqData[$key]['video_thumbnail'] = $video_thumbnail;
			}else $rqData[$key]['video_thumbnail'] = false;		
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $rqData[$key]['hasfile'] = true;
			else $rqData[$key]['hasfile'] = false;		
			
			$rqData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH'].$rqData[$key]['imagepath'];			
		}
		//pr($rqData);
		if($rqData) $qData=	$this->getStatistictArticle($rqData);		
		else $qData = false;
		
		if(!$qData) return false;
		$arrGallery['result'] = $qData;
		$arrGallery['total'] = $total['total'];
		return $arrGallery;
	}
	
	function hapusmygallery(){
		$cid = $this->apps->_p('cid');
		$sql = "UPDATE my_images set n_status = 0 WHERE id = {$cid}";
		if ($this->apps->query($sql)) {
			$data = array("status"=>1);
		} else {
			$data = array("status"=>0);
		}
		
		return $data;
	}
	
	function addUploadImage($data=false,$type=NULL){
		global $LOCALE;
		$fromwho =1;
		$type = intval($this->apps->_p('type'));
	
		$brand = intval($this->apps->_p('brand'));
		$brand_id = intval($this->apps->_p('brand_id'));
		
		$title = strip_tags($this->apps->_p('title'));
		// if($title=='') return false;
		$description = strip_tags($this->apps->_p('desc'));
		$tags = strip_tags($this->apps->_p('tags'));
		$brief = strip_tags($this->apps->_p('brief'));
		if($brief=='') $brief = $this->wordcut($description,10);
		$posted_date = strip_tags($this->apps->_p('posted_date'));
		$posted_date_times = strip_tags($this->apps->_p('times'));
		$expired_date = strip_tags($this->apps->_p('expired_date'));
		$city_event = intval($this->apps->_p('city_event'));
		$topcontent = intval($this->apps->_p('sticky'));
		$type_brand = intval($this->apps->_p('upload_type'));

		if($topcontent==1){
			$upt="UPDATE {$this->dbshema}_news_content
					SET topcontent = 0
					WHERE articleType = 4 AND authorid = {$brand} AND topcontent = 1";
			$this->apps->query($upt);
		}

		$fid[] = $this->apps->_p('fid');
		$fid[] = $this->apps->_p('fidbrand');
		$fid[] = $this->apps->_p('fidarea');
		$fid[] = $this->apps->_p('fidpl');
		
		if($fid) $fid = implode(',',$fid);
		

		$prize = intval($this->apps->_p('prize'));

		$ftype[] = $this->apps->_p('ftype');
		$ftype[] = $this->apps->_p('ftypebrand');
		$ftype[] = $this->apps->_p('ftypearea');
		$ftype[] = $this->apps->_p('ftypepl');
		
		if($ftype) $ftype = implode(',',$ftype);
		
		$image = false;
		$file = false;
		if($data) {
		
			if(array_key_exists('arrImage',$data))	$image = $data['arrImage']['filename'];			
			if(array_key_exists('arrVideo',$data)) $image = $data['arrVideo']['filename'];
			if(array_key_exists('arrFile',$data)) $file = $data['arrFile']['filename'];
		}else{
			if($type_brand>0){
				$image = strip_tags($this->apps->_p('old_image'));
			}
		}
		
		$url = strip_tags($this->apps->_p('url'));		
		
		if(!$this->uid) return false;

		if($brand){
			$authorid = $brand;
		}else{
			$authorid = intval($this->uid);
		}
		
		if(!$authorid) return false;

		
		if($posted_date=='') {
			$posted_date = date('Y-m-d H:i:s');
		} else {
			if($posted_date_times=='') {
				$arrDates = explode(" ",$posted_date);
				if(!is_array($arrDates)) $posted_date = $posted_date." ".date('H:i:s');
			} else {
				$posted_date = $posted_date." ".$posted_date_times;
			}
		}
		if($expired_date=='') $expired_date = $posted_date;
		
		
		// plan  =  0	
		if(in_array($this->apps->_request('page'),$this->modzeropage)) $this->moderation = 0;
		
		$plantypes = $this->apps->user->plantypes;
		if($plantypes=='Brands') $this->moderation = 1;
		
		$leadertype = intval($this->apps->user->leaderdetail->type);
		if($leadertype)	if(in_array($leadertype,$this->approver)) $this->moderation = 1;
		$categoryid = 0;
		
		if($type==5){
			$categoryid = intval($this->apps->_p('categoryid'));
			$captimesplan = date('Y-m-d H:i:s');
		 	$div = floor((strtotime($captimesplan)-strtotime($posted_date))/60);
			
			$this->logger->log("plan times agains server times : {$captimesplan} - {$posted_date} = {$div} ");	
			if($div>-5) return false;
			
		}
		
		// if($leadertype==100) return false;
		
		if($type_brand>0){
			$sql ="
			UPDATE {$this->dbshema}_news_content 
			SET brief='{$brief}',title='{$title}',content='{$description}',image='{$image}',posted_date='{$posted_date}',expired_date='{$expired_date}',topcontent={$topcontent}
			WHERE articleType = 4 AND id = {$brand_id}";
			//var_dump($sql);exit;
		}else{
			$sql ="
			INSERT INTO {$this->dbshema}_news_content (cityid,brief,title,content,tags,image,articleType,created_date,posted_date,expired_date,authorid,fromwho,n_status,url,file,topcontent,categoryid) 
			VALUES ('{$city_event}','{$brief}','{$title}','{$description}','{$tags}','{$image}',{$type},NOW(),'{$posted_date}','{$expired_date}','{$authorid}','{$fromwho}',{$this->moderation},\"{$url}\",\"{$file}\",{$topcontent},{$categoryid})
			";
		}
		
		// pr($sql);exit;
		$this->logger->log($sql);	
		
		if ($this->apps->query($sql)) {
			$this->logger->log($this->apps->getLastInsertId());	
			if($this->apps->getLastInsertId()>0) {
				$message = " {$this->apps->user->name} {$this->apps->user->last_name} {$LOCALE[1]['tagged']} {$title} ";
				$cid = $this->apps->getLastInsertId();
				$this->PointChallenge($prize,$cid);
			
			
				if($fid){	
				
					$arrfid = explode(',',$fid);
					$arrftype = explode(',',$ftype);
					$frienddata = false;
					if(is_array($arrfid)){
						foreach($arrfid as $key => $val){
							$frienddata[$key]['fid'] = $val;
							$frienddata[$key]['ftype'] = $arrftype[$key];
							
						}
						
						if($frienddata){
					
							foreach($frienddata as $val){
								if($val['fid']!=0){
								$this->addFriendTags($cid,$val['fid'],$val['ftype'],false,$message);
							
								}
							}
						
						}
					}else{
						$ftype = intval($ftype);
						if($fid!=0){
							$this->addFriendTags($cid,intval($fid),intval($ftype),false,$message);						
							
						}
					}
			}
						
				
				return true;
			}elseif($type_brand>0){
				return true;
			}
		} else return false;
	}
	
	function getPagesCategory($pageid='photography',$checkpage=true){
		if($checkpage){
			$page = strip_tags($pageid);
			$sql ="SELECT * FROM {$this->dbshema}_news_content_page WHERE pagename='{$page}' LIMIT 1" ;
			// pr($sql);
			$pageData = $this->apps->fetch($sql); 
			if(!$pageData) return false;
			$pageid = intval($pageData['id']);
		}else $pageid = intval($pageid);
		
		$sql ="SELECT * FROM {$this->dbshema}_news_content_category WHERE pageid={$pageid} " ;
		
		$qData = $this->apps->fetch($sql,1);
		
		return $qData ; 
	}
	
	function PointChallenge($prize=null,$cid=null,$act=null){
		
		if ($act=="getpoint" && $cid){
			$sql ="SELECT * FROM {$this->dbshema}_news_content_challenge WHERE contentid = {$cid} AND n_status = 1 LIMIT 1";
			$qData = $this->apps->fetch($sql);
			if ($qData) {
				return $qData;
			} else return false;
		} else {
			$badgeid = intval($this->apps->_p('badgeid'));
			$rewardType = intval($this->apps->_p('rewardType'));
			if($rewardType==1){
				if($badgeid && $cid){
					$sql ="INSERT INTO {$this->dbshema}_news_content_challenge (contentid,badgeid,date,n_status)
						VALUE ('{$cid}','{$badgeid}',NOW(),1)
						";			
						if ($this->apps->query($sql)) {
							return true;
						} else return false;
				}
			}else{				
				if($prize && $cid){
					$sql ="INSERT INTO {$this->dbshema}_news_content_challenge (contentid,prize,date,n_status)
					VALUE ('{$cid}','{$prize}',NOW(),1)
					";			
					if ($this->apps->query($sql)) {
						return true;
					} else return false;
				}
			}
		}
		return false;
	}
	
	function populartags($contenttype=0,$limit=5){
			
			$typeid = strip_tags($this->checkPage($contenttype));
			
			$limit = intval($limit);
			
			$sql ="	SELECT COUNT(*) total,content.id,content.tags
					FROM {$this->dbshema}_news_content content 
					LEFT JOIN tbl_activity_log log ON log.action_value = content.id
					WHERE log.action_id=2  AND content.n_status=1 AND content.articleType IN ({$typeid})
					GROUP BY content.id
					ORDER BY total DESC LIMIT {$limit}
					";
			// pr($sql);
			$qData = $this->apps->fetch($sql,1);
			
			if(!$qData) return false;
			$nametags = false;
			foreach($qData as $key => $val){
				if($val['tags']) $nametags[$val['id']] = $val['tags'];				
			}
			$qData = null;
						
			if($nametags)	return $nametags;
			return false;
	}
	
	function weeklyPopular ($contenttype=0,$limit=9){
			global $CONFIG;
			$typeid = strip_tags($this->checkPage($contenttype));
			
			$limit = intval($limit);
			//get between this week days
				//get monday of this week
					$mondaydate = date("Y-m-d",strtotime('last monday', strtotime('next sunday')));
				
			$sql ="	
					SELECT COUNT(*) total,content.*
					FROM {$this->dbshema}_news_content content 
					LEFT JOIN tbl_activity_log log ON log.action_value = content.id
					WHERE log.date_time BETWEEN '{$mondaydate}' AND DATE_ADD(NOW(),INTERVAL 1 DAY) AND content.n_status=1 AND articleType IN ({$typeid})
					GROUP BY content.id
					ORDER BY total DESC LIMIT {$limit}
					";
	
			$qData = $this->apps->fetch($sql,1);

			if(!$qData) return false;
			foreach($qData as $key => $val){
			$qData[$key]['imagepath'] = false;
								
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			
			
			//parseurl for video thumb
			$video_thumbnail = false;
			if($val['url']!='')	{
				//parser url and get param data
				$parseUrl = parse_url($val['url']);
				if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
				else $parseQuery = false;
				if($parseQuery) {
					if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
				} 
				$qData[$key]['video_thumbnail'] = $video_thumbnail;
			}else $qData[$key]['video_thumbnail'] = false;		
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $qData[$key]['hasfile'] = true;
			else $qData[$key]['hasfile'] = false;			
		}
		if($qData) $qData=	$this->getStatistictArticle($qData);
		else $qData = false;
		return $qData;
	}
	
	
	function addFavorite(){
		
		if(!$this->uid) return false;
		$cid = intval($this->apps->_p("cid"));
		$emoid = intval($this->apps->_p("emoid"));
		if($cid==0) return false;
		
		/*
		$sql = "
		SELECT count(*) total 
		FROM {$this->dbshema}_news_content_favorite
		WHERE userid={$this->uid} AND contentid={$cid} LIMIT 1";
		
		$qData = $this->apps->fetch($sql);
		
		if(!$qData) return false;
		if($qData['total']>0) return false;		
		*/
		
		$sql=" 
				INSERT INTO 
				{$this->dbshema}_news_content_favorite 	(userid ,	contentid 	,likes, 	date ,	n_status  ) 
				VALUES ({$this->uid},{$cid},{$emoid},NOW(),1)
				ON DUPLICATE KEY UPDATE likes={$emoid} , date = NOW()
				";
		$this->apps->query($sql);
		if($this->apps->getLastInsertId()>0) return true;
		return false;
	
	}
	function getFriendTagged($strCid=null,$tagin=0,$authorid=true){
		if(!$this->uid) return false;
		if($strCid==null) return false;
		if($authorid)$quserid = " AND userid={$this->uid} ";
		else $quserid = "";
		$sql = "
		SELECT * 
		FROM {$this->dbshema}_news_content_tags
		WHERE contentid IN ({$strCid}) {$quserid} AND n_status = 1 AND tagin = {$tagin} ";
		
		// pr($sql);
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		$data = false;
		$arrSocialFid = false;
		$arrEntourageFid = false;
				foreach($qData as $val){	
				 // friendid 	friendtype 	date 	tagin 	n_status 
					/* BA */	
					if($val['friendtype']==0) $arrEntourageFid[$val['friendid']] = $val['friendid'];
					/* entourage */
					if($val['friendtype']==1) $arrSocialFid[$val['friendid']] = $val['friendid'];
					
					$frienddata[$val['contentid']][$val['friendtype']][$val['friendid']]= $val;
				}
				$socialdata = false;
				$entouragedata = false;
				if($arrSocialFid) {
					$strsocialfid = implode(',',$arrSocialFid);
					// $this->logger->log($strsocialfid);
					$socialfid = $this->apps->userHelper->socialdata($strsocialfid);
					// $this->logger->log(json_encode($socialfid));
					if($socialfid){
						foreach($socialfid as $key => $val){
							$socialfid[$key]['friendtype'] = 1;
							$socialdata[1][$val['id']]=$socialfid[$key];
						}
					}
				}
				
				if($arrEntourageFid) {
					$strentouragefid = implode(',',$arrEntourageFid);
					// $this->logger->log($strentouragefid);
					$entouragefid = $this->apps->userHelper->entouragedata($strentouragefid,false);
					// $this->logger->log(json_encode($entouragefid));
					if($entouragefid){
						foreach($entouragefid as $key => $val){
								$entouragefid[$key]['friendtype'] = 0;
								$entouragedata[0][$val['id']]=$entouragefid[$key];
						}
					}
				}
				
				if(!$frienddata) return false;
				
				//merge data
				foreach($frienddata as $contentkey => $ftypeval){
					foreach($ftypeval as $keyftype => $ftype){
						foreach($ftype as $key => $val){
						if($socialdata)if(array_key_exists($keyftype,$socialdata)) if(array_key_exists($key,$socialdata[$keyftype]))  $data[$val['contentid']][] = $socialdata[$keyftype][$key];
						if($entouragedata) if(array_key_exists($keyftype,$entouragedata)) if(array_key_exists($key,$entouragedata[$keyftype]))  $data[$val['contentid']][] = $entouragedata[$keyftype][$key];
						 // pr($val);
						}
						
					}
				}
		// $this->logger->log(json_encode($frienddata));
		// $this->logger->log(json_encode($data));
		if($data) return $data;
		return false;
	} 
	
	function addFriendTags($cid=0,$fid=0,$ftype='pass',$gallery=false,$message=false){
		
		if(!$this->uid) return false;
		if( $cid==0 ) $cid = intval($this->apps->_p("cid"));
		if( $fid==0 ) $fid = intval($this->apps->_p("fid"));
		if( $ftype=='pass') $ftype = intval($this->apps->_p("ftype"));
			$gallery = intval($gallery);
		if( $cid==0) return false;
		global $LOCALE;
		 $dontusenotif = false;
		if($message==false) {
			$sql=" SELECT title,articleType FROM {$this->dbshema}_news_content WHERE id={$cid} LIMIT 1";
			$qData = $this->apps->fetch($sql);
			if($qData) $message = " {$this->apps->user->name} {$this->apps->user->last_name} {$LOCALE[1]['tagged']} {$qData['title']} ";
			else $message =  " {$this->apps->user->name} {$this->apps->user->last_name} {$LOCALE[1]['tagged']} ";
			
			if($qData['articleType']==5){
				if($this->apps->user->leaderdetail->type==1) $dontusenotif = true;
			}
		}
		$sql=" 
				INSERT INTO 
				{$this->dbshema}_news_content_tags 	(userid ,	contentid 	,friendid, 	friendtype, date ,	n_status, tagin ) 
				VALUES ({$this->uid},{$cid},{$fid},{$ftype},NOW(),1,{$gallery})
				ON DUPLICATE KEY UPDATE n_status = 1
				";
			// pr($sql);exit;
			$this->logger->log($sql);
			// $this->logger->log($message);
		$this->apps->query($sql);
		if($this->apps->getLastInsertId()>0) {
			if($message){
				// $this->apps->messageHelper->createMessage($fid,$message,$ftype);
				if(!$dontusenotif) $this->apps->notif($message,$cid,$fid,"tags");
			}			
			return true;
		}
		return false;
	
	}
	
	function unFriendTags($cid=0,$fid=0,$ftype='pass',$gallery=false){
		
		if(!$this->uid) return false;
		if( $cid==0 ) $cid = intval($this->apps->_p("cid"));
		if( $fid==0 ) $fid = intval($this->apps->_p("fid"));
		if( $ftype=='pass') $ftype = intval($this->apps->_p("ftype"));
		$gallery = intval($gallery);
		if( $cid==0) return false;
		
		
			
		$sql=" 	UPDATE 
				{$this->dbshema}_news_content_tags SET n_status = 0 WHERE
				userid={$this->uid} AND friendid ={$fid} AND contentid={$cid}  LIMIT 1
				";
			$this->logger->log($sql);
		$data = $this->apps->query($sql);
		if($data) return true;
		return false;
	
	}
	
	function friendTagsSearch(){
		
		$limit = 16;
		$start= intval($this->apps->_request('start'));
		$searchKeyOn = array("name","email","last_name");
		$keywords = strip_tags($this->apps->_request('keywords'));	
		$keywords = rtrim($keywords);
		$keywords = ltrim($keywords);
		
		$realkeywords = $keywords;
		$keywords = '';
		
		if(strpos($keywords,' ')) $parseKeywords = explode(' ', $keywords);
		else $parseKeywords = false;
		
		if(is_array($parseKeywords)) $keywords = $keywords.'|'.trim(implode('|',$parseKeywords));
		else  $keywords = trim($keywords);
		
		if(!$realkeywords){
			if($keywords!=''){
				foreach($searchKeyOn as $key => $val){
					$searchKeyOn[$key] = " {$val} REGEXP '{$keywords}' ";
				}
				$strSearchKeyOn = implode(" OR ",$searchKeyOn);
				$qKeywords = " 	AND  ( {$strSearchKeyOn} )";
			}else $qKeywords = " ";
		}else{
			foreach($searchKeyOn as $key => $val){
				$searchKeyOn[$key] = " {$val} like '{$realkeywords}%' ";
				if($val=="email") $searchKeyOn[$key] = " {$val} = '{$realkeywords}' ";
				if($val=="last_name") $searchKeyOn[$key] = " {$val} like '%{$realkeywords}%' ";
				
			}
			$strSearchKeyOn = implode(" OR ",$searchKeyOn);
			$qKeywords = " 	AND  ( {$strSearchKeyOn} )";
		}
		$sql = "SELECT count(*) total FROM my_entourage WHERE n_status =1  {$qKeywords} ORDER BY name ASC ";
		$total = $this->apps->fetch($sql);
		if(!$total) return false;
		
		$sql = "SELECT id,name,img,email,IF(last_name IS NULL,'',last_name) last_name , referrerbybrand FROM my_entourage WHERE n_status =1  {$qKeywords} ORDER BY name ASC, last_name ASC LIMIT {$start},{$limit}";
	
		$qData = $this->apps->fetch($sql,1);
	
		if(!$qData) return false;
		foreach($qData as $key => $val){
			$arrFriends[$val['id']] = $val['id']; 
			if($val['referrerbybrand']==$this->uid) $qData[$key]['isFriends'] = true;
			else $qData[$key]['isFriends'] =false;
		}
		
		if($qData){
			$data['result'] = $qData;
			$data['total'] = $total['total'];
			$data['myid'] = intval($this->uid);
		}
		return $data;
	}
	
	function getnewestupload(){
		global $CONFIG;
		$sql = "
			SELECT anc.id,anc.title,anc.brief,image,thumbnail_image,slider_image,posted_date,file,url,fromwho,tags,authorid,topcontent,cityid , anct.type,anct.content,anc.articleType,anct.type pagesname
			FROM {$this->dbshema}_news_content  anc
			LEFT JOIN {$this->dbshema}_news_content_type anct ON anc.articleType = anct.id
			WHERE n_status = 1   AND anc.articleType <> 0
			ORDER BY created_date DESC , id DESC
			LIMIT 4";
			$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		//cek detail image from folder
			//if is article, image banner do not shown
		foreach($qData as $key => $val){
			$qData[$key]['imagepath'] = false;
								
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/thumbnail_{$val['thumbnail_image']}")) $qData[$key]['image'] = "thumbnail_{$val['image']}";	
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/square{$val['image']}")) $qData[$key]['image'] = "square{$val['image']}";
			
			
			//parseurl for video thumb
			$video_thumbnail = false;
			if($val['url']!='')	{
				//parser url and get param data
				$parseUrl = parse_url($val['url']);
				if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
				else $parseQuery = false;
				if($parseQuery) {
					if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
				} 
				$qData[$key]['video_thumbnail'] = $video_thumbnail;
			}else $qData[$key]['video_thumbnail'] = false;		
			
			if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $qData[$key]['hasfile'] = true;
			else $qData[$key]['hasfile'] = false;			
		}
		if($qData) $qData=	$this->getStatistictArticle($qData);
		else $qData = false;
		//pr($qData);
		return $qData;
	}
	
	function setCover(){
		global $CONFIG;
		include_once '../engines/Utility/phpthumb/ThumbLib.inc.php';
		
		$cid = intval($this->apps->_request('cid'));
		$fromwhere = intval($this->apps->_request('fromwhere'));
		$typeofpage = intval($this->apps->_request('typeofpage')); //used by who
	
		//type of page , define user pages
		if($typeofpage!=0){
			$myid = $this->pageid; // wait for session
		}else $myid = $this->uid;
		$image = false;
		//if from content, get image content
		$sql ="SELECT id,image FROM {$this->dbshema}_news_content WHERE  id={$cid} AND fromwho = {$fromwhere} LIMIT 1;";
		$data = $this->apps->fetch($sql);
	
		if(!$data) return false;
		if($typeofpage==0) $coverfolder = "user/cover/";
		else $coverfolder = "pages/cover/";
		$folder = $CONFIG['LOCAL_PUBLIC_ASSET'];
		$image = $data['image'];	
		copy($folder."article/".$image,$folder.$coverfolder.$image);
	
		//userid 	image 	otherid 	fromwhere 0:news_content;1:my	type 0:user;1-n mypagestype	n_status 
		$sql =" 
		INSERT INTO my_wallpaper ( myid,	image ,	otherid ,	fromwhere ,type, n_status ,datetime )
		VALUES ({$myid},'{$image}',{$cid},{$fromwhere},{$typeofpage},1,NOW())
		ON DUPLICATE KEY UPDATE datetime=NOW()
		";
		//pr($sql);
		$this->apps->query($sql);
		
		if($this->apps->getLastInsertId()>0) {
			$sql =" 
			INSERT INTO my_images ( userid,	contentid ,	date ,	n_status )
			VALUES ({$myid},{$cid},NOW(),1)
			ON DUPLICATE KEY UPDATE date=NOW()
			";
			
			$this->apps->query($sql);
			return true;
		} else return false;
		
	}
	
	function setPlaylist(){
		$cid = intval($this->apps->_request('cid'));
		$fromwhere = intval($this->apps->_request('fromwhere'));
		$typeofpage = intval($this->apps->_request('typeofpage'));
		$authorid = intval($this->apps->_request('authorid'));
		
		//check user have myid relation		
		$sql ="SELECT id, ownerid FROM my_pages WHERE ownerid={$this->uid} AND n_status<> 3 LIMIT 1";
		$data = $this->apps->fetch($sql);
		if($data) {
			if($data['id']==$authorid) {
				return false;
			} else {
				$myid = $this->uid;
			}
		} else {
			$myid = $this->uid;
		}
		
		//get file content
		$file = false;
		$sql ="SELECT id,file FROM {$this->dbshema}_news_content WHERE  id={$cid} AND fromwho = {$fromwhere} LIMIT 1;";
		$data = $this->apps->fetch($sql);
	
		if(!$data) return false;
		$file = $data['file'];
		
		//get type of page
		$type = false;
		$sql ="SELECT id,type FROM my_pages WHERE  id={$authorid} LIMIT 1;";
		$arrtype = $this->apps->fetch($sql);
		if (!$arrtype) return false;
		$type = $arrtype['type'];
		
		//userid  image  otherid  fromwhere 0:news_content;1:my	type 0:user;1-n mypagestype	n_status 
		$sql =" 
			INSERT INTO my_playlist (myid,file,otherid,fromwhere,type,n_status,datetime) VALUES ({$myid},'{$file}',{$cid},{$fromwhere},{$type},1,NOW())
			ON DUPLICATE KEY UPDATE datetime=NOW()
		";
		
		$this->apps->query($sql);
		
		if($this->apps->getLastInsertId()>0) {
			/*
			$sql =" 
			INSERT INTO my_images ( userid,	contentid ,	date ,	n_status )
			VALUES ({$myid},{$cid},NOW(),1)
			ON DUPLICATE KEY UPDATE date=NOW()
			";
			
			$this->apps->query($sql);
			*/
			return true;
		} else return false;
	}
		
	function getMyFavorite($userid=0,$limit=10){
			global $CONFIG;
			
			if($userid==0) return false;
			$start = intval($this->apps->_request('start'));
			$sql ="
					SELECT contentid FROM {$this->dbshema}_news_content_favorite WHERE n_status=  1 AND userid = {$userid} ORDER BY date DESC  LIMIT {$start},{$limit}
					";

				$qData = $this->apps->fetch($sql,1);
				if($qData) {
					$this->logger->log("have favorite");
					foreach($qData as $val){
						$favoriteData[$val['contentid']]=$val['contentid'];
					}
				
					if(!$favoriteData) return false;
					$strcontentid = implode(',',$favoriteData);
					
					//get content
					$sql = "
					SELECT anc.id,anc.title,anc.brief,anc.image,anc.thumbnail_image,anc.slider_image,anc.posted_date,anc.file,anc.url,anc.fromwho,anc.tags,anc.authorid,anc.topcontent,anc.cityid,anct.type pagesname FROM athreesix_news_content anc
					LEFT JOIN {$this->dbshema}_news_content_type anct ON anct.id = anc.articleType					
					WHERE anc.id IN ({$strcontentid}) AND anc.n_status=1 LIMIT {$limit}";
			
					$qData = $this->apps->fetch($sql,1);
					if($qData){
						foreach($qData as $val){
							$arrContent[] = $val;
						}
					}else $arrContent = false;
					
					if($arrContent){
						
						foreach($arrContent as $key => $val){
							$arrContent[$key]['imagepath'] = false;
								
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$arrContent[$key]['imagepath'] = "event";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$arrContent[$key]['imagepath'] = "banner";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$arrContent[$key]['imagepath'] = "article";	
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$arrContent[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$arrContent[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$arrContent[$key]['image'] = "small_{$val['image']}";	
							
							$video_thumbnail = false;
							if($val['url']!='')	{
								//parser url and get param data
								$parseUrl = parse_url($val['url']);
								if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
								else $parseQuery = false;
								if($parseQuery) {
									if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
								} 
								$arrContent[$key]['video_thumbnail'] = $video_thumbnail;
							}else $arrContent[$key]['video_thumbnail'] = false;		
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $arrContent[$key]['hasfile'] = true;
							else $arrContent[$key]['hasfile'] = false;		
						}
						
						$arrContent = $this->apps->contentHelper->getStatistictArticle($arrContent);
						return $arrContent;
					}else return false;
				}
		return false;
			
			
			
			
	}
	
	
	function getContestSubmission($userid=0,$mypagestype=0,$limit=10){
			
			global $CONFIG;
			if($userid==0) return false;
			$start = intval($this->apps->_request('start'));
			$sql ="
					SELECT contestid FROM my_contest WHERE n_status=  1 AND otherid = {$userid}  AND mypagestype={$mypagestype} LIMIT {$start},{$limit}
					";
		// pr($sql);
				$qData = $this->apps->fetch($sql,1);
				if($qData) {
					$this->logger->log("have contest");
					foreach($qData as $val){
						$contestData[$val['contestid']]=$val['contestid'];
					}

					if(!$contestData) return false;
					$strcontentid = implode(',',$contestData);
					
					//get content
					$sql = "
					SELECT anc.id,anc.title,anc.brief,anc.image,anc.thumbnail_image,anc.slider_image,anc.posted_date,anc.file,anc.url,anc.fromwho,anc.tags,anc.authorid,anc.topcontent,anc.cityid,anct.type pagesname FROM athreesix_news_content anc
					LEFT JOIN {$this->dbshema}_news_content_type anct ON anct.id = anc.articleType					
					WHERE anc.id IN ({$strcontentid}) AND anc.n_status=1 LIMIT {$limit}";
			
					$qData = $this->apps->fetch($sql,1);
					if($qData){
						foreach($qData as $key => $val){
							$qData[$key]['imagepath'] = false;
								
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";	
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							
							$video_thumbnail = false;
							if($val['url']!='')	{
								//parser url and get param data
								$parseUrl = parse_url($val['url']);
								if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
								else $parseQuery = false;
								if($parseQuery) {
									if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
								} 
								$qData[$key]['video_thumbnail'] = $video_thumbnail;
							}else $qData[$key]['video_thumbnail'] = false;	
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $qData[$key]['hasfile'] = true;
							else $qData[$key]['hasfile'] = false;		
							
							$arrContent[] = $qData[$key];
							
						}
					}else $arrContent = false;
					
					if($arrContent){
						$arrContent = $this->apps->contentHelper->getStatistictArticle($arrContent);
						return $arrContent;
					}else return false;
				}
		return false;			
	}
	
	
	function getMyCalendar($userid=0,$mypagestype=0,$limit=10){
			
			global $CONFIG;
			if($userid==0) return false;
			$contestData = false;
			$start = intval($this->apps->_request('start'));
			if($mypagestype>=2) $qFromWho = " AND anc.fromwho = {$mypagestype} ";
			else $qFromWho = "";
					$sql ="
					SELECT contestid FROM my_contest WHERE n_status=  1 AND otherid = {$userid}  AND mypagestype={$mypagestype} LIMIT {$start},{$limit}
					";
			
				$qData = $this->apps->fetch($sql,1);
				// pr($sql);
					if($qData) {
						$this->logger->log("have contest");
						foreach($qData as $val){
							$contestData[$val['contestid']]=$val['contestid'];
						}
					}
					
					if($contestData){
						$strcontentid = implode(',',$contestData);
						$qContentId = " anc.id IN ({$strcontentid}) OR  ";
					}else $qContentId = "";
					//get content
					$sql = "
					SELECT anc.id,anc.title,anc.brief,anc.image,anc.thumbnail_image,anc.slider_image,anc.posted_date,anc.file,anc.url,anc.fromwho,anc.tags,anc.authorid,anc.topcontent,anc.cityid,anct.type pagesname FROM {$this->dbshema}_news_content anc
					LEFT JOIN {$this->dbshema}_news_content_type anct ON anct.id = anc.articleType					
					WHERE {$qContentId} authorid={$userid}  AND anc.n_status=1 {$qFromWho} AND articleType= 5 ORDER BY anc.posted_date DESC LIMIT {$limit}";
						// pr($sql);exit;
					$qData = $this->apps->fetch($sql,1);
					
					if($qData){
						foreach($qData as $key => $val){
							$qData[$key]['imagepath'] = false;
								
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";	
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/small_{$val['image']}"))  	$qData[$key]['image'] = "small_{$val['image']}";	
							
							$video_thumbnail = false;
							if($val['url']!='')	{
								//parser url and get param data
								$parseUrl = parse_url($val['url']);
								if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
								else $parseQuery = false;
								if($parseQuery) {
									if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
								} 
								$qData[$key]['video_thumbnail'] = $video_thumbnail;
							}else $qData[$key]['video_thumbnail'] = false;	
							
							if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}music/mp3/{$val['file']}")) $qData[$key]['hasfile'] = true;
							else $qData[$key]['hasfile'] = false;		
							
							$arrContent[] = $qData[$key];
							
						}
					}else $arrContent = false;
					
					if($arrContent){
						$arrContent = $this->apps->contentHelper->getStatistictArticle($arrContent);
						return $arrContent;
					}else return false;
			
		return false;			
	}
	
	
	function setEditContent(){
		global $CONFIG;
		$cid = intval($this->apps->_p('article_id'));
		$title = strip_tags($this->apps->_p('title'));
		$content = strip_tags($this->apps->_p('description'));
		$tags = strip_tags($this->apps->_p('tags'));		
		
		$sql = "UPDATE {$this->dbshema}_news_content SET title = \"{$title}\",content = \"{$content}\",tags = '{$tags}' WHERE id = '{$cid}'  AND authorid={$this->uid} AND fromwho=1 ";
		if ($this->apps->query($sql)) {
			return true;
		} else return false;
		return false;
	}
	
	function unContentPost(){
		$cid = intval($this->apps->_p('cid'));
		$type = intval($this->apps->_p('type'));
		
		if($this->apps->_p('publishedtype')){
			$publishedtype = intval($this->apps->_p('publishedtype'));
			if($publishedtype==0) $publishedtype=1;
		}else $publishedtype=3;
		
		if($cid==0) return false;
		if($type==0) return false;
		if($this->uid==0) return false;
		$qauthorid = " AND authorid={$this->uid} ";
		if($this->apps->user->leaderdetail->type==666) $qauthorid = ""; 
		if($this->apps->user->leaderdetail->type==100) $qauthorid = ""; 
		if($this->apps->user->leaderdetail->type==101) $qauthorid = ""; 
		$sql = "UPDATE {$this->dbshema}_news_content SET n_status = {$publishedtype} WHERE id = '{$cid}' AND fromwho=1 AND articleType={$type} {$qauthorid} LIMIT 1";
		// pr($sql);exit;
		if ($this->apps->query($sql)) {
			return true;
		} else return false;
		return false;
	}
	function setComplete(){
		$cid = intval($this->apps->_p('cid'));
		$type = intval($this->apps->_p('type'));
		
		if($cid==0) return false;
		if($type==0) return false;
		if($this->uid==0) return false;
		
		$sql = "UPDATE {$this->dbshema}_news_content SET n_status = 0 WHERE id = '{$cid}' AND fromwho=1 AND articleType={$type} AND authorid={$this->uid} LIMIT 1";
		
		if ($this->apps->query($sql)) {
			return true;
		} else return false;
		return false;
	}
	
	function editVenue(){
		$cid = intval($this->apps->_p('cid'));
		$id_cat = intval($this->apps->_p('id_cat'));
		$venuename = strip_tags($this->apps->_p('venuename'));
		$venueaddr = strip_tags($this->apps->_p('venueaddr'));
		$provinceName = strip_tags($this->apps->_p('provinceName'));
		$city = strip_tags($this->apps->_p('city'));
		$latitude = $this->apps->_p('latitude');
		$longitude = $this->apps->_p('longitude');
		
		if(!$cid)return false;

		$sql = "UPDATE {$this->dbshema}_venue_master
				SET venuename='{$venuename}',address='{$venueaddr}',venuecategory='{$id_cat}',provinceName='{$provinceName}',city='{$city}',latitude='{$latitude}',longitude='{$longitude}'
				WHERE id = '{$cid}'";
		//pr($sql);exit;
		if ($this->apps->query($sql)) {
			return true;
		} else return false;	
	}

	function unVenueReference($act=null) {
		
		$publishedtype = intval($this->apps->_p('publishedtype'));
		if($publishedtype==0)$publishedtype=1;
		
		if ($act=="venueid") {
			$cid = intval($this->apps->_p('cid'));
			$idvenue = intval($this->apps->_p('idvenue'));
		
			$sql = "UPDATE {$this->dbshema}_venue_master SET venueid = {$idvenue} WHERE id = '{$cid}'";
			if ($this->apps->query($sql)) {
				return true;
			} else return false;		
		} else {
			$cid = intval($this->apps->_request('id'));
			
			if($cid==0) return false;
			if($this->uid==0) return false;
			
			$sql = "UPDATE {$this->dbshema}_venue_master SET n_status = {$publishedtype} WHERE id = '{$cid}'";
			if ($this->apps->query($sql)) {
				return true;
			} else return false;
		}		
		return false;
	}
	
	function unCommentPost(){
		$id = intval($this->apps->_p('id'));
		if( $this->apps->_p('publishedtype')){
			$publishedtype = intval($this->apps->_p('publishedtype'));
			if($publishedtype==0)$publishedtype=1;
		}else $publishedtype=3;
		
		if($id==0) return false;
		if($this->uid==0) return false;
		$qauthorid = " AND userid={$this->uid} ";
		if($this->apps->user->leaderdetail->type==666) $qauthorid = ""; 
		if($this->apps->user->leaderdetail->type==100) $qauthorid = ""; 
		if($this->apps->user->leaderdetail->type==101) $qauthorid = ""; 
		$sql = "UPDATE {$this->dbshema}_news_content_comment SET n_status = {$publishedtype} WHERE id = '{$id}' {$qauthorid} LIMIT 1";		
		if ($this->apps->query($sql)) {
			return true;
		} else return false;
		return false;
	}
	
	function unComment(){
		
		$cid = intval($this->apps->_p('cid'));
		if($this->uid==0) return false;
		
		if($cid==0) return false;
			
		$sql = "UPDATE {$this->dbshema}_news_content_comment SET n_status = 0 WHERE id = '{$cid}' AND userid={$this->uid} LIMIT 1";
		$this->logger->log($sql);
		if ($this->apps->query($sql)) {
			return true;
		} else return false;
		return false;
	}
	
	
	function wordcut($str=null,$num=1){
			if($str==null) return false;
			
			$arrStr = explode(" ",$str);
			$arrNewStr = false;
			foreach($arrStr as $key => $val){
				if($key<=$num){
					$arrNewStr[] = $val;
				}else break;
			}
			if($arrNewStr==false) return false;
			$str = implode(" ",$arrNewStr);
			return $str;
	
	}
	function clearphotogallery(){
		$id = intval($this->apps->_request('id'));
		
		$sql ="UPDATE {$this->dbshema}_news_content_repo SET n_status = 3 WHERE id={$id}  AND userid={$this->uid} LIMIT 1 ";
		
		return $this->apps->query($sql);
		
	}
	function clearphotocovergallery(){
		$id = intval($this->apps->_request('id'));
		
		$sql ="UPDATE {$this->dbshema}_news_content SET image='' WHERE id={$id}  AND authorid={$this->uid} LIMIT 1 ";
		
		return $this->apps->query($sql);
		
	}
	function addUploadImageGallery($data=false,$type=NULL){
		global $LOCALE;
		$fromwho =1;
		$type = intval($this->apps->_p('type'));
		$cid = intval($this->apps->_p('cid'));
		$title = strip_tags($this->apps->_p('title'));
		$brief = strip_tags($this->apps->_p('brief'));
		$content = strip_tags($this->apps->_p('desc'));
		$friendtags = $this->apps->_p('fid');
		$friendtypetags = $this->apps->_p('ftype');
		
		if($data) {
			 $image = false;
			if(array_key_exists('arrImage',$data))	$image = $data['arrImage']['filename'];			
			if(array_key_exists('arrVideo',$data)) $image = $data['arrVideo']['filename'];		
			
		}
		
		if(!$this->uid) return false;
		$authorid = intval($this->uid);
		if(!$authorid) return false;
		
		$sql ="
			INSERT INTO {$this->dbshema}_news_content_repo (`title`, `brief`, `content`, `typealbum`, `gallerytype`, `files`, `fromwho`, `otherid`, `created_date`, `n_status`,authorid) VALUES ( '{$title}', '{$brief}', '{$content}', '1', '0', '{$image}', '1', '{$cid}', NOW(), '1',{$authorid});
			";
			
			// pr($sql);exit;
			$this->logger->log($sql);
			$this->apps->query($sql);
			if($this->apps->getLastInsertId()>0){
				$message = " {$this->apps->user->name} {$this->apps->user->last_name} {$LOCALE[1]['tagged']} {$title} ";
				$galleryid = $this->apps->getLastInsertId();
				if($friendtags){					
					$arrfid = explode(',',$friendtags);
					$arrftype = explode(',',$friendtypetags);
					$frienddata = false;
					if(is_array($arrfid)){
						foreach($arrfid as $key => $val){
							$frienddata[$key]['fid'] = $val;
							$frienddata[$key]['ftype'] = $arrftype[$key];
							
						}
						
						if($frienddata){
						
							foreach($frienddata as $val){
								$this->addFriendTags($galleryid,$val['fid'],$val['ftype'],true,$message);
							}
						
						}
					}else{
						$friendtypetags = intval($friendtypetags);
						$this->addFriendTags($galleryid,intval($friendtags),intval($friendtypetags),true,$message);
					}
				}
								
				return true;
			
			}else return false;
				
			
		
	}
	
	function editContentArticle($data=false){
		global $CONFIG,$LOCALE;
		$fromwho =1;
	
		$id = intval($this->apps->_p('id'));
		$qSet['title'] = strip_tags($this->apps->_p('title'));
		$qSet['content'] = strip_tags($this->apps->_p('desc'));
		$qSet['tags'] = strip_tags($this->apps->_p('tags'));
		$qSet['brief'] = strip_tags($this->apps->_p('brief'));
		$qSet['n_status'] = intval($this->apps->_p('n_status'));
		$reason = strip_tags($this->apps->_p('reason'));
		if($qSet['brief']=='') $qSet['brief'] = $this->wordcut($qSet['content'],10);
		$qSet['posted_date'] = strip_tags($this->apps->_p('posted_date'));
		$times = strip_tags($this->apps->_p('times'));
		$qSet['expired_date'] = strip_tags($this->apps->_p('expired_date'));
	
		$fid = $this->apps->_p('fid');
		$ftype = $this->apps->_p('ftype');
		
		/* if(!in_array($this->apps->user->leaderdetail->type,$this->approver)){
			$prohibitedit = $this->prohibittoedit($id);
			if(!$prohibitedit) return false;
		} */
		if($qSet['n_status']!='') {
			$n_status = intval($qSet['n_status']);
			$qSet['n_status'] = $n_status;
		}
		
		if($data) {
		
			if(array_key_exists('arrImage',$data))	$qSet['image'] = $data['arrImage']['filename'];			
			if(array_key_exists('arrVideo',$data)) $qSet['image'] = $data['arrVideo']['filename'];
			if(array_key_exists('arrFile',$data)) $qSet['file'] = $data['arrFile']['filename'];
		}
		
		
		$url = strip_tags($this->apps->_p('url'));		
		$this->logger->log(" plan edit, user id: {$this->uid} ");
		if(!$this->uid) return false;

		if($qSet['posted_date']=='') $qSet['posted_date'] = date('Y-m-d H:i:s');		
		if($qSet['expired_date'] =='') $qSet['expired_date'] = $qSet['posted_date'];	
		if($times!='') {
					$posted_date = explode(' ',$qSet['posted_date']);
					if(is_array($posted_date)){
						$datetimes[0] = $posted_date[0];
						$datetimes[1] = $times;
						$qSet['posted_date'] = implode(' ',$datetimes);
					}
		}
		$qUpdates = false;
		$qSetUpdate = false;
		foreach($qSet as $key => $val){
			if($val!='') $qSetUpdate[]="{$key}=\"{$val}\"";
		}
		if($qSetUpdate){
			$qUpdates = implode(',',$qSetUpdate);
		}
		if(!$qUpdates) return false;
		
		
			
		$qWhere = " AND authorid={$this->uid} AND fromwho=1  ";
		
		$leadertype = intval($this->apps->user->leaderdetail->type);
		if($leadertype){
			$approver = $this->approver;
			if(in_array($leadertype,$approver)){
					$qWhere = "   ";
			}
		}
		
		$sql = "UPDATE {$this->dbshema}_news_content 
		SET {$qUpdates}
		WHERE id = '{$id}' {$qWhere} ";
		$this->logger->log($sql);
		if ($this->apps->query($sql)) {
			if($reason) $message = $reason;
			else $message = " {$this->apps->user->name} {$this->apps->user->last_name} {$LOCALE[1]['tagged']} {$qSet['title']} ";
			
			
			if($fid){					
					$arrfid = explode(',',$fid);
					$arrftype = explode(',',$ftype);
					$frienddata = false;
					if(is_array($arrfid)){
						foreach($arrfid as $key => $val){
							$frienddata[$key]['fid'] = $val;
							$frienddata[$key]['ftype'] = $arrftype[$key];
						
						}
						
						if($frienddata){
					
							foreach($frienddata as $val){
								
								$this->addFriendTags($id,$val['fid'],$val['ftype'],false,$message);
								
							}
						
						}
					}else{
						$ftype = intval($ftype);
					
						$this->addFriendTags($id,intval($fid),intval($ftype),false,$message);						
						
					}
			}
						
			return true;
		} else return false;
		return false;
	}
	
	
	function getCheckin($cidstr=false){
		if($cidstr==false) return false;
		$sql = " 
		SELECT checkin.*,master.provinceName,master.city cityname,master.venuecategory, master.venuename , master.address
		FROM my_checkin checkin 
		LEFT JOIN {$this->dbshema}_venue_master master ON master.id = checkin.venueid
		WHERE contentid in ({$cidstr})  ";
		
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		
		
		$dataceckin = false;
		foreach($qData as $key => $val){	
		
		 
		 
			$qData[$key]['venue'] = html_entity_decode(ucwords(strtolower($val['venue'])));
			$qData[$key]['venuename'] = html_entity_decode(ucwords(strtolower($val['venuename'])));
			$qData[$key]['address'] = html_entity_decode(ucwords(strtolower($val['address'])));
			$qData[$key]['cityname'] = html_entity_decode(ucwords(strtolower($val['cityname']))); 
			$qData[$key]['provinceName'] = html_entity_decode(ucwords(strtolower($val['provinceName'])));
			
				$sql = " 
			SELECT  AVG(rating) rating , AVG(prize)  prize, SUM(wifi) wifi , SUM(smoking) smoking , COUNT(*) total
			FROM my_checkin 
			WHERE venueid = {$val['venueid']} AND ( rating <> 0 OR prize <> 0 )
			";
			$checkindata = $this->apps->fetch($sql);
			
			if($checkindata) {
				$wifi = "0";
				$smoking = "0";
				if($checkindata['wifi']>=$checkindata['total']) $wifi  = "1";
				if($checkindata['smoking']>=$checkindata['total']) $smoking   = "1";
				$qData[$key]['rating'] = (string) intval($checkindata['rating']);
				$qData[$key]['prize'] = (string) intval($checkindata['prize']);
			
				$qData[$key]['wifi'] = $wifi;
				$qData[$key]['smoking'] = $smoking;
			}
			$dataceckin[$val['contentid']] = $qData[$key];	
			
		}
		
		return $dataceckin;
		
		
	}
	
	
	function getGalleryTypeContent($start=null,$limit=12) {
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$uid = intval($this->apps->_request('uid'));
		if($uid==0)	{
			$uidType = $this->uid;
			$uid = $this->uid;
		}
		
		
		$limit = intval($limit);
		
		
		//RUN FILTER ENGINE, KEYWORDSEARCH , CONTENTSEARCH 
		$filter = $this->apps->searchHelper->filterEngine($limit,false,false);
				
		$search = "";
		$startdate = "";
		$enddate = "";
		$authorid = "";		
	
		if ($this->apps->_p('search')) {
			if ($this->apps->_p('search')!="Search...") {
				$search = rtrim(strip_tags($this->apps->_p('search')));
				$search = ltrim($search);
				
				if(strpos($search,' ')) $parseSearch = explode(' ', $search);
				else $parseSearch = false;
				
				if(is_array($parseSearch)) $search = $search.'|'.trim(implode('|',$parseSearch));
				else  $search = trim($search);
				
				$search = " AND (anc.title REGEXP  '{$search}' OR anc.brief REGEXP  '{$search}' OR anc.content REGEXP  '{$search}') ";
			}
		}
		if ($this->apps->_p('startdate')) {
			$start_date = strip_tags($this->apps->_p('startdate'));
			$startdate = " AND DATE(anc.posted_date) >= DATE('{$start_date}') ";
		}
		if ($this->apps->_p('enddate')) {
			$end_date = strip_tags($this->apps->_p('enddate'));
			$enddate = " AND DATE(anc.posted_date) <= DATE('{$end_date}') ";
		}
		$authorid = " AND anc.authorid = {$uid}";
		
		$qStatus = "  AND anc.n_status = 1 ";
		$qHirarki ="";
		if($filter['uidsearch']!='') $qHirarki = " {$filter['uidsearch']} ";
		else {
			$leadertype = intval($this->apps->user->leaderdetail->type);
			if($leadertype){
				
					
							$auhtorarrid[$uid] = $uid;
							$auhtorminion = @$this->apps->user->branddetail;
							if($auhtorminion){
								foreach($auhtorminion as $val){
										$auhtorarrid[$val->ownerid] = $val->ownerid;
								}
							}
							
							$auhtorminion = @$this->apps->user->areadetail;
							if($auhtorminion){
								foreach($auhtorminion as $val){
										$auhtorarrid[$val->ownerid] = $val->ownerid;
								}
							}		
							
							$auhtorminion = @$this->apps->user->pldetail;
							if($auhtorminion){
								foreach($auhtorminion as $val){
										$auhtorarrid[$val->ownerid] = $val->ownerid;
								}
							}	
							
							$auhtorminion = @$this->apps->user->badetail;
							if($auhtorminion){
								foreach($auhtorminion as $val){
										$auhtorarrid[$val->ownerid] = $val->ownerid;
								}
							}	
							
							if(is_array($auhtorarrid)) 	{
								// pr($minionarr);
								$authorids = implode(',',$auhtorarrid);
							}else $authorids = $uid;
							
							$qHirarki = " AND ( authorid IN ({$authorids}) OR EXISTS ( SELECT contentid FROM {$this->dbshema}_news_content_tags WHERE friendid IN ({$authorids}) AND friendtype=1  AND contentid=anc.id) ) ";
						
					
			}
		}
		
		$qStatus = "  AND anc.n_status IN (1) ";				
		$qArticleType = " anc.image <> '' AND anc.image is not null  ";
		
		$brands = strip_tags($this->apps->_request('category'));
		$qCategories = " ";
		if($brands=='cocreation') $qCategories = " AND pages.type IN ({$this->cocreationtypeid}) AND anc.articleType = 5 ";
		if($brands=='brands') $qCategories = " AND pages.type IN ({$this->brandtypeid}) AND anc.articleType = 5  ";
		if($brands=='timeline') {
			$qCategories = " AND anc.articleType = 3  ";
		 	$qHirarki = " AND ( authorid IN ({$uid}) OR EXISTS ( SELECT contentid FROM {$this->dbshema}_news_content_tags WHERE friendid IN ({$uid}) AND friendtype=1  AND contentid=anc.id) ) ";
		}
		if($brands=='myplan') {
			$qCategories = " AND pages.type IN ( 1 ) AND anc.articleType = 5  ";
				$qHirarki = " AND ( authorid IN ({$uid}) OR EXISTS ( SELECT contentid FROM {$this->dbshema}_news_content_tags WHERE friendid IN ({$uid}) AND friendtype=1  AND contentid=anc.id) ) ";
		}
		// if($brands=='baengagement') $qCategories = " AND pages.type IN ( {$this->cocreationtypeid},{$this->brandtypeid} ) AND EXISTS ( SELECT contentid FROM my_checkin checkin WHERE checkin.contentid=anc.id AND checkin.userid IN ({$authorids}) AND checkin.n_status=1 ) ";
		if($brands=='baengagement') {
			$qCategories = " AND anc.articleType = 5 AND EXISTS ( SELECT contentid FROM my_checkin checkin WHERE checkin.contentid=anc.id AND checkin.userid
			IN ({$authorids}) AND checkin.n_status=1 ) ";
			$qArticleType = " 1 ";
			$qHirarki = " ";
		}
		
		//GET TOTAL ARTICLE

		$sql = "SELECT count(*) total 
		FROM {$this->dbshema}_news_content anc
		LEFT JOIN my_pages pages ON anc.authorid=pages.ownerid 
		{$filter['subqueryfilter']} 
		WHERE {$qArticleType} {$search} {$startdate} {$enddate} {$filter['keywordsearch']} {$filter['categorysearch']} {$filter['citysearch']} {$filter['postedsearch']} {$filter['fromwhosearch']} {$qStatus} {$qHirarki} {$qCategories}
		{$filter['groupbyfilter']} ";
		
		$total = $this->apps->fetch($sql);
		
		if(intval($total['total'])<=$limit) $start = 0;
		
		$sql = "
			SELECT anc.id,anc.title,anc.brief,anc.image,anc.thumbnail_image,anc.slider_image,anc.posted_date,anc.expired_date,anc.file,anc.url,anc.fromwho,anc.tags,anc.authorid,anc.topcontent,anc.cityid ,anc.articleType,anc.can_save,anc.n_status,pages.type pagetypes
			FROM {$this->dbshema}_news_content anc
			LEFT JOIN my_pages pages ON anc.authorid=pages.ownerid 
			{$filter['subqueryfilter']} 
			WHERE {$qArticleType} {$search} {$startdate} {$enddate} {$filter['keywordsearch']} {$filter['categorysearch']} {$filter['citysearch']} {$filter['postedsearch']} {$filter['fromwhosearch']} {$qStatus} {$qHirarki} {$qCategories}
			{$filter['groupbyfilter']} ORDER BY {$filter['suborderfilter']}  anc.posted_date DESC , anc.id DESC 
			LIMIT {$start},{$limit}";
		// pr($sql);
		$this->logger->log(" my album : ".$sql);
		$rqData = $this->apps->fetch($sql,1);
		// $this->logger->log($sql);
		if($rqData) {
			//CEK DETAIL IMAGE FROM FOLDER
			//IF IS ARTICLE, IMAGE BANNER DO NOT SHOWN
			foreach($rqData as $key => $val){
				$rqData[$key]['imagepath'] = false;
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$rqData[$key]['imagepath'] = "event";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$rqData[$key]['imagepath'] = "banner";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$rqData[$key]['imagepath'] = "article";					
				
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}")) 	$rqData[$key]['banner'] = false;
				else $rqData[$key]['banner'] = true;
			
				//CHECK FILE SMALL
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}{$rqData[$key]['imagepath']}/small_{$val['image']}")) $rqData[$key]['image'] = "small_{$val['image']}";
				
				//PARSEURL FOR VIDEO THUMB
				$video_thumbnail = false;
				if($val['url']!='')	{
					//PARSER URL AND GET PARAM DATA
					$parseUrl = parse_url($val['url']);
					if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
					else $parseQuery = false;
					if($parseQuery) {
						if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
					} 
					$rqData[$key]['video_thumbnail'] = $video_thumbnail;
				}else $rqData[$key]['video_thumbnail'] = false;
				
				if($rqData[$key]['imagepath']) $rqData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/".$rqData[$key]['imagepath']."/".$rqData[$key]['image'];
				else $rqData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/article/default.jpg";
				
				
			}
			
			if($rqData) $qData=	$this->getStatistictArticle($rqData);
			else $qData = false;
		}else $qData = false;		
	
		$result['result'] = $qData;
		$result['timeline'] = $this->getTotalTimelinePicture();
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
		
		return $result;
	}
	
	
	function getTotalTimelinePicture(){
	
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		$uid = intval($this->apps->_request('uid'));
		if($uid==0)	{
			$uidType = $this->uid;
			$uid = $this->uid;
		}
		$authorid = " AND anc.authorid = {$uid}";
		
		$qStatus = "  AND anc.n_status = 1 ";
		$qHirarki ="";
		 
		$leadertype = intval($this->apps->user->leaderdetail->type);
		if($leadertype){
				$auhtorarrid[$uid] = $uid;
				$auhtorminion = @$this->apps->user->branddetail;
				if($auhtorminion){
					foreach($auhtorminion as $val){
							$auhtorarrid[$val->ownerid] = $val->ownerid;
					}
				}
				
				$auhtorminion = @$this->apps->user->areadetail;
				if($auhtorminion){
					foreach($auhtorminion as $val){
							$auhtorarrid[$val->ownerid] = $val->ownerid;
					}
				}		
				
				$auhtorminion = @$this->apps->user->pldetail;
				if($auhtorminion){
					foreach($auhtorminion as $val){
							$auhtorarrid[$val->ownerid] = $val->ownerid;
					}
				}	
				
				$auhtorminion = @$this->apps->user->badetail;
				if($auhtorminion){
					foreach($auhtorminion as $val){
							$auhtorarrid[$val->ownerid] = $val->ownerid;
					}
				}	
				
				if(is_array($auhtorarrid)) 	{
					// pr($minionarr);
					$authorids = implode(',',$auhtorarrid);
				}else $authorids = $uid;
				
				$qHirarki = " AND ( authorid IN ({$uid}) OR EXISTS ( SELECT contentid FROM {$this->dbshema}_news_content_tags WHERE friendid IN ({$uid}) AND friendtype=1  AND contentid=anc.id) ) ";
		}
		 
		$qStatus = "  AND anc.n_status IN (1) ";				
		$qArticleType = " anc.image <> '' AND anc.image is not null  ";
		 
		$qCategories = " AND anc.articleType = 3  "; 
		
		$sql = "SELECT count(*) total 
		FROM {$this->dbshema}_news_content anc
		LEFT JOIN my_pages pages ON anc.authorid=pages.ownerid 
		WHERE  {$qArticleType}  {$qStatus} {$qHirarki} {$qCategories}
	 	";
		// pr($sql);
		$total = $this->apps->fetch($sql);
		
		 if(!$total) return false;
	  
		$sql = "
			SELECT anc.id,anc.title,anc.brief,anc.image,anc.thumbnail_image,anc.slider_image,anc.posted_date,anc.expired_date,anc.file,anc.url,anc.fromwho,anc.tags,anc.authorid,anc.topcontent,anc.cityid ,anc.articleType,anc.can_save,anc.n_status,pages.type pagetypes
			FROM {$this->dbshema}_news_content anc
			LEFT JOIN my_pages pages ON anc.authorid=pages.ownerid 
			WHERE {$qArticleType}  {$qStatus} {$qHirarki} {$qCategories}
			ORDER BY  anc.posted_date DESC , anc.id DESC  LIMIT 1";
		// pr($sql);
		
		$rqData = $this->apps->fetch($sql,1);
		$this->logger->log($sql);
		if($rqData) {
			//CEK DETAIL IMAGE FROM FOLDER
			//IF IS ARTICLE, IMAGE BANNER DO NOT SHOWN
			foreach($rqData as $key => $val){
				$rqData[$key]['imagepath'] = false;
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$rqData[$key]['imagepath'] = "event";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$rqData[$key]['imagepath'] = "banner";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$rqData[$key]['imagepath'] = "article";					
				
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}")) 	$rqData[$key]['banner'] = false;
				else $rqData[$key]['banner'] = true;
			
				//CHECK FILE SMALL
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}{$rqData[$key]['imagepath']}/small_{$val['image']}")) $rqData[$key]['image'] = "small_{$val['image']}";
				
				//PARSEURL FOR VIDEO THUMB
				$video_thumbnail = false;
				if($val['url']!='')	{
					//PARSER URL AND GET PARAM DATA
					$parseUrl = parse_url($val['url']);
					if(array_key_exists('query',$parseUrl)) parse_str($parseUrl['query'],$parseQuery);
					else $parseQuery = false;
					if($parseQuery) {
						if(array_key_exists('v',$parseQuery))$video_thumbnail = $parseQuery['v'];
					} 
					$rqData[$key]['video_thumbnail'] = $video_thumbnail;
				}else $rqData[$key]['video_thumbnail'] = false;
				
				if($rqData[$key]['imagepath']) $rqData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/".$rqData[$key]['imagepath']."/".$rqData[$key]['image'];
				else $rqData[$key]['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/article/default.jpg";
				
				
			} 
			 
		}else $rqData = false;		
	
		$result['result'] = $rqData;
		$result['total'] = intval($total['total']);
	 
		 
		return $result;
	
	}
	
	
	function brandpreferrence(){
		$subbrandname = strip_tags($this->apps->_request("subbrandname"));
		if($subbrandname=='') return false;
		
		$sql =" 
		SELECT preferenceid SubBrandID, subbrandname SubBrandName
		FROM tbl_brand_preferences_references 
		WHERE subbrandname LIKE '%{$subbrandname}%' LIMIT 10";
		// pr($sql);
		
		
		$qData = $this->apps->fetch($sql,1);
		if($qData) return $qData;
		else return false;
		
	}
	
	
	function prohibittoedit($id=0){
		if($id==0) return false;
		/* check content type : if plan cannot edit */
		$sql = "SELECT articleType FROM {$this->dbshema}_news_content WHERE id={$id} LIMIT 1";
		$this->logger->log(" prohibition : ".$sql);
		$articletype = $this->apps->fetch($sql);
		if($articletype){
			if($articletype['articleType']==5) return false;
			else return true;
		}else return false;
		
	}

	function addBadges($data=false,$type=NULL){
		global $LOCALE;
	
		$name = strip_tags($this->apps->_p('name'));
		$detail = strip_tags($this->apps->_p('desc'));
		$checkcondition = strip_tags($this->apps->_p('checkcondition'));
		$badgecode = strip_tags($this->apps->_p('badgecode'));
		$gaintype = strip_tags($this->apps->_p('gaintype'));
		$badge_status = intval($this->apps->_p('badge_status'));
		$badgeType = intval($this->apps->_p('badgeType'));
		$point = intval($this->apps->_p('point'));
		$badgeType = intval($this->apps->_p('badgeType'));
		$updateBadge = intval($this->apps->_p('updateBadge'));

		$image = false;
		$file = false;
		if($data) {
			$updateIMG='';
			if(array_key_exists('arrImage',$data)){
				$image_active = $data['arrImage']['filename'];
				if($updateBadge>0){
					$updateIMG .= "image='{$image_active}',";
				}	
			}			
			if(array_key_exists('arrImage2',$data)){
				$image_non = $data['arrImage2']['filename'];
				if($updateBadge>0){
					$updateIMG .= "image_2='{$image_non}',";
				}
			}
				
		}
		
		if(!$this->uid) return false;
		$authorid = intval($this->uid);
		if(!$authorid) return false;
		
		// if($leadertype==100) return false;
		
		if($updateBadge>0){
			$sql="UPDATE tbl_badge_detail 
					SET `name`='{$name}',
					 detail = '{$detail}',
					 n_status='{$badge_status}',
					 checkcondition='{$checkcondition}',
					 gaintype='{$gaintype}',
					 {$updateIMG}
					 badgetype={$badgeType},
					 `point`={$point}
					WHERE id={$updateBadge}";
		}else{
			$sql ="
				INSERT INTO tbl_badge_detail (id,`name`,detail, image, image_2,datetime, n_status,checkcondition,gaintype,badgetype,point) 
				VALUES (NULL,'{$name}','{$detail}','{$image_active}','{$image_non}',NOW(),'{$badge_status}','{$checkcondition}','{$gaintype}',{$badgeType},{$point})
			";
		}

		
		//pr($sql);exit;
		$this->logger->log($sql);	
		
		if ($this->apps->query($sql)) {
			if($updateBadge>0){
				return true;
			}else{
				$this->logger->log($this->apps->getLastInsertId());	
				if($this->apps->getLastInsertId()>0) {				
					return true;
				}
			}
		} else return false;
	}

	function addMerchandise($data=false,$type=NULL){
		global $LOCALE;
	
		$name = strip_tags($this->apps->_p('name'));
		$detail = strip_tags($this->apps->_p('desc'));
		$stock = intval($this->apps->_p('stock'));
		$point = intval($this->apps->_p('point'));
		$n_status = intval($this->apps->_p('n_status'));
		$m_type = intval($this->apps->_p('m_type'));
		$updateMerchandise = intval($this->apps->_p('updateMerchandise'));

		$image = false;
		$file = false;
		if($data) {
			$updateIMG='';
			if(array_key_exists('arrImage',$data)){
				$merchandise_img = $data['arrImage']['filename'];
				if($updateMerchandise>0){
					$updateIMG = "image='{$merchandise_img}',";
				}	
			}			
		}
		
		if(!$this->uid) return false;
		$authorid = intval($this->uid);
		if(!$authorid) return false;
		
		// if($leadertype==100) return false;
		
		if($updateMerchandise>0){
			$sql="UPDATE tbl_merchandise_detail 
					SET `name`='{$name}',
					 detail = '{$detail}',
					 n_status={$n_status},
					 stock={$stock},
					 merchandise_type={$m_type},
					 {$updateIMG}
					 `point`={$point}
					WHERE id={$updateMerchandise}";
		}else{
			$sql ="
				INSERT INTO tbl_merchandise_detail (id,`name`,detail, image, postdate, n_status,stock,point, merchandise_type) 
				VALUES (NULL,'{$name}','{$detail}','{$merchandise_img}',NOW(),{$n_status},{$stock},{$point},{$m_type})
			";
		}

		
		//pr($sql);exit;
		$this->logger->log($sql);	
		
		if ($this->apps->query($sql)) {
			if($updateMerchandise>0){
				return true;
			}else{
				$this->logger->log($this->apps->getLastInsertId());	
				if($this->apps->getLastInsertId()>0) {				
					return true;
				}
			}
		} else return false;
	}

	function getBadgeList($start=null,$limit=12){
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$uid = intval($this->apps->_request('uid'));
		if($uid==0)	{
			$uidType = $this->uid;
			$uid = $this->uid;
		}
			
		$limit = intval($limit);

		$sql = "SElECT * FROM tbl_badge_detail 
				WHERE n_status = 1 
				ORDER BY datetime DESC LIMIT {$start},{$limit}";
		$rs = $this->apps->fetch($sql,1);

		$sql_total = "SELECT COUNT(id) total
						FROM tbl_badge_detail
						WHERE n_status=1";
		$rs_total = $this->apps->fetch($sql_total);


		return array('result'=>$rs, 'total'=>$rs_total);	
	}
	function getMerchandiseList($start=null,$limit=12){
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$uid = intval($this->apps->_request('uid'));
		if($uid==0)	{
			$uidType = $this->uid;
			$uid = $this->uid;
		}
			
		$limit = intval($limit);
		$m_type = '0,4,5';
		if($this->apps->_p('m_type')=='0'){
			$m_type=0;
		}else{
			$m_type = strip_tags($this->apps->_p('m_type'));
		}
		
		//pr($this->apps->_p('m_type'));
		$n_status = intval($this->apps->_p('n_status'));
		$n_status_filter = "AND n_status IN (1)";
		if($n_status==2){
			$n_status_filter = "AND n_status IN (0,2)";
		}


		$sql = "SElECT * FROM tbl_merchandise_detail 
				WHERE merchandise_type IN ({$m_type}) {$n_status_filter}
				ORDER BY postdate DESC LIMIT {$start},{$limit}";
		$rs = $this->apps->fetch($sql,1);

		$sql_total = "SELECT COUNT(id) total
						FROM tbl_merchandise_detail
						WHERE {$n_status_filter}";
		$rs_total = $this->apps->fetch($sql_total);


		return array('result'=>$rs, 'total'=>$rs_total);	
	}

	function detailBadges($id=null){
		global $CONFIG;

		$sql = "SELECT * 
				FROM tbl_badge_detail
				WHERE n_status=1 AND id={$id} LIMIT 1";
		$rs = $this->apps->fetch($sql);

		if($rs) return $rs;
		return false;
	}
	function detailMerchandise($id=null){
		global $CONFIG;

		$sql = "SELECT * 
				FROM tbl_merchandise_detail
				WHERE id={$id} LIMIT 1";
		$rs = $this->apps->fetch($sql);
		//pr($rs);exit;
		if($rs) return $rs;
		return false;
	}
	
	function listBA(){
		global $CONFIG;

		$area = intval($this->apps->_p('area'));
		if($area>0){
			$filterArea = "AND mp.city = {$area}";
		}
		$brand = intval($this->apps->_p('brand'));
		if($brand>0){
			$filterBrand = "AND mp.brandid = {$brand}";
		}

		$sql="SELECT sm.id, sm.name, sm.last_name, mp.city, mp.brandid, mp.type
				FROM social_member sm
				INNER JOIN my_pages mp
				ON sm.id=mp.ownerid
				WHERE mp.type=1 {$filterArea} {$filterBrand}";
		$rs = $this->apps->fetch($sql,1);

		if($rs) return $rs;
		return false;
	}

	function loadCity(){
	 	global $CONFIG;

		$sql="SELECT *
				FROM beat_city_reference
				WHERE id IN (140,199,215,292,318,407,451)";
		$rs = $this->apps->fetch($sql,1);

		if($rs) return $rs;
		return false;
	}

	function sendBadge($list){
		global $CONFIG;

		$badgeID = intval($this->apps->_p('badgeID'));
		$badgePoint = intval($this->apps->_p('badgePoint'));
		
		foreach ($list as $key => $value) {
			$sql="INSERT INTO my_badge (id,badgecode,badgepoint,userid,datetime,n_status)
					VALUES (NULL,{$badgeID},{$badgePoint},{$value},NOW(),1)";

			if(!$this->apps->query($sql)) return false;
		}
		return true;
	}

	function deleteBadge(){
		global $CONFIG;

		$badgeID = intval($this->apps->_g('id'));

		$sql = "UPDATE tbl_badge_detail set n_status = 2 WHERE id = {$badgeID}";

		if(!$this->apps->query($sql)) return false;
		return true;
	}

	function publishMerchandise(){
		global $CONFIG;

		$id = intval($this->apps->_g('id'));

		$sql = "UPDATE tbl_merchandise_detail set n_status = 1 WHERE id = {$id}";
		
		if(!$this->apps->query($sql)) return false;
		return true;
	}

	function deleteMerchandise(){
		global $CONFIG;

		$id = intval($this->apps->_g('id'));

		$sql = "UPDATE tbl_merchandise_detail set n_status = 2 WHERE id = {$id}";

		if(!$this->apps->query($sql)) return false;
		return true;
	}

	function loadBrandDetail(){
		global $CONFIG;

		$id = intval($this->apps->_g('id'));

		$sql = "SELECT *,DATE_FORMAT(expired_date,'%Y-%m-%d') AS ed,DATE_FORMAT(created_date,'%Y-%m-%d') AS cd 
				FROM beat_news_content WHERE id = {$id}";
		$rs = $this->apps->fetch($sql);
		
		if($rs)return $rs;
		return false;
	}
	
	
	
	
	
	
	function getChallangeData($cid=false){
	
		if($cid==false) return false;
		global $CONFIG;
		$sql ="
		SELECT c.*,b.id as badgeid , b.name as badgename, b.image 
		FROM {$this->dbshema}_news_content_challenge c
		LEFT JOIN tbl_badge_detail b ON b.id = c.badgeid
		WHERE contentid = {$cid} AND c.n_status = 1  LIMIT 1";
		$qData = $this->apps->fetch($sql);
		// pr($sql);
		$qData['imagepath'] = false;
			
		if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}badges/{$qData['image']}"))  	$qData['imagepath'] = "badges";	
			// pr("{$CONFIG['LOCAL_PUBLIC_ASSET']}badges/{$qData['image']}");		
		//CHECK FILE SMALL
		if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}{$qData['imagepath']}/small_{$qData['image']}")) $qData['image'] = "small_{$qData['image']}";
		
		
		if($qData['imagepath']) $qData['image_full_path'] = $CONFIG['ADMIN_DOMAIN_PATH']."public_assets/".$qData['imagepath']."/".$qData['image'];
		else $qData['image_full_path'] = false;
		
		return $qData;
		
	}
	
	function newlistuploadphoto()
	{
	global $CONFIG;
	
		$sql = "SELECT sm.id, sm.name, sm.last_name, sm.email, sm.image_profile, sm.register_date, cr.city cityname, sm.n_status, sm.photo_moderation
				FROM {$CONFIG['DATABASE_WEB']}.social_member sm
				LEFT JOIN {$CONFIG['DATABASE_WEB']}.city_references cr ON sm.city = cr.id WHERE 1 ORDER BY sm.register_date DESC LIMIT 5
		";
		$qData = $this->apps->fetch($sql,1);
		if($qData) {
			$no = 1;
			foreach($qData as $key => $val){
				$val['no'] = $no++;
				$qData[$key] = $val;
			}
		}
		return $qData;
	
	}
	
	function badgeslist(){
		global $CONFIG;
	
		$sql = "SELECT mb.redeem_date, sm.name, sm.last_name, bc.code, b.image, b.id badgesid FROM {$CONFIG['DATABASE_WEB']}.my_badges mb 
				LEFT JOIN {$CONFIG['DATABASE_WEB']}.badges b ON mb.badgesid = b.id
				LEFT JOIN {$CONFIG['DATABASE_WEB']}.social_member sm ON mb.userid = sm.id
				LEFT JOIN {$CONFIG['DATABASE_WEB']}.badges_code bc ON mb.codeid = bc.id
				ORDER BY mb.redeem_date DESC LIMIT 5";
		$qData = $this->apps->fetch($sql,1);
		if($qData) {
			$no = 1;
			foreach($qData as $key => $val){
				$val['no'] = $no++;
				$qData[$key] = $val;
			}
		}
		return $qData;
	}
	
	function eventlist(){
		global $CONFIG;
		
		$sql ="SELECT sm.name, sm.last_name, sb.title, sb.desc description, sb.uploaddate, sb.img, sb.n_status, sb.win, IF( v.totalvote IS NULL , 0, v.totalvote ) totalvote
		FROM {$CONFIG['DATABASE_WEB']}.share_brag_contest sb
		LEFT JOIN {$CONFIG['DATABASE_WEB']}.social_member sm ON sb.userid = sm.id
		LEFT JOIN (

		SELECT COUNT( * ) totalvote, contentid
		FROM {$CONFIG['DATABASE_WEB']}.votes
		WHERE n_status =1
		GROUP BY contentid
		)v ON v.contentid = sb.id
		WHERE sb.n_status
		IN ( 1, 2 )
		ORDER BY sb.uploaddate DESC
		LIMIT 5";
		$qData = $this->apps->fetch($sql,1);
		if($qData) {
			$no = 1;
			foreach($qData as $key => $val){
				$val['no'] = $no++;
				$qData[$key] = $val;
			}
		}
		return $qData;
	
	}
	
	function redeemuserlist(){
		global $CONFIG;
		
		$sql = "SELECT mc.id, sm.name, sm.last_name, mc.email, mc.address, c.name merchname, mc.redeemdate, mc.phonenumber, mc.n_status
				FROM {$CONFIG['DATABASE_WEB']}.my_collectables mc
				LEFT JOIN {$CONFIG['DATABASE_WEB']}.social_member sm ON mc.userid = sm.id
				LEFT JOIN {$CONFIG['DATABASE_WEB']}.collectables c ON mc.merchandiseid = c.id
				WHERE 1 ORDER BY mc.redeemdate";
		$qData = $this->apps->fetch($sql,1);
		if($qData) {
			$no = 1;
			foreach($qData as $key => $val){
				$val['no'] = $no++;
				$qData[$key] = $val;
			}
		}
		return $qData;
	}	
	
}
?>

