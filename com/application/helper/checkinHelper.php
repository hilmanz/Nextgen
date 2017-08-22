<?php 

class checkinHelper {

	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);

		$this->dbschema = "beat";	
		$this->radius = intval($CONFIG['radius']) / 10000;
		$this->pradius = 500 / 10000;
		$this->dbshema = "beat";
	}


	function searchvenue($coor=null,$keywords=null){
		// search vennue
		$limit = 30;
		$start= intval($this->apps->_request('start'));
		
		if (strip_tags($this->apps->_request('coor'))) {
			$coor = strip_tags($this->apps->_request('coor'));
		} else {
			if ($coor) $coor;
		}
		/* testing */
		
		$searchKeyOn = array("latitude","longitude");
		
		if (strip_tags($this->apps->_p('keywords'))) {
			$keywords = strip_tags($this->apps->_p('keywords'));
		} else {
			if ($keywords) $keywords;
		}
		
		$keywords = rtrim($keywords);
		$keywords = ltrim($keywords);
		if($coor=='') return false;
		$realkeywords = $keywords;
		$newcoor =false;
		$lon = 0;
		$lonmax = 0;
		$lat = 0;
		$latmax = 0;
		
		
			/* radius calc */
			$arrcoor = explode(',',$coor);
			if(is_array($arrcoor)){
				$lat = $arrcoor[0] + $this->radius;
				$latmax = $arrcoor[0] - $this->radius;
				$lon = $arrcoor[1] - $this->radius;
				$lonmax = $arrcoor[1] + $this->radius;
		
			}
			
			/*
			
				SELECT SUBSTR(coor,1, LOCATE(',',coor)-1) lon, SUBSTR(coor,LOCATE(',',coor)+1) lat FROM `beat_city_reference` WHERE 1
			*/
			foreach($searchKeyOn as $key => $val){
				$searchKeyOn[$key] = " vm.{$val} like '%{$realkeywords}%' ";
				if($val=="city") $searchKeyOn[$key] = " vm.{$val} like '%{$realkeywords}%' ";
				
				if($val=="latitude"&&$lat!=0&&$latmax!=0) {
					$searchKeyOn[$key] = " 	vm.{$val} >= '{$lat}' AND vm.{$val} <= '{$latmax}' ";					
				}
				
				if($val=="longitude"&&$lon!=0&&$lonmax!=0) {
					$searchKeyOn[$key] = " vm.{$val} >= '{$lon}' AND vm.{$val} <= '{$lonmax}' ";					
									
				}
			}
		$keywordsearch = "";
		if($keywords) $keywordsearch = " AND ( vm.venuename like '%{$realkeywords}%' OR vm.city like '%{$realkeywords}%'  OR vm.address  like '%{$realkeywords}%' ) ";
		$strSearchKeyOn = implode(" AND ",$searchKeyOn);
		$qKeywords = " 	AND  ( {$strSearchKeyOn} {$keywordsearch} )";

		$sql = "
		SELECT vm.*,vm.venuename venue
		FROM {$this->dbschema}_venue_master vm		 
		WHERE 1  {$qKeywords} 
		GROUP BY vm.id 
		ORDER BY abs(vm.latitude - {$lat}) ASC, abs(vm.longitude - {$lon}) ASC, vm.venuename ASC LIMIT {$start},{$limit}";
		//pr($sql);
		$this->logger->log($coor);
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql,1);
		if($qData){
			$venueid = false;
			$datacheckin = false;
			$datatotalcheckin = false;
			foreach($qData as $val){
					$venueid[$val['id']] = $val['id'];
			}
			if($venueid){
				$strvenue = implode(',',$venueid);
			}
			if($strvenue){
				$sql ="SELECT COUNT(*) total,venueid FROM my_checkin WHERE  userid={$this->uid} AND venueid IN  ({$strvenue}) GROUP BY venueid  ";
				$this->logger->log($sql);
				$mycheckin = $this->apps->fetch($sql,1);
				if($mycheckin){
				
					foreach($mycheckin as $val){
						$datacheckin[$val['venueid']] = $val['total'];
					}
					
				}
				
				
				$sql ="SELECT COUNT(*) total,venueid FROM my_checkin WHERE  venueid IN  ({$strvenue}) GROUP BY venueid  ";
				$this->logger->log($sql);
				$mycheckin = $this->apps->fetch($sql,1);
				if($mycheckin){
				
					foreach($mycheckin as $val){
						$datatotalcheckin[$val['venueid']] = $val['total'];
					}
					
				}
			}
			
			foreach( $qData as $key => $val ){
				
				$qData[$key]['mycheckin'] = 0;
				$qData[$key]['totalcheckin'] = 0;
				
				$qData[$key]['keywords'] = html_entity_decode(ucwords(strtolower($val['venue'])));
				$qData[$key]['venue'] = html_entity_decode(ucwords(strtolower($val['venuename'])));
				if($val['address']) $qData[$key]['venuename'] = html_entity_decode(ucwords(strtolower($val['address'])));
				$qData[$key]['city'] = html_entity_decode(ucwords(strtolower($val['city']))); 
				$qData[$key]['provinceName'] = html_entity_decode(ucwords(strtolower($val['provinceName'])));
			
				if($datacheckin){
					if(array_key_exists($val['id'],$datacheckin)) $qData[$key]['mycheckin'] = $datacheckin[$val['id']];
				}
				if($datatotalcheckin){
					if(array_key_exists($val['id'],$datatotalcheckin)) $qData[$key]['totalcheckin'] = $datatotalcheckin[$val['id']];
				}
			}
			
			
			return $qData;
		}			
		
		return false;
	}
	
	function getVenue($start=null,$limit=10,$filter=null){
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
	
		if($start==null)$start = intval($this->apps->_request('start'));		
		$limit = intval($limit);
		$start= intval($this->apps->_request('start'));		
		
		$search = "";
		$orderBy = "ORDER BY id DESC";
		
		if ($this->apps->_p('search')) {
			if ($this->apps->_p('search')!="Search...") {
				$search = rtrim(strip_tags($this->apps->_p('search')));
				$search = ltrim($search);
				
				if(strpos($search,' ')) $parseSearch = explode(' ', $search);
				else $parseSearch = false;
				
				
				
				if(intval($this->apps->_p('searchType'))==2){
					if(is_array($parseSearch)) $search = $search.'|'.$parseSearch[0];
					else  $search = trim($search);
					
					$orderBy = "ORDER BY provinceName,city DESC";
				}else{
					if(is_array($parseSearch)) $search = $search.'|'.trim(implode('|',$parseSearch));
					else  $search = trim($search);

					$orderBy = "ORDER BY venuename,address DESC";
				}
				$search = "AND (address REGEXP  '{$search}' OR venuename REGEXP  '{$search}' OR provinceName REGEXP  '{$search}' OR city REGEXP  '{$search}') ";

			}
			
			if ($this->apps->_p('startdate')) {
				$start_date = $this->apps->_p('startdate');
				$startdate = " AND DATE(datetimes) >= DATE('{$start_date}') ";
			}
			if ($this->apps->_p('enddate')) {
				$end_date = $this->apps->_p('enddate');
				$enddate = " AND DATE(datetimes) <= DATE('{$end_date}') ";
			}
			
		}
		$qStatus = " AND n_status   = 1 ";
		if ($this->apps->_p('publishedtype')) {
			$publishedtype = intval($this->apps->_p('publishedtype'));
			$qStatus = " AND n_status   =  {$publishedtype}  ";
		}
		
		//GET TOTAL VENUE
		$sql_total = "
			SELECT count(*) as total
			FROM {$this->dbschema}_venue_master
			WHERE 1 {$search}
			AND venuename <> '' AND venuename IS NOT NULL 
			{$startdate} {$enddate}	
			{$qStatus} ";
		$total = $this->apps->fetch($sql_total);
		if(intval($total['total'])<=$limit) $start = 0;
		//pr($sql_total);
		$cid = $this->apps->_request('id');	
		$detailID="";	
		if ($filter) {
			$detailID = "AND id = {$cid}";
			$limit = 1;
		}
		
		// GET VENUE
		$sql = "
			SELECT *
			FROM {$this->dbschema}_venue_master
			WHERE 1 {$search} 
			AND venuename <> '' AND venuename IS NOT NULL 	
			{$startdate} {$enddate}		
			{$qStatus} 
			{$detailID} {$orderBy} LIMIT {$start},{$limit}
		";
		//pr($sql);
		$qData = $this->apps->fetch($sql,1);
		//pr($qData);exit;
		 
		$result['result'] = $qData;
		$result['total'] = $total['total'];
		return $result;
	}
	
	function checkin(){
		global $CONFIG;
		
		$data['status'] =  false;
		$data['questioner']	= false;
			
		$data['entourages']	= array();
		$venueid = intval($this->apps->_p('venueid'));
		$contentid = intval($this->apps->_p('cid'));
		$this->logger->log("checkin by plan : id {$contentid}");
		$venue = strip_tags($this->apps->_p('venue'));
		$brief = strip_tags($this->apps->_p('brief'));
		$title = strip_tags($this->apps->_p('title'));
		$content = strip_tags($this->apps->_p('content'));
		$venuerefid = intval($this->apps->_p('venuerefid'));
		$coor = strip_tags($this->apps->_p('coor'));
		$mypagestype = intval($this->apps->_p('mypagestype'));
		$friendtags = $this->apps->_p('fid');
		$friendtypetags = $this->apps->_p('ftype');
		$rating = intval($this->apps->_p('rating'));
		$prize = intval($this->apps->_p('prize'));
		$wifi = intval($this->apps->_p('wifi'));
		$smoking = intval($this->apps->_p('smoking'));
		$image = '';
		$type = 3;
		$fromwho = 1;
		if(!$this->uid) return $data;
		
		
		
		$authorid = intval($this->uid);
		
		if($mypagestype==0) $mypagestype = 1;
		/* radius calc */
			$arrcoor = explode(',',$coor);
			if(is_array($arrcoor)){
				$lat = $arrcoor[0];				
				$lon = str_replace('+','',$arrcoor[1]);
			
		
			}
		$path = $CONFIG['LOCAL_PUBLIC_ASSET']."article/";
		
			$this->logger->log(json_encode(@$_FILES['image']));
			
			if (isset($_FILES['image'])&&$_FILES['image']['name']!=NULL) {
				if (isset($_FILES['image'])&&$_FILES['image']['size'] <= 20000000) {
					$data = $this->apps->uploadHelper->uploadThisImage($_FILES['image'],$path);
				
						if ($data['arrImage']!=NULL) {
								$image = $data['arrImage']['filename'];
						}
				}
			}
		$posted_date = date('Y-m-d H:i:s');	
		$parentid = 0;
		$categoryid = 0;
		if($contentid){
			$sql =" 
				SELECT *,count(*) total
				FROM  {$this->dbshema}_news_content nc
				WHERE 
				id = {$contentid}  				
				AND EXISTS 
				(
					SELECT contentid 
					FROM my_checkin mc 
					WHERE nc.id=mc.contentid AND userid={$this->uid} 
				) 
				LIMIT 1";
				  $this->logger->log($sql);
				$contentcheckin = $this->apps->fetch($sql);
				if($contentcheckin){
					
					if($contentcheckin['total']>0) {
					
						if($contentcheckin['categoryid']==2||$contentcheckin['categoryid']==3){
							$parentid = $contentcheckin['id'];
							$type = 5;
							
						}
						$categoryid = $contentcheckin['categoryid'];
						$contentid = 0;
						
					}else{
							$sql =" 
								SELECT *,count(*) total
								FROM  {$this->dbshema}_news_content nc
								WHERE 
								id = {$contentid}  AND articleType = 5	AND authorid<>{$this->uid} LIMIT 1";
								 $this->logger->log($sql);
								$contentcheckin = $this->apps->fetch($sql);
								if($contentcheckin){
							
									if($contentcheckin['total']>0) {
									
										if($contentcheckin['categoryid']==2||$contentcheckin['categoryid']==3){
											$parentid = $contentcheckin['id'];
											$type = 5;
										}
										$categoryid = $contentcheckin['categoryid'];
										$contentid = 0;
										
									}
								}
					}
				}
		}
		
		if(!$contentid){
			 	
		
		
			
			
			$sql ="
				INSERT INTO {$this->dbshema}_news_content (cityid,brief,title,content,tags,image,articleType,created_date,posted_date,authorid,fromwho,n_status,url,parentid,categoryid) 
				VALUES ('{$venueid}','{$brief}','{$title}',\"{$content}\",'','{$image}',{$type},NOW(),'{$posted_date}','{$authorid}','{$fromwho}',1,'',{$parentid},{$categoryid})
				";
				
				// pr($sql);
			$this->logger->log($sql);
			$this->apps->query($sql);
			if($this->apps->getLastInsertId())  $contentid = $this->apps->getLastInsertId();
		}else{
			if($contentid!=0){
				
				$sql = "
				SELECT posted_date,id ,brief
				FROM  {$this->dbshema}_news_content 
				WHERE id = {$contentid} 
				AND posted_date >= DATE_SUB(NOW(),INTERVAL 1 DAY ) 
				AND  posted_date <= DATE_ADD(NOW(), INTERVAL 1 DAY )  
				LIMIT 1";
				$this->logger->log(" check content plan data : ".$sql);
				$checkd = $this->apps->fetch($sql);
				if($checkd){
					$contentdata = $checkd['brief'];
					$sql = "UPDATE {$this->dbshema}_news_content SET image='{$image}',posted_date=NOW(),content='{$contentdata}',brief='{$brief}' WHERE id = {$contentid} LIMIT 1";
					$this->logger->log($sql);
					$this->apps->query($sql);
					
				}else return $data;
			}
		}
		
		 // Full texts 	id 	contentid 	userid	venue 	venueid 	venuerefid 	coor	mypagestype join_date 	n_status
		$sql = " INSERT INTO my_checkin(contentid ,	userid	,venue ,	venueid 	,venuerefid 	,latitude,longitude	,mypagestype ,rating ,prize ,wifi ,smoking ,join_date ,	n_status) VALUES 
		({$contentid},{$this->uid},\"{$venue}\",\"{$venueid}\",\"{$venuerefid}\",\"{$lat}\",\"{$lon}\",\"{$mypagestype}\",{$rating},{$prize},{$wifi},{$smoking},NOW(),1)
		";
		// pr($sql);
		$this->logger->log($sql);
		$this->apps->query($sql);
		
		
		
		if($this->apps->getLastInsertId()) {
			if($friendtags){
				$cid = $this->apps->getLastInsertId();
				$arrfid = explode(',',$friendtags);
				$arrftype = explode(',',$friendtypetags);
				// $this->logger->log("before mapping checkin tagging users : ".json_encode($friendtypetags));
				$frienddata = false;
				if(is_array($arrfid)){
					foreach($arrfid as $key => $val){
						$frienddata[$key]['fid'] = $val;
						$frienddata[$key]['ftype'] = intval($arrftype[$key]);
						if(array_key_exists($key,$arrftype)) $frienddata[$key]['ftype'] = $arrftype[$key];
					}
					
					if($frienddata){
						// $this->logger->log("after mapping checkin tagging users : ".json_encode($frienddata));
						foreach($frienddata as $val){
							// $this->logger->log("after mapping checkin tagging users; sending data : ".json_encode($val));
							$this->apps->contentHelper->addFriendTags($contentid,intval($val['fid']),intval($val['ftype']));
						}
					
					}
				}else{
					$this->apps->contentHelper->addFriendTags($contentid,intval($friendtags),intval($friendtypetags));
				}
			}
			
			
			// check BA[ per entourage ] engagement for questioner
			
			$engagement = $this->checkengagement($contentid,$friendtags);
			$data['status'] =  true;
			if($engagement){
				$data['questioner']	= $engagement['questioner'];	
				$data['entourages']	= $engagement['entourage'];
			}
			return $data;
		}else return $data;
	}
	
	
	function checkengagement($contentid=false,$friendtags=false){
		if($contentid==false) return false;
		if($friendtags==false) return false;
		$data['questioner'] = false;
		$data['entourage'] = array();
		
		$sql = " SELECT COUNT(*) total FROM  tbl_brand_questioner WHERE  entourageid IN ({$friendtags}) AND n_status = 0 AND DATE(datetimes) = DATE(NOW())  LIMIT 1 ";
		$qTotal = $this->apps->fetch($sql);
		if($qTotal) {
			if($qTotal['total']>0) return false;
		}
		$sql = " SELECT * FROM  {$this->dbshema}_news_content WHERE id = {$contentid} AND ( articleType= 5 OR categoryid IN (2,3) ) LIMIT 1 ";
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;

		$sql =" 
			SELECT COUNT(*) total,a.friendid,a.friendtype,a.Brand1_ID,a.Brand1U_ID,a.name,a.last_name 
			FROM(
				SELECT COUNT(*) total ,tags.friendid,tags.friendtype,e.Brand1_ID,e.Brand1U_ID,e.name,e.last_name 
				FROM 
				my_checkin mc 
				LEFT JOIN {$this->dbshema}_news_content nc ON nc.id = mc.contentid
				LEFT JOIN {$this->dbshema}_news_content_tags tags ON tags.contentid =mc.contentid
				LEFT JOIN my_entourage e ON e.id = tags.friendid AND tags.friendtype = 0
				WHERE 
				mc.userid={$this->uid} 
				AND ( nc.articleType= 5 OR nc.categoryid IN (2,3) )
				AND tags.friendtype = 0
				AND friendid IS NOT NULL
				AND friendid IN  ({$friendtags})
				GROUP BY tags.friendid, DATE(tags.date) ORDER BY  tags.date ASC
			) a	 
			GROUP BY a.friendid
			 
		";
		
		$qData = $this->apps->fetch($sql,1);
		// pr($sql);
		// pr($qData);
		if(!$qData) return $data;
		// pr($sql);
		/* count mod 3 and 6
		 engagement 		 
		 
			// check if n /6 not have comma , position = 6;
			// check if x = n/ 6 have comma, possition = n - (FLOOR(x) * 6 ) ;
			  
		*/
		$position = false;
		foreach($qData as $val){
			$engagement = intval($val['total']);
			
			if($engagement > 6 ){
				if($engagement%6==0) $position[$val['friendid']] = true;
				else{
					$x = FLOOR(($engagement/6) * 6 );
					$xn = $engagement - $x;
					if($xn%3) $position[$val['friendid']] = true;
					else $position[$val['friendid']] = false;
				}
			}else{
				if($engagement%3==0)  $position[$val['friendid']] = true;
				else $position[$val['friendid']] = false;
			}
			$entouragearr[$val['friendid']]=$val;
		}
		if(in_array(true,$position))  $questioner = true;
		else $questioner = false;
		$data['questioner'] = $questioner;
		
		foreach($position as $key => $val){
			if($val==true)if(array_key_exists($key,$entouragearr)) $entourage[] = $entouragearr[$key];
		}
		
		if($entourage)		$data['entourage'] =$entourage;
		else 		$data['entourage'] =array();
		
		return  $data;
		
	}
	
	function addvenue(){
		$venueid = intval($this->apps->_p('venueid'));
		$keywords = strip_tags($this->apps->_p('keywords'));	// android , ipad is venuename
 
		$city = strip_tags($this->apps->_p('city'));	
		// $venuename = strip_tags($this->apps->_p('venuename'));	// android , ipad is address
		$venuename = strip_tags($this->apps->_p('keywords'));	// android , ipad is address
		
		// $address = strip_tags($this->apps->_p('address'));	
		$address = strip_tags($this->apps->_p('venuename'));	
		
		$venuecategory = intval($this->apps->_p('category'));	
		if($venuename=='') $venuename = $keywords;
		$coor = strip_tags($this->apps->_p('coor'));
			$searchKeyOn = array("latitude","longitude");
	
				/* radius calc */
			$arrcoor = explode(',',$coor);
			if(is_array($arrcoor)){
				$lati = $arrcoor[0];				
				$longi = str_replace('+','',$arrcoor[1]);	
			}
		
		
	
		/* radius calc */
		$arrcoor = explode(',',$coor);
		if(is_array($arrcoor)){
			$lat = $arrcoor[0] + $this->pradius;
			$latmax = $arrcoor[0] - $this->pradius;
			$lon = $arrcoor[1] - $this->pradius;
			$lonmax = $arrcoor[1] + $this->pradius;
	
		}
		
		foreach($searchKeyOn as $key => $val){
				
			if($val=="latitude"&&$lat!=0&&$latmax!=0) {
				$searchKeyOn[$key] = " 	{$val} >= '{$lat}' AND {$val} <= '{$latmax}' ";					
			}
			
			if($val=="longitude"&&$lon!=0&&$lonmax!=0) {
				$searchKeyOn[$key] = " {$val} >= '{$lon}' AND {$val} <= '{$lonmax}' ";					
								
			}
		}
	
		$strSearchKeyOn = implode(" AND ",$searchKeyOn);
		$qKeywords = " 	AND  ( {$strSearchKeyOn} )";

		$sql = "
		SELECT * FROM {$this->dbschema}_venue_master 
		WHERE 1 {$qKeywords} AND venuename <> '' AND venuename IS NOT NULL ORDER BY id ASC LIMIT 1 ";
		//pr($sql);
		$this->logger->log($sql);
		$mastervenue = $this->apps->fetch($sql);
		if($mastervenue){
			$provinceName = $mastervenue['provinceName'];
			if($city=='') $city = $mastervenue['city'];
			if($address=='') $address = $mastervenue['address'];
		}else{
			$provinceName = $keywords;
			if($city=='') $city = $keywords;
			if($address=='') $address = $keywords;
		}
		
		if($provinceName=='') return false;
		if($city=='') return false;
		if($venuename=='') return false;
		if($lati=='') return false;
		if($longi=='') return false;
		
		if($venueid!=0){
			$sql ="INSERT INTO {$this->dbschema}_venue_reference
				( venueid,	keywords ,latitude,longitude	, 	datetime , n_status )
				VALUES({$venueid},\"{$keywords}\",\"{$lati}\",\"{$longi}\",NOW(),1)
				";
				
				$this->apps->query($sql);
				if($this->apps->getLastInsertId()) 	{
						$data['result'] = true;
						$data['venueid'] = $venueid;
						$data['venuename'] = $venuename;
						$data['keywords'] = $keywords;
						$data['venuerefid'] = $this->apps->getLastInsertId();
						$data['coor'] = $coor;
						return $data;
				}
				else {
					$data['result'] = false;
					return $data;
				}
		}else{
			/* tambahan klo kirim ke master juga */
			$sql ="INSERT INTO {$this->dbschema}_venue_master 
			( provinceName ,	city ,	venuename ,	latitude ,	longitude ,	venuecategory ,	address,	n_status )
			VALUES(\"{$provinceName}\",\"{$city}\",\"{$venuename}\",\"{$lati}\",\"{$longi}\",'{$venuecategory}',\"{$address}\",1)
			";
				// pr($sql);
			$this->logger->log($sql);
			$this->apps->query($sql);
			if($this->apps->getLastInsertId()) {
				$venueid = $this->apps->getLastInsertId();
				$sql ="INSERT INTO {$this->dbschema}_venue_reference
				( venueid,	keywords ,latitude,longitude	, 	datetime , n_status )
				VALUES({$venueid},\"{$keywords}\",\"{$lati}\",\"{$longi}\",NOW(),1)
				";
				
				$this->apps->query($sql);
				if($this->apps->getLastInsertId()) 	{
						$data['result'] = true;
						$data['venueid'] = $venueid;
						$data['venuename'] = $venuename;
						$data['keywords'] = $keywords;
						$data['venuerefid'] = $this->apps->getLastInsertId();
						$data['coor'] = $coor;
						return $data;
				}
				else {
					$data['result'] = false;
					return $data;
				}
			}else return false;
		
		}
		return false;
		
		
	}
	
		
	function uncheckin(){
		$cid = intval($this->apps->_p('cid'));
		
		$sql = " UPDATE my_checkin SET n_status = 0 WHERE userid={$this->uid} AND id={$cid} LIMIT 1";
		// pr($sql);
		$qData = $this->apps->query($sql);
		if($qData) return true;
		else return false;
	}
	
	
}

?>

