<?php 

class entourageHelper {

	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		if($this->apps->isUserOnline())  {
			if(is_object($this->apps->user)) {
				$uid = intval($this->apps->_request('uid'));
				if($uid==0) $this->uid = intval($this->apps->user->id);
				else $this->uid = $uid;
			}
			
			
		}
		
		$this->config = $CONFIG;
		$this->dbshema = "beat";	
		
		
		$sbaid = intval($this->apps->_p('uid'));
		if($sbaid!=0) $this->qSbaFilter = " AND pages.ownerid IN ({$sbaid}) ";
		else {
			if(@$this->apps->user->leaderdetail->type!=666){
			$auhtorminion = @$this->apps->user->badetail;
			 $auhtorarrid = false;
			if($auhtorminion){
				foreach($auhtorminion as $val){
						$auhtorarrid[$val->ownerid] = $val->ownerid;
				}
			}
			//$sbaid = implode(',',$auhtorarrid);
			//$this->qSbaFilter = " AND pages.ownerid IN ({$sbaid}) ";
				if($auhtorarrid) {
					$sbaid = implode(',',$auhtorarrid);
					$this->qSbaFilter = " AND pages.ownerid IN ({$sbaid}) ";
				}else $this->qSbaFilter ="";


			}else {
				$this->qSbaFilter ="";
			}
		}
		
		$brandid = intval($this->apps->_g('brandid'));
		if($brandid!=0) $this->qBrandFilter = " AND ( pages.brandid IN ({$brandid}) OR pages.brandsubid IN ({$brandid}) ) ";
		else {
			
			$this->qBrandFilter = "   ";
		}
		
