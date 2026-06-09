<?php
//uncomment those two lines for debugging
ini_set('display_errors',1); 
error_reporting(E_ALL);
session_start();
$__systemRoot="../";
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
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset='utf8'>
        <title>بيان الاشراف لدورة</title>
        <style>
            .prtBtn {
                background-color: Blue;
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
            .cnlBtn {
                background-color: darkred;
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
            .buttons{
                text-align: center;
            }
            @media print{
                .buttons{
                    display: none;
                }
            }
            .mainDoc{
                width:24cm;
                height:18cm;
                margin: auto;
                direction: rtl;
            }
            .pti{
                text-align: right;
                font-size: x-large;
                font-weight: bold;
            }
            .title{
                text-align: center;
                font-size: x-large;
                font-weight: bold;
            }
            .subTitle{
                text-align: center;
                font-size: large;
                font-weight: bold;
            }
            .topTable{
                width: 100%;
                border-style: solid;
                border-width: 1px;
                border-spacing: 0px;
            }
            .topTable td{
                border-style: solid;
                border-width: 1px;
                padding: 5px;
            }
            .supervisorTable{
                width: 100%;
                border-style: solid;
                border-width: 1px;
                border-spacing: 0px;
            }
            .supervisorTable td{
                border-style: solid;
                border-width: 1px;
                padding: 5px;
                text-align: right;
            }
            .mainTable{
                width: 20cm;
                border-style: solid;
                border-width: 1px;
                border-spacing: 0px;
                margin: auto;
            }
            .mainTable td{
                border-style: solid;
                border-width: 1px;
                padding: 5px;
                text-align: center;
            }
            .signatureTable{
                width: 100%;
                border-style: none;
                border-spacing: 0px;
            }
            .signatureTable td{
                border-style: none;
                padding: 5px;
                text-align: center;
                width: 25%;
            }
        </style>
    </head>
    <body onafterprint="window.close();" onload="window.print();">
<?php
$prmOk=true;
if($prmOk){

    if(isset($_SESSION['CoursId'])){
        $CoursId=$_SESSION['CoursId'];
    }
	//initialize form variables and make database connection
	if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
		die("Could not connect to database please contact system admin...");
	}
    $user_year=getuseryear($__uid,$dbc);
    $year=$user_year['Desc'];
    if(!isset($errorMessage)){
        $errorMessage="";
    }
    $q="select CoursCrsId,CoursType,CoursBulletin,CoursFromPln,CouursToPln,CoursFromAct,CoursToAct,CoursSupervisorInt,CoursSupervisorExt,CoursYear,CoursArea,CoursSection,CoursGenRept,CoursAreaRept,CoursLocation,CoursStatus from Courses where CoursId=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
            if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $CoursCrsId,$CoursType,$CoursBulletin,$CoursFromPln,$CouursToPln,$CoursFromAct,$CoursToAct,$CoursSupervisorInt,$CoursSupervisorExt,$CoursYear,$CoursArea,$CoursSection,$CoursGenRept,$CoursAreaRept,$CoursLocation,$CoursStatus)){
                if(!mysqli_stmt_fetch($stmt)){
                    $errorMessage.= "<span dir='ltr'>Error reading data [030105".$__uid.date("YmdHis")."]!...</span><br>";
                }
            }
            }
        }
        mysqli_stmt_close($stmt);
    }      

    $q="select CrstpDescription from CourseTypes Where crstpId=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursType)){
            if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $CrstpDescription)){
                if(!mysqli_stmt_fetch($stmt)){
                    $errorMessage.= "<span dir='ltr'>Error reading data [030105".$__uid.date("YmdHis")."]!...</span><br>";
                }
            }
            }
        }
        mysqli_stmt_close($stmt);
    }

    $q="select CrsProgram,CrsCode,CrsName,CrsTHours,CrsPHours from CoursesGuide where crsId=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursCrsId)){
            if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $CrsProgram,$CrsCode,$CrsName,$CrsTHours,$CrsPHours)){
                if(!mysqli_stmt_fetch($stmt)){
                    $errorMessage.= "<span dir='ltr'>Error reading data [030105".$__uid.date("YmdHis")."]!...</span><br>";
                }
            }
            }
        }
        mysqli_stmt_close($stmt);
    }

    $q="select dist_name from districts where dist_id=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursArea)){
            if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $dist_name)){
                if(!mysqli_stmt_fetch($stmt)){
                    $errorMessage.= "<span dir='ltr'>Error reading data [030105".$__uid.date("YmdHis")."]!...</span><br>";
                }
            }
            }
        }
        mysqli_stmt_close($stmt);
    }
    
    $q="select cmpName from companies where cmpId=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursLocation)){
            if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $location)){
                if(!mysqli_stmt_fetch($stmt)){
                    $errorMessage.= "<span dir='ltr'>Error reading data [030105".$__uid.date("YmdHis")."]!...</span><br>";
                }
            }
            }
        }
        mysqli_stmt_close($stmt);
    }
    $q="SELECT count(crstrnid) AS totTrainees FROM coursetrainees WHERE crstrncourse=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
            if(mysqli_stmt_execute($stmt)){
                if(mysqli_stmt_bind_result($stmt, $totTrainees)){
                    if(!mysqli_stmt_fetch($stmt)){
                        $errorMessage.= "<span dir='ltr'>Error reading data [030105".$__uid.date("YmdHis")."]!...</span><br>";
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    $totActHours=0;
    $lecs=array();
    $q="select staffname,clhHoursP, clhHoursT, lecfees from CourseLecHours inner join Lecturers on clhLecId=lecid inner join staff on clhLecId=staffid where clhCrsId=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
            if(mysqli_stmt_execute($stmt)){
                if(mysqli_stmt_bind_result($stmt, $staffname,$clhHoursP,$clhHoursT,$lecfees)){
                    $i=0;
                    while(mysqli_stmt_fetch($stmt)){
                        $lecs[$i]['lecName']=$staffname;
                        $lecs[$i]['thhrs']=$clhHoursT;
                        $lecs[$i]['prhrs']=$clhHoursP;
                        $lecs[$i]['fees']=$lecfees;
                        $totActHours+=$clhHoursT+$clhHoursP;
                        $i++;
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    $q="SELECT cmpName FROM companies INNER JOIN Courses ON cmpId=CoursSupervisorExt WHERE CoursId=?";
    if($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i",$CoursId)){
            if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt,$extCoursSupervisor)){
                mysqli_stmt_fetch($stmt);
            }
            }
        }
        mysqli_stmt_close($stmt);
    }

    $crsHours=$CrsPHours+$CrsTHours;
    $hidePrint=true;
    include("print.buttons.php");
    echo "
        <div class='mainDoc'>
            <div class='pti'>معهد تدريب الموانئ</div>
            <div class='title'>بيان الاشراف لدورة</div>
            <div class='subTitle'>العام التدريبي: $year &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;نوع الدورة: $CrstpDescription</div>
            <table class='topTable'>
                <tr><td colspan='3' style='width:15cm;'>البرنامج: $CrsName</td><td style='width:3cm;'>رقم: $CoursBulletin</td><td>الساعات المخططة: $crsHours</td></tr>
                <tr><td style='width:9cm;'>المنطقة:$dist_name</td><td colspan='3' style='width:9cm;'>من:$CoursFromAct  الى:$CoursToAct</td><td>الساعات المنفذة: $totActHours</td></tr>
                <tr><td colspan='2' style='width:11.5cm'>مكان الانعقاد: $location</td><td colspan='3'>عدد المتدربين: $totTrainees</td></tr>
            </table>
            <br>
            <table class='supervisorTable'>
                <tr><td>جهة الاشراف: $extCoursSupervisor</td></tr>
            </table>
            <br>            
            <table class='mainTable'>
                <tr><td style='width:1.5cm;'>م</td><td style='width:9.5cm;'>الاسم</td><td style='width:3.5cm;'>القيمة</td><td style='width:5.5cm;'>التوقيع</td></tr>";
                $totAmount=0;
                $ser=1;

                $q="SELECT supSalutation,supName,supAmount FROM Supervisors WHERE supCompany=?";
                if($stmt = mysqli_prepare($dbc, $q)){
                  if(mysqli_stmt_bind_param($stmt, "i",$CoursSupervisorExt)){
                    if(mysqli_stmt_execute($stmt)){
                      if(mysqli_stmt_bind_result($stmt,$supSalutation,$supName,$supAmount)){
                        while(mysqli_stmt_fetch($stmt)){
                          echo "<tr><td>$ser</td><td> $supSalutation / $supName </td><td> $supAmount </td></tr>";
                          $totAmount+=$supAmount;
                          $ser++;
                        }
                      }
                    }
                  }
                  mysqli_stmt_close($stmt);
                }
            $hrSig=getSignatureById($dbc,10);
            $benUserId=getHRAccredation($dbc,$CoursId);
            $benSig=getSignatureByUserId($dbc,$benUserId);
            echo "
            </table>
            <table class='mainTable' style='border-style: none;'>
                <tr><td style='border-style: none;width:1.5cm;'></td><td style='border-style: none;width:9.5cm;'></td><td style='width:3.5cm;'>$totAmount</td><td style='border-style: none;width:5.5cm;'></td></tr>
            </table>

            <br>

            <table class='signatureTable'>
                <tr><td>رئيس وحدة الاستحقاقات</td><td>مدير ادارة الموارد البشرية</td><td>رئيس قسم المراجعة</td><td>مدير ادارة الشئون المالية</td></tr>
                <tr><td>التوقيع</td><td>التوقيع</td><td>التوقيع</td><td>التوقيع</td></tr>
                <tr><td>$benSig</td><td>$hrSig</td><td>&nbsp;</td><td>&nbsp;</td></tr>
            </table>
        </div>";
}
echo "</body></html>";
?>