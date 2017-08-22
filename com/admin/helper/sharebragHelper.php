<?php 

class sharebragHelper {


	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;

		$this->apps = $apps;
		if($this->apps->isUserOnline())  {
			if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
			
		}
		
		if(isset($_SESSION['lid'])) $this->lid = intval($_SESSION['lid']);
		else $this->lid = 1;
		if($this->lid=='')$this->lid=1;
		$this->server = intval($CONFIG['VIEW_ON']); 
		
		
		$this->dbshema = "marlboro-inorout";
		
		$this->approver=array(1,2,3,4);
		$this->inviter=array(1,2,3,4);
	}
	
	function sharebragList($start=null,$limit=10)
	{
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
		
		$search = strip_tags($this->apps->_p('search'));
		$sortby = strip_tags($this->apps->_p('sortby'));
		$sortevent = strip_tags($this->apps->_p('sortevent'));
		// $publishedtype = intval($this->apps->_p('publishedtype'));
		$startdate = $this->apps->_p('startdate');
		$enddate = $this->apps->_p('enddate');
		
		//RUN FILTER
		$filter = "";
		$filter = $search=="Search..." ? "" : "AND (sb.title LIKE '%{$search}%' OR sb.desc LIKE '%{$search}%' OR sm.last_name LIKE '%{$search}%' OR sm.name LIKE '%{$search}%')";
		
		if ($sortby=='') {
			$orderby = ' sb.uploaddate DESC ';
		}
		if ($sortby=='win') {
			$filter.=" AND sb.win = 1";
			$orderby =" sb.win DESC ";
		}		
		if ($sortby=='finalist'){
			$filter.=" AND sb.n_status = 2";
			$orderby =" sb.n_status DESC ";
		}
		if ($sortby=='vote'){
			$filter.=" AND totalvote<>0"; 
			$orderby =" totalvote DESC ";
		}
		 
		
		// $filter .= $winner!=0 ? " AND sb.win = {$win}" : " AND sb.win = 1";
		// $filter .= $publishedtype ? "AND n_status = {$publishedtype}" : " AND n_status != 3";
		$filter .= $startdate ? " AND uploaddate >= '{$startdate}'" : "";
		$filter .= $enddate ? " AND uploaddate < '{$enddate}'" : "";
		
		// drop down list event
		$sqlevent = "SELECT * FROM {$CONFIG['DATABASE_WEB']}.events WHERE type = 4";
		$resEvent = $this->apps->fetch($sqlevent,1);
		if($resEvent) $edata=	$resEvent;
			else $edata = false; 
			
		//GET TOTAL
		$sql = "SELECT count(*) total FROM {$CONFIG['DATABASE_WEB']}.share_brag_contest sb
				LEFT JOIN
				{$CONFIG['DATABASE_WEB']}.social_member sm ON sb.userid = sm.id
				LEFT JOIN 
				( SELECT COUNT( * ) totalvote,contentid FROM {$CONFIG['DATABASE_WEB']}.votes WHERE n_status = 1 GROUP BY contentid ) v  ON v.contentid = sb.id
				WHERE sb.n_status IN(1,2)  {$filter}";
		$total = $this->apps->fetch($sql);		
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST 
		$sql = "SELECT sm.name, sm.last_name, sb.title, sb.desc description, sb.uploaddate, sb.img, sb.n_status, sb.win, IF( v.totalvote IS NULL ,0,v.totalvote ) totalvote
				FROM {$CONFIG['DATABASE_WEB']}.share_brag_contest sb
				LEFT JOIN
				{$CONFIG['DATABASE_WEB']}.social_member sm ON sb.userid = sm.id
				LEFT JOIN 
				( SELECT COUNT( * ) totalvote,contentid FROM {$CONFIG['DATABASE_WEB']}.votes WHERE n_status = 1 GROUP BY contentid ) v  ON v.contentid = sb.id
				WHERE sb.n_status IN (1,2)  {$filter}
				ORDER BY {$orderby} LIMIT {$start},{$limit}";
		// pr($sql);
		$rqData = $this->apps->fetch($sql,1);
		if($rqData) {
			$no = $start+1;
			foreach($rqData as $key => $val){
				$val['no'] = $no++;
				$rqData[$key] = $val;
			}
			
			if($rqData) $qData=	$rqData;
			else $qData = false;
		} else $qData = false;
		
		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
		$result['resEvent'] = $resEvent;
		return $result;
	}
	
	
}
?>