		$city = intval($this->apps->_g('city'));
		if($city!=0) $this->qAreaFilter = " AND pages.city IN ({$city}) ";
		else {
			
			$this->qAreaFilter = "   ";
		}
		
		
	}

	 function getDob( ){
		global $CONFIG;
		
		$sql = " SELECT name, birthday
				 FROM my_entourage ";
		$age = $this->apps->fetch($sql);	
		
		// $age = list($day,$month,$year) = explode("/",$birthday);
		// $year_diff  = date("Y") - $year;
		// $month_diff = date("m") - $month;
		// $day_diff   = date("d") - $day;
		// if ($day_diff < 0 || $month_diff < 0)
		  // $year_diff--;
		  
		return $age;
  }
  
  function getBrand(){
	global $CONFIG;
	$sql = " SELECT brandid
			 FROM my_pages ";
	$brand = $this->apps->fetch($sql);
	// pr($brand);
	return $brand;
  }
	
	function getGender(){
	global $CONFIG;
	
	$sql = " SELECT COUNT(*) male FROM my_entourage WHERE sex='Male' ";
	$data['male'] = $this->apps->fetch($sql);			
	
	$sql = " SELECT COUNT(*) Female FROM my_entourage WHERE sex='Female' ";
	$data['female'] = $this->apps->fetch($sql);		
	
	$sql = " SELECT COUNT(*) sex FROM my_entourage ";
	$data['all'] = $this->apps->fetch($sql);		
	
	return $data;
	
	// pr($data);
	
	}
	
	function getEntourage($streid=null,$start=0,$limit=10,$all=false,$summary=false,$alldata=false){
		global $CONFIG;
 
		$sql = "SELECT preferenceid,brandtype,id FROM tbl_brand_preferences_references ";
		$qData = $this->apps->fetch($sql,1);
		if(!$qData)return  array();
		$competitorarr = array();
		$pmiarr = array();
		$ourarr = array();
		foreach($qData as $val){
			if($val['brandtype']==0) $competitorarr[(string)$val['preferenceid']] =(string)$val['id'];
			if($val['brandtype']==1) $pmiarr[(string)$val['preferenceid']] =(string)$val['id'];
			if($val['brandtype']==2) $ourarr[(string)$val['preferenceid']] =(string)$val['id'];
		}
		
		if(intval($this->apps->_request('start'))!=0) $start = intval($this->apps->_request('start'));
		if($streid){			
			$qEntourage = " AND id IN ({$streid}) ";
			
		}else{
			$qEntourage = "";
		}
		if($all){
			$qLimit = " ";
		}else $qLimit = " LIMIT {$start},{$limit} ";
		
		$filter = strip_tags($this->apps->_p('filter'));
		$alphabet = strip_tags($this->apps->_p('alphabet'));
		
		$cityid = strip_tags($this->apps->_p('cityid'));
		$brandid = strip_tags($this->apps->_p('brandid'));
		$areaid = strip_tags($this->apps->_p('areaid'));
		$plid = strip_tags($this->apps->_p('plid'));
		 
		$totalengagement = intval($this->apps->_p('totalengagement'));
		
		$qFilter ="";
		if($filter=="pending") $qFilter = " AND n_status = 0 ";
		if($filter=="accept") $qFilter = " AND n_status = 1 ";
		if($filter=="reject") $qFilter = " AND n_status = 2 ";
		if($filter=="engagement") 	{
			if($totalengagement==0) $qFilter = " AND stat.total<>{$totalengagement} ";
			else  $qFilter = " AND stat.total ={$totalengagement} ";
		}
		
		if($alphabet!='') $qFilterAlphabet = " AND name like '{$alphabet}%' ";
		else $qFilterAlphabet = " ";
		
		
		$qDate = "";
		$startdate = strip_tags($this->apps->_p('startdate'));
		$enddate = strip_tags($this->apps->_p('enddate'));
		
		if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
		
		if($startdate&&$enddate){
			$qDate = " AND DATE(entou.register_date) >= DATE('{$startdate}') AND DATE(entou.register_date) <= DATE('{$enddate}') ";
		}
		$data['result'] = array();
		$data['total'] = 0;
		if($this->apps->user->leaderdetail->type!=1) {
							$uid = intval($this->apps->_request('uid'));
							$auhtorarrid = false;
							if($uid==0)	{
								$filterArea = "";
								$filterBrand = "";
								$filterPL = "";
								if($cityid>0)$filterArea = "AND mp.areaid = {$cityid}";
								if($brandid>0)	$filterBrand = "AND ( mp.brandid = {$brandid} OR mp.brandsubid = {$brandid} ) ";
								if($plid>0)	$filterPL = "AND mp.otherid = {$plid}";
								
								$sql="SELECT sm.id
								FROM social_member sm
								INNER JOIN my_pages mp
								ON sm.id=mp.ownerid
								WHERE mp.type=1 {$filterArea} {$filterBrand} {$filterPL} ";
								$rs = $this->apps->fetch($sql,1);
								$this->logger->log($sql);
								if($rs){
									foreach($rs as $val){
										$auhtorarrid[$val['id']] = $val['id'];
									}
								}
							}
							if(is_array($auhtorarrid)) 	{
								// pr($minionarr);
								$authorids = implode(',',$auhtorarrid);
							}else $authorids = $uid;
							
					 $qUserid = " AND entou.referrerbybrand IN ({$authorids}) ";		
		}else $qUserid = " AND  entou.referrerbybrand ={$this->uid} ";
		if($summary) $qFilter = " AND n_status = 1 ";
		
		
		if($alldata){
			$sql = "	
			SELECT COUNT(*) total, SUM(IF(n_status = 1,1,0)) as accepted, SUM(IF(n_status = 2,1,0)) as rejected FROM my_entourage entou 
			LEFT JOIN ( 
					SELECT count(*) total,tags.id,tags.friendid
						FROM {$this->dbshema}_news_content_tags tags
						LEFT JOIN {$this->dbshema}_news_content content ON content.id = tags.contentid 
					WHERE  
						tags.n_status=1 
						AND tags.friendtype = 0  
						AND ( content.articleType=5 OR content.categoryid IN (2,3) ) 
						AND EXISTS ( SELECT contentid FROM my_checkin checkin WHERE checkin.contentid=tags.contentid AND n_status = 1 )
					GROUP BY tags.friendid  
				) stat ON stat.friendid= entou.id
			WHERE  1 {$qUserid} {$qEntourage} {$qFilter} {$qFilterAlphabet}   ";	
		}else{
			$sql = "	
			SELECT COUNT(*) total, SUM(IF(n_status = 1,1,0)) as accepted, SUM(IF(n_status = 2,1,0)) as rejected FROM my_entourage entou 
			LEFT JOIN ( 
					SELECT count(*) total,tags.id,tags.friendid
						FROM {$this->dbshema}_news_content_tags tags
						LEFT JOIN {$this->dbshema}_news_content content ON content.id = tags.contentid 
					WHERE  
						tags.n_status=1 
						AND tags.friendtype = 0  
						AND  ( content.articleType=5 OR content.categoryid IN (2,3) ) 
						AND EXISTS ( SELECT contentid FROM my_checkin checkin WHERE checkin.contentid=tags.contentid AND n_status = 1 )
					GROUP BY tags.friendid  
				) stat ON stat.friendid= entou.id
			WHERE  1 {$qUserid} {$qEntourage} {$qFilter} {$qFilterAlphabet} {$qDate}   ";	
		}
		
		$total_per_status = $this->apps->fetch($sql);		
		
		$sql = "	
		SELECT COUNT(*) total FROM my_entourage entou 
		LEFT JOIN ( 
				SELECT count(*) total,tags.id,tags.friendid
					FROM {$this->dbshema}_news_content_tags tags
					LEFT JOIN {$this->dbshema}_news_content content ON content.id = tags.contentid 
				WHERE  
					tags.n_status=1 
					AND tags.friendtype = 0  
					AND ( content.articleType=5 OR content.categoryid IN (2,3) ) 
					AND EXISTS ( SELECT contentid FROM my_checkin checkin WHERE checkin.contentid=tags.contentid AND n_status = 1 )
				GROUP BY tags.friendid  
			) stat ON stat.friendid= entou.id
		WHERE  1 {$qUserid} {$qEntourage} {$qFilter} {$qFilterAlphabet} {$qDate}  ";	
		$total = $this->apps->fetch($sql);		
		//pr($total);
		if(!$total)return  array();
		$sql = "
		SELECT entou.*, IF(stat.total IS NULL,0,stat.total) total FROM my_entourage entou 
		LEFT JOIN ( 
				SELECT count(*) total,tags.id,tags.friendid
					FROM {$this->dbshema}_news_content_tags tags
					LEFT JOIN {$this->dbshema}_news_content content ON content.id = tags.contentid 
				WHERE  
					tags.n_status=1 
					AND tags.friendtype = 0  
					AND ( content.articleType=5 OR content.categoryid IN (2,3) ) 
					AND EXISTS ( SELECT contentid FROM my_checkin checkin WHERE checkin.contentid=tags.contentid AND n_status = 1 )
				GROUP BY tags.friendid  
			) stat ON stat.friendid= entou.id
		WHERE 1 {$qUserid} {$qEntourage} {$qFilter} {$qFilterAlphabet} {$qDate}  ORDER BY entou.register_date DESC  {$qLimit} ";		
		
		$qData = $this->apps->fetch($sql,1);
		//pr($sql);
		$this->logger->log($sql);
		//pr($qData);

		
		if($qData) {
			$brandid = @$this->apps->user->branddetail;
			if($brandid){
				foreach($brandid as $val){
						$brandid = $val->ownerid;
				}
			}
			$brandcode = 0;
			if($brandid==5) $brandcode = 2;
			if($brandid==4) $brandcode = 22;
			$arrentourage = false;
			$strentourage = false;
			$entouragedata = false;
			foreach($qData as $val){
				$arrentourage[$val['id']] = $val['id'];
			}
			if($arrentourage){
				$strentourage = implode(',',$arrentourage);
				$entouragedata = $this->entouragestatistic($strentourage);
			}
			if($arrentourage){
				$strentourage = implode(',',$arrentourage);
				$latestengagement = $this->getlatestengagement($strentourage);
				// pr($latestengagement);
			}			
			//pr($competitorarr);
			//pr($pmiarr);
			foreach($qData as $key => $val){
					
					$qData[$key]['name'] =  ucwords(strtolower($qData[$key]['name'] ));
					$qData[$key]['last_name'] =  ucwords($qData[$key]['last_name']);
					
					if(is_file($CONFIG['LOCAL_PUBLIC_ASSET']."entourage/photo/small_".$val['img'])) {
						$qData[$key]['image_full_path']= $CONFIG['BASE_DOMAIN_PATH']."public_assets/entourage/photo/small_".$val['img'];
					}else  $qData[$key]['image_full_path'] =  $CONFIG['BASE_DOMAIN_PATH']."public_assets/entourage/photo/default.jpg";
					
					$qData[$key]['entouragetype'] = "Our";
					if(array_key_exists($val['Brand1_ID'],$competitorarr)) $qData[$key]['entouragetype'] = "Competitor";				
					if(array_key_exists($val['Brand1_ID'],$pmiarr)) {
						if($brandcode==$pmiarr[$val['Brand1_ID']]) $qData[$key]['entouragetype'] = "Our";
						else $qData[$key]['entouragetype'] = "PMI";
					}
					//if(array_key_exists($val['Brand1_ID'],$ourarr)) $qData[$key]['entouragetype'] = "Our";
					
					//pr($val['Brand1_ID']);
					//	pr($val['Brand1U_ID']); 				
					if(array_key_exists($val['Brand1_ID'],$competitorarr))  $qData[$key]['brandrelevancycompetitor']=1	;	
					if(array_key_exists($val['Brand1_ID'],$pmiarr)) {
						//pr($val['Brand1_ID']);
						if($brandcode==$pmiarr[$val['Brand1_ID']]) $qData[$key]['brandrelevancyour']=1;
						$qData[$key]['brandrelevancypmi']=1;
					}
					if(array_key_exists($val['Brand1U_ID'],$competitorarr))  $qData[$key]['brandrelevancycompetitor']=1	;		
					if(array_key_exists($val['Brand1U_ID'],$pmiarr)) {
						if($brandcode==$pmiarr[$val['Brand1_ID']])  $qData[$key]['brandrelevancyour']=1;
						$qData[$key]['brandrelevancypmi']=1;
					
					}
				 
					if($latestengagement){					
						if(array_key_exists($val['id'],$latestengagement))  $qData[$key]['latestengagament'] = $latestengagement[$val['id']];
						else  $qData[$key]['latestengagament'] = false;
					}else  $qData[$key]['latestengagament'] = false;
					if($entouragedata){
						if(array_key_exists($val['id'],$entouragedata))  $qData[$key]['entouragedata']= $entouragedata[$val['id']];
						else  $qData[$key]['entouragedata']= false;
					}else  $qData[$key]['entouragedata']= false;
					
				
			}
			$data['result'] = $qData;
			
			$data['total'] = $total['total'];
			//pr($data);
			$totals = intval($total['total']);
		
			if($totals>$start) $nextstart = $start;
			else $nextstart = 0;
			
					
			if($start<=0)$countstart = $limit;
			else $countstart = $limit+$nextstart;
			
			$thenextpage = intval($limit+$nextstart);
			if($totals<=$thenextpage)	$thenextpage = 0;	
			$data['pages']['nextpage'] = $thenextpage;
			$data['pages']['prevpage'] = $countstart-$limit;
			
		}
		//pr($data);
		$data['total_per_status'] = $total_per_status;
		return $data;
		// return $list;
		
		
	}
	
	function getlatestengagement($strentourage=false){
		if($strentourage==false) return false;
		global $CONFIG;
		//get enggement of entourage
		
			$sql ="
			SELECT * 
				FROM
				(
					SELECT * 
					FROM
					(
						SELECT tags.* 
						FROM {$this->dbshema}_news_content_tags tags
							LEFT JOIN {$this->dbshema}_news_content content ON content.id = tags.contentid 
						WHERE  
							tags.n_status=1 
							AND tags.friendid IN ({$strentourage})
							AND tags.friendtype = 0  
							AND ( content.articleType=5 OR content.categoryid IN (2,3) ) 
							AND EXISTS ( SELECT contentid FROM my_checkin checkin WHERE checkin.contentid=tags.contentid AND n_status = 1 )
						GROUP BY tags.friendid, DATE(tags.date) ORDER BY tags.date ASC
					) a
					GROUP BY a.friendid, DATE(a.date) ORDER BY a.date DESC
				) b
				GROUP BY b.friendid ORDER BY b.date DESC
			";	
		
			$qData = $this->apps->fetch($sql,1);
				// pr($sql); 
			$arrfid = false;
			if(!$qData) return false;
			foreach($qData as $key => $val){
					
				$contentid[$val['contentid']] = $val['contentid'];				
			}
				$contentarr = false;
			if($contentid){
		
				$strcid = implode(',',$contentid);
				$sql="
				SELECT anc.id,anc.title,anc.brief,anc.image,anc.posted_date,tpages.name pagetypes
				FROM {$this->dbshema}_news_content anc			
				LEFT JOIN my_pages pages ON anc.authorid=pages.ownerid		
				LEFT JOIN my_pages_type tpages ON tpages.id=pages.type 				
				WHERE anc.id IN ({$strcid})   ";
					// pr($sql);exit;
				$rqData = $this->apps->fetch($sql,1);
				foreach($rqData as $key => $val){
					$rqData[$key]['engagementtype'] = "Personal";
					if($val['pagetypes']=='SBA') $rqData[$key]['engagementtype'] = "Personal";
					if($val['pagetypes']=='PL') $rqData[$key]['engagementtype'] = "Co-Creation";
					if($val['pagetypes']=='Brand') $rqData[$key]['engagementtype'] = "BRAND";
					if($val['pagetypes']=='121') $rqData[$key]['engagementtype'] = "Co-Creation";
					if($val['pagetypes']=='IS') $rqData[$key]['engagementtype'] = "BRAND";
					
					if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))   $rqData[$key]['image_full_path'] = $CONFIG['BASE_DOMAIN_PATH']."public_assets/article/".$val['image'];
					else $rqData[$key]['image_full_path'] = $CONFIG['BASE_DOMAIN_PATH']."public_assets/article/default.jpg";	
					
					$contentarr[$val['id']] = $rqData[$key];				
				}
			}
			if(!$qData) return false;
			foreach($qData as $key => $val){						
				$qData[$key]['engagementtype'] = "Personal";
				if($contentarr){			
				
						if(array_key_exists($val['contentid'],$contentarr))  {
							$qData[$key]['contentdetail'] = $contentarr[$val['contentid']];
							$qData[$key]['engagementtype'] =  $contentarr[$val['contentid']]['engagementtype'];
						}else  $qData[$key]['contentdetail'] = false;
				}	
				
				$arrfid[$val['friendid']] = $qData[$key];	
			}
			
			
			// pr($arrfid);exit;
			return $arrfid;
	}
	
	function entourageDetail(){
		global $CONFIG;
		$id=intval($this->apps->_g('id'));
		
		$sql = "SELECT * FROM my_entourage WHERE id={$id} LIMIT 1 ";		
		$qData = $this->apps->fetch($sql);
		
		// pr($qData);
		
		if($qData)	{
			if(is_file( $CONFIG['LOCAL_PUBLIC_ASSET']."entourage/photo/".$qData['img'])) $qData['image_full_path']= $CONFIG['BASE_DOMAIN_PATH']."public_assets/entourage/photo/".$qData['img'];
			else $qData['image_full_path']=  $CONFIG['BASE_DOMAIN_PATH']."public_assets/entourage/photo/default.jpg";
			
			$qData['entouragedata'] = $this->entouragestatistic($qData['id']);
			
			 
		}
		// pr($qData);
		
		if($qData) 	return $qData;
		return false;
		
		
	}
	
	function entourageProfile(){
		global $CONFIG;
		$id=intval($this->apps->_request('id'));
		$uid = intval($this->uid);
		
		$sql = "
		SELECT  entou.*, IF(stat.total IS NULL,0,stat.total) total 
		FROM my_entourage entou 
		LEFT JOIN 
			( 
				SELECT count(*) total,tags.id,tags.friendid
					FROM {$this->dbshema}_news_content_tags tags
					LEFT JOIN {$this->dbshema}_news_content content ON content.id = tags.contentid 
				WHERE  
					tags.n_status=1 
					AND tags.friendtype = 0  
					AND ( content.articleType=5 OR content.categoryid IN (2,3) ) 
					AND EXISTS ( SELECT contentid FROM my_checkin checkin WHERE checkin.contentid=tags.contentid AND n_status = 1 )
				GROUP BY tags.friendid  
			) stat ON stat.friendid= entou.id
		WHERE  entou.id={$id} LIMIT 1 ";		
		$qData = $this->apps->fetch($sql);
		
		// pr($qData);
		
		if($qData)	{
			$qData['BRAND_NAME'] = false;	 
			/**
			$sql = "
			SELECT subbrandname 
			FROM tbl_brand_preferences_references 
			WHERE preferenceid IN ('{$qData['Brand1_ID']}','{$qData['Brand1U_ID']}') GROUP BY preferenceid LIMIT 1";
			**/
			
			$sql = "
                        SELECT subbrandname 
                        FROM tbl_brand_preferences_references 
                        WHERE preferenceid IN ('{$qData['Brand1_ID']}') GROUP BY preferenceid LIMIT 1";

			$rs = $this->apps->fetch($sql,1);
			if($rs){
				foreach($rs as $val){
						$qData['BRAND_NAME'][]=$val['subbrandname'];
				}
			}
			$sql = "
			SELECT subbrandname 
			FROM tbl_brand_preferences_references 
			WHERE preferenceid IN ( '{$qData['Brand1U_ID']}') GROUP BY preferenceid LIMIT 1";
			$rs = $this->apps->fetch($sql,1);
			if($rs){
				foreach($rs as $val){
						$qData['BRAND_NAME'][]=$val['subbrandname'];
				}
			} 			
			if(is_file( $CONFIG['LOCAL_PUBLIC_ASSET']."entourage/photo/small_".$qData['img'])) $qData['image_full_path']= $CONFIG['BASE_DOMAIN_PATH']."public_assets/entourage/photo/small_".$qData['img'];
			else $qData['image_full_path']=  $CONFIG['BASE_DOMAIN_PATH']."public_assets/entourage/photo/default.jpg";
			
			$qData['entouragedata'] = $this->entouragestatistic($qData['id']);
			$qData['latestengagament'] = $this->getlatestengagement($qData['id']);
			 
		}
		// pr($qData);
		
		if($qData) 	return $qData;
		return false;
		
		
	}
	
	function addEntourage($img=false,$signature=false,$signatureba=false){
		
		// if($this->apps->user->leaderdetail->type!=1) return false;
		
		$this->logger->log(" update or register phase 1 : ");
		$firstname=strip_tags($this->apps->_request("name"));
		$lastname=strip_tags($this->apps->_request("lastname"));
		$nickname=strip_tags($this->apps->_request("nickname"));
		$email=strip_tags($this->apps->_request("email"));
	
		$city=intval($this->apps->_request("city"));
		$state=strip_tags($this->apps->_request("state"));
		$giidnumber=strip_tags($this->apps->_request("giidnumber"));
		$giidtype=strip_tags($this->apps->_request("giidtype"));
			if($giidtype==1) $giidtype = "K";
		if($giidtype==2) $giidtype = "S";
		$companymobile=strip_tags($this->apps->_request("companymobile"));
		$phone_number=strip_tags($this->apps->_request("phone_number"));
		$sex=strip_tags($this->apps->_request("sex"));
		$birthday=strip_tags($this->apps->_request("birthday"));
		$description=strip_tags($this->apps->_request("description"));
		$StreetName=strip_tags($this->apps->_request("StreetName"));
		if($companymobile!='ST1')$phone_number=$companymobile;		
	
		$brand1=strip_tags($this->apps->_request("Brand1_ID"));
		if($brand1=='') {
				$brand1 = "63";
				 
		}
		// $brand1 = "0004";
		$brandsub1=strip_tags($this->apps->_request("Brand1SUB_ID"));
		if($brandsub1==''){
			$brandsub1 = "63"; 
		 		
		}
		
		$socialaccount=strip_tags($this->apps->_request("socialaccount"));
		$socialaccount_sub=strip_tags($this->apps->_request("socialaccount_sub"));
		if($birthday!=''){
			$this->logger->log($birthday);
			$birthdayarr = explode('/',$birthday);
			if(is_array($birthdayarr)&&(count($birthdayarr)==3)){
				$weekslen = strlen($birthdayarr[1]);
				
				if($weekslen<2) $birthdayarr[1] = "0".$birthdayarr[1];
				$datelen = strlen($birthdayarr[0]);
				if($datelen<2) $birthdayarr[0] = "0".$birthdayarr[0];
				
				$newbirth= "{$birthdayarr[2]}-{$birthdayarr[0]}-{$birthdayarr[1]}";
				if($newbirth!='')$birthday = $newbirth;
			}
		
		}
		
		
		$updatedatavalidation=false;
		
		if($phone_number!='') $updatedatavalidation[] = "phone_number='{$phone_number}'";
		if($sex!='') $updatedatavalidation[] = "sex='{$sex}'";
		if($birthday!='') $updatedatavalidation[] = "birthday='{$birthday}'";
		if($brand1!='') $updatedatavalidation[] = "Brand1_ID='{$brand1}'";
		if($brandsub1!='') $updatedatavalidation[] = "Brand1U_ID='{$brandsub1}'";
		if($giidnumber!='') $updatedatavalidation[] = "giidnumber='{$giidnumber}'";
		if($giidtype!='') $updatedatavalidation[] = "giidtype='{$giidtype}'";
		if($socialaccount!='') $updatedatavalidation[] = "socialaccount='{$socialaccount}'";
		if($firstname!='') $updatedatavalidation[] = "name='{$firstname}'";
		if($nickname!='') $updatedatavalidation[] = "nickname='{$nickname}'";
		if($lastname!='') $updatedatavalidation[] = "last_name='{$lastname}'";
		if($img) $updatedatavalidation[] = "img='{$img}'";
		if($signature) $updatedatavalidation[] = "signature='{$signature}'";
		if($signatureba) $updatedatavalidation[] = "signatureba='{$signatureba}'";
		
		
		if($updatedatavalidation){
			$qInsertOnUpdateVerified = implode(',',$updatedatavalidation);
		}

		$usertype=intval(@$this->apps->session->getSession($this->config['SESSION_NAME'],'USERTYPE')->users);
		
		$referrerbybrand = intval($this->uid); /* use on segment 8  */
		if($referrerbybrand==0) return false;
		if(!$email) return false;
		if(!$giidnumber) return false;
		
		$confirm18=1;
		$receiveinfo=1;
		$n_status=0;
		$verified = 1;
		// pr($img);exit;
		
		$sql = " SELECT MAX(version) version FROM my_entourage LIMIT 1";
		
		$latestversion = $this->apps->fetch($sql);
		if($latestversion) $versionlatest = intval($latestversion['version']);
		else $versionlatest = 0;
		
		$sql ="
		INSERT INTO my_entourage 
		(registerid ,name ,	nickname ,	email ,	register_date ,	img ,	small_img ,	city 	,sex ,	birthday ,	description, 	last_name ,	StreetName, 	phone_number ,	n_status ,	Brand1_ID ,	referrerbybrand ,verified, usertype,giidnumber,giidtype,socialaccount,socialaccount_sub,Brand1U_ID,version,signature,signatureba) 
		VALUES
		('',\"{$firstname}\",\"{$nickname}\",\"{$email}\",NOW(),\"{$img}\",\"{$img}\",\"{$city}\",\"{$sex}\",\"{$birthday}\",\"{$description}\",\"{$lastname}\",\"{$StreetName}\",\"{$phone_number}\",{$n_status},\"{$brand1}\",{$referrerbybrand},{$verified},{$usertype},\"{$giidnumber}\",\"{$giidtype}\",\"{$socialaccount}\",\"{$socialaccount_sub}\",\"{$brandsub1}\",{$versionlatest},\"{$signature}\",\"{$signatureba}\")	
		ON DUPLICATE KEY UPDATE {$qInsertOnUpdateVerified} , version={$versionlatest}+1
		";
		
		// pr($sql);exit;
		$this->logger->log($sql);
		// $this->logger->log($qInsertOnUpdateVerified);
		$qData = $this->apps->query($sql);
		$data['result'] = false;
		$entourageid = false;
		if($this->apps->getLastInsertId())	$entourageid = $this->apps->getLastInsertId();
		
		$sql =" SELECT * FROM my_entourage WHERE email=\"{$email}\" LIMIT 1";
		// $this->logger->log($sql);
		$entorourage = $this->apps->fetch($sql);
		
		if($entorourage){
			if(!$entourageid)$entourageid = $entorourage['id'];
			$referrerbybrand = $entorourage['referrerbybrand'];
		}else return false;
		
				
		$data['result'] = true;
		$data['savedb'] = true;
		$data['savefriends'] = false;
		$data['savemop'] = false;
		
	
			
		
			$mop = $this->apps->deviceMopHelper->syncAdminUserRegistrant("AdminRegisterProfileDeDuplication");
			// $mop['result'] = 0;
			// pr($mop);
			$this->logger->log(json_encode($mop));
			if($mop['result']==1) {
				$sql = "UPDATE my_entourage SET registerid='{$mop['data'][0]['RegistrationID']}',n_status=1 WHERE id={$entourageid} LIMIT 1 ";
				$qData = $this->apps->query($sql);
				if($qData) $data['savemop'] = true;
			
			}else{
				if($mop['result']==99) {
					/* dont update status */
				}else{
					$sql = "UPDATE my_entourage SET n_status=2 WHERE id={$entourageid} LIMIT 1 ";
					$qData = $this->apps->query($sql);
					$data['savemop'] = false;	
				}
			}
			
			if(!$referrerbybrand){
				$sql =" SELECT * FROM my_entourage WHERE email=\"{$email}\" LIMIT 1";
				// $this->logger->log($sql);
				$getentourage = $this->apps->fetch($sql);
				
				if($getentourage){
				 
					$referrerbybrand = $getentourage['referrerbybrand'];
				}
			}
			
			$this->logger->log("send data to circle : {$this->apps->user->id} - {$referrerbybrand}");
			if($this->apps->user->id!=$referrerbybrand){
				$sql = "
				INSERT INTO my_circle (friendid,userid,ftype,groupid,date_time,n_status)
				VALUES ('{$entourageid}','{$this->apps->user->id}',0,0,NOW(),1)
				ON DUPLICATE KEY UPDATE n_status=1
				";
			
				$this->apps->query($sql);
				$this->logger->log($sql);
				if($this->apps->getLastInsertId()) 	$data['savefriends'] = true;
			}
				
				// insert brand tracker
				$sql =" 
				INSERT INTO tbl_brand_questioner 
				( userid,brandid,brandsubid ,entourageid,datetimes,n_status) 
				VALUES 
				({$this->uid},{$brand1},{$brandsub1} ,{$entourageid},NOW(),1)
				";
				$this->apps->query($sql);
			
			// pr($data);
			return $data;	
		
				
	}
	
	function synchenturage(){
		
			$this->logger->log("sync entourage : ");
		$sql = "SELECT * FROM my_entourage WHERE n_status = 0 ORDER BY register_date DESC LIMIT 1 ";
		$val = $this->apps->fetch($sql);
		
			$this->logger->log($sql);
		if(!$val) 
		{
		pr('no data');
		exit;
		}
		
		// pr($val);exit;
		
			$this->apps->Request->setParamPost("name",$val['name']);
			$this->apps->Request->setParamPost("lastname",$val['last_name']);
			$this->apps->Request->setParamPost("nickname",$val['nickname']);
			$this->apps->Request->setParamPost("email",$val['email']);
			
			
			$sql = "SELECT * FROM beat_city_reference WHERE cityidmop='{$val['city']}' LIMIT 1";
			$city = $this->apps->fetch($sql);		
			
			$this->apps->Request->setParamPost("state",$city['provinceid']);
			$this->apps->Request->setParamPost("city",$city['cityidmop']);
			$this->apps->Request->setParamPost("giidnumber",$val['giidnumber']);
			$this->apps->Request->setParamPost("giidtype",$val['giidtype']);
			$this->apps->Request->setParamPost("sex",$val['sex']);
			$this->apps->Request->setParamPost("birthday",$val['birthday']);
			$this->apps->Request->setParamPost("phone_number",$val['phone_number']);
			$this->apps->Request->setParamPost("Brand1_ID",$val['Brand1_ID']);
			$this->apps->Request->setParamPost("Brand1SUB_ID",$val['Brand1U_ID']);
			$this->apps->Request->setParamPost("referrerbybrand",$val['referrerbybrand']);
			$this->apps->Request->setParamPost("companymobile","ST1");
						
			// pr($this->apps->_request("giidtype"));exit;
				
			$mop = $this->apps->deviceMopHelper->syncAdminUserRegistrant("AdminRegisterProfileDeDuplication",true);
			
			// pr($mop);exit;
			if($mop['result']==1) {
				$sql = "UPDATE my_entourage SET registerid='{$mop['data'][0]['RegistrationID']}',n_status=1 WHERE id={$val['id']} LIMIT 1 ";
				$qData = $this->apps->query($sql);
				if($qData) $data['savemop'] = true;		
			}else{
				if($mop['result']==99) {
					/* dont update status */
				}else{
					$sql = "UPDATE my_entourage SET n_status=2 WHERE id={$val['id']} LIMIT 1 ";
					$qData = $this->apps->query($sql);
					$data['savemop'] = false;	
				}
			}
			
			
			$sql = "
			INSERT INTO my_circle (friendid,userid,ftype,groupid,date_time,n_status)
			VALUES ('{$val['id']}','{$val['referrerbybrand']}',0,0,NOW(),1)
			ON DUPLICATE KEY UPDATE n_status=1
			";
		
			$this->apps->query($sql);
			 
		pr($mop);exit;
	}
	
	
	function getSearchEntourage(){
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
	
	
	function entouragestatistic($strentourage=null){
	
		// pr($this->apps->user);exit;
			if($strentourage==null) return false;
			global $CONFIG;
				
			//get enggement of entourage
			$sql ="
			SELECT *
				FROM
				(
					SELECT tags.*
						FROM {$this->dbshema}_news_content_tags tags
						LEFT JOIN {$this->dbshema}_news_content content ON content.id = tags.contentid 
						WHERE  
							tags.n_status=1 
							AND tags.friendid IN ({$strentourage})
							AND tags.friendtype = 0  
							AND ( content.articleType=5 OR content.categoryid IN (2,3) ) 
							AND EXISTS ( SELECT contentid FROM my_checkin checkin WHERE checkin.contentid=tags.contentid AND n_status = 1  )
						GROUP BY tags.friendid , DATE(tags.date) ORDER BY tags.date ASC
					) a
				GROUP BY a.friendid, DATE(a.date) ORDER BY a.date DESC 
			";	
			$rqData = $this->apps->fetch($sql,1);
			$this->logger->log(" tags search : ".$sql);
			$strcid = false;
			// pr($rqData);
			if(!$rqData) return false;
				$arrfid = false;
			foreach($rqData as $val){
				$arrcid[$val['contentid']] = $val['contentid'];
			}
			if($arrcid) $strcid = implode(',',$arrcid);
			
			//get contentid detail
			$sql="
			SELECT id,title,brief,image,thumbnail_image,slider_image,posted_date,file,url,fromwho,tags,authorid,topcontent,cityid ,articleType,can_save
			FROM {$this->dbshema}_news_content anc
			WHERE id IN ({$strcid})";
			// pr($sql);
			$qData = $this->apps->fetch($sql,1);
			$this->logger->log(" content search : ".$sql);
			if(!$qData) return false;
			
			foreach($qData as $key => $val){
				$qData[$key]['imagepath'] = false;
				
				
				
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}event/{$val['image']}")) 	$qData[$key]['imagepath'] = "event";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}banner/{$val['image']}")) 	$qData[$key]['imagepath'] = "banner";
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}"))  	$qData[$key]['imagepath'] = "article";					
				
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}article/{$val['image']}")) 	$qData[$key]['banner'] = false;
				else $qData[$key]['banner'] = true;
								
				//CHECK FILE SMALL
				if(is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}{$qData[$key]['imagepath']}/small_{$val['image']}")) $qData[$key]['image'] = "small_{$val['image']}";
				
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
					$qData[$key]['video_thumbnail'] = $video_thumbnail;
				}else $qData[$key]['video_thumbnail'] = false;
				
				if($qData[$key]['imagepath']) $qData[$key]['image_full_path'] = $CONFIG['BASE_DOMAIN_PATH']."public_assets/".$qData[$key]['imagepath']."/".$qData[$key]['image'];
				else $qData[$key]['image_full_path'] = $CONFIG['BASE_DOMAIN_PATH']."public_assets/article/default.jpg";
				$contentdata[$val['id']] =  $qData[$key];
				
			}
			
			
			foreach($rqData as $key => $val){
				$arrfid[$val['friendid']][$key] = $val;
				if(array_key_exists($val['contentid'],$contentdata)) $arrfid[$val['friendid']][$key]['contentdetail'] = $contentdata[$val['contentid']];
				else  $arrfid[$val['friendid']][$key]['contentdetail']  = false;
			}
			if($arrfid) return $arrfid;
			
			return false;
	
			
		// i need check how many entourage of this BA
		// check how many times the entourage has engagement
	}
	
	function checkentourage(){
		global $CONFIG;
		$email= strip_tags($this->apps->_request('email'));
		$giid= strip_tags($this->apps->_request('giidnumber'));
		$filter = false;
		
		if($email) $filter[] = " email =\"{$email}\" ";
		if($giid) $filter[] = " giidnumber = \"{$giid}\" ";
		
		if($filter) $qFilter =	implode(" AND ",$filter);
		else $qFilter="";
		
		if($qFilter=="") return false;
		
		$sql = "SELECT * FROM my_entourage WHERE {$qFilter} LIMIT 1 ";		
				// pr($sql);
		$qData = $this->apps->fetch($sql);
		if($qData)	{
			$sql = "SELECT * FROM beat_city_reference WHERE cityidmop='{$qData['city']}' LIMIT 1";
			$city = $this->apps->fetch($sql);		
			
			$qData["state"] = $city['provinceid'];
			$qData["city"] = $city['cityidmop']; 
			$qData['sex'] =  substr($qData['sex'],0,1);
			
			$brand1=strip_tags($qData["Brand1_ID"]);
			if($brand1=='') {
				$brand1 = "63"; 
			}
			// $brand1 = "0004";
			$brandsub1=strip_tags($qData["Brand1U_ID"]);
			if($brandsub1==''){
				$brandsub1 = "63";  		
			}
			
			
			$sql = "SELECT id,code,preference,preferenceid,others FROM tbl_brand_preferences_references WHERE preferenceid IN ('{$brand1}','{$brandsub1}') GROUP BY preferenceid LIMIT 2";
			$rs = $this->apps->fetch($sql,1);		
			// pr($rs);
			$qData['brand1ref']= "63";
			$qData['brand1']= "31";	
			$qData['brandsub1ref']= "63";
			$qData['brandsub1']= "31";					
			if($rs){
				$brandarr = false;
				foreach($rs as $val){
					if($val['others']==1){
						$brandarr[$val['preferenceid']]['brand'] = "31";
						$brandarr[$val['preferenceid']]['preference'] = "63";
					}else{
						$brandarr[$val['preferenceid']]['brand'] = $val['id'];
						$brandarr[$val['preferenceid']]['preference'] = $val['preferenceid'];
					}
				}
			 		// pr($brandarr);
				if($brandarr){ 
					if(array_key_exists($brand1,$brandarr)){
						$qData['brand1ref']=$brandarr[$brand1]['preference'];
						$qData['brand1']=$brandarr[$brand1]['brand'];
					}  
					if(array_key_exists($brandsub1,$brandarr)){
						$qData['brandsub1ref']=$brandarr[$brandsub1]['preference'];
						$qData['brandsub1']=$brandarr[$brandsub1]['brand'];
					} 
				}
			}

				// pr($qData);
			
			if(is_file( $CONFIG['LOCAL_PUBLIC_ASSET']."entourage/photo/small_".$qData['img'])) $qData['image_full_path']= $CONFIG['BASE_DOMAIN_PATH']."public_assets/entourage/photo/small_".$qData['img'];
			else $qData['image_full_path']=  $CONFIG['BASE_DOMAIN_PATH']."public_assets/entourage/photo/default.jpg";
			
			// $qData['entouragedata'] = $this->entouragestatistic($qData['id']);
		}
	
		
		if($qData) 	return array('result'=>true,'data'=>$qData);
		return array('result'=>false,'data'=>false);
	}
	
	function getAge($birthDate){
		
         $birthDate = explode("-", $birthDate);
         $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1], $birthDate[0]))) > date("md") ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));
        return $age;
	}
	
	
	function changephoto($img=false){
		
		if($this->apps->user->leaderdetail->type!=1) return false;
		
		if($img){
			$email=strip_tags($this->apps->_request("email"));
		 
			$sql ="
				UPDATE my_entourage SET img='{$img}',version=version+1 WHERE email=\"{$email}\" LIMIT 1
			";
			
			$this->logger->log($sql);
	 
			$qData = $this->apps->query($sql);
			if($qData) return true;
		}
		return false;	
		
				
	}
	
	
		
	function brandPref(){
		
		$sql = "SELECT code,brandtype FROM tbl_brand_preferences_references ";
		$qData = $this->apps->fetch($sql,1);
		if(!$qData)return false;
		$competitorarr = array();
		$pmiarr = array();
		$ourarr = array();
		foreach($qData as $val){
			if($val['brandtype']==0) $competitorarr[(string)$val['code']] =(string)$val['code'];
			if($val['brandtype']==1) $pmiarr[(string)$val['code']] =(string)$val['code'];
			if($val['brandtype']==2) $ourarr[(string)$val['code']] =(string)$val['code'];
		}
		
		$sql = "SELECT COUNT( * ) total, me.Brand1_ID
				FROM my_entourage me
				LEFT JOIN social_member sm ON me.referrerbybrand = sm.id
				LEFT JOIN my_pages pages ON pages.ownerid = me.referrerbybrand 
				WHERE me.n_status
				IN ( 1, 2 )
				AND sm.n_status =1 
				{$this->qSbaFilter}
				{$this->qAreaFilter}
				{$this->qBrandFilter}						 
				GROUP BY me.Brand1_ID
				ORDER BY total";
		$qData = $this->apps->fetch($sql,1);
		// pr($sql);
		if(!$qData) return false;
		
		foreach($qData as $key => $val){
				$qData[$key]['brandname'] = "Our";
				if(in_array($val['Brand1_ID'],$competitorarr)) $qData[$key]['brandname'] = "Competitor";				
				if(in_array($val['Brand1_ID'],$pmiarr)) $qData[$key]['brandname'] = "PMI";
				if(in_array($val['Brand1_ID'],$ourarr)) $qData[$key]['brandname'] = "Our";
					
		}
		$data['Our'] = 0;
		$data['Competitor'] = 0;
		$data['PMI'] = 0;
		
		foreach($qData as $key => $val){
				if($qData[$key]['brandname']=='Our') $data[$qData[$key]['brandname']]+=$val['total'];
				if($qData[$key]['brandname']=='Competitor')$data[$qData[$key]['brandname']]+=$val['total'];
				if($qData[$key]['brandname']=='PMI')$data[$qData[$key]['brandname']]+=$val['total'];
		}
		// pr($data);
		return $data;
	
	}
	
	function genderPref(){
	
		$sql = "SELECT COUNT( * ) num, me.sex, DATE(me.register_date) dd
				FROM my_entourage me 
				LEFT JOIN social_member sm ON me.referrerbybrand = sm.id
				LEFT JOIN my_pages pages ON pages.ownerid = me.referrerbybrand 
				WHERE me.n_status IN ( 1, 2 )
				AND sm.n_status =1 AND me.sex NOT LIKE 'M' AND me.sex NOT LIKE 'F'
				{$this->qSbaFilter}
				{$this->qAreaFilter}
				{$this->qBrandFilter}	 
				GROUP BY me.sex
				ORDER BY num";
				// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		
		foreach($qData as $val){
			
			$data[$val['sex']] = $val['num'];
		}
		// pr($qData);
		return $qData;
	
	}
	
	function agePref(){
		 
		$sql = "
				SELECT count( * ) num, DATE_FORMAT( me.register_date, '%Y-%m-%d' ) datetime, me.sex, YEAR(
				CURRENT_TIMESTAMP ) - YEAR( me.birthday ) - ( RIGHT(
				CURRENT_TIMESTAMP , 5 ) < RIGHT( me.birthday, 5 ) ) AS age
				FROM my_entourage me
				LEFT JOIN social_member sm ON me.referrerbybrand = sm.id
				LEFT JOIN my_pages pages ON pages.ownerid = me.referrerbybrand 
				WHERE me.n_status IN ( 1, 2 )
				AND me.register_date <> '0000-00-00'
				AND me.register_date IS NOT NULL 
				{$this->qSbaFilter}
				{$this->qAreaFilter}
				{$this->qBrandFilter}
				GROUP BY age
				HAVING age <> '2013'
				AND age >= 0
				ORDER BY age ASC";
				// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		$data = false;
		$data['18 - 24']= 0;
		$data['25 - 29']= 0;
		$data['30 - above']= 0;
		foreach( $qData as $val ){
			if($val['age']==null ) $data['null']+= $val['num'];
			else{
			if($val['age']<=24 ) $data['18 - 24'] += $val['num']; 
			if($val['age']>=25 && $val['age']<=29 ) $data['25 - 29'] += $val['num'];
			if($val['age']>=30 ) $data['30 - above']+= $val['num'];
			}
			
		}		
		 
		return $data;	
	}
	
	function getallpmientourage(){
		
	 
		$data['Competitor'] = 0;
		$data['PMI'] = 0;
		
		$sql = "SELECT preferenceid,brandtype FROM tbl_brand_preferences_references ";
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return $data;
		$competitorarr = array();
		$pmiarr = array();
		 
		foreach($qData as $val){
			if($val['brandtype']==0) $competitorarr[(string)$val['preferenceid']] =(string)$val['preferenceid'];
			else $pmiarr[(string)$val['preferenceid']] =(string)$val['preferenceid'];
		 
		}
		
		
		$filter = strip_tags($this->apps->_p('filter'));
	  
		$qFilter =" me.n_status	IN ( 0,1,2 )	";
		if($filter=="pending") $qFilter = "   me.n_status = 0 ";
		if($filter=="accept") $qFilter = "   me.n_status = 1 ";
		if($filter=="reject") $qFilter = "   me.n_status = 2 ";
		
		 
		$sql = "SELECT COUNT( * ) total, me.Brand1_ID
				FROM my_entourage me 
				WHERE {$qFilter} 	 
				GROUP BY me.Brand1_ID
				ORDER BY total";
		$qData = $this->apps->fetch($sql,1);
		// pr($sql);
		if(!$qData) return $data;
		
		foreach($qData as $key => $val){
				$qData[$key]['brandname'] = "PMI";
				if(in_array($val['Brand1_ID'],$competitorarr)) $qData[$key]['brandname'] = "Competitor";				
				if(in_array($val['Brand1_ID'],$pmiarr)) $qData[$key]['brandname'] = "PMI";
				 
					
		}
		
		
		foreach($qData as $key => $val){
				 
				if($qData[$key]['brandname']=='Competitor')$data[$qData[$key]['brandname']]+=$val['total'];
				if($qData[$key]['brandname']=='PMI')$data[$qData[$key]['brandname']]+=$val['total'];
		}
		// pr($data);
		return $data;
	}
	
	
	function brandpreflists(){
		$Brand_ID = (string)$this->apps->_p('Brand_ID');
		if($Brand_ID){
			$qPrefBrand = " AND id  = '{$Brand_ID}'" ;
			$qGroupBy = "  " ;
			$qSelected = " id Brand_ID,preferenceid SubBrand_ID,brand_name Brand_Name, subbrandname SubBrand_Name  " ; 
		}else{
			$qPrefBrand = "  " ;
			$qGroupBy = "  GROUP BY id " ;
			$qSelected = " id Brand_ID, brand_name Brand_Name " ; 
			/* kevin request di buka semua */
			$qGroupBy = "  " ;
			$qSelected = " *  " ; 
		}
		$sql = "
		SELECT {$qSelected} 
		FROM tbl_brand_preferences_references 
		WHERE 1 {$qPrefBrand} {$qGroupBy} ";
		$rs = $this->apps->fetch($sql,1);
		if($rs) return $rs;
		else return array();
	}
	
	function citylists(){
			
			$provinces = (string)$this->apps->_p('Province_ID');
			if($provinces){
				$qPrefBrand = "  AND provinceid='{$provinces}' " ;
				$qGroupBy = "  " ;
				$qSelected = " cityidmop City_ID,city City_Name" ; 
			}else{
				$qPrefBrand = "  " ;
				$qGroupBy = "  GROUP BY provinceid " ;
				$qSelected = " provinceName, provinceid Province_ID  " ; 
				/* kevin request di buka semua */
				$qGroupBy = "  " ;
				$qSelected = " *  " ; 
			}
			
			$sql = "SELECT {$qSelected} FROM beat_city_reference WHERE 1  {$qPrefBrand} {$qGroupBy}  ";
			$rs = $this->apps->fetch($sql,1);	
			if($rs) return $rs;
			else return array();			
	}
	
}

?>

