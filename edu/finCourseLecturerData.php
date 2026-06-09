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
        <meta charset='utf8'>
        <title>بيانات المحاضرين بالدورات</title>
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
            .maindoc{
                width: 18cm;
                border-width: 1px;
                border-color: black;
                border-style: solid;
                padding: 10px;
                direction: rtl;
                margin: auto;
            }
            .ptiLogo{
                width:2cm;
                float: left;
            }
            .ptiLogo img{
                width: 2cm;
            }
            .reportTitle{
                color: grey;
                font-size: x-large;
                font-weight: bold;
                float: right;
                width: 16cm;
                text-align: right;
            }
            .reportData{
                width: 100%;
            }
            .sectionTitle{
                color: grey;
                font-size: x-large;
                font-weight: bold;
                width: 100%;
                text-align: right;
                padding: 5px;
            }
            .itemTitles{
                width: 100%;
                text-align: right;
                font-weight: bold;
                font-size: large;
                padding: 5px;
            }
            .itemData{
                text-align: right;
                font-size: large;
            }
            .lecTable{
                width: 100%;
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
    if(isset($_SESSION['clhPeriod'])){
        $clhPeriod=$_SESSION['clhPeriod'];
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
    $q="select CoursCrsId,CoursType,CoursBulletin,CoursFromPln,CouursToPln,CoursFromAct,CoursToAct,CoursSupervisorInt,CoursSupervisorExt,CoursYear,CoursArea,CoursSection,CoursGenRept,CoursAreaRept,CoursLocation,CoursStatus,CoursDescription from Courses where CoursId=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
            if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $CoursCrsId,$CoursType,$CoursBulletin,$CoursFromPln,$CouursToPln,$CoursFromAct,$CoursToAct,$CoursSupervisorInt,$CoursSupervisorExt,$CoursYear,$CoursArea,$CoursSection,$CoursGenRept,$CoursAreaRept,$CoursLocation,$CoursStatus,$CoursDescription)){
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
    $q="SELECT PrgName FROM Programs INNER JOIN CoursesGuide ON PrgId = CrsProgram WHERE CrsId=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursCrsId)){
            if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $program)){
                if(!mysqli_stmt_fetch($stmt)){
                    $errorMessage.= "<span dir='ltr'>Error reading data [030105".$__uid.date("YmdHis")."]!...</span><br>";
                }
            }
            }
        }
        mysqli_stmt_close($stmt);
    }
    //read course company
    $q="SELECT cmpName FROM conCourses INNER JOIN companies ON concrsCmp=cmpId WHERE concrsId=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
            if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $cmpName)){
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
    $couseDevidable=courseIsDevidable($dbc,$CoursId);
    if($couseDevidable){
        $q="select staffname,clhLecId, clhHoursP, clhHoursT, clhNights, clhratio, clhDistrictFrom, clhDistrictTo, clhDays, clhReturn, lecfees from CourseLecHours inner join Lecturers on clhLecId=lecid inner join staff on clhLecId=staffid where clhPeriod=?";
        $par=$clhPeriod;
    }else{
        $q="select staffname,clhLecId, clhHoursP, clhHoursT, clhNights, clhratio, clhDistrictFrom, clhDistrictTo, clhDays, clhReturn, lecfees from CourseLecHours inner join Lecturers on clhLecId=lecid inner join staff on clhLecId=staffid where clhCrsId=?";
        $par=$CoursId;
    }

    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $par)){
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
    $q="select staffname FROM staff where staffid=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursSupervisorInt)){
            if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $supervisorInt)){
                if(!mysqli_stmt_fetch($stmt)){
                    $errorMessage.= "<span dir='ltr'>Error reading data [030105".$__uid.date("YmdHis")."]!...</span><br>";
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
    $q="SELECT sum(crscmpPlanned) FROM courseCompanies WHERE crscmpCourse=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
            if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $totPlannedTrainees)){
                if(!mysqli_stmt_fetch($stmt)){
                    $errorMessage.= "<span dir='ltr'>Error reading data [030105".$__uid.date("YmdHis")."]!...</span><br>";
                }
            }
            }
        }
        mysqli_stmt_close($stmt);
    }
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
    if($CoursType != 1){
        $totSupAmount=0;
    }
    $totSupAmount=number_format($totSupAmount,0);
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
    include("$__includeDir/readDistricts.php");

    $yearLabel="العام التدريبي";
    if($CoursType == 5){
        $year=getYear($dbc,$CoursYear)['Desc'];
        $yearLabel="الدفعة";
    }
    $yearLabel="العام التدريبي";
    if($CoursType == 5){
        $year=getYear($dbc,$CoursYear)['Desc'];
        $yearLabel="الدفعة";
        $CrsName = $CoursDescription;
    }
    $datesLabel="";
    $crsHoursLabel="الساعات المخططة :";
    if($couseDevidable){
        $period=readPeriod($dbc,$clhPeriod);
        $CoursFromAct=$period['From'];
        $CoursToAct=$period['To'];
        $datesLabel="عن الفترة";
    }
    if($CoursType ==2 or $CoursType==5){
        $totTrainees=getConTrnData($dbc,$CoursId,true)['TrnCount'];
        $crsHours="";
        $crsHoursLabel="";
    }
    $hidePrint=true;
    include("print.buttons.php");
    echo "  <div class='maindoc'>
            <div class='ptiLogo'><img src='img/pti.png'></div>
            <div class='reportTitle'>بيانات المحاضرين بالدورات</div>
            <br><br><br><br>
            <div class='reportData'>
                <div class='itemTitles'>$yearLabel:<span class='itemData'> $year </span></div>
                <div class='itemTitles'>رقم المنشور:<span class='itemData'> $CoursBulletin</span></div>
                <div class='itemTitles'>نوع الدورة:<span class='itemData'> $CrstpDescription</span></div>
                <div class='itemTitles'>المنطقة:<span class='itemData'> $dist_name</span></div>
                <hr>
                <div class='sectionTitle'>بيانات الدورة</div>
                <hr>
                <div class='itemTitles'>تصنيف البرنامج:<span class='itemData'> $program</span></div>
                <div class='itemTitles'>البرنامج:<span class='itemData'> $CrsName</span></div>
                <div class='itemTitles'>عدد الساعات:<span class='itemData'> $totActHours</span></div>
                <div class='itemTitles'>خلال الفترة من:<span class='itemData'> $CoursFromAct</span></div>
                <div class='itemTitles'>الى:<span class='itemData'> $CoursToAct</span></div>";
                if($CoursType != 5 and $CoursType != 2){
                    echo "<div class='itemTitles'>تحت اشراف الحالي:<span class='itemData'> $extCoursSupervisor</span></div>";
                }
                echo  "<div class='itemTitles'>مكان الانعقاد الحالي:<span class='itemData'> $location</span></div>";
                if($CoursType == 2){
                    echo "<div class='itemTitles'>الجهة:<span class='itemData'> $extCoursSupervisor </span></div>";
                }
                echo "<hr><hr>";
                if($CoursType != 5 and $CoursType != 2){
                echo "<div class='itemTitles'>إجمالي المتدربين المخطط:<span class='itemData'> $totPlannedTrainees</span></div>";
                }
                echo "<div class='itemTitles'>إجمالي المتدربين الفعلي:<span class='itemData'> $totTrainees</span></div>
                <hr>
                <hr>
                <div class='sectionTitle'>المحاضرين</div>
                <table class='lecTable'>
                    <tr><td>المحاضر</td><td>س النظري</td><td>س العملي</td><td>التدريس</td><td>عدد الليالي</td><td>النسبة</td><td>الانتقال</td><td>عدد ايام الانتقال</td><td>الاجمالي</td><td>العوده</td><td>ف نظري</td><td>ف عملي</td><td>ب سفر</td></tr>";
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
                    }else{
                        $trvlAllowCat=0;
                        $TrvlAllowance=0;
                        $ratio=0;    
                    }
                    $relocationDays=$value['Days'];
                    if($relocationDays!=0){
                        $to=$value['To'];
                        $from=$value['From'];
                        $trvlDistination=$dists[$to];
                        $reloctionAllowanceCat=getTransportFees($dbc,$from,$to);
                        $returnReloctionAllowanceCat=getTransportFees($dbc,$to,$from);
                        $relocationDays=$value['Days'];
                        $return=$value['Return'];
                        $reloctionAllowance = $reloctionAllowanceCat * $relocationDays + $returnReloctionAllowanceCat * $relocationDays * $return;                  
                    }else{
                      $trvlDistination="بدون بدل انتقال";
                      $reloctionAllowance=0;
                      $reloctionAllowanceCat=0;
                    }              
                    $totNoTravel=$tHoursFees+$pHoursFees+$reloctionAllowance;
                    $totLecFees=$tHoursFees+$pHoursFees+$reloctionAllowance+$TrvlAllowance;
                    $totCrsLecFees+=$totLecFees;
                    $return=0;
                    echo"<tr><td>$ser-$lecName</td><td>$tHours</td><td>$pHours</td><td>$totLecFees</td><td>$noNights</td><td>$ratio</td><td>$reloctionAllowance</td><td>$relocationDays</td><td>$totNoTravel</td><td>$return</td><td>$tFees</td><td>$pFees</td><td>$TrvlAllowance</td></tr>";
                }
                $totCrsFees=$totCrsLecFees+$totSupAmount;
                echo"
                </table>                
                <div class='itemTitles'>ارفاق سجل الدورة:<span class='itemData'> </span></div>
                <div class='itemTitles'>ارفاق استطلاع الرأي:<span class='itemData'> </span></div>
                <div class='itemTitles'>ارفاق اي فواتير / مستندات:<span class='itemData'> </span></div>
                <br>";
                if($CoursType != 5 and $CoursType != 2){
                echo "
                <div class='itemTitles'>الاشراف:<span class='itemData'> $extCoursSupervisor</span></div>
                <div class='itemTitles'>قيمة الاشراف:<span class='itemData'> $totSupAmount</span></div>
                ";
                }
                echo "<div class='itemTitles'>الاجمالي العام لتكلفة الدورة:<span class='itemData'> $totCrsFees</span></div>";
                //echo "<div class='itemTitles'>اعتماد رئيس القسم المختص:<span class='itemData'> </span></div>";
                $sigTitle="مدير إدارة التعليم";
                if($CoursType==4){
                    $sigTitle="مدير إدارة تطوير الأعمال";
                }
                echo "<br><div class='itemTitles'>اعتماد $sigTitle:<span class='itemData'> ";
                $sigId=4;
                if($CoursType==4){
                    $sigId=13;
                }
                echo getSignatureById($dbc,$sigId,100);
                echo "</span></div>
                <br><br>
                <hr>
                <hr>
            </div>
        </div>";
}
echo"</body></html>";
?>