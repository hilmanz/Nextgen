<?php
@include_once "locale.inc.php";

$CONFIG['LOG_DIR'] = "../logs/";
$GLOBAL_PATH = "../";
$APP_PATH = "../com/";
$ENGINE_PATH = "../engines/";
$WEBROOT = "../public_html/";

error_reporting(E_ALL);
//set aplikasi yang digunakan
define('APPLICATION','application');
define('COORPORATE_APPS','coorporate_apps');
define('MOBILE_APPS','mobile');
define('WAP_APPS','wap_apps'); 
define('DASHBOARD_APPS','dashboard'); 

define('WIDGET_DOMAIN_WEB',APPLICATION."/widgets/");
define('WIDGET_DOMAIN_COORPORATE',COORPORATE_APPS."/widgets/");
define('WIDGET_DOMAIN_MOBILE',MOBILE_APPS."/widgets/");
define('WIDGET_DOMAIN_WAP',WAP_APPS."/widgets/"); //new
define('WIDGET_DOMAIN_DASHBOARD',DASHBOARD_APPS."/widgets/"); //new

define('HELPER_DOMAIN_WEB',APPLICATION."/helper/");
define('HELPER_DOMAIN_COORPORATE',COORPORATE_APPS."/helper/");
define('HELPER_DOMAIN_MOBILE',MOBILE_APPS."/helper/");
define('HELPER_DOMAIN_WAP',WAP_APPS."/helper/"); //new
define('HELPER_DOMAIN_DASHBOARD',DASHBOARD_APPS."/helper/"); //new

define('MODULES_DOMAIN_WEB',$APP_PATH.APPLICATION."/modules/");
define('MODULES_DOMAIN_COORPORATE',$APP_PATH.COORPORATE_APPS."/modules/");
define('MODULES_DOMAIN_MOBILE',$APP_PATH.MOBILE_APPS."/modules/");
define('MODULES_DOMAIN_WAP',$APP_PATH.WAP_APPS."/modules/"); //new
define('MODULES_DOMAIN_DASHBOARD',$APP_PATH.DASHBOARD_APPS."/modules/"); //new

define('TEMPLATE_DOMAIN_WEB',APPLICATION."/web/");
define('TEMPLATE_DOMAIN_COORPORATE',APPLICATION."/coorporate/");
define('TEMPLATE_DOMAIN_MOBILE',APPLICATION."/mobile/");
define('TEMPLATE_DOMAIN_WAP',APPLICATION."/wap/"); //new
define('TEMPLATE_DOMAIN_DASHBOARD',APPLICATION."/dashboard/"); //new
define('SCHEMA_DATA','code2book');
//set TRUE jika dalam local
$local = true;
$DEVELOPMENT_MODE = true;
$CONFIG['DEFAULT_MODULES'] = "home.php";
$CONFIG['VIEW_ON']  = 1;
$CONFIG['DINAMIC_MODULE']  = "home";
$CONFIG['REGISTER_PAGE']  = "register";
$CONFIG['LOCAL_DEVELOPMENT'] = true;
$CONFIG['DELAYTIME'] = 0;
//WEB APP BASE DOMAIN
// echo ("preview.kanadigital.com");
if(preg_match("/202./i",gethostbyname($_SERVER['HTTP_HOST']))){
	$DOMAIN = "http://{$_SERVER['HTTP_HOST']}/a360/trunk/athreesix/";
}else{
	$DOMAIN = "http://{$_SERVER['HTTP_HOST']}/a360/trunk/athreesix/";
}
// $CONFIG['BASE_DOMAIN_PATH'] = "https://staging.amild.com/";
$CONFIG['BASE_DOMAIN_PATH'] = "http://dev.a360.coid/";

$CONFIG['CLOSED_WEB'] = false;
$CONFIG['TEASER_DOMAIN'] =  "{$DOMAIN}";
$CONFIG['MAINTENANCE'] = false;
$CONFIG['BASE_DOMAIN'] = "{$DOMAIN}";
$CONFIG['DASHBOARD_DOMAIN'] = "{$DOMAIN}dashboard_html/";
$CONFIG['COORPORATE_DOMAIN'] = "{$DOMAIN}coorporate_html/";
$CONFIG['WAP_DOMAIN'] =  "{$DOMAIN}wap_html/"; //new
$CONFIG['Postpaid_OnlineRegistration'] = "{$DOMAIN}Postpaid_OnlineRegistration/";
$CONFIG['Prepaid_Registrations'] = "{$DOMAIN}Prepaid_Registrations/";

$CONFIG['ASSETS_DOMAIN_WEB'] = $CONFIG['BASE_DOMAIN']."assets/";
$CONFIG['ASSETS_DOMAIN_COORPORATE'] = $CONFIG['COORPORATE_DOMAIN']."assets/";
$CONFIG['ASSETS_DOMAIN_WAP'] = $CONFIG['WAP_DOMAIN']."assets/"; //new
$CONFIG['ASSETS_DOMAIN_DASHBOARD'] = $CONFIG['DASHBOARD_DOMAIN']."assets/"; //new

