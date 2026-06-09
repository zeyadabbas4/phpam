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
$formCourseStatusLevel = 3;
if (!isset($mode)) {
  $mode = '';
}
if (!isset($errorMessage)) {
  $errorMessage = "";
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
    $pageSubTitle = "مراجعة مدير ادارة التعليم";
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
    echo ".navBtn {background-color: dodgerblue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
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
    echo "</style>";
    echo "</head>";
    echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>$pageTitle</h2>";
    echo "<br><h3 style='text-align: center;color: RoyalBlue;'>$pageSubTitle</h3>";

    //************************************************************************************ */
    //sec:View Course Data
    //************************************************************************************ */
    if (!isset($clhPeriod)) {
      $clhPeriod = 0;
    }
    if ($mode == 'view') {
      if ($clhPeriod == 0 and courseIsDevidable($dbc, $CoursId)) {
        $errorMessage = "لابد من اختيار فترة";
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
        displayCourseDetails($dbc, $CoursId, $user_year['Id'], $clhPeriod);
        echo "<br><br>";

        //بيانات المحاضرين
        //include("$__includeDir/lecturersTable.php");
        drawCourseLecturerTable($dbc, $CoursId, false, $clhPeriod);
        echo "<br><br>";

        //بيانات بدل السفر والانتقال
        //include("$__includeDir/travelTransportationTable.php");
        drawTravelTable($dbc, $CoursId, $clhPeriod);
        echo "<br><br>";

        // بيانات المتدربين
        if (!courseIsDevidable($dbc, $CoursId)) {
          drawCourseTraineesConracted($dbc, $CoursId);
        }
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
        echo "<input type='hidden' name='clhPeriod' value='$clhPeriod'>";
        $toBeRev = false;
        if ($divisiable) {
          if ($periodStatus == $formCourseStatusLevel) {
            $toBeRev = true;
          } else {
            $toBeRev = false;
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
        echo "<button type='button' onclick='window.location=\"$fileName\";' class='okBtn'>إغلاق</button>";
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
    include('conractedAgreeReject.php');

    //**************************************************************************************************************
    // sec:conReport main
    //**************************************************************************************************************
    if ($mode == 'conReport') {
      echo "<script> window.open('conReport.php?CoursId=$CoursId');</script>";
    }



    /*********************************************************************************************************** */
    //sec:courses list
    /*********************************************************************************************************** */
    if ($showList) {
      if ($errorMessage != "") {
        echo "<div class='error'>$errorMessage<br><br></div>";
      }
      $q = "SELECT  CoursId,CoursBulletin,CrsName,CoursStatus FROM Courses inner join CoursesGuide on CoursCrsId =CrsId left JOIN  coursPeriods on CoursId=crprCourseId WHERE CoursType=2 AND   CoursYear=". $user_year['Id']. "order by CoursId";
      $r = mysqli_query($dbc, $q);
      if ($r) {
        //filter list form 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table id='preFilter'>";
        echo "<tr><td style='text-aligh: left;'><form method='post'>";
        echo "<button type='submit' class='pwdBtn' name='mode' value='filterReview' >مراجعة</button> ";
        echo "<button type='submit' class='pwdBtn' name='mode' value='filterView' >عرض</button> ";
        echo "<button type='submit' class='pwdBtn' name='mode' value='filterAll' >الكل</button> ";
        echo "<form></td></td><table>";
        echo "<table id='mainTable'>";
        echo "<tr class='header'>";
        echo "<th>المنشور - اسم الدورة</th><th style='width:900px;text-align: center;'></th></tr>";
        $rowNo = 0;
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
          foreach ($row as $key => $value) {
            $$key = $value;
          }
          $periods = comparePeriodStatus($dbc, $CoursId, ">=", $formCourseStatusLevel);
          $approvedPrds = comparePeriodStatus($dbc, $CoursId, ">", $formCourseStatusLevel);
          $allPeriodsApproved = false;
          $dividable = courseIsDevidable($dbc, $CoursId);
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
          } else {
            $buttonCaption = "مراجعة";
            $recordStatus = "review";
            $buttonClass = "viewBtn";
          }
          $dividable = courseIsDevidable($dbc, $CoursId);
          $devInfo = "";
          if (isset($perms['Developer'])) {
            include('conractedDevInfo.php');
            //$devInfo = "<br>[Id = $CoursId - crsSts = $CoursStatus/formId=$formCourseStatusLevel - allPrdAprv=$boolarray[$allPeriodsApproved]]";
          }
          $rowNo++;

          $buttonStatus = "";
          $listDisabled = "";
          if (count($periods) == 0) {
            $listDisabled = " disabled ";
          }
          $taggedPrd = tagPrd($periods, $formCourseStatusLevel);
          $allCrsPrds = readPeriods($dbc, $CoursId);
          // echo "<br>count of all crs prds is ".count($allCrsPrds)." and all tagged prds is ".count($taggedPrd)." for crs $CoursId";
          if (count($taggedPrd) != count($allCrsPrds)) {
            $buttonStatus = " disabled style='background-color: lightgrey;'";
          }
          $sortedPrd = srtPrd($taggedPrd, "Status");

          if ($recordStatus == "review" and $mode == "filterView") {
            continue;
          }
          if ($recordStatus == "view" and $mode == "filterReview") {
            continue;
          }


          echo "<tr><td>$CoursBulletin - $CrsName $devInfo</td>";
          echo "<td style='text-align: left;'><form method='post'>";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          echo "<button type='submit' class='pwdBtn' name='mode' value='conReport' $buttonStatus >بيانات الدورة</button> ";
          echo "<button type='submit' class='$buttonClass' name='mode' value='view'>$buttonCaption</button> ";
          echo "<select name='clhPeriod' style='width: 260px; height: 37px;'$listDisabled><option value='0'>حدد الفترة</option>";

          foreach ($sortedPrd as $key => $value) {
            echo "<option value='$key'>" . $value['Description'] . " - " . $value['tag'] . " (" . $value['From'] . " - " . $value['To'] . ")</option>";
          }
          echo "</select>";
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
