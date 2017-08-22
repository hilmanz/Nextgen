<?php
include_once "config/config.inc.php";
include_once "engines/Database/SQLData.php";
$db = new SQLData();
$db->open();
$refID = $_GET['ref'];
if($refID==NULL){
	$refID = 0;	
}
$refID = mysql_escape_string($refID);
$db->query("INSERT INTO referral_hits(refID,capture_date) VALUES('".$refID."',NOW())");
$db->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="MSSmartTagsPreventParsing" content="TRUE" />
<meta name="robots" content="noarchive" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="-1" />
<meta name="description" content="Welcome to A MILD.COM - Go Ahead!" />
<meta name="keywords" content="go ahead,a mild, review musik, musik, mp3, berita musik, wanted,band indie, rekaman, lagu, as you like it, game, online, dmassive, musik lokal">
</meta>

<meta http-equiv="refresh" content="1;URL=landing.php?ref=<?=$_GET['ref']?>" />

<meta name="author" content="Kanadigital" />
<title>AMILD.COM</title>
<link href="amild.css" rel="stylesheet" type="text/css" />
</head>

<body>

<script type="text/javascript">
           
            var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
            document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
           
        </script>

        <script type="text/javascript">
           
            try {
                var pageTracker = _gat._getTracker("UA-867847-20");
                pageTracker._trackPageview();
            } catch(err) {}

            
        </script>

</body>
</html>
