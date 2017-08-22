<?php

class letterHelper {

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
	
	function numberRedeemLetter(){
	
		$sql = "SELECT COUNT( * ) num, tcd.codename
				FROM tbl_code_inventory tci
				LEFT JOIN tbl_code_detail tcd ON tci.codeid = tcd.id
				WHERE  tci.userid NOT IN ({$this->apps->getadminemail()})
				GROUP BY tci.codeid";
		// pr($sql);
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		return $qData;
	
	}
	
	function letterPercentage(){
	
		$sql = "SELECT * FROM tbl_code_detail";
		$qData = $this->apps->fetch($sql,1);
			foreach($qData as $val){
				$data[$val['id']][$val['codename']] = $val['prob']*100;
			}
			// pr($data);
		return $data;
		
	}
	
	function letterPercentageRedeem(){
	
		$letter = array(1,2,3,4,5,6,7,8,9,10,11,12);
		// $this->startdate = '2013-07-25';
		$sql = "SELECT count( codeid ) total, codeid
				FROM tbl_code_inventory
				WHERE DATE( datetimes ) >= '{$this->startdate}'
				AND DATE( datetimes ) <= '{$this->enddate}'
				GROUP BY codeid";
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		
		
		if ($qData){
			
			// $sum = count($qData);
			$sum = 0;
			
			foreach($qData as $val){
				
				$gotletter[] = $val['codeid'];
				$sum += $val['total']; 
			}
			// pr($sum);
			$nohave = array_diff($letter, $gotletter);
			foreach($nohave as $val){
				$qData[] = array('total' => 0, 'codeid'=>$val);
				
			}
			
			foreach($qData as $key => $val){
				$data[$val['codeid']] = $val;
			}
			
			ksort($data);
			
			foreach($data as $key => $val){
				$data[$key]['persentase'] = floor((100/$sum) * $val['total']);
				// $data[$key]['persentase'] = ((100/$sum) * $val['total']);
			}
			
		}else{
			foreach($letter as $val){
				$data[$val] = array('total' => 0, 'codeid'=>$val, 'persentase'=>0);
				
			}
		}
		
		return $data;
		
	}
	
	function numRedeemLetter()
	{
		$this->startdate = '2013-07-05';
		
		$history = array(
						1 => 'login existing user',
						2 => 'input code',
						3 => 'trading',
						4 => 'hidden code',
						5 => 'This or that ',
						6 => 'Login 20',
						7 => 'numRedeemLetter',
						8 => 'Cross Out',
						9 => 'Wallbreaker',
						10 => 'Word Hunt',
						11 => 'Doubt crasher',
						12 => 'minicooper',
						13 => 'stranger',
						14 => 'marlboros');
						
		$sql = "SELECT count( codeid ) total, histories
				FROM tbl_code_inventory
				WHERE DATE( datetimes ) >= '{$this->startdate}'
				AND DATE( datetimes ) <= '{$this->enddate}'
				AND histories NOT LIKE '%test%'
				AND histories NOT LIKE '%offlineuserletter%'
				AND histories NOT LIKE '%onlineuserletter%'
				AND histories NOT LIKE '%manual insert%'
				AND histories NOT LIKE 'gift from admin'
				AND histories IS NOT NULL
				AND histories <> ''
				GROUP BY histories";
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		// pr($qData);
		if ($qData){
				
			
			foreach ($qData as $key=> $value){
				
				
				if (preg_match("/thisorthat/", $value['histories'])){
					
					$thisorthat[] = $value;
					
				}else{
					
					if (preg_match("/existinguserletter/", $value['histories'])) $qData[$key]['histories'] = strtoupper($history[1]);
					if (preg_match("/hidden code/", $value['histories'])) $qData[$key]['histories'] = strtoupper($history[4]);
					if (preg_match("/key account/", $value['histories'])) $qData[$key]['histories'] = strtoupper($history[2]);
					if (preg_match("/20th/", $value['histories'])) $qData[$key]['histories'] = strtoupper($history[6]);
					if (preg_match("/trade/", $value['histories'])) $qData[$key]['histories'] = strtoupper($history[3]);
					if (preg_match("/games 1/", $value['histories'])) $qData[$key]['histories'] = strtoupper($history[8]);
					if (preg_match("/games 2/", $value['histories'])) $qData[$key]['histories'] = strtoupper($history[9]);
					if (preg_match("/games 4/", $value['histories'])) $qData[$key]['histories'] = strtoupper($history[10]);
					if (preg_match("/games 5/", $value['histories'])) $qData[$key]['histories'] = strtoupper($history[11]);
					if (preg_match("/minicooper/", $value['histories'])) $qData[$key]['histories'] = strtoupper($history[12]);
					if (preg_match("/stranger/", $value['histories'])) $qData[$key]['histories'] = strtoupper($history[13]);
					if (preg_match("/event marlboros/", $value['histories'])) $qData[$key]['histories'] = strtoupper($history[14]);
					
					// if (preg_match("/get from thisorthat/", $value['histories'])){
						// $qData[$key]['histories'] = strtoupper($history[5].str_replace('get from thisorthatcode', '',$value['histories']));
					// }
					
					$dataRedeem[] = $qData[$key];
					
				}
				
				
			}
			
			if ($thisorthat){
				$total = 0;
				foreach ($thisorthat as $value){
					$total += $value['total'];
				}
				
				$dataRedeem[] = array('total'=>$total, 'histories'=>strtoupper($history[5]));
			}
			
			// pr($qData);
			// pr($dataRedeem);
			return $dataRedeem;
		}
		return false;
	}
	
