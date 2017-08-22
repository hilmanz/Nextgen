<?php
global $APP_PATH;
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $APP_PATH. APPLICATION . "/helper/SessionHelper.php";

$view = new BasicView();
$session = new SessionHelper(APPLICATION.'_Session');
$db = new SQLData();
$user = json_decode(urldecode64($session->get('user')));

$sql="SELECT campaign_name,id,client_id FROM smac_web.tbl_campaign WHERE id='".intval($_SESSION['campaign_id'])."' AND client_id='".intval($user->account_id)."'; ";
$db->open(0);
$rs=$db->fetch($sql);
//echo $sql;
//echo mysql_error();
//exit;
$db->close();

//echo 'account_id: ' . $user->account_id .' - '. $rs['client_id'] . '<br />';
//echo 'campaign_id: ' . $_SESSION['campaign_id'] .' - '. $rs['id'] . '<br />';
//exit;

if( intval($user->account_id) == intval($rs['client_id']) && intval($_SESSION['campaign_id']) == intval($rs['id']) ){

	global $CONFIG;
	$download_url = $CONFIG['download_url'] . 'client_' . intval($user->account_id) .'/'. intval($_SESSION['campaign_id']) .'_topic_summary_report.pdf';
	$filename = strtolower($rs['id']) . '_topic_summary_report.pdf';

	$speed = null; 
	while (ob_get_level() > 0){ 
		ob_end_clean();         
	}          
	$size = sprintf('%u', filesize($download_url));         

	header('Expires: 0');         
	header('Pragma: public');         
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');         
	header('Content-Type: application/octet-stream');         
	header('Content-Length: ' . $size);         
	header('Content-Transfer-Encoding: binary');          
	header('Content-disposition: attachment; filename='.$filename);
	readfile($download_url);
	
}else{
	sendRedirect('index.php');
}

exit;
?>