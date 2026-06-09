<?php
session_start();
if(isset($_SESSION['__uid'])){
    $target='main.php';
}else{
    $target='login.php';
}
header("Location: $target");
die();
?>