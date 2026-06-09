<?php
//ini_set('display_errors',1); 
//error_reporting(E_ALL);
session_start();
if(isset($_SESSION['errorMessage']))
	$errorMessage=$_SESSION['errorMessage'];
else
	$errorMessage="";
unset($_SESSION['errorMessage']);
if(isset($_SESSION['usrnam']))
	$usrnam=$_SESSION['usrnam'];
include('functions.php');
$loginMessage=getSystemValue("loginMessage");
$systemLogo=getSystemValue("systemLogo");
$loginFormButtonBG=getSystemValue("loginFormButtonBG");
$loginFormButtonFG=getSystemValue("loginFormButtonFG");
$loginFormFields=getSystemValue("loginFormFields");
$loginFormIconsBG=getSystemValue("loginFormIconsBG");
$loginFormIconsFG=getSystemValue("loginFormIconsFG");
$loginMessageText=getSystemValue("loginMessageText");
$statusBarBackground=getSystemValue("loginStatusBarBG");
$statusBarMessageColor=getSystemValue("loginStatusBarFG");

echo "<!DOCTYPE html>";
echo "<html><head><meta charset=\"UTF-8\">";
echo "<title>System login</title>";
echo "<link rel='stylesheet' type='text/css' href='css/all.css'>";
echo "<style>";
echo "body {font-family: Arial, Helvetica, sans-serif;} * {box-sizing: border-box;}";
echo ".input-container {display: -ms-flexbox; /* IE10 */  display: flex;  width: 100%;  margin-bottom: 15px;}";
echo ".icon {padding: 10px;  background: $loginFormIconsBG;  color: $loginFormIconsFG;  min-width: 50px;  text-align: center;}";
echo ".input-field {  width: 100%;  padding: 10px;  outline: none;}";
echo ".input-field:focus {border: 2px solid $loginFormFields;}";
echo ".btn {background-color: $loginFormButtonBG;  color: $loginFormButtonFG;  padding: 15px 20px;  border: none;  cursor: pointer;  width: 100%;  opacity: 0.9;}";
echo ".btn:hover {opacity: 1;}";
echo "h2{color: $loginMessageText;}";
echo ".error{color: red; font-size: small;text-align:center}";
echo "</style>";
echo "</head><body style='margin:0;'>";
echo "<p><br></p>";
echo "<div style='width: 200px; height: 200px; margin: auto;'>";
echo "<img src='images/$systemLogo' style='width: 100%;'></div>";
echo "<form action='signin.php' method='post' style='max-width:500px;margin:auto'>";
echo "<h2 style='text-align: center;'>$loginMessage</h2>";
echo "<div class='input-container'><i class='fa-solid fa-user icon'></i><input class='input-field' type='text' placeholder='Username' name='usrnam'";
if(isset($usrnam))
	echo " value='$usrnam'";
echo "></div>";
echo "<div class='input-container'><i class='fa-solid fa-key icon'></i><input class='input-field' type='password' placeholder='Password' name='passwd'></div>";
echo "<button type='submit' class='btn'>Sign in</button>";
echo "</form>";
echo "<div class='error'><p>$errorMessage</p></div>";

include ("bottom.php");
?>
