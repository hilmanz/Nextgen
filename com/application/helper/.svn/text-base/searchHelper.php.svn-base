<?php 

class searchHelper {


	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;

		$this->apps = $apps;
		if($this->apps->isUserOnline())  {
			if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
			
		}
		
		if(isset($_SESSION['lid'])) $this->lid = intval($_SESSION['lid']);
		else $this->lid = 1;
		if($this->lid=='')$this->lid=1;
		$this->server = intval($CONFIG['VIEW_ON']);
		$this->osDetect = new Mobile_Detect;
		
		
		$this->dbshema = "beat";
		
		$this->approver=array(1,2,3,4);
		$this->inviter=array(1,2,3,4);
	}
	
	function search($start=null,$limit=10, $category=null,$keywords=null) {
		global $CONFIG;
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
		
		if($keywords==null) $keywords = strip_tags($this->apps->_request('q'));
		if($keywords==null)	return false;
		
		$keywords = rtrim($keywords);
		$keywords = ltrim($keywords);
		
		if(strpos($keywords,' ')) $parseKeywords = explode(' ', $keywords);
		else $parseKeywords = false;
		
		if(is_array($parseKeywords)) $keywords = $keywords.'|'.trim(implode('|',$parseKeywords));
		else  $keywords = trim($keywords);
		
		
		//get total article
		$sql = "
		SELECT count(*) total 
		FROM {$this->dbshema}_news_content anc
		LEFT JOIN social_keywords_search keywords ON  keywords.contentid=anc.id AND keywords.keyword  REGEXP  '{$keywords}' 
		WHERE 
		( anc.title REGEXP  '{$keywords}'  OR	
			anc.brief REGEXP  '{$keywords}' 
		)
		AND n_status NOT IN (0,3) ";
		$total = $this->apps->fetch($sql);
		if(!$total) return false;
		if($start>intval($total['total'])) return false;
		if(intval($total['total'])<=$limit) $start = 0;		
		
		$sql = "
		SELECT anc.id,anc.title,anc.brief,anc.image,anc.thumbnail_image,anc.slider_image,anc.posted_date,anc.file,anc.url,anc.fromwho,anc.tags,anc.authorid,anc.topcontent,anc.cityid,anct.type pagesname
			FROM {$this->dbshema}_news_content anc
			LEFT JOIN {$this->dbshema}_news_content_category ancc ON ancc.id = anc.categoryid
			LEFT JOIN {$this->dbshema}_news_content_type anct ON anct.id = anc.articleType
			LEFT JOIN social_keywords_search keywords ON  keywords.contentid=anc.id AND keywords.keyword  REGEXP  '{$keywords}' 
			WHERE
				( anc.title REGEXP  '{$keywords}'  OR	
					anc.brief REGEXP  '{$keywords}' 
				) AND n_status NOT IN (0,3)  
			ORDER BY posted_date DESC , id DESC
			LIMIT {$start},{$limit}";
		//pr($sql);
		$rqData = $this->apps->fetch($sql,1);
		if(!$rqData) return false;
		//cek detail image from folder
			//if is article, image banner do not shown
		foreach($rqData as $key => $val){
			if(file_exists("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}")) $rqData[$key]['banner'] = false;
			else $rqData[$key]['banner'] = true;	
			
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
					
		}
	
		if($rqData) $qData=	$this->apps->contentHelper->getStatistictArticle($rqData);
		else $qData = false;
		
		if(!$qData) return false;

		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
		//pr($result);
		//exit;
		return $result;
	}
	
	function addKeywords($keywords=null,$contentid=0,$fromwhere=0,$links=null){
		if($keywords==null) return false;
		if($links==null) return false;
		if($contentid==0) return false;
		
		
		$sql ="	INSERT INTO social_keywords_search (keyword,link,contentid,fromwhere,datetime,count) 
				VALUES (\"{$keywords}\",\"{$links}\",{$contentid},{$fromwhere},NOW(),1)
				ON DUPLICATE KEY UPDATE count=count+1;
		";
		// pr($sql);exit;
		$this->apps->query($sql);
		if($this->apps->getLastInsertId()>0) return true;
		else return false;
		
	}
	
	function filterEngine($limit=10,$groupbytype=false,$author=false){
		
		$searchKeyOn = array("title","brief","tags");
		$thesubquery = false;
		$fromwhoQuery = false;
		$suborderfilter = false;
	
		/* filter */
			$start = intval($this->apps->_request('start'));
			$categorytype = intval($this->apps->_p('categorytype'));
			$bandcategory = intval($this->apps->_p('bandcategory'));
			$citycategory = intval($this->apps->_p('cityid'));
			$dateposted = intval($this->apps->_p('dateposted'));
			$filtertype = strip_tags($this->apps->_p('filtertype'));
			
			$contentType = strip_tags($this->apps->_g('act'));
			$uidType = intval($this->apps->_request('uid'));
			if($author)	$uidType = $this->uid;
			
			$keywords = strip_tags($this->apps->_p('q'));	
			$keywords = rtrim($keywords);
			$keywords = ltrim($keywords);
			if(strpos($keywords,' ')) $parseKeywords = explode(' ', $keywords);
			else $parseKeywords = false;
			if(is_array($parseKeywords)) $keywords = $keywords.'|'.trim(implode('|',$parseKeywords));
			else  $keywords = trim($keywords);
			if($keywords!=''){
				foreach($searchKeyOn as $key => $val){
					$searchKeyOn[$key] = "{$val} REGEXP '{$keywords}'";
				}
				$strSearchKeyOn = implode(" OR ",$searchKeyOn);
				$qKeywords = " 	AND  ( {$strSearchKeyOn} )";
			}else $qKeywords = "";
		
		if($categorytype!=0) $qCategory = " AND categoryid={$categorytype} ";
		else $qCategory = "";
		
		if($citycategory!=0) $qCityId = " AND cityid={$citycategory} ";
		else $qCityId = "";
		
		if($dateposted!=0) $qPostedDate = " AND DATE_FORMAT(posted_date,'%m') = {$dateposted} ";
		else $qPostedDate = "";
		
		$arrContentId = false;
		if($bandcategory!=0) {
			//popular
			$sql = " SELECT contentid FROM {$this->dbshema}_news_content_comment WHERE n_status = 1 AND contentid IN ({$bandcategory}) GROUP BY contentid ORDER BY total DESC LIMIT  {$start},{$limit} ";
			$thesubquery = "LEFT JOIN ({$sql}) subs ON anc.id=subs.contentid  ";
			$suborderfilter = "" ;
		}
			
		if($filtertype!='') {
			if($filtertype=="popular"){
				//popular
				$sql = " SELECT count(*) total, contentid FROM {$this->dbshema}_news_content_comment WHERE n_status = 1 GROUP BY contentid ORDER BY total DESC LIMIT  {$start},{$limit} ";
				$thesubquery = "LEFT JOIN ({$sql}) subs ON anc.id=subs.contentid  ";
				$suborderfilter = "subs.total DESC ," ;
			}
			
			if($filtertype=="terbaik"){
				//terbaik
				$sql = " SELECT count(*) total, contentid FROM {$this->dbshema}_news_content_favorite WHERE n_status = 1 GROUP BY contentid ORDER BY total DESC LIMIT  {$start},{$limit} ";
				$thesubquery = "LEFT JOIN ({$sql}) subs ON anc.id=subs.contentid ";
				$suborderfilter = "subs.total DESC ," ;
			}
			
			if($filtertype=="weekly"){
				//weekly				
				$suborderfilter = " DATE_FORMAT(posted_date,'%m') DESC ," ;				
			}
		}
		
		if($contentType=="article"){
			$fromwhoQuery = " AND fromwho = 0  " ;			
		} else {
			if(strip_tags($this->apps->_g('act')=="article" || strip_tags($this->apps->_p('acts')=="article"))) {
				$fromwhoQuery = "AND fromwho IN (0)";
			} else {
				if ($this->apps->_g('page')=="picks") {
					$fromwhoQuery = "AND fromwho IN (0,1,2)";
				} else {
					$fromwhoQuery = "AND fromwho IN (1,2)";
				}
			}
		}
		
		
		if($uidType!=0){
				// $uidQuery = " AND ( authorid IN ({$uidType}) OR EXISTS ( SELECT contentid FROM {$this->dbshema}_news_content_tags WHERE friendid IN ({$uidType}) AND friendtype=1  AND contentid=anc.id) )  ";		
				$uidQuery = " AND  authorid IN ({$uidType})  ";		
		}else $uidQuery = "";
		
		if($groupbytype){
				$groupbyQuery = "  GROUP BY {$groupbytype} ";		
		}else $groupbyQuery = "";
		
		/* end of filter */
		$data['keywordsearch'] = $qKeywords;
		$data['categorysearch'] = $qCategory;
		$data['citysearch'] = $qCityId;
		$data['postedsearch'] = $qPostedDate;
		$data['subqueryfilter'] = $thesubquery;
		$data['suborderfilter'] = $suborderfilter;
		$data['fromwhosearch'] = $fromwhoQuery;
		$data['uidsearch'] = $uidQuery;
		$data['groupbyfilter'] = $groupbyQuery;
		return $data;
	}
}
?>