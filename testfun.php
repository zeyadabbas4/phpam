<?php
echo "testing<br>";
include('functions.php');
$v=getSystemValue("loginscreen");
if($v)
    echo "$v";
else
    echo "lo";
?>
