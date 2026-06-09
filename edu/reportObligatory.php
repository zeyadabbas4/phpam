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
    <title>تقرير الدورات الحتمية</title>
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
    if(!date_parse($datefrom) or !date_parse($dateto)){
        $errorMessge="لابد من ادخال التواريخ بشكل سليم";
    }
    if($errorMessage==""){
        echo "<div class='mainForm'>";
        echo "<br><button type='button' class='pwdBtn' onclick='window.print();'> طباعة </button> <button type='button' class='cnlBtn' onclick='window.location=\"$filename\";'> تراجع </button><br>";
        echo "<hr>";
        echo "</div>";
        $result=array();
        $i=0;
        $totTrn=0;
        $q="SELECT CoursId,CoursBulletin,CrsName,CoursFromAct,CoursToAct,COUNT(crstrnid) AS traineeCount,CoursType FROM Courses INNER JOIN CoursesGuide ON CoursCrsId= CrsId LEFT JOIN coursetrainees ON CoursId=crstrncourse GROUP BY crstrncourse HAVING CoursFromAct>=? AND CoursFromAct<=? AND CoursType=4 ORDER BY CoursFromAct";
        if ($stmt = mysqli_prepare($dbc, $q)){
            if(mysqli_stmt_bind_param($stmt,"ss",$datefrom,$dateto)){
                if(mysqli_stmt_execute($stmt)){
                    if(mysqli_stmt_bind_result($stmt,$CoursId,$CoursBulletin,$CrsName,$CoursFromAct,$CoursToAct,$traineeCount,$CoursType)){
                            while(mysqli_stmt_fetch($stmt)){
                            $result[$i]['CoursId']=$CoursId;
                            $result[$i]['CoursBulletin']=$CoursBulletin;
                            $result[$i]['CrsName']=$CrsName;
                            $result[$i]['CoursFromAct']=$CoursFromAct;
                            $result[$i]['CoursToAct']=$CoursToAct;
                            $result[$i]['traineeCount']=$traineeCount;
                            $i++;        
                            $totTrn+=$traineeCount;                
                        }
                    }
                }    
            }            
            mysqli_stmt_close($stmt);
        }
        echo "
        <div class='mainDoc'>
            <table class='headTable'>
                <tr>
                    <td class='leftLogo'><img src='img/pti.png'></td>
                    <td>
                        <h2>معهد تدريب الموانئ</h2>
                        <h2>إدارة التعليم والتدريب</h2>
                        <h3>تقرير الدورات الحتمية من $datefrom وحتى $dateto</h3>            
                    </td>
                    <td class='rightLogo'><img src='img/aastmt.png'></td>
                </tr>
            </table>
            <table class='contentTable'>
                <tr>
                    <th style='width: 50px;'>م</th>
                    <th>رقم الاخطار</th>
                    <th>إسم الدورة</th>
                    <th>تاريخ البداية</th>
                    <th>تاريخ النهاية</th>
                    <th>عدد المتدربين</th>
                </tr>";
        foreach($result as $key => $value){
            $ser=$key+1;
            echo "<tr>
                    <td>$ser</td>
                    <td>".$value['CoursBulletin']."</td>
                    <td>".$value['CrsName']."</td>
                    <td>".$value['CoursFromAct']."</td>
                    <td>".$value['CoursToAct']."</td>
                    <td>".$value['traineeCount']."</td>
                </tr>";
        }
        echo "<tr><td colspan='5' >إجمالي</td><td>$totTrn</td></tr>";
        echo "
            </table>";
        echo "</div>";        
    }
}

else{
    echo"
    <h2>معهد تدريب الموانئ</h2>
    <h2>إدارة التعليم والتدريب</h2>
    <h3>تقرير الدورات الحتمية</h3>
    <div class='error'>$errorMessage</div>
    <hr>
    <div class='mainForm'>
    <form method='post'>
        من: <input type='date' name='datefrom' value='$datefrom'> إلى: <input type='date' name='dateto' value='$dateto'><br><br>
        <button type='submit' name='mode' value='-1' class='okBtn'> عرض </button>
    </form>
    </div>
    <hr>
";
}
?>
</body>
</html>