	function numTotalTrade()
	{
		$letter = array(1=>'D',2=>'O',3=>'N',4=>'T',5=>'B1',6=>'E1',7=>'A1',8=>'M',9=>'A2',10=>'Y',11=>'B2',12=>'E2');
		
		$sql = "SELECT count( sourceCode ) total, sourceCode
				FROM tbl_code_trade
				WHERE date( datetime ) >= '{$this->startdate}'
				AND date( datetime ) <= '{$this->enddate}'
				AND n_status IN (0,2)
				GROUP BY sourceCode";
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if ($qData){
			
			foreach($qData as $val){
				// $data[$letter[$val['sourceCode']]] = $val['total'];
				
				$codeid[] = $val['sourceCode'];
				$data['get'][$val['sourceCode']] = $val;
			}
			
			foreach ($letter as $key => $val){
				if (!in_array($key, $codeid)) $diff[] = $key;
			}
			
			foreach ($diff as $val){
				$data['get'][$val] = array('total'=>0, 'sourceCode'=>$val);
			}
			
			ksort($data['get']);
			
			$data['total'] = 0;
			foreach ($data['get'] as $key => $val){
				$data['get'][$key]['codeName'] = $letter[$val['sourceCode']];
				$data['total'] += $val['total'];
			}
			
			// pr($data);
			// pr($diff);
		}else{
			foreach ($letter as $key => $val){
				$data['get'][$key]  = array('total'=>0, 'sourceCode'=>$key, 'codeName' =>$val);
			}
			$data['total'] = 0;
		}
		
		return $data;
	}
	
	function numSuccessTrade()
	{
		$letter = array(1=>'D',2=>'O',3=>'N',4=>'T',5=>'B1',6=>'E1',7=>'A1',8=>'M',9=>'A2',10=>'Y',11=>'B2',12=>'E2');
		
		$sql = "SELECT count( sourceCode ) total, sourceCode
				FROM tbl_code_trade
				WHERE date( datetime ) >= '{$this->startdate}'
				AND date( datetime ) <= '{$this->enddate}'
				AND n_status =2
				GROUP BY sourceCode";
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if ($qData){
			
			foreach($qData as $val){
				// $data[$letter[$val['sourceCode']]] = $val['total'];
				
				$codeid[] = $val['sourceCode'];
				$data['get'][$val['sourceCode']] = $val;
			}
			
			foreach ($letter as $key => $val){
				if (!in_array($key, $codeid)) $diff[] = $key;
			}
			
			foreach ($diff as $val){
				$data['get'][$val] = array('total'=>0, 'sourceCode'=>$val);
			}
			
			ksort($data['get']);
			
			$data['total'] = 0;
			foreach ($data['get'] as $key => $val){
				$data['get'][$key]['codeName'] = $letter[$val['sourceCode']];
				$data['total'] += $val['total'];
			}
			
			// pr($data);
			// pr($diff);
		}else{
			foreach ($letter as $key => $val){
				$data['get'][$key]  = array('total'=>0, 'sourceCode'=>$key, 'codeName' =>$val);
			}
			$data['total'] = 0;
		}
		
		return $data;
	}
	
	function letterBeingTrade()
	{
		$letter = array(1=>'D',2=>'O',3=>'N',4=>'T',5=>'B1',6=>'E1',7=>'A1',8=>'M',9=>'A2',10=>'Y',11=>'B2',12=>'E2');
		
		$sql = "SELECT count( sourceCode ) total, sourceCode
				FROM tbl_code_trade
				WHERE date( datetime ) >= '{$this->startdate}'
				AND date( datetime ) <= '{$this->enddate}'
				AND n_status =0
				GROUP BY sourceCode";
		// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if ($qData){
			
			foreach($qData as $val){
				// $data[$letter[$val['sourceCode']]] = $val['total'];
				
				$codeid[] = $val['sourceCode'];
				$data['get'][$val['sourceCode']] = $val;
			}
			
			foreach ($letter as $key => $val){
				if (!in_array($key, $codeid)) $diff[] = $key;
			}
			
			foreach ($diff as $val){
				$data['get'][$val] = array('total'=>0, 'sourceCode'=>$val);
			}
			
			ksort($data['get']);
			
			$data['total'] = 0;
			foreach ($data['get'] as $key => $val){
				$data['get'][$key]['codeName'] = $letter[$val['sourceCode']];
				$data['total'] += $val['total'];
			}
			
			// pr($data);
			// pr($diff);
		}else{
			foreach ($letter as $key => $val){
				$data['get'][$key]  = array('total'=>0, 'sourceCode'=>$key, 'codeName' =>$val);
			}
			
			$data['total'] = 0;
		}
		
