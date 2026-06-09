<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$__systemRoot="../";                        //path to system root
include($__systemRoot."functions.php");     //system Functions don't remove
$logged=false;
if(isset($_SESSION['__uid'])){
  $__uid=$_SESSION['__uid'];
  $fileName = basename($_SERVER['PHP_SELF']);
  $mnuId=getCommandMenuId($fileName);
  if(checkUserMenuItem($__uid,$mnuId)){
    $logged=true;
  }
  if($logged){
    echo "<!DOCTYPE html><html><head>";
      $_SESSION['Elevated']=true;
      echo "<script>window.location='../edu/lecturerHoursReport.php';</script>";
    echo "</head>";
    echo "<body></body></html>";
  }
}
if(!$logged){
  include($__systemRoot."expired.php");
}
?>