<?php
	global $CONFIG;
	include_once "common.php";
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html dir="ltr" lang="en-US" class="ie6"> <![endif]-->
<!--[if IE 7]>    <html dir="ltr" lang="en-US" class="ie7"> <![endif]-->
<!--[if IE 8]>    <html dir="ltr" lang="en-US" class="ie8"> <![endif]-->
<!--[if gt IE 8]><!--> <html dir="ltr" lang="en-US"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<title>BEAT</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<link rel="icon" type="img/png" href="<?php echo $CONFIG['BASE_DOMAIN_PATH'] ?>img/favicon.png">
<!-- Styles -->
<link href="<?php echo $CONFIG['BASE_DOMAIN_PATH'] ?>assets/css/mobile.css" rel="stylesheet">
<script src="<?php echo $CONFIG['BASE_DOMAIN_PATH'] ?>assets/js/jquery-1.7.2.min.js"></script>
</head>
<body>
	<div id="body-container">
            <div id="body">
            	<img class="andbg" src="<?php echo $CONFIG['BASE_DOMAIN_PATH'] ?>assets/img/mobile/android_logo.png">
                <div id="content">
                	<div id="logo">
            			<img class="logo" src="<?php echo $CONFIG['BASE_DOMAIN_PATH'] ?>assets/img/mobile/logo.png">
                	</div>
                    <div class="container">
                 	  	<h3>INFORMATION</h3>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="80">Version</td>
                            <td width="1">:</td>
                            <td>0.9.10</td>
                          </tr>
                          <tr>
                            <td>Size</td>
                            <td width="1">:</td>
                            <td>33 MB</td>
                          </tr>
                          <tr>
                            <td>Compatibility</td>
                            <td width="1">:</td>
                            <td>Android 2.2 (Froyo) or greater</td>
                          </tr>
                        </table>
                    </div> <!-- /.row -->
                    <div class="downloadBtn">
						<?php
                           echo '<a href="'.$CONFIG['BASE_DOMAIN_PATH'].'/assets/sources/apk/beat.apk">DOWNLOAD</a>';
                        ?>
                    </div>
                     <div class="downloadBtn" style="margin-top:10px;">
            <?php
                           echo '<a href="'.$CONFIG['BASE_DOMAIN'].'logout.php">LOGOUT</a>';
                        ?>
                    </div>
                </div> <!-- /#theContent -->
            </div> <!-- /#body -->
    </div> <!-- /#body-container -->
 
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-867847-51', 'ba-space.com');
  ga('send', 'pageview');
</script>

</body>
</html>