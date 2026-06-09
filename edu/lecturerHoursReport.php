<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$__systemRoot="../";                        //path to system root
$__includeDir="../include";                //path to include directory
include($__systemRoot."functions.php");     //system Functions don't remove
include('functions.php');                   //uncomment this line if you have a local functions file
include("appdb.php");                       //application database credentials
foreach($_POST as $key => $value){
    $$key=$value;
}
$__dir="rtl";
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
if(!isset($mode)){
    $mode="";
}
if(!isset($errorMessage)){
    $errorMessage="";
}
$logged=false;
if(isset($_SESSION['__uid'])){
    $__uid=$_SESSION['__uid'];
    $user_year=getuseryear($__uid,$dbc);
    $logged=true;
    $filename=basename($_SERVER["SCRIPT_NAME"]);
    $mnuId=getCommandMenuId($filename);
    $authorised=false;
    $elevated=false;
    if(isset($_SESSION['Elevated'])){
        $elevated=$_SESSION['Elevated'];
    }
    if(checkUserMenuItem($__uid,$mnuId)){
        $authorised=true;
    }
    if($elevated){
        $authorised=true;
    }
}
if(!$logged){
    include($__systemRoot."expired.php");
    die();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>تقرير ساعات محاضر</title>
    <style>
        * {
            box-sizing: border-box
        }
        body{
            text-align:center;
            margin: 0;
        }
        .mainDoc{
            width: 18cm;
            margin: auto;
        }
        .headTable{
            width: 18cm;
        }
        .leftLogo{
            width: 3cm;
        }
        .rightLogo{
            width: 3cm;
        }
        .leftLogo img{
            width: 100%;
        }
        .rightLogo img{
            width: 100%;
        }
        .contentTable{
            width: 18cm;
            border-collapse: collapse;
            border-style: solid;
            border-width: 2px;
            border-color: black;
            direction:rtl;
        }
        .contentTable th{
            border-style: solid;
            border-width: 2px;
            border-color: black;
            font-weight: bold;
            font-size: large;
        }
        .contentTable td{
            border-style: solid;
            border-width: 2px;
            border-color: black;
            font-size: large;
        }
        .lecName{
            direction: rtl;
            text-align: right;
        }
        .eduYear{
            text-align: center;
            direction: rtl;
        }
        .mainForm{
            width: 80%;
            margin: auto;
            text-align: center;
        }
        .mainForm table{
            width: 100%;
            direction: rtl;
        }
        .input-container {
            display: -ms-flexbox; /* IE10 */  
            display: flex;  
            width: 100%;  
            margin-bottom: 15px;
        }
        .input-field { 
            width: 100%;  
            padding: 10px;  
            outline: none;
        }
        .input-field:focus {
            border: 2px solid dodgerblue;
            opacity: 1;
        }
        .viewBtn {
            background-color: Chocolate;  
            color: white;  
            padding: 10px 10px;  
            border: none;  
            cursor: pointer;  
            width: 100px;  
            opacity: 0.9;
        }
        .viewBtn:hover {
            opacity: 1;
        }
        .pwdBtn {
            background-color: Blue;  
            color: white;  
            padding: 10px 10px;  
            border: none;  
            cursor: pointer;  
            width: 100px;  
            opacity: 0.9;
        }
        .pwdBtn:hover {
            opacity: 1;
        }
        .cnlBtn {
            background-color: red;  
            color: white;  padding: 10px 10px;  
            border: none;  
            cursor: pointer;  
            width: 100px;  
            opacity: 0.9;
        }
        .cnlBtn:hover {
            opacity: 1;
        }
        .error{
            color:red; 
            font-weight:bold;
            text-align:center;
            direction:rtl;
        }
        @media print{
            .mainForm{
                display: none;
            }
        }
    </style>
</head>
<body>
<?php
if(!$authorised){
    echo "<div class='error'>ليس مسموحا لك باستخدام هذا الخيار!...</div>";
}else{
    if($mode=='display'){
        // if($lecId == "-1"){
        if($lecName == ""){
            echo "<div class='error'>لابد من إختيار محاضر!...</div>";
            $mode="";
        }
    }
    include ("$__includeDir/readLecNames.php");
    $labelWidth="100px";
    echo "<div class='mainForm'>";
    echo "<form method='post'>";
    echo "<h3>تقرير ساعات محاضر</h3>";
    echo "<table>";
    echo "<tr><td style='width: $labelWidth;'>اسم المحاضر:</td><td colspan='4'>";
    echo "<div class='input-container'><input class='input-field' name='lecName' list='lecList' caption='حدد المحاضر'>";
    echo "<datalist id='lecList'>";
    foreach($lecNames as $key => $value){
        echo "<option value='$value'>";
        }
    echo "</datalist>";
    echo "</div></td></tr>";

    $tYears=listEduTrnYears($dbc,"pkey");

    echo "<tr><td style='width: $labelWidth;'>العام التدريبي:</td><td colspan='4'>";
    echo "<div class='input-container'>";
    echo "<select class='input-field' name='yearId' onchange='setDates(this.value);'><option value='-1'>حدد العام التدريبي</option>";
    foreach($tYears as $key => $value){
        echo "<option value='$key'";
        if(isset($yearId)){
            if($yearId==$key){
                echo " selected";
            }
        }
        echo ">".$value['desc']."</option>";
        }
    echo "</select></div></td></tr>";
    echo "<tr><td>عن الفترة:</td><td>من:</td><td><input class='input-field' type='date' name='dateFrom' id='dateFrom'></td><td>الى:</td><td><input class='input-field' type='date' name='dateTo' id='dateTo'></td></tr>";
    echo "</table>";
    echo "<br>";
    echo "<div class='frmButtons'>";
    if($mode != ""){
        echo "<button type='button' class='cnlBtn' onclick='window.location=(\"$filename\");'>تراجع</button> ";
        echo "<button type='button' class='pwdBtn' onclick='window.print();'>طباعة</button> ";
    }
    echo "<button type='submit' class='viewBtn' name='mode' value='display'>عرض</button> ";
    echo "</div>";
    echo "</form>";
    echo "</div>";
    echo "<script>";
    echo "const starts=[];\n";
    echo "const ends=[];\n";
    foreach($tYears as $key => $value){
        echo "starts[$key]='".$value['start']."';\n";
        echo "ends[$key]='".$value['end']."';\n";
    }
    echo "
    function setDates(yearId){
        var fromDate=document.getElementById('dateFrom');
        var toDate=document.getElementById('dateTo');
        fromDate.value=starts[yearId];
        toDate.value=ends[yearId];
    }
    ";

    echo "</script>";
    if($mode=='display'){
        $result=array();
        $i=0;
        $totHours=0;
        $fromClause="";
        if($dateFrom!=''){
            $fromClause="AND (CoursFromAct > ? OR CoursToAct>?)";
        }
        $toClause="";
        if($dateTo!=''){
            $toClause="AND CoursFromAct < ?";
        }
        $yearClause="";
        /* 
        if($yearId != "-1"){
            $yearClause="AND CoursYear=?";
        }
        */
        $districts=readDistrcts($dbc);
        $coursTypes=readCourseTypes($dbc);
        $q="SELECT staffname,CrsName,CoursBulletin,CoursFromAct,CoursToAct,CoursArea,CoursType,clhHoursP+clhHoursT AS clhHours FROM staff INNER JOIN CourseLecHours ON staffid=clhLecId INNER JOIN Courses ON clhCrsId=CoursId INNER JOIN CoursesGuide ON CoursCrsId=CrsId WHERE staffname LIKE ? $fromClause $toClause $yearClause";
        //echo $q;
        if ($stmt = mysqli_prepare($dbc, $q)){
            $bound=false;
            $params=array();
            $params[]=$lecName;
            $types="s";
            if($dateFrom!=''){
                $types.="ss";
                $params[]=$dateFrom;
                $params[]=$dateFrom;
            }
            if($dateTo!=''){
                $types.="s";
                $params[]=$dateTo;
            }
            /*
            if($yearId != "-1"){
                $types .="i";
                $params[]=$yearId;
            }
            */
            $bound=mysqli_stmt_bind_param($stmt, $types, ...$params);
            if($bound){
                if(mysqli_stmt_execute($stmt)){
                    if(mysqli_stmt_bind_result($stmt, $staffname,$CrsName,$CoursBulletin,$CoursFromAct,$CoursToAct,$CoursArea,$CoursType,$clhHours)){
                        while(mysqli_stmt_fetch($stmt)){
                            $result[$i]['staffname']=$staffname;
                            $result[$i]['CrsName']=$CrsName;
                            $result[$i]['CoursFromAct']=$CoursFromAct;
                            $result[$i]['CoursToAct']=$CoursToAct;
                            $result[$i]['Area']=$CoursArea;
                            $result[$i]['Type']=$CoursType;
                            $result[$i]['clhHours']=$clhHours;
                            $result[$i]['Bulletin']=$CoursBulletin;
                            $totHours+=$clhHours;
                            $i++;                        
                        }
                    }
                }    
            }
            mysqli_stmt_close($stmt);
        }
        $tYears=listEduTrnYears($dbc,"pkey");
        $tYears[-1]="الكل";
        echo "
        <div class='mainDoc'>
            <table class='headTable'>
                <tr>
                    <td class='leftLogo'><img src='img/pti.png'></td>
                    <td>
                        <h2>معهد تدريب الموانئ</h2>
                        <h2>إدارة التعليم والتدريب</h2>
                        <h3>تقرير ساعات محاضر</h3>            
                    </td>
                    <td class='rightLogo'><img src='img/aastmt.png'></td>
                </tr>
            </table>";
            if($yearId != '-1'){
                echo "<p class='eduYear'>في العام التدريبي: ".$tYears[$yearId]['desc']."</p>";
            }
            echo"
            <p class='lecName'>اﻹسم: $lecName</p>
            <table class='contentTable'>
                <tr>
                    <th style='width: 50px;'>م</th>
                    <th>اسم الدورة</th>
                    <th>النوع</th>
                    <th>المنشور/الاخطار</th>
                    <th>المنطقة</th>
                    <th style='width: 120px;'>البداية</th>
                    <th style='width: 120px;'>النهاية</th>
                    <th style='width: 75px;'>الساعات</th>
                </tr>";
        $ser=1;
        foreach($result as $key => $value){
            echo "<tr>
                    <td>$ser</td>
                    <td>".$value['CrsName']."</td>
                    <td>".$coursTypes[$value['Type']]."</td>
                    <td>".$value['Bulletin']."</td>
                    <td>".$districts[$value['Area']]['Name']."</td>
                    <td>".$value['CoursFromAct']."</td>
                    <td>".$value['CoursToAct']."</td>
                    <td>".$value['clhHours']."</td>
                </tr>";
                $ser++;
        }
        echo "      <tr>
                    <td colspan='7'>اﻹجمالي</td>
                    <td>". number_format($totHours,2) ."</td>
                </tr>
            </table>";
        echo "</div>";
    }
?>
<?php
}
?>
</body>
</html>
