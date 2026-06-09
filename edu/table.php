
<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$__systemRoot="../";                        //path to system root
include($__systemRoot."functions.php");     //system Functions don't remove
include('functions.php');                   //uncomment this line if you have a local functions file
include("appdb.php");                       //application database credentials
foreach($_POST as $key => $value){
    $$key=$value;
}
$mode="normal";
if(isset($_GET['CoursId'])){
    $crsId=intval($_GET['CoursId']);
    $submitted=1;
    $mode="popup";
    $dayLength=5;
    $readOne=false;
    $topicNo=1;
    $offset=0;
    $topics=array();
    $hours=array();
    $table=array();
    $dayHoures=0;
    $topicDay=1;
    $weekDays=array(1 => "اﻷحد",
                    2 => "اﻹثنين",
                    3 => "الثلاثاء",
                    4=> "اﻷربعاء",
                    5 => "الخميس"
                );
}
$enabled=array(false,true);
if(isset($_GET['pe'])){
    $pe=intval($_GET['pe']);
    $printEnabled=$enabled[$pe];
}else{
    $printEnabled=true;
}

$__dir="rtl";
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
if(isset($_SESSION['__uid'])){
    $__uid=$_SESSION['__uid'];
        $user_year=getuseryear($__uid,$dbc);
        $perms=getUserPermissions($__uid);
        if($crsId != -1){
            //read course basic data
            $q="SELECT CoursId, CoursStatus, CoursCrsId, CrsName, CrsTHours+CrsPHours AS totHoures, CoursFromPln, CouursToPln, CoursBulletin, dist_name FROM ((Courses INNER JOIN CoursesGuide ON CoursCrsId = CrsId) inner join districts on dist_id = CoursArea) WHERE CoursId=?";
            if ($stmt = mysqli_prepare($dbc, $q)) {
                if(mysqli_stmt_bind_param($stmt, "i", $crsId)){
                    if(mysqli_stmt_execute($stmt)){
                        if(mysqli_stmt_bind_result($stmt, $CoursId, $CoursStatus, $CoursCrsId, $CrsName, $totHoures, $CoursFromPln, $CoursToPln, $CoursBulletin, $dist_name)){
                            if(mysqli_stmt_fetch($stmt)){
                                $readOne=true;
                                $startDay=$CoursFromPln;
                                $noDays=$totHoures/$dayLength;
                            }
                        }
                        mysqli_stmt_close($stmt);
                    }
                }
            }
        }
        if($readOne){
            // get topics
            $q="SELECT tpcdesc,tpcthrs+tpcphrs as tpcHours,staffname FROM (CoursLecs inner join CourseTopics on CrslecPrgId=tpccourse and CrslecTpcId=tpcId) inner join staff on staffid=CrslecLecId WHERE CrslecCrsId=? order by tpcId";
            if ($stmt = mysqli_prepare($dbc, $q)) {
                if(mysqli_stmt_bind_param($stmt, "i", $crsId)){
                    if(mysqli_stmt_execute($stmt)){
                        if(mysqli_stmt_bind_result($stmt, $tpcdesc, $tpcHours, $staffname)){
                            while(mysqli_stmt_fetch($stmt)){
                                $topics[$topicNo]["title"]=$tpcdesc;
                                $topics[$topicNo]["hours"]=$tpcHours;
                                $topics[$topicNo]["lecturer"]=$staffname;
                                $topicNo++;
                            }
                        }
                        mysqli_stmt_close($stmt);        
                    }
                }
            }
            $noOfTopics=count($topics);
            //slice topics
            $h=1;
            for($i=1;$i<=$noOfTopics;$i++){
                for($j=1;$j<=$topics[$i]["hours"];$j++){
                    $hours[$h]["topic"]=$topics[$i]["title"];
                    $hours[$h]["lecturer"]=$topics[$i]["lecturer"];
                    $h++;
                }
            }
            //fill in data
            $h=1;
            $dt = new DateTimeImmutable($startDay);
            for($i=1;$i<=$noDays;$i++){
                //fill in dates
                $inc=$offset+$i-1;
                $table[$i]["date"] = $dt->modify("+$inc day");
                $dow=$table[$i]["date"]->format('w');
                if($dow==4){										//if day of week is thursday increase offset by 2
                    $offset+=2;										
                }
                //fill in detail
                $lastLecturer="";
                $lastTopic="";
                $dayTopic="";
                $dayLecturer="";
                $seperator="";
                for($j=1;$j<=$dayLength;$j++){
                    if($hours[$h]["topic"] != $lastTopic){
                        $dayTopic .= $seperator . $hours[$h]["topic"];
                    }
                    if($hours[$h]["lecturer"] != $lastLecturer){
                        $dayLecturer .= $seperator . $hours[$h]["lecturer"];
                    }
                    $seperator= " - ";
                    $lastTopic=$hours[$h]["topic"];
                    $lastLecturer=$hours[$h]["lecturer"];
                    $h++;
                }
                $table[$i]["topic"]=$dayTopic;
                $table[$i]["lecturer"]=$dayLecturer;
            }
        }
        //Draw table
        echo "
<!DOCTYPE html>
<html>
    <head>
        <title>جدول دورة</title>
        <meta charset='utf-8'>
        <style>
            hr{
                border-style: double;
                border-width: medium;
            }
            table{
                margin: auto;
            }
            .timeTable{
                border-style: solid;
                border-color: black;
                border-width: 1px;
                width: 90%;
            }
            .timeTableCell{
                border-style: solid;
                border-color: black;
                border-width: 1px;
                width: 14.28%;
            }
            .main-doc{
                width: 25cm;
                height: 16cm;
                margin: auto;
                direction:rtl;
                border-style: double;
                border-width: medium;
                text-align: center;
            }
            .logos{
                width:100%;
            }
            .pti-title{
                width: 10cm;
                margin: auto;
                text-align: center;
            }
            .pti-title-en{
                font-size: x-large;
            }
            .pti-title-ar{
                font-size: large;
            }
            .aast-logo{
                width:2cm;
                float:right;
            }
            .pti-logo{
                width:2cm;
                float:left;
            }
            .content{
                text-align:right;
            }
            .signature{
                width:6.5cm;
                text-align: center;
                margin-right: 11.5cm;
            }
            .line-title{
                font-weight: bold;
            }
            .footer {
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
                font-size: x-small;
                text-align: center;
                display:none;
            }
            .buttons{
                direction:rtl;
                margin: auto;
                width:25cm;
                text-align: center;
            }
            .cnlBtn {
                background-color: red;
                color: white;
                padding: 10px 10px;
                border: none;
                cursor: pointer;
                width: 100px;
                opacity: 0.9;
            }
            .cnlBtn:hover {
                opacity: 1;
            }
            .prtBtn {
                background-color: blue;
                color: white;
                padding: 10px 10px;
                border: none;
                cursor: pointer;
                width: 100px;
                opacity: 0.9;
            }
            .prtBtn:hover {
                opacity: 1;
            }

            @media print {
                .footer{
                    display:block;
                    page-break-after: always;
                }
                .buttons{
                    display:none;
                }
            }
        </style>
    </head>";
        echo "<body>";
        echo "<div class='buttons'>";
        if($printEnabled){
            echo "<button type='button' class='prtBtn' onclick='window.print();window.close();'>طباعة</button>&nbsp;";
        }
        echo "<button type='button' class='cnlBtn' onclick='window.close();'>إغلاق</button>";
        echo "<hr><br>";
        echo "</div>";
        if($readOne){
            $noWeeks=ceil($noDays / 5);
            for($weekNo=1;$weekNo<=$noWeeks;$weekNo++){
                $dayOffset=($weekNo - 1) * 5;
                echo "
                <div class='main-doc'>
                    <div class='logos'>
                        <div class='pti-logo'><img src='img/pti.png' width='100%'></div>
                        <div class='aast-logo'><img src='img/aastmt.png' width='100%'></div>
                        <div class='pti-title'>
                            <p class='pti-title-ar'>اﻷكاديمية العربية للعلوم والتكنولوجيا والنقل البحري<br>
                            معهد تدريب الموانئ</p>
                        </div>
                    </div>
                    <br>
                    <hr>
                    الجدول الدراسي لدورة: $CrsName
                    <table width='80%'>
                        <tr>
                            <td width='33%'>رقم الدورة:$CoursBulletin</td>
                            <td width='33%'></td>
                            <td width='33%'>المنطقة: $dist_name</td>
                        </tr>
                        <tr>
                            <td>مــــن: $CoursFromPln</td>
                            <td>إلـــى: $CoursToPln</td>
                            <td>اﻷسبوع: $weekNo</td>
                        </tr>
                    </table>
                    <table class='timeTable'>
                        <tr>
                            <td class='timeTableCell' rowspan='2'><div style='text-align:left;'>الحصص</div><div style='text-align:right;'>اﻷيام</div></td>
                            <td class='timeTableCell'>اﻷولى</td>
                            <td class='timeTableCell'>الثانية</td>
                            <td class='timeTableCell'>الثالثة</td>
                            <td class='timeTableCell'>راحة</td>
                            <td class='timeTableCell'>الرابعة</td>
                            <td class='timeTableCell'>الخامسة</td>
                        </tr>
                        <tr>
                            <td class='timeTableCell'>0935-0850</td>
                            <td class='timeTableCell'>1025-0940</td>
                            <td class='timeTableCell'>1115-1030</td>
                            <td class='timeTableCell'>30 دقيقة</td>
                            <td class='timeTableCell'>1215-1130</td>
                            <td class='timeTableCell'>1305-1220</td>
                        </tr>";
                        $remainingDays=$noDays-($weekNo-1)*5;
                        if($remainingDays==5){
                            $activeWeekDays=5;
                        }elseif($remainingDays<5){
                            $activeWeekDays=$remainingDays;
                        }else{
                            $activeWeekDays=5;
                        }
                        for($weekDay=1;$weekDay<=$activeWeekDays;$weekDay++){
                            if($weekDay == 1 and $weekNo ==1){
                                echo "
                                <tr>
                                    <td class='timeTableCell'><div style='text-align:right'>".$weekDays[$weekDay]."</div><div style='text-align:left'>".$table[$dayOffset + $weekDay]["date"]->format("Y-m-d")."</div></td>
                                    <td class='timeTableCell'><span style='font-size:small'>إفتتاح وتسجيل الدورة</span></td>
                                    <td class='timeTableCell' colspan='5'><div style='text-align:right'>".$table[$dayOffset + $weekDay]["topic"]."</div><div style='text-align:left'>".$table[$dayOffset + $weekDay]["lecturer"]."</div></td>
                                </tr>";        
                            }elseif($weekDay + 5*($weekNo-1) == $noDays){
                                echo "
                                <tr>
                                    <td class='timeTableCell'><div style='text-align:right'>".$weekDays[$weekDay]."</div><div style='text-align:left'>".$table[$dayOffset + $weekDay]["date"]->format("Y-m-d")."</div></td>
                                    <td class='timeTableCell' colspan='5'><div style='text-align:right'>".$table[$dayOffset + $weekDay]["topic"]."</div><div style='text-align:left'>".$table[$dayOffset + $weekDay]["lecturer"]."</div></td>
                                    <td class='timeTableCell'><span style='font-size:small'>إختتام الدورة</span></td>
                                </tr>";
                                break;
                            }else{
                                echo "
                                <tr>
                                    <td class='timeTableCell'><div style='text-align:right'>".$weekDays[$weekDay]."</div><div style='text-align:left'>".$table[$dayOffset + $weekDay]["date"]->format("Y-m-d")."</div></td>
                                    <td class='timeTableCell' colspan='6'><div style='text-align:right'>".$table[$dayOffset + $weekDay]["topic"]."</div><div style='text-align:left'>".$table[$dayOffset + $weekDay]["lecturer"]."</div></td>
                                </tr>";
                            }
                        }

                    echo "
                    </table>
                    <table style='width:90%;'>";
                    if($CoursStatus>=1){
                        $q="SELECT educValue,educSigFile FROM EduConstants WHERE educName='EduMan'";
                        if ($stmt = mysqli_prepare($dbc, $q)) {
                            if(mysqli_stmt_execute($stmt)){
                                if(mysqli_stmt_bind_result($stmt, $educValue,$educSigFile)){
                                    mysqli_stmt_fetch($stmt);
                                }
                                mysqli_stmt_close($stmt);        
                            }
                        }    
                        $q="SELECT staffname,staffsalutation FROM staff WHERE staffid=$educValue";
                        if ($stmt = mysqli_prepare($dbc, $q)) {
                            if(mysqli_stmt_execute($stmt)){
                                if(mysqli_stmt_bind_result($stmt, $staffname,$staffsalutation)){
                                    mysqli_stmt_fetch($stmt);
                                }
                                mysqli_stmt_close($stmt);        
                            }
                        }
//                        echo "<tr><td><br><br>مشرف الدورة</td><td>$staffsalutation / $staffname<br><img src='sig/$educSigFile' width='75px'><br>مدير إدارة شئون التعليم والتدريب</td></tr>";
                        echo "<tr><td><br><br></td><td><br>";
                        echo getSignatureById($dbc,4);
                        //<img src='sig/$educSigFile' width='75px'>
                        echo "<br>مدير إدارة شئون التعليم والتدريب</td></tr>";
                    }
        
                    echo "</table>";
                    $filler=$noWeeks * 5 - $noDays;
                    for($f=1;$f<=$filler;$f++){
                        echo "<br><br><br>";
                    }
                    echo "
                    <hr>
                    <table style='width:90%'>
                        <tr>
                            <td style='text-align:right;font-size:small;width:33.33%;'>إصدار : (1) بتاريخ 1/1/2018<br>تعديل :(...) بتاريخ .../.../....</td>
                            <td style='text-align:center;font-size:small;width:33.33%;'>صفحة $weekNo من $noWeeks</td>
                            <td style='text-align:left;font-size:small;width:33.33%;'>F-IPM-(13)#20</td>
                        </tr>
                    </table>
                </div>            
                ";
            }
        }
    }
    echo "</body></html>";
?>