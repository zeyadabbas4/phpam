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
}

$__dir="rtl";
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
$logged=false;
$labelWidth="100px";
$formWidth="800px";
$readOne=false;
$readMany=false;
if(!isset($mode)){
    $mode=0;
}
if(!isset($errorMessage)){
    $errorMessage="";
}
if(isset($_SESSION['__uid'])){
    $__uid=$_SESSION['__uid'];
    $fileName="manshours.php";
    $mnuId=getCommandMenuId($fileName);
    if(checkUserMenuItem($__uid,$mnuId)){
        $logged=true;
    }
    if($logged){

        $user_year=getuseryear($__uid,$dbc);

        if(isset($submitted)){
            if($crsId != -1){
                $q="SELECT CoursId, CoursCrsId, CrsName, CoursFromPln, CouursToPln, CoursBulletin, dist_name, CrsDescription, CrsQualification, CrsPrerequisits, CrsDepartments, trntpDescription, cmpName, s1.staffname staffnameint, COALESCE(s2.staffname,'-') staffnameext FROM (((((Courses INNER JOIN CoursesGuide ON CoursCrsId = CrsId) inner join districts on dist_id = CoursArea) inner join trainingTypes on trntpId=CrsTrnType) inner join companies on CoursLocation=cmpId) inner join staff s1 on CoursSupervisorInt=s1.staffid) left join staff s2 on CoursSupervisorExt=s2.staffid WHERE CoursId=?";
                if ($stmt = mysqli_prepare($dbc, $q)) {
                    if(mysqli_stmt_bind_param($stmt, "i", $crsId)){
                        if(mysqli_stmt_execute($stmt)){
                            if(mysqli_stmt_bind_result($stmt, $CoursId, $CoursCrsId, $CrsName, $CoursFromPln, $CoursToPln, $CoursBulletin, $dist_name, $CrsDescription, $CrsQualification, $CrsPrerequisits, $CrsDepartments, $trntpDescription, $cmpName, $staffnameint, $staffnameext)){
                                if(mysqli_stmt_fetch($stmt)){
                                    $readOne=true;
                                }
                            }
                            mysqli_stmt_close($stmt);        
                        }
                    }
                }
            }elseif($manNoFrom != '' and $manNoTo ==''){
                $q="SELECT CoursId, CoursCrsId, CrsName, CoursFromPln, CouursToPln, CoursBulletin, dist_name, CrsDescription, CrsQualification, CrsPrerequisits, CrsDepartments, trntpDescription, cmpName, s1.staffname staffnameint, COALESCE(s2.staffname,'-') staffnameext FROM (((((Courses INNER JOIN CoursesGuide ON CoursCrsId = CrsId) inner join districts on dist_id = CoursArea) inner join trainingTypes on trntpId=CrsTrnType) inner join companies on CoursLocation=cmpId) inner join staff s1 on CoursSupervisorInt=s1.staffid) left join staff s2 on CoursSupervisorExt=s2.staffid WHERE CoursBulletin=? and CoursYear=?";
                if ($stmt = mysqli_prepare($dbc, $q)) {
                    if(mysqli_stmt_bind_param($stmt, "ii", $manNoFrom,$user_year['Id'])){
                        if(mysqli_stmt_execute($stmt)){
                            if(mysqli_stmt_bind_result($stmt, $CoursId, $CoursCrsId, $CrsName, $CoursFromPln, $CoursToPln, $CoursBulletin, $dist_name, $CrsDescription, $CrsQualification, $CrsPrerequisits, $CrsDepartments, $trntpDescription, $cmpName, $staffnameint, $staffnameext)){
                                if(mysqli_stmt_fetch($stmt)){
                                    $readOne=true;
                                }
                            }
                            mysqli_stmt_close($stmt);        
                        }
                    }
                }
            }elseif($manNoFrom != '' and $manNoTo !='' and $area==-1){
                $courses=array();
                $crsCount=0;
                $q="SELECT CoursId, CoursCrsId, CrsName, CoursFromPln, CouursToPln, CoursBulletin, dist_name, CrsDescription, CrsQualification, CrsPrerequisits, CrsDepartments, trntpDescription, cmpName, s1.staffname staffnameint, COALESCE(s2.staffname,'-') staffnameext FROM (((((Courses INNER JOIN CoursesGuide ON CoursCrsId = CrsId) inner join districts on dist_id = CoursArea) inner join trainingTypes on trntpId=CrsTrnType) inner join companies on CoursLocation=cmpId) inner join staff s1 on CoursSupervisorInt=s1.staffid) left join staff s2 on CoursSupervisorExt=s2.staffid WHERE CoursBulletin>=? and CoursBulletin<=? and CoursYear=?";
                if ($stmt = mysqli_prepare($dbc, $q)) {
                    if(mysqli_stmt_bind_param($stmt, "iii", $manNoFrom,$manNoTo,$user_year['Id'])){
                        if(mysqli_stmt_execute($stmt)){
                            if(mysqli_stmt_bind_result($stmt, $CoursId, $CoursCrsId, $CrsName, $CoursFromPln, $CoursToPln, $CoursBulletin, $dist_name, $CrsDescription, $CrsQualification, $CrsPrerequisits, $CrsDepartments, $trntpDescription, $cmpName, $staffnameint, $staffnameext)){
                                while(mysqli_stmt_fetch($stmt)){
                                    $readMany=true;
                                    $crsCount++;
                                    $courses[$crsCount]['CoursId']=$CoursId;
                                    $courses[$crsCount]['CoursCrsId']=$CoursCrsId;
                                    $courses[$crsCount]['CrsName']=$CrsName;
                                    $courses[$crsCount]['CoursFromPln']=$CoursFromPln;
                                    $courses[$crsCount]['CoursToPln']=$CoursToPln;
                                    $courses[$crsCount]['CoursBulletin']=$CoursBulletin;
                                    $courses[$crsCount]['dist_name']=$dist_name;
                                    $courses[$crsCount]['CrsDescription']=$CrsDescription;
                                    $courses[$crsCount]['CrsQualification']=$CrsQualification;
                                    $courses[$crsCount]['CrsPrerequisits']=$CrsPrerequisits;
                                    $courses[$crsCount]['CrsDepartments']=$CrsDepartments;
                                    $courses[$crsCount]['trntpDescription']=$trntpDescription;
                                    $courses[$crsCount]['cmpName']=$cmpName;
                                    $courses[$crsCount]['staffnameint']=$staffnameint;
                                    $courses[$crsCount]['staffnameext']=$staffnameext;
                                }
                            }
                            mysqli_stmt_close($stmt);        
                        }
                    }
                }
            }elseif($manNoFrom != '' and $manNoTo !='' and $area!=-1){
                $courses=array();
                $crsCount=0;
                $q="SELECT CoursId, CoursCrsId, CrsName, CoursFromPln, CouursToPln, CoursBulletin, dist_name, CrsDescription, CrsQualification, CrsPrerequisits, CrsDepartments, trntpDescription, cmpName, s1.staffname staffnameint, COALESCE(s2.staffname,'-') staffnameext FROM (((((Courses INNER JOIN CoursesGuide ON CoursCrsId = CrsId) inner join districts on dist_id = CoursArea) inner join trainingTypes on trntpId=CrsTrnType) inner join companies on CoursLocation=cmpId) inner join staff s1 on CoursSupervisorInt=s1.staffid) left join staff s2 on CoursSupervisorExt=s2.staffid WHERE CoursBulletin>=? and CoursBulletin<=? and CoursYear=? and CoursArea=?";
                if ($stmt = mysqli_prepare($dbc, $q)) {
                    if(mysqli_stmt_bind_param($stmt, "iii", $manNoFrom,$manNoTo,$user_year['Id'],$area)){
                        if(mysqli_stmt_execute($stmt)){
                            if(mysqli_stmt_bind_result($stmt, $CoursId, $CoursCrsId, $CrsName, $CoursFromPln, $CoursToPln, $CoursBulletin, $dist_name, $CrsDescription, $CrsQualification, $CrsPrerequisits, $CrsDepartments, $trntpDescription, $cmpName, $staffnameint, $staffnameext)){
                                while(mysqli_stmt_fetch($stmt)){
                                    $readMany=true;
                                    $crsCount++;
                                    $courses[$crsCount]['CoursId']=$CoursId;
                                    $courses[$crsCount]['CoursCrsId']=$CoursCrsId;
                                    $courses[$crsCount]['CrsName']=$CrsName;
                                    $courses[$crsCount]['CoursFromPln']=$CoursFromPln;
                                    $courses[$crsCount]['CoursToPln']=$CoursToPln;
                                    $courses[$crsCount]['CoursBulletin']=$CoursBulletin;
                                    $courses[$crsCount]['dist_name']=$dist_name;
                                    $courses[$crsCount]['CrsDescription']=$CrsDescription;
                                    $courses[$crsCount]['CrsQualification']=$CrsQualification;
                                    $courses[$crsCount]['CrsPrerequisits']=$CrsPrerequisits;
                                    $courses[$crsCount]['CrsDepartments']=$CrsDepartments;
                                    $courses[$crsCount]['trntpDescription']=$trntpDescription;
                                    $courses[$crsCount]['cmpName']=$cmpName;
                                    $courses[$crsCount]['staffnameint']=$staffnameint;
                                    $courses[$crsCount]['staffnameext']=$staffnameext;
                                }
                            }
                            mysqli_stmt_close($stmt);        
                        }
                    }
                }
            }else{
                unset($_POST['submitted']);
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
                                width:17cm;
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
                    </head>
                    <body>
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
                            <hr>";
                echo "<div class='doc-title'>منشــور تـدريـبـي</div><div class='content'>
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
        if($staffnameext != '-'){
            echo "<li>$staffnameext</li>";
        }
                            
        echo "
                            </ul>
                    </p>
                    </div><br><div class='signature'>
                    دكتور / علاء محمود محمد مرسي
                    <br><br>
                    مساعد عميد المعهد
                    <br>
                    ومدير إدارة شئون التعليم والتدريب
                    </div>";
                include("letter.footer.php");
            }elseif($readMany){
                for($i=1;$i<=$crsCount;$i++){

                    include("letter.head.php");
                    echo "<div class='doc-title'>منشــور تـدريـبـي</div><div class='content'>
                        <p><span class='line-title'>منشور رقم: ( ".$courses[$i]['CoursBulletin']." )</span></p>
                        <p><span class='line-title'>المنطقة: ".$courses[$i]['dist_name']."</span></p>
                        <p><span class='line-title'>اسم الدورة: ".$courses[$i]['CrsName']."</span></p>
                        <p><span class='line-title'>أهداف الدورة:</span></p>
                        <p>
                            <ul>
                                <li>".$courses[$i]['CrsDescription']."</li>
                            </ul>
                        </p>
                        <p><span class='line-title'>شروط الالتحاق:</span></p>
                        <p>
                            <ul>
                                <li>المؤهل: ".$courses[$i]['CrsQualification']."</li>
                                <li>الدورات: ".$courses[$i]['CrsPrerequisits']."</li>
                                <li>الادارات: ".$courses[$i]['CrsDepartments']."</li>
                            </ul>
                        </p>
                        <p><span class='line-title'>مدة الدورة: من :</span> ".$courses[$i]['CoursFromPln']." <span class='line-title'> إلى:</span> ".$courses[$i]['CoursToPln']."</p>
                        <p><span class='line-title'>نوع التدريب:</span> ".$courses[$i]['trntpDescription']."</p>
                        <p><span class='line-title'>مكان الانعقاد: ".$courses[$i]['cmpName']."</span></p>
                        <p><span class='line-title'>الاشراف:</span></p>
                        <p>
                            <ul>
                                <li>".$courses[$i]['staffnameint']."</li>";
            if($courses[$i]['staffnameext'] != '-'){
                echo "<li>".$courses[$i]['staffnameext']."</li>";
            }
                                
            echo "
                                </ul>
                        </p>
                        </div><br><div class='signature'>
                        دكتور / علاء محمود محمد مرسي
                        <br><br>
                        مساعد عميد المعهد
                        <br>
                        ومدير إدارة شئون التعليم والتدريب
                        </div>";
                        echo "
                        <div class='buttons'>";
                        echo "
                        <button type='button' class='prtBtn' onclick='window.print();'>طباعة</button>&nbsp;
                        <button type='button' class='cnlBtn' onclick='window.location=\"manshours.php\";'>تراجع</button>
                        ";
                        echo "            
                                    </div>
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
        }else{
            unset($_POST['submitted']);
        }

        if(!isset($submitted)){
            echo"
            <!DOCTYPE html>
                <html>
                    <head>
                        <title>منشور تدريبي</title>
                        <meta charset='utf-8'>
                        <style>
                        .pti-title{
                            font-size: xx-large;
                            text-align: center;
                        }
                        .man-form{
                            width: $formWidth;
                            margin:auto;
                            font-size: large;
                            text-align: right;
                        }
                        .buttons{
                            width: 400px;
                            text-align: center;
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
                    <body dir='rtl'>
                        <div class='pti-title'>
                            <p><span class='pti-title-ar'>طباعة المنشورات</span><br>
                        </div>
                    <hr>
                    <div class='man-form'>
                        <form method='post'>
                            <table>
                                <tr>
                                <td>إختار الدورة</td><td>:</td><td><select name='crsId'>
                                <option value='-1'>&nbsp;</option>
                                ";
            $i=0;
            $q="SELECT CoursId, CoursCrsId, CrsName, CoursFromPln, CoursBulletin, dist_name FROM (Courses INNER JOIN CoursesGuide ON CoursCrsId = CrsId) inner join districts on dist_id = CoursArea WHERE CoursYear=? and CoursType=1 order by CoursFromPln";
            if ($stmt = mysqli_prepare($dbc, $q)) {
                if(mysqli_stmt_bind_param($stmt, "i", $user_year['Id'])){
                    if(mysqli_stmt_execute($stmt)){
                        if(mysqli_stmt_bind_result($stmt, $CoursId, $CoursCrsId, $CrsName, $CoursFromPln, $CoursBulletin, $dist_name)){
                            while(mysqli_stmt_fetch($stmt)){
                                echo "<option value='$CoursId'>$CoursBulletin - $CrsName - $CoursFromPln - $dist_name</option>";
                            }    
                        }
                        mysqli_stmt_close($stmt);        
                    }
                }
            }
            echo "                    
                                </select></td>
                                </tr>
                                <tr>
                                    <td>أو رقم المنشور من</td><td>:</td><td><input type='text' name='manNoFrom' size='5'> الى: <input type='text' name='manNoTo' size='5'></td>
                                </tr>
                                <tr>
                                <td>المنطقة</td><td>:</td><td><select name='area'>
                                <option value='-1'>&nbsp;</option>
                                ";
            $i=0;
            $q="SELECT dist_id,dist_name FROM districts order by dist_name";
            if ($stmt = mysqli_prepare($dbc, $q)) {
                if(mysqli_stmt_execute($stmt)){
                    if(mysqli_stmt_bind_result($stmt, $dist_id,$dist_name)){
                        while(mysqli_stmt_fetch($stmt)){
                            echo "<option value='$dist_id'>$dist_name</option>";
                        }    
                    }
                    mysqli_stmt_close($stmt);        
                }
            }
            echo "
                                </select></td>
                                </tr>
                            </table>
                            <input type='hidden' name='submitted' value='1'>
                            <div class='buttons'>
                                <button type='submit' class='prtBtn'>طباعة</button>
                            </div>
                        </form>
                    </div>
                    ";
        }
    }
}
if(!$logged){
  include($__systemRoot."expired.php");
}
?>