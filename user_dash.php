<?php
echo "<center>";
echo "<h3>".$user['usrFullName']. " - التاريخ: ". date("d / m / Y"). " - الوقت: ". date("h:i:s a")."</h3><br>";
echo "<form method='post'>";
echo "<table style='width:100%'>";
echo "<tr>";
echo "<td style='width:25%;background-color:".$colors['lstHdBG'].";color:".$colors['lstHdFG'].";text-align:center;'>";
echo "<h3><i class='fa-solid fa-square-phone-flip'></i> دليل الهاتف</h3>";
echo "</td>";
echo "<td style='width:10%;'>";
echo "</td>";
echo "<td style='width:25%;background-color:".$colors['lstHdBG'].";color:".$colors['lstHdFG'].";text-align:center;'>";
echo "<h3><i class='fa-solid fa-envelope'></i> الرسائل</h3>";
echo "</td>";
echo "<td style='width:10%;'>";
echo "</td>";
echo "<td style='width:25%;background-color:".$colors['lstHdBG'].";color:".$colors['lstHdFG'].";text-align:center;'>";
echo "<h3><i class='fa-solid fa-qrcode'></i> كود QR</h3>";
echo "</td>";
echo "</tr><tr>";
echo "<td style='text-align:center;'>";
echo "عدد اﻷسماء المسجلة في دليل الهاتف: ";
$q="select count(contId) as totNames from phoneBook where contUser=$__uid";
$r=mysqli_query($dbc,$q);
if($r){
    if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value)
            $$key=$value;
    }
    mysqli_free_result($r);
}
echo $totNames;
echo "<br><br><br>";
echo "<div class='frmButtons'><button type='submit' class='okBtn' name='activeTab' value='phoneBook' style='width:150px;'>مزيد من التفاصيل</button></div>";
echo "<br>";
echo "</td>";
echo "<td>";
echo "</td>";
echo "<td style='text-align:center;'>";
echo "عدد الرسائل اﻹجمالي: ";
$q="select count(msgId) as totMessages from messages where msgTo=$__uid and msgDeleted=0";
$r=mysqli_query($dbc,$q);
if($r){
    if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value)
            $$key=$value;
    }
    mysqli_free_result($r);
}
echo $totMessages;
echo "<br>";
echo "عدد الرسائل الجديدة: ";
$q="select count(msgId) as totNewMessages from messages where msgTo=$__uid and msgDeleted=0 and msgRead=0";
$r=mysqli_query($dbc,$q);
if($r){
    if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value)
            $$key=$value;
    }
    mysqli_free_result($r);
}
echo $totNewMessages;
echo "<br><br>";
echo "<div class='frmButtons'><button type='submit' class='okBtn' name='activeTab' value='messages' style='width:150px;'>مزيد من التفاصيل</button></div>";
echo "<br>";
echo "</td>";
echo "<td>";
echo "</td>";
echo "<td style='text-align:center;'>";
echo "عدد الأكواد المسجلة : ";
$q="select count(qrId) as qrCount from qr where qrUser=$__uid";
$r=mysqli_query($dbc,$q);
if($r){
    if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value)
            $$key=$value;
    }
    mysqli_free_result($r);
}
echo $qrCount;
echo "<br><br><br>";
echo "<div class='frmButtons'><button type='submit' class='okBtn' name='activeTab' value='qr' style='width:150px;'>مزيد من التفاصيل</button></div>";
echo "<br>";
echo "</td>";
echo "</tr><tr>";
echo "<td style='background-color:".$colors['lstHdBG'].";color:".$colors['lstHdFG'].";text-align:center;'>";
echo "<h3><i class='fa-solid fa-bars-progress'></i> سجل المهام</h3>";
echo "</td>";
echo "<td>";
echo "</td>";
echo "<td style='background-color:".$colors['lstHdBG'].";color:".$colors['lstHdFG'].";text-align:center;'>";
echo "<h3><i class='fa-solid fa-note-sticky'></i> دفتر الملاحظات</h3>";
echo "</td>";
echo "<td>";
echo "</td>";
echo "<td>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td style='text-align:center;'>";
echo "عدد المهام اﻹجمالي: ";
$q="select count(tskId)as totTasks from tasks where tskUser=$__uid";
$r=mysqli_query($dbc,$q);
if($r){
    if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value)
            $$key=$value;
    }
    mysqli_free_result($r);
}
echo $totTasks;
echo "<br>";
echo "عدد المهام المتأخرة: ";
$q="select count(tskId)as totLateTasks from tasks where tskUser=$__uid and tskEndDate<curdate() and tskPercentComplete<100";
$r=mysqli_query($dbc,$q);
if($r){
    if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value)
            $$key=$value;
    }
    mysqli_free_result($r);
}
echo $totLateTasks;
echo "<br><br>";
echo "<div class='frmButtons'><button type='submit' class='okBtn' name='activeTab' value='tasks' style='width:150px;'>مزيد من التفاصيل</button></div>";
echo "<br>";
echo "</td>";
echo "<td>";
echo "</td>";
echo "<td style='text-align:center;'>";
echo "عدد الملاحظات المسجلة: ";
$q="select count(noteId) as totNotes from notes where noteUser=$__uid";
$r=mysqli_query($dbc,$q);
if($r){
    if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value)
            $$key=$value;
    }
    mysqli_free_result($r);
}
echo $totNotes;
echo "<br><br><br>";
echo "<div class='frmButtons'><button type='submit' class='okBtn' name='activeTab' value='notes' style='width:150px;'>مزيد من التفاصيل</button></div>";
echo "<br>";
echo "</td>";
echo "<td>";
echo "</td>";
echo "<td>";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</form>";
echo "<br></center>";
?>