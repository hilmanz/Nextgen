<?php
/**
 * @author duf
 *
 */
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Twitter/tmhOAuth.php";
include_once $ENGINE_PATH."Utility/Twitter/tmhUtilities.php";

class twitter_service extends API{
	var $user_id;
	var $tmhOAuth;
	var $oauth;
	function init(){
		global $TWITTER;
		
		$this->tmhOAuth = new tmhOAuth(array(
							  'consumer_key'    => $TWITTER['CONSUMER_KEY'],
							  'consumer_secret' =>  $TWITTER['CONSUMER_SECRET']
							));
		$this->user_id = $this->access_info['user_id'];
	}
	function foo(){
		
		//$response = json_encode(array("status"=>1));
		return $this->toJson(1,'message',array("foo"=>"bar","name"=>"hello"));	
	}
	function login(){
		$this->init();
		$this->open(0);
		$user = $this->get_twitter_info();
		$this->close();
		if($user['token']!=NULL&&$user['secret']!=NULL){
			return $this->toJson(1,'login ok',array("user"=>$user['twitter_id']));	
		}else{
			$login_url = $this->request_login_link();
			return $this->toJson(0,'need to login',array("link"=>$login_url,"c"=>urlencode64(serialize($this->oauth))));
		}
	}
	function authorize(){
		$this->init();
		
		$request_code = unserialize(urldecode64($this->Request->getParam("c")));
		
		$this->tmhOAuth->config['user_token']  = $request_code['oauth_token'];
		$this->tmhOAuth->config['user_secret'] = $request_code['oauth_token_secret'];
		
		$code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url('oauth/access_token', ''), 
										array(
										'oauth_verifier' => $this->Request->getParam('oauth_verifier')
									  	)
	  	);
		
		if ($code == 200) {
			$access_token = $this->tmhOAuth->extract_params($this->tmhOAuth->response['response']);
			$twitter_id = $access_token['screen_name'];
			$token = $access_token['oauth_token'];
			$secret = $access_token['oauth_token_secret'];
			
			if($this->updateTwitterAccess($this->user_id,$twitter_id,$token,$secret)){
				return $this->toJson(1,'authorization complete',array("twitter_id"=>$twitter_id));
			}else{
				return $this->toJson(0,'update credential failed.',null);
			}
			//unset($_SESSION['oauth']);
		}
		return $this->toJson(0,'Authorization failed',null);
	}
	
	function get_twitter_info(){
		$this->init();
		$sql = "SELECT * FROM axis.tbl_user_twitter WHERE user_id={$this->user_id} LIMIT 1";
		
		$rs = $this->fetch($sql);
		return $rs;
	}
	function request_login_link(){
		global $TWITTER;
		
   		$callback = isset($_REQUEST['oob']) ? 'oob' : $TWITTER['LOGIN_CALLBACK'];
   		$params = array(
    		'oauth_callback'=> $callback,
   			'x_auth_access_type'=>'write'
  		);
		$code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url('oauth/request_token', ''), $params);
		
	  	if ($code == 200) {
		  //berhasil dapet access token
	    	$_SESSION['oauth'] = $this->tmhOAuth->extract_params($this->tmhOAuth->response['response']);
	    	$this->oauth = $_SESSION['oauth'];
	    	$method = 'authenticate';
	    	$force  = '';
	    	$authurl = $this->tmhOAuth->url("oauth/{$method}", '') .  "?oauth_token={$_SESSION['oauth']['oauth_token']}{$force}";
	    	return $authurl;
	  	} else {
	    	return "";
	  	}
	}
	function updateTwitterAccess($user_id,$twitter_id,$token,$secret){
		$sql = "INSERT INTO axis.tbl_user_twitter 
				(user_id, twitter_id, token, secret, authorized_date)
				VALUES
				({$user_id}, '{$twitter_id}', '{$token}', '{$secret}', NOW())
				ON DUPLICATE KEY
				UPDATE
				token = VALUES(token),
				secret = VALUES(secret),
				twitter_id = VALUES(twitter_id),
				authorized_date = VALUES(authorized_date);";
		$this->open(0);
		$q = $this->query($sql);
		$sql = "INSERT IGNORE INTO axis.job_twitter
				(twitter_id,token,secret,submit_date)
				VALUES
				('{$twitter_id}','{$token}','{$secret}',NOW());";
		$q = $this->query($sql);
		$this->close();
		return $q;
	}
	function get_tweets(){
		$this->init();
		$limit = 20; //limit rows per page
		$this->open(0);
		$twitter = $this->get_twitter_info();
		$page = intval($this->Request->getParam("page"));
		
		//define page offset
		if($page==0){$page=1;}
		$start = ($page-1)*$limit;
		
		//the query
		$twitter_id = mysql_escape_string($twitter['twitter_id']);
		$sql = "SELECT * FROM axis.tbl_twitter 
				WHERE twitter_id='{$twitter_id}' 
				AND flag=1 LIMIT {$start},{$limit}";
		
		$rs = $this->fetch($sql,1);
		$this->close();
		
		//response
		return $this->toJson(1,'tweets',$rs);
	}
	function remove_tweet(){
		$this->init();
		$this->open(0);
		$twitter = $this->get_twitter_info();
		$this->close();
		if($twitter['token']!=null && $twitter['secret']!=null){
			$this->tmhOAuth->config['user_token']  = $twitter['token'];
			$this->tmhOAuth->config['user_secret'] = $twitter['secret'];
			$id = $this->Request->getParam("id");
			if(strlen($id)>8){
				$code = $this->tmhOAuth->request('POST', $this->tmhOAuth->url("1/statuses/destroy/{$id}"));
				if($code==200){
					//flag deleted
					//$this->flag_deleted_tweet($this->user_id,$twitter['twitter_id'],$id);
					return $this->toJson(1,'the tweet has been deleted successfully',null);
				}else{
					return $this->toJson(0,'tweet not found',null);
				}
			}else{
				return $this->toJson(0,'Cannot remove tweet. u need to specify the tweet id',null);
			}
		}
		return $this->toJson(0,'unauthorized access',null);
	}
	
	function flag_deleted_tweet($user_id,$twitter_id,$deleted_id){
		$this->open(0);
		$sql = "DELETE FROM axis.tbl_twitter WHERE twitter_id='{$twitter_id}' AND feed_id_str='{$deleted_id}'";
		$q = $this->query($sql);
		$this->close();
		return $q;
	}
}
?>