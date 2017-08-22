<?php

class uActivitiesHelper {

	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);

		$this->dbshema = "marlborohunt";	
		$this->dateLetterDistribution = "2013-07-01";
		$this->startdate = $this->apps->_g('startdate');
		$this->enddate = $this->apps->_g('enddate');	
		if($this->enddate=='') $this->enddate = date('Y-m-d');		
		if($this->startdate=='') $this->startdate = date('Y-m-d' ,  strtotime( '-7 day' ,strtotime($this->enddate)) );
	}
	
/* 	function fixeddate($qData=false,$datetimesformat = 'datetime'){
		if(!$qData) return false;
		
		$startdate = strip_tags($this->apps->_g('startdate'));
		$enddate = strip_tags($this->apps->_g('enddate'));
		$mindate = 0;
		$maxdate = 0;
		if($startdate){
			if(!$enddate) if($startdate)  $enddate = date("Y-m-d",strtotime($startdate. "+7 day"));
			$mindate = strtotime($startdate);
			$maxdate = strtotime($enddate);
		}
		$totaldate = ($maxdate - $mindate) / (60*60*24);
		
		
		for($i=0;$i<=$totaldate;$i++){
		// pr($totaldate);
			$dates = date("Y-m-d",$mindate);
			$val = date("Y-m-d" , strtotime("{$dates} +{$i} day"));
			// pr($val);
			foreach($qData as $key => $valve) {					
				if(array_key_exists($val,$qData[$key][$datetimesformat])) $qData[$key][$datetimesformat] = $qData[$key][$datetimesformat];
				else  $qData[$key][$datetimesformat] = 0;
			}
			
		}
		return $qData;
	} */

	function numberofLogin(){
		
		$sql = "SELECT count(*) num, DATE(date_time) datetime FROM tbl_activity_log 
				WHERE action_id = 1 
				AND DATE(date_time) >= '{$this->startdate}' 
				AND DATE(date_time) <= '{$this->enddate}'
				GROUP BY datetime 
				ORDER BY datetime ASC";
				
		// $this->apps->open(1);
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		/* 
		if($qData) $data = $this->fixeddate($qData,'datetime');
		else $data = $qData; */
		
		return $qData;
	}
	
	function loginHistory(){
		
		/*
		$sql = "SELECT COUNT( * ) num, log.user_id, DATE(log.date_time) date_time
				FROM tbl_activity_log log
				LEFT JOIN tbl_activity_actions act ON log.action_id = act.id
				WHERE log.action_id = 1 AND date_time >= '{$this->startdate}'
				AND date_time <= '{$this->enddate}'
				GROUP BY log.user_id
				ORDER BY date_time DESC LIMIT 10";
				
				SELECT MAX(logintime) totallogintime ,dd FROM
				(
				SELECT  max(date_ts) - min(date_ts) logintime ,DATE(date_time) dd,user_id FROM `tbl_activity_log` WHERE action_id = 1
				GROUP BY dd,user_id
				 ) a
				GROUP BY dd
				
		*/
		$typeoftime = strip_tags($this->apps->_p('typeoftime'));
		$qTimes = " ROUND( MAX(logintime)/ (60*60)) num ";
		if($typeoftime=='hour') $qTimes = "  MAX(logintime)/ (60*60*24)  num  ";
		if($typeoftime=='minute')$qTimes = "ROUND( MAX(logintime)/ (60*60))  num  ";
		$sql = "
				SELECT {$qTimes} ,dd date_time FROM
				(
				SELECT  max(date_ts) - min(date_ts) logintime ,DATE(date_time) dd,user_id FROM `tbl_activity_log` 
				WHERE action_id = 1
				AND DATE(date_time) >= '{$this->startdate}'
				AND DATE(date_time) <= '{$this->enddate}'
				GROUP BY dd,user_id
				 ) a
				
				GROUP BY date_time ASC 
		";
		// pr($sql);
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		return $qData;
				
	}
	
	function numberofRegistrant(){
	
		$sql = "SELECT count( * ) num, DATE_FORMAT( register_date, '%Y-%m-%d' ) datetime, sex, YEAR(
				CURRENT_TIMESTAMP ) - YEAR( birthday ) - ( RIGHT(
				CURRENT_TIMESTAMP , 5 ) < RIGHT( birthday, 5 ) ) AS age
				FROM social_member
				GROUP BY age
				ORDER BY num ASC";
		// $this->apps->open(1);
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
	
	function numberofExistingUser(){
		
		
		$sql = "SELECT count( * ) num, DATE_FORMAT( register_date, '%Y-%m-%d' ) datetime, sex, YEAR(
				CURRENT_TIMESTAMP ) - YEAR( birthday ) - ( RIGHT(
				CURRENT_TIMESTAMP , 5 ) < RIGHT( birthday, 5 ) ) AS age
				FROM social_member WHERE id IN (
					SELECT DISTINCT (userid) FROM tbl_code_inventory WHERE 1)
				AND usertype = 2 
				GROUP BY age
				ORDER BY datetime ASC";
				
				
		// $this->apps->open(1);
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
	
	function numberofNewUser(){
	
		$sql = "SELECT count( * ) num, DATE_FORMAT( register_date, '%Y-%m-%d' ) datetime, sex, YEAR(
				CURRENT_TIMESTAMP ) - YEAR( birthday ) - ( RIGHT(
				CURRENT_TIMESTAMP , 5 ) < RIGHT( birthday, 5 ) ) AS age
				FROM social_member WHERE id IN (
					SELECT DISTINCT (userid) FROM tbl_code_inventory WHERE 1
				)
				AND usertype in (0,1)
				GROUP BY age
				ORDER BY datetime ASC";
		// $this->apps->open(1);	
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
	
	function maleFemaleUser(){
	
		//first query//
		/* $sql = "SELECT count( * ) num, DATE( register_date  ) datetime, sex
				FROM social_member WHERE				
				sex <> '' AND sex IS NOT NULL
				GROUP BY sex	ORDER BY datetime DESC"; */
				
		//rev query
		$sql = "SELECT count( * ) num, DATE( register_date ) datetime, sex
				FROM social_member WHERE n_status = 1 AND sex <> '' 
				AND sex IS NOT NULL GROUP BY sex ORDER BY datetime ASC";
				// pr($sql);
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		// pr($qData);
		if(!$qData) return false;
		$data = false;
		$data['count'] = 0;
		$data['jumlah_female'] = false;
		$data['jumlah_male'] = false;
		$data['unknown'] = false;
		$data['jumlah'] = false;
		foreach($qData as $val){
			$data['data'][$val['datetime']] = $val['num'];
			if(strtolower($val['sex'])=="female") $data['jumlah_female']+= $val['num'];
			if(strtolower($val['sex'])=="male") $data['jumlah_male']+= $val['num'];			
			if($val['sex']!="Male"&&$val['sex']!="Female") $data['unknown']+= $val['num'];			
			
			$data['date'][$val['datetime']] = $val['datetime'];
			
			$data['jumlah']+= $val['num'];		
			
		}
		// pr($data);
		$data['count'] = count($qData);
		return $data;
	}
	
	function mostLetterCollect($start=0,$limit=20){
	
		$start = intval($this->apps->_g('st'));
	
		$sql = "SELECT COUNT( * ) num, sm.name, sm.last_name
				FROM tbl_code_inventory tci
				LEFT JOIN social_member sm ON tci.userid = sm.id
				WHERE sm.name IS NOT NULL AND sm.name <> ''				 
				AND sm.id NOT IN ({$this->apps->getadminemail()})
				GROUP BY tci.userid
				ORDER BY num DESC
				LIMIT {$start},{$limit}
				";
		// pr($sql);
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		
		$sqlTotal = "
				SElECT COUNT(*) total FROM (
				SELECT COUNT( * ) num
				FROM tbl_code_inventory tci
				LEFT JOIN social_member sm ON tci.userid = sm.id
				WHERE sm.name IS NOT NULL AND sm.name <> ''				 
				AND sm.id NOT IN ({$this->apps->getadminemail()})
				GROUP BY tci.userid
				ORDER BY num DESC) a
				";
		// pr($sqlTotal);		
		$qDataTotal = $this->apps->fetch($sqlTotal);
		
		return array('rec'=>$qData, 'total'=>$qDataTotal['total']);
					
	}
	
	function getChartDataOf($searchData=null){
		
		if($searchData==null) return false;
		
		if(is_array($searchData)) {
			foreach($searchData as $val){
				$nuArr[] = "'{$val}'";
			}
			if($nuArr)	$searchData = implode(',',$nuArr);
			else return false;
		}
		
		$theactivity = "{$searchData}";
		
		/* get activity ID */
		$actionnamedata = $this->getactivitytype($theactivity);

		if($actionnamedata) {
			
			$activityID = implode(',',$actionnamedata['id']);
		}else $activityID = false;
			
		$sql = "SELECT count(*) total, DATE(date_time) dateformatonly  FROM tbl_activity_log WHERE action_id IN ({$activityId}) ORDER BY dateformatonly GROUP BY dateformatonly LIMIT {$start},{$limit}";

		$getChartDataOf[$searchData] = $this->apps->fetch($sql);
		
		//pr($getChartDataOf);
		exit;

	}

	function getactivitytype($dataactivity=null,$id=false){
			if($dataactivity==null)return false;
			if($id) $qAppender = " id IN ({$dataactivity}) ";
			else $qAppender = " activityName IN ({$dataactivity})  ";
			$theactivity = false;
			/* get activity  id */	
			$sql = " SELECT * FROM tbl_activity_actions WHERE {$qAppender} ";

			$qData = $this->apps->open(1);
				
			if($qData) {
				foreach($qData as $val){
					$theactivity['id'][$val['id']]=$val['id'];
					$theactivity['name'][$val['id']]=$val['activityName'];
					
				}
			}
			return $theactivity;
		}
	
	function timeVisit()
	{
		$sql = "SELECT SEC_TO_TIME(AVG(dd)) dd, 'website' action_value, dateday,AVG(dd) intdd
					FROM
					(
					SELECT   ROUND(AVG( ss )/(60 )) dd , action_value , dd dateday
									FROM (
									SELECT action_value, max( date_ts ) - min( date_ts ) ss, DATE( date_time ) dd
									FROM tbl_activity_log
									WHERE action_id =6 AND action_value NOT REGEXP 'send'
									AND DATE(date_time) >= '{$this->startdate}' 
									AND DATE(date_time) <= '{$this->enddate}'
									GROUP BY action_value, dd
									ORDER BY ss DESC
									)a
									GROUP BY action_value, dateday
									ORDER BY dateday DESC
						) b
				GROUP BY dateday";
		$qData = $this->apps->fetch($sql,1);
		// pr($qData);
		if(!$qData) return false;
		return $qData;
	}
	
	function timeSpent()
	{
		$sql = "SELECT  SEC_TO_TIME(ROUND(AVG( ss )/(60 ))) dd , action_value 
				FROM (
				SELECT action_value, max( date_ts ) - min( date_ts ) ss, DATE( date_time ) dd
				FROM tbl_activity_log
				WHERE action_id =6 AND action_value NOT REGEXP 'send'
				AND DATE(date_time) >= '2013-09-11' 
				AND DATE(date_time) <= '{$this->enddate}'
				GROUP BY action_value, dd
				ORDER BY ss DESC
				)a
				GROUP BY action_value 
				ORDER BY dd DESC";
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		return $qData;
	}
	
	function loginInventory()
	{
		$sql = "SELECT COUNT( * ) num, sm.name, sm.last_name, sm.email, sm.login_count, DATE( log.date_time ) dd
				FROM tbl_activity_log log
				LEFT JOIN social_member sm ON log.user_id = sm.id
				WHERE log.action_id =1
				AND sm.name IS NOT NULL
				AND sm.name <> ''
				GROUP BY log.user_id
				ORDER BY dd DESC";
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		return $qData;
	}
	
	function regionLogin()
	{
		$sql = "
				SELECT COUNT(*) num , city FROM (
					SELECT COUNT(*)num,  city.city, log.user_id FROM tbl_activity_log log
					LEFT JOIN social_member sm ON log.user_id = sm.id
					LEFT JOIN marlborohunt_city_reference city ON sm.city = city.id
					WHERE log.action_id = 1 AND sm.name IS NOT NULL AND sm.name <> '' AND sm.city IS NOT NULL 
					AND sm.city <> '' AND city.city IS NOT NULL AND city.city  <> ''
					GROUP BY city.city, log.user_id ORDER BY num DESC )a
				GROUP BY city ORDER BY num DESC LIMIT 10";
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		return $qData;
	}
	
	function top10page()
	{
		$sql = "SELECT COUNT( * ) num, action_value, DATE( date_time ) dd
				FROM tbl_activity_log
				WHERE action_id =6 AND action_value NOT REGEXP 'home'
				GROUP BY action_value
				ORDER BY num DESC LIMIT 10";
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		return $qData;
	}
	
	function timedayoflogin()
	{
	$dataPeak = false;
		$sql = " SELECT AVG(login_num) login_num, date_d,date_h,COUNT(userid) total_user
                    FROM
                    (
                        SELECT (MAX(date_ts) - MIN(date_ts)) / COUNT(user_id)  as login_num, 
						COUNT(user_id)  as userid, 
                        DATE_FORMAT(date_time,'%H') as date_h , DATE(date_time) as date_d
                        FROM tbl_activity_log
                        WHERE action_id=1
                         
                        GROUP BY date_d,date_h,user_id 
                    ) as peakTbl
                    GROUP BY date_d,date_h ";
					// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		for($n=0;$n<=24;$n++) {
		
			
			
            if(strlen($n)==1) $n="0{$n}";
			
			if($n==24) $x="00";
			else $x= $n;
			$dataPeak[(string)$x]=0;
        }
		
		
		foreach ($qData as $kPeak => $vPeak) {
         if($vPeak['date_h']==24) $vPeak['date_h']="00";
		   $dataPeak[(string)$vPeak['date_h']]+=$vPeak['total_user'];
        }
		ksort($dataPeak);
	 // pr($dataPeak);
	 // exit;
		return $dataPeak;
	}
	
	function reporttimelogin()
	{
	
		$sql = "SELECT count(*) num, DATE(date_time) datetime FROM tbl_activity_log 
				WHERE action_id = 1 
				AND DATE(date_time) >= '{$this->startdate}' 
				AND DATE(date_time) <= '{$this->enddate}'
				GROUP BY datetime 
				ORDER BY datetime ASC";
		$qData = $this->apps->fetch($sql,1);
	
		return $qData;
	
	}
	
	function loginhistories(){
		$data =false;
				 
		/* total login */
		// LOGIN
		$sql = "
		SELECT COUNT(*) total,DATE(log.date_time) dd ,log.user_id,sm.name,sm.last_name,sm.email
		FROM `tbl_activity_log` log
		LEFT JOIN social_member sm ON sm.id= log.user_id
		WHERE action_id = 1 AND sm.email <> ''
		AND user_id <> 0 	
		AND DATE(date_time) >= '{$this->startdate}' 
		AND DATE(date_time) <= '{$this->enddate}'		
		GROUP BY dd,user_id
		 ";
		$qData = $this->apps->fetch($sql,1);	
		$arrdata=false;
		$sData = false;
		$dates = false;
		$edates = false;
		$userData = false;
		if(!$qData) return false;
		foreach($qData as $val){
			$sData[$val['user_id']]['datas'][$val['dd']] = $val['total'];
			$sData[$val['user_id']]['users']['name'] = $val['name'];
			$sData[$val['user_id']]['users']['last_name'] = $val['last_name'];
			$sData[$val['user_id']]['users']['email'] = $val['email'];
						
			
			
			$userData[$val['user_id']] = $val['user_id'];
		}
		
		$mindate = strtotime($this->startdate);
		$maxdate = strtotime($this->enddate);
		$totaldate = ($maxdate - $mindate) / (60*60*24);
		$dates[$this->startdate] = $this->startdate;
		for($i=0;$i<=$totaldate;$i++){
		// pr($totaldate);
			$datetimes = date("Y-m-d",$mindate);
			$val = date("Y-m-d" , strtotime("{$datetimes} +{$i} day"));
			$dates[$val] = $val;	
			$edates[$val] = explode('-',$val );
		}	
	
		foreach($userData as $udata){
			foreach($dates  as $vdate){
				if(!array_key_exists($vdate,$sData[$udata]['datas']))$sData[$udata]['datas'][$vdate] = 0;		
			}
		}
		// pr($sData);exit;
		$data['datas']	=	$sData;	
		$data['dates']	=	$edates;
		$data['users']	=	$userData;
		// pr($data);
		return $data;
	
	}
	
	function dyouserweekly(){
		$data =false;
				 
		/* total login */
		// LOGIN
		$sql = "
	 
		SELECT userid user_id,name, last_name, email, apoint total,weeky dd,DATE_SUB(
		DATE_ADD(MAKEDATE('2013', 1), INTERVAL weeky WEEK),
		INTERVAL WEEKDAY(
		DATE_ADD(MAKEDATE('2013', 1), INTERVAL weeky WEEK)
		) +7 DAY) startdates,DATE_SUB(
		DATE_ADD(MAKEDATE('2013', 1), INTERVAL weeky WEEK),
		INTERVAL WEEKDAY(
		DATE_ADD(MAKEDATE('2013', 1), INTERVAL weeky WEEK)
		) +1 DAY) enddates
		FROM (
			SELECT spoint apoint,userid ,WEEKOFYEAR(dd) weeky
			FROM (
				SELECT 
				COUNT(*) spoint, DATE(datetime) dd,userid
				FROM my_dyo 
			  				WHERE  
				DATE(datetime) >= '{$this->startdate}' 
				AND DATE(datetime) <= '{$this->enddate}'		
				GROUP BY userid,dd
			) a

			GROUP BY userid,weeky
		) b
		LEFT JOIN social_member sm ON sm.id = b.userid
		WHERE name IS NOT NULL AND name <> ''
		GROUP BY weeky,userid
		ORDER BY weeky DESC 

		";
			// WHERE  
				// DATE(datetime) >= '{$this->startdate}' 
				// AND DATE(datetime) <= '{$this->enddate}'		
				
		$qData = $this->apps->fetch($sql,1);	
		$arrdata=false;
		$sData = false;
		$dates = false;
		$edates = false;
		$userData = false;
		
			// pr($sql);exit;
		
		if(!$qData) return false;
		foreach($qData as $val){
			$sData[$val['user_id']]['datas'][$val['dd']] = $val['total'];
			$sData[$val['user_id']]['users']['name'] = $val['name'];
			$sData[$val['user_id']]['users']['last_name'] = $val['last_name'];
			$sData[$val['user_id']]['users']['email'] = $val['email'];
						
			
			
			$userData[$val['user_id']] = $val['user_id'];
			
			$arrstart = explode('-',$val['startdates']);
			$arrend = explode('-',$val['enddates']);
			
			$edates[$val['dd']] = $arrstart[1]."/".$arrstart[2]."<br />s/d<br />". $arrend[1]."/".$arrend[2];
		}
		
		/*
		$mindate = strtotime($this->startdate);
		$maxdate = strtotime($this->enddate);
		$totaldate = ($maxdate - $mindate) / (60*60*24);
		$dates[$this->startdate] = $this->startdate;
		for($i=0;$i<=$totaldate;$i++){
		// pr($totaldate);
			$datetimes = date("Y-m-d",$mindate);
			$val = date("Y-m-d" , strtotime("{$datetimes} +{$i} day"));
			$dates[$val] = $val;	
			$edates[$val] = explode('-',$val );
		}	
	
		foreach($userData as $udata){
			foreach($dates  as $vdate){
				if(!array_key_exists($vdate,$sData[$udata]['datas']))$sData[$udata]['datas'][$vdate] = 0;		
			}
		}
		*/
		foreach($userData as $udata){
			foreach($edates  as $kdate => $vdate){
				if(!array_key_exists($kdate,$sData[$udata]['datas']))$sData[$udata]['datas'][$kdate] = 0;		
			}
		}
		// pr($sData);exit;
		$data['datas']	=	$sData;	
		$data['dates']	=	$edates;
		$data['users']	=	$userData;
		// pr($data);
		return $data;
	}
	
	function averageuserpointweekly($start=0,$limit=4){
		/*total*/
		$data['result'] = false;
		$data['total'] = 0;
		$sql ="
			SELECT COUNT(*) total FROM (
				SELECT ROUND(AVG(apoint)) wpoint,weeky,DATE_SUB(
				DATE_ADD(MAKEDATE('2013', 1), INTERVAL weeky WEEK),
				INTERVAL WEEKDAY(
				DATE_ADD(MAKEDATE('2013', 1), INTERVAL weeky WEEK)
				) +7 DAY) startdates,DATE_SUB(
				DATE_ADD(MAKEDATE('2013', 1), INTERVAL weeky WEEK),
				INTERVAL WEEKDAY(
				DATE_ADD(MAKEDATE('2013', 1), INTERVAL weeky WEEK)
				) +1 DAY) enddates
				FROM (
				SELECT ROUND(AVG(spoint)) apoint,userid ,WEEKOFYEAR(dd) weeky
				FROM (
					SELECT 
					SUM(point) spoint, DATE(datetimes) dd,userid
					FROM `tbl_code_inventory` 
					WHERE 
					n_status <> 4
					GROUP BY userid,dd
				) a

				GROUP BY userid,weeky
				) b
				GROUP BY weeky
			) c
		";
		$qTotal = $this->apps->fetch($sql);
		if($qTotal)$total = intval($qTotal['total']);
		else $total = 0;
		$sql ="
			SELECT ROUND(AVG(apoint)) wpoint,weeky,DATE_SUB(
			DATE_ADD(MAKEDATE('2013', 1), INTERVAL weeky WEEK),
			INTERVAL WEEKDAY(
			DATE_ADD(MAKEDATE('2013', 1), INTERVAL weeky WEEK)
			) +7 DAY) startdates,DATE_SUB(
			DATE_ADD(MAKEDATE('2013', 1), INTERVAL weeky WEEK),
			INTERVAL WEEKDAY(
			DATE_ADD(MAKEDATE('2013', 1), INTERVAL weeky WEEK)
			) +1 DAY) enddates
			FROM (
			SELECT ROUND(AVG(spoint)) apoint,userid ,WEEKOFYEAR(dd) weeky
			FROM (
				SELECT 
				SUM(point) spoint, DATE(datetimes) dd,userid
				FROM `tbl_code_inventory` 
				WHERE 
				n_status <> 4
				GROUP BY userid,dd
			) a

			GROUP BY userid,weeky
			) b
			GROUP BY weeky
			ORDER BY weeky DESC 
		";
		
		$qData = $this->apps->fetch($sql,1);
		if($qData) {
			foreach($qData as $key => $val){ 
			$medianData = 0;
			$sql ="
				 SELECT  apoint,userid 
				 FROM
						 (
						SELECT ROUND(AVG(spoint)) apoint,userid 
						FROM (
							SELECT 
							SUM(point) spoint, DATE(datetimes) dd,userid
							FROM `tbl_code_inventory` 
							WHERE 				
							n_status <> 4
									AND DATE(datetimes) >= '{$val['startdates']}' 
									AND DATE(datetimes) <= '{$val['enddates']}'	
							GROUP BY userid,dd
						) a
						 
						GROUP BY userid 
					) b
					GROUP BY apoint 
					ORDER BY apoint ASC
				";
			$medData = $this->apps->fetch($sql,1);
				if($medData){
				// pr($medData);
					$idxmed = ROUND((count($medData)+1) / 2);
					if($idxmed!=0) if(array_key_exists((string)$idxmed,$medData))$medianData = $medData[$idxmed]['apoint'];
				}
				
			$qData[$key]['median'] = $medianData;	
			}
			$data['result'] = $qData;
			
		}
		// pr($qData);exit;
		$data['total'] = $total;
		return $data;
		
		
	}
	function downloadaverageuserpointweekly(){
	 
		 $medianData = 0;
		$sql ="
			 SELECT  apoint,userid 
			 FROM
				 (
				SELECT ROUND(AVG(spoint)) apoint,userid 
				FROM (
					SELECT 
					SUM(point) spoint, DATE(datetimes) dd,userid
					FROM `tbl_code_inventory` 
					WHERE 				
					n_status <> 4
							AND DATE(datetimes) >= '{$this->startdate}' 
							AND DATE(datetimes) <= '{$this->enddate}'	
					GROUP BY userid,dd
				) a
				 
				GROUP BY userid 
			) b
			GROUP BY apoint
		";
		
		$qData = $this->apps->fetch($sql,1);
		 if($qData)	{
				if($qData){
					$idxmed = ROUND((count($qData)+1) / 2);
					if($idxmed!=0) $medianData = $qData[$idxmed]['apoint'];
					 
				}
			return array('result'=>$qData,'median'=>$medianData);
			
		} else return  false;
		
		
	}
	
	
	
}

?>