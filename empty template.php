<?php
//uncomment those two lines for debugging
//ini_set('display_errors',1); 
//error_reporting(E_ALL);
session_start();
if(isset($_SESSION['uid']))
{
  include('../sysdb.php');
  include('../functions.php');
  //include('functions.php');               //uncomment this line if you have a local functions file
  $usrid=$_SESSION['uid'];
  $filename=""; 			    //enter file name here
  $scrid=getscreenid($filename);
  if(checkuserscreen($usrid,$scrid))
  {
    $PageTitle="";			   //write the title of your page
    include('../top.php');
    include('../title.php');
    foreach ($_POST as $key => $value) 
	$$key=$value;
    // Your code goes below this line




    // Your code ends above this line
    include('../bot.php');
  }
  else
  {
    $target="../index.php";
    include('../redirect.php');
  }
  
}
else
{
  $target="../index.php";
  include('../redirect.php');
}
?>