<?php
//uncomment those two lines for debugging
ini_set('display_errors',1); 
error_reporting(E_ALL);
session_start();
$__systemRoot="../";
//include($__systemRoot.'sysdb.php');            //setup system databse connection
include($__systemRoot.'functions.php');        //include system functions
include('functions.php');	         		   //uncomment this line if you have a local functions file
include('appdb.php');             			   //include app database connection
if(isset($_SESSION['__uid'])){        		   //check for session and set the sesOk variable
    $__uid=$_SESSION['__uid'];
    $sesOk=true;
}else{
    $sesOk=false;
}
$prmOk=false;
?>
<!DOCTYPE html>
<html lang="ar">
    <head>
        <meta charset="utf8">
        <title>Basic Studies Lecturer Report</title>
        <style>
            * {
                box-sizing: border-box
            }
            body{
                margin: 0;
                text-align: center;
                font-family: 'Times New Roman', Times, serif;
                direction: rtl;
            }
            .mainDoc{
                width: 19cm;
                height: 27cm;
                margin: auto;
            }
            .headerTable{
                width: 90%;
                margin: auto;
            }
            .aastLogo{
                width: 2cm;
            }
            .aastLogo img{
                width: 100%;
            }
            .ptiLogo{
                width: 2cm;
            }
            .ptiLogo img{
                width: 90%;
            }
            .pageTitle{
                font-weight: bold;
                font-size: large;
            }
            .pageSubtitle{
                font-weight: bold;
                font-size: small;
            }            
            .mainTable{
                width: 90%;
                border-style: solid;
                border-color: black;
                border-width: 1px;
                border-collapse: collapse;
                margin: auto;
            }
            .mainTable th{
                border-style: solid;
                border-color: black;
                border-width: 1px;
                padding: 3px;
                font-size: small;
            }
            .mainTable td{
                border-style: solid;
                border-color: black;
                border-width: 1px;
                padding: 3px;
                font-size: medium;
            }
            .supervisorCell{
                text-align: right;
            }
            .DocumentDate{
                text-align: right;
                width: 80%;
                margin: auto;
            }
            .opinions{
                font-size: Medium;
            }
            .signatures{
                margin: auto;
                width: 90%;
            }
            .signatures td{
                font-size: medium;
            }
            .iso {
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
                font-size: x-small;
                page-break-after: always;
            }
            .isoVersion{
                text-align: right;
                font-size: small;
            }
            .isoCode{
                direction: ltr;
                text-align: left;
                font-size: small;
            }
            .buttons{
                text-align: center;
                direction: rtl;
            }
            @media print {
                .buttons{
                    display:none;
                }
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
        </style>
    </head>
    <body>
<?php
if($sesOk){
    //session is up check for user permission!...   
    $filename=basename(__FILE__);                               //get script name
/*  disable menu check bec this file is called from a button
    $mnuId=getCommandMenuId($filename);                         //get sreen id
    if(checkUserMenuItem($__uid,$mnuId)){                       //check for user permission to use the screen
        $prmOk=true;
    }else{
        $prmOk=false;
        echo "<br><div class='alertmessages' style='direction: ltr'>Access denied!...</div>";    
    }   */
    $prmOk=true;
}else{
    //session not up display error!...
    echo "<br><div class='alertmessages' style='direction: ltr'>Session expired please re-login!...</div>";
}
if($prmOk){
	//read post data
    if(!isset($_SESSION['clsId'])){
        echo "<script>window.close();</script>";
    }
	$clsId=$_SESSION['clsId'];
	$batchId=$_SESSION['batchId'];
	$clhPeriod=$_SESSION['clhPeriod'];
	unset($_SESSION['clsId']);
	unset($_SESSION['batchId']);
	unset($_SESSION['clhPeriod']);

	//initialize form variables and make database connection
	if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
		die("Could not connect to database please contact system admin...");
	}
    $period=readPeriod($dbc,$clhPeriod);
    $periodTitle=$period['Description'];
    $periodFrom=$period['From'];
    $periodTo=$period['To'];

    $class=readClass($dbc,$clsId);
    $className=$class['Description'];

    $batch=readBatch($dbc,$batchId);
    $batchName=$batch['Description'];

    $today=date("Y/m/d");
    echo "<div class='buttons'>";
    echo "<button type='button' class='prtBtn' onclick='window.print();window.close();'>طباعة</button>&nbsp;";
    echo "<button type='button' class='cnlBtn' onclick='window.close();'>إغلاق</button>";
    echo "<hr><br></div>";
    echo "
        <div class='mainDoc'>
            <table class='headerTable'>
                <tr>
                    <td class='aastLogo'><img src='img/aastmt.png'></td>
                    <td class='pageTitle'>
                        اﻷكاديمية العربية للعلوم والتكنولوجيا والنقل البحري<br>
                        معهد تدريب الموانئ
                    </td>
                    <td class='ptiLogo'><img src='img/pti.png'></td>
                </tr>
            </table>
            <p class='pageTitle'>الدراسات اﻷساسية للبحارة</p>
            <p class='pageSubtitle'>ساعات المحاضرين من $periodFrom حتى $periodTo</p>
            <p class='pageSubtitle'>$batchName</p>
            <p class='pageSubtitle'>إجمالي ساعات $periodTitle $className</p>

            <div class='DocumentDate'>$today</div>
            <table class='mainTable'>
                <tr>
                    <th rowspan='2'>م</th>
                    <th rowspan='2'>اسم المحاضر/المدرب</th>
                    <th rowspan='2'>نظري</th>
                    <th rowspan='2'>عملي</th>
                    <th rowspan='2'>إجمالي<br>الساعات</th>
                    <th rowspan='2'>توقيع<br>المحاضر</th>
                    <th colspan='2'>الفئة</th>
                    <th colspan='2'>أخرى</th>
                    <th colspan='2'>المبلغ<br>المستحق</th>
                </tr>
                <tr>
                    <th>نظري</th>
                    <th>عملي</th>
                    <th>بدل سفر</th>
                    <th>انتقالات</th>
                    <th>قرش</th>
                    <th>جنيه</th>
                </tr>";
    $lecs=readCourseLecturers($dbc,$clsId,$clhPeriod);
    for($i=1;$i<=10;$i++){
        echo "
        <tr>
            <td>$i</td>
            <td>";
            if(isset($lecs[$i-1]['name'])){
                echo $lecs[$i-1]['name'];
            }
            echo "</td>
            <td>";
            if(isset($lecs[$i-1]['thhrs'])){
                echo number_format($lecs[$i-1]['thhrs'],0);
            }
            echo "</td>
            <td>";
            if(isset($lecs[$i-1]['prhrs'])){
                echo number_format($lecs[$i-1]['prhrs'],0);
            }
            echo "</td>
            <td>";
            if(isset($lecs[$i-1]['name'])){
                echo number_format($lecs[$i-1]['thhrs']+$lecs[$i-1]['prhrs'],0);
            }
            echo "</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>";
    }
    echo "
                <tr>
                    <td colspan='10' class='supervisorCell'>جهة اﻹشراف:<br><br></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan='10'>اﻹجمالي</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <br><br>
            <p class='opinions'>رأي مشرف الدورة :- ............................................................................. التوقيع :- .....................</p>
            <p class='opinions'>رأي رئيس قسم الدورة :- ........................................................................ التوقيع :- .....................</p>
            <br><br>
            <table class='signatures'>
                <tr>
                    <td>مسئول التسجيل</td>
                    <td>رئيس القسم</td>
                    <td>مدير إدارة التعليم</td>
                    <td>التخطيط</td>
                    <td>الموارد البشرية</td>
                    <td>المراجعة</td>
                    <td>المدير المالي</td>
                    <td>عميد المعهد</td>
                </tr>
            </table>
            <br><br><br><br><br><br>
            <table class='iso'>
                <tr>
                    <td class='isoVersion'>إصدار:- () بتاريخ 000/00/0<br>تعديل:- () بتاريخ 000/00/0</td>
                    <td class='isoCode'>F-IPM-(13)#6</td>
                </tr>
            </table>
        </div>
    </body>
</html>";
}
?>