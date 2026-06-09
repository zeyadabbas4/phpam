<?php
session_start();
include ("functions.php");
$dbc=sysdbConnect();
if(isset($_SESSION['__uid'])){
    $__uid=$_SESSION['__uid'];
    $user=getUserDetails($__uid);
    $colors=getusercolors($__uid);
    $gdir=getSystemValue("globalDirection");
    $galn=getSystemValue("globalAlign");
    include("head.php");
    include("body.php");
    include("menu.php");
    include("content.php");
    include("bottom.php");
}else{
    $target='login.php';
    header("Location: $target");
}
?>