		return $data;
	}
	
	function numSingleLetter(){
	
		$sql = "SELECT COUNT( * ) num, tci.codeid, tcd.codename
				FROM tbl_code_inventory tci
				LEFT JOIN tbl_code_detail tcd ON tci.codeid = tcd.id
				WHERE tci.n_status IN (0,3) AND tci.userid NOT IN ({$this->apps->getadminemail()})
				GROUP BY tci.codeid ORDER BY tci.codeid";
				// pr($sql);
		$qData = $this->apps->fetch($sql,1);
		if(!$qData) return false;
		foreach($qData as $val){
			$data[$val['codeid']][$val['codename']] = $val['num'];		
		}
		
		return $data;
	}
	
	 function numEachLetterset(){
	
		$sql = "SELECT sm.id,name,last_name, email, (
					SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id 
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =1
					) AS D,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =2
					) AS O,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =3
					) AS N,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =4
					) AS T,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =5
					) AS B1,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =6
					) AS E1,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =7
					) AS A1,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =8
					) AS M,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =9
					) AS A2,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =10
					) AS Y,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =11
					) AS B2,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =12
					) AS E2
					FROM social_member sm 
					WHERE n_status = 1 
					AND sm.id NOT IN ({$this->apps->getadminemail()}) 
					AND email <> '' 
					AND email is not null
					";
		$qData = $this->apps->fetch($sql,1);
	
		$dontbeamaybe = array();
		$dontbea = array();
		$dontbe = array();
		$dont = array();
		$newarr = array();
		foreach($qData as $val){
			
			$newarr[$val['id']]['D'] =$val['D'];
			$newarr[$val['id']]['O'] =$val['O'];
			$newarr[$val['id']]['N'] =$val['N'];
			$newarr[$val['id']]['T'] =$val['T'];
				
			$newarr[$val['id']]['B1'] =$val['B1'];
			$newarr[$val['id']]['E1'] =$val['E1'];
				
			$newarr[$val['id']]['A1'] =$val['A1'];
				
			$newarr[$val['id']]['M'] =$val['M'];
			$newarr[$val['id']]['A2'] =$val['A2'];
			$newarr[$val['id']]['Y'] =$val['Y'];
			$newarr[$val['id']]['B2'] =$val['B2'];
			$newarr[$val['id']]['E2'] =$val['E2'];
			
			if(!in_array(0,$newarr[$val['id']])) $dontbeamaybe[$val['id']] = $val['id'];
		}
		$newarr = array();
		foreach($qData as $val){
			if(!array_key_exists($val['id'],$dontbeamaybe)){
				$newarr[$val['id']]['D'] =$val['D'];
				$newarr[$val['id']]['O'] =$val['O'];
				$newarr[$val['id']]['N'] =$val['N'];
				$newarr[$val['id']]['T'] =$val['T'];
					
				$newarr[$val['id']]['B1'] =$val['B1'];
				$newarr[$val['id']]['E1'] =$val['E1'];
					
				$newarr[$val['id']]['A1'] =$val['A1'];
				if(!in_array(0,$newarr[$val['id']])) $dontbea[$val['id']] = $val['id'];
			
			}
			
		}
		
		$newarr = array();
		$dontbeamaybedontbea = array_merge($dontbea,$dontbeamaybe);
		foreach($qData as $val){
			if(!array_key_exists($val['id'],$dontbeamaybedontbea)){
				$newarr[$val['id']]['D'] =$val['D'];
				$newarr[$val['id']]['O'] =$val['O'];
				$newarr[$val['id']]['N'] =$val['N'];
				$newarr[$val['id']]['T'] =$val['T'];
					
				$newarr[$val['id']]['B1'] =$val['B1'];
				$newarr[$val['id']]['E1'] =$val['E1'];
				
				if(!in_array(0,$newarr[$val['id']])) $dontbe[$val['id']] = $val['id'];
			}
			
		}
		
		$newarr = array();
		$dontbeamaybedontbeadontbe = array_merge($dontbe,$dontbeamaybedontbea);
		foreach($qData as $val){
			if(!array_key_exists($val['id'],$dontbeamaybedontbeadontbe)){
				$newarr[$val['id']]['D'] =$val['D'];
				$newarr[$val['id']]['O'] =$val['O'];
				$newarr[$val['id']]['N'] =$val['N'];
				$newarr[$val['id']]['T'] =$val['T'];
					
				if(!in_array(0,$newarr[$val['id']])) $dont[$val['id']] = $val['id'];
			}
						
		}
		
		$data['DONTBEAMAYBE'] = count($dontbeamaybe);
		$data['DONTBEA'] = count($dontbea);
		$data['DONTBE'] = count($dontbe);
		$data['DONT'] = count($dont);
		
		return $data;
	}
	
	function getMasterCode()
	{
		$sql = "SELECT id, codename FROM tbl_code_detail WHERE n_status = 1";
		$result = $this->apps->fetch($sql,1);
		if ($result){
			foreach ($result as $value){
				$data[$value['id']] = $value['codename'];
				// $dataID[] = $value['id'];
				// $codeName[] = $value['codename'];
			}
			
			return $result;
		}else{
			return false;
		}
	}
	
	/*
	function letterBeingTrade(){
		
		$getMasterCode = $this->getMasterCode();
		if ($getMasterCode){
		
			foreach ($getMasterCode as $value){
				$data[$value['id']] = $value['codename'];
				
			}
		}
		
		$sql = "SELECT detail.id, COUNT(trade.sourceCode) total FROM tbl_code_trade AS trade 
				LEFT JOIN tbl_code_detail AS detail 
				ON trade.sourceCode = detail.id 
				GROUP BY trade.sourceCode";
		$qData = $this->apps->fetch($sql,1);
		
		if ($qData){
		
		$typeDouble = array(9,11,12);
			$i = 1;
			foreach ($qData as $key => $value){
				$idTrade[] = $value['id'];
				$dataTrade[$value['id']] = $value['total'];
				
				if ($i != $value['id']){
					$qData[$key]['id'] = $i;
					$qData[$key]['total'] = 0;
				}
				$i++;
			}
			
			foreach ($data as $key => $value){
				
				if (in_array($key, $idTrade)){
					$hasil[$key] = $dataTrade[$key];
					// $hasil[$key]['code'] = $data[$key];
					
				}else{
					
					$hasil[$key] = 0;
					
				}
			}
			
			$i = 0;
			foreach ($hasil as $key => $value){
				$letter[$i]['id'] = $key;
				$letter[$i]['total'] = $value;
				$letter[$i]['code'] = $data[$key];
				$i++;
			}
		}
		
		// pr($letter);
		// $letter = array();
		// $letter['d'] = 0;
		// $letter['o'] = 0;
		// $letter['n'] = 0;
		// $letter['t'] = 0;
		// $letter['b'] = 0;
		// $letter['e'] = 0;
		// $letter['a'] = 0;
		// $letter['m'] = 0;
		// $letter['a1'] = 0;
		// $letter['y'] = 0;
		// $letter['b1'] = 0;
		// $letter['e1'] = 0;
		// foreach ($qData as $value){
			// $letter['d'] += $value['D'];
			// $letter['o'] += $value['O'];
			// $letter['n'] += $value['N'];
			// $letter['t'] += $value['T'];
			// $letter['b'] += $value['B1'];
			// $letter['e'] += $value['E1'];
			// $letter['a'] += $value['A1'];
			// $letter['m'] += $value['M'];
			// $letter['a1'] += $value['A2'];
			// $letter['y'] += $value['Y'];
			// $letter['b1'] += $value['B2'];
			// $letter['e1'] += $value['E2'];
		// }
		
		// pr($letter);
		return $letter;
		
	} 
	*/
	
	
	function letterperuser($limit=10,$all=false){
		$start = intval($this->apps->_p('start'));
				
		if($all){
			$qLimit = "  ";
		}else $qLimit = "LIMIT {$start},{$limit}";
		$sql = "SELECT name,last_name, email, (
					SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id 
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =1
					) AS D,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =2
					) AS O,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =3
					) AS N,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =4
					) AS T,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =5
					) AS B1,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =6
					) AS E1,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =7
					) AS A1,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =8
					) AS M,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =9
					) AS A2,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =10
					) AS Y,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =11
					) AS B2,
					(SELECT count( * ) total
					FROM tbl_code_inventory
					WHERE userid = sm.id
					AND DATE(datetimes) >= '{$this->startdate}' 
					AND DATE(datetimes) <= '{$this->enddate}' 
					AND codeid =12
					) AS E2
					FROM social_member sm 
					WHERE n_status = 1 
					AND sm.id NOT IN ({$this->apps->getadminemail()}) 
					AND email <> '' 
					AND email is not null 
					{$qLimit}
					";
		$qData = $this->apps->fetch($sql,1);
		
	 
		if($qData)return $qData;
		return false;
		
	} 
	
 
	function totalTrading(){
		
		$sql = "SELECT COUNT( * ) num FROM tbl_code_trade WHERE n_status = 0";
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);		
		if($qData){
			foreach ($qData as $key => $val){
					$qData = $val;
			}
		
		}
		
		return $val;
				
	}
	
	function mostTradeLetter(){
		
		$sql = "SELECT COUNT( sourceCode ) totalCode, tcd.codename
				FROM tbl_code_trade tct
				LEFT JOIN tbl_code_detail tcd ON tct.sourceCode = tcd.id
				GROUP BY tct.sourceCode
				ORDER BY totalCode DESC";
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);
		// pr($qData);
		return $qData;		
	
	}
	
	function succesTrade(){
	
		$sql = "SELECT COUNT( * ) num FROM tbl_code_trade WHERE n_status = 2";
		// $this->apps->open(1);
		$qData = $this->apps->fetch($sql,1);		
		// pr($sql);
		if($qData){
			foreach ($qData as $key => $val){
					$qData = $val;
			}
		
		}
		
		return $val;	
	
	}
	
	function getChartDataOf($searchData=null){
		
		if($searchData==null) return false;
		
		if(is_array($searchData)) {
			foreach($searchData as $val){
				$nuArr[] = "'{$val}'";
			}
			if($nuArr)	$searchData = implode(',',$nuArr);
			else return false;
		}
		
		$theactivity = "{$searchData}";
		
		/* get activity ID */
		$actionnamedata = $this->getactivitytype($theactivity);

		if($actionnamedata) {
			
			$activityID = implode(',',$actionnamedata['id']);
		}else $activityID = false;
			
		$sql = "SELECT count(*) total, DATE(date_time) dateformatonly  FROM tbl_activity_log WHERE action_id IN ({$activityId}) ORDER BY dateformatonly GROUP BY dateformatonly LIMIT {$start},{$limit}";

		$getChartDataOf[$searchData] = $this->apps->fetch($sql);
		
		//pr($getChartDataOf);
		exit;

	}

	function getactivitytype($dataactivity=null,$id=false){
			if($dataactivity==null)return false;
			if($id) $qAppender = " id IN ({$dataactivity}) ";
			else $qAppender = " activityName IN ({$dataactivity})  ";
			$theactivity = false;
			/* get activity  id */	
			$sql = " SELECT * FROM tbl_activity_actions WHERE {$qAppender} ";

			$qData = $this->apps->open(1);
				
			if($qData) {
				foreach($qData as $val){
					$theactivity['id'][$val['id']]=$val['id'];
					$theactivity['name'][$val['id']]=$val['activityName'];
					
				}
			}
			return $theactivity;
		}

}

?>