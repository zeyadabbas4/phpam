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
    $mode="popup";
}
$enabled=array(false,true);
if(isset($_GET['pe'])){
    $pe=intval($_GET['pe']);
    $printEnabled=$enabled[$pe];
    $submitted='1';
    $letterDate=date('Y-m-d');
    $letterRegNo="##";
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
        $q="SELECT CoursId, CoursStatus, CrsCode, CoursCrsId, CoursGenRept, CrsName, CoursFromPln, CouursToPln, CoursBulletin, CoursSupervisorExt, dist_name, CrsDescription, CrsQualification, CrsPrerequisits, CrsDepartments, trntpDescription, cmpName, s1.staffname staffnameint, COALESCE(s2.staffname,'-') staffnameext , CoursLetterName FROM (((((Courses INNER JOIN CoursesGuide ON CoursCrsId = CrsId) inner join districts on dist_id = CoursArea) inner join trainingTypes on trntpId=CrsTrnType) inner join companies on CoursLocation=cmpId) inner join staff s1 on CoursSupervisorInt=s1.staffid) left join staff s2 on CoursSupervisorExt=s2.staffid WHERE CoursId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            if(mysqli_stmt_bind_param($stmt, "i", $crsId)){
                if(mysqli_stmt_execute($stmt)){
                    if(mysqli_stmt_bind_result($stmt, $CoursId, $CoursStatus, $CrsCode, $CoursCrsId, $CoursGenRept, $CrsName, $CoursFromPln, $CoursToPln, $CoursBulletin, $CoursSupervisorExt, $dist_name, $CrsDescription, $CrsQualification, $CrsPrerequisits, $CrsDepartments, $trntpDescription, $cmpName, $staffnameint, $staffnameext,$CoursLetterName)){
                        if(mysqli_stmt_fetch($stmt)){
                            $readOne=true;
                        }
                    }
                    mysqli_stmt_close($stmt);        
                }
            }
        }
        if($readOne){
            $contacts=array();
            $q="SELECT cmpName,cmpId,conttitle,contname,contjob FROM companies INNER JOIN contacts ON cmpId=contcompany";
            if ($stmt = mysqli_prepare($dbc, $q)) {
                if(mysqli_stmt_execute($stmt)){
                    if(mysqli_stmt_bind_result($stmt, $cmpName,$cmpId,$conttitle,$contname,$contjob)){
                        while(mysqli_stmt_fetch($stmt)){
                            $contacts[$cmpId]['Name']=$cmpName;
                            $contacts[$cmpId]['contact']= "$conttitle / $contname";
                            $contacts[$cmpId]['contjob']=$contjob;
                        }
                    }
                    mysqli_stmt_close($stmt);        
                }
            }    

            $conts=array();
            $totPlanned=0;
            $q="SELECT crscmpPlanned,cmpName,cmpId,conttitle,contname,contjob FROM (courseCompanies INNER JOIN companies ON crscmpCompany=cmpId) INNER JOIN contacts ON cmpId=contcompany WHERE crscmpCourse=?";
            if ($stmt = mysqli_prepare($dbc, $q)) {
                if(mysqli_stmt_bind_param($stmt, "i", $crsId)){
                    if(mysqli_stmt_execute($stmt)){
                        if(mysqli_stmt_bind_result($stmt, $crscmpPlanned,$cmpName,$cmpId,$conttitle,$contname,$contjob)){
                            while(mysqli_stmt_fetch($stmt)){
                                $conts[$cmpId]['Name']=$cmpName;
                                $conts[$cmpId]['Count']=$crscmpPlanned;
                                $conts[$cmpId]['contact']= "$conttitle / $contname";
                                $conts[$cmpId]['contjob']=$contjob;
                                $totPlanned += $crscmpPlanned;
                            }
                        }
                        mysqli_stmt_close($stmt);        
                    }
                }
            }    
        }
    }

    //read companies
    $comps=readCompanies($dbc, true);

    if(!isset($submitted)){
        $submitted=0;
    }
    $validationError=false;

    if($submitted == '1'){
        if(!isset($letterRegNo)){
            $errorMessage="<span style='color:red;'>من فضلك أدخل رقم القيد</span><br>";
            $validationError=true;
        }
    }
    if(!isset($letterDate)){
        $letterDate=date('Y-m-d');
    }
    if($assignTo != -1){
        $q="UPDATE Courses SET CoursLetterName=? WHERE CoursId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            if(mysqli_stmt_bind_param($stmt, "ii", $assignTo,$crsId)){
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_close($stmt);        
                }
            }
        }    

    }else{
        if($CoursLetterName != 0){
            $assignTo=$CoursLetterName;
        }    
    }

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

    echo "
        <body>
            <div class='buttons'>
                $errorMessage
                <h2>طباعة خطابات المنشور لدورة $CrsName</h2>";
                if(!isset($pe)){
                    echo "      <form method='post'>
                    <table width='400px' align='center'>
                        <tr><td>رقم القيد</td><td>:</td><td align='right'><input type='text' name='letterRegNo' value='$letterRegNo'></td></tr>
                        <tr><td>التاريخ</td><td>:</td><td align='right'><input type='date' name='letterDate' value='$letterDate'></td></tr>
                        <tr><td>التوجيه</td><td>:</td><td align='right'><select name='assignTo'><option value='-1'>---</option>";
                        foreach($comps as $key => $value){
                            echo "<option value='$key'";
                            if(isset($assignTo)){
                                if($assignTo==$key){
                                    echo " selected";
                                }
                            }
                            echo ">$value</option>";
                        }
                        echo "</select></td></tr>
                    </table>
                    <br>
                    <input type='hidden' name='submitted' value='1'>
                    <button type='submit' class='savBtn'>عرض</button> ";
                    if($validationError || $submitted != '1'){
                        echo "<button type='button' class='cnlBtn' onclick='window.close();'>إغلاق</button>";
                    }
                    echo"
                    </form>
                    <br>";

                }
                if(!$validationError && $submitted == '1'){
                    if($printEnabled){
                        echo "<button type='button' class='prtBtn' onclick='window.print();'>طباعة</button>&nbsp;";
                    }
                    echo "<button type='button' class='cnlBtn' onclick='window.close();'>إغلاق</button>";
                }
                echo "
                <hr><br>
            </div>
        ";
    if(!$validationError && $submitted == '1'){
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
            <p><span class='line-title'>رقم القيد: </span> $letterRegNo</p>
            <p><span class='line-title'>التاريخ: </span> $letterDate </p>
            <p></p>
            <p>".$contacts[$assignTo]['contact']."</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$contacts[$assignTo]['contjob']."</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$contacts[$assignTo]['Name']."</p>
            <p>تحية طيبة وبعد،،،</p>
            <p>مرسل لسيادتكم طيه المنشور الدريبي رقم ( $CoursBulletin ) للعام التدريبي ".$user_year['Desc']." والخاص بتنظيم الدراسة والتدريب بدورة:-</p>
            <p class='crs-title'>$CrsName</p>
            <p>دورة رقم ($CoursGenRept) كود ($CrsCode) والتي تعقد بمنطقة $dist_name لعدد ($totPlanned) مشارك خلال الفترة من $CoursFromPln الى $CoursToPln وذلك تحت اشراف معهد تدريب الموانئ.</p>
            <p class='centered'>وتفضلوا بقبول فائق اﻹحترام</p>
            <br><div class='signature'>";
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
                echo "<br>مدير إدارة شئون التعليم والتدريب";    
            }
            echo"
            </div>
            التوزيع:
            <table width='100%' border='1' cellspacing='0'>
                <tr>
                    <td>م</td>
                    <td>الجهة</td>
                    <td>المشاركين</td>
                    <td>المختص</td>
                </tr>";
                $line=0;
                foreach($conts as $key => $value){
                    $line++;
                    echo "
                    <tr>
                    <td>$line</td>
                    <td>".$value['Name']."</td>
                    <td>".$value['Count']."</td>
                    <td>".$value['contjob']."</td>
                    </tr>
                    ";
                }
            echo "
            </table>
            <div class='footer'>
                <hr>
                <p>بجوار باب 27 خارج الدائرة الجمركية لميناء الاسكندرية / ص.ب: سيدي جابر الاسكندرية رقم بريدي 21211 تليفون : 4865087/4810004  فاكس:4829930-00203
                <br>
                P.O. Box: 123 Sidi Gaber-Alexandria-2311/Phone: 00203-4865087/4810004 - fax: 00203-4829930 - email: info@pti-aast.org</p>
            </div>
        </div>";
    }
    echo "
        </body>
        </html>";
}
?>