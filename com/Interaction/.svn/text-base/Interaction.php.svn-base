<?php
class Interaction extends SQLData{
	function __construct($req){
		
	}
	function track(){
		$t = $_REQUEST['t'];
		$p = $this->toArray($t);
		$this->open();
		$type = intval($p['type']);
		$user_id = $p['user_id'];
		$action_id = $p['action_id'];
		$weight = $p['weight'];
		$source_id = $p['source_id'];
		if($type==0){
			//free clicks
			$sql = "INSERT IGNORE INTO tbl_interaction_free(user_id,action_id,weight,log_time,source_id)
					VALUES(".intval($user_id).",".intval($action_id).",".intval($weight).",NOW(),'".$source_id."')";
			$this->query($sql);
		}else{
			//1 time per day click.
			$sql = "SELECT COUNT(id) as total FROM tbl_interaction_daily 
					WHERE user_id=".intval($user_id)." 
					AND TO_DAYS(log_time) = TO_DAYS(NOW()) 
					AND source_id='".$source_id."'";
			$rows = $this->fetch($sql);
			if($rows['total']==0){
				$sql = "INSERT IGNORE INTO tbl_interaction_daily(user_id,action_id,weight,log_time,source_id)
					VALUES(".intval($user_id).",".intval($action_id).",".intval($weight).",NOW(),'".$source_id."')";
				$this->query($sql);
			}
		}
	}
	function toArray($params){
		return unserialize(urldecode64($params));
	}
	function addTrack($type,$user_id,$action_id,$weight,$source_id){
		global $CONFIG;
		$uri = $CONFIG['TRACKER_URL'];
		$params = urlencode64(serialize(array("type"=>intval($type),"user_id"=>intval($user_id),
									"action_id"=>intval($action_id),
									"weight"=>intval($weight),"source_id"=>mysql_escape_string($source_id))));
		$uri = $uri."?t=".$params;
		return "<img src='".$uri."' width='1' height='1'/>";
	}
	function getTrackerCode($type,$user_id,$action_id,$weight,$source_id){
		$params = urlencode64(serialize(array("type"=>intval($type),"user_id"=>intval($user_id),
									"action_id"=>intval($action_id),
									"weight"=>intval($weight),"source_id"=>mysql_escape_string($source_id))));
		return $params;
	}
	function doTrack($type,$user_id,$action_id,$weight,$source_id){
		global $CONFIG;
		$uri = $CONFIG['TRACKER_URL'];
		$params = urlencode64(serialize(array("type"=>intval($type),"user_id"=>intval($user_id),
									"action_id"=>intval($action_id),
									"weight"=>intval($weight),"source_id"=>mysql_escape_string($source_id))));
		$uri = $uri."?t=".$params;
		file_get_contents($uri);
	}
}
?>