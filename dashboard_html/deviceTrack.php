<?php
	/*
	 *BABAR - DEVICE TRACKING
	 *09/04/2012
	 */
	/*** DEVICE TRACKING ***/
	// include_once "../engines/Database/SQLData.php";
	// $sql = new SQLData();
	include_once "../engines/Utility/Browser.php";
	$browser = new Browser();
	
	// cek exist tracking
	// $q = "SELECT COUNT(*) total FROM ".ReportDB.".rp_user_device WHERE device_name='".$browser->getPlatform()."' AND sessid='".session_id()."' LIMIT 1";
	// $sql->open(0);
	// $exist = $sql->fetch($q);
	// $sql->close();
	// if($exist['total']==0){
		// tracking user device for reporting
		// $q = "INSERT INTO ".ReportDB.".rp_user_device 
				// (device_name, sessid, date_time, date_time_ts) 
				// VALUES ('".$browser->getPlatform()."', '".session_id()."',NOW(), '".time()."')";
		// $sql->open(0);
		// $sql->query($q);
		// $sql->close();
		// tracking user device for reporting
	// }
	/*** END ***/
?>