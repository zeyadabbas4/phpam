
<?php
session_start();
$__systemRoot="../";  
include($__systemRoot."functions.php"); 
include("appdb.php");                       //application database credentials
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
$CoursId=$_SESSION['CoursId'];
//Read districts
$dists=array();
$q="SELECT dist_id,dist_name from districts";
$r=mysqli_query($dbc,$q);
if($r){
  while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
    foreach($row as $key => $value){
      $$key=$value;
    }
    $dists[$dist_id]=$dist_name;
  }
}
//Read countries
$cunts=array();
$cuntse=array();
$q="SELECT cntId,CntArabName,cntName from Countries";
$r=mysqli_query($dbc,$q);
if($r){
  while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
    foreach($row as $key => $value){
      $$key=$value;
    }
    $cunts[$cntId]=$CntArabName;
    $cuntse[$cntId]=$CntName;
  }
}
//Read governrates
$govs=array();
$govse=array();
$q="SELECT GovId,GovName,GovNameEng from Governrates";
$r=mysqli_query($dbc,$q);
if($r){
  while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
    foreach($row as $key => $value){
      $$key=$value;
    }
    $govs[$GovId]=$GovName;
    $govse[$GovId]=$GovNameEng;
  }
}
//Read companies
$comps=array();
$compse=array();
$q="SELECT cmpId,cmpName,cmpNameEng from companies";
$r=mysqli_query($dbc,$q);
if($r){
  while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
    foreach($row as $key => $value){
      $$key=$value;
    }
    $comps[$cmpId]=$cmpName;
    $compse[$cmpId]=$cmpNameEng;
  }
}
//read course name
$q="select CrsName,CoursBulletin,CoursArea,CrsNameEng from CoursesGuide inner join Courses on CrsId=CoursCrsId where CoursId=$CoursId";
$r=mysqli_query($dbc,$q);
if($r){
    if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $currentCrsName=$row['CrsName'];
        $CoursBulletin=$row['CoursBulletin'];
        $CoursArea=$row['CoursArea'];
        $CrsNameEng=$row['CrsNameEng'];
    }
}
if(isset($_SESSION['TrnNo'])){
    $TrnNo=$_SESSION['TrnNo'];
    //read trainnee info
    $q="SELECT TrnNo,TrnName,trnEname,TrnAddress,TrnTels,TrnWhatsApp,TrnCo,TrnIdNo,trnBDate,trnBGovernrate,trnBState,trnNationality,trnPassportNo FROM Trainees WHERE TrnNo='$TrnNo'";
    $r=mysqli_query($dbc,$q);
    if($r){
      if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value){
          $$key=$value;
        }
      }
    }
    //read job
    $q="select trnjobName from TraineeJobs where trnjobTrainee=$TrnNo order by trnjobDate desc";
    $r=mysqli_query($dbc,$q);
    if($r){
    if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value){
        $$key=$value;
        }  
    }
    }

    //read certificates
    $certs=array();
    $i=0;
    $q="select trncrtName from TraineeCerts where trncrtTrn=$TrnNo order by trncrtDate desc";
    $r=mysqli_query($dbc,$q);
    if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
            $$key=$value;
            }
            $certs[$i++]=$trncrtName;
        }
    }

    //read external courses
    $crsList=array();
    $q="select trncrsName from TraineeCourses where trncrsTrn=$TrnNo order by trncrsDate desc";
    $r=mysqli_query($dbc,$q);
    if($r){
    while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value){
            $$key=$value;
        }
        $crsList[]=$trncrsName;
    }
    }

    //read internal courses
    $q="select CrsName from (CoursesGuide inner join Courses on CrsId=CoursCrsId) inner join coursetrainees on CoursId=crstrncourse where crstrntrainee=$TrnNo";
    $r=mysqli_query($dbc,$q);
    if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
                $$key=$value;
            }
            $crsList[]="[$CrsName]";
        }
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>إستمارة بيان مشارك</title>
<?php
include("trnInfocss.php");
?>
    </head>
    <body>
        <div class='buttons'>
            <button type='button' class='prtBtn' onclick='window.print();window.close();'>طباعة</button>&nbsp;
            <button type='button' class='cnlBtn' onclick='window.close();'>إغلاق</button>
            <hr><br>
        </div>
        <div class="maindoc">
            <div class="docheader">
                <div class="ptilogo">
                    <img src="img/pti.png">
                </div>
                <div class="aastmtlogo">
                    <img src="img/aastmt.png">
                </div>  
                <div class="maintitle">
                    اﻷكاديمية العربية للعلوم والتكنولوجيا والنقل البحري
                    <br>
                    معهد تدريب الموانئ
                </div>              
            </div>
            <div class="arbdata">
                <p class="arbtitle">استمارة بيان مشارك</p>
                <p class="arblines">
                    <table class="crsData">
                        <tr><td class='dataLable'>اســـــــــــــم الـــــــــــــدورة</td><td>:</td><td><?php echo "$currentCrsName ($CoursBulletin)";?></td></tr>
                        <tr><td>المنطقـــــــــــــــــــــــــــــــة</td><td>:</td><td><?php echo $dists[$CoursArea];?></td></tr>
                        <tr><td>اســــــــــم المشــــــــــــــارك</td><td>:</td><td><?php echo "$TrnName";?></td></tr>
                        <tr><td>الرقـــم القومي / رقــم الهوية</td><td>:</td><td><?php echo "$TrnIdNo";?></td></tr>
                        <tr><td>تـــــــــــــاريخ المـــــــــــيلاد</td><td>:</td><td><?php echo "$trnBDate - جهة الميلاد : $govs[$trnBGovernrate] - $cunts[$trnBState]";?></td></tr>
                        <tr><td>الجنسيــــــــــــــــــــــــــــــــة</td><td>:</td><td><?php echo $cunts[$trnNationality];?></td></tr>
                        <tr><td>الجهـــــة التـــــــابع لهـــــــا</td><td>:</td><td><?php echo "$comps[$TrnCo]";?></td></tr>
                        <tr><td>الوظيفـــــــــة الحاليـــــــــــة</td><td>:</td><td><?php echo "$trnjobName";?></td></tr>
                        <tr><td>المـــــــــؤهلات العلميـــــــــة</td><td>:</td><td><?php for($i=0;$i<3;$i++) echo $certs[$i]."<br>";?> </td></tr>
                        <tr><td>العنـــــــــــــــــــــــــــــــــوان</td><td>:</td><td><?php echo "$TrnAddress";?></td></tr>
                        <tr><td>رقــــــــــــم التليفــــــــــــون</td><td>:</td><td><?php echo "$TrnTels - واتساب : $TrnWhatsApp";?></td></tr>
                        <tr><td>بيان الدورات الحاصل عليها</td><td>:</td><td><?php foreach($crsList as $value) echo "$value - ";?></td></tr>
                        <tr><td>رقم جـواز الســفر البحــري</td><td>:</td><td><?php echo "$trnPassportNo";?></td></tr>
                    </table>
                </p>
            </div>
            <div class="engdata">
                <p class="engtitle">بيانات تكتب باللغة الانجليزية</p>
                <p class="englines">
                <table class="crsDataEng">
                  <tr><td class='dataLableEng'>Course Title</td><td>:</td><td><?php echo "$CrsNameEng ($CoursBulletin)";?></td></tr>
                  <tr><td>Name</td><td>:</td><td><?php echo "$trnEname";?></td></tr>
                  <tr><td>Date of Birth</td><td>:</td><td><?php echo "$trnBDate Place of birth: $govse[$trnBGovernrate] - $cuntse[$trnBState]";?></td></tr>
                  <tr><td>Organization</td><td>:</td><td><?php echo "$compse[$TrnCo]";?></td></tr>
                  </table>
                </p>
            </div>
            <div class="docfooter">
                <p class="footerlines">
                    إصدار   : (1) بتاريخ 1/5/2003<br>
                    <table width='100%'><tr><td class='footertd'>تعــديل   : (3) بتاريخ 17/12/2008</td><td class='footertd'> صفحة 1 من 1</td><td class='footertd' style="text-align: left;direction: ltr;"> F-IPM-(13)#4</td></tr><table>
                </p>
            </div>
        </div>
    </body>
</html>
