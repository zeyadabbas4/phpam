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
        if($yearId == "-1"){
            echo "<div class='error'>لابد من تحديد العام التدريبي!...</div>";
            $mode="";
        }
        if($districtId == "-1"){
            echo "<div class='error'>لابد من تحديد المنطقة!...</div>";
            $mode="";
        }
        if($company == "-1"){
            echo "<div class='error'>لابد من تحديد الشركة!...</div>";
            $mode="";
        }
    }
    //read districts
    $districts=readDistrcts($dbc);
    //read companies
    $comps=array();
    $q="SELECT cmpId,cmpName,cmpDistrict FROM companies WHERE cmpPlanCont=1";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $cmpId,$cmpName,$cmpDistrict)){
                while(mysqli_stmt_fetch($stmt)){
                    $comps[$cmpId]['name']=$cmpName;
                    $comps[$cmpId]['dist']=$cmpDistrict;
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    include ("$__includeDir/readLecNames.php");
    echo "<div class='mainForm'>";
    echo "<form method='post'>";
    echo "<h3>تقرير اﻹنجاز للدورات المخططة</h3>";
    echo "<table>";
    $tYears=listEduTrnYears($dbc,"pkey");
    echo "<td> </td><td style='width: 100px;'>العام التدريبي:</td><td style='width: 200px;'>";
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
    echo "</select></td>";
//    echo "<td>عن الفترة:</td><td>من:</td><td><input class='input-field' type='date' name='dateFrom' id='dateFrom'></td><td>الى:</td><td><input class='input-field' type='date' name='dateTo' id='dateTo'></td></tr>";
    echo "<td style='width: 75px;'>المنطقة:</td>";
    echo "<td style='width: 200px;'>";
    echo "<select name='districtId' class='input-field' onchange='setCompany(this.value);'><option value='-1'>حدد المنطقة</option>";
    foreach($districts as $key => $value){
        echo "<option value='$key'>".$value['Name']."</option>";
    }
    echo "</select>";
    echo "</td>";
    echo "<td style='width: 75px;'>الشركة:</td>";
    echo "<td style='width: 500px;'>";
    echo "<select name='company' id='company' class='input-field'><option value='-1'>حدد الشركة</option>";
    echo "</select>";
    echo "</td><td> </td>";
    echo "</tr>";
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
    echo "const compKeys=[];\n";
    echo "const compNames=[];\n";
    echo "const compDists=[];\n";
    foreach($tYears as $key => $value){
        echo "starts[$key]='".$value['start']."';\n";
        echo "ends[$key]='".$value['end']."';\n";
    }
    $i=0;
    foreach($comps as $key => $value){
        echo "compKeys[$i]=".$key.";\n";
        echo "compNames[$i]='".$value['name']."';\n";
        echo "compDists[$i]=".$value['dist'].";\n";
        $i++;
    }
    echo "
    function setDates(yearId){
        var fromDate=document.getElementById('dateFrom');
        var toDate=document.getElementById('dateTo');
        fromDate.value=starts[yearId];
        toDate.value=ends[yearId];
    }
    function setCompany(distId){
        var compSelect=document.getElementById('company');
        var len=compSelect.options.length;
        for(var i=0;i<len;i++){
          compSelect.remove(0);
        }
        var dists=compDists.length;
        var opt=document.createElement(\"option\");
        opt.text='حدد الشركة';
        opt.value=-1;
        compSelect.add(opt);  
        for(i=0;i<dists;i++){
            if(distId==compDists[i]){
                var opt=document.createElement(\"option\");
                opt.text=compNames[i];
                opt.value=compKeys[i];
                compSelect.add(opt);  
            }
        }
    }
    ";

    echo "</script>";
    if($mode=='display'){
        $result=array();
        $i=0;
        $totHours=0;
        $fromClause="";
        if($dateFrom!=''){
            $fromClause="AND CoursFromAct > ?";
        }
        $toClause="";
        if($dateTo!=''){
            $toClause="AND CoursToAct < ?";
        }
        $yearClause="";
        /* 
        if($yearId != "-1"){
            $yearClause="AND CoursYear=?";
        }
        */
        echo "year=$yearId<br>district=$districtId<br>company=$company<br>";
        $coursTypes=readCourseTypes($dbc);
        $q="SELECT SELECT CoursId,CoursBulletin,CoursFromPln,CouursToPln,CoursFromAct,CoursToAct FROM Courses WHERE CoursArea=? AND CoursYear=? AND CoursType=1 ORDER BY CoursBulletin";
        //echo $q;
        if ($stmt = mysqli_prepare($dbc, $q)){
            $bound=false;
            $params=array();
            $params[]=$districtId;
            $params[]=$yearId;
            $types="ii";
            if($dateFrom!=''){
                $types.="s";
                $params[]=$dateFrom;
            }
            if($dateTo!=''){
                $types.="s";
                $params[]=$dateTo;
            }
            $bound=mysqli_stmt_bind_param($stmt, $types, ...$params);
            if($bound){
                if(mysqli_stmt_execute($stmt)){
                    if(mysqli_stmt_bind_result($stmt,$CoursId,$CoursBulletin,$CoursFromPln,$CouursToPln,$CoursFromAct,$CoursToAct)){
                        while(mysqli_stmt_fetch($stmt)){
                            $result[$i]['CoursId']=$CoursId;
                            $result[$i]['CoursBulletin']=$CoursBulletin;
                            $result[$i]['CoursFromPln']=$CoursFromPln;
                            $result[$i]['CouursToPln']=$CouursToPln;
                            $result[$i]['CoursFromAct']=$CoursFromAct;
                            $result[$i]['CoursToAct']=$CoursToAct;
                            $result[$i]['trnPlanned']=0;
                            $result[$i]['trnActual']=0;
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
                        <h3>تقرير الانجاز للدورات المخططة</h3>            
                    </td>
                    <td class='rightLogo'><img src='img/aastmt.png'></td>
                </tr>
            </table>";
            if($yearId != '-1'){
                echo "<p class='eduYear'>في العام التدريبي: ".$tYears[$yearId]['desc']."</p>";
            }
            echo"
            <p class='lecName'>منطقة: ".$districts[$districtId]['Name']."</p>
            <p class='lecName'>شركة: ".$comps[$company]['name']."</p>
            <table class='contentTable'>
                <tr>
                    <th style='width: 50px;'>م</th>
                    <th>رقم المنشور</th>
                    <th>تاريخ البداية(مخطط)</th>
                    <th>تاريخ النهاية(مخطط)</th>
                    <th>تاريخ البداية(فعلي)</th>
                    <th>تاريخ النهاية(فعلي)</th>
                    <th>المتدربين(مخطط)</th>
                    <th>المتدربين(فعلى)</th>
                    <th>الزيادة/النقص</th>
                </tr>";
        $ser=1;
        foreach($result as $key => $value){
            echo "<tr>
                    <td>$ser</td>
                    <td>".$value['CoursBulletin']."</td>
                    <td>".$value['CoursFromPln']."</td>
                    <td>".$value['CouursToPln']."</td>
                    <td>".$value['CoursFromAct']."</td>
                    <td>".$value['CoursToAct']."</td>
                    <td>".$value['trnPlanned']."</td>
                    <td>".$value['trnActual']."</td>
                    <td>".$value['trnPlanned'] - $value['trnActual']."</td>
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
