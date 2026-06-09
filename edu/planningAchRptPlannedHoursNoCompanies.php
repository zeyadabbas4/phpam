<?php
$__systemRoot="../";                        //path to system root
$__includeDir="../include";                //path to include directory
include($__systemRoot."functions.php");     //system Functions don't remove
include('functions.php');                   //uncomment this line if you have a local functions file
include("appdb.php");                       //application database credentials
$__dir="rtl";
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
if(!isset($errorMessage)){
    $errorMessage="";
}
foreach($_POST as $key => $value){
    $$key=$value;
}
$filename=basename(__FILE__);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>تقرير الدورات المخططة</title>
    <style>
        * {
            box-sizing: border-box
        }
        body{
            text-align:center;
            margin: 0;
        }
        .mainDoc{
            width: 28cm;
            margin: auto;
        }
        .headTable{
            width: 28cm;
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
            width: 28cm;
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
            direction: rtl;
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
        .okBtn{
            background-color: green;  
            color: white;  
            padding: 10px 10px;  
            border: none;  
            cursor: pointer;  
            width: 100px;  
            opacity: 0.9;
        }
        .okBtn:hover{
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
if(isset($mode)){
    if(!isset($datefrom) or !isset($dateto)){
        $errorMessge="لابد من ادخال التواريخ بشكل سليم";
    }
    if($errorMessage==""){
        echo "<div class='mainForm'>";
        echo "<br>";
        echo "<table style='width:300px;margin:auto;'>";
        echo "<tr><td style='text-align:center;'><button type='button' class='pwdBtn' onclick='window.print();'> طباعة </button></td><td style='text-align:center;'><button type='button' class='cnlBtn' onclick='window.location=\"$filename\";'> تراجع </button></td></tr>";
        echo "</table>";
        echo "<hr>";
        echo "</div>";
        $result=array();
        $i=0;
        $totTrn=0;
        $distFilter="";
        $progFilter="";
        $types="ss";
        $parameters[]=$datefrom;
        $parameters[]=$dateto;
        if($dist != -1){
            $distFilter="AND dist_id=?";
            $types = $types ."i";
            $parameters[]=$dist;
        }
        if($prog != -1){
            $progFilter="AND PrgId=?";
            $types = $types ."i";
            $parameters[]=$prog;
        }
        $q="SELECT CoursId,CoursBulletin,CrsName,CoursFromAct,CoursToAct,CoursFromPln,CouursToPln,dist_name,dist_id,PrgName,PrgId,CrsTHours+CrsPHours AS CrsHours FROM Courses INNER JOIN CoursesGuide ON CoursCrsId=CrsId INNER JOIN districts ON CoursArea=dist_id INNER JOIN Programs ON CrsProgram=PrgId WHERE CoursType=1 AND CoursFromPln>? AND CouursToPln<? $distFilter $progFilter ORDER BY dist_name,PrgName,CoursFromPln";
        if ($stmt = mysqli_prepare($dbc, $q)){
            if(mysqli_stmt_bind_param($stmt,$types,...$parameters)){
                if(mysqli_stmt_execute($stmt)){
                    if(mysqli_stmt_bind_result($stmt,$CoursId,$CoursBulletin,$CrsName,$CoursFromAct,$CoursToAct,$CoursFromPln,$CouursToPln,$dist_name,$dist_id,$PrgName,$PrgId,$CrsHours)){
                            while(mysqli_stmt_fetch($stmt)){
                            $result[$i]['CoursId']=$CoursId;
                            $result[$i]['CoursBulletin']=$CoursBulletin;
                            $result[$i]['CrsName']=$CrsName;
                            $result[$i]['CoursFromAct']=$CoursFromAct;
                            $result[$i]['CoursToAct']=$CoursToAct;
                            $result[$i]['CoursFromPln']=$CoursFromPln;
                            $result[$i]['CouursToPln']=$CouursToPln;
                            $result[$i]['dist_name']=$dist_name;
                            $result[$i]['dist_id']=$dist_id;
                            $result[$i]['PrgName']=$PrgName;
                            $result[$i]['PrgId']=$PrgId;
                            $result[$i]['trnCountPln']=0;
                            $result[$i]['trnCountAct']=0;
                            $result[$i]['trnCountOver']=0;
                            $result[$i]['trnCountUnder']=0;
                            $result[$i]['CrsHours']=$CrsHours;
                            $i++;
                        }
                    }
                }
            }
            mysqli_stmt_close($stmt);
        }
        //get planned trainee count
        $q="SELECT SUM(crscmpPlanned) AS trnCountPln FROM courseCompanies WHERE crscmpCourse=?";
        if ($stmt = mysqli_prepare($dbc, $q)){
            foreach($result as $key => $value){
                $CoursId=$value['CoursId'];
                if(mysqli_stmt_bind_param($stmt,"i",$CoursId)){
                    if(mysqli_stmt_execute($stmt)){
                        if(mysqli_stmt_bind_result($stmt,$trnCountPln)){
                            if(mysqli_stmt_fetch($stmt)){
                                $result[$key]['trnCountPln']=$trnCountPln;                               
                            }
                        }
                    }
                }
            }
            mysqli_stmt_close($stmt);
        }
        //get actual trainee count and diff
        $q="SELECT COUNT(crstrntrainee) AS trnCountAct FROM coursetrainees WHERE crstrncourse=?";
        if ($stmt = mysqli_prepare($dbc, $q)){
            foreach($result as $key => $value){
                $CoursId=$value['CoursId'];
                $crscmpPlanned=$value['trnCountPln'];
                if(mysqli_stmt_bind_param($stmt,"i",$CoursId)){
                    if(mysqli_stmt_execute($stmt)){
                        if(mysqli_stmt_bind_result($stmt,$trnCountAct)){
                            if(mysqli_stmt_fetch($stmt)){
                                $result[$key]['trnCountAct']=$trnCountAct;
                                if($trnCountAct-$crscmpPlanned > 0){
                                    $result[$key]['trnCountOver']=$trnCountAct-$crscmpPlanned;
                                }else{
                                    $result[$key]['trnCountUnder']=$crscmpPlanned-$trnCountAct;
                                }
                                
                            }
                        }
                    }
                }
            }
            mysqli_stmt_close($stmt);
        }
        //echo mysqli_error($dbc);
        echo "
        <div class='mainDoc'>
            <table class='headTable'>
                <tr>
                    <td class='leftLogo'><img src='img/pti.png'></td>
                    <td>
                        <h2>معهد تدريب الموانئ</h2>
                        <h2>إدارة التعليم والتدريب</h2>
                        <h3>تقرير الدورات المخططة من $datefrom الى $dateto</h3>            
                    </td>
                    <td class='rightLogo'><img src='img/aastmt.png'></td>
                </tr>
            </table>";
            echo "<table class='contentTable'>
                <tr>
                    <th style='width: 50px;' rowspan='2'>م</th>
                    <th rowspan='2'>المنشور</th>
                    <th rowspan='2'>البرنامج</th>
                    <th rowspan='2'>المنطقة</th>
                    <th colspan='2'>الفترة</th>
                    <th colspan='4'>عدد المتدربين</th>
                    <th>الساعات</th>
                </tr>
                <tr>
                    <th>من</th>
                    <th>إلى</th>
                    <th>مخطط</th>
                    <th>منفذ</th>
                    <th>زيادة</th>
                    <th>نقص</th>
                </tr>
                ";
        $lastCompany="";
        $lastProgram="";
        $lastArea="";
        $totPlaned=0;
        $totActual=0;
        $totOver=0;
        $totUnder=0;
        $totHours=0;
        $cmpTotPlaned=0;
        $cmpTotActual=0;
        $cmpTotOver=0;
        $cmpTotUnder=0;
        $cmpTotHours=0;
        $firstCompany=true;
        $prgTotPlaned=0;
        $prgTotActual=0;
        $prgTotOver=0;
        $prgTotUnder=0;
        $prgTotHours=0;
        $firstProgram=true;
        $areaTotPlaned=0;
        $areaTotActual=0;
        $areaTotOver=0;
        $areaTotUnder=0;
        $areaTotHours=0;
        $firstArea=true;
        foreach($result as $key => $value){
            $ser=$key+1;
            if($lastProgram != $value['PrgName']){
                if(!$firstProgram){
                    $style="style='text-align:center;padding-right:5px;background-color:#999;color:#fff;'";
                    echo "<tr><td colspan='6' $style>إجمالي $lastProgram</td><td $style>$prgTotPlaned</td><td $style>$prgTotActual</td><td $style>$prgTotOver</td><td $style>$prgTotUnder</td><td $style>$prgTotHours</td></tr>";
                    $prgTotPlaned=0;
                    $prgTotActual=0;
                    $prgTotOver=0;
                    $prgTotUnder=0;
                    $prgTotHours=0;
                }
            }
            if($lastArea != $value['dist_name']){
                if(!$firstArea){
                    $style="style='text-align:center;font-weight:bold;padding-right:5px;background-color:#696969;color:#fff;'";
                    echo "<tr><td colspan='6' $style>إجمالي $lastArea</td><td $style>$areaTotPlaned</td><td $style>$areaTotActual</td><td $style>$areaTotOver</td><td $style>$areaTotUnder</td><td $style>$areaTotHours</td></tr>";
                    $areaTotPlaned=0;
                    $areaTotActual=0;
                    $areaTotOver=0;
                    $areaTotUnder=0;
                    $areaTotHours=0;
                }
                $lastArea=$value['dist_name'];
            }
            if($lastCompany != $value['cmpName']){
                $lastCompany=$value['cmpName'];
                echo "<tr><td colspan='11' style='text-align:right;font-weight:bold;padding-right:5px;background-color:#696969;color:#fff;'>".$value['cmpName']."</td></tr>";
            }
            if($lastProgram != $value['PrgName']){
                $lastProgram=$value['PrgName'];
                echo "<tr><td colspan='11' style='text-align:right;font-weight:bold;padding-right:5px;background-color:#D3D3D3;'>".$value['PrgName']."</td></tr>";
            }
            echo "<tr>
                    <td>$ser</td>
                    <td>".$value['CoursBulletin']."</td>
                    <td>".$value['CrsName']."</td>
                    <td>".$value['dist_name']."</td>
                    <td>".$value['CoursFromAct']."</td>
                    <td>".$value['CoursToAct']."</td>
                    <td>".$value['trnCountPln']."</td>
                    <td>".$value['trnCountAct']."</td>
                    <td>".$value['trnCountOver']."</td>
                    <td>".$value['trnCountUnder']."</td>
                    <td>".$value['CrsHours']."</td>
                </tr>";
                $totPlaned+=$value['trnCountPln'];
                $totActual+=$value['trnCountAct'];    
                $totOver+=$value['trnCountOver'];
                $totUnder+=$value['trnCountUnder'];
                $totHours+=$value['CrsHours'];
                $prgTotPlaned+=$value['trnCountPln'];
                $prgTotActual+=$value['trnCountAct'];    
                $prgTotOver+=$value['trnCountOver'];
                $prgTotUnder+=$value['trnCountUnder'];
                $prgTotHours+=$value['CrsHours'];
                $areaTotPlaned+=$value['trnCountPln'];
                $areaTotActual+=$value['trnCountAct'];    
                $areaTotOver+=$value['trnCountOver'];
                $areaTotUnder+=$value['trnCountUnder'];
                $areaTotHours+=$value['CrsHours'];
                $firstCompany=false;
                $firstProgram=false;
                $firstArea=false;
        }
        $style="style='text-align:center;padding-right:5px;background-color:#999;color:#fff;'";
        echo "<tr><td colspan='6' $style>إجمالي $lastProgram في $lastCompany</td><td $style>$prgTotPlaned</td><td $style>$prgTotActual</td><td $style>$prgTotOver</td><td $style>$prgTotUnder</td><td $style>$prgTotHours</td></tr>";
        $style="style='text-align:center;font-weight:bold;padding-right:5px;background-color:#696969;color:#fff;'";
        echo "<tr><td colspan='6' $style>إجمالي $lastArea</td><td $style>$areaTotPlaned</td><td $style>$areaTotActual</td><td $style>$areaTotOver</td><td $style>$areaTotUnder</td><td $style>$areaTotHours</td></tr>";
        echo "<tr><td colspan='6' >إجمالي</td><td>$totPlaned</td><td>$totActual</td><td>$totOver</td><td>$totUnder</td><td>$totHours</td></tr>";
        echo "
            </table>";
        echo "</div>";
    }
}else{
    echo"
    <h2>معهد تدريب الموانئ</h2>
    <h2>إدارة التعليم والتدريب</h2>
    <h3>تقرير الدورات المخططة مناطق فقط بالساعات</h3>
    <div class='error'>$errorMessage</div>
    <hr>
    <div class='mainForm'>
    <form method='post'>
        من: <input type='date' name='datefrom' value='$datefrom'> إلى: <input type='date' name='dateto' value='$dateto'><br><br>";
    $dists = readDistrcts($dbc);
    echo "المنطقة: ";
    echo "<select name='dist' id='dist'><option value='-1'>حدد المنطقة</option>";
    foreach($dists as $key => $value){
        echo "<option value='$key'";
        if(isset($dist)){
            if($key == $dist){
                echo " selected";
            }    
        }
        echo ">".$value['Name']."</option>";
    }
    echo "</select> ";
    echo "&nbsp;&nbsp;&nbsp;";
    $progs=readPrograms($dbc);
    echo "البرنامج: ";
    echo "<select name='prog' id='prog'><option value='-1'>حدد البرنامج</option>";
    foreach($progs as $key => $value){
        echo "<option value='$key'";
        if(isset($dist)){
            if($key == $dist){
                echo " selected";
            }    
        }
        echo ">".$value['Name']."</option>";
    }
    echo "</select> ";
    echo "<br><br>";
    echo "<input type='hidden' name='showUnfinished' value='0'>";
    //echo "عرض الدورات غير المنتهية: <input type='checkbox' name='showUnfinished' value='1'>";
    echo "<br><br>";
    echo "    <button type='submit' name='mode' value='-1' class='okBtn'> عرض </button>
    </form>
    </div>
    <hr>
";
}
?>
</body>
</html>