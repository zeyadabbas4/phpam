<?php
/*ini_set('display_errors',1);
error_reporting(E_ALL);*/
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
$labelWidth="100px";
$formWidth="800px";
$readOne=false;
if(isset($_SESSION['__uid'])){
    $__uid=$_SESSION['__uid'];
    $user_year=getuseryear($__uid,$dbc);

    if($crsId != -1){
        $q="SELECT CoursId, CoursStatus, CoursCrsId, CrsName, CoursFromPln, CouursToPln, CoursBulletin, CoursSupervisorExt, dist_name, dist_local, CrsDescription, CrsQualification, CrsPrerequisits, CrsDepartments, trntpDescription, cmpName, s1.staffname staffnameint, COALESCE(s2.staffname,'-') staffnameext FROM (((((Courses INNER JOIN CoursesGuide ON CoursCrsId = CrsId) inner join districts on dist_id = CoursArea) inner join trainingTypes on trntpId=CrsTrnType) inner join companies on CoursLocation=cmpId) inner join staff s1 on CoursSupervisorInt=s1.staffid) left join staff s2 on CoursSupervisorExt=s2.staffid WHERE CoursId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            if(mysqli_stmt_bind_param($stmt, "i", $crsId)){
                if(mysqli_stmt_execute($stmt)){
                    if(mysqli_stmt_bind_result($stmt, $CoursId, $CoursStatus, $CoursCrsId, $CrsName, $CoursFromPln, $CoursToPln, $CoursBulletin, $CoursSupervisorExt, $dist_name, $dist_local, $CrsDescription, $CrsQualification, $CrsPrerequisits, $CrsDepartments, $trntpDescription, $cmpName, $staffnameint, $staffnameext)){
                        if(mysqli_stmt_fetch($stmt)){
                            $readOne=true;
                        }
                    }
                    mysqli_stmt_close($stmt);        
                }
            }
        }
    }else{
        unset($_POST['submitted']);
    }
         
    $q="SELECT conttitle,contname FROM companies INNER JOIN contacts ON cmpId=contcompany WHERE cmpId=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt, "i", $CoursSupervisorExt)){
            if(mysqli_stmt_execute($stmt)){
                if(mysqli_stmt_bind_result($stmt, $conttitle,$contname)){
                    mysqli_stmt_fetch($stmt);
                }
                mysqli_stmt_close($stmt);        
            }
        }
    }

    if($readOne){
        echo "
        <!DOCTYPE html>
        <html>
            <head>
                <title>منشور تدريبي</title>
                <meta charset='utf-8'>
                <style>
                    .main-doc{
                        width: 17cm;
                        height: 24cm;
                        margin: auto;
                        direction:rtl;
                    }
                    .doc-title{
                        width: 6cm;
                        border-width: 1px;
                        border-style: solid;
                        text-align: center;
                        margin: auto;
                        font-size: x-large;
                    }
                    .logos{
                        width:100%;
                    }
                    .pti-title{
                        width:7.5cm;
                        margin: auto;
                        text-align: center;
                    }
                    .pti-title-en{
                        font-size: x-large;
                    }
                    .pti-title-ar{
                        font-size: xx-large;
                    }
                    .aast-logo{
                        width:2.5cm;
                        float:right;
                    }
                    .pti-logo{
                        width:2.5cm;
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
                    .crs-title{
                        font-weight: bold;
                        font-size: x-large;
                        text-align: center;
                    }
                    .centered{
                        text-align: center;
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
                        text-align: center;
                        direction: rtl;
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
                    .savBtn {
                        background-color: green;
                        color: white;
                        padding: 10px 10px;
                        border: none;
                        cursor: pointer;
                        width: 100px;
                        opacity: 0.9;
                    }
                    .savBtn:hover {
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
        echo "
        <div class='main-doc'>
            <div class='logos'>
                <div class='pti-logo'><img src='img/pti.png' width='100%'></div>
                <div class='aast-logo'><img src='img/aastmt.png' width='100%'></div>
                <div class='pti-title'>
                    <p><span class='pti-title-ar'>معـهد تـدريب المــوانئ</span><br>
                    <span class='pti-title-en'>Port Training Institute</span></p>
                </div>
            </div>
            <br>
            <hr>
            <div class='doc-title'>منشــور تـدريـبـي</div><div class='content'>
            <p><span class='line-title'>منشور رقم: ( $CoursBulletin )</span></p>
            <p><span class='line-title'>المنطقة: $dist_name</span></p>
            <p><span class='line-title'>اسم الدورة: $CrsName</span></p>
            <p><span class='line-title'>أهداف الدورة:</span></p>
            <p>
                <ul>
                    <li>$CrsDescription</li>
                </ul>
            </p>
            <p><span class='line-title'>شروط الالتحاق:</span></p>
            <p>
                <ul>
                    <li>المؤهل: $CrsQualification</li>
                    <li>الدورات: $CrsPrerequisits</li>
                    <li>الادارات: $CrsDepartments</li>
                </ul>
            </p>
            <p><span class='line-title'>مدة الدورة: من :</span> $CoursFromPln <span class='line-title'> إلى:</span> $CoursToPln</p>
            <p><span class='line-title'>نوع التدريب:</span> $trntpDescription</p>
            <p><span class='line-title'>مكان الانعقاد: $cmpName</span></p>
            <p><span class='line-title'>الاشراف:</span></p>
            <p>
                <ul>
                    <li>$staffnameint</li>";
            if($dist_local == 0){
                echo "<li>$contname</li>";
            }
            echo "
                        </ul>
                </p>
                </div><br><div class='signature'>";
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
                    echo "
                    $staffsalutation / $staffname<br>";
                    echo getSignatureById($dbc,4);
                    //<img src='sig/$educSigFile' width='200px'>
                    echo "<br>
                    مدير إدارة شئون التعليم والتدريب";    
                }
                echo"
                </div>";
                echo "
                <div class='footer'>
                    <hr>
                    <p>
                        بجوار باب 27 خارج الدائرة الجمركية لميناء الاسكندرية / ص.ب: سيدي جابر الاسكندرية رقم بريدي 21211 تليفون : 4865087/4810004  فاكس:4829930-00203
                        <br>
                        P.O. Box: 123 Sidi Gaber-Alexandria-2311/Phone: 00203-4865087/4810004 - fax: 00203-4829930 - email: info@pti-aast.org
                    </p>
                </div>
            </div>
        </body>
    </html>";
        }
    }
?>