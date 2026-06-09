<?php
//ini_set('display_errors',1);
//error_reporting(E_ALL);
session_start();
$logged=false;
if(isset($_SESSION['__uid'])){
  $__uid=$_SESSION['__uid'];
  if($__uid==0){
	$logged=true;
	include ("functions.php");
	echo "<!DOCTYPE html><html><head>";
	echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
	echo "<title>";
	echo getSystemValue('systemName')." Administration";
	echo "</title>";
	echo "<style>";
	echo ".navbar { overflow: hidden; background-color: #333; font-family: Arial, Helvetica, sans-serif;}";
	echo ".navbar a { float: left; font-size: 16px; color: white; text-align: center; padding: 14px 16px; text-decoration: none;}";
	echo ".dropdown { float: left; overflow: hidden;}";
	echo ".dropdown .dropbtn { font-size: 16px; border: none; outline: none; color: white; padding: 14px 16px; background-color: inherit; font-family: inherit; margin: 0;}";
	echo ".navbar a:hover, .dropdown:hover .dropbtn { background-color: red;}";
	echo ".dropdown-content { display: none; position: absolute; background-color: #f9f9f9; min-width: 160px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 1;}";
	echo ".dropdown-content a { float: none; color: black; padding: 12px 16px; text-decoration: none; display: block; text-align: left;}";
	echo ".dropdown-content a:hover { background-color: #ddd;}";
	echo ".dropdown:hover .dropdown-content { display: block;}";
	echo ".topnav-right { float: right;}";
	echo "</style>";
	echo "<script>";
	echo "function calcContentSize(){";
	echo "  var x=window.innerWidth;";
	echo "  var y=window.innerHeight;";
	echo "  document.getElementById('content').width=x-20;";
	echo "  document.getElementById('content').height=y-110;";
	echo "}";
	echo "</script>";
	echo "</head>";
	echo "<body onpageshow='calcContentSize();' onload='calcContentSize();' onresize='calcContentSize();'>";
	echo "<div class=\"navbar\">";
	echo "  <div class=\"dropdown\">";
	echo "    <button class=\"dropbtn\">Users and Menus</button>";
	echo "    <div class=\"dropdown-content\">";
	echo "      <a href=\"#\" onclick=\"document.getElementById('content').src='users.php'\">Users</a>";
	echo "      <a href=\"#\" onclick=\"document.getElementById('content').src='groups.php'\">Groups</a>";
	echo "      <a href=\"#\" onclick=\"document.getElementById('content').src='menus.php'\">Menus</a>";
	echo "      <a href=\"#\" onclick=\"document.getElementById('content').src='permissions.php'\">Permissions</a>";
	echo "    </div>";
	echo "  </div>";
	echo "  <div class=\"dropdown\">";
	echo "    <button class=\"dropbtn\">Application Development</button>";
	echo "    <div class=\"dropdown-content\">";
	echo "      <a href=\"#\" onclick=\"document.getElementById('content').src='forms.php'\">Forms</a>";
	echo "      <a href=\"#\" onclick=\"document.getElementById('content').src='reports.php'\">Reports</a>";
	$target=getSystemValue("databaseManager");
	echo "      <a href=\"$target\" target=\"_blank\" onclick=\"document.getElementById('content').src='about.php'\">Database</a>";
	echo "      <a href=\"#\" onclick=\"document.getElementById('content').src='funRef.php'\">Function Reference</a>";
	echo "    </div>";
	echo "  </div>";
	echo "  <div class=\"dropdown\">";
	echo "    <button class=\"dropbtn\">System Settings</button>";
	echo "    <div class=\"dropdown-content\">";
	echo "      <a href=\"#\" onclick=\"document.getElementById('content').src='options.php'\">System Options</a>";
	echo "      <a href=\"#\" onclick=\"document.getElementById('content').src='colors.php'\">System Colors</a>";
	echo "      <a href=\"#\" onclick=\"document.getElementById('content').src='sysValues.php'\">System Values</a>";
	echo "      <a href=\"#\" onclick=\"document.getElementById('content').src='backup.php'\">Backup and Restore</a>";
	echo "      <a href=\"#\" onclick=\"document.getElementById('content').src='message.php'\">Global Message</a>";
	echo "      <a href=\"#\" onclick=\"document.getElementById('content').src='update.php'\">Update Manager</a>";
	echo "    </div>";
	echo "  </div>";
	echo "  <div class=\"topnav-right\">";
	echo "    <a href=\"#\" onclick=\"document.getElementById('content').src='about.php'\"> About </a>";
	echo "    <a href=\"#\" onclick=\"window.location='logout.php';\"> Logout </a>";
	echo "  </div>";
	echo "</div>";
	echo "<iframe src='about.php' width='100%' height='100%' id='content'></iframe>";
	$statusBarBackground="#333333";
	$statusBarMessageColor="#dbdbdb";
	include ("bottom.php");
  }
}
if(!$logged){
  $target="login.php";
  header("Location: $target");
}
?>
