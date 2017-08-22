<?php

class demographyHelper {

	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);

		$this->dbshema = "marlborohunt";			
		$this->startdate = $this->apps->_g('startdate');
		$this->enddate = $this->apps->_g('enddate');	
		if($this->enddate=='') $this->enddate = date('Y-m-d');		
		if($this->startdate=='') $this->startdate = date('Y-m-d' ,  strtotime( '-7 day' ,strtotime($this->enddate)) );
		
		
	}

	function userBasedonAge(){
	
		$sql = "SELECT count( * ) num, DATE_FORMAT( register_date, '%Y-%m-%d' ) datetime, sex, YEAR(
				CURRENT_TIMESTAMP ) - YEAR( birthday ) - ( RIGHT(
				CURRENT_TIMESTAMP , 5 ) < RIGHT( birthday, 5 ) ) AS age
				FROM social_member
				WHERE register_date <> '0000-00-00' AND register_date IS NOT NULL 
				AND id NOT IN ({$this->apps->getadminemail()})
				GROUP BY age
				HAVING age <> '2013' AND age >=0
				ORDER BY datetime ASC";
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
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
	
	function basedOnBrandPref(){
	
		$sql = "SELECT COUNT( * ) num, b.brands_name
				FROM social_brand_preferences sbp
				LEFT JOIN social_member sm ON sbp.userid = sm.id
				LEFT JOIN brands b ON sbp.brand_primary = b.id
				WHERE b.brands_name IS NOT NULL AND b.brands_name <> '' AND sm.id NOT IN ({$this->apps->getadminemail()})
				GROUP BY b.brands_name
				ORDER BY num DESC LIMIT 10";
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		return $qData;
	
	}
	
	function basedOnLocation(){
		
		$id = $this->apps->_p('toptensortby');
		
		if ($id==1) $filter = 'mcr.city';
		if ($id==2) $filter = 'mpr.province';
		// $filter = "";
		if($filter=="") $filter = 'mcr.city';
	
		$sql = "SELECT COUNT( * ) num, {$filter}  as city
				FROM social_member sm
				LEFT JOIN marlborohunt_city_reference mcr ON sm.city = mcr.id
				LEFT JOIN marlborohunt_province_reference mpr ON mcr.provinceid = mpr.id
				WHERE mcr.city IS NOT NULL AND mcr.city <> '' AND mcr.city not like '%not specified%'  AND sm.id NOT IN ({$this->apps->getadminemail()})
				GROUP BY {$filter}
				ORDER BY num DESC LIMIT 10";
		// pr($sql);
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		return $qData;
	
	}

}

?>