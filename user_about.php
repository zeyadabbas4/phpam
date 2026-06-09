<?php
echo "<center><br><img src='images/";
echo getSystemValue("systemLogo");
echo "' style='margin: auto; width: 200px;'>";
echo "<br><br>إصدار النظام: phpam version ";
echo getSystemVersion();
echo "<br><br>التاريخ: ". date("j")."<sup>".date("S")."</sup> of ".date(" F Y");
echo "<br><br>الوقت: ". date("h:i:s a");
echo "<br><br>النطاق الزمنى: ". date("e");
echo "<br><br></center>";
?>