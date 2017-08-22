<?php 
class WizardApp extends BasicView{
	var $title; //wizard title
	var $id; //wizard session id
	var $steps; //array of steps
	var $current;//current step
	var $requestID;
	var $invoker;
	var $handlerName;
	var $wizard_type;
	var $WIZARD_SAVE_WITHOUT_CONTINUE;
	function __construct(){
		parent::BasicView();
	}
	function init($title,$requestID,$currentStep,$invoker,$type="WIZARD_CREATE"){
		
		$id = md5(date("Ymdhis"));
		$this->current = $currentStep;
		$this->invoker = $invoker;
		$this->title = $title;
		$this->requestID = $requestID;
		$this->steps = array();
		$this->wizard_type = $type;
		$this->WIZARD_SAVE_WITHOUT_CONTINUE = false;
	}
	function addStep($description,$view,$callBack,$onSuccess,$onFailure,$onSkip){
		//$this->current = 0;
		$this->steps[] = array("description"=>$description,"view"=>$view,"callback"=>$callBack,"onSuccess"=>$onSuccess,"onFailure"=>$onFailure,"onSkip"=>$onSkip);
	}
	function T_GOTO($step){
		$this->current = $step-1;
	}
	function nextStep(){
		$this->current++;
	}
	/**
	 * 
	 * mencek apakah callback step yg sedang aktif itu sudah pernah dieksekusi apa belum.
	 * ini untuk memastikan kalo user mencet tombol F5, content yg sudah di submit ke db, gak disubmit lagi. cukup 1x.
	 */
	function isAlreadyExecuted(){
		
		if($_SESSION['WZ_SESSION_STATUS'][$this->current] == '1'){
			return true;
		}
	}
	function execute(){
		//print_r($_SESSION);
		//print_r($this->steps);
		//print $this->invoker;
		if($_POST['wz_action']=="continue"){
			$callback = $this->steps[$this->current]['callback'];
			if($this->invoker->$callback()){
				//last step ?
				if($this->current==sizeof($this->steps)-1){
					//yes
					return $this->invoker->onFinished($_REQUEST['do']);
				}else{
					//no
					//onSuccess
					$onSuccess = $this->steps[$this->current]['onSuccess'];
					$_SESSION['WZ_SESSION_STATUS'][$this->current] = '1';
					$this->invoker->$onSuccess();
				}
			}else{
				$onFailure = $this->steps[$this->current]['onFailure'];
				$this->invoker->$onFailure($this->current+1);
			}
		}else if($_POST['wz_action']=="save"){
			$callback = $this->steps[$this->current]['callback'];
			if($this->invoker->$callback()){
				//do something here
				$onSuccess = $this->steps[$this->current]['onSuccess'];
				$_SESSION['WZ_SESSION_STATUS'][$this->current] = '1';
				$this->saveOnly(true);
				$this->invoker->$onSuccess();
			}else{
				$onFailure = $this->steps[$this->current]['onFailure'];
				$this->invoker->$onFailure($this->current+1);
			}
		}else if($_POST['wz_action']=="finish"){
			$_SESSION['WZ_SESSION_STATUS'] = null;
			return $this->invoker->onFinished($_REQUEST['do']);
		}else if($_POST['wz_action']=="skip"){
				if($this->steps[$this->current]['onSkip']!=null){
					if($this->current==sizeof($this->steps)-1){
						//yes
						return $this->invoker->onFinished($_REQUEST['do']);
					}
						$onSkip = $this->steps[$this->current]['onSkip'];
						$this->invoker->$onSkip($this->current+1);
					
				}
			
		}else{
			//do nothing
		}
		
		
		//print "hoho";
		return $this->showPage();
		
	}
	function flushSession(){
		$_SESSION['WZ_SESSION_STATUS'][$this->current] = "0";
	}
	function saveOnly($f){
		
		$this->WIZARD_SAVE_WITHOUT_CONTINUE = $f;
	}
	function notify($msg){
		$this->assign("WIZARD_NOTIFY","1");
		$this->assign("WIZARD_ERROR_MSG",$msg);
	}
	function error($msg){
		$this->assign("WIZARD_ERROR_MSG",$msg);
	}
	function showPage(){
		
		if($this->current==null){$this->current=0;}
		$this->assign("WIZARD_NAME",$this->title);
		$this->assign("WIZARD_STEP",$this->current+1);
		$this->assign("WIZARD_TOTAL_STEP",sizeof($this->steps));
		$this->assign("WIZARD_STEP_DESCRIPTION",$this->steps[$this->current]['description']);
		$this->assign("requestID",$this->requestID);
		$this->assign("do",$_REQUEST['do']);
		$this->assign("step",$this->current);
		
		
		$step_view = $this->steps[$this->current]['view'];
		$this->assign("CONTENT",$this->invoker->$step_view());
		$this->assign("WIZARD_SAVE_WITHOUT_CONTINUE",$this->WIZARD_SAVE_WITHOUT_CONTINUE);
		return $this->toString("Wizard/container.html");
	}
	function isSaveOnly(){
		return $this->WIZARD_SAVE_WITHOUT_CONTINUE;
	}
}
?>