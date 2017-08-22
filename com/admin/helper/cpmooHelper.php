<?php
class cpmooHelper {
	
	var $_mainLayout="";
	
	var $login = false;
	
	function __construct($apps=false){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		
		$this->config = $CONFIG;
		
	
	}
	
	function cpmoolists(){
	 
		$sql = "
			SELECT * FROM `cpmoo_code`
		";
	
		$list = $this->apps->fetch($sql,1);
		
		if($list){
				
			$n=$start+1;
			foreach($list as $key => $val){
					$list[$key]['no'] = $n++;
					 
			}
			
		
			 
			 return $list;
		}
		
			
		return false;
		 
	
	}
	
	
	function savecpmoo(){
		
		global $CONFIG;
	 	$data['id'] 			= $this->apps->_p('id');
		$data['CodeName'] 		= $this->apps->_p('CodeName');
		$data['Campaign'] 		= $this->apps->_p('Campaign');
		$data['Phase'] 			= $this->apps->_p('Phase');
		$data['Audience'] 		= $this->apps->_p('Audience');
		$data['MediaCategory'] 	= $this->apps->_p('MediaCategory');
		$data['OfferCategory'] 	= $this->apps->_p('OfferCategory');
		$data['CPAOType'] 		= $this->apps->_p('CPAOType');
		$data['siteID']			= $this->apps->_p('siteID');
	 
		foreach($data as $key => $val){
			$$key= $val;
		}
			 
		$sql = "INSERT INTO cpmoo_code (id,CodeName,Campaign,Phase,Audience,MediaCategory,OfferCategory,CPAOType,siteID,n_status) 
		VALUES ('{$id}','{$CodeName}',\"{$Campaign}\",'{$Phase}','{$Audience}','{$MediaCategory}','{$OfferCategory}','{$CPAOType}','{$siteID}','{$n_status}')
		ON DUPLICATE KEY UPDATE CodeName=VALUES(CodeName),OfferCategory=VALUES(OfferCategory),n_status=VALUES(n_status),siteID=VALUES(siteID)
		";
		$this->apps->query($sql);
		 
		$affectedrow = mysql_affected_rows();
			if($affectedrow){
				 return true;
			}
		  return true;
	}
	
	
	
}
