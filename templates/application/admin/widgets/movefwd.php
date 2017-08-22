<?php
class movefwd extends App{

	function beforeFilter(){
		global $LOCALE,$CONFIG;
	
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->activityHelper = $this->useHelper('activityHelper');
		$this->userHelper = $this->useHelper('userHelper');
		$this->newsHelper = $this->useHelper('newsHelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		$this->botHelper = $this->useHelper('botHelper');
		$this->messageHelper = $this->useHelper('messageHelper');
		
		
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_WEB']);
		$this->assign('locale', $LOCALE[1]);
		
		$this->imageType = array('image/jpeg','image/png','image/jpg','image/gif');
		$this->videoType = array("video/mpeg","video/mp4","video/quicktime");
		$this->imageSize = '20000000';
		$this->videoSize = '20000000';
	}
	
	function main() {
		$this->log('surf', 'movefwd');
		$movefwd = $this->contentHelper->getMovefwd();
		
		if ($movefwd){
			foreach ($movefwd as $key => $val){
				if ($key%2 == 0){
					$movefwd[$key]['number'] = 0;
				}else{
					$movefwd[$key]['number'] = 1;
				}
			}
			
			// pr($movefwd);
			
			$this->View->assign('movefwd', $movefwd);
		}
		
		$getUserhaslook = $this->userHelper->userlookpage('movefwd');
		if ($getUserhaslook){
			$this->View->assign('haslook', true);
		}else{
			$this->View->assign('haslook', false);
		}
		// $this->View->assign('movefwd_list',$this->setWidgets('movefwd_list'));
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/move-fwd.html');
	}
	
	function detail(){
		// pr($this->_g('id'));
		$this->View->assign('news_list',$this->setWidgets('news_list'));
		// widget detail
		$this->View->assign('news_detail',$this->setWidgets('news_detail'));
		
		$this->log('read article',"news_{$this->_g('id')}");
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/move-fwd-activity.html');
	}
	
	function activity() {
	
		global $CONFIG;
		
		
		$activityid = strip_tags(intval($this->_g('id')));
		
		if (!$activityid) sendRedirect("{$CONFIG['BASE_DOMAIN']}movefwd");
		
		$checkCurrentContent = $this->contentHelper->isPostMoveFwdExist($activityid);
		if (!$checkCurrentContent) sendRedirect("{$CONFIG['BASE_DOMAIN']}movefwd");
		
		
		$myaccount = $this->userHelper->getUserProfile();
		// pr($myaccount);exit;
		$getActivity = $this->contentHelper->getMovefwd($activityid);
		$postContent = $this->contentHelper->getMyPost($activityid);
		$participant = $this->contentHelper->listParticipant($activityid);
		$completeTask = $this->contentHelper->isCompleteChallenge($activityid);
		$moveforwardlist = $this->contentHelper->getMovefwd();
		$getLatestGallery = $this->contentHelper->getLatestGallery($activityid);
		
		$dataCode = $this->messageHelper->getMessage();
		$dataInbox = $this->messageHelper->getInbox();
		
		$catName = "";
		foreach ($moveforwardlist as $val){
			if ($val['id']==$activityid) $catName = $val['categoryname'];
			
		}
		$this->log('surf', "movefwd activity {$catName}");
		
		if($dataCode) {
		
			$this->assign('usermessage',count($dataCode));
			$this->assign('inboxcount',count($dataInbox));
		}else{
			$this->assign('usermessage',0);
			$this->assign('inboxcount',0);
		}
		
		// pr($participant);exit;
		$this->View->assign('myaccount',$myaccount);
		$this->View->assign('postContent',$postContent);
		$this->View->assign('activity',$getActivity);
		$this->View->assign('participant',$participant);
		$this->View->assign('completeTask',$completeTask);
		$this->View->assign('moveforwardlist',$moveforwardlist);
		$this->View->assign('getLatestGallery',$getLatestGallery);
		
		$this->View->assign('pagesid',$activityid);
		
		
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/move-fwd-activity.html');
	}
	
	function posts()
	{
		global $CONFIG;
		
		$postid = intval($this->_p('content_id'));
		$data = false;
		// pr($_FILES);
		if ($this->_p('submitpost')){
			
			if (isset($_FILES['postfoto']))
			{
				$data['result'] = false;
				$data['filename'] = false;
				if (isset($_FILES['postfoto']) && $_FILES['postfoto']['name']!=NULL) {
					$path = $CONFIG['LOCAL_PUBLIC_ASSET']."post/photo/";
					if (in_array($_FILES['postfoto']['type'],$this->imageType)){
						if (isset($_FILES['postfoto']) && $_FILES['postfoto']['size'] <= $this->imageSize) {
							$res = $this->uploadHelper->uploadThisImage($_FILES['postfoto'],$path,700,true,'fwd');
							if ($res['arrImage']!=NULL) {
								$data['result'] = true;
								$data['filename'] = $res['arrImage'];
								$data['nosave'] = false;
								$data['type'] = 1;
								if ($data['result']){
									$this->View->assign('status','4');
								}else{
									$this->View->assign('status','3');
								}
							}
						}else{
							$this->View->assign('status','2');
						}
					}else{
						$this->View->assign('status','1');
					}
				}
			}
		}
		
		
			
			if (isset($_FILES['postvideo']))
			{
				$data['result'] = false;
				$data['filename'] = false;
				if (isset($_FILES['postvideo']) && $_FILES['postvideo']['name']!=NULL) {
					$path = $CONFIG['LOCAL_PUBLIC_ASSET']."post/video/";
					if (in_array($_FILES['postvideo']['type'],$this->videoType)){
						if (isset($_FILES['postvideo']) && $_FILES['postvideo']['size'] <= $this->videoSize) {
							$res = $this->uploadHelper->uploadThisVideo($_FILES['postvideo'],$path,700,true);
							// pr($res);
							if ($res['arrVideo']!=NULL) {
								$data['result'] = true;
								$data['filename'] = $res['arrVideo'];
								$data['nosave'] = false;
								$data['type'] = 2;
								if ($data['result']){
									$this->View->assign('status','4');
								}else{
								
									$this->View->assign('status','3');
								}
							}
						}else{
							// echo 'ada1';
							$this->View->assign('status','2');
						}
					}else{
					// echo 'ada2';
						$this->View->assign('status','1');
					}
				}
			}
			
		// pr($data);exit;
		$saveStatus = $this->contentHelper->saveStatusMoveFwd($data);
		if ($saveStatus){
			print json_encode(array('status'=>true));
		}else{
			print json_encode(array('status'=>false));
		}
		
		exit;
		//sendRedirect("{$CONFIG['BASE_DOMAIN']}movefwd/activity/{$postid}");
		
	}
	
	function commentAjax()
	{
		if ($this->_p('comment')){
		
			$postid = intval($this->_p('postid'));
			$content = strip_tags($this->_p('content'));
			
			if ($postid<=0 or $content=="") {print json_encode(array('status'=>false)); exit;}
			
			$getLimitComment = $this->messageHelper->getLimitComment(2);
			if ($getLimitComment) {print json_encode(array('status'=>false,'msg'=>'Message Limit')); exit;}
			
			$saveComment = $this->contentHelper->saveMoveFwdCommand();
			
			$getComment = $this->contentHelper->getMoveFwdCommand($postid);
			// pr($getComment);
			if ($saveComment){
				// print json_encode(array('status'=>true, 'rec'=>$getComment, 'cid'=>$postid));
				print json_encode(array('status'=>true));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		exit;
	}
	
	function getParticipant()
	{
		
		if ($this->_p('nextlistplayer')){
			
			$cid = intval($this->_p('cid'));
			$getPlayer = $this->contentHelper->listParticipant($cid);
			// pr($getPlayer);
			if($getPlayer['res']){
				// pr($getPlayer);
				
					print json_encode(array('status'=>true, 'rec'=>$getPlayer['res'], 'total'=>$getPlayer['total']));
				
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('prevlistplayer')){
			
			$cid = intval($this->_p('cid'));
			$getPlayer = $this->contentHelper->listParticipant($cid);
			// pr($getPlayer);
			if($getPlayer['res']){
				// pr($getPlayer);
				
					print json_encode(array('status'=>true, 'rec'=>$getPlayer['res'], 'total'=>$getPlayer['total']));
				
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		exit;
	}
	
	function ajaxChallenge()
	{
		if ($this->_p('challenge')){
		
			$postid = intval($this->_p('postid'));
			
			if ($postid<=0) {print json_encode(array('status'=>false)); exit;}
			
			
			/*
			$getLimitChallenge = $this->contentHelper->challengeCount($postid);
			if ($getLimitChallenge['status']){
				print json_encode(array('status'=>false,'msg'=>'challenge not available')); 
				exit;
			}
			*/
			
			$isUserHas = $this->contentHelper->isUserExistChallenge($postid);
			
			$getTask = $this->contentHelper->getChallengeTask($postid);
			if ($isUserHas){
				
				$count = count($isUserHas);
				if ($count>0){
					print json_encode(array('status'=>true,'has'=>true,'rec'=>$getTask, 'cid'=>$postid));
					exit;
				}
			}
			// pr($isUserHas);
			// pr($getTask);
			// exit;
			// pr($getTask);
			if ($getTask){
				print json_encode(array('status'=>true, 'has'=>false, 'rec'=>$getTask, 'cid'=>$postid, 'c_left'=>0));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('choose')){
		
			$postid = intval($this->_p('postid'));
			
			if ($postid<=0) {print json_encode(array('status'=>false)); exit;}
			
			
			$getTask = $this->contentHelper->getChallengeTask($postid);
			// pr($getTask);
			if ($getTask){
				print json_encode(array('status'=>true, 'rec'=>$getTask, 'cid'=>$postid));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		if ($this->_p('preupload')){
		
			$postid = intval($this->_p('postid'));
			
			if ($postid<=0) {print json_encode(array('status'=>false)); exit;}
			
			
			$isExist = $this->contentHelper->isExistChallenge($postid,1);
			// pr($isExist);
			if($isExist) {print json_encode(array('status'=>false)); exit;}
			
			
			$getTask = $this->contentHelper->getChallengeTask($postid);
			// pr($getTask);
			if ($getTask){
				print json_encode(array('status'=>true, 'rec'=>$getTask, 'cid'=>$postid));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		
		exit;
	}
	
	function uploadDataChallenge()
	{
	
		// pr($_FILES);exit;
		global $CONFIG;
		if ($this->_p('token')){
			
			$bucketid = intval($this->_p('bucketid'));
			$taskid = intval($this->_p('taskid'));
			if (isset($_FILES['dataChallenge']))
			{
			
				$checkBeforeSubmit = $this->contentHelper->checkiftaskexist($bucketid,$taskid);
				if ($checkBeforeSubmit){print json_encode(array('status'=>false, 'msg'=>'already submit task')); exit;}
				
				$data['result'] = false;
				$data['filename'] = false;
				if (isset($_FILES['dataChallenge']) && $_FILES['dataChallenge']['name']!=NULL) {
					$path = $CONFIG['LOCAL_PUBLIC_ASSET']."moveForward/";
					if (in_array($_FILES['dataChallenge']['type'],$this->imageType)){
						if (isset($_FILES['dataChallenge']) && $_FILES['dataChallenge']['size'] <= $this->imageSize) {
							$res = $this->uploadHelper->uploadThisImage($_FILES['dataChallenge'],$path,700,true);
							if ($res['arrImage']!=NULL) {
								$data['result'] = true;
								$data['filename'] = $res['arrImage'];
								$data['nosave'] = false;
								$data['type'] = 1;
								if ($data['result']){
									
									$filename = $data['filename']['filename'];
									$saveChallenge = $this->contentHelper->saveChallenge($bucketid,$taskid,$filename);
									if ($saveChallenge){
										print json_encode(array('status'=>true, 'msg'=>'upload success', 'cid'=>$bucketid));
									}else{
										print json_encode(array('status'=>false, 'msg'=>'not store'));
									}
									
									
								}else{
									print json_encode(array('status'=>false, 'msg'=>'upload failed'));
								}
							}
						}else{
							print json_encode(array('status'=>false, 'msg'=>'big size'));
						}
					}else if (in_array($_FILES['dataChallenge']['type'],$this->videoType)){
						if (isset($_FILES['dataChallenge']) && $_FILES['dataChallenge']['size'] <= $this->videoSize) {
							$res = $this->uploadHelper->uploadThisVideo($_FILES['dataChallenge'],$path,700,true);
							if ($res['arrVideo']!=NULL) {
								$data['result'] = true;
								$data['filename'] = $res['arrVideo'];
								$data['nosave'] = false;
								$data['type'] = 2;
								if ($data['result']){
									
									$filename = $data['filename']['filename'];
									$saveChallenge = $this->contentHelper->saveChallenge($bucketid,$taskid,$filename,3);
									if ($saveChallenge){
										print json_encode(array('status'=>true, 'msg'=>'upload video success', 'cid'=>$bucketid));
									}else{
										print json_encode(array('status'=>false, 'msg'=>'not store video'));
									}
									
									
								}else{
									print json_encode(array('status'=>false, 'msg'=>'upload video failed'));
								}
							}
						}else{
							print json_encode(array('status'=>false, 'msg'=>'big size video'));
						}
					}else{
						print json_encode(array('status'=>false, 'msg'=>'unknown type video'));
					}
				}else{
					print json_encode(array('status'=>false, 'msg'=>'file video not defined'));
				}
			}else{
				print json_encode(array('status'=>false, 'msg'=>'file video not exist'));
			}
		}
		
		exit;
	}
	
	function setByPoint()
	{
		
		global $LOCALE;
		
		if ($this->_p('point')){
		
			$bucketid = intval($this->_p('postid'));
			
			if($bucketid<=0){
				print json_encode(array('status'=>false, 'msg'=>'invalid postid'));
				
			}else{
			
				$getPoint =  $this->userHelper->getUserPoint();
				
				
				$getPointChallenge = $this->contentHelper->challengePoint($bucketid);
				$point = $getPointChallenge['point'];
				if ($point){
					$msg ='sorry you don\'t have enough points to pledge';
					if (intval($getPoint['point']) < intval($point)){
						print json_encode(array('status'=>false, 'msg'=>$msg));
						exit;
						
					}else{
						$saveChallenge = $this->contentHelper->saveChallenge($bucketid,false,false,false,1,$point);
						if ($saveChallenge){
							$msg ="You pledged {$point} points";
							$this->log('bucketlist', ' complete challenge '.$getPointChallenge['title']);
							
							$userid = $this->user->id;
							sleep(1);
							$isCompleteAll = $this->contentHelper->allChallengeComplete($userid);
							if ($isCompleteAll){
								sleep(1);
								$this->botHelper->setPointByUser('CHALLENGE', 'challengecomplete',30,'second', $userid);
								sleep(1);
								$this->log('account', $LOCALE[1]['challengecomplete']);
							}
							
							print json_encode(array('status'=>true, 'msg'=>$msg, 'cid'=>$bucketid));
						}else{
							print json_encode(array('status'=>false, 'msg'=>$msg));
						}
					}
					
					
					
				}else{
					print json_encode(array('status'=>false, 'msg'=>'point challenge not available'));
				}
				
			}
		}
		
		
		exit;
	}
	
	
	function sendMessage()
	{
		
		
		$sendMessage = $this->messageHelper->sendMessage();
		if ($sendMessage){
			print json_encode(array('status'=>true));
		}else{
			print json_encode(array('status'=>false));
		}
		
		exit;
	}
	
	function participant()
	{
		
		if ($this->_p('nextlistplayer')){
			
			$contentid = intval($this->_p('contentid'));
			$nextlistplayer = $this->contentHelper->listParticipant($contentid);
			// pr($nextlistplayer);
			if ($nextlistplayer){
				print json_encode(array('status'=>true, 'rec'=>$nextlistplayer['res']));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		
		
		if ($this->_p('prevlistplayer')){
			
			$contentid = intval($this->_p('contentid'));
			$prevlistplayer = $this->contentHelper->listParticipant($contentid);
			// pr($nextlistplayer);
			if ($prevlistplayer){
				print json_encode(array('status'=>true, 'rec'=>$prevlistplayer['res']));
			}else{
				print json_encode(array('status'=>false));
			}
		}
		exit;
	}
}
?>