<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$__systemRoot="../";                        //path to system root
$__includeDir="../include";                //path to include directory
include($__systemRoot."functions.php");     //system Functions don't remove
include('../edu/functions.php');                   //uncomment this line if you have a local functions file
include("appdb.php");                       //application database credentials
foreach($_POST as $key => $value){
  $$key=$value;
}

if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
  die("Could not connect to database please contact system admin...");
}
$logDir=$__systemRoot."/logs";
$logFile="edu.log";
$__dir="rtl";
$showList=true;
$logged=false;
$labelWidth="200px";
$formWidth="1000px";
$formCourseStatusLevel=8;
if(!isset($mode)){
  $mode='';
}
if(!isset($errorMsg)){
  $errorMsg="";
}
if(isset($_SESSION['__uid'])){
  $__uid=$_SESSION['__uid'];
  $fileName = basename($_SERVER['PHP_SELF']);
  $mnuId=getCommandMenuId($fileName);
  if(checkUserMenuItem($__uid,$mnuId)){
    $logged=true;
  }
  if($logged){
    $perms=getUserPermissions($__uid);
    $user_year=getuseryear($__uid,$dbc);
    $pageTitle="الدورات المخططة لعام ".$user_year['Desc'];
    $pageSubTitle="المستحقات المالية للدورات";
    echo "<!DOCTYPE html><html><head>";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
    echo "<title>$pageTitle</title>";
    echo "<style>";
    echo ":root{direction: rtl;text-align: right;}";
    echo "* { box-sizing: border-box;}";
    echo "#filterBox {background-image: url('images/searchicon.png');background-position: 10px 10px;background-repeat: no-repeat;width: 100%;font-size: 16px;padding: 12px 20px 12px 40px;border: 1px solid #ddd;margin-bottom: 12px;}";
    echo "#mainTable {border-collapse: collapse;width: 100%;border: 1px solid #ddd;font-size: 18px;}";
    echo "#mainTable th, #mainTable td {text-align: right;padding: 12px;}";
    echo "#mainTable tr {border-bottom: 1px solid #ddd;}";
    echo "#mainTable tr.header, #mainTable tr:hover {background-color: #f1f1f1;}";
    echo ".navBtn {background-color: dodgerblue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 120px;  opacity: 0.9;}";
    echo ".navBtn:hover {opacity: 1;}";
    echo ".addBtn {background-color: MidnightBlue ;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;margin: auto;}";
    echo ".addBtn:hover {opacity: 1;}";
    echo ".edtBtn {background-color: teal;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".edtBtn:hover {opacity: 1;}";
    echo ".pwdBtn {background-color: Blue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".pwdBtn:hover {opacity: 1;}";
    echo ".delBtn {background-color: darkred;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".delBtn:hover {opacity: 1;}";
    echo ".savBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".savBtn:hover {opacity: 1;}";
    echo ".yesBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".yesBtn:hover {opacity: 1;}";
    echo ".noBtn {background-color: red;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".noBtn:hover {opacity: 1;}";
    echo ".genBtn {background-color: DarkMagenta ;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".genBtn:hover {opacity: 1;}";
    echo ".viewBtn {background-color: Chocolate;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".viewBtn:hover {opacity: 1;}";
    echo ".cnlBtn {background-color: red;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".cnlBtn:hover {opacity: 1;}";
    echo ".disabledBtn {background-color: grey;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".disabledBtn:hover {opacity: 1;}";
    echo ".okBtn {background-color: indigo;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".okBtn:hover {opacity: 1;}";
    echo ".grpBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".grpBtn:hover {opacity: 1;}";
    echo ".rptBtn {background-color: Maroon;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".rptBtn:hover {opacity: 1;}";
    echo ".arrowBtn {background-color: DarkOrchid;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 50px;  opacity: 0.9;}";
    echo ".arrowBtn:hover {opacity: 1;}";
    echo ".input-container {display: -ms-flexbox; /* IE10 */  display: flex;  width: 100%;  margin-bottom: 15px;}";
    echo ".input-field { width: 100%;  padding: 10px;  outline: none;}";
    echo ".input-field:focus {border: 2px solid dodgerblue;}";
    echo ".error{color:red; font-weight:bold;text-align:center;direction:rtl;}";
    echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";   
    echo ".lecTable{border-style:solid;border-color:black;border-width:1px;border-spacing:0px;}" ;
    echo ".lecTable td{border-style:solid;border-color:black;border-width:1px;}";
    echo ".lecTable th{border-style:solid;border-color:black;border-width:1px;text-align:center;}";
    echo ".mainDoc{text-align: center;width: 98%;margin: auto;background-color:#ddd; border-radius:5px;}";
    echo ".nameHeader{width:74%;float:right;background-color:#fff;text-align: center;border-radius:5px;margin:2px;padding:10px;font-size:large;font-weight:bold;}";
    echo ".amountHeader{width:10%;float:right;background-color:#fff;text-align: center;border-radius:5px;margin:2px;padding:10px;font-size:large;font-weight:bold;}";
    echo ".nameCell{width:74%;float:right;background-color:#fff;text-align: right;border-radius:5px;margin:2px;padding:19px;}";
    echo ".amountCell{width:10%;float:right;background-color:#fff;text-align: center;border-radius:5px;margin:2px;padding:19px;}";
    echo ".nameField{width:74%;float:right;background-color:#fff;text-align: right;border-radius:5px;margin:2px;padding:10px;}";
    echo ".amountField{width:10%;float:right;background-color:#fff;text-align: center;border-radius:5px;margin:2px;padding:10px;}";
    echo ".buttons{width:15%;float:right;background-color:#fff;text-align: center;border-radius:5px;margin:2px;padding:10px;font-size:large;font-weight:bold;}";
    echo ".totalLabel{width:74%;float:right;background-color:#fff;text-align: center;border-radius:5px;margin:2px;padding:10px;font-size:large;font-weight:bold;}";
    echo ".totalAmount{width:10%;float:right;background-color:#fff;text-align: center;border-radius:5px;margin:2px;padding:10px;font-size:large;font-weight:bold;}";
    echo ".bottomButtons{width:100%;text-align:center;padding-bottom:10px;}";
    echo ".infoMsg{text-align:center;color:green;font-weight:bold;}";
    echo ".errorMsg{text-align:center;color:red;font-weight:bold;}";
    echo ".badge {background-color: red;color: white;padding: 4px 8px;text-align: center;border-radius: 5px;font-size: x-small;position:relative;top:-5;}";
    echo "body{direction:rtl;}";
    echo "</style>";
    echo "</head>";
	  echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>$pageTitle</h2>";
    echo "<br><h3 style='text-align: center;color: RoyalBlue;'>$pageSubTitle</h3>";

//************************************************************************************ */
//View Course Data
//************************************************************************************ */    
    if($mode=='view' ){
      $showList=false;
      //load ref data
      
      include("$__includeDir/readDistricts.php");
      include("$__includeDir/readSections.php");
      include("$__includeDir/readPrograms.php");
      include("$__includeDir/readCompanies.php");
      include("$__includeDir/readCourseLecs.php");
      include("$__includeDir/readCourseData.php");
      include("$__includeDir/readStaff.php");
      
      
      echo "<div style='direction: rtl; text-align: right; width:1100px;margin: auto;'>";
      echo "<center>";
  
      //Course details
      include("$__includeDir/courseDetails.php");
      echo "<br><br>";
    
      //بيانات المحاضرين
      //include("$__includeDir/lecturersTable.php");
      drawCourseLecturerTable($dbc,$CoursId,true);
      echo "<br><br>";
  
      //بيانات بدل السفر والانتقال
      //include("$__includeDir/travelTransportationTable.php");
      drawTravelTable($dbc,$CoursId);
      echo "<br><br>";
  
      //بيانات المتدربين  
      include("$__includeDir/traineesTable.php");
      echo "<br><br>";
  
      //بيانات المرفقات
      include("$__includeDir/attachmentTable.php");
      echo "<br><br>";

      //بيانات الاشراف
      include("$__includeDir/supervisorsTable.php");

      $hide="";
      if($CoursStatus>$formCourseStatusLevel){
        $hide="display: none;";
      }
      echo "<br><br>";
      echo "<form method='post'>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<button type='submit' name='mode' value='agreeConfirm' class='yesBtn' style='width: 150px;$hide'>موافق</button> "; 
      echo "<button type='submit' name='mode' value='rejectConfirm' class='noBtn' style='width: 150px;$hide'>غير موافق</button> ";  
      echo "<button type='button' onclick='window.location=\"$fileName\";' class='okBtn'>إغلاق</button>";
      echo "</form>";
      echo "</center>";
      echo "</div>";
    }

//************************************************************************************************************* */
//View Attachment
//************************************************************************************************************* */
    if($mode=="viewAttachment"){
      $showList=false;
      include("$__includeDir/readPrograms.php"); 
      include("$__includeDir/readCourseData.php");
      include("$__includeDir/viewAttachment.php");
    }

/***********************************************************************************
Confirm new course status
************************************************************************************/
    if($mode=="agreeConfirm" or $mode=="rejectConfirm" or $mode=="sendFinanceConfirm"){
      $showList=false;
      echo "<div style='width:800px,margin: auto;text-align: center;'>";
      echo "هل أنت متأكد...";
      echo "<center>";
      echo "<br><br>";
      $newMode=str_replace("Confirm","Save",$mode);
      echo "<form method='post'>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<button type='submit' name='mode' value='$newMode' class='yesBtn'>نعم</button> "; 
      echo "<button type='submit' name='mode' value='view' class='noBtn'>لا</button> ";
      echo "</form>";
      echo "</center>";
      echo "</div>";
    }


/***********************************************************************************
save and set new course status
************************************************************************************/
    if($mode=="agreeSave"){
      $newFlag=$formCourseStatusLevel+1;
      $mode="Save";
    }

    if($mode=="rejectSave"){
      $newFlag=$formCourseStatusLevel-1;
      $mode="Save";
    }

    if($mode=="Save"){
      $q="update Courses set CoursStatus=? where CoursId=?";
      if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "ii", $newFlag,$CoursId)){
          if(!mysqli_stmt_execute($stmt)){
            $errorMsg.= "Error saving reading [030103".$__uid.date("YmdHis")."]!...<br>";
            appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030103".$__uid.date("YmdHis"));    
          }
        }else{
          $errorMsg.= "Error saving reading [030102".$__uid.date("YmdHis")."]!...<br>";
          appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030102".$__uid.date("YmdHis"));
        }
        mysqli_stmt_close($stmt);
      }else{
        $errorMsg.= "Error saving reading [030101".$__uid.date("YmdHis")."]!...<br>";
        appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030101".$__uid.date("YmdHis"));
      }      
    }


/*********************************************************************************************************** */
//print Benefits
/*********************************************************************************************************** */
if($mode=="printBenefits"){
  $_SESSION['CoursId']=$CoursId;
  $CoursPrtBenifits++;
  $q="update Courses set CoursPrtBenifits=? where CoursId=?";
  if ($stmt = mysqli_prepare($dbc, $q)){
    if(mysqli_stmt_bind_param($stmt, "ii", $CoursPrtBenifits,$CoursId)){
      if(!mysqli_stmt_execute($stmt)){
        $errorMsg.= "Error saving reading [030103".$__uid.date("YmdHis")."]!...<br>";
        appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030103".$__uid.date("YmdHis"));    
      }
    }else{
      $errorMsg.= "Error saving reading [030102".$__uid.date("YmdHis")."]!...<br>";
      appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030102".$__uid.date("YmdHis"));
    }
    mysqli_stmt_close($stmt);
  }else{
    $errorMsg.= "Error saving reading [030101".$__uid.date("YmdHis")."]!...<br>";
    appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030101".$__uid.date("YmdHis"));
  }      
  echo "
  <script>
  window.open(\"finCourseBenefits.php\");
  </script>
  ";
}

/*********************************************************************************************************** */
//print Course Data
/*********************************************************************************************************** */
if($mode=="printCourseData"){
  $_SESSION['CoursId']=$CoursId;
  $CoursPrtCourseData++;
  $q="update Courses set CoursPrtCourseData=? where CoursId=?";
  if ($stmt = mysqli_prepare($dbc, $q)){
    if(mysqli_stmt_bind_param($stmt, "ii", $CoursPrtCourseData,$CoursId)){
      if(!mysqli_stmt_execute($stmt)){
        $errorMsg.= "Error saving reading [030103".$__uid.date("YmdHis")."]!...<br>";
        appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030103".$__uid.date("YmdHis"));    
      }
    }else{
      $errorMsg.= "Error saving reading [030102".$__uid.date("YmdHis")."]!...<br>";
      appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030102".$__uid.date("YmdHis"));
    }
    mysqli_stmt_close($stmt);
  }else{
    $errorMsg.= "Error saving reading [030101".$__uid.date("YmdHis")."]!...<br>";
    appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030101".$__uid.date("YmdHis"));
  }      
  echo "
  <script>
  window.open(\"finCourseLecturerData.php\");
  </script>
  ";
}

/*********************************************************************************************************** */
//print Supervision
/*********************************************************************************************************** */
if($mode=="printSupervision"){
  $_SESSION['CoursId']=$CoursId;
  $CoursPrtSupervision++;
  $q="update Courses set CoursPrtSupervision=? where CoursId=?";
  if ($stmt = mysqli_prepare($dbc, $q)){
    if(mysqli_stmt_bind_param($stmt, "ii", $CoursPrtSupervision,$CoursId)){
      if(!mysqli_stmt_execute($stmt)){
        $errorMsg.= "Error saving reading [030103".$__uid.date("YmdHis")."]!...<br>";
        appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030103".$__uid.date("YmdHis"));    
      }
    }else{
      $errorMsg.= "Error saving reading [030102".$__uid.date("YmdHis")."]!...<br>";
      appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030102".$__uid.date("YmdHis"));
    }
    mysqli_stmt_close($stmt);
  }else{
    $errorMsg.= "Error saving reading [030101".$__uid.date("YmdHis")."]!...<br>";
    appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030101".$__uid.date("YmdHis"));
  }      
  echo "
  <script>
  window.open(\"finSupervision.php\");
  </script>
  ";
}

/*********************************************************************************************************** */
//Request reprint
/*********************************************************************************************************** */
if($mode=="reprintRequest"){
  include("$__includeDir/readCourseData.php");
  $requestDate=date("Y-m-d");
  $requestTime=date("H:i:s");
  $requestUser=getUserDetails($__uid);
  $q="INSERT INTO courseReprintLog(crlUserName, crlDate, crlTime, crlFlag, crlCourseId, crlCourseType, crlBulleten) VALUES (?,?,?,'CoursPrtBenifits',?,1,?)";
  if ($stmt = mysqli_prepare($dbc, $q)){
    if(mysqli_stmt_bind_param($stmt, "sssii", $requestUser['usrFullName'], $requestDate,$requestTime,$CoursId,$CoursBulletin)){
      if(!mysqli_stmt_execute($stmt)){
        $errorMsg.= "Error saving reading [030103".$__uid.date("YmdHis")."]!...<br>";
        appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030103".$__uid.date("YmdHis"));    
      }else{
        $infoMsg="تم تسجيل طلب إعادة طباعة مستحقات الدورة $CoursBulletin<br>";
      }
    }else{
      $errorMsg.= "Error saving reading [030102".$__uid.date("YmdHis")."]!...<br>";
      appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030102".$__uid.date("YmdHis"));
    }
    mysqli_stmt_close($stmt);
  }else{
    $errorMsg.= "Error saving reading [030101".$__uid.date("YmdHis")."]!...<br>";
    appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030101".$__uid.date("YmdHis"));
  }      
  $q="INSERT INTO courseReprintLog(crlUserName, crlDate, crlTime, crlFlag, crlCourseId, crlCourseType, crlBulleten) VALUES (?,?,?,'CoursPrtSupervision',?,1,?)";
  if ($stmt = mysqli_prepare($dbc, $q)){
    if(mysqli_stmt_bind_param($stmt, "sssii", $requestUser['usrFullName'], $requestDate,$requestTime,$CoursId,$CoursBulletin)){
      if(!mysqli_stmt_execute($stmt)){
        $errorMsg.= "Error saving reading [030103".$__uid.date("YmdHis")."]!...<br>";
        appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030103".$__uid.date("YmdHis"));    
      }else{
        $infoMsg.="تم تسجيل طلب إعادة طباعة اﻹشراف للدورة $CoursBulletin<br>";
      }
    }else{
      $errorMsg.= "Error saving reading [030102".$__uid.date("YmdHis")."]!...<br>";
      appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030102".$__uid.date("YmdHis"));
    }
    mysqli_stmt_close($stmt);
  }else{
    $errorMsg.= "Error saving reading [030101".$__uid.date("YmdHis")."]!...<br>";
    appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030101".$__uid.date("YmdHis"));
  }      
  $q="INSERT INTO courseReprintLog(crlUserName, crlDate, crlTime, crlFlag, crlCourseId, crlCourseType, crlBulleten) VALUES (?,?,?,'CoursPrtCourseData',?,1,?)";
  if ($stmt = mysqli_prepare($dbc, $q)){
    if(mysqli_stmt_bind_param($stmt, "sssii", $requestUser['usrFullName'], $requestDate,$requestTime,$CoursId,$CoursBulletin)){
      if(!mysqli_stmt_execute($stmt)){
        $errorMsg.= "Error saving reading [030103".$__uid.date("YmdHis")."]!...<br>";
        appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030103".$__uid.date("YmdHis"));    
      }else{
        $infoMsg.="تم تسجيل طلب إعادة طباعة بيانات الدورة $CoursBulletin<br>";
      }
    }else{
      $errorMsg.= "Error saving reading [030102".$__uid.date("YmdHis")."]!...<br>";
      appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030102".$__uid.date("YmdHis"));
    }
    mysqli_stmt_close($stmt);
  }else{
    $errorMsg.= "Error saving reading [030101".$__uid.date("YmdHis")."]!...<br>";
    appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030101".$__uid.date("YmdHis"));
  }      

}

/*********************************************************************************************************** */
//courses list
/*********************************************************************************************************** */
    if($showList){
      if(isset($infoMsg)){
        echo "<div class='infoMsg'>$infoMsg</div>";
        $infoMsg="";
      }
      if(isset($errorMsg)){
        echo "<div class='errorMsg'>$errorMsg</div>";
        $errorMsg="";
      }
      $q="SELECT CoursId,CoursBulletin,CrsName,CoursStatus,CoursPrtBenifits,CoursPrtSupervision,CoursPrtCourseData FROM Courses inner join CoursesGuide on CoursCrsId=CrsId WHERE CoursType=1 and CoursYear=".$user_year['Id']." and CoursStatus >=$formCourseStatusLevel order by CoursStatus,CoursBulletin";
      $r=mysqli_query($dbc,$q);
      if($r){
        //filter list form 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث..' title='Type in a name'>";
        echo "<table id='mainTable'>";
        echo "<tr class='header'>";
        echo "<th>المنشور - اسم الدورة</th><th style='width:550px;text-align: center;'></th></tr>";
        $rowNo=0;
        $buttonColors=array("gray","DarkGreen","OrangeRed","DarkRed");
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value){
            $$key=$value;
          }
          $printedMessage="";
          $BenefitsDisabled=" disabled style='background-color:gray;'";
          $supervisionDisabled=" disabled style='background-color:gray;'";
          $courseDataDisabled=" disabled style='background-color:gray;'";
          $reprintRequestDisabled=" disabled style='background-color:gray;'";
          $benefitsBadge="";
          $supervisionBadge="";
          $courseDataBadge="";
          if($CoursStatus > $formCourseStatusLevel){       //When course is abroved by the dean
            $BenefitsDisabled="";
            $supervisionDisabled="";
            $courseDataDisabled="";
          }
          if($CoursPrtBenifits>0){
            if($CoursPrtBenifits>3){
              $colorIndex=3;
            }else{
              $colorIndex=$CoursPrtBenifits;
            }
            $BenefitsDisabled=" style='background-color:".$buttonColors[$colorIndex].";'";
            $printedMessage="[تمت الطباعة]";
            $benefitsBadge="<sup class='badge'>$CoursPrtBenifits</sup>";
            $reprintRequestDisabled="";
          }
          if($CoursPrtSupervision>0){
            if($CoursPrtSupervision>3){
              $colorIndex=3;
            }else{
              $colorIndex=$CoursPrtSupervision;
            }
            $supervisionDisabled=" style='background-color:".$buttonColors[$colorIndex].";'";
            $printedMessage="[تمت الطباعة]";
            $supervisionBadge="<sup class='badge'>$CoursPrtSupervision</sup>";
            $reprintRequestDisabled="";
          }
          if($CoursPrtCourseData>0){
            if($CoursPrtCourseData>3){
              $colorIndex=3;
            }else{
              $colorIndex=$CoursPrtCourseData;
            }
            $courseDataDisabled=" style='background-color:".$buttonColors[$colorIndex].";'";
            $printedMessage="[تمت الطباعة]";
            $courseDataBadge="<sup class='badge'>$CoursPrtCourseData</sup>";
            $reprintRequestDisabled="";
          }
          $rowNo++;
          $viewButttonStyle="";
          if(!isset($perms['FinanceAuditCourse'])){
            $viewButttonStyle=" style='display:none;'";
          }
          echo "<tr><td>$CoursBulletin - $CrsName $printedMessage</td>";
          echo "<td style='text-align: right;'><form method='post'>";
          echo "<input type='hidden' name='CoursPrtBenifits' value='$CoursPrtBenifits'>";
          echo "<input type='hidden' name='CoursPrtSupervision' value='$CoursPrtSupervision'>";
          echo "<input type='hidden' name='CoursPrtCourseData' value='$CoursPrtCourseData'>";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          echo "<button type='submit' class='viewBtn' name='mode' value='view'$viewButttonStyle>عرض</button> ";
          echo "<button type='submit' class='navBtn' name='mode' value='printBenefits'$BenefitsDisabled>المستحقات$benefitsBadge</button> ";
          echo "<button type='submit' class='navBtn' name='mode' value='printSupervision'$supervisionDisabled>الاشراف$supervisionBadge</button> ";
          echo "<button type='submit' class='navBtn' name='mode' value='printCourseData'$courseDataDisabled>بيانات الدورة$courseDataBadge</button> ";
          //echo "<button type='submit' class='rptBtn' name='mode' value='reprintRequest'$reprintRequestDisabled>إعادة الطباعة</button> ";
          echo "</form></td></tr>";
        }
        echo "</table>";
        mysqli_free_result($r);
      }
    }
    mysqli_close($dbc);
    echo "<script>
    var rows=0;
    function filterList() {
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById(\"filterBox\");
      filter = input.value.toUpperCase();
      table = document.getElementById(\"mainTable\");
      tr = table.getElementsByTagName(\"tr\");
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName(\"td\")[0];
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = \"\";
          } else {
            tr[i].style.display = \"none\";
          }
        }   
      }
    }
  </script>";
    echo "</body></html>";
  }
}
if(!$logged){
  include($__systemRoot."expired.php");
}
?>