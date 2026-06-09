<?php
//ini_set('display_errors',1);
//error_reporting(E_ALL);
session_start();
$logged=false;
if(isset($_SESSION['__uid'])){
  $__uid=$_SESSION['__uid'];
  if($__uid==0){
    $logged=true;
    $showList=true;
    include ("functions.php");
    $dbc=sysdbConnect();
    $target=getSystemValue("databaseManager");
//    header("Location: $target");
$url=$target;
include("redirect.php");
  }
}
if(!$logged){
  $url="login.php";
  $target="_TOP";
  include("redirect.php");
  //header("Location: $target");
}
?>
