<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/gplus/apiClient.php";
include_once $ENGINE_PATH."Utility/gplus/contrib/apiPlusService.php";
include_once $ENGINE_PATH."Utility/gplus/contrib/apiOauth2Service.php";
class VikiHelper {
	protected $access_token;
	public function __construct($apps=null){
		global $VIKI,$logger;
		$this->logger = $logger;
		$this->appId = $VIKI['application_id'];
		$this->appSecret = $VIKI['application_secret'];
		$this->api_callback = $VIKI['callback'];
		$this->apps = $apps;
		$this->access_token = @$this->apps->session->getSession('viki_sesison','viki_data')->access_token;
		$this->init();	
	}
	private function init(){
		$this->logger->log("viki login");
		if(!$this->isLogin()){
			$this->logger->log("viki not login");
			$this->authenticate();
		}
	}
	public function authenticate(){
		$this->logger->log("viki auth");
		$api_endpoint = $this->api_callback."/oauth/token";
		$response = post_json($api_endpoint,array("grant_type"=>"client_credentials",
												  "client_id"=>$this->appId,
												  "client_secret"=>$this->appSecret));
		$rs = json_decode($response['response'],true);
		if(strlen($rs['access_token'])>0){
			$this->logger->log("viki success auth");
			$this->access_token = $rs['access_token'];
			$data = $rs;
			$this->apps->session->setSession('viki_sesison','viki_data',$data);
		}else $this->logger->log("viki not success auth");
	}
	public function isLogin(){
		if($this->has_token()){
			return true;
		}
	}
	public function has_token(){
		if(isset($rs)){
			if(strlen($rs['access_token'])>0){
				return true;
			}
		}
	}
	public function movies($genre_id){
		$this->logger->log("viki get movie");
		$api_endpoint = $this->api_callback."/api/v3/movies.json?genre={$genre_id}&access_token={$this->access_token}";
		$response = json_decode(curlGet($api_endpoint),true);
		return $response;
	}
	public function featured($genre_id){
		$api_endpoint = $this->api_callback."/api/v3/featured.json?genre={$genre_id}&access_token={$this->access_token}";
		$response = json_decode(curlGet($api_endpoint),true);
		return $response;
	}
	public function music_videos($genre_id){
		$api_endpoint = $this->api_callback."/api/v3/music_videos.json?genre={$genre_id}&access_token={$this->access_token}";
		$response = json_decode(curlGet($api_endpoint),true);
		return $response;
	}
	
}
?>