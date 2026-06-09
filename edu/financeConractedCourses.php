<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
$__systemRoot = "../";                        //path to system root
$__includeDir = "../include";                //path to include directory
include($__systemRoot . "functions.php");     //system Functions don't remove
include('functions.php');                   //uncomment this line if you have a local functions file
include("appdb.php");                       //application database credentials
foreach ($_POST as $key => $value) {
  $$key = $value;
}

if (!$dbc = dbConnect($db_host, $db_schema, $db_user, $db_password)) {
  die("Could not connect to database please contact system admin...");
}
$boolarray = array(false => 'false', true => 'true');
$logDir = $__systemRoot . "/logs";
$logFile = "edu.log";
$__dir = "rtl";
$showList = true;
$logged = false;
$labelWidth = "200px";
$formWidth = "1000px";
$formCourseStatusLevel = 8;
if (!isset($mode)) {
  $mode = '';
}
if (!isset($errorMsg)) {
  $errorMsg = "";
}
if (isset($_SESSION['__uid'])) {
  $__uid = $_SESSION['__uid'];
  $fileName = basename($_SERVER['PHP_SELF']);
  $mnuId = getCommandMenuId($fileName);
  if (checkUserMenuItem($__uid, $mnuId)) {
    $logged = true;
  }
  $perms = getUserPermissions($__uid);
  if ($logged) {
    $user_year = getuseryear($__uid, $dbc);
    $pageTitle = "الدورات التعاقدية لعام " . $user_year['Desc'];
    $pageSubTitle = "المستحقات المالية للدورات";
    echo "<!DOCTYPE html><html><head>";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
    echo "<title>$pageTitle</title>";
    echo "<style>";
    echo "* { box-sizing: border-box;}";
    echo "#filterBox {background-image: url('images/searchicon.png');background-position: 10px 10px;background-repeat: no-repeat;width: 100%;font-size: 16px;padding: 12px 20px 12px 40px;border: 1px solid #ddd;margin-bottom: 12px;}";
    echo "#mainTable {border-collapse: collapse;width: 100%;border: 1px solid #ddd;font-size: 18px;direction:rtl;}";
    echo "#mainTable th, #mainTable td {text-align: left;padding: 12px;}";
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
    echo ".okBtn {background-color: indigo;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".okBtn:hover {opacity: 1;}";
    echo ".grpBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".grpBtn:hover {opacity: 1;}";
    echo ".arrowBtn {background-color: DarkOrchid;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 50px;  opacity: 0.9;}";
    echo ".arrowBtn:hover {opacity: 1;}";
    echo ".input-container {display: -ms-flexbox; /* IE10 */  display: flex;  width: 100%;  margin-bottom: 15px;}";
    echo ".input-field { width: 100%;  padding: 10px;  outline: none;}";
    echo ".input-field:focus {border: 2px solid dodgerblue;}";
    echo ".error{color:red; font-weight:bold;text-align:center;direction:rtl;}";
    echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";
    echo ".lecTable{border-style:solid;border-color:black;border-width:1px;border-spacing:0px;}";
    echo ".lecTable td{border-style:solid;border-color:black;border-width:1px;}";
    echo ".lecTable th{border-style:solid;border-color:black;border-width:1px;text-align:center;}";
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
    //sec:view Course Data
    //************************************************************************************ */
    if (!isset($clhPeriod)) {
      $clhPeriod = 0;
    }
    if ($mode == 'view') {
      if ($clhPeriod == 0 and courseIsDevidable($dbc, $CoursId)) {
        $errorMsg = "لابد من اختيار فترة";
      } else {
        $showList = false;
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
        //include("$__includeDir/courseDetails.php");
        displayCourseDetails($dbc, $CoursId, $CoursYear, $clhPeriod);
        echo "<br><br>";

        //بيانات المحاضرين
        //include("$__includeDir/lecturersTable.php");
        drawCourseLecturerTable($dbc, $CoursId, true, $clhPeriod);
        echo "<br><br>";

        //بيانات بدل السفر والانتقال
        //include("$__includeDir/travelTransportationTable.php");
        drawTravelTable($dbc, $CoursId, $clhPeriod);
        echo "<br><br>";

        // بيانات المتدربين
        //if (!courseIsDevidable($dbc, $CoursId)) {
          drawCourseTraineesConracted($dbc, $CoursId);
        //}
        echo "<br><br>";

        //بيانات المرفقات
        if (!courseIsDevidable($dbc, $CoursId)) {
          include("$__includeDir/attachmentTable.php");
          echo "<br><br>";
        }

        if (courseIsDevidable($dbc, $CoursId)) {
          $periodStatus = readPeriod($dbc, $clhPeriod)['Status'];
        } else {
          $periodStatus = -1;
        }
        $divisiable = courseIsDevidable($dbc, $CoursId);
        echo "<form method='post'>";
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<input type='hidden' name='fmode' value='$fmode'>";
        echo "<input type='hidden' name='clhPeriod' value='$clhPeriod'>";
        $toBeRev = false;
        if ($divisiable) {
          if ($periodStatus == $formCourseStatusLevel) {
            $toBeRev = true;
          }
        } else {
          if ($CoursStatus == $formCourseStatusLevel) {
            $toBeRev = true;
          }
        }
        if ($toBeRev) {
          echo "<button type='submit' name='mode' value='agreeConfirm' class='yesBtn'>موافق</button> ";
          echo "<button type='submit' name='mode' value='rejectConfirm' class='noBtn'>غير موافق</button> ";
        }
        echo "<button type='submit' class='okBtn'>إغلاق</button>";
        echo "</form>";
        echo "</center>";
        echo "</div>";
      }
    }

    //************************************************************************************************************* */
    //sec:View Attachment
    //************************************************************************************************************* */
    if ($mode == "viewAttachment") {
      $showList = false;
      include("$__includeDir/readPrograms.php");
      include("$__includeDir/readCourseData.php");
      include("$__includeDir/viewAttachment.php");
    }

    /***********************************************************************************
//sec:Confirm new course status
     ************************************************************************************/
    if ($mode == "agreeConfirm" or $mode == "rejectConfirm" or $mode == "editConfirm") {
      $showList = false;
      echo "<div style='width:800px,margin: auto;text-align: center;'>";
      echo "هل أنت متأكد...";
      echo "<center>";
      echo "<br><br>";
      $newMode = str_replace("Confirm", "Save", $mode);
      echo "<form method='post'>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<input type='hidden' name='fmode' value='$fmode'>";
      echo "<input type='hidden' name='clhPeriod' value='$clhPeriod'>";
      echo "<button type='submit' name='mode' value='$newMode' class='yesBtn'>نعم</button> ";
      echo "<button type='submit' name='mode' value='view' class='noBtn'>لا</button> ";
      echo "</form>";
      echo "</center>";
      echo "</div>";
    }

    /***********************************************************************************
    //sec:save and set new course status
    / ************************************************************************************/
    $rejectAmount=3;
    include('conractedAgreeReject.php');

    //**************************************************************************************************************
    // sec:conReport main
    //**************************************************************************************************************
    if ($mode == 'conReport') {
      echo "<script> window.open('conReport.php?CoursId=$CoursId');</script>";
    }

    /*********************************************************************************************************** */
    //sec:print Benefits
    /*********************************************************************************************************** */
    if ($mode == "printBenefits") {
      $_SESSION['CoursId'] = $CoursId;
      $_SESSION['clhPeriod'] = $clhPeriod;
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
      $dividable = courseIsDevidable($dbc, $CoursId);
      if ($dividable == true) {
        if ($clhPeriod != 0) {
          $prdStat = getPrdStat($dbc, $clhPeriod);
          if ($prdStat == $formCourseStatusLevel + 1)
            echo " <script> window.open(\"finCourseBenefits.php\"); </script> ";
          else
            $errorMsg = "لم يتم الاعتماد لهذة الفترة";
        } else {
          $errorMsg = "لابد من اختيار فترة";
        }
      } else {

        echo " <script> window.open(\"finCourseBenefits.php\"); </script> ";
      }
    }


    /*********************************************************************************************************** */
    //sec:print Course Data
    /*********************************************************************************************************** */
    if ($mode == "printCourseData") {
      $_SESSION['CoursId'] = $CoursId;
      $_SESSION['clhPeriod'] = $clhPeriod;
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
      $dividable = courseIsDevidable($dbc, $CoursId);
      if ($dividable == true) {
        if ($clhPeriod != 0) {
          $prdStat = getPrdStat($dbc, $clhPeriod);
          if ($prdStat == $formCourseStatusLevel + 1)
            echo " <script> window.open(\"finCourseLecturerData.php\"); </script> ";
          else
            $errorMsg = "لم يتم الاعتماد لهذة الفترة";
        } else {
          $errorMsg = "لابد من اختيار فترة";
        }
      } else {
        echo " <script> window.open(\"finCourseLecturerData.php\"); </script> ";
      }
    }



    /*********************************************************************************************************** */
    //sec:courses list
    /*********************************************************************************************************** */
    if ($showList) {
      if(isset($infoMsg)){
        echo "<div class='infoMsg'>$infoMsg</div>";
        $infoMsg="";
      }
      if(isset($errorMsg)){
        echo "<div class='errorMsg'>$errorMsg</div>";
        $errorMsg="";
      }
      $printedMessage="";
      $benefitsBadge="";
      $courseDataBadge="";
      $currentEduYear=getSystemValue('curEduYear');
      $previousEduYear=getSystemValue('PreEduYear');
      $q = "SELECT distinct CoursId,CoursBulletin,CrsName,CoursStatus,CoursYear,CoursPrtCourseData,CoursPrtBenifits,CoursPrtSupervision FROM Courses inner join CoursesGuide on CoursCrsId =CrsId left JOIN  coursPeriods on CoursId=crprCourseId WHERE CoursType=2 AND (CoursYear=" . $user_year['Id'] . " OR CoursYear=$previousEduYear) AND (CoursStatus >=$formCourseStatusLevel OR crprStatus >=$formCourseStatusLevel) order by CoursId";
      $r = mysqli_query($dbc, $q);
      if ($r) {
        //filter list form 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table style='width:100%;' id='preFilter'>";
        echo "<tr><td style='text-align: center;'><form method='post'>";
        echo "<button type='submit' class='pwdBtn' name='fmode' value='filterReview' >مراجعة</button> ";
        echo "<button type='submit' class='pwdBtn' name='fmode' value='filterView' >عرض</button> ";
        echo "<button type='submit' class='pwdBtn' name='fmode' value='filterAll' >الكل</button> ";
        echo "</form></td></tr></table>";

        echo "<table id='mainTable'>";
        echo "<tr class='header'>";
        echo "<th>المنشور - اسم الدورة</th><th style='width:900px;text-align: center;'></th></tr>";
        if (!isset($fmode)) {
          $fmode = 'filterAll';
        }
        $buttonColors=array("gray","DarkGreen","OrangeRed","DarkRed");
        $rowNo = 0;
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
          foreach ($row as $key => $value) {
            $$key = $value;
          }
          $periods = comparePeriodStatus($dbc, $CoursId, ">=", $formCourseStatusLevel);
          $approvedPrds = comparePeriodStatus($dbc, $CoursId, ">", $formCourseStatusLevel);
          $allPeriodsApproved = false;
          $dividable = courseIsDevidable($dbc, $CoursId);
          // record status
          $buttonCaption = "عرض";
          $recordStatus = "view";
          $buttonClass = "edtBtn";

          if (count($periods) == count($approvedPrds) and $dividable) {
            $allPeriodsApproved = true;
          }
          if (!$dividable) {
            $allPeriodsApproved = true;
          }


          if ($CoursStatus > $formCourseStatusLevel and $allPeriodsApproved) {
            $buttonCaption = "عرض";
            $recordStatus = "view";
            $buttonClass = "edtBtn";
          } elseif (!$allPeriodsApproved or (!$dividable and $CoursStatus == $formCourseStatusLevel)) {
            $buttonCaption = "مراجعة";
            $recordStatus = "review";
            $buttonClass = "viewBtn";
          }

          $dividable = courseIsDevidable($dbc, $CoursId);
          $devInfo = "";
          $devMode=0;
          if (isset($perms['Developer'])) {
            $devMode = 1;
            include('conractedDevInfo.php');
            //$devInfo = "[Id = $CoursId - Status = $CoursStatus]- isdvd=$boolarray[$dividable]";
          }
          $rowNo++;


          $listDisabled = "";
          if (count($periods) == 0) {
            $listDisabled = " disabled ";
          }
          $taggedPrd = tagPrdBenefits($periods, $formCourseStatusLevel);
          $allCrsPrds = readPeriods($dbc, $CoursId);
          $sortedPrd = srtPrd($taggedPrd, "Status");
          $benefitsDisabled = " disabled style='background-color:gray;'";
          $courseDataDisabled  = " disabled style='background-color:gray;'";

          if ($CoursStatus >= $formCourseStatusLevel + 1 and !$dividable) {       //When course is aproved and not divid course
            $benefitsDisabled = "";
            $courseDataDisabled = "";
          }


          if ($recordStatus == "review" and $fmode == "filterView") {
            continue;
          }
          if ($recordStatus == "view" and $fmode == "filterReview") {
            continue;
          }
          if($CoursPrtCourseData>0 or $CoursPrtBenifits>0){
            $printedMessage="[تمت الطباعة]";
          }

          $message="";
          if($CoursYear == $previousEduYear){
            $message="<span style='color:red;'>[العام السابق]</span>";
          }

          echo "<tr><td>$CoursBulletin - $CrsName  $printedMessage $message<br> $devInfo</td>";
          echo "<td style='text-align: left;'><form method='post'>";
          echo "<select name='clhPeriod' style='width: 260px; height: 37px;'$listDisabled><option value='0'>حدد الفترة</option>";

          foreach ($sortedPrd as $key => $value) {
            echo "<option value='$key'>" . $value['Description'] . " - " . $value['tag'] . " (" . $value['From'] . " - " . $value['To'] . ")-";
            echo ($devMode == 1) ? $value['Status'] : ' ';
            echo "</option>";
            if ($value['Status'] == $formCourseStatusLevel + 1) {
              $benefitsDisabled = "";
              $courseDataDisabled = "";
            }
          }
          echo "</select> ";
          if($CoursPrtCourseData>0){
            if($CoursPrtCourseData>3){
              $colorIndex=3;
            }else{
              $colorIndex=$CoursPrtCourseData;
            }
            $courseDataDisabled=" style='background-color:".$buttonColors[$colorIndex].";'";
            $courseDataBadge="<sup class='badge'>$CoursPrtCourseData</sup>";
            $reprintRequestDisabled="";
          }
          if($CoursPrtBenifits>0){
            if($CoursPrtBenifits>3){
              $colorIndex=3;
            }else{
              $colorIndex=$CoursPrtBenifits;
            }
            $benefitsDisabled=" style='background-color:".$buttonColors[$colorIndex].";'";
            $benefitsBadge="<sup class='badge'>$CoursPrtBenifits</sup>";
            $reprintRequestDisabled="";
          }
          $viewButttonStyle="";
          if(!isset($perms['FinanceAuditCourse'])){
            $viewButttonStyle=" style='display:none;'";
          }
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          echo "<input type='hidden' name='fmode' value='$fmode'>";
          echo "<input type='hidden' name='CoursPrtBenifits' value='$CoursPrtBenifits'>";
          echo "<input type='hidden' name='CoursPrtSupervision' value='$CoursPrtSupervision'>";
          echo "<input type='hidden' name='CoursPrtCourseData' value='$CoursPrtCourseData'>";
          echo "<button type='submit' class='$buttonClass' name='mode' value='view'$viewButttonStyle>$buttonCaption</button> ";
          echo "<button type='submit' class='navBtn' name='mode' value='printBenefits'$benefitsDisabled>المستحقات$benefitsBadge</button> ";
          echo "<button type='submit' class='navBtn' name='mode' value='printCourseData'$courseDataDisabled>بيانات الدورة$courseDataBadge</button> ";
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
if (!$logged) {
  include($__systemRoot . "expired.php");
}
