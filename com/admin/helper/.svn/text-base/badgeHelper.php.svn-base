<?php 


class badgeHelper {
	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);	
		$this->config = $CONFIG;
	}
	
	function badgeslist($start=null,$limit=12)
	{
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
		
		$search = strip_tags($this->apps->_p('search'));
		$notiftype = intval($this->apps->_p('notiftype'));
		// $publishedtype = intval($this->apps->_p('publishedtype'));
		$startdate = $this->apps->_p('startdate');
		$enddate = $this->apps->_p('enddate');
		
		//RUN FILTER
		$filter = "";
		$filter = $search=="Search..." ? "" : "AND (name LIKE '%{$search}%')";
		// $filter .= $notiftype!=0 ? " AND notiftype = {$notiftype}" : " AND notiftype = 3";
		// $filter .= $publishedtype ? "AND n_status = {$publishedtype}" : " AND n_status != 3";
		$filter .= $startdate ? " AND created_date >= '{$startdate}'" : "";
		$filter .= $enddate ? " AND created_date < '{$enddate}'" : "";
		
		//GET TOTAL
		$sql = "SELECT count(*) total FROM {$CONFIG['DATABASE_WEB']}.badges b  WHERE n_status = 1 {$filter}";
		$total = $this->apps->fetch($sql);		
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "
			SELECT b.* FROM {$CONFIG['DATABASE_WEB']}.badges b
			WHERE b.n_status = 1 {$filter} LIMIT {$start},{$limit}
		";
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
		return $result;
	
	}
	
	function insertnewbadges()
	{
		global $CONFIG;
		
		// pr($_POST);exit;
		$name = $this->apps->_p('name');
		$description = $this->apps->_p('description');
		$type = $this->apps->_p('type'); 
		$point = $this->apps->_p('point'); 
		$probability = $this->apps->_p('probability'); 
		$n_status = $this->apps->_p('n_status'); 
		
		$simpan = $this->apps->_p('submit');
		if(isset($simpan)){
			$sql = "INSERT INTO {$CONFIG['DATABASE_WEB']}.badges (`name`, `desc`, `type`, `point`, `prob`, `n_status`) 
					VALUES ('{$name}', '{$description}', '{$type}', '{$point}',  '{$probability}', '{$n_status}' )";
			// pr($sql);exit;
			$res = $this->apps->query($sql);
			return $this->apps->getLastInsertId();
		}
		
		return false;
	}
	
	function badgesimageupdate($insertnewbadges=false, $images=false)
	{
		global $CONFIG;
		//update
		// pr($images); exit;
		if (!$insertnewbadges) return false;
		
		$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.badges SET image = '{$images}' WHERE id = {$insertnewbadges}";
		$res = $this->apps->query($sql);
		// pr($sql);exit;
	}
	
	function selectupdatedata($id=NULL)
	{
		
		global $CONFIG;
		$sql = "SELECT * FROM {$CONFIG['DATABASE_WEB']}.badges WHERE id = {$id} LIMIT 1";
		// pr($sql);
		// fetch()
		$qData = $this->apps->fetch($sql);
		return $qData;
	
	}
	
	function updatethedata($id=NULL, $images=false){
		global $CONFIG;
		// pr($_POST);
		$name = $this->apps->_p('name');
		$description = $this->apps->_p('description');
		$type = $this->apps->_p('type'); 
		$point = $this->apps->_p('point'); 
		$probability = $this->apps->_p('probability');  
		$n_status = $this->apps->_p('n_status'); 
		
		$submit = $this->apps->_p('submit');
		if(isset($submit)){
			$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.badges SET `name` = '{$name}',  
																`desc` = '{$description}', 
																`type` = '{$type}', 
																`point` = '{$point}', 
																`prob` = '{$probability}',
																`n_status` = '{$n_status}'
																WHERE id = '{$id}' "; 
			$res = $this->apps->query($sql);
			// pr($sql);exit;
			if($images!=false) 	$this->apps->query("UPDATE {$CONFIG['DATABASE_WEB']}.badges SET image='{$images}' WHERE id={$id}");
			return $res;
		}
		
		return false;
	}
	
	function getHapus($cid=NULL){
		global $CONFIG;
		if($cid){
			$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.badges SET n_status = 3 WHERE id = {$cid} ";
			$qdata  =  $this->apps->query($sql);
			if ($qdata) $data = true;
			else $data = false;
		}else {
			$data = false;	
		}
		return $data;		
	}
	
	function masterbBadges(){
		 
		$sql="SELECT * 
				FROM {$this->config['DATABASE_WEB']}.badges
				WHERE n_status =1  ORDER BY id ASC ";
		$rs=$this->apps->fetch($sql,1);
		$badges = false;
		if($rs){
			foreach($rs as $val){
				$badges[$val['id']] = $val;
			}
		}
		return $badges;
	}
	 
	function getcodelist($start=0, $limit=20){
	global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		if($start==null)$start = intval($this->apps->_request('start'));
		$limit = intval($limit);
		
		$search = strip_tags($this->apps->_p('search'));
		$notiftype = intval($this->apps->_p('notiftype'));
		// $publishedtype = intval($this->apps->_p('publishedtype'));
		$startdate = $this->apps->_p('startdate');
		$enddate = $this->apps->_p('enddate');
	
		$filter = "";
		$filter = $search=="Search..." ? "" : "AND (code LIKE '%{$search}%')";
		// $filter .= $notiftype!=0 ? " AND notiftype = {$notiftype}" : " AND notiftype = 3";
		// $filter .= $publishedtype ? "AND n_status = {$publishedtype}" : " AND n_status != 3";
		$filter .= $startdate ? " AND created_date >= '{$startdate}'" : "";
		$filter .= $enddate ? " AND created_date < '{$enddate}'" : "";
		
		//GET TOTAL
		$sql = "SELECT count(*) total FROM {$CONFIG['DATABASE_WEB']}.badges b  WHERE n_status = 1 {$filter}";
		$total = $this->apps->fetch($sql);		
		if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "
			SELECT * 
				FROM {$this->config['DATABASE_WEB']}.badges_code
				WHERE n_status =1 {$filter} ORDER BY id DESC LIMIT {$start},{$limit}
		";
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
		return $result;
	 		
	}
	
	
	function generateCode(){
		
		 
		$data['message'] = " sorry cannot generates ";
		$data['code'] =0;
		$data['result'] =false;
		
		$channel = $this->apps->_p('channel');
		$subchannel = $this->apps->_p('subchannel');
		// $type = $this->apps->_p('type');
		$loop = intval($this->apps->_p('iteration'));
		
		if($loop==0) return $data;
		
		$posteddate = date('Y-m-d H:i:s');
		$expireddate =date('Y-m-d H:i:s');
	
		
		$iscommonbadges=$this->apps->_p('type');
		$datetime = date("Y-m-d H:i:s");
		$getres = false;		
		
		if($subchannel==0) return $data;
		
		$sql = "SELECT * FROM {$this->config['DATABASE_WEB']}.badges_channel WHERE id={$subchannel} LIMIT 1";
		$channeldata =  $this->apps->fetch($sql);
		if(!$channeldata) return $data;
		
		$channel = $channeldata['channel'];
		$subchannel= $channeldata['sub_channel'];
		
		for ($i = 1; $i <= $loop; $i++){
		 
			$letters  = "ABCDEFGHJKMNPQRSTUVWXYZ23456789";
			$maskcode = substr(str_shuffle($letters), 0, $this->config['codedigit']);
			

			$sql = "INSERT INTO {$this->config['DATABASE_WEB']}.badges_code 
					(code, code_type, code_sub_channel, code_channel, created_date,  n_status)
					VALUES 
					('{$maskcode}', {$iscommonbadges}, '{$subchannel}', '{$channel}',   '{$datetime}', 1 )";
			// pr($sql);
			 
			 $this->apps->query($sql);
			if($this->apps->getLastInsertId()){
				$getres[$maskcode] = 1;
			}else $getres[$maskcode] = 0;
			
	
		}
		
		if($getres){
			$success = 0;
			$failed = 0;
			foreach($getres as $key => $val){
				if($val==1) $success++;
				else $failed++;			
			}
				
			$data['message'] = " success : {$success} , failed ; {$failed} ";
			$data['code'] =1;
			$data['result'] =true;
			return $data;
		}
		
				
		return $data;
		
	}
	
	function channellistdropdown(){
	
		$sql = "SELECT id, channel FROM {$this->config['DATABASE_WEB']}.badges_channel WHERE 1 GROUP BY channel";
		$res = $this->apps->fetch($sql,1);
		if(!$res) return false;
		return $res;		
		
	}
	
	function subchannellistdropdown(){
	
		$sql = "SELECT id, sub_channel FROM {$this->config['DATABASE_WEB']}.badges_channel WHERE 1 GROUP BY sub_channel";
		$res = $this->apps->fetch($sql,1);
		if(!$res) return false;
		return $res;		
		
	}
	
	 function yellowCodeGenerator(){
		
		$data['message'] = " sorry cannot generates ";
		$data['code'] =0;
		$data['result'] =false;
		
		$channel = $this->apps->_p('channel');
		$subchannel = $this->apps->_p('subchannel');
		$created_date = $this->apps->_p('created_date');
		$loop = intval($this->apps->_p('iteration'));
		
		if($loop==0) return $data;
		
		$posteddate = date('Y-m-d H:i:s');
		$expireddate =date('Y-m-d H:i:s');
	
		
		$iscommonbadges=1;
		$datetime = date("Y-m-d H:i:s");
		$getres = false;		
		
		for ($i = 1; $i <= $loop; $i++){
		 
			$letters  = "ABCDEFGHJKMNPQRSTUVWXYZ23456789";
			$maskcode = substr(str_shuffle($letters), 0, $this->config['codedigit']);
			

			$sql = "INSERT INTO {$this->config['DATABASE_WEB']}.badges_code 
					(code, code_type, code_sub_channel, code_channel, created_date,  n_status)
					VALUES 
					('{$maskcode}', {$iscommonbadges}, '{$subchannel}', '{$channel}',   '{$created_date}', 1 )";
			// pr($sql);
			 
			 $this->apps->query($sql);
			if($this->apps->getLastInsertId()){
				$getres[$maskcode] = 1;
			}else $getres[$maskcode] = 0;
			
	
		}
		
		if($getres){
			$success = 0;
			$failed = 0;
			foreach($getres as $key => $val){
				if($val==1) $success++;
				else $failed++;			
			}
				
			$data['message'] = " success : {$success} , failed ; {$failed} ";
			$data['code'] =1;
			$data['result'] =true;
			return $data;
		}
		
				
		return $data;
		
	}
	
	function getgamescodelist(){
		$sql="
				SELECT * 
				FROM {$this->config['DATABASE_WEB']}.badges_code
				WHERE n_status =1 AND code_channel = 'games' ORDER BY id DESC ";
		$rs=$this->apps->fetch($sql,1);
		$codes = false;
		if($rs){
			foreach($rs as $val){
				$codes[$val['id']] = $val;
			}
		}
		return $codes;
		
	}
	
	function savetohistory($userid=0,$badgeid=0,$codeid=0){
	
		if($userid==0) return false;
		if($badgeid==0) return false;
		if($codeid==0) return false;
		$dateTime = date('Y-m-d H:i:s');
		
		$sql = "INSERT INTO {$this->config['DATABASE_WEB']}.badges_history (userid, badgesid,codeid, n_status ,redeem_date)
				VALUES ({$userid}, {$badgeid},  {$codeid},  1 ,'{$dateTime}')";
	 
		$this->apps->query($sql);
	}
	
	function randomcodegen($getMasterCode = false){
			 
			$randbadge = false;
			if($getMasterCode){
				foreach ($getMasterCode as $key => $value){
					 
						if($value['type']==0){
							$popprob = ($value['prob'] * ($value['prob'] * (rand(1,12))));
							$randbadge[$value['id']] = $popprob;
						}
					 
				}
				
				if($randbadge){
					$maxBadge = max($randbadge);
					$badgeid = array_search($maxBadge, $randbadge);
					return $badgeid;
				}
			}
			return false;
	}
	
	function badgesofflinelist(){
		$sql="
				SELECT * 
				FROM {$this->config['DATABASE_WEB']}.tbl_offline_engagement
				WHERE 1 ORDER BY id DESC ";
		$rs=$this->apps->fetch($sql,1);
		$codes = false;
		if($rs){
			foreach($rs as $val){
				$codes[$val['id']] = $val;
			}
		}
		return $codes;
	
	}
	
	function sendGlobalMail($to,$from,$msg, $flag){
		GLOBAL $ENGINE_PATH, $CONFIG, $LOCALE;
		require_once $ENGINE_PATH."Utility/PHPMailer/class.phpmailer.php";
		
		$to = $this->apps->_p('email');
		$msg = 'halo please click this <a href="#">link </a>';
		
		$mail = new PHPMailer();
				
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
												   // 1 = errors and messages
												   // 2 = messages only		
		$mail->Host       = $CONFIG['EMAIL_SMTP_HOST'];  // sets the SMTP server
		$mail->SMTPAuth   = false;                  // enable SMTP authentication
		// $mail->Port       = 26;                    // set the SMTP port for the GMAIL server
		$mail->Username   = $CONFIG['EMAIL_SMTP_USER']; // SMTP account username
		$mail->Password   = $CONFIG['EMAIL_SMTP_PASSWORD'];        // SMTP account password
		
		$mail->SetFrom($CONFIG['EMAIL_FROM_DEFAULT'], 'No Reply Account');
		// $mail->From =$CONFIG['EMAIL_FROM_DEFAULT'];	

		if ($flag = 1){
			$mail->Subject    = "[ NOTIFICATION ] Account User Verification ";
		}
		if ($flag = 2){
			$mail->Subject    = "[ NOTIFICATION ] {$LOCALE[1]['sendmailresetpass']}";
		}

		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

		$mail->MsgHTML($msg);

		$address = $to;
		$mail->AddAddress($address);

		//$mail->AddAttachment("images/phpmailer.gif");      // attachment
		
		$result = $mail->Send();
	
		if($result) return array('message'=>'success send mail','result'=>true,'res'=>$result);
		else return array('message'=>'error mail setting','result'=>false,'res'=>$mail->ErrorInfo);
	}
		
	function downloadreport(){ 
	
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		// if($start==null)$start = intval($this->apps->_request('start'));
		// $limit = intval($limit);
		
		$search = strip_tags($this->apps->_p('search'));
		$notiftype = intval($this->apps->_p('notiftype'));
		// $publishedtype = intval($this->apps->_p('publishedtype'));
		$startdate = $this->apps->_p('startdate');
		$enddate = $this->apps->_p('enddate');
		
		//RUN FILTER
		$filter = "";
		// $filter = $search=="Search..." ? "" : "AND (code_sub_channel  LIKE '%{$search}%' OR code_channel LIKE '%{$search}%')";
		// $filter .= $notiftype!=0 ? " AND notiftype = {$notiftype}" : " AND notiftype = 3";
		// $filter .= $publishedtype ? "AND n_status = {$publishedtype}" : " AND n_status != 3";
		$filter .= $startdate ? " AND created_date >= '{$startdate}'" : "";
		$filter .= $enddate ? " AND created_date <= '{$enddate}'" : "";
		
		//GET TOTAL
		$sql = "SELECT COUNT(*) total 
				FROM {$this->config['DATABASE_WEB']}.badges_code
				WHERE n_status =1 {$filter}  ORDER BY id DESC";
		$total = $this->apps->fetch($sql);		
		// if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "
			SELECT * 
				FROM {$this->config['DATABASE_WEB']}.badges_code
				WHERE n_status =1  {$filter} ORDER BY id DESC ";
		// pr($sql);
		$rqData = $this->apps->fetch($sql,1);
		if($rqData) {
			$no = 1;
			foreach($rqData as $key => $val){
				$val['no'] = $no++;
				$rqData[$key] = $val;
			}
			
			if($rqData) $qData=	$rqData;
			else $qData = false;
		} else $qData = false;
		
		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
		return $result;
	
	}
	
	function downloadreportyellow(){ 
	
		global $CONFIG;
		
		$result['result'] = false;
		$result['total'] = 0;
		
		// if($start==null)$start = intval($this->apps->_request('start'));
		// $limit = intval($limit);
		
		$search = strip_tags($this->apps->_p('search'));
		$notiftype = intval($this->apps->_p('notiftype'));
		// $publishedtype = intval($this->apps->_p('publishedtype'));
		$startdate = $this->apps->_p('startdate');
		$enddate = $this->apps->_p('enddate');
		
		//RUN FILTER
		$filter = "";
		$filter = $search=="Search..." ? "" : "AND (code_sub_channel  LIKE '%{$search}%' OR code_channel LIKE '%{$search}%')";
		// $filter .= $notiftype!=0 ? " AND notiftype = {$notiftype}" : " AND notiftype = 3";
		// $filter .= $publishedtype ? "AND n_status = {$publishedtype}" : " AND n_status != 3";
		$filter .= $startdate ? " AND created_date >= '{$startdate}'" : "";
		$filter .= $enddate ? " AND created_date < '{$enddate}'" : "";
		
		//GET TOTAL
		$sql = "SELECT COUNT(*) total 
				FROM {$this->config['DATABASE_WEB']}.badges_code
				WHERE n_status =1  ORDER BY id DESC";
		$total = $this->apps->fetch($sql);		
		// if(intval($total['total'])<=$limit) $start = 0;
		
		//GET LIST
		$sql = "
			SELECT * {$this->config['DATABASE_WEB']}.badges_code
				WHERE n_status =1 AND code_channel = 'games' {$filter} ORDER BY id DESC ";
		$rqData = $this->apps->fetch($sql,1);
		pr($rqData);
		if($rqData) {
			$no = 1;
			foreach($rqData as $key => $val){
				$val['no'] = $no++;
				$rqData[$key] = $val;
			}
			
			if($rqData) $qData=	$rqData;
			else $qData = false;
		} else $qData = false;
		
		$result['result'] = $qData;
		$result['total'] = intval($total['total']);
		return $result;
	
	}
	
}
?>