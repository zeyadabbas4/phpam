<?php
include ("functions.php");
echo "<!DOCTYPE html><html><head><title>About phpam</title>";
echo "<meta charset=\"utf8\"></head>";
echo "<body style=\"text-align: center;\">";
echo "<br><br><img src=\"images/";
echo getSystemValue("systemLogo");
echo "\" style=\"margin: auto; width: 200px;\">";
echo "<br><br>phpam Version: ";
echo getSystemVersion();
echo "<br><br>Date: ". date("j")."<sup>".date("S")."</sup> of ".date(" F Y");
echo "<br><br>Time: ". date("h:i:s a");
echo "<br><br>Time zone: ". date("e");
echo "</body>";
?>