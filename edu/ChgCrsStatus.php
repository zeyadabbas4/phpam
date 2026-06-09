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
$boolarray = array(false => 'false', true => 'true');
$systemArray= array (-1=> 'Choose filter type',2 =>'Conracted',1=>'Planned',5=>'Basic Studies',4=>'Obligatory Courses',3=>'Simulator');
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
    $pageTitle = "Change course status form for years:- " . $user_year['Desc'];
    if (!isset($filterType)) {
      $filterType = -1;
    }
    $pageSubTitle =  $systemArray[$filterType];
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
    echo ".prdBtn {background-color: #E74C3C;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 150px;  opacity: 0.9; border-radius:6px;}";
    echo ".filterBtn {background-color: #BA4A00;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 150px;  opacity: 0.9; border-radius:6px;}";
    echo ".disabled {opacity: 0.3; cursor:not-allowed;}";
    echo ".noHover {pointer-events: none;}";
    echo ".statusBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 150px;  opacity: 0.9; border-radius:6px;}";
    echo ".pwdBtn:hover {opacity: 1;}";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>$pageTitle</h2>";
    echo "<br><h3 style='text-align: center;color: RoyalBlue;'>$pageSubTitle</h3>";

    /***********************************************************************************
 //sec:Confirm
 // ************************************************************************************/
    if (!isset($newStatus)) {  // trap the case of empty new Status as it defaults to zero
      $newStatus = "";
    }
    while ($mode == "chgConfirm") {
      if ($newStatus == "") {
        $errorMessage = "new status field can't be empty";
        if (isset($crprId)) {
          $mode = "prds";
        }
        break;
      }
      $showList = false;
      echo "<div style='width:800px,margin: auto;text-align: center;'>";
      echo "هل أنت متأكد...";
      echo "<center>";
      echo "<br><br>";
      $newMode = str_replace("Confirm", "Save", $mode);
      echo "<form method='post'>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      if (isset($crprId)) {
        echo "<input type='hidden' name='crprId' value='$crprId'>";
      }
      echo "<input type='hidden' name='filterType' value='$filterType'>";
      echo "<input type='hidden' name='newStatus' value='$newStatus'>";
      echo "<input type='hidden' name='changeType' value='$changeType'>";
      echo "<button type='submit' name='mode' value='$newMode' class='yesBtn'>نعم</button> ";
      echo "<button type='submit' name='mode' value='view' class='noBtn'>لا</button> ";
      echo "</form>";
      echo "</center>";
      echo "</div>";
      break;
    }


    /*********************************************************************************************************** */
    //sec:chg Status
    /*********************************************************************************************************** */

    if ($mode == "chgSave") {
      if ($changeType == "crsChange") {
        setCrsStat($dbc, $CoursId, $newStatus);
      }

      if ($changeType == "prdChange") {
        setPerStat($dbc, $crprId, $newStatus);
        $mode = "prds";
      }
    }


    /*********************************************************************************************************** */
    //sec:prds
    /*********************************************************************************************************** */
    if ($mode == "prds") {
      $showList = false;
      if ($errorMessage != "") {
        echo "<div class='error'>$errorMessage<br><br></div>";
      }

      $q = "SELECT crprId ,crprDescription ,crprFrom ,crprTo ,crprCourseId,crprStatus  from coursPeriods where crprCourseId=$CoursId";
      $r = mysqli_query($dbc, $q);
      if ($r) {
        //filter list form
        $crsName = getCrsInfo($dbc, $CoursId)['Name'];
        $crsBullNum = getCrsInfo($dbc, $CoursId)['CoursBulletin'];
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name' >";
        echo "<table id='mainTable'>";
        echo "<tr class='header'>";
        echo "<th style='width:1100px;'>فترات -";
        echo " $crsName - $crsBullNum </th><th>Status</th><th style='width:200px;text-align:center;'>New Status</th><th> </th><th style='width:900px;text-align: center;'></th></tr>";
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
          foreach ($row as $key => $value) {
            $$key = $value;
          }

          echo "<tr><td style='text-algin:right;'>$crprDescription</td><td style='text-align:center;'>$crprStatus</td><td>$crprId</td>";
          echo "<td style='text-align:center';><form method='post'>";
          echo "<input type='text' name='newStatus' value='' size='3' style='text-align:center;'></td>";
          echo "<td><button type='submit' class='statusBtn' name='mode' value='chgConfirm'>Change Status</button></td> ";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          echo "<input type='hidden' name='changeType' value='prdChange'>";
          echo "<input type='hidden' name='filterType' value='$filterType'>";
          echo "<input type='hidden' name='crprId' value='$crprId'>";
          echo "</form></td></tr>";
        }
        echo "</table>";
        echo "<form method='post'>";
        echo "<div class='frmButtons'><button type='submit' class='cnlBtn' name='mode' value=''>تراجع</button></div>";
        echo "<input type='hidden' name='filterType' value='$filterType'>";
        echo "</form>";
        mysqli_free_result($r);
      }
    }


    /*********************************************************************************************************** */
    //sec:courses list
    /*********************************************************************************************************** */
    if ($showList) {
      if ($errorMessage != "") {
        echo "<div class='error'>$errorMessage<br><br></div>";
      }
      if (!isset($filterType)) {
        $filterType = -1;
      }
      $userYearFilter = "";
      if ($filterType != 5) {
        $userYearFilter = "AND CoursYear=" . $user_year['Id'];
      }
      $q = "SELECT distinct CoursYear,CoursId,CoursBulletin,CrsName,CoursStatus FROM Courses inner join CoursesGuide on CoursCrsId =CrsId left JOIN  coursPeriods on CoursId=crprCourseId WHERE CoursType=$filterType $userYearFilter order by CoursId";
      $r = mysqli_query($dbc, $q);
      if ($r) {
        //filter list form 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table id='crsType' style='width:100%;'>";
        echo "<tr><td style='text-align: center;'><form method='post'>";
        echo "<button type='submit' class='filterBtn' name='filterType' value='2' >Conracted</button> ";
        echo "<button type='submit' class='filterBtn' name='filterType' value='1' >Planned</button> ";
        echo "<button type='submit' class='filterBtn' name='filterType' value='5' >BasicStd</button> ";
        echo "<button type='submit' class='filterBtn' name='filterType' value='4' >Obligatory</button> ";
        echo "<button type='submit' class='filterBtn' name='filterType' value='3' >Simulator</button> ";
        echo "</form></td></tr></table>";

        echo "<table id='mainTable'>";
        echo "<tr class='header'>";
        echo "<th style='width:1100px;'>المنشور - اسم الدورة</th>";
        echo "<th>Course Status</th><th>New Status</th> <td></th><th style='width:900px;text-align: left;'>Periods</th></tr>";
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

          $dividable = courseIsDevidable($dbc, $CoursId);
          $devInfo = "";
          if (isset($perms['Developer'])) {
            include('conractedDevInfo.php');
          }
          $rowNo++;

          $buttonStatus = "";
          $listDisabled = "";
          if (count($periods) == 0) {
            $listDisabled = " disabled ";
          }
          $allCrsPrds = readPeriods($dbc, $CoursId);
          $sortedPrd = srtPrd($periods, "Status");
          $prdButton = "noHover disabled ";
          if ($dividable) {
            $prdButton = "";
          }

          if($filterType == 5) {
            $bsYears= readBSYears($dbc);
            $bsYearDesc = $bsYears[$CoursYear]['desc'];
          }
          echo "<tr><td>$CoursYear - $CoursBulletin - $CrsName -  crsId: $CoursId";
          if($filterType==5){
            echo " - $bsYearDesc";
          }
          echo "</td><td>$CoursStatus</td>";
          echo "<td style='text-align: left;'>";
          echo "<form method='post'>";
          echo "<input type='text' name='newStatus' value='' size='3' style='text-align:center;' ></td>";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          echo "<td><button type='submit' class='statusBtn' name='mode' value='chgConfirm'>Change Status</button></td> ";
          echo "<input type='hidden' name='changeType' value='crsChange'>";
          echo "<input type='hidden' name='filterType' value='$filterType'>";
          echo "<td><button type='submit' class='prdBtn $prdButton'  name='mode' value='prds' >Periods</button></td> ";
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
