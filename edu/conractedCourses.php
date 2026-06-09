<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
$__systemRoot = "../";                        //path to system root
include($__systemRoot . "functions.php");     //system Functions don't remove
include('functions.php');                   //uncomment this line if you have a local functions file
include("appdb.php");                       //application database credentials "for unplanned it will be found in edu"
// Global Variables
//
$tableBorderSize = 0;

foreach ($_POST as $key => $value) {
  $$key = $value;
}

if (!$dbc = dbConnect($db_host, $db_schema, $db_user, $db_password)) {
  die("Could not connect to database please contact system admin...");
}
$__includeDir = "../include"; //path to include directory
include("$__includeDir/errorPrint.php");
$logDir = $__systemRoot . "/logs";
$logFile = "edu.log";
$__dir = "rtl";
$showList = true;
$logged = false;
$labelWidth = "200px";
$formWidth = "1000px";
$formCourseStatusLevel = 0;
$showMode = false;
$status = array("", " disabled style='background-color: lightgrey;'");
if (!isset($newCourse)) {
  $newCourse = false;
}
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
  if ($logged) {
    $perms = getUserPermissions($__uid);
    $user_year = getuseryear($__uid, $dbc);
    $yearStart=$user_year['Start'];
    $yearEnd=$user_year['End'];
    $pageTitle = "الدورات التعاقدية لعام " . $user_year['Desc'];
    echo "<!DOCTYPE html><html><head>";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
    echo "<title>$pageTitle</title>";
    echo "<style>";
    echo "* { box-sizing: border-box;}";
    echo "h2{text-align:center;color:royalblue;}";
    echo "h3{text-align:center;color:royalblue;}";
    echo "#filterBox {background-image: url('images/searchicon.png');background-position: 10px 10px;background-repeat: no-repeat;width: 100%;font-size: 16px;padding: 12px 20px 12px 40px;border: 1px solid #ddd;margin-bottom: 12px;}";
    echo "#mainTable {border-collapse: collapse;width: 100%;border: 1px solid #ddd;font-size: 18px;}";
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
    echo ".check-box   {height: 20px; width: 20px;}";
    echo ".input-field:focus {border: 2px solid dodgerblue;}";
    echo ".error{color:red; font-weight:bold;text-align:center;direction:rtl;}";
    echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";
    echo ".disabled {opacity: 0.3; cursor:not-allowed;}";
    echo ".noHover {pointer-events: none;}";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>$pageTitle</h2>";

    //**************************************************************************************************************
    //sec:load-ref-data
    //**************************************************************************************************************
    $dists = array();
    $q = "select dist_id,dist_name from districts";
    $r = mysqli_query($dbc, $q);
    if ($r) {
      while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
        $dists[$row['dist_id']] = $row['dist_name'];
      }
    }
    $secs = array();
    $q = "select PrgId,PrgName from Programs";
    $r = mysqli_query($dbc, $q);
    if ($r) {
      while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
        $secs[$row['PrgId']] = $row['PrgName'];
      }
    }

    $prgs = array();
    $q = "select CrsId,CrsProgram,CrsCode,CrsName from CoursesGuide";
    $r = mysqli_query($dbc, $q);
    if ($r) {
      while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
        $prgs[$row['CrsId']]['Name'] = $row['CrsName'];
        $prgs[$row['CrsId']]['Code'] = $row['CrsCode'];
        $prgs[$row['CrsId']]['Program'] = $row['CrsProgram'];
      }
    }
    // read courses ID and their total hours from CouresGuide
    $prgsHours = array();
    $q = "select CrsId,(CrsTHours+CrsPHours) as tHours from CoursesGuide";
    $r = mysqli_query($dbc, $q);
    if ($r) {
      while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
        $prgsHours[$row['CrsId']] = $row['tHours'];
      }
    }

    $comps = array();
    $q = "select cmpId,cmpName from companies order by cmpName";
    $r = mysqli_query($dbc, $q);
    if ($r) {
      while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
        $comps[$row['cmpId']] = $row['cmpName'];
      }
    }

    $currency = array();
    $q = "select CurId,CurName from Currencies";
    $r = mysqli_query($dbc, $q);
    if ($r) {
      while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
        $currency[$row['CurId']] = $row['CurName'];
      }
    }


    $intSup = array();
    $q = "select staffid,staffname from staff where (staffcontract=1 or staffcontract=4 or staffcontract=2) and staffislec=1 and staffdeleted=0 order by staffname";
    $r = mysqli_query($dbc, $q);
    if ($r) {
      while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
        $intSup[$row['staffid']] = $row['staffname'];
      }
    }

    //**************************************************************************************************************
    // sec:data-validation
    //**************************************************************************************************************
    if ($mode == 'saveadd' or $mode == 'saveedit' or $mode == "addNominee") {
      //validation for new or old records
      if ($mode == "addNominee") {
        if ($concrsCmp == "0") {
          $errorMessage .= "لا بد من اختيار الشركة<br>";
        }
        if ($concrsTrnCount == "") {
          $errorMessage .= "لا بد من ادخال عدد المتدربين<br>";
        }
        if ($concrsCost == "") {
          $errorMessage .= "لا بد من ادخال تكلفة المتدرب<br>";
        }
        if ($concrsCurrency == "-1") {
          $errorMessage .= "لا بد من ادخال العملة<br>";
        }

        $q = "select concrsCmp,concrsTrnCount from conCourses where concrsId =$CoursId";
        $r = mysqli_query($dbc, $q);
        if ($r) {
          while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            if ($row['concrsCmp'] == $concrsCmp) {
              $errorMessage = "لقد تمت إضافة " . $comps[$row['concrsCmp']] . " مسبقا بواقع " . $row['concrsTrnCount'] . " مرشحين";
            }
          }
        }
      } else {
        
        if ($CoursSupervisorExt == "-1") {
          $errorMessage .= "لا بد من ادخال جهة التعاقد<br>";
        }
        if ($CoursArea == "0") {
          $errorMessage .= "لا بد من ادخال المنطقة<br>";
        }
        if ($CoursSection == "0") {
          $errorMessage .= "لا بد من ادخال القسم<br>";
        }
        if ($CoursCrsId == "0") {
          $errorMessage .= "لا بد من ادخال الدورة<br>";
        }
        if ($CoursFromPln == "") {
          $errorMessage .= "لا بد من ادخال تاريخ البداية<br>";
        }
        if($CoursFromPln < $yearStart){
          $errorMessage.="تاريخ البداية قبل بداية العام التدريبي<br>";
        }
        if($CoursFromPln > $yearEnd){
          $errorMessage.="تاريخ البداية بعد نهاية العام التدريبي<br>";
        }
        if ($CouursToPln == "") {
          $errorMessage .= "لا بد من ادخال تاريخ النهاية<br>";
        }
        /*
        if($CouursToPln < $yearStart){
          $errorMessage.="تاريخ النهاية قبل بداية العام التدريبي<br>";
        }
        if($CouursToPln > $yearEnd){
          $errorMessage.="تاريخ النهاية بعد نهاية العام التدريبي<br>";
        }
        */
        if ($CoursLocation == "0") {
          $errorMessage .= "لا بد من ادخال مكان الانعقاد<br>";
        }
        if ($CoursBulletin == "") {
          $errorMessage .= "لا بد من ادخال رقم المنشور<br>";
        } else {
          $manshourIsFound = manshourIsFound($dbc, $CoursBulletin, $user_year['Id']);
        }
        if($manshourIsFound == true and $mode == 'saveadd')
          {
          $errorMessage .= "لا بد من ادخال رقم المنشور جديد<br>";
          }
      }
    }


    //**************************************************************************************************************
    // sec:add-nominee
    //**************************************************************************************************************
    if ($mode == "addNominee") {
      if ($errorMessage == "") {
        $concrsId = $CoursId;
        $q = "INSERT INTO conCourses(concrsId,concrsCmp,concrsCost,concrsCurrency,concrsTrnCount,concrsTrnCerts) VALUES (?,?,?,?,?,?)";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if (mysqli_stmt_bind_param($stmt, "iiiiii", $concrsId, $concrsCmp, $concrsCost, $concrsCurrency, $concrsTrnCount, $concrsTrnCerts)) {
            if (!mysqli_stmt_execute($stmt)) {
              $errorMessage .= "Error saving data [050103" . $__uid . date("YmdHis") . "]!...<br>";
              appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050103" . $__uid . date("YmdHis"));
            }
          } else {
            $errorMessage .= "Error saving data [050102]" . $__uid . date("YmdHis") . "]!...<br>";
            appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050102" . $__uid . date("YmdHis"));
          }
          mysqli_stmt_close($stmt);
        } else {
          $errorMessage .= "Error saving data [050101]" . $__uid . date("YmdHis") . "]!...<br>";
          appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050101" . $__uid . date("YmdHis"));
        }
      }
      $mode = "nominees";
    }

    //**************************************************************************************************************
    // sec:delete-Period
    //**************************************************************************************************************
    if ($mode == "deletePeriods") {
      if ($errorMessage == "") {
        $concrsId = $CoursId;
        $q = "delete from coursPeriods where crprId=? and crprCourseId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if (mysqli_stmt_bind_param($stmt, "ii", $crprId, $crprCourseId)) {
            if (!mysqli_stmt_execute($stmt)) {
              $errorMessage .= "Error saving data [060103" . $__uid . date("YmdHis") . "]!...<br>";
              appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "060103" . $__uid . date("YmdHis"));
            }
          } else {
            $errorMessage .= "Error saving data [060102]" . $__uid . date("YmdHis") . "]!...<br>";
            appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "060102" . $__uid . date("YmdHis"));
          }
          mysqli_stmt_close($stmt);
        } else {
          $errorMessage .= "Error saving data [060101]" . $__uid . date("YmdHis") . "]!...<br>";
          appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "060101" . $__uid . date("YmdHis"));
        }
      }
      $mode = "periods";
    }





    //**************************************************************************************************************
    // sec:delete-nominee
    //**************************************************************************************************************
    if ($mode == "delNominee") {
      if ($errorMessage == "") {
        $concrsId = $CoursId;
        $q = "delete from conCourses where concrsCmp=? and concrsId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if (mysqli_stmt_bind_param($stmt, "ii", $concrsCmp, $concrsId)) {
            if (!mysqli_stmt_execute($stmt)) {
              $errorMessage .= "Error saving data [060103" . $__uid . date("YmdHis") . "]!...<br>";
              appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "060103" . $__uid . date("YmdHis"));
            }
          } else {
            $errorMessage .= "Error saving data [060102]" . $__uid . date("YmdHis") . "]!...<br>";
            appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "060102" . $__uid . date("YmdHis"));
          }
          mysqli_stmt_close($stmt);
        } else {
          $errorMessage .= "Error saving data [060101]" . $__uid . date("YmdHis") . "]!...<br>";
          appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "060101" . $__uid . date("YmdHis"));
        }
      }
      $mode = "nominees";
    }


    //**************************************************************************************************************
    // sec:read-course-data
    //**************************************************************************************************************
    if ($mode == 'view' or $mode == 'edit' or $mode == 'deleteconfirm' or $mode == 'nominees' or $mode == 'lecs' or $mode == 'periods') {
      $q = "select CoursCrsId,CoursType,CoursBulletin,CoursFromPln,CouursToPln,CoursSupervisorInt,CoursSupervisorExt,CoursYear,CoursArea,CoursSection,CoursGenRept,CoursAreaRept,CoursLocation,CoursStatus,CoursDevidable from Courses where CoursId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $CoursId)) {
          if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_bind_result($stmt, $CoursCrsId, $CoursType, $CoursBulletin, $CoursFromPln, $CouursToPln, $CoursSupervisorInt, $CoursSupervisorExt, $CoursYear, $CoursArea, $CoursSection, $CoursGenRept, $CoursAreaRept, $CoursLocation, $CoursStatus, $CoursDevidable)) {
              if (!mysqli_stmt_fetch($stmt)) {
                $errorMessage .= "<span dir='ltr'>Error reading data [030105" . $__uid . date("YmdHis") . "]!...</span><br>";
                appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "030105" . $__uid . date("YmdHis"));
              }
            } else {
              $errorMessage .= "Error reading data [030104" . $__uid . date("YmdHis") . "]!...<br>";
              appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "030104" . $__uid . date("YmdHis"));
            }
          } else {
            $errorMessage .= "Error saving reading [030103" . $__uid . date("YmdHis") . "]!...<br>";
            appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "030103" . $__uid . date("YmdHis"));
          }
        } else {
          $errorMessage .= "Error saving reading [030102" . $__uid . date("YmdHis") . "]!...<br>";
          appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "030102" . $__uid . date("YmdHis"));
        }
        mysqli_stmt_close($stmt);
      } else {
        $errorMessage .= "Error saving reading [030101" . $__uid . date("YmdHis") . "]!...<br>";
        appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "030101" . $__uid . date("YmdHis"));
      }
    }

    //**************************************************************************************************************
    // sec:save-new-course-data
    //**************************************************************************************************************
    if ($mode == 'saveadd') {
      if ($errorMessage == "") {
        $CoursType = 2;
        $CoursYear = $user_year['Id'];
        $CoursStatus = $formCourseStatusLevel;
        echo "Deviaadable Data <br>";
        echo $CoursDevidable;
        $q = "INSERT INTO Courses(CoursCrsId,CoursType,CoursBulletin,CoursFromPln,CouursToPln,CoursYear,CoursArea,CoursSection,CoursLocation,CoursStatus,CoursDevidable,CoursSupervisorExt) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if (mysqli_stmt_bind_param($stmt, "iiissiiiiiii", $CoursCrsId, $CoursType, $CoursBulletin, $CoursFromPln, $CouursToPln, $CoursYear, $CoursArea, $CoursSection, $CoursLocation, $CoursStatus, $CoursDevidable,$CoursSupervisorExt)) {
            if (mysqli_stmt_execute($stmt)) {
              $CoursId = mysqli_insert_id($dbc);
              /*
              unset($CoursCrsId);
              unset($CoursFromPln);
              unset($CouursToPln);
              unset($CoursGenRept);
              unset($CoursAreaRept);
              unset($CoursLocation);
              unset($CoursSupervisorExt);
              */
            } else {
              $errorMessage .= "Error saving data [010103" . $__uid . date("YmdHis") . "]!...<br>";
              appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "010103" . $__uid . date("YmdHis"));
            }
          } else {
            $errorMessage .= "Error saving data [010102]" . $__uid . date("YmdHis") . "]!...<br>";
            appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "010102" . $__uid . date("YmdHis"));
          }
          mysqli_stmt_close($stmt);
        } else {
          $errorMessage .= "Error saving data [010101]" . $__uid . date("YmdHis") . "]!...<br>";
          appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "010101" . $__uid . date("YmdHis"));
        }
        //$mode = "nominees";
        $mode = "nominees";
        //echo $mode;
      } else {
        $mode = "add";
      }
      $errorMessage .= mysqli_error($dbc);
      //echo "$CoursCrsId<br>$CoursType<br>$CoursBulletin<br>$CoursFromPln<br>$CouursToPln<br>$CoursSupervisorInt<br>$CoursSupervisorExt<br>$CoursYear<br>$CoursArea<br>$CoursSection<br>$CoursGenRept<br>$CoursAreaRept<br>$CoursLocation<br>$CoursStatus<br>";
    }

    //**************************************************************************************************************
    // sec:save-edited-course-data
    //**************************************************************************************************************
    if ($mode == 'saveedit') {
      //save new
      if ($errorMessage == "") {
        //save master
        $q = "update Courses set CoursCrsId=?,CoursBulletin=?,CoursFromPln=?,CouursToPln=?,CoursSupervisorInt=?,CoursSupervisorExt=?,CoursArea=?,CoursSection=?,CoursGenRept=?,CoursAreaRept=?,CoursLocation=?,CoursDevidable=? where CoursId=?;";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if (mysqli_stmt_bind_param($stmt, "iissiiiiiiiii", $CoursCrsId, $CoursBulletin, $CoursFromPln, $CouursToPln, $CoursSupervisorInt, $CoursSupervisorExt, $CoursArea, $CoursSection, $CoursGenRept, $CoursAreaRept, $CoursLocation, $CoursDevidable, $CoursId)) {
            if (!mysqli_stmt_execute($stmt)) {
              $errorMessage .= "Error saving data [020103" . $__uid . date("YmdHis") . "]!...<br>";
              appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "020103" . $__uid . date("YmdHis"));
            }
          } else {
            $errorMessage .= "Error saving data [020102]" . $__uid . date("YmdHis") . "]!...<br>";
            appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "020102" . $__uid . date("YmdHis"));
          }
          mysqli_stmt_close($stmt);
        } else {
          $errorMessage .= "Error saving data [020101]" . $__uid . date("YmdHis") . "]!...<br>";
          appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "020101" . $__uid . date("YmdHis"));
        }
      } else {
        $mode = 'edit';
      }
    }

    //**************************************************************************************************************
    // sec:delete-course
    //**************************************************************************************************************
    if ($mode == "delete") {
      $q = "delete from Courses where CoursId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $CoursId)) {
          if (!mysqli_stmt_execute($stmt)) {
            $errorMessage .= "Error saving data [040103" . $__uid . date("YmdHis") . "]!...<br>";
            appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "040103" . $__uid . date("YmdHis"));
          }
          mysqli_stmt_close($stmt);
        } else {
          $errorMessage .= "Error saving data [040102]" . $__uid . date("YmdHis") . "]!...<br>";
          appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "040102" . $__uid . date("YmdHis"));
        }
      } else {
        $errorMessage .= "Error saving data [040101]" . $__uid . date("YmdHis") . "]!...<br>";
        appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "040101" . $__uid . date("YmdHis"));
      }
    }

    //**************************************************************************************************************
    // sec:new-edit-or-view-form
    //**************************************************************************************************************
    if ($mode == 'add' or $mode == 'edit' or $mode == 'view') {

      printMode($mode, $showMode);
      $showList = false;
      if ($mode == 'edit' or $mode == 'view')
        $_SESSION['CourseId'] = array($CoursId, $mnuId);
      if (!isset($CoursBulletin)) {
        // get new manshour no
        $q = "select ifnull(max(CoursBulletin),0)+1 as newManshour from Courses where CoursYear=" . $user_year['Id'] . " and CoursType=2";
        $r = mysqli_query($dbc, $q);
        if ($r) {
          if ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            $CoursBulletin = $row['newManshour'];
          }
        }
      }
      if ($mode == 'view') {
        $disabled = " disabled";
      } else {
        $disabled = "";
      }
      echo "<form method='post' style='max-width:$formWidth;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>إضافة دورة  " . $user_year['Desc'] . "</h2>";
      if ($errorMessage != "") {
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<table width='100%'>";
      echo "<tr><td style='width: $labelWidth;'>المنطقة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='CoursArea'$disabled><option value='0'>منطقة الانعقاد</option>";
      foreach ($dists as $key => $value) {
        echo "<option value='$key'";
        if (isset($CoursArea)) {
          if ($CoursArea == $key) {
            echo " selected";
          }
        }
        echo ">$value</option>";
      }
      echo "</select>";
      echo "</div></td></tr>";

      echo "<tr><td style='width: $labelWidth;'>الجهة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='CoursSupervisorExt'$disabled><option value='-1'>جهة التعاقد</option>";
      foreach ($comps as $key => $value) {
        echo "<option value='$key'";
        if (isset($CoursSupervisorExt)) {
          if ($CoursSupervisorExt == $key) {
            echo " selected";
          }
        }
        echo ">$value</option>";
      }
      echo "</select>";
      echo "</div></td></tr>";


      echo "<tr><td style='width: $labelWidth;'>القسم:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='CoursSection' onchange='filterPrg(this.value)'$disabled>\r\n<option value='0'>القسم المختص</option>\r\n";
      foreach ($secs as $key => $value) {
        echo "<option value='$key'";
        if (isset($CoursSection)) {
          if ($CoursSection == $key) {
            echo " selected";
          }
        }
        echo ">$value</option>\r\n";
      }
      echo "</select>\r\n";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>البرنامج:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='CoursCrsId' id='CoursCrsId' onchange='getTHours(this.value)'$disabled >\r\n<option value='0'>البرنامج</option>\r\n";
      foreach ($prgs as $key => $value) {
        if ($value['Program'] == $CoursSection) {
          echo "<option value='$key'";
          if (isset($CoursCrsId)) {
            if ($CoursCrsId == $key) {
              echo " selected";
            }
          }
          echo ">" . $value['Code'] . " - " . $value['Name'] . "</option>\r\n";
        }
      }
      echo "</select>\r\n";
      echo "</div></td></tr>";

      //total hours start

      echo "<tr><td style='width: $labelWidth;'>  عدد الساعات:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='عدد الساعات' id='CourstHours' name='CourstHours' readonly>";
      echo "</div></td></tr>";
      //total hours end

      if (isset($CoursDevidable)) {
        if ($CoursDevidable == 1)
          $checked = 'checked';
        else
          $checked = '';
      }
      if ($mode == 'view')
        $disabled = 'disabled';
      else
        $disabled = '';
      if ($mode == 'add')
        $checked = '';
      $hasLecs = -1;
      if (isset($CoursId)) {
        $hasLecs = courseHasLecs($dbc, $CoursId);
      }
      /**
       *   This checker is to prevent changing course dividabelity option after
       *   lecturers have been assigned to the course
       *   to prevent assiging lectuerer to period Zero if done otherwise.
       */
      if(isset($CoursDevidable)){
        echo "<input type='hidden' name='CoursDevidable' value='$CoursDevidable'>";
      }
      if (($mode == 'edit' and $hasLecs == 0) or $mode == 'add' or $mode=='view') {
        echo "<tr><td style='width: $labelWidth;'>مقسمة:</td><td colspan='2'>";
        echo "<input type='hidden' name='CoursDevidable' value=0>";
        echo "<input class='check-box' type='checkbox' value=1  id='CoursDevidable' name='CoursDevidable' $checked $disabled>";
        echo "</div></td></tr>";
      }
      echo "<tr><td style='width: $labelWidth;'>تاريخ بداية الدورة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='date' name='CoursFromPln'$disabled";
      if (isset($CoursFromPln))
        echo " value='$CoursFromPln'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>تاريخ نهاية الدورة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='Date' name='CouursToPln'$disabled";
      if (isset($CouursToPln))
        echo " value='$CouursToPln'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>رقم الاخطار:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='رقم الاخطار' name='CoursBulletin'$disabled";
      if (isset($CoursBulletin))
        echo " value='$CoursBulletin'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>مكان الانعقاد:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='CoursLocation'$disabled><option value='0'>مكان الانعقاد</option>\r\n";
      foreach ($comps as $key => $value) {
        echo "<option value='$key'";
        if (isset($CoursLocation)) {
          if ($CoursLocation == $key) {
            echo " selected";
          }
        }
        echo ">$value</option>";
      }
      echo "</select>";
      echo "</div></td></tr>";
      echo "</table>";
      echo "<br>";
      if ($mode == 'add') {
        $newCourse = true;
        echo "<input type='hidden' name='newCourse' value='$newCourse'>";
      }
      if ($mode == 'edit') {
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      }
      if ($mode == 'view') {
        echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode' value=''>موافق</button></div>";
      } else {
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='save$mode'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value=''>تراجع</button></div>";
      }
      echo "</form>";
      echo "<script>
      var prgsId = [];\r\n
      var prgsName = [];\r\n
      var prgsPrg = [];\r\n
      ";
      foreach ($prgs as $key => $value) {
        echo "prgsId.push('$key');\r\n";
        echo "prgsName.push('" . $value['Code'] . " - " . $value['Name'] . "');\r\n";
        echo "prgsPrg.push('" . $value['Program'] . "');\r\n";
      }
      echo "
      function filterPrg(pId){
        var prgSelect=document.getElementById('CoursCrsId');
        var len=prgSelect.options.length;
        for(var i=0;i<len;i++){
          prgSelect.remove(0);
        }
        len=prgsId.length;
        for(i=0;i<len;i++){
          if(prgsPrg[i] == pId){
            var opt=document.createElement(\"option\");
            opt.text=prgsName[i];
            opt.value=prgsId[i];
            prgSelect.add(opt);  
          }
        }
      }
";
      echo "
      var crsId = [];\r\n
      var crsHours = [];\r\n
";
      // fill array from php to java
      foreach ($prgsHours as $key => $value) {
        echo "crsId.push('$key');\r\n";
        echo "crsHours.push('$value');\r\n";
      }
      echo "
     function getTHours(cid){
        document.getElementById('CourstHours').value=crsHours[crsId.indexOf(cid)];
    }
      </script>";
    }
    if ($mode == 'view' or $mode == 'edit') {
      echo "<script>
        var cid = document.getElementById('CoursCrsId').value;
        getTHours(cid);
        </script>";
    }

    //**************************************************************************************************************
    // sec:delete-confirmation
    //**************************************************************************************************************
    if ($mode == 'deleteconfirm') {
      $showList = false;
      echo "<form method='post' style='max-width:500px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>الغاء</h2>";
      echo "<div style='text-align: center;direction:$__dir;'>سيتم الغاء دورة " . $prgs[$CoursCrsId]["Name"] . " منشور رقم  $CoursBulletin هل انت متأكد?...</div><br>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='delete'>نعم</button> <button type='submit' class='cnlBtn' name='mode' value=''>لا</button></div>";
      echo "</form>";
    }

    //**************************************************************************************************************
    // sec:nominees
    //**************************************************************************************************************
    // if ($mode == "nominees") {
    //   $showList = false;
    //   $nominees = array();
    //   $_SESSION['CourseId'] = array($CoursId, $mnuId);
    //   //$q = "select crscmpCompany,crscmpPlanned from courseCompanies where crscmpCourse=$CoursId";
    //   $q = "select concrsId,concrsCmp,concrsTrnCount,concrsCost,concrsCurrency,concrsTrnCerts from conCourses where concrsId = $CoursId";
    //   $r = mysqli_query($dbc, $q);
    //   if ($r) {
    //     while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
    //       $nominees[$row['concrsCmp']] = $row;
    //     }
    //   }
    //   echo "<br>";
    //   if ($errorMessage != "") {
    //     echo "<div class='error'><p>$errorMessage</p></div>";
    //   }
    //   echo "<div style='max-width:800px;margin:auto;direction:$__dir;'>";
    //   echo "<h2 style='text-align: center;'>اﻷعداد المرشحة لدورة " . $prgs[$CoursCrsId]["Name"] . "<br>إخطار رقم : $CoursBulletin</h2><hr>\r\n";
    //   echo "<table  border ='$tableBorderSize'  width='100%'>";
    //   echo "<tr>";
    //   echo    "<td style='text-align: center;width: 600px;'> الشركة </td>";
    //   echo    "<td style='text-align: center;width: 100px;'> تكلفة المتدرب </td>";
    //   echo    "<td style='text-align: center;width: 100px;'> العملة </td>";
    //   echo    "<td style='text-align: center;width: 100px;'> اﻷعداد المرشحة </td>";
    //   echo    "<td style='text-align: center;width: 100px;'> إجمالي الشهادات </td>";
    //   echo    "<td style='text-align: center;width: 150px;'> &nbsp;</td>";
    //   echo "</tr>\r\n";
    //   $tot = 0;
    //   foreach ($nominees as $key => $value) {
    //     echo "<tr><td colspan='6'><form method='post'><table border = '$tableBorderSize' width='100%'>";
    //     echo "<tr><td width='600px' style='text-align:right;'>" . $comps[$key] . "</td>";
    //     echo "<td width='100px'>" . $value['concrsCost'] . "</td>";
    //     echo "<td width='100px'>" . $currency[$value['concrsCurrency']] . "</td>";
    //     echo "<td width='100px'>" . $value['concrsTrnCount'] . "</td>";
    //     echo "<td width='100px'>" . $value['concrsTrnCerts'] * $value['concrsTrnCount'] . "</td>";
    //     echo "<td>";
    //     if ($CoursStatus > $formCourseStatusLevel) {
    //       echo "&nbsp;";
    //     } else {
    //       echo "<button type='submit' class='delBtn' name='mode' value='delNominee'>الغاء</button>";
    //     }
    //     echo "</td>";
    //     echo "<input type='hidden' name='CoursId' value='$CoursId'>";
    //     echo "<input type='hidden' name='concrsCmp' value='$key'>";
    //     echo "<input type='hidden' name='newCourse' value='$newCourse'>";
    //     echo "</table></form></td></tr>";
    //     $tot += $value['concrsTrnCount'];
    //   }
    //   echo "<tr><td colspan='4'><table border='0' width='100%'>";
    //   echo "<tr><td width='600px' style='text-align:right;'>اﻹجمـــــــالي</td>";
    //   echo "<td width='100px' style='text-align:center';>$tot</td>";
    //   echo "<td>&nbsp;</td>";
    //   echo "</table></td></tr>";

    //   echo "<tr><td colspan='6'>";
    //   if ($CoursStatus > $formCourseStatusLevel) {
    //     echo "&nbsp;";
    //   } else {
    //     // adding
    //     echo "<form method='post'><table width='100%'>";
    //     echo "<tr>";

    //     echo "<td width='800px'><select class='input-field' name='concrsCmp'><option value='0'>حدد الشركة</option>\r\n";
    //     foreach ($comps as $key => $value) {
    //       echo "<option value='$key'";
    //       if (isset($concrsCmp)) {
    //         if ($concrsCmp == $key) {
    //           echo " selected";
    //         }
    //       }
    //       echo ">$value</option>\r\n";
    //     }

    //     echo "</select></td>\r\n";
    //     if (!isset($concrsCost)) {
    //       $concrsCost = '';
    //     }
    //     echo "<td style='vertical-align: middle;' width='300px'><input class='input-field' type='text' placeholder='تكلفة المتدرب' name='concrsCost' value='$concrsCost' ></td>";
    //     // start curr
    //     if (!isset($concrsCurrency)) {
    //       $concrsCurrency = '';
    //     }
    //     echo "<td width='300px'><select class='input-field' name='concrsCurrency'><option value='-1'>حدد العملة</option>\r\n";
    //     foreach ($currency as $key => $value) {
    //       echo "<option value='$key'";
    //       if ($concrsCurrency == $key)
    //         echo "selected";
    //       echo ">$value</option>\r\n";
    //     }
    //     echo "</select></td>\r\n";
    //     if (!isset($concrsTrnCount)) {
    //       $concrsTrnCount = '';
    //     }
    //     if (!isset($concrsTrnCerts)) {
    //       $concrsTrnCerts = '';
    //     }

    //     echo "<td style='vertical-align: middle;' width='250px'><input class='input-field' type='text' placeholder='العدد الفعلي' name='concrsTrnCount' value='$concrsTrnCount'></td>";
    //     echo "<td style='vertical-align: middle;' width='270px'><input class='input-field' type='text' placeholder='عدد الشهادات' name='concrsTrnCerts' value='$concrsTrnCerts'></td>";


    //     echo "<td style='text-align:center;vertical-align: middle;'>";
    //     echo "<button type='submit' class='savBtn' name='mode' value='addNominee'> إضافة </button>\r\n";
    //     echo "<input type='hidden' name='CoursId' value='$CoursId'>";
    //     echo "<input type='hidden' name='newCourse' value='$newCourse'>";
    //     echo "</td></tr></table></form>";
    //   }
    //   echo "</td></tr></table><br>\r\n";

    //   echo "<div class='frmButtons'><form method='post'>";
    //   if ($newCourse) {
    //     echo "<input type='hidden' name='CoursId' value='$CoursId'>";
    //     echo "<input type='hidden' name='newCourse' value='$newCourse'>";
    //     echo "<button type='submit' class='addBtn' name='mode' value='lecs'> المحاضرين </button> ";
    //   }
    //   echo "<button type='button' class='addBtn' name='mode' onclick='window.location=\"conractedCourses.php\"'> إغلاق </button> ";
    //   echo "</form></div>\r\n";
    //   echo "</div>";
    // }

    // //************************************************************************************************************* */
    // sec:save-lecs
    //************************************************************************************************************* */
    if ($mode == "savelecs") {
      printMode($mode, $showMode);
      if ($errorMessage == "") {
        $q = "INSERT INTO CourseLecHours(clhLecId,clhCrsId,clhHoursP,clhHoursT,clhNights,clhratio,clhDistrictFrom,clhDistrictTo,clhDays,clhReturn) VALUES (?,?,?,?,?,?,?,?,?,?)";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if (mysqli_stmt_bind_param($stmt, "iiddiiiiii", $fclhLecId, $CoursId, $fclhHoursP, $fclhHoursT, $fclhNights, $fclhratio, $fclhDistrictFrom, $fclhDistrictTo, $fclhDays, $fclhReturn)) {
            if (!mysqli_stmt_execute($stmt)) {
              $errorMessage .= "Error saving data [050103" . $__uid . date("YmdHis") . "]!...<br>";
              appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050103" . $__uid . date("YmdHis"));
            }
          } else {
            $errorMessage .= "Error saving data [050102]" . $__uid . date("YmdHis") . "]!...<br>";
            appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050102" . $__uid . date("YmdHis"));
          }
          mysqli_stmt_close($stmt);
        } else {
          $errorMessage .= "Error saving data [050101]" . $__uid . date("YmdHis") . "]!...<br>";
          appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050101" . $__uid . date("YmdHis"));
        }
      }

      $mode = "lecs";
    }

    //************************************************************************************************************* */
    // sec:delLecs delete lecture
    //************************************************************************************************************* */
    if ($mode == "delLecs") {
      printMode($mode, $showMode);
      if ($errorMessage == "") {
        $clhCrsId = $CoursId;
        $clhLecId = $LecId;

        $q = "delete from CourseLecHours where clhLecId=? and clhCrsId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if (mysqli_stmt_bind_param($stmt, "ii", $LecId, $CoursId)) {
            if (!mysqli_stmt_execute($stmt)) {
              $errorMessage .= "Error saving data [060103" . $__uid . date("YmdHis") . "]!...<br>";
              appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "060103" . $__uid . date("YmdHis"));
            }
          } else {
            $errorMessage .= "Error saving data [060102]" . $__uid . date("YmdHis") . "]!...<br>";
            appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "060102" . $__uid . date("YmdHis"));
          }
          mysqli_stmt_close($stmt);
        } else {
          $errorMessage .= "Error saving data [060101]" . $__uid . date("YmdHis") . "]!...<br>";
          appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "060101" . $__uid . date("YmdHis"));
        }
      }
      $mode = $fromCaller;
    }


    //**************************************************************************************************************
    // sec:lecturers -- to be overridden from kimo's work
    //**************************************************************************************************************
    if ($mode == 'lecs') {
      printMode($mode, $showMode);

      $_SESSION['CourseId'] = array($CoursId, $mnuId);
      $showList = false;
      include("$__includeDir/readPrograms.php");
      include("$__includeDir/readCourseData.php");
      include("$__includeDir/readCourseLecs.php");
      include("$__includeDir/readLecNames.php");
      if ($errorMessage != "") {
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<div style='max-width:1000px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>محاضري دورة " . $prgs[$CoursCrsId]["Name"] . "<br>منشور رقم : $CoursBulletin</h2><hr>\r\n";
      echo "<table  width='100%'>";
      echo "<tr><td style='text-align: center;width: 250px;'> المحاضر </td>";
      echo "<td style='text-align: center;width: 75px;'>نظري</td>";
      echo "<td style='text-align: center;width: 75px;'>عملي</td>";
      echo "<td style='text-align: center;width: 75px;'>الليالي</td>";
      echo "<td style='text-align: center;width: 75px;'>النسبة</td>";
      echo "<td style='text-align: center;width: 75px;'>من</td>";
      echo "<td style='text-align: center;width: 75px;'>الى</td>";
      echo "<td style='text-align: center;width: 75px;'>عدد ايام الانتقال</td>";
      echo "<td style='text-align: center;width: 75px;'>العودة</td>";
      echo "<td>&nbsp;</td></tr>\r\n";
      $totT = 0;
      $totP = 0;
      foreach ($lecs as $key => $value) {
        echo "<tr><td width='250px' style='text-align:right;'>" . $lecNames[$value['lecId']] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $value['thhrs'] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $value['prhrs'] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $value['Nights'] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $value['ratio'] . "%</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $dists[$value['From']] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $dists[$value['To']] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $value['Days'] . " </td>";
        $checked = "";
        if ($value['Return'] == 1) {
          $checked = " checked";
        }
        echo "<td style='text-align: center;width: 75px;'><input type='checkbox' $checked disabled></td>";
        echo "<td><form method='post'><button type='submit' class='delBtn' name='mode' value='delLecs'>الغاء</button>";
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<input type='hidden' name='LecId' value='" . $value['lecId'] . "'>";
        echo "</form></td></tr>";
        $totT += $value['thhrs'];
        $totP += $value['prhrs'];
      }
      $tot = $totT + $totP;
      echo "<tr>";
      echo "<td width='250px' style='text-align:right;font-weight:bold;'>إجمالي الساعات</td>";
      echo "<td width='75px'style='text-align:center;font-weight:bold;'>" . number_format($totT, 2) . "</td>";
      echo "<td width='75px'style='text-align:center;font-weight:bold;'>" . number_format($totP, 2) . "</td>";
      echo "<td width='450px'style='text-align:right;' colspan='6'>&nbsp;</td>";
      echo "<td width='100px'style='text-align:center;font-weight:bold;'>" . number_format($tot, 2) . "</td>";
      echo "</tr>";

      echo "<tr><td colspan='10'><form method='post'><table width='100%' border='1'>";
      echo "<tr><td width='250px'><select class='input-field' name='fclhLecId'><option value='0'>حدد المحاضر</option>\r\n";
      foreach ($lecNames as $key => $value) {
        echo "<option value='" . $key . "'";
        if (isset($fclhLecId)) {
          if ($fclhLecId == $value) {
            echo " selected";
          }
        }
        echo ">" . $value . "</option>\r\n";
      }
      echo "</select></td>\r\n";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input class='input-field' type='text' placeholder='نظري' name='fclhHoursT'";
      if (isset($fclhHoursT))
        echo " value='$fclhHoursT'";
      echo "></td>";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input class='input-field' type='text' placeholder='عملي' name='fclhHoursP'";
      if (isset($fclhHoursP))
        echo " value='$fclhHoursP'";
      echo "></td>\r\n";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input class='input-field' type='text' placeholder='الليالي' name='fclhNights'";
      if (isset($fclhNights))
        echo " value='$fclhNights'";
      echo "></td>\r\n";

      echo "<td width='80px'><select class='input-field' name='fclhratio'>";
      echo "<option value='-1'>النسبة</option>";
      echo "<option value='100'";
      if (isset($fclhratio)) {
        if ($fclhratio == "100") {
          echo " selected";
        }
      }
      echo ">100%</option>";
      echo "<option value='50'";
      if (isset($fclhratio)) {
        if ($fclhratio == "50") {
          echo " selected";
        }
      }
      echo ">50%</option>";
      echo "</select></td>\r\n";


      echo "<td width='75px'><select class='input-field' name='fclhDistrictFrom'><option value='0'>من</option>\r\n";
      foreach ($dists as $key => $value) {
        echo "<option value='$key'";
        if (isset($fclhDistrictFrom)) {
          if ($fclhDistrictFrom == $key) {
            echo " selected";
          }
        }
        echo ">$value</option>\r\n";
      }
      echo "</select></td>\r\n";

      echo "<td width='75px'><select class='input-field' name='fclhDistrictTo'><option value='0'>الى</option>\r\n";
      foreach ($dists as $key => $value) {
        echo "<option value='$key'";
        if (isset($fclhDistrictTo)) {
          if ($fclhDistrictTo == $key) {
            echo " selected";
          }
        }
        echo ">$value</option>\r\n";
      }
      echo "</select></td>\r\n";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input class='input-field' type='text' placeholder='العدد' name='fclhDays'";
      if (isset($fclhDays))
        echo " value='$fclhDays'";
      echo "></td>\r\n";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input type='hidden' name='fclhReturn' value='0'>";
      echo "العودة:<input type='checkbox' name='fclhReturn' value='1'";
      if (isset($fclhReturn))
        if ($fclhReturn == 1) {
          echo " checked";
        }
      echo "></td>\r\n";

      echo "<td style='text-align: center;vertical-align: middle;'><button type='submit' class='savBtn' name='mode' value='savelecs'> إضافة </button>\r\n";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "</td></tr></table></form>";

      echo "</td></tr></table>\r\n";

      echo "<div class='frmButtons'><br><button type='button' class='addBtn' name='mode' onclick='window.location=\"conractedCourses.php\"'> العودة </button></div>\r\n";
      echo "</div>";
    }



    //************************************************************************************************************* */
    // sec:Save Lecs 4 periods kimo
    //************************************************************************************************************* */
    if ($mode == "kimosavelecs") {
      if ($fclhLecId == "0") {
        $errorMessage .= "لا بد من اختيار المحاضر<br>";
      }
      if ($fclhHoursP == "" and $fclhHoursT == "") {
        $errorMessage .= "لا بد من ادخال عدد الساعات<br>";
      }
      if ($clhPeriod == "0") {
        $errorMessage .= "لا بد من اختيار الفترة<br>";
      }
      $q = "select clhLecId,clhHoursT from CourseLecHours where clhCrsId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $clsId)) {
          if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_bind_result($stmt, $clhLecIdv, $clhHoursTv)) {
              if (mysqli_stmt_fetch($stmt)) {
                if ($clhLecIdv == $clhLecId) {
                  $errorMessage = "لقد تمت إضافة " . $lecs[$row['clhLecId']] . " مسبقا بواقع " . $row['clhHours'] . " ساعات";
                }
              }
            }
          }
        }
        mysqli_stmt_close($stmt);
      }
      if ($errorMessage == "") {
        $q = "INSERT INTO CourseLecHours(clhLecId,clhCrsId,clhHoursP,clhHoursT,clhNights,clhratio,clhDistrictFrom,clhDistrictTo,clhDays,clhReturn,clhPeriod) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if (mysqli_stmt_bind_param($stmt, "iiddiiiiiii", $fclhLecId, $CoursId, $fclhHoursP, $fclhHoursT, $fclhNights, $fclhratio, $fclhDistrictFrom, $fclhDistrictTo, $fclhDays, $fclhReturn, $clhPeriod)) {
            if (!mysqli_stmt_execute($stmt)) {
              $errorMessage .= "Error saving data [050103" . $__uid . date("YmdHis") . "]!...<br>";
              appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050103" . $__uid . date("YmdHis"));
            }
          } else {
            $errorMessage .= "Error saving data [050102]" . $__uid . date("YmdHis") . "]!...<br>";
            appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050102" . $__uid . date("YmdHis"));
          }
          mysqli_stmt_close($stmt);
        } else {
          $errorMessage .= "Error saving data [050101]" . $__uid . date("YmdHis") . "]!...<br>";
          appenmr_mkedLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050101" . $__uid . date("YmdHis"));
        }
      }
      $mode = $fromCaller;
    }


    //********************************************************************************************************* */
    //sec:lecs lecturers form -4 periods kimo
    //********************************************************************************************************* */
    if ($mode == "kimolecs") {
      $showList = false;
      echo "crprId= $crprId";
      echo "<div class=''>";
      if (!isset($clhPeriod)) {
        $clhPeriod = 0;
      }
      $courseP = readClass($dbc, $CoursId);
      $lecs = readCourseLecturers($dbc, $CoursId, $clhPeriod);
      //$batch=readBatch($dbc,$batchId);
      $lecNames = readLecturerNames($dbc);
      if ($errorMessage != "") {
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<div style='max-width:1000px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>محاضري دورة : " . $courseP["Description"] . "</h2>";
      $periods = readPeriods($dbc, $CoursId);
      echo "<form method='post' id='selectPeriod'>";
      echo "<h3>للفترة: <select name='clhPeriod' onchange='getElementById(\"selectPeriod\").submit();'><option value='0' >حدد الفترة</option>";
      foreach ($periods as $key => $value) {
        echo "<option value='$key'";
        if ($key == $clhPeriod) {
          echo " Selected";
        }
        echo ">" . $value['Description'] . " (" . $value['From'] . " - " . $value['To'] . ")</option>";
      }
      echo "</select></h3>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<input type='hidden' name='mode' value=$mode>";
      echo "<input type='hidden' name='crprId' value='$crprId'>";
      echo "</form>";
      echo "<table  width='100%'>";
      echo "<tr><td style='text-align: center;width: 250px;'> المحاضر </td>";
      echo "<td style='text-align: center;width: 75px;'>نظري</td>";
      echo "<td style='text-align: center;width: 75px;'>عملي</td>";
      echo "<td style='text-align: center;width: 75px;'>الليالي</td>";
      echo "<td style='text-align: center;width: 75px;'>النسبة</td>";
      echo "<td style='text-align: center;width: 75px;'>من</td>";
      echo "<td style='text-align: center;width: 75px;'>الى</td>";
      echo "<td style='text-align: center;width: 75px;'>العدد</td>";
      echo "<td style='text-align: center;width: 75px;'>العودة</td>";
      echo "<td>&nbsp;</td></tr>\r\n";
      $totT = 0;
      $totP = 0;
      //lecs here will be the lec that are registerd on the course
      foreach ($lecs as $key => $value) {
        echo "<tr><td width='250px' style='text-align:right;'>" . $lecNames[$value['lecId']] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $value['thhrs'] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $value['prhrs'] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $value['Nights'] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $value['ratio'] . "%</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $dists[$value['From']] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $dists[$value['To']] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $value['Days'] . " مرة</td>";
        $checked = "";
        if ($value['Return'] == 1) {
          $checked = " checked";
        }
        echo "<td style='text-align: center;width: 75px;'><input type='checkbox' $checked disabled></td>";
        echo "<td><form method='post'><button type='submit' class='delBtn' name='mode' value='delLecs'>الغاء</button>";
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<input type='hidden' name='crprId' value='$crprId'>";
        echo "<input type='hidden' name='fromCaller' value='$mode'>";
        echo "<input type='hidden' name='LecId' value='" . $value['lecId'] . "'>";
        echo "</form></td></tr>";
        $totT += $value['thhrs'];
        $totP += $value['prhrs'];
      }
      $tot = $totT + $totP;
      echo "<tr>";
      echo "<td width='250px' style='text-align:right;font-weight:bold;'>إجمالي الساعات</td>";
      echo "<td width='75px'style='text-align:center;font-weight:bold;'>" . number_format($totT, 2) . "</td>";
      echo "<td width='75px'style='text-align:center;font-weight:bold;'>" . number_format($totP, 2) . "</td>";
      echo "<td width='450px'style='text-align:right;' colspan='6'>&nbsp;</td>";
      echo "<td width='100px'style='text-align:center;font-weight:bold;'>" . number_format($tot, 2) . "</td>";
      echo "</tr>";

      echo "<tr><td colspan='10'><form method='post' id='addLec'><table width='100%' border='1'>";
      echo "<tr><td width='250px'><select class='input-field' name='fclhLecId'><option value='0'>حدد المحاضر</option>\r\n";
      foreach ($lecNames as $key => $value) {
        echo "<option value='$key'";
        if (isset($fclhLecId)) {
          if ($fclhLecId == $key) {
            echo " selected";
          }
        }
        echo ">$value</option>\r\n";
      }
      echo "</select></td>\r\n";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input class='input-field' type='text' placeholder='نظري' name='fclhHoursT'";
      if (isset($fclhHoursT))
        echo " value='$fclhHoursT'";
      echo "></td>";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input class='input-field' type='text' placeholder='عملي' name='fclhHoursP'";
      if (isset($fclhHoursP))
        echo " value='$fclhHoursP'";
      echo "></td>\r\n";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input class='input-field' type='text' placeholder='الليالي' name='fclhNights'";
      if (isset($fclhNights))
        echo " value='$fclhNights'";
      echo "></td>\r\n";

      echo "<td width='80px'><select class='input-field' name='fclhratio'>";
      echo "<option value='0'>النسبة</option>";
      echo "<option value='100'";
      if (isset($fclhratio)) {
        if ($fclhratio == "100") {
          echo " selected";
        }
      }
      echo ">100%</option>";
      echo "<option value='50'";
      if (isset($fclhratio)) {
        if ($fclhratio == "50") {
          echo " selected";
        }
      }
      echo ">50%</option>";
      echo "</select></td>\r\n";


      echo "<td width='75px'><select class='input-field' name='fclhDistrictFrom'><option value='0'>من</option>\r\n";
      foreach ($dists as $key => $value) {
        echo "<option value='$key'";
        if (isset($fclhDistrictFrom)) {
          if ($fclhDistrictFrom == $key) {
            echo " selected";
          }
        }
        echo ">$value</option>\r\n";
      }
      echo "</select></td>\r\n";

      echo "<td width='75px'><select class='input-field' name='fclhDistrictTo'><option value='0'>الى</option>\r\n";
      foreach ($dists as $key => $value) {
        echo "<option value='$key'";
        if (isset($fclhDistrictTo)) {
          if ($fclhDistrictTo == $key) {
            echo " selected";
          }
        }
        echo ">$value</option>\r\n";
      }
      echo "</select></td>\r\n";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input class='input-field' type='text' placeholder='العدد' name='fclhDays'";
      if (isset($fclhDays))
        echo " value='$fclhDays'";
      echo "></td>\r\n";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input type='hidden' name='fclhReturn' value='0'>";
      echo "العودة:<input type='checkbox' name='fclhReturn' value='1'";
      if (isset($fclhReturn))
        if ($fclhReturn == 1) {
          echo " checked";
        }
      echo "></td>\r\n";

      echo "<td style='text-align: center;vertical-align: middle;'><button type='submit' class='savBtn' name='mode' value='kimosavelecs'> إضافة </button>\r\n";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<input type='hidden' name='fromCaller' value='$mode'>";
      echo "<input type='hidden' name='clhPeriod' value='$clhPeriod'>";
      echo "<input type='hidden' name='crprId' value='$crprId'>";
      echo "</td></tr></table></form>";

      echo "</td></tr></table>\r\n";

      echo "<div class='frmButtons'><br><form method='post'>";
      echo "<input type='hidden' name='crprId' value='$crprId'>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<input type='hidden' name='mode' value='periods'>";
      echo "<button type='submit' class='addBtn'> العودة </button></div>\r\n ";
      echo "</div>";
      echo "</div>";
    }




    //**************************************************************************************************************
    // sec:lecsPeriods
    //***********************************************************************************************************
    if ($mode == 'lecsPeriods') {
      printMode($mode, $showMode);

      $_SESSION['CourseId'] = array($CoursId, $mnuId);
      $_SESSION['periodId'] = array($crprId, $mnuId);

      $showList = false;
      include("$__includeDir/readPrograms.php");
      include("$__includeDir/readCourseData.php");
      include("$__includeDir/readCourseLecs.php");
      include("$__includeDir/readLecNames.php");
      if ($errorMessage != "") {
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<div style='max-width:1000px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>محاضري دورة " . $prgs[$CoursCrsId]["Name"] . "<br>منشور رقم : $CoursBulletin</h2><hr>\r\n";
      echo "<table  width='100%'>";
      echo "<tr><td style='text-align: center;width: 250px;'> المحاضر </td>";
      echo "<td style='text-align: center;width: 75px;'>نظري</td>";
      echo "<td style='text-align: center;width: 75px;'>عملي</td>";
      echo "<td style='text-align: center;width: 75px;'>الليالي</td>";
      echo "<td style='text-align: center;width: 75px;'>النسبة</td>";
      echo "<td style='text-align: center;width: 75px;'>من</td>";
      echo "<td style='text-align: center;width: 75px;'>الى</td>";
      echo "<td style='text-align: center;width: 75px;'>عدد ايام الانتقال</td>";
      echo "<td style='text-align: center;width: 75px;'>العودة</td>";
      echo "<td>&nbsp;</td></tr>\r\n";
      $totT = 0;
      $totP = 0;
      foreach ($lecs as $key => $value) {
        echo "<tr><td width='250px' style='text-align:right;'>" . $lecNames[$value['lecId']] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $value['thhrs'] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $value['prhrs'] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $value['Nights'] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $value['ratio'] . "%</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $dists[$value['From']] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $dists[$value['To']] . "</td>";
        echo "<td style='text-align: center;width: 75px;'>" . $value['Days'] . " </td>";
        $checked = "";
        if ($value['Return'] == 1) {
          $checked = " checked";
        }
        echo "<td style='text-align: center;width: 75px;'><input type='checkbox' $checked disabled></td>";
        echo "<td><form method='post'><button type='submit' class='delBtn' name='mode' value='delLecs'>الغاء</button>";
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<input type='hidden' name='fromCaller' value='$mode'>";
        echo "<input type='hidden' name='LecId' value='" . $value['lecId'] . "'>";
        echo "</form></td></tr>";
        $totT += $value['thhrs'];
        $totP += $value['prhrs'];
      }
      $tot = $totT + $totP;
      echo "<tr>";
      echo "<td width='250px' style='text-align:right;font-weight:bold;'>إجمالي الساعات</td>";
      echo "<td width='75px'style='text-align:center;font-weight:bold;'>" . number_format($totT, 2) . "</td>";
      echo "<td width='75px'style='text-align:center;font-weight:bold;'>" . number_format($totP, 2) . "</td>";
      echo "<td width='450px'style='text-align:right;' colspan='6'>&nbsp;</td>";
      echo "<td width='100px'style='text-align:center;font-weight:bold;'>" . number_format($tot, 2) . "</td>";
      echo "</tr>";

      echo "<tr><td colspan='10'><form method='post'><table width='100%' border='1'>";
      echo "<tr><td width='250px'><select class='input-field' name='fclhLecId'><option value='0'>حدد المحاضر</option>\r\n";
      foreach ($lecNames as $key => $value) {
        echo "<option value='" . $key . "'";
        if (isset($fclhLecId)) {
          if ($fclhLecId == $value) {
            echo " selected";
          }
        }
        echo ">" . $value . "</option>\r\n";
      }
      echo "</select></td>\r\n";
      //:check: [2023-11-06 Mon]
      $periodsListing = readPeriods($dbc, $CoursId);
      echo "<tr><td width='250px'><select class='input-field' name='period'><option value='0'>حدد الفترة</option>\r\n";
      foreach ($lecNames as $key => $value) {
        echo "<option value='" . $key . "'";
        if (isset($fclhLecId)) {
          if ($fclhLecId == $value) {
            echo " selected";
          }
        }
        echo ">" . $value . "</option>\r\n";
      }
      echo "</select></td>\r\n";




      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input class='input-field' type='text' placeholder='نظري' name='fclhHoursT'";
      if (isset($fclhHoursT))
        echo " value='$fclhHoursT'";
      echo "></td>";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input class='input-field' type='text' placeholder='عملي' name='fclhHoursP'";
      if (isset($fclhHoursP))
        echo " value='$fclhHoursP'";
      echo "></td>\r\n";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input class='input-field' type='text' placeholder='الليالي' name='fclhNights'";
      if (isset($fclhNights))
        echo " value='$fclhNights'";
      echo "></td>\r\n";

      echo "<td width='80px'><select class='input-field' name='fclhratio'>";
      echo "<option value='-1'>النسبة</option>";
      echo "<option value='100'";
      if (isset($fclhratio)) {
        if ($fclhratio == "100") {
          echo " selected";
        }
      }
      echo ">100%</option>";
      echo "<option value='50'";
      if (isset($fclhratio)) {
        if ($fclhratio == "50") {
          echo " selected";
        }
      }
      echo ">50%</option>";
      echo "</select></td>\r\n";


      echo "<td width='75px'><select class='input-field' name='fclhDistrictFrom'><option value='0'>من</option>\r\n";
      foreach ($dists as $key => $value) {
        echo "<option value='$key'";
        if (isset($fclhDistrictFrom)) {
          if ($fclhDistrictFrom == $key) {
            echo " selected";
          }
        }
        echo ">$value</option>\r\n";
      }
      echo "</select></td>\r\n";

      echo "<td width='75px'><select class='input-field' name='fclhDistrictTo'><option value='0'>الى</option>\r\n";
      foreach ($dists as $key => $value) {
        echo "<option value='$key'";
        if (isset($fclhDistrictTo)) {
          if ($fclhDistrictTo == $key) {
            echo " selected";
          }
        }
        echo ">$value</option>\r\n";
      }
      echo "</select></td>\r\n";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input class='input-field' type='text' placeholder='العدد' name='fclhDays'";
      if (isset($fclhDays))
        echo " value='$fclhDays'";
      echo "></td>\r\n";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input type='hidden' name='fclhReturn' value='0'>";
      echo "العودة:<input type='checkbox' name='fclhReturn' value='1'";
      if (isset($fclhReturn))
        if ($fclhReturn == 1) {
          echo " checked";
        }
      echo "></td>\r\n";

      echo "<td style='text-align: center;vertical-align: middle;'><button type='submit' class='savBtn' name='mode' value='savelecs'> إضافة </button>\r\n";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "</td></tr></table></form>";

      echo "</td></tr></table>\r\n";

      echo "<form method='post'>";
      echo "<div class='frmButtons'>";
      echo "<br><button type='submit' class='addBtn' name='mode' value='periods'> العودة </button></div>\r\n";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<input type='hidden' name='crprId' value='$crprId'>";
      echo "</td></tr></table></form>";
      echo "</div>";
      echo "</form>";
    }





    //**************************************************************************************************************
    // sec:periods-data-validation
    //**************************************************************************************************************
    if ($mode == 'saveaddPeriods' or $mode == 'saveeditPeriods') {
      echo "newPeriod Flag $newPeriod<br>";
      //validation for new or old records
      if ($crprDescription == "") {
        $errorMessage .= "لابد من ادخال وصف للفترة<br>";
      }
      if ($crprFrom == "") {
        $errorMessage .= "لابد من ادخال تاريخ البدء<br>";
      }
      if ($crprTo == "") {
        $errorMessage .= "لابد من ادخال تاريخ النهاية<br>";
      }

      if ($errorMessage != "") {
        $mode = substr($mode, 4);
      } else
        $mode = "SQLPeriods";
    }

    //////////////////////////////////////////////////////////////////////////////////////
    // sec:SQLPeriods                                                                   //
    //////////////////////////////////////////////////////////////////////////////////////
    if ($mode == "SQLPeriods") {
      printMode($mode, $showMode);
      $crprCourseId = $CoursId;
      if ($newPeriod == 'edit') {
        $q = "update coursPeriods set crprDescription=?,crprFrom=?,crprTo=? where crprCourseId=? and crprId=?;";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if (mysqli_stmt_bind_param($stmt, "sssii", $crprDescription, $crprFrom, $crprTo, $CoursId, $crprId)) {
            if (!mysqli_stmt_execute($stmt)) {
              $errorMessage .= "Error saving data [050103" . $__uid . date("YmdHis") . "]!...<br>";
              appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050103" . $__uid . date("YmdHis"));
            }
          } else {
            $errorMessage .= "Error saving data [050102]" . $__uid . date("YmdHis") . "]!...<br>";
            appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050102" . $__uid . date("YmdHis"));
          }
          mysqli_stmt_close($stmt);
        } else {
          $errorMessage .= "Error saving data [050101]" . $__uid . date("YmdHis") . "]!...<br>";
          appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050101" . $__uid . date("YmdHis"));
        }
        $mode = "periods";
      }
      if ($newPeriod == 'new') {
        $q = "INSERT INTO  coursPeriods(crprDescription,crprFrom,crprTo,crprCourseId) VALUES (?,?,?,?)";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if (mysqli_stmt_bind_param($stmt, "sssi", $crprDescription, $crprFrom, $crprTo, $crprCourseId)) {
            if (!mysqli_stmt_execute($stmt)) {
              $errorMessage .= "Error saving data [050103" . $__uid . date("YmdHis") . "]!...<br>";
              appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050103" . $__uid . date("YmdHis"));
            }
          } else {
            $errorMessage .= "Error saving data [050102]" . $__uid . date("YmdHis") . "]!...<br>";
            appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050102" . $__uid . date("YmdHis"));
          }
          mysqli_stmt_close($stmt);
        } else {
          $errorMessage .= "Error saving data [050101]" . $__uid . date("YmdHis") . "]!...<br>";
          appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050101" . $__uid . date("YmdHis"));
        }
      }
      $mode = "periods";
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // sec:conReportPeriods                                                             //
    //////////////////////////////////////////////////////////////////////////////////////
    if ($mode == 'conReportPeriods') {
      echo "<script> window.open('conReportPeriods.php?CoursId=$CoursId,crprId=$crprId');</script>";
      $mode = 'periods';
    }





    /////////////////////////////////////////////////////////////////////////////////////
    // sec:periods-mode                                                                //
    /////////////////////////////////////////////////////////////////////////////////////
    if ($mode == 'periods') {
      $showList = false;
      $newPeriod = false;
      printMode($mode, $showMode);
      //color for main showlist i.e courses not periods
      $_SESSION['CourseId'] = array($CoursId, $mnuId);
      $sessionCourseId = $_SESSION['CourseId'];

      //hilite color period section -->
      if (isset($crprId))
        $_SESSION['periodId'] = array($crprId, $mnuId);
      $HilitePeriodId = "";
      if (isset($crprId)) {
        $HilitePeriodId = $crprId;
      } elseif (isset($_SESSION['periodId'])) {
        $sessionPeriodId = $_SESSION['periodId'];
        if ($sessionCourseId[1] == $mnuId) {
          $HilitePeriodIId = $sessionPeriodId[0];
        }
      }
      //hilite color period section  <--
      //echo "<h3>فترات دورة" . $prgs[$CoursId]['Name'] . "</h3> "; to be refactored
      echo "<h3>إخطار رقم" . $CoursBulletin . "</h3> ";
      if ($errorMessage != "") {
        echo "<div class='error'>$errorMessage<br><br></div>";
      }
      $q = "SELECT crprId ,crprDescription ,crprFrom ,crprTo ,crprCourseId  from coursPeriods where crprCourseId=$CoursId";
      $r = mysqli_query($dbc, $q);
      if ($r) {
        //new form
        echo "<div style='width: 110px; margin: auto;'><form method='post'>";
        echo "<input type='hidden' name='mode' value='addPeriods'>";
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<input type='hidden' name='CoursBulletin' value='$CoursBulletin'>";
        echo "<button type='submit' class='addBtn'>جديد</button>";
        echo "</form></div><br>";
        //filter list form
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table id='mainTable'>";
        echo "<tr class='header'>";
        echo "<th>الفترات - اسم الدورة</th><th style='width:900px;text-align: center;'></th></tr>";
        $rowNo = 0;
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
          foreach ($row as $key => $value) {
            $$key = $value;
          }
          $rowNo++;
          $rowStyle = "";
          if ($crprId == $HilitePeriodId) {
            $rowStyle = " style='background-color: DarkKhaki;'";
          }

          if (!isset($CoursStatus))
            $CoursStatus = "";

          echo "<tr $rowStyle><td>$crprDescription ";
          if (isset($perms['Developer'])) {
          include('conractedDevInfo.php');
           // echo "<br>Course Status: [$CoursStatus] - Course Id: [$CoursId] - crpr Id: [$crprId]";
          }
          echo "</td>";
          echo "<td style='text-align:right';><form method='post'>";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          echo "<input type='hidden' name='CoursBulletin' value='$CoursBulletin'>";
          echo "<input type='hidden' name='crprId' value='$crprId'>";

          if (!isset($buttonStatus))
            $buttonStatus = "";

          $prdStatus = readPeriod($dbc,$crprId)['Status'];
          if($prdStatus == $formCourseStatusLevel){
            $buttonStatus = "";
          }else{
              $buttonStatus = " disabled style='background-color: lightgrey;'";
          }
          echo "<button type='submit' class='edtBtn' name='mode' value='editPeriods' $buttonStatus>تعديل</button> ";
          echo "<button type='submit' class='viewBtn' name='mode' value='viewPeriods'>عرض</button> ";
          echo "<button type='submit' class='delBtn' name='mode' value='deleteconfirmPeriods'$buttonStatus>الغاء</button> ";
          echo "</form></td></tr>";
        }
        echo "</table>";
        echo "<form>";
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<div class='frmButtons'><button type='submit' class='cnlBtn' name='mode' value=''>تراجع</button></div>";
        echo "</form>";
        mysqli_free_result($r);
      }
    }





    //////////////////////////////////////////////////////////////////////////////////////
    // sec:periods-add-edit-view mode
    //////////////////////////////////////////////////////////////////////////////////////

    if ($mode == 'addPeriods' or $mode == 'editPeriods' or $mode == 'viewPeriods') {
      $showList = false;
      printMode($mode, $showMode);
      $whereClause = '';
      if ($mode == 'editPeriods' or $mode == 'viewPeriods')
        $whereClause = "and crprId=$crprId";
      $q = "SELECT crprId ,crprDescription ,crprFrom ,crprTo ,crprCourseId  from coursPeriods where crprCourseId=$CoursId $whereClause ";
      $r = mysqli_query($dbc, $q);
      if ($r) {
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
          foreach ($row as $key => $value) {
            $$key = $value;
          }
        }
      }

      if ($mode == 'viewPeriods') {
        $disabled = " disabled";
      } else {
        $disabled = "";
      }

      if (!isset($crprDescription))
        $crprDescription = "";
      if (!isset($crprFrom))
        $crprFrom = "";
      if (!isset($crprTo))
        $crprTo = "";
      echo "<form method='post' style='max-width:$formWidth;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>إضافة فترة لدورة   " . $CoursBulletin . "</h2>";
      if ($errorMessage != "") {
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<table width='100%'>";
      echo "<tr><td style='width: $labelWidth;'>وصف الفترة</td>";
      echo "<td><div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='وصف الفترة' id='crprDescription' name='crprDescription' value='$crprDescription'  $disabled>";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>من</td>";
      echo "<td><div class='input-container'>";
      echo "<input class='input-field' type='date' id='crprFrom' value='$crprFrom' name='crprFrom' $disabled>";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>إلى</td>";
      echo "<td><div class='input-container'>";
      echo "<input class='input-field' type='date'  id='crprTo' value='$crprTo' name='crprTo' $disabled>";
      echo "</div></td></tr>";
      echo "</table>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      if (isset($crprId))
        echo "<input type='hidden' name='crprId' value='$crprId'>";
      if ($mode == 'addPeriods') {
        $newPeriod = "new";
      }
      if ($mode == 'editPeriods') {
        $newPeriod = "edit";
      }
      if ($mode == 'viewPeriods') {
        echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode' value='periods'>موافق</button></div>";
        echo "<input type='hidden' name='CoursBulletin' value='$CoursBulletin'>";
        $newPeriod = '';
      } else {
        echo "<input type='hidden' name='CoursBulletin' value='$CoursBulletin'>";
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='save$mode'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value='periods'>تراجع</button></div>";
      }
      echo "<input type='hidden' name='newPeriod' value='$newPeriod'>";
      echo "</form>";
    }


    //**************************************************************************************************************
    // sec:deleteconfirmPeriods-confirmation delete confirmation for period
    //**************************************************************************************************************
    if ($mode == 'deleteconfirmPeriods') {
      $showList = false;

      $q = "SELECT crprId ,crprDescription ,crprFrom ,crprTo ,crprCourseId  from coursPeriods where crprCourseId=$CoursId and crprId=$crprId";
      $r = mysqli_query($dbc, $q);
      if ($r) {
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
          foreach ($row as $key => $value) {
            $$key = $value;
          }
        }
      }
      echo "<form method='post' style='max-width:500px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>الغاء</h2>";
      echo "<div style='text-align: center;direction:$__dir;'>سيتم الغاء فترة $crprDescription<br> هل انت متأكد؟...</div><br>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<input type='hidden' name='crprId' value='$crprId'>";
      echo "<input type='hidden' name='crprCourseId' value='$crprCourseId'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='deletePeriods'>نعم</button> <button type='submit' class='cnlBtn' name='mode' value='periods'>لا</button></div>";
      echo "</form>";
    }

    //**************************************************************************************************************
    // sec:conReport main
    //**************************************************************************************************************
    if ($mode == 'conReport') {
      echo "<script> window.open('conReport.php?CoursId=$CoursId');</script>";
    }


    //**************************************************************************************************************
    // sec:course-list
    //**************************************************************************************************************
    if ($showList) {
      $newCourse = false;
      //hilite color section -->
      $HiliteCourseId = "";
      if (isset($CoursId)) {
        $HiliteCourseId = $CoursId;
      } elseif (isset($_SESSION['CourseId'])) {
        $sessionCourseId = $_SESSION['CourseId'];
        if ($sessionCourseId[1] == $mnuId) {
          $HiliteCourseId = $sessionCourseId[0];
        }
      }
      $currentEduYear=getSystemValue('curEduYear');
      $previousEduYear=getSystemValue('PreEduYear');
      //hilite color section  <--
      if ($errorMessage != "") {
        echo "<div class='error'>$errorMessage<br><br></div>";
      }
      $newLevel=$formCourseStatusLevel+1;
      $q = "SELECT CoursId,CoursBulletin,CrsName,CoursStatus,CoursDevidable,CoursYear FROM Courses inner join CoursesGuide on CoursCrsId= CrsId WHERE CoursType=2 and (CoursYear=" . $user_year['Id'] . " OR (CoursYear=".$previousEduYear ." and CoursStatus <= ".$newLevel.")) order by CoursBulletin ";
      if (isset($perms['Developer'])) {
        echo $q;
      }
      $r = mysqli_query($dbc, $q);
      if ($r) {
        //new form
        echo "<div style='width: 110px; margin: auto;'><form method='post'>";
        echo "<input type='hidden' name='mode' value='add'>";
        echo "<button type='submit' class='addBtn'>جديد</button>";
        echo "</form></div><br>";
        //filter list form 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table id='mainTable'>";
        echo "<tr class='header'>";
        echo "<th>المنشور - اسم الدورة</th><th style='width:900px;text-align: center;'></th></tr>";
        $rowNo = 0;
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
          foreach ($row as $key => $value) {
            $$key = $value;
          }
          if ($CoursStatus == $formCourseStatusLevel + 1) {
            $printButtonStatus = $status[0];
          } else {
            $printButtonStatus = $status[1];
          }
          if ($CoursStatus > $formCourseStatusLevel) {
            $buttonStatus = $status[1];
          } else {
            $buttonStatus = $status[0];
          }
          $rowNo++;
          $rowStyle = "";
          if ($CoursId == $HiliteCourseId) {
            $rowStyle = " style='background-color: DarkKhaki;'";
          }

          $message="";
          if($CoursYear == $previousEduYear){
            $message="<span style='color:red;'>[العام السابق]</span>";
          }

          echo "<tr $rowStyle><td>$CoursBulletin - $CrsName $message";
          if (isset($perms['Developer'])) {
            echo "<br>Course Status: [$CoursStatus] - Course Id: [$CoursId] - Course Year: [$CoursYear]";
          }
          echo "</td>";
          echo "<td style='text-align:right';><form method='post'>";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          echo "<input type='hidden' name='CoursBulletin' value='$CoursBulletin'>";

          if ($CoursDevidable == 0) {
            $periodDisbale = "disabled";
            $class = " noHover disabled ";
          } else {
            $periodDisbale = "";
            $class = "";
          }

          echo "<button type='submit' class='pwdBtn $class'  name='mode' value='periods' $periodDisbale >فترات</button> ";
          echo "<button type='submit' class='edtBtn' name='mode' value='edit'$buttonStatus>تعديل</button> ";
          echo "<button type='submit' class='viewBtn' name='mode' value='view'>عرض</button> ";
          echo "<button type='submit' class='delBtn' name='mode' value='deleteconfirm'$buttonStatus>الغاء</button> ";
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
