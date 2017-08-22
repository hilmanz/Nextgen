<?php 

class dateHelper {

		
	function __construct($apps){
		$this->apps = $apps;
	
	}
	
	function dateDiff($pubDate){
		$today = strtotime(date('Y-m-d H:i:s'));
		$pubDate = strtotime($pubDate);
		//detik,menit,jam,hari
		$seconds = floor(abs($today-$pubDate));
		$minutes = floor(abs($today-$pubDate)/60);
		$hours = floor(abs($today-$pubDate)/60/60);
		$days = floor(abs($today-$pubDate)/60/60/24);
		$weeks = floor(abs($today-$pubDate)/60/60/24/7);
		$months = floor(abs($today-$pubDate)/60/60/24/7/4);
		$years = floor(abs($today-$pubDate)/60/60/24/7/4/12);
			
		$formatDiff = array($seconds,$minutes,$hours,$days,$weeks,$months,$years);
		
		if($seconds < 60) return $formatDiff[0].' seconds ago';
		if($seconds > 60 && $minutes < 60) return $formatDiff[1].' minutes ago';
		if($minutes > 60 && $hours < 24) return $formatDiff[2].' hours ago';
		if($hours > 24 && $days < 7) return $formatDiff[3].' days ago';
		if($days > 7 && $weeks < 4) return $formatDiff[4].' weeks ago';
		if($weeks > 4 && $months < 12) return $formatDiff[5].' months ago';
		if($months > 12 ) return $formatDiff[6].' years ago';
		
	}
	
	function remainingTime($enddate=NULL)
	{ 
			if($enddate==NULL)$enddate = date("Y-m-d H:i:s");
			$startdate=date("Y-m-d H:i:s");
			
			$diff=strtotime($enddate)-strtotime($startdate);
			
			// immediately convert to days
			$temp=$diff/86400; // 60 sec/min*60 min/hr*24 hr/day=86400 sec/day

			// days
			$days=floor($temp); 
			$temp=24*($temp-$days);
			// hours
			$hours=floor($temp);
			$temp=60*($temp-$hours);
			// minutes
			$minutes=floor($temp);
			$temp=60*($temp-$minutes);
			// seconds
			$seconds=floor($temp);
			
			$data->days = $days;
			$data->hours = $hours;
			$data->minutes = $minutes;
			$data->seconds = $seconds;
			
			return $data;
	}
	
	function hoursCalendar($pubDate){
		// $pubDate = strtotime($pubDate);
		$fullMonth = array('January','February','Maret','April','Mei','Juni','July','Agustus','September','Oktober','November','Desember');
		
		$year = substr($pubDate, 0, 4);
		$month = abs(substr($pubDate, 5, 2))-1;
		if ($month == 0){
			$month = $fullMonth[12];
		}else{
			$month = $fullMonth[$month];
		}
		$day = abs(substr($pubDate, 8, 2));
		$hourAndMinutes = substr($pubDate, 11, 5);
		
		return $hourAndMinutes.' '.$day.' '.$month.' '.$year;
	}
			
}	

?>

