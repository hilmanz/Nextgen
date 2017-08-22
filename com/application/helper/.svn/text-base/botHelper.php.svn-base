<?php 

class botHelper {
	
	function __construct($apps){
		global $logger;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
		$this->dbshema= 'tbl';
	}
	 
	 
	 
	 
	 
	function getAquisition(){
	 
		 
		$sql = "
		INSERT INTO tbl_sba_archivement
		( userid, datetimes, aquisition )
		 
		
			SELECT 
			referrerbybrand userid,DATE(e.register_date) datetimes  ,COUNT(*) aquisition
			FROM my_entourage e
			LEFT JOIN social_member sm ON sm.id = e.referrerbybrand
			LEFT JOIN my_pages p ON p.ownerid = sm.id
			WHERE 
			e.n_status = 1 
			AND p.type = 1
			AND e.referrerbybrand IS NOT NULL
			AND e.referrerbybrand <> ''
			AND sm.n_status = 1
			GROUP BY e.referrerbybrand,datetimes
			ORDER BY datetimes DESC
		 
		ON DUPLICATE KEY UPDATE aquisition= VALUES(aquisition)
		";
		 
		 
		 
		$qData = $this->apps->query($sql);
		
	 
	 
	}
	
	function getCheckin(){
		
		$sql = "
		INSERT INTO tbl_sba_archivement
		( userid, datetimes, checkin )
		 
		
						SELECT 
			e.userid,DATE(e.join_date) datetimes  ,COUNT(*) checkin
			FROM my_checkin e
			LEFT JOIN beat_news_content nc ON nc.id = e.contentid
			LEFT JOIN social_member sm ON sm.id = e.userid
			LEFT JOIN my_pages p ON p.ownerid = sm.id
			WHERE 
			e.n_status = 1 
			AND nc.articleType = 3
			AND nc.n_status = 1
			AND p.type = 1
			AND e.userid IS NOT NULL
			AND e.userid <> ''
			AND sm.n_status = 1
			GROUP BY e.userid,datetimes
			ORDER BY datetimes DESC
		 
		ON DUPLICATE KEY UPDATE checkin= VALUES(checkin)
		";
		 
		 
		 
		$qData = $this->apps->query($sql);
		
	}
	 
	 
	 function getEngagement(){
		
		$sql = "
		INSERT INTO tbl_sba_archivement
		( userid, datetimes, engagement )
		 
		
			SELECT 
			e.userid,DATE(e.join_date) datetimes  ,COUNT(*) engagement
			FROM my_checkin e
			LEFT JOIN beat_news_content nc ON nc.id = e.contentid
			LEFT JOIN social_member sm ON sm.id = e.userid
			LEFT JOIN my_pages p ON p.ownerid = sm.id
			WHERE 
			e.n_status = 1 
			AND nc.articleType = 5
			AND nc.authorid = e.userid
			AND nc.n_status = 1
			AND p.type = 1
			AND e.userid IS NOT NULL
			AND e.userid <> ''
			AND sm.n_status = 1
			AND EXISTS ( SELECT * FROM beat_news_content_tags t WHERE t.friendtype = 0 AND t.contentid  = e.contentid LIMIT 1 )
			GROUP BY e.userid,datetimes
			ORDER BY datetimes DESC
		 
		ON DUPLICATE KEY UPDATE engagement= VALUES(engagement)
		";
		 
		 
		 
		$qData = $this->apps->query($sql);
		
	}
	 
	 
	 
	 
	 
}
?>