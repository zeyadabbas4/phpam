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
        <title>بيان الانجاز السنوى</title>
        <meta charset="utf8">
        <style>
            *{
                font-family: 'Times New Roman', Times, serif;
            }
            h2{
                text-align: center;
                text-decoration: underline;
            }
            .yearForm{
                text-align: center;
                direction:rtl;
                width:250px;
                margin: auto;
                font-size: large;
            }
            .main-doc{
                width: 18cm;
                height: 28cm;
                margin: auto;
            }
            .doc-title{
                direction: rtl;
                text-align: center;
                font-size: large;
                font-weight: bold;
                text-decoration: underline;
                margin-bottom: 25px;
                font-family: 'Times New Roman', Times, serif;
            }
            .main-table{
                direction: rtl;
                border-collapse: collapse;
                width: 100%;
                border: 1px solid #000;
                font-size: 14pt;
                font-family: 'Times New Roman', Times, serif;
            }
            th, td{
                text-align: center;
                padding: 10px;
                border: 1px solid #000;
            }
            .salutation{
                direction: rtl;
                text-align:center;
                font-size: 14pt;
                font-family: 'Times New Roman', Times, serif;
                margin-top: 25px;
            }
            .signature{
                direction: rtl;
                width: 50%;
                margin-left: 0px;
                text-align:center;
                font-size: 14pt;
                font-family: 'Times New Roman', Times, serif;
                margin-top: 25px;
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
            .okBtn {
				background-color: indigo;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.okBtn:hover {
				opacity: 1;
			}
            .delBtn {
				background-color: darkred;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.delBtn:hover {
				opacity: 1;
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
			}
            .alertmessages{
				color: red;
                font-weight: bold;
				font-size: medium;
				text-align: center;
			}
            .frmButtons{
                direction: rtl;
                text-align: center;
            }
            @media print{
                .frmButtons{
                    display: none;
                }
            }
        </style>
    </head>
    <body>
<?php
    if($sesOk){
        //session is up check for user permission!...   
        $filename=basename(__FILE__);                               //get script name
        $mnuId=getCommandMenuId($filename);                         //get sreen id
        if(checkUserMenuItem($__uid,$mnuId)){                       //check for user permission to use the screen
            $prmOk=true;
        }else{
            $prmOk=false;
            echo "<br><div class='alertmessages' style='direction: ltr'>Access denied!...</div>";    
        }
    }else{
        //session not up display error!...
        echo "<br><div class='alertmessages' style='direction: ltr'>Session expired please re-login!...</div>";
    }
    if($prmOk){
        //read post data
        foreach($_POST as $key => $value){
            $$key=$value;
        }
        //initialize database connection
        if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
            die("Could not connect to database please contact system admin...");
        }
        if(!isset($mode)){
            $mode="";
        } 
        if($mode=="showRep" and $repYear==-1){
            $mode="";
            $errorMessage="لابد  من تحديد السنة";
        }
        if($mode=="showRep"){
            $months=array("يناير","فبراير","مارس","ابريل","مايو","يونيو","يوليو","أغسطس","سبتمبر","أكتوبر","نوفمبر","ديسمبر");
            echo "<div class='frmButtons'>";
            echo "<button type='button' class='pwdBtn' onclick='window.print();'>طباعة</button> ";
            echo "<button type='button' class='delBtn' onclick='window.location=\"$filename\";'>إغلاق</button> ";
            echo "<hr>";
            echo "</div>";    
            echo"
            <div class='main-doc'>
                <div class='doc-title'>
                    بيان اﻹنجاز السنوي لعام 2023 لقسم البرامج غير المخططة
                </div>
                <table class='main-table'>
                    <tr>
                        <th>الشهر</th><th>عدد دورات</th><th>عدد متدربين</th><th>القيمة بالمصري</th><th>القيمة بالدولار</th><th>القيمة باليورو</th>
                    </tr>";
            //calc number of courses
            $courseCounts=array();
            $NumCourses=0;
            $q="SELECT COUNT(CoursId) AS courseCount, MONTH(CoursFromAct) AS CourseMonth FROM Courses WHERE CoursType=2 AND YEAR(CoursFromAct) = ? GROUP BY CourseMonth";
            if ($stmt = mysqli_prepare($dbc, $q)){
                if(mysqli_stmt_bind_param($stmt, "i", $repYear)){
                    if(mysqli_stmt_execute($stmt)){
                        if(mysqli_stmt_bind_result($stmt, $courseCount,$CourseMonth)){
                            while(mysqli_stmt_fetch($stmt)){
                                $courseCounts[$CourseMonth]=$courseCount;
                                $NumCourses+=$courseCount;
                            }
                        }
                    }    
                }
                mysqli_stmt_close($stmt);
            }
            //calc trn count
            $trnCounts=array();
            $totTrn=0;
            $q="SELECT SUM(concrsTrnCount) AS trnCount,MONTH(CoursFromAct) AS CourseMonth FROM conCourses INNER JOIN Courses ON concrsId=CoursId WHERE YEAR(CoursFromAct) = ? GROUP BY CourseMonth";
            if ($stmt = mysqli_prepare($dbc, $q)){
                if(mysqli_stmt_bind_param($stmt, "i", $repYear)){
                    if(mysqli_stmt_execute($stmt)){
                        if(mysqli_stmt_bind_result($stmt, $trnCount,$CourseMonth)){
                            while(mysqli_stmt_fetch($stmt)){
                                $trnCounts[$CourseMonth]=$trnCount;
                                $totTrn+=$trnCount;
                            }
                        }
                    }    
                }
                mysqli_stmt_close($stmt);
            }
            //calc EGP cost
            $EGPCosts=array();
            $totEGPCost=0;
            $q="SELECT SUM(concrsCost) AS EGPCost,MONTH(CoursFromAct) AS CourseMonth FROM conCourses INNER JOIN Courses ON concrsId=CoursId WHERE YEAR(CoursFromAct) = ? AND concrsCurrency=1 GROUP BY CourseMonth";
            if ($stmt = mysqli_prepare($dbc, $q)){
                if(mysqli_stmt_bind_param($stmt, "i", $repYear)){
                    if(mysqli_stmt_execute($stmt)){
                        if(mysqli_stmt_bind_result($stmt, $EGPCost,$CourseMonth)){
                            while(mysqli_stmt_fetch($stmt)){
                                $EGPCosts[$CourseMonth]=$EGPCost;
                                $totEGPCost+=$EGPCost;
                            }
                        }
                    }    
                }
                mysqli_stmt_close($stmt);
            }
            //calc USD cost
            $USDCosts=array();
            $totUSDCost=0;
            $q="SELECT SUM(concrsCost) AS USDCost,MONTH(CoursFromAct) AS CourseMonth FROM conCourses INNER JOIN Courses ON concrsId=CoursId WHERE YEAR(CoursFromAct) = ? AND concrsCurrency=2 GROUP BY CourseMonth";
            if ($stmt = mysqli_prepare($dbc, $q)){
                if(mysqli_stmt_bind_param($stmt, "i", $repYear)){
                    if(mysqli_stmt_execute($stmt)){
                        if(mysqli_stmt_bind_result($stmt, $USDCost,$CourseMonth)){
                            while(mysqli_stmt_fetch($stmt)){
                                $USDCosts[$CourseMonth]=$USDCost;
                                $totUSDCost+=$USDCost;
                            }
                        }
                    }    
                }
                mysqli_stmt_close($stmt);
            }
            //calc EURO cost
            $EURCosts=array();
            $totEURCost=0;
            $q="SELECT SUM(concrsCost) AS EURCost,MONTH(CoursFromAct) AS CourseMonth FROM conCourses INNER JOIN Courses ON concrsId=CoursId WHERE YEAR(CoursFromAct) = ? AND concrsCurrency=3 GROUP BY CourseMonth";
            if ($stmt = mysqli_prepare($dbc, $q)){
                if(mysqli_stmt_bind_param($stmt, "i", $repYear)){
                    if(mysqli_stmt_execute($stmt)){
                        if(mysqli_stmt_bind_result($stmt, $EURCost,$CourseMonth)){
                            while(mysqli_stmt_fetch($stmt)){
                                $EURCosts[$CourseMonth]=$EURCost;
                                $totEURCost+=$EURCost;
                            }
                        }
                    }    
                }
                mysqli_stmt_close($stmt);
            }
            for($i=0;$i<12;$i++){
                $Mon=$i+1;
                echo "<tr><td>$months[$i]</td><td>";
                if(isset($courseCounts[$Mon])){
                    echo $courseCounts[$Mon];
                }
                echo "</td><td>";
                if(isset($trnCounts[$Mon])){
                    echo $trnCounts[$Mon];
                }
                echo "</td><td>";
                if(isset($EGPCosts[$Mon])){
                    echo $EGPCosts[$Mon];
                }
                echo "</td><td>";
                if(isset($USDCosts[$Mon])){
                    echo $USDCosts[$Mon];
                }
                echo "</td><td>";
                if(isset($EURCosts[$Mon])){
                    echo $EURCosts[$Mon];
                }
                echo "</td></tr>";
            }
            echo"
                    <tr>
                        <th>اﻻجمالي</th><th>$NumCourses</th><th>$totTrn</th><th>$totEGPCost</th><th>$totUSDCost</th><th>$totEURCost</th>
                    </tr>
                </table>
                <div class='salutation'>وتفضلوا بقبول فائق الاحترام،،،</div>
                <div class='signature'>دكتور/عصام الدين مصطفى<br><br>رئيس قسم البرامج غير المحططة</div>
            </div>";    
        }else{
            if(isset($errorMessage)){
                echo "<div class='alertmessages'>$errorMessage</div>";
            }
            echo "<h2>بيان اﻹنجاز السنوي لقسم البرامج غير المخططة</h2>";
            echo "<div class='yearForm'>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='mode' value='showRep'>";
            echo "<select name='repYear' class='input-field'><option value='-1'>-- حدد السنة --</option>";
            $q="SELECT DISTINCT year(CoursFromAct) AS crsYear FROM Courses WHERE CoursType=2";
            if ($stmt = mysqli_prepare($dbc, $q)) {
                if (mysqli_stmt_execute($stmt)) {
                    if (mysqli_stmt_bind_result($stmt, $crsYear)) {
                        while (mysqli_stmt_fetch($stmt)) {
                            echo "<option value='$crsYear'> $crsYear </option>";
                        }
                    }
                }
                mysqli_stmt_close($stmt);
            }
            echo "</select>";
            echo "<br><br>";
            echo "<button class='okBtn' type='submit'>عرض</button>";
            echo "</form>";
            echo "</div>";
        }

    }
?>
    </body>
</html>