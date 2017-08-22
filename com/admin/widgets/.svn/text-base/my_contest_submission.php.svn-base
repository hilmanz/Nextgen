<?php
class my_contest_submission{
	
	function __construct($apps=null){		
			$this->apps = $apps;
			global $LOCALE,$CONFIG;
			$this->apps->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
			$this->apps->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
			$this->apps->assign('locale',$LOCALE[$this->apps->lid]);
	}

	function main(){
	
		$data = $this->apps->contentHelper->getContestSubmission($this->apps->user->id);
	
		$this->apps->assign('my_contest_submission',$data);
		return $this->apps->View->toString(TEMPLATE_DOMAIN_ADMIN ."widgets/my-contest-submission.html");
	}


}


?>