$CONFIG['PUBLIC_ASSET'] = "public_assets/";
$CONFIG['LOCAL_PUBLIC_ASSET'] = "D:/xampp/htdocs/a360/trunk/athreesix/public_html/public_assets/";

if($CONFIG['LOCAL_DEVELOPMENT']) $CONFIG['LOGIN_PAGE']  = "{$DOMAIN}login/local";
else  $CONFIG['LOGIN_PAGE']  = "{$DOMAIN}landing"; 

$CONFIG['MOBILE_SITE'] =  "{$DOMAIN}mobile_html/";
$CONFIG['ASSETS_DOMAIN_MOBILE'] = $CONFIG['MOBILE_SITE']."assets/"; //new

$CONFIG['SESSION_NAME'] = "social_a360";

/* allow access page on unverified */
$CONFIG['access-unverified'] = array("home");


//SOCIAL MEDIA
//testing
$FB['appID'] = "181586055282513";
$FB['appSecret'] = "d22971d06613820427e4e44cdfe1d67b";

// $FB['appID'] = "341380259214774";
// $FB['appSecret'] = "63685e1fd7db81fc51a04de0e2034ceb";

$TWITTER['CONSUMER_KEY'] = 'CeAeKQ6W2flJaiR7m5D3uQ';
$TWITTER['CONSUMER_SECRET'] = 'QS7jBlukxkXhN1bUqFAh5K3Z1pz84Z9fGjgoeJ5mxu8';
$TWITTER['LOGIN_CALLBACK'] = $CONFIG['BASE_DOMAIN'].'?loginType=twitter';

$GPLUS['client_id'] = "990314435829.apps.googleusercontent.com";
$GPLUS['client_secret'] = "c6TzeOJkdOJxtzr_TGMxv5xN";
$GPLUS['developer_key'] = "AIzaSyAWZTca5Nth3LPhlzI9dJUsG2kZUMhFB7I";
$GPLUS['redirect_url'] = "{$DOMAIN}public_html/?loginType=google";

$VIKI['application_id'] ="4fd917c27e2e3f464ebee73fea5abab9f42607887a7f5d705361c4e1dec3fdd8";
$VIKI['application_secret'] = "f59f2126673bf7b629a2867d9dc02e6dcff1e9896fa1b25be6e9ba2eb4003bdb";
$VIKI['callback'] =  "http://viki.com";

/**
 * memcache setting
 */
 $CONFIG['memcache_host'] = "127.0.0.1";
 $CONFIG['memcache_port'] = 11211;


/**
 * GPlus Bot Configuration
 */
$GPLUSBOT['target_id'] = "111091089527727420853";
$GPLUSBOT['maxResults'] = 10;
$GPLUSBOT['bot_sleep_time'] = 60;

if($local){
	$CONFIG['DATABASE'][0]['HOST'] 		= "localhost";
	$CONFIG['DATABASE'][0]['USERNAME'] 	= "root";
	$CONFIG['DATABASE'][0]['PASSWORD'] 	= "";
	$CONFIG['DATABASE'][0]['DATABASE'] 	= "athreesix_web_2013";
	

}else{
	$CONFIG['DATABASE'][0]['HOST'] 		= "117.54.1.99";
	$CONFIG['DATABASE'][0]['USERNAME'] 	= "amild";
	$CONFIG['DATABASE'][0]['PASSWORD'] 	= "m1ldl1ght*";
	$CONFIG['DATABASE'][0]['DATABASE'] 	= "amild_athreesix_web_2013";
	

}

$CONFIG['SERVICE_URL'] = "service/";
$CONFIG['salt'] = '12345678';
/* DATETIME SET */
$timeZone = 'Asia/Jakarta';
date_default_timezone_set($timeZone);


$SMAC_SECRET = sha1("harveyspecterssuits");
$SMAC_HASH = sha1("mikerosssuits");

$CONFIG['SERVICE_KEY'] = sha1("axis2012");


/**
 * Email settings
 */
$CONFIG['EMAIL_FROM_DEFAULT'] = "noreply-axis2012@codebook.com";
$CONFIG['EMAIL_SMTP_HOST'] = "localhost";
$CONFIG['EMAIL_SMTP_PORT'] = 25;
$CONFIG['EMAIL_SMTP_USER'] = "";
$CONFIG['EMAIL_SMTP_PASSWORD'] = "";
$CONFIG['EMAIL_SMTP_SSL'] = 0;
$CONFIG['EMAIL_AXIS'][0] = 'cendiqkrn@gmail.com';
$CONFIG['EMAIL_AXIS'][1] = 'kia_krn@yahoo.com';


/* MOP SETTING */
$CONFIG['BASE_MOP_URL'] = "https://staging-artcademy-amild.es-dm.com/";
$CONFIG['MOP_URL'] = "{$CONFIG['BASE_MOP_URL']}dm.mopid.webservice/centralwebservice.asmx";
$CONFIG['MOP_USER'] = "hosting\pmimopID";
$CONFIG['MOP_PWD'] = "Pm1jkd!";



?>
