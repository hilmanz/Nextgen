<?php
class registrant extends ServiceAPI{

	
	function beforeFilter(){
	 
		$this->entourageHelper = $this->useHelper('entourageHelper');
	 	$this->deviceMopHelper = $this->useHelper('deviceMopHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));		
	}
	 
	function register(){
		GLOBAL $CONFIG;
		$success = false;
		
			
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."entourage/photo/";
			$img = false;
			if (isset($_FILES['img'])&&$_FILES['img']['name']!=NULL) {
				if (isset($_FILES['img'])&&$_FILES['img']['size'] <= 20000000) {
					$img = $this->uploadHelper->uploadThisImage($_FILES['img'],$path);
						
				} else {
					$success = false;
				}
			} else {
				$success = false;
			}
		
		if($img)$filename = $img['arrImage']['filename'];
		else $filename = false;
		
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."entourage/signature/";
			$signature = false;
			if (isset($_FILES['signature'])&&$_FILES['signature']['name']!=NULL) {
				if (isset($_FILES['signature'])&&$_FILES['signature']['size'] <= 20000000) {
					$signature = $this->uploadHelper->uploadThisImage($_FILES['signature'],$path);						
				} else {
					$success = false;
				}
			} else {
				$success = false;
			}
	
	
		if($signature)$signature = $signature['arrImage']['filename'];
		else $signature = false;
		
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."entourage/signatureba/";
			$signatureba = false;
			if (isset($_FILES['signatureba'])&&$_FILES['signatureba']['name']!=NULL) {
				if (isset($_FILES['signatureba'])&&$_FILES['signatureba']['size'] <= 20000000) {
					$signatureba = $this->uploadHelper->uploadThisImage($_FILES['signatureba'],$path);						
				} else {
					$success = false;
				}
			} else {
				$success = false;
			}
	
		if($signatureba)$signatureba = $signatureba['arrImage']['filename'];
		else $signatureba = false;
		
		
		$data = $this->entourageHelper->addEntourage($filename,$signature,$signatureba);
		if($data) $success = true;		
		else $success = false;
		
		return array("result"=>$success);
	}
	
	function checkemail(){
		
		// return false;
		
			$resultdata['result'] = 5;
			$resultdata['RegistrationID'] = "";
			$resultdata['FirstName'] = "";
			$resultdata['LastName'] = "";
			$resultdata['FullName'] = "";
			$resultdata['DateOfBirth'] = "";
			$resultdata['Email'] = "";
			$resultdata['GIIDType'] = "";
			$resultdata['GIIDNumber'] = "";
			$resultdata['Mobile'] ="";
			$resultdata['SpareText5'] = "";
			$resultdata['SpareText2'] = "";
			$resultdata['Gender'] = "";
			$resultdata['CityID'] = "";
			$resultdata['StateID'] = "";
			$resultdata['Brand1_ID'] = "";
			$resultdata['SubBrand1_ID'] = "";
			$resultdata['Brand2_ID'] = "";
			$resultdata['SubBrand2_ID'] = "";
			/* 	
			 "Gender": "M",
			"CityID": "103",
            "StateID": "10",
			"Brand1_ID": "22",
            "SubBrand1_ID": "50",           
            "Brand2_ID": "22",
            "SubBrand2_ID": "51", 
			*/
			$result = false;
			// $data = $this->entourageHelper->checkentourage();	
			$data['result'] = false;
			if($data['result']){
				$result = $data;
				$resultdata['result'] = 1;
				$resultdata['RegistrationID'] = $result['data']['registerid'];
				$resultdata['FirstName'] = $result['data']['name'];
				$resultdata['LastName'] = $result['data']['last_name'];
				$resultdata['FullName'] = $result['data']['name']." ".$result['data']['last_name'];
				$resultdata['DateOfBirth'] = $result['data']['birthday'];
				$resultdata['Email'] = $result['data']['email'];
				$resultdata['GIIDType'] = $result['data']['giidtype'];
				$resultdata['GIIDNumber'] = $result['data']['giidnumber'];
				$resultdata['Mobile'] = $result['data']['phone_number'];
				$resultdata['SpareText5'] = $result['data']['referrerbybrandemail'];
				$resultdata['SpareText2'] = @$result['data']['description'];
				$resultdata['Gender'] = $result['data']['sex'];
				$resultdata['CityID'] = $result['data']['city'];
				$resultdata['StateID'] = $result['data']['state'];
				$resultdata['Brand1_ID'] = $result['data']['brand1'];
				$resultdata['SubBrand1_ID'] = $result['data']['brand1ref'];
				$resultdata['Brand2_ID'] =$result['data']['brandsub1'];
				$resultdata['SubBrand2_ID'] =$result['data']['brandsub1ref'];					
			}else {
				$dataemail = $this->deviceMopHelper->searchProfileUser();	
// pr($dataemail);				
				if($dataemail) {
					if($dataemail['result']==1){
					 
						$lastindexemail = count($dataemail['data'])-1;
						$lastindexemail = 0;
						$GIIDNumber = $dataemail['data'][$lastindexemail]['GIIDNumber'];
						$this->Request->setParamPost("giidnumber",$GIIDNumber);
						// pr($this->_request('giidnumber'));
						$data = $this->deviceMopHelper->AdminGetProfileonGiid();
// pr($data);						
						if($data) {
						
							if($data['result']==1){
								$result = $data;
								$resultdata['result'] = 1;
								$lastindex = count($data['data'])-1;
								$resultdata['RegistrationID'] = $data['data'][$lastindex]['RegistrationID'];
								$resultdata['FirstName'] = $data['data'][$lastindex]['FirstName'];
								$resultdata['LastName'] = $data['data'][$lastindex]['LastName'];
								$resultdata['FullName'] = $data['data'][$lastindex]['FirstName']." ".$data['data'][$lastindex]['LastName'];
								$dateofbirth = explode('/',$data['data'][$lastindex]['DateOfBirth']);
								$DOB = $data['data'][$lastindex]['DateOfBirth'];
								if($dateofbirth){
									if(strlen($dateofbirth[0])==1)$dateofbirth[0]="0{$dateofbirth[0]}";
									if(strlen($dateofbirth[1])==1)$dateofbirth[1]="0{$dateofbirth[1]}";
									$DOB = $dateofbirth[2]."-".$dateofbirth[0]."-".$dateofbirth[1];
								
								}
								$resultdata['DateOfBirth'] = $DOB;
								$resultdata['Email'] = (string)$data['data'][$lastindex]['Email'];
								if($data['data'][$lastindex]['GIIDType']==1)$data['data'][$lastindex]['GIIDType']="K";
								if($data['data'][$lastindex]['GIIDType']==2)$data['data'][$lastindex]['GIIDType']="S";
								$resultdata['GIIDType'] = $data['data'][$lastindex]['GIIDType'];
								$resultdata['GIIDNumber'] = $data['data'][$lastindex]['GIIDNumber'];
								$resultdata['Mobile'] = (string)$data['data'][$lastindex]['MobilePhoneNumber'];
								$resultdata['SpareText5'] =  (string)$data['data'][$lastindex]['SpareText5'];	
								$resultdata['SpareText2'] =  (string)$data['data'][$lastindex]['SpareText2'];	
								$resultdata['Gender'] =  (string)$data['data'][$lastindex]['Gender'];
								$resultdata['CityID'] =  (string)$data['data'][$lastindex]['CityID'];
								$resultdata['StateID'] = (string)$data['data'][$lastindex]['StateID'];
								$resultdata['Brand1_ID'] = (string)$data['data'][$lastindex]['Brand1_ID'];
								$resultdata['SubBrand1_ID'] = (string)$data['data'][$lastindex]['SubBrand1_ID'];
								$resultdata['Brand2_ID'] = (string)$data['data'][$lastindex]['Brand2_ID'];
								$resultdata['SubBrand2_ID'] =  (string)$data['data'][$lastindex]['SubBrand2_ID'];
								 
							}
						}
						if($data['result']==99){
							$resultdata['result'] = 99;
						}
					}
					if($dataemail['result']==99){
						$resultdata['result'] = 99;
					}
					
				}
			}
			if(!$result) {
				$result['result'] = false;
				$result['data'] = array();
				$resultdata['Email'] = strip_tags($this->_p('email'));
				
			} 
			return $resultdata;
	}
	
	function checkgiid(){
			// return false;
			$result = false;
			
			$resultdata['result'] = 5;
			$resultdata['RegistrationID'] = "";
			$resultdata['FirstName'] = "";
			$resultdata['LastName'] = "";
			$resultdata['FullName'] ="";
			$resultdata['DateOfBirth'] = "";
			$resultdata['Email'] = "";
			$resultdata['GIIDType'] = "";
			$resultdata['GIIDNumber'] = "";
			$resultdata['Mobile'] ="";
			$resultdata['SpareText5'] = "";
			$resultdata['SpareText2'] = "";
			$resultdata['Gender'] = "";
			$resultdata['CityID'] = "";
			$resultdata['StateID'] = "";
			$resultdata['Brand1_ID'] = "";
			$resultdata['SubBrand1_ID'] = "";
			$resultdata['Brand2_ID'] = "";
			$resultdata['SubBrand2_ID'] = "";
			
			// $data = $this->entourageHelper->checkentourage();	
				$data['result'] = false;
			if($data['result']) {
				$result = $data;
				$resultdata['result'] = 1;
				$resultdata['RegistrationID'] = $result['data']['registerid'];
				$resultdata['FirstName'] = $result['data']['name'];
				$resultdata['LastName'] = $result['data']['last_name'];
				$resultdata['FullName'] = $result['data']['name']." ".$result['data']['last_name'];
				$resultdata['DateOfBirth'] = $result['data']['birthday'];
				$resultdata['Email'] = $result['data']['email'];
				$resultdata['GIIDType'] = $result['data']['giidtype'];
				$resultdata['GIIDNumber'] = $result['data']['giidnumber'];
				$resultdata['Mobile'] = $result['data']['phone_number'];
				$resultdata['SpareText5'] = $result['data']['referrerbybrandemail'];
				$resultdata['SpareText2'] = @$result['data']['description'];
				$resultdata['Gender'] = $result['data']['sex'];
				$resultdata['CityID'] = $result['data']['city'];
				$resultdata['StateID'] = $result['data']['state'];
				$resultdata['Brand1_ID'] = $result['data']['brand1'];
				$resultdata['SubBrand1_ID'] = $result['data']['brand1ref'];
				$resultdata['Brand2_ID'] =$result['data']['brandsub1'];
				$resultdata['SubBrand2_ID'] =$result['data']['brandsub1ref'];		
			}else {
					$data = $this->deviceMopHelper->AdminGetProfileonGiid();					 
					if($data) {
					
						if($data['result']==1){
							$result = $data;
							$resultdata['result'] = 1;
							$lastindex = count($data['data'])-1;
							$resultdata['RegistrationID'] = $data['data'][$lastindex]['RegistrationID'];
							$resultdata['FirstName'] = $data['data'][$lastindex]['FirstName'];
							$resultdata['LastName'] = $data['data'][$lastindex]['LastName'];
							$resultdata['FullName'] = $data['data'][$lastindex]['FirstName']." ".$data['data'][$lastindex]['LastName'];
							$dateofbirth = explode('/',$data['data'][$lastindex]['DateOfBirth']);
							$DOB = $data['data'][$lastindex]['DateOfBirth'];
							if($dateofbirth){
								if(strlen($dateofbirth[0])==1)$dateofbirth[0]="0{$dateofbirth[0]}";
								if(strlen($dateofbirth[1])==1)$dateofbirth[1]="0{$dateofbirth[1]}";
								$DOB = $dateofbirth[2]."-".$dateofbirth[0]."-".$dateofbirth[1];
							
							}
							$resultdata['DateOfBirth'] = $DOB;
							$resultdata['Email'] = (string)$data['data'][$lastindex]['Email'];
							if($data['data'][$lastindex]['GIIDType']==1)$data['data'][$lastindex]['GIIDType']="K";
							if($data['data'][$lastindex]['GIIDType']==2)$data['data'][$lastindex]['GIIDType']="S";
							$resultdata['GIIDType'] = $data['data'][$lastindex]['GIIDType'];
							$resultdata['GIIDNumber'] = $data['data'][$lastindex]['GIIDNumber'];
							$resultdata['Mobile'] = (string)$data['data'][$lastindex]['MobilePhoneNumber'];
							$resultdata['SpareText5'] =  (string)$data['data'][$lastindex]['SpareText5'];	
							$resultdata['SpareText2'] =  (string)$data['data'][$lastindex]['SpareText2'];	
							$resultdata['Gender'] =  (string)$data['data'][$lastindex]['Gender'];
							$resultdata['CityID'] =  (string)$data['data'][$lastindex]['CityID'];
							$resultdata['StateID'] = (string)$data['data'][$lastindex]['StateID'];
							$resultdata['Brand1_ID'] = (string)$data['data'][$lastindex]['Brand1_ID'];
							$resultdata['SubBrand1_ID'] = (string)$data['data'][$lastindex]['SubBrand1_ID'];
							$resultdata['Brand2_ID'] = (string)$data['data'][$lastindex]['Brand2_ID'];
							$resultdata['SubBrand2_ID'] =  (string)$data['data'][$lastindex]['SubBrand2_ID'];
							 
						}
						if($data['result']==99){
							$resultdata['result'] = 99;
						}
					}
			}
			if(!$result) {
				$result['result'] = false;
				$result['data'] = array();
				$resultdata['GIIDNumber'] = strip_tags($this->_p('giidnumber'));
				
			}
			return $resultdata;
	}
	
	function getCity(){
		$result['result'] = false;		
		$result['data'] = array();
		$data = $this->entourageHelper->citylists();	
		if($data) $result['result'] = true;		
		$result['data'] = $data;
		return $result;
		
	}
	
	function getBrandPref(){
		$result['result'] = false;		
		$result['data'] = array();
		$data = $this->entourageHelper->brandpreflists();	
		if($data) $result['result'] = true;		
		$result['data'] = $data;
		return $result;
	}
	
	
}
?>
