<?php

class googleAnalyticHelper {

	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);

		$this->dbshema = "marlborohunt";	
	}
	
	function gaData(){
		$startdate = $this->apps->_g('startdate');
		$enddate = $this->apps->_g('enddate');	
		if($enddate=='') $enddate = date('Y-m-d');		
		if($startdate=='') $startdate = date('Y-m-d' ,  strtotime( '-7 day' ,strtotime($enddate)) );
				
		$sql = "SELECT 
					SUM(page_views) numPageViews , 
					SUM(visits) numVisits, 
					SEC_TO_TIME(AVG(visitDuration)) time_onSite,
					SEC_TO_TIME(AVG(time_onPage)) numTimeOnPage,
					ROUND(AVG(bounce_rate)) numBounceRate,
					SEC_TO_TIME(AVG(time_onSite)) numVisitDuration,
					SUM(unique_visitor) numUniqueVisitor,					
					date_d 

				FROM `ga_daily_data`
				WHERE 
				date_d >= '{$startdate}'
				AND date_d <= '{$enddate}'
				
				";
				// pr($sql);
		// $this->apps->open(1);// this how to use other server data
		$qData = $this->apps->fetch($sql);
		return $qData;
	}
	
	function gaDataChart(){
		$startdate = $this->apps->_g('startdate');
		$enddate = $this->apps->_g('enddate');	
		if($enddate=='') $enddate = date('Y-m-d');		
		if($startdate=='') $startdate = date('Y-m-d' ,  strtotime( '-7 day' ,strtotime($enddate)) );
		$this->apps->open(1);
		$sql = "SELECT * FROM ga_daily_data WHERE 
				date_d >= '{$startdate}'
				AND date_d <= '{$enddate}'";
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		$data = false;
		foreach ($qData as $val){
			$data['data']['visit'][$val['date_d']] = $val['visits'];
			$data['data']['bounce_rate'][$val['date_d']] = $val['bounce_rate'];
			$data['data']['page_views'][$val['date_d']] = $val['page_views'];
			$data['data']['time_onSite'][$val['date_d']] = $val['time_onSite'];
			$data['data']['visitDuration'][$val['date_d']] = $val['visitDuration'];
			$data['data']['time_onPage'][$val['date_d']] = $val['time_onPage'];
			$data['data']['time_onSite'][$val['date_d']] = $val['time_onSite'];
		}
		return $data;
	}
	
	function gaMobileChart(){
		$startdate = $this->apps->_g('startdate');
		$enddate = $this->apps->_g('enddate');	
		if($enddate=='') $enddate = date('Y-m-d');		
		if($startdate=='') $startdate = date('Y-m-d' ,  strtotime( '-7 day' ,strtotime($enddate)) );
		$this->apps->open(1);
		$sql = "SELECT visits AS num, device, DATE(date_d) dd
				FROM ga_daily_device_data
				WHERE date_d >= '{$startdate}'
				AND date_d <= '{$enddate}' AND device = 'mobile'";
		$qData = $this->apps->fetch($sql,1);
		// pr($qData);
		if(!$qData) return false;
		return $qData;
	}
	
	function gaDesktopChart(){
		$startdate = $this->apps->_g('startdate');
		$enddate = $this->apps->_g('enddate');	
		if($enddate=='') $enddate = date('Y-m-d');		
		if($startdate=='') $startdate = date('Y-m-d' ,  strtotime( '-7 day' ,strtotime($enddate)) );
		$this->apps->open(1);
		$sql = "SELECT visits AS num, device, DATE(date_d) dd
				FROM ga_daily_device_data
				WHERE date_d >= '{$startdate}'
				AND date_d <= '{$enddate}' AND device = 'desktop'";
		$qData = $this->apps->fetch($sql,1);
		// pr($qData);
		if(!$qData) return false;
		return $qData;
	}
	
	function gaTabletChart(){
		$startdate = $this->apps->_g('startdate');
		$enddate = $this->apps->_g('enddate');	
		if($enddate=='') $enddate = date('Y-m-d');		
		if($startdate=='') $startdate = date('Y-m-d' ,  strtotime( '-7 day' ,strtotime($enddate)) );
		$this->apps->open(1);
		$sql = "SELECT visits AS num, device, DATE(date_d) dd
				FROM ga_daily_device_data
				WHERE date_d >= '{$startdate}'
				AND date_d <= '{$enddate}' AND device = 'tablet'";
		$qData = $this->apps->fetch($sql,1);
		// pr($qData);
		if(!$qData) return false;
		return $qData;
	}
	
	function gaAllDeviceData()
	{
		$startdate = $this->apps->_g('startdate');
		$enddate = $this->apps->_g('enddate');	
		if($enddate=='') $enddate = date('Y-m-d');		
		if($startdate=='') $startdate = date('Y-m-d' ,  strtotime( '-7 day' ,strtotime($enddate)) );
		$this->apps->open(1);
		$sql = "SELECT SUM( visits ) total, device, date_d
				FROM ga_daily_device_all_data WHERE date_d >= '{$startdate}'
				AND date_d <= '{$enddate}'
				GROUP BY date_d, device
				ORDER BY date_d";
		$qData = $this->apps->fetch($sql,1);
		// pr($sql);
		if(!$qData) return false;
		$data = false;
		// kumpulin device id nya
		$arrDevice = false;
		foreach ($qData as $val){
			$arrDevice[$val['device']] = $val['device'];
		}
		// remap array per device id nya
		 
			foreach ($qData as $val){
				$data[$val['date_d']][$val['device']] = $val['total'];
			 
			}
		 foreach ($data as $key => $devicedata ){
					foreach ($arrDevice as $val ){
							// pr();
							if(!array_key_exists($val,$data[$key])) $data[$key][$val]= 0;
							
			 
					}
					ksort( $data[$key]);
			 
			}
			// pr($data);
		return $data;
	}
	
}

?>