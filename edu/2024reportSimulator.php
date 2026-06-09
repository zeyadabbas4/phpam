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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>دورات المحاكي من  1-4 وحتى 30-6-2024</title>
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
$result=array();
$i=0;
$q="SELECT CoursId,CoursBulletin,CrsName,CoursFromAct,CoursToAct,COUNT(crstrnid) AS traineeCount,CoursType,crstrncompany,cmpName FROM Courses INNER JOIN CoursesGuide ON CoursCrsId= CrsId LEFT JOIN coursetrainees ON CoursId=crstrncourse INNER JOIN companies ON crstrncompany=cmpId GROUP BY crstrncourse HAVING CoursFromAct>='2024-04-1' AND CoursFromAct<='2024-06-30' AND CoursType=3 ORDER BY CoursType,CoursFromAct;";
if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt,$CoursId,$CoursBulletin,$CrsName,$CoursFromAct,$CoursToAct,$traineeCount,$CoursType,$crstrncompany,$cmpName)){
                    while(mysqli_stmt_fetch($stmt)){
                    $result[$i]['CoursId']=$CoursId;
                    $result[$i]['CoursBulletin']=$CoursBulletin;
                    $result[$i]['CrsName']=$CrsName;
                    $result[$i]['CoursFromAct']=$CoursFromAct;
                    $result[$i]['CoursToAct']=$CoursToAct;
                    $result[$i]['traineeCount']=$traineeCount;
                    $result[$i]['cmpName']=$cmpName;
                    $i++;                        
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
                <h3>دورات المحاكي من  1-4 وحتى 30-6-2024</h3>            
            </td>
            <td class='rightLogo'><img src='img/aastmt.png'></td>
        </tr>
    </table>
    <table class='contentTable'>
        <tr>
            <th style='width: 50px;'>م</th>
            <th>رقم المنشور</th>
            <th>إسم الدورة</th>
            <th>تاريخ البداية</th>
            <th>تاريخ النهاية</th>
            <th>الجهة</th>
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
            <td>".$value['cmpName']."</td>
            <td>".$value['traineeCount']."</td>
        </tr>";
}
echo "
    </table>";
echo "</div>";
?>
</body>
</html>
