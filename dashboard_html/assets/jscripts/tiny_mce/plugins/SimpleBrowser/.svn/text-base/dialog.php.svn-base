<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Image Browser</title>
	<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
	<script type="text/javascript" src="js/dialog.js"></script>
    <script type="text/javascript">
    function preview_img(f){
		SimpleBrowser.insert(f);
	}
    </script>
</head>
<body>
<form action="dialog.php" enctype="multipart/form-data" method="post">
  <table width="100%" border="0" cellspacing="5" cellpadding="5">
    <tr>
        <td width="30%"> Upload Image : </td>
        <td width="70%"><input type="file" name="img" id="img" /></td>
    </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" id="submit" value="Upload Image" /></td>
      </tr>
    </table>
    <div class="mceActionPanel">
		<div style="float: left"></div>

<div style="float: right">
			<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
		</div>
	</div>
</form>
<div style="overflow:auto;width:480px;height:300px;border:1px #333333 solid;">
<?php
//edit this line
$self = $_SERVER['PHP_SELF'];
if($self==NULL){
	$self = $_ENV['PHP_SELF'];
}

$foo = explode("/",$self);
$n = sizeof($foo);
$j=$n;
$rel_path="";
//print "Self : ".$self."<br/>";
while($j>=$n-4){
	$rel_path .="../"; 
	$j--;
}

//print "Real path : ".$rel_path."<br/";
chdir($rel_path);
//print "current dir : ".getcwd()."<br/>";
$dir_path = "contents/images/";
//print $dir_path;

print "<script>SimpleBrowser.setPath('".$dir_path."')</script>";
if(!is_dir($dir_path)){
//print "no directory found<br/>";
 print "<script>alert('DIRECTORY NOT FOUND, WE\'RE CREATING ONE!');</script>";
 //die();

 if(mkdir($dir_path)){
 	
 	print "New directory created !<br/>";
 }else{
 print "Sorry, failed <br/>";
 }
}else{
	//print "ready<br/>";
}
//print getcwd();
//print "yey";
//upload procedure
if($_FILES['img']['tmp_name']!=NULL){
	if(move_uploaded_file($_FILES['img']['tmp_name'],$dir_path.str_replace(" ","_",$_FILES['img']['name']))){
		print "Upload Success !";
	}
	
}
if($_GET['delete']!=NULL){
	@unlink($dir_path.$_GET['delete']);
}
$dir = opendir($dir_path);
while($the_dir=readdir($dir)){
	if($the_dir!="."&&$the_dir!=".."&&eregi("\.jpg|\.gif|\.png",$the_dir)){
		$files[] = $the_dir;
	}
}
$n = sizeof($files);
for($i=0;$i<$n;$i++){
$stats = lstat($dir_path.$files[$i]);

print "<div style='padding:5px;'><img src='img/icon.gif' width='10' height='10'/>&nbsp;<a href='javascript:;' onclick=preview_img(\"".$files[$i]."\")>".$files[$i]."</a>&nbsp;&nbsp;".number_format(filesize($dir_path.$files[$i]))."&nbsp;bytes&nbsp;&nbsp;".date("d/m/Y H:i:s",$stats['mtime'])."&nbsp;&nbsp;&nbsp;<a href='dialog.php?delete=".$files[$i]."'>Delete</a></div>";
}
//-->
?>
</div>
</body>
</html>
