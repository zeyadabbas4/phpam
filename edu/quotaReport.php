<?php
//uncomment those two lines for debugging
ini_set('display_errors',1); 
error_reporting(E_ALL);
session_start();
$__systemRoot="../";
$__includeDir="../include";                //path to include directory
include($__systemRoot.'functions.php');        //include system functions
include('../edu/functions.php');         		   //uncomment this line if you have a local functions file
include('appdb.php');             			   //include app database connection
include('only.php');
if(isset($_SESSION['__uid'])){        		   //check for session and set the sesOk variable
    $__uid=$_SESSION['__uid'];
    $sesOk=true;
}else{
    $sesOk=false;
}
//initialize form variables and make database connection
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}

$report=array();
$q="SELECT `staffid`,`staffname`,`lecquota` FROM `staff` INNER JOIN `Lecturers` ON `staffid`=`lecid` WHERE `staffcontract` IN(1,2,4) AND `lecquota`!=0 ORDER BY `staffname`";
if ($stmt = mysqli_prepare($dbc, $q)){
	if(mysqli_stmt_execute($stmt)){
        if(mysqli_stmt_bind_result($stmt, $staffid,$staffname,$lecquota)){
            while(mysqli_stmt_fetch($stmt)){
                $report[$staffid]['staffname']=$staffname;
                $report[$staffid]['lecquota']=$lecquota;
                $report[$staffid]['THours']=0;
                $report[$staffid]['PHours']=0;
            }
        }
    }
	mysqli_stmt_close($stmt);
}
 
$from="2024-07-01";
$to="2025-06-30";
$q="SELECT IFNULL(SUM(`clhHoursT`),0) AS `THours` FROM `CourseLecHours` INNER JOIN `Courses` ON `clhCrsId`=`CoursId`  WHERE `clhLecId`=? AND (CoursFromAct > ? OR CoursToAct>?) AND CoursFromAct < ?";
if ($stmt = mysqli_prepare($dbc, $q)){
    foreach($report as $key => $value){
        if(mysqli_stmt_bind_param($stmt, "isss", $key,$from,$from,$to)){
            if(mysqli_stmt_execute($stmt)){
                if(mysqli_stmt_bind_result($stmt, $THours)){
                    if(mysqli_stmt_fetch($stmt)){
                       $report[$key]['THours']=$THours;
                    }
                }
            }    
        }
    }
    mysqli_stmt_close($stmt);
}
$q="SELECT IFNULL(SUM(`clhHoursP`),0) AS `PHours` FROM `CourseLecHours` INNER JOIN `Courses` ON `clhCrsId`=`CoursId`  WHERE `clhLecId`=? AND (CoursFromAct > ? OR CoursToAct>?) AND CoursFromAct < ?";
if ($stmt = mysqli_prepare($dbc, $q)){
    foreach($report as $key => $value){
        if(mysqli_stmt_bind_param($stmt, "isss", $key,$from,$from,$to)){
            if(mysqli_stmt_execute($stmt)){
                if(mysqli_stmt_bind_result($stmt, $PHours)){
                    if(mysqli_stmt_fetch($stmt)){
                       $report[$key]['PHours']=$PHours;
                    }
                }
            }    
        }
    }
    mysqli_stmt_close($stmt);
}
echo "<div style='text-align:center;font-weight:bold;font-size:x-large;'>بيان ساعات النصاب للسادة محاضري المعهد</div>";
echo "<table border='1' style='margin:auto;direction:rtl;'>";
    echo "<tr>";
    echo "<td style='text-align:center;'>رقم الملف</td>";
    echo "<td style='text-align:center;'>اسم<br>المحاضر</td>";
    echo "<td style='text-align:center;'>النصاب</td>";
    echo "<td style='text-align:center;'>الساعات<br>النظرية</td>";
    echo "<td style='text-align:center;'>الساعات<br>العملية</td>";
    echo "<td style='text-align:center;'>الساعات<br>المنفذة</td>";
    echo "<td style='text-align:center;'>الساعات<br>الزائدة</td>";
    echo "<td style='text-align:center;'>الساعات<br>الناقصة</td>";
    echo "</tr>";
foreach($report as $key => $value){
    if($value['THours']!=0 or $value['PHours']!=0){
        $totHours=$value['THours']+$value['PHours'];
        $hoursDiff=$totHours-$value['lecquota'];
        if($hoursDiff>=0){
            $surplusHours=$hoursDiff;
            $dificitHours="";
        }else{
            $dificitHours=$hoursDiff*-1;
            $surplusHours="";
        }
        echo "<tr>";
        echo "<td style='text-align:center;'>";
            echo $key;
        echo "</td>";
        echo "<td style='text-align:center;'>";
            echo $value['staffname'];
        echo "</td>";
        echo "<td style='text-align:center;'>";
            echo $value['lecquota'];
        echo "</td>";
        echo "<td style='text-align:center;'>";
            echo $value['THours'];
        echo "</td>";
        echo "<td style='text-align:center;'>";
            echo $value['PHours'];
        echo "</td>";
        echo "<td style='text-align:center;'>";
            echo $totHours;
        echo "</td>";
        echo "<td style='text-align:center;'>";
            echo $surplusHours;
        echo "</td>";        
        echo "<td style='text-align:center;'>";
            echo $dificitHours;
        echo "</td>";
        echo "</tr>";
    }
}
echo "</table>";
?>