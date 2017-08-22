<?php
class login extends ServiceAPI{
		
	function beforeFilter(){
		$this->loginHelper = $this->useHelper('loginHelper');
		$this->deviceMopHelper = $this->useHelper('deviceMopHelper');
		$this->BEATServHelper = $this->useHelper('BEATServHelper');
		
	}
	
	 
	
	function beat(){
	
		global $CONFIG,$logger;
		
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign('basedomain',$basedomain);
			 
			$logger->log(strip_tags($this->_request('username')));
			if($this->_p('username')&&$this->_p('password')){
				 
				$res = $this->loginHelper->beatloginsession();
				if($res){
				 
					if(isset($res->status)){	
								$this->log('login');		 
								return array(
								'status'=>true,
								'code'=>$res->code, 
								'msg'=>'welcomes'
							 
								);
					}else{
							return array(
							'status'=>false,
							'code'=>$res->code,
							'msg'=>$res->msg
							);
					}
				}
			}
				print  $this->error_404();exit;
			
	
		
	}
	
	function developmentmop(){
	
		$user = $this->deviceMopHelper->loginAdminMop(); 
		if(!$user) {
			$data['status'] = "0";
			$data['SessionID'] = "0";		
			$data['msg'] = "you are not registered";		
			return $data;
		}else{
			$data['status'] = (String)intval($user->Result[0]);
			$data['SessionID'] = (String)intval($user->SessionID[0]);		
			
			$data['msg'] = "wrong credential";				
			if(intval($user->Result[0])==1) {
				$data['msg'] = "welcome";	
				$res = $this->loginHelper->beatmoploginsession();
				if($res){
				 
					if(isset($res->status)){	
								$this->log('login');		 
								return array(
								'status'=>true,
								'code'=>$res->code, 
								'msg'=>'welcomes'
							 
								);
					}else{
							return array(
							'status'=>false,
							'code'=>$res->code,
							'msg'=>$res->msg
							);
					}
				}
				
			}
			if(intval($user->Result[0])==2) $data['msg'] = "Whoops! Please check your credential again or please contact support@ba-space.com";	
			if(intval($user->Result[0])==3) $data['msg'] = "Whoops! Please check your credential again or please contact support@ba-space.com";	
			if(intval($user->Result[0])==4) $data['msg'] = "your account blocked";	
			if(intval($user->Result[0])==100) $data['msg'] = "something wrong on MOP";	
			if(intval($user->Result[0])==99) $data['msg'] = "your session MOP expired";	
			return $data;
		}
	
		
	}
	
	function mopreals(){
	
		$user = $this->deviceMopHelper->loginAdminMop(); 
		if(!$user) {
			$data['status'] = "0";
			$data['SessionID'] = "0";		
			$data['msg'] = "you are not registered";		
			return $data;
		}else{
			$data['status'] = (String)intval($user->Result[0]);
			$data['SessionID'] = (String)intval($user->SessionID[0]);		
			
			$data['msg'] = "wrong credential";				
			if(intval($user->Result[0])==1) {
				$data['msg'] = "welcome";	
				$res = $this->loginHelper->beatmoploginsession();
				if($res){
				 
					if(isset($res->status)){
						if($res->status){
								$this->log('login');		 
								return array(
								'status'=>true,
								'code'=>$res->code, 
								'msg'=>'welcomes'
							 
								);
						}else{
								$this->log('login');		 
								return array(
								'status'=>false,
								'code'=>2, 
								'msg'=>'Whoops! Please check your credential again or please contact support@ba-space.com'
							 
								);
						}
					}else{
							return array(
							'status'=>false,
							'code'=>$res->code,
							'msg'=>$res->msg
							);
					}
				}
				
			}
			if(intval($user->Result[0])==2) $data['msg'] = "Whoops! Please check your credential again or please contact support@ba-space.com";	
			if(intval($user->Result[0])==3) $data['msg'] = "Whoops! Please check your credential again or please contact support@ba-space.com";	
			if(intval($user->Result[0])==4) $data['msg'] = "your account blocked";	
			if(intval($user->Result[0])==100) $data['msg'] = "something wrong on MOP";	
			if(intval($user->Result[0])==99) $data['msg'] = "your session MOP expired";	
			return $data;
		}
	
		
	}
	
	function mop(){
	
		global $CONFIG,$logger;
	
		$basedomain = $CONFIG['BASE_DOMAIN'];
		$this->assign('basedomain',$basedomain);
	 
			if($this->_p('username')&&$this->_p('password')){
			 
				$res = $this->loginHelper->loginSession();
				if($res){
							// $users = $this->loginHelper->getProfile();
							// pr($users);
							return array(
							'status'=>true, 
							'code'=>"1", 
							'msg'=>'welcomes' 
							);
				}else{
						return array(
						'status'=>false, 
						'code'=>"0", 
						'msg'=>'Whoops! Please check your credentials and try again'
						);
				}
			}
		
				print  $this->error_404();exit;
			
	
		
	}
	
	function profile(){
		return 	$checkProfile = $this->BEATServHelper->checkProfile();
							
	}
	
	function brandtheme(){
		$themes = strip_tags($this->_p('themes'));
		
		if($themes=='amild')$brand=4;
		if($themes=='marlboro')$brand=5;
		 
		global $CONFIG;
		$data =array();
		$sql ="SELECT * FROM customize_templates WHERE brand={$brand} AND n_status = 1 ";
		$qData = $this->fetch($sql,1);
		// pr($sql);
		if($qData) {
			 
				
			foreach($qData as $key => $val){
			 
				
				if($val['color']) $data[$val['sections']] = $val['color'];
				if($val['size']) $data[$val['sections']] = $val['size'];
				
				if($val['images']){
				
					if(!is_file("{$CONFIG['LOCAL_PUBLIC_ASSET']}content/{$val['sections']}/{$val['images']}")) $val['images'] = false;
				
					if($val['images']) $qData[$key]['images_full_path'] = $CONFIG['BASE_DOMAIN_PATH']."public_assets/content/{$val['sections']}/". $val['images'];
					else $qData[$key]['images_full_path']= $CONFIG['BASE_DOMAIN_PATH']."public_assets/content/{$val['sections']}/default.jpg";					
					
					$data[$val['sections']] =$qData[$key]['images_full_path'];
				}
			}
			$data['id']=(string)$brand;
			return $data;
		
		}else return false;
		
		
	}
	
	function brandlist(){
	
			$data[0]['brandid'] = "4";
			$data[0]['brandname'] = "amild";
			$data[1]['brandid'] ="5" ;
			$data[1]['brandname'] ="marlboro";
			
			return $data;
	}
}
?>