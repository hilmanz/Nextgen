<?php 
class System extends SQLData{
    var $m_options;
	function System(){
		parent::SQLData();
		$this->init();
	}
	function init(){
		$this->open(0);
		
		$rs = $this->fetch("SELECT * FROM gm_options",1);
		$n = sizeof($rs);
		$this->close();
		for($i=0;$i<$n;$i++){
			$this->m_options[$rs[$i]['name']] = $rs[$i]['val'];
		}
	}
	function update($show,$broadcast,$mode){
		$this->query("UPDATE gm_options SET val='".$show."' WHERE name='show_broadcast'");
		$this->query("UPDATE gm_options SET val='".$broadcast."' WHERE name='broadcast_message'");
		$this->query("UPDATE gm_options SET val='".$mode."' WHERE name='maintenance_mode'");
		return true;
	}
	function isMaintenance(){
		global $CONFIG;
		if($this->m_options['maintenance_mode']=="1"||$CONFIG['MAINTENANCE_MODE']){
			return true;
		}
	}
	function hasBroadcast(){
		if($this->m_options['show_broadcast']=="1"){
			return true;
		}
	}
	function broadcast(){
		if($this->hasBroadcast()){
			return $this->m_options['broadcast_message'];
		}
	}
}
?>