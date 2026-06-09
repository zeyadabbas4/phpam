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
?>
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset="utf-8">
        <title>المستحقات المالية لدورة</title>
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
            .mainTable{
                width: 100%;
                border-style: solid;
                border-width: 1px;
                border-spacing: 0px;
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
    <body>
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
    //load ref data

    $totActHours=0;
    $lecs=array();
    
    $q="select staffname,clhLecId, clhHoursP, clhHoursT, clhNights, clhratio, clhDistrictFrom, clhDistrictTo, clhDays, clhReturn, lecfees from CourseLecHours inner join Lecturers on clhLecId=lecid inner join staff on clhLecId=staffid where clhCrsId=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
    if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
        if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $staffname,$clhLecId,$clhHoursP,$clhHoursT,$clhNights, $clhratio, $clhDistrictFrom, $clhDistrictTo, $clhDays, $clhReturn,$lecfees)){
                $i=0;
                while(mysqli_stmt_fetch($stmt)){
                    $lecs[$i]['id']=$clhLecId;
                    $lecs[$i]['lecName']=$staffname;
                    $lecs[$i]['thhrs']=$clhHoursT;
                    $lecs[$i]['prhrs']=$clhHoursP;
                    $lecs[$i]['Nights']=$clhNights;
                    $lecs[$i]['ratio']=$clhratio;
                    $lecs[$i]['From']=$clhDistrictFrom;
                    $lecs[$i]['To']=$clhDistrictTo;
                    $lecs[$i]['Days']=$clhDays;
                    $lecs[$i]['Return']=$clhReturn;
                    $lecs[$i]['fees']=$lecfees;
                    $totActHours+=$clhHoursT+$clhHoursP;
                    $i++;
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
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
 
    $fees=array();
    $q="SELECT feeid,feetheoritical,feespractical,feetravel FROM fees";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $feeid,$feetheoritical,$feespractical,$feetravel)){
            while(mysqli_stmt_fetch($stmt)){
                $fees[$feeid]['theoritical']=$feetheoritical;
                $fees[$feeid]['practical']=$feespractical;
                $fees[$feeid]['travel']=$feetravel;
            }
            }
        }
        mysqli_stmt_close($stmt);
    }      

    $q="SELECT cmpName,cmpSupDpt FROM companies INNER JOIN Courses ON cmpId=CoursSupervisorExt WHERE CoursId=?";
    if($stmt = mysqli_prepare($dbc, $q)){
    if(mysqli_stmt_bind_param($stmt, "i",$CoursId)){
        if(mysqli_stmt_execute($stmt)){
        if(mysqli_stmt_bind_result($stmt,$extCoursSupervisor,$cmpSupDpt)){
            mysqli_stmt_fetch($stmt);
        }
        }
    }
    mysqli_stmt_close($stmt);
    }

    include("$__includeDir/readDistricts.php");

    $q="SELECT supSalutation,supName,supAmount FROM Supervisors WHERE supCompany=?";
    $totSupAmount=0;
    if($stmt = mysqli_prepare($dbc, $q)){
      if(mysqli_stmt_bind_param($stmt, "i",$CoursSupervisorExt)){
        if(mysqli_stmt_execute($stmt)){
          if(mysqli_stmt_bind_result($stmt,$supSalutation,$supName,$supAmount)){
            while(mysqli_stmt_fetch($stmt)){
                $totSupAmount += $supAmount;
            }
          }
        }
      }
      mysqli_stmt_close($stmt);
    }
    $totSupAmount=number_format($totSupAmount,0);
    $crsHours=$CrsPHours+$CrsTHours;
    include("print.buttons.php");
	echo "
	<div class='mainDoc'>
	<div class='pti'>معهد تدريب الموانئ</div>
	<div class='title'>المستحقات المالية لدورة</div>
	<div class='subTitle'>العام التدريبي: $year &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;نوع الدورة: $CrstpDescription</div>
	<table class='topTable'>
		<tr><td colspan='3' style='width:15cm;'>البرنامج: $CrsName</td><td style='width:3cm;'>رقم: $CoursBulletin</td><td>الساعات المخططة: $crsHours</td></tr>
		<tr><td style='width:9cm;'>المنطقة: $dist_name </td><td colspan='3' style='width:9cm;'>من:$CoursFromAct  الى:$CoursToAct</td><td>الساعات المنفذة: $totActHours</td></tr>
		<tr><td colspan='2' style='width:11.5cm'>مكان الانعقاد: $location</td><td colspan='3'>عدد المتدربين: $totTrainees</td></tr>
	</table>
	<br>
	<table class='mainTable'>
		<tr><td style='width:1cm;' rowspan='3'>م</td><td style='width:4cm;' rowspan='3' colspan='2'>المحاضر</td><td colspan='5' style='width:1cm;'>التدريس</td><td colspan='3' style='width:1cm;'>السفر</td><td colspan='3' style='width:1.6cm;'>الانتقال</td><td style='width:2cm;' rowspan='3'>إجمالي المحاضر</td><td rowspan='3'>التوقيع بالاستلام</td></tr>
		<tr><td colspan='2'>عدد الساعات</td><td colspan='2'>الفئة</td><td rowspan='2'>إجمالي التدريس</td><td rowspan='2'>عدد الليالي</td><td rowspan='2'>الفئة</td><td rowspan='2'>بدل السفر</td><td rowspan='2'>جهة الانتقال</td><td rowspan='2'>الفئة</td><td rowspan='2'>بدل الانتقال</td></tr>
		<tr><td>نظري</td><td>عملي</td><td>نظري</td><td>عملي</td></tr>";
    $totThours=0;
    $totPhours=0;
    $totThoursFees=0;
    $totPhoursFees=0;
    $totCrsLecFees=0;
    foreach($lecs as $key => $value){
      $ser=$key+1;
      $lectId=$value['id'];
      $lecName=$value['lecName'];
      $tHours=number_format($value['thhrs'],0);
      $tHoursFees=$fees[$lecs[$key]['fees']]['theoritical']*$tHours;
      $tFees=$fees[$lecs[$key]['fees']]['theoritical'];
      $pHours=number_format($value['prhrs'],0);
      $pHoursFees=$fees[$lecs[$key]['fees']]['practical']*$pHours;
      $pFees=$fees[$lecs[$key]['fees']]['practical'];
      $noNights=$value['Nights'];
      if($noNights != 0){
        $ratio=$value['ratio'];
        $trvlFees=get_TravelData($dbc,$lectId);
        $trvlAllowCat=$trvlFees;
        $TrvlAllowance=$trvlFees * $noNights * $ratio / 100;
        $to=$value['To'];
        $from=$value['From'];
        $trvlDistination=$dists[$to];
        $reloctionAllowanceCat=getTransportFees($dbc,$from,$to);
        $returnReloctionAllowanceCat=getTransportFees($dbc,$to,$from);
        $transportCount=$value['Days'];
        $return=$value['Return'];
        $reloctionAllowance = $reloctionAllowanceCat * $transportCount + $returnReloctionAllowanceCat * $transportCount * $return;  
      }else{
        $trvlAllowCat=0;
        $TrvlAllowance=0;
        $trvlDistination="بدون بدل انتقال";
        $reloctionAllowance=0;
        $reloctionAllowanceCat=0;
  
      }
      $totLecHoursFees=$tHoursFees+$pHoursFees;
      $totLecFees=$totLecHoursFees+$reloctionAllowance+$TrvlAllowance;
      $totCrsLecFees+=$totLecFees;
      echo "<tr><td>$ser</td><td colspan='2'>$lecName</td><td>$tHours</td><td>$pHours</td><td>$tFees</td><td>$pFees</td><td>$totLecHoursFees</td><td>$noNights</td><td>$trvlAllowCat</td><td>$TrvlAllowance</td><td>$trvlDistination</td><td>$reloctionAllowanceCat</td><td>$reloctionAllowance</td><td>$totLecFees</td><td></td></tr>";
    }
    $totCrsFees=$totCrsLecFees+$totSupAmount;
    $totCrsFeesLetters=only($totCrsFees,1,"جنيه","جنيهات");
    echo "
		<tr><td colspan='14'>إجمالي مستحقات التدريس</td><td>$totCrsLecFees</td><td></td></tr>
		<tr><td colspan='2'>جهة الاشراف</td><td colspan='8'>$extCoursSupervisor</td><td>اﻹدارة</td><td colspan='4'>$cmpSupDpt</td><td></td></tr>
		<tr><td colspan='14'>قيمة الاشراف</td><td>$totSupAmount</td><td></td></tr>
		<tr><td colspan='14'>اﻹجمالي الكلي $totCrsFeesLetters </td><td>$totCrsFees</td><td></td></tr>
	</table>
	<br>
	مرفق فاتورة فندق: نعم  <input type='checkbox'> لا <input type='checkbox'>
	<table class='signatureTable'>
		<tr><td>رئيس وحدة الاستحقاقات</td><td>رئيس قسم الموارد البشرية</td><td>رئيس قسم المراجعة</td><td>مدير ادارة الشئون المالية</td></tr>
		<tr><td>التوقيع</td><td>التوقيع</td><td>التوقيع</td><td>التوقيع</td></tr>
	</table>
</div>
";
}
echo "</body></html>";
?>