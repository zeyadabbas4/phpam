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
//echo "mode is $mode"; //catch me
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
$formCourseStatusLevel = 1;
$showMode = false;
$boolarray = array(false => 'false', true => 'true');
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
    echo ".costInput {color: black;  padding: 10px 10px;  box-sizing: border-box;  width: 70px; margin: 0px;}";
    echo ".costBtn {background-color: darkblue;  color: white;  padding:10px;margin: 3px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".delBtn {background-color: darkred;  color: white;  padding: 10px 10px;margin:3px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
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
    echo ".attBtn {background-color: DodgerBlue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".attBtn:hover {opacity: 1;}";
    echo ".startBtn {background-color: #7FFF00;  color: #191970;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".startBtn:hover {opacity: 1;}";
    echo ".nflBtn {background-color: SteelBlue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".nflBtn:hover {opacity: 1;}";
    echo ".filBtn {background-color: DarkViolet;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".filBtn:hover {opacity: 1;}";
    echo ".input-container {display: -ms-flexbox; /* IE10 */  display: flex;  width: 100%;  margin-bottom: 15px;}";
    echo ".input-field { width: 100%;  padding: 10px;  outline: none;}";
    echo ".check-box   {height: 20px; width: 20px;}";
    echo ".input-field:focus {border: 2px solid dodgerblue;}";
    echo ".error{color:red; font-weight:bold;text-align:center;direction:rtl;}";
    echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>$pageTitle</h2>";

    //**************************************************************************************************************
    //sec:load-ref-data
    //**************************************************************************************************************
    $dists = array();
    $dists[0] = " ";
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
        if ($CouursToPln == "") {
          $errorMessage .= "لا بد من ادخال تاريخ النهاية<br>";
        }
        if ($CoursLocation == "0") {
          $errorMessage .= "لا بد من ادخال مكان الانعقاد<br>";
        }
        if ($CoursBulletin == "") {
          $errorMessage .= "لا بد من ادخال رقم المنشور<br>";
        }
      }
    }
//************************************************************************************************************* */
//Save Attachment
//************************************************************************************************************* */
      if($mode=="saveAttachments"){
        $errormsg="";
        $fileList=array();
        $fileDescription=array();
        $target_dir = "Attachments/";
        foreach($_FILES["fcatFile"]["name"] as $key => $value){
          $uploadedFileName=basename($value);
          $fileType = strtolower(pathinfo($uploadedFileName,PATHINFO_EXTENSION));
          $fileExtension = "." . $fileType;
          $target_file = $target_dir . basename($value,$fileExtension) . "-" . $CoursId . $fileExtension;
          $check=false;
          if($fileType == "jpg"){
            $check = getimagesize($_FILES["fcatFile"]["tmp_name"][$key]);
          }elseif($fileType=="pdf"){
            $type=$_FILES["fcatFile"]["type"][$key];
            $check=( $type === "application/pdf") ? true : false;
          }
          if($check !== false) {
            $uploadOk = 1;
          } else {
            $errormsg .= "خطأ في التحميل ملف غير متوافق.<br>";
            $uploadOk = 0;
          }
          if($fileType != "jpg" and $fileType !="pdf") {
            $errormsg.= "مسموح فقط بتحميل الملفات من نوع jpg و pdf.<br>";
            $uploadOk = 0;
          }
          if ($uploadOk == 0) {
            $errormsg .= "لم يتم تحميل الملف $key.";
            echo "<div style='text-align: center;color: red;'>$errormsg</div>";
          } else {
            if (move_uploaded_file($_FILES["fcatFile"]["tmp_name"][$key], $target_file)) {
              $fileList[]=basename($target_file);
              $fileDescription[]=$fcatDescription[$key];
              echo "<div style='text-align: center;color: green;direction:rtl;'>تم تحميل [". htmlspecialchars($target_file)."].</div>";
              $moveOk=1;
            } else {
              echo "<div style='text-align: center;color: red;'>خطأ في تحميل الملف.</div>";
              $moveOk=0;
            }
          }
        }

        if($uploadOk == 1 and $moveOk == 1){
          $q="INSERT INTO courseAttachments (catDescription, catFile, catCourse) VALUES (?,?,?)";
          if ($stmt = mysqli_prepare($dbc, $q)){
            foreach($fileList as $key => $value){
              
              if(mysqli_stmt_bind_param($stmt, "ssi", $fileDescription[$key],$value,$CoursId)){
                mysqli_stmt_execute($stmt);
              }  
            }
          }
        }
        $mode="attachment";
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

//************************************************************************************************************* */
//Delete Attachment Confirm form
//************************************************************************************************************* */
    if($mode=="deleteAttachmentConfirm"){
      $showList=false;
      include("$__includeDir/readPrograms.php"); 
      include("$__includeDir/readCourseData.php");    
      echo "<div style='text-align: center;width: 800px; margin: auto;'>";
      echo "<h3>مرفقات دورة " . $prgs[$CoursCrsId]['Name']."</h3>";
      $q="SELECT catDescription,catFile FROM courseAttachments WHERE catid=?";
      if($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $catid)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $catDescription,$catFile)){
              if(mysqli_stmt_fetch($stmt)){
                echo "سيتم الغاء $catDescription هل أنت متاكد<br><br>";
              }
            }
          }
        }
      }
      echo "<form method='post'>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<input type='hidden' name='catid' value='$catid'>";
      echo "<button type='submit' name='mode' class='okBtn' value='attachment' > غير موافق </button> ";
      echo "<button type='submit' name='mode' class='delBtn' value='deleteAttachment' > موافق </button> ";
      echo "</form>";
      echo "</div>";
    }

//************************************************************************************************************* */
//Delete Attachment
//************************************************************************************************************* */
    if($mode=="deleteAttachment"){
      $q="SELECT catDescription,catFile FROM courseAttachments WHERE catid=?";
      if($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $catid)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $catDescription,$catFile)){
              mysqli_stmt_fetch($stmt);
            }
          }
        }
        mysqli_stmt_close($stmt);
      }
      $deleted=false;
      $q="DELETE FROM courseAttachments WHERE catid=?";
      if($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $catid)){
          if(mysqli_stmt_execute($stmt)){
            $deleted=true;
          }
        }
      }
      if($deleted){
        unlink("Attachments/$catFile");
      }
      $mode="attachment";
    }

//************************************************************************************************************* */
//Attachment form
//************************************************************************************************************* */
    if($mode=="attachment"){
      $showList=false;
      include("$__includeDir/readPrograms.php");
      include("$__includeDir/readCourseData.php");
      echo "<div style='width: 800px;margin: auto;text-align: center;direction: rtl;'>";
      echo "<h3>مرفقات دورة " . $prgs[$CoursCrsId]['Name']."</h3>";
      echo "<form method='post' enctype='multipart/form-data'>";
      echo "<table width='100%' id='attachmentTable'>";
      $cellStyle="background-color:DarkTurquoise;padding: 10px;font-weight: bold;";
      echo "<tr><td style='width:300px;$cellStyle'>وصف المرفق: </td><td style='width:400px;$cellStyle'>إختيار الملف</td><td style='width:100px;$cellStyle'></td></tr>";
      echo "</tr><td><input type='text' name='fcatDescription[]' class='input-field'></td>";
      echo "<td><input name='fcatFile[]' type='file' class='input-field filBtn' accept='.jpg,.pdf'></td>";
      echo "<td><button type='button' class='delBtn' onclick='delAtt(this);'> إلغاء </button></td></tr>";
      echo "</table><br>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<button type='button' class='nflBtn' onclick='addAtt();'> إضافة مرفق </button> ";
      echo "<button type='submit' name='mode' class='attBtn' value='saveAttachments' > تحميل </button> ";
      echo "<button type='button' onclick='window.location=\"$fileName\";' class='okBtn'>إغلاق</button>";
      echo "</form>";
      echo "<br><br>";
      $q="SELECT catid,catDescription,catFile FROM courseAttachments WHERE catCourse=?";
      if($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $catid,$catDescription,$catFile)){
              //filter list form 
              echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث باسم المرفق..' title='بحث باسم المرفق..'>";
              echo "<table id='mainTable'>";
              echo "<tr class='header'>";
              echo "<th style='text-align: right;'>اسم المرفق</th><th style='width:250px;text-align: center;'></th></tr>";
              $rowNo=0;
              while(mysqli_stmt_fetch($stmt)){
                $rowNo++;
                echo "<tr><td style='text-align: right;'>$rowNo - $catDescription</td>";
                echo "<td style='text-align: right;'><form method='post'>";
                echo "<input type='hidden' name='CoursId' value='$CoursId'>";
                echo "<input type='hidden' name='catid' value='$catid'>";
                echo "<input type='hidden' name='returnValue' value='attachment'>";
                echo "<button type='submit' class='viewBtn' name='mode' value='viewAttachment'>عرض</button> ";
                echo "<button type='submit' class='delBtn' name='mode' value='deleteAttachmentConfirm'>الغاء</button> ";
                echo "</form></td></tr>";      
              }
              echo "</table>";
            }
          }
        }
      }
?>
<script>
function addAtt(){
  var table = document.getElementById("attachmentTable");
  var row = table.insertRow();
  var cell0 = row.insertCell(0);
  var cell1 = row.insertCell(1);
  var cell2 = row.insertCell(2);
  cell0.innerHTML = "<input type='text' name='fcatDescription[]' class='input-field'>"; 
  cell1.innerHTML = "<input name='fcatFile[]' type='file' class='input-field filBtn' accept='.jpg'>";
  cell2.innerHTML = "<button type='button' class='delBtn' onclick='delAtt(this);'> إلغاء </button>";
}
function delAtt(r) {
  var i = r.parentNode.parentNode.rowIndex;
  document.getElementById("attachmentTable").deleteRow(i);
} 
</script>
<?php
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
        $q = "INSERT INTO Courses(CoursCrsId,CoursType,CoursBulletin,CoursFromPln,CouursToPln,CoursYear,CoursArea,CoursSection,CoursLocation,CoursStatus,CoursDevidable) VALUES (?,?,?,?,?,?,?,?,?,?,?);";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if (mysqli_stmt_bind_param($stmt, "iiissiiiiii", $CoursCrsId, $CoursType, $CoursBulletin, $CoursFromPln, $CouursToPln, $CoursYear, $CoursArea, $CoursSection, $CoursLocation, $CoursStatus, $CoursDevidable)) {
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
    if ($mode == "nominees") {
      $showList = false;
      $nominees = array();
      $_SESSION['CourseId'] = array($CoursId, $mnuId);
      //$q = "select crscmpCompany,crscmpPlanned from courseCompanies where crscmpCourse=$CoursId";
      $q = "select concrsId,concrsCmp,concrsTrnCount,concrsCost,concrsCurrency,concrsTrnCerts from conCourses where concrsId = $CoursId";
      $r = mysqli_query($dbc, $q);
      if ($r) {
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
          $nominees[$row['concrsCmp']] = $row;
        }
      }
      echo "<br>";
      if ($errorMessage != "") {
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<div style='max-width:800px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>اﻷعداد المرشحة لدورة " . $prgs[$CoursCrsId]["Name"] . "<br>إخطار رقم : $CoursBulletin</h2><hr>\r\n";
      echo "<table  border ='$tableBorderSize'  width='100%'>";
      echo "<tr>";
      echo    "<td style='text-align: center;width: 600px;'> الشركة </td>";
      echo    "<td style='text-align: center;width: 100px;'> تكلفة المتدرب </td>";
      echo    "<td style='text-align: center;width: 100px;'> العملة </td>";
      echo    "<td style='text-align: center;width: 100px;'> اﻷعداد المرشحة </td>";
      echo    "<td style='text-align: center;width: 100px;'> إجمالي الشهادات </td>";
      echo    "<td style='text-align: center;width: 150px;'> &nbsp;</td>";
      echo "</tr>\r\n";
      $tot = 0;
      foreach ($nominees as $key => $value) {
        echo "<tr><td colspan='6'><form method='post'><table border = '$tableBorderSize' width='100%'>";
        echo "<tr><td width='600px' style='text-align:right;'>" . $comps[$key] . "</td>";
        echo "<td width='100px'>" . $value['concrsCost'] . "</td>";
        echo "<td width='100px'>" . $currency[$value['concrsCurrency']] . "</td>";
        echo "<td width='100px'>" . $value['concrsTrnCount'] . "</td>";
        echo "<td width='100px'>" . $value['concrsTrnCerts'] * $value['concrsTrnCount'] . "</td>";
        echo "<td>";
        if ($CoursStatus > $formCourseStatusLevel) {
          echo "&nbsp;";
        } else {
          echo "<button type='submit' class='delBtn' name='mode' value='delNominee'>الغاء</button>";
        }
        echo "</td>";
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<input type='hidden' name='concrsCmp' value='$key'>";
        echo "<input type='hidden' name='newCourse' value='$newCourse'>";
        echo "</table></form></td></tr>";
        $tot += $value['concrsTrnCount'];
      }
      echo "<tr><td colspan='4'><table border='0' width='100%'>";
      echo "<tr><td width='600px' style='text-align:right;'>اﻹجمـــــــالي</td>";
      echo "<td width='100px' style='text-align:center';>$tot</td>";
      echo "<td>&nbsp;</td>";
      echo "</table></td></tr>";

      echo "<tr><td colspan='6'>";
      if ($CoursStatus > $formCourseStatusLevel) {
        echo "&nbsp;";
      } else {
        // adding
        echo "<form method='post'><table width='100%'>";
        echo "<tr>";

        echo "<td width='800px'><select class='input-field' name='concrsCmp'><option value='0'>حدد الشركة</option>\r\n";
        foreach ($comps as $key => $value) {
          echo "<option value='$key'";
          if (isset($concrsCmp)) {
            if ($concrsCmp == $key) {
              echo " selected";
            }
          }
          echo ">$value</option>\r\n";
        }

        echo "</select></td>\r\n";
        if (!isset($concrsCost)) {
          $concrsCost = '';
        }
        echo "<td style='vertical-align: middle;' width='300px'><input class='input-field' type='text' placeholder='تكلفة المتدرب' name='concrsCost' value='$concrsCost' ></td>";
        // start curr
        if (!isset($concrsCurrency)) {
          $concrsCurrency = '';
        }
        echo "<td width='300px'><select class='input-field' name='concrsCurrency'><option value='-1'>حدد العملة</option>\r\n";
        foreach ($currency as $key => $value) {
          echo "<option value='$key'";
          if ($concrsCurrency == $key)
            echo "selected";
          echo ">$value</option>\r\n";
        }
        echo "</select></td>\r\n";
        if (!isset($concrsTrnCount)) {
          $concrsTrnCount = '';
        }
        if (!isset($concrsTrnCerts)) {
          $concrsTrnCerts = '';
        }

        echo "<td style='vertical-align: middle;' width='250px'><input class='input-field' type='text' placeholder='العدد الفعلي' name='concrsTrnCount' value='$concrsTrnCount'></td>";
        echo "<td style='vertical-align: middle;' width='270px'><input class='input-field' type='text' placeholder='عدد الشهادات' name='concrsTrnCerts' value='$concrsTrnCerts'></td>";


        echo "<td style='text-align:center;vertical-align: middle;'>";
        echo "<button type='submit' class='savBtn' name='mode' value='addNominee'> إضافة </button>\r\n";
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<input type='hidden' name='newCourse' value='$newCourse'>";
        echo "</td></tr></table></form>";
      }
      echo "</td></tr></table><br>\r\n";

      echo "<div class='frmButtons'><form method='post'>";
      if ($newCourse) {
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<input type='hidden' name='newCourse' value='$newCourse'>";
        echo "<button type='submit' class='addBtn' name='mode' value='lecs'> المحاضرين </button> ";
      }
      echo "<button type='button' class='addBtn' name='mode' onclick='window.location=\"conractedCoursesFU.php\"'> إغلاق </button> ";
      echo "</form></div>\r\n";
      echo "</div>";
    }

    //************************************************************************************************************* */
    // sec:delLecs delete lecture
    //************************************************************************************************************* */
    if ($mode == "delLecs") {
      printMode($mode, $showMode);
      if ($errorMessage == "") {
        $clhCrsId = $CoursId;
        $clhLecId = $LecId;

        $q = "delete from CourseLecHours where clhLecId=? and clhCrsId=? and clhPeriod=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if (mysqli_stmt_bind_param($stmt, "iii", $LecId, $CoursId, $clhPeriod)) {
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


    //************************************************************************************************************* */
    // sec:Save Lecs
    //************************************************************************************************************* */
    if ($mode == "savelecs") {
      if ($fclhLecId == "0") {
        $errorMessage .= "لا بد من اختيار المحاضر<br>";
      }
      if ($fclhHoursP == "" and $fclhHoursT == "") {
        $errorMessage .= "لا بد من ادخال عدد الساعات<br>";
      }
      if ($clhPeriod == "0" and $CoursDevidable == 1) {
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
          appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050101" . $__uid . date("YmdHis"));
        }
      }
      $mode = $fromCaller;
    }


    //********************************************************************************************************* */
    //sec:lecturers form
    //********************************************************************************************************* */
    if ($mode == "lecs") {
      $showList = false;
      echo "<div class=''>";
      if (!isset($clhPeriod)) {
        $clhPeriod = 0;
      }
      //$doesCourseHasLecs = courseHasLecs($dbc,$CoursId);
      $lecs = readCourseLecturers($dbc, $CoursId, $clhPeriod);
      $courseData = readCourseGuide($dbc, $CoursCrsId);
      $lecNames = readLecturerNames($dbc);
      if ($errorMessage != "") {
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<div style='max-width:1000px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>محاضري دورة : " . $courseData["name"] . "</h2>";
      //$periods = readPeriods($dbc, $CoursId);
      $periods = comparePeriodStatus($dbc, $CoursId, "<=", $formCourseStatusLevel);
      if ($CoursDevidable == 1) {
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
 if (isset($perms['Developer'])) {
            include('conractedDevInfo.php');
             echo "<br>Crs Stat: [$CoursStatus] - Crs Id: [$CoursId] prd Id: [$clhPeriod]- isDiv: [$boolarray[$dividable]]";
          }

        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<input type='hidden' name='mode' value=$mode>";
        echo "</form>";
      }
      $showLecForm = true;

      if ($clhPeriod == 0) {
        $showLecForm = false;
      }
      if ($CoursDevidable == 0) {
        $showLecForm = true;
      }
      if ($showLecForm) {
        echo "<table  width='100%'>";
        echo "<tr><td style='text-align: center;width: 50px;'> الملف </td>";
        echo "<td style='text-align: center;width: 250px;'> المحاضر </td>";  
        echo "<td style='text-align: center;width: 75px;'>نظري</td>";
        echo "<td style='text-align: center;width: 75px;'>عملي</td>";
        echo "<td style='text-align: center;width: 75px;'>الليالي</td>";
        echo "<td style='text-align: center;width: 75px;'>النسبة</td>";
        echo "<td style='text-align: center;width: 60px;'>من</td>";
        echo "<td style='text-align: center;width: 60px;'>الى</td>";
        echo "<td style='text-align: center;width: 60px;'>العدد</td>";
        echo "<td style='text-align: center;width: 75px;'>العودة</td>";
        echo "<td>&nbsp;</td></tr>\r\n";
        $totT = 0;
        $totP = 0;
        foreach ($lecs as $key => $value) {
          echo "<tr><td style='text-align:right;'>".$value['lecId']."</td>";
          echo "<td style='text-align: right;'>".$lecNames[$value['lecId']]."</td>";  
          echo "<td style='text-align: center;'>" . $value['thhrs'] . "</td>";
          echo "<td style='text-align: center;'>" . $value['prhrs'] . "</td>";
          echo "<td style='text-align: center;'>" . $value['Nights'] . "</td>";
          echo "<td style='text-align: center;'>" . $value['ratio'] . "%</td>";
          echo "<td style='text-align: center;'>" . $dists[$value['From']] . "</td>";
          echo "<td style='text-align: center;'>" . $dists[$value['To']] . "</td>";
          echo "<td style='text-align: center;'>" . $value['Days'] . " مرة</td>";
          $checked = "";
          if ($value['Return'] == 1) {
            $checked = " checked";
          }
          echo "<td style='text-align: center;width: 75px;'><input type='checkbox' $checked disabled></td>";
          echo "<td><form method='post'><button type='submit' class='delBtn' name='mode' value='delLecs'>الغاء</button>";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          echo "<input type='hidden' name='fromCaller' value='$mode'>";
          echo "<input type='hidden' name='CoursDevidable' value='$CoursDevidable'>";
          echo "<input type='hidden' name='clhPeriod' value='$clhPeriod'>";
          echo "<input type='hidden' name='CoursCrsId' value='$CoursCrsId'>";
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

        echo "<tr><td colspan='11'><form method='post' id='addLec'><table width='100%' border='1'>";
        echo "<tr>";
        echo "<td width='50'><input class='input-field' type='text' id='lecNo' onchange='selectLec();'></td>";
        echo "<td width='250px'><select class='input-field' name='fclhLecId' id='lecList'><option value='0'>حدد المحاضر</option>\r\n";  
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


        echo "<td width='60px'><select class='input-field' name='fclhDistrictFrom'><option value='0'>من</option>\r\n";
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

        echo "<td width='60px'><select class='input-field' name='fclhDistrictTo'><option value='0'>الى</option>\r\n";
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

        echo "<td style='vertical-align: middle;' width='60px'>";
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
        echo "<input type='hidden' name='fromCaller' value='$mode'>";
        echo "<input type='hidden' name='clhPeriod' value='$clhPeriod'>";
        echo "<input type='hidden' name='CoursDevidable' value='$CoursDevidable'>";
        echo "<input type='hidden' name='CoursCrsId' value='$CoursCrsId'>";
        echo "</td></tr></table></form>";

        echo "</td></tr></table>\r\n";
      }
      echo "<div class='frmButtons'><br><form method='post'>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<input type='hidden' name='mode' value='periods'>";
      echo "<button type='submit' class='addBtn'> العودة </button></div>\r\n ";
      echo "</div>";
      echo "</div>";
      ?>
      <script>
      function selectLec(){
        var fileNo=document.getElementById("lecNo").value;
        var lecSelect=document.getElementById("lecList");
        var lecCount=lecSelect.options.length;
        var found=0;
        var i;
        var lec;
        for(i=0;i<lecCount;i++){
          if(fileNo == lecSelect.options[i].value){
            lecSelect.selectedIndex=i;
            found=1;
            break;
          }
        }
        if(found == 0){
          alert("Not Found!...");
        }
      }
      </script>
      <?php
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
    // sec:conReportPeriods                                                             //
    //////////////////////////////////////////////////////////////////////////////////////
    if ($mode == 'conReportPeriods') {
      echo "<script> window.open('conReportPeriods.php?CoursId=$CoursId,crprId=$crprId');</script>";
      $mode = 'periods';
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
    // sec:new-edit-or-view-form
    //**************************************************************************************************************
    if ($mode == 'add' or $mode == 'edit' or $mode == 'view') {

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
      echo "<tr><td style='width: $labelWidth;'>مقسمة:</td><td colspan='2'>";
      echo "<input type='hidden' name='CoursDevidable' value=0>";
      echo "<input class='check-box' type='checkbox' value=1  id='CoursDevidable' name='CoursDevidable' $checked $disabled>";
      echo "</div></td></tr>";

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
    // sec:conReport main
    //**************************************************************************************************************
    if ($mode == 'conReport') {
      echo "<script> window.open('conReport.php?CoursId=$CoursId');</script>";
    }


    //************************************************************************************************************* */
    //sec:saveFinish Course
    //************************************************************************************************************* */
    if ($mode == "saveFinish") {
      $allClosed = allPeriodsEnded($dbc, $CoursId, $formCourseStatusLevel);
      $dividable = courseIsDevidable($dbc, $CoursId);
      if (!isset($CoursToAct) and $allClosed == true) {
        $errorMessage = "لابد من ادخال التاريخ...";
        $mode = "start";
      } else {
        if ($allClosed == false) {
          $q = "UPDATE coursPeriods set crprStatus=?,crprCloseDate=? where crprId=?";
          if ($stmt = mysqli_prepare($dbc, $q)) {
            if (mysqli_stmt_bind_param($stmt, "isi", $CourseFlag, $CoursToAct, $clhPeriod)) {
              if (mysqli_stmt_execute($stmt)) {
                //$hits++;
              } else {
                $miss++;
              }
            }
          } // end of prepare

        } else {
          $q = "UPDATE Courses set CoursStatus=?,CoursToAct=? where CoursId=?";
          if ($stmt = mysqli_prepare($dbc, $q)) {
            if (mysqli_stmt_bind_param($stmt, "isi", $CourseFlag, $CoursToAct, $CoursId)) {
              if (mysqli_stmt_execute($stmt)) {
                //$hits++;
              } else {
                $miss++;
              }
            }
          } // end of prepare

        }
      }
      $allClosed = allPeriodsEnded($dbc, $CoursId, $formCourseStatusLevel);
      if ($allClosed and !isset($CoursToAct)) {
        $mode = "finish";
      }
    }

    //************************************************************************************************************* */
    //sec:finish course
    //************************************************************************************************************* */
    if ($mode == "finish") {
      include("$__includeDir/readCourseData.php");
      if ($showMode) {
        showModeF($mode);
      }
      $showList = false;
      $courseGuide = readCourseGuide($dbc, $CoursCrsId);
      $dividable = courseIsDevidable($dbc, $CoursId);
      $allClosed = allPeriodsEnded($dbc, $CoursId, $formCourseStatusLevel);
      echo "<div style='text-align: center;direction: rtl;'>";
      if (!$dividable or $allClosed) {
        echo "إنهاء دورة " . $courseGuide['name'] . " منشور رقم: $CoursBulletin ...<br><br>";
      } else {
        echo "إنهاء فترة - لدورة  " . $courseGuide['name'] . " منشور رقم: $CoursBulletin ...<br><br>";
      }
      echo "<form method='post'>";
      echo "<table width='100%'>";
      if (!isset($CoursToAct)) {
        $CoursToAct = $CouursToPln;
      }
      echo "</div>";
      if (!$dividable or $allClosed == true) {
        echo "<tr><td style='width: $labelWidth;'>تاريخ نهاية الدورة:</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='date' name='CoursToAct'";
        if (isset($CoursToAct))
          echo " value='$CoursToAct'";
        echo ">";
        echo "<tr><td></td>";
      }
      echo "<td colspan ='2' style='text-align: center;'>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      $periods = comparePeriodStatus($dbc, $CoursId, "<=", $formCourseStatusLevel);
      $sortedPrd = rsrtPrd($periods, "Description");
      $listDisabled = "";
      if (count($periods) == 0) {
        $listDisabled = " disabled ";
      }

      echo "<select name='clhPeriod' style='width: 260px; height: 37px;'$listDisabled><option value='0'>حدد الفترة</option>";
      foreach ($sortedPrd as $key => $value) {
        echo "<option value='$key'>" . $value['Description'] . " (" . $value['From'] . " - " . $value['To'] . ")</option>";
      }
      echo "</select>";
      echo "</td></tr>";
      echo "</div></td></tr>";
      echo "</table>";
      echo "<br>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      $CourseFlag = $formCourseStatusLevel + 2;
      echo "<input type='hidden' name='CourseFlag' value='$CourseFlag'>";
      echo "<button type='submit' class='okBtn' name='mode' value='saveFinish'>حفظ</button> ";
      echo "<button type='submit' class='cnlBtn' name='mode' value=''>تراجع</button> ";
      echo "</form>";
      echo "</div>";
    }


    //************************************************************************************************************* */
    // save course start
    //************************************************************************************************************* */
    if ($mode == "saveStart") {

      if (!isset($CoursFromAct)) {
        $errorMessage = "لابد من ادخال التاريخ...";
        $mode = "start";
      } else {
        $q = "update Courses set CoursFromAct=?,CoursStatus=? WHERE CoursId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if (mysqli_stmt_bind_param($stmt, "sii", $CoursFromAct, $formCourseStatusLevel, $CoursId)) {
            if (mysqli_stmt_execute($stmt)) {
              $msg = "تم افتتاح الدورة";
            }
          }
          mysqli_stmt_close($stmt);
        }
      }
    }

    //************************************************************************************************************* */
    // Start Course
    //************************************************************************************************************* */
    if ($mode == "start") {
      $showList = false;
      include("$__includeDir/readCourseData.php");
      echo "<form method='post' style='max-width:$formWidth;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>إفتتاح دورة في خطة " . $user_year['Desc'] . "</h2>";
      if ($errorMessage != "") {
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<table width='100%'>";
      if (!isset($CoursFromAct)) {
        $CoursFromAct = $CoursFromPln;
      }
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>تاريخ بداية الدورة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='date' name='CoursFromAct'";
      if (isset($CoursFromAct))
        echo " value='$CoursFromAct'";
      echo ">";
      echo "</div></td></tr>";

      echo "</table>";
      echo "<br>";

      echo "<input type='hidden' name='CoursId' value='$CoursId'>";

      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='saveStart'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value=''>تراجع</button></div>";

      echo "</form>";
    }


    //*************************************************************************************** */
    // Remove Registration
    //*************************************************************************************** */
    //echo "mode is $mode";
    if ($mode == "remReg") {
      $showList = false;
      if ($errorMessage == "") {
        $clhCrsId = $CoursId;
        $q = "delete from coursetrainees where crstrncourse=? and crstrntrainee=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if (mysqli_stmt_bind_param($stmt, "ii", $CoursId, $TrnNo)) {
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
      $mode = "register";
    }


    //**************************************************************************************************************
    // sec:save Fees
    //**************************************************************************************************************

    if ($mode == "saveFees") {
      $costFees     = "costTrn$TrnNo";
      $crtCurrency  = "cryTrn$TrnNo";
      $crtCount     = "crtTrn$TrnNo";
      $trnCrsCompId = "cmpTrn$TrnNo";
      //echo $$trnCrsCompId;
      if ($errorMessage == "") {
        $concrsId = $CoursId;
        $q = "update coursetrainees set crstrncompany=?,crstrnFees=?,crstrnCurrency=?,crstrnCertCount=? where crstrntrainee = ? and crstrncourse = ?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if (mysqli_stmt_bind_param($stmt, "iiiiii",$$trnCrsCompId,$$costFees,$$crtCurrency,$$crtCount,$TrnNo,$CoursId)) {
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
      $mode = "register";
    }




    //************************************************************************************************************* */
    // registeration
    //************************************************************************************************************* */
    include("registration.php");

    //************************************************************************************************************* */
    // register
    //************************************************************************************************************* */
    if ($mode == "register") {
      //hide course list
      $showList = false;

      // echo "mode is $mode";
      //get course name
      $q = "SELECT CrsName FROM Courses inner join CoursesGuide on CoursCrsId= CrsId WHERE CoursId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $CoursId)) {
          if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_bind_result($stmt, $CrsName)) {
              mysqli_stmt_fetch($stmt);
            }
          }
        }
        mysqli_stmt_close($stmt);
      }
      //Nominated counts
      include("$__includeDir/readNominees.php");
      //read trainees
      $trn = array();
      $q = "SELECT crstrncompany,cmpName,TrnName,TrnNo,TrnIdNo,crstrnFees,crstrnCurrency,crstrnCertCount FROM ((Courses inner join coursetrainees on crstrncourse=CoursId) inner join Trainees on TrnNo = crstrntrainee) inner join companies on cmpId = crstrncompany WHERE CoursId=? order by TrnName";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $CoursId)) {
          if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_bind_result($stmt,$trnCompId,$cmpName, $TrnName, $TrnNo, $TrnIdNo,$TrnFees,$TrnCurrency,$TrnCertCount)) {
              $count = 1;
              while (mysqli_stmt_fetch($stmt)) {
                $trn[$count]['trnCompId'] = $trnCompId;
                $trn[$count]['cmpName'] = $cmpName;
                $trn[$count]['TrnName'] = $TrnName;
                $trn[$count]['TrnNo'] = $TrnNo;
                $trn[$count]['TrnIdNo'] = $TrnIdNo;
                $trn[$count]['TrnFees'] = $TrnFees;
                $trn[$count]['TrnCurrency'] = $TrnCurrency;
                $trn[$count]['TrnCertCount'] = $TrnCertCount;
                $found = false;
                foreach ($nom as $key => $value) {
                  if ($value['company'] == $cmpName) {
                    $nom[$key]['actual']++;
                    $found = true;
                  }
                }
                $n = count($nom);
                if (!$found) {
                  $nom[$n]["company"] = $cmpName;
                  $nom[$n]["actual"] = 1;
                  $nom[$n]["planned"] = 0;
                }
                $count++;
              }
            }
          }
        }
        mysqli_stmt_close($stmt);
      }

      // register form
      echo "<div style='text-align:center;width:75%;Margin: auto;direction: $__dir;'>";
      echo "<h3> تسجيل المتدربين في دورة: $CrsName</h3>";
      echo "<form method='post'>";
      echo "<input type='hidden' name='returnMode' value='register'>";
      echo "<input type='hidden' name='cancelLabel' value='تراجع'>";
      //echo "<input type='hidden' name='afterSaveMode' value='register'>";
      echo "<button type='submit' name='mode' value='registeration' class='addBtn'>تسجيل متدرب</button> ";
      echo "<button type='button' class='cnlBtn' onclick= 'window.location=\"$fileName\"'>تراجع</button>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "</form>";
      if (!isset($message)) {
        $message = "";
      }

      echo "<br><br><table style='width:100%;margin:auto;border-collapse:collapse;'>";
      echo "<tr style='border-bottom-style:solid;'><td></td><td>رقم</td><td>الاسم</td><td>الشركه</td><td>رقم هويه</td><td>مصروفات</td><td>عدد الشهادات</td><td>العمله</td></tr>";

      foreach ($trn as $key => $value) {
        $count = $key;
        $TrnName      = $value['TrnName'];
        $cmpName      = $value['cmpName'];
        $trnCompId     = $value['trnCompId'];
        $TrnNo        = $value['TrnNo'];
        $TrnIdNo      = $value['TrnIdNo'];
        $TrnFees      = $value['TrnFees'];
        $TrnCurrency  = $value['TrnCurrency'];
        $TrnCertCount = $value['TrnCertCount'];

        if (!isset($TrnFees)) {
          $TrnFees = 0;
        }
        if (!isset($TrnCurrency)) {
          $TrnCurrency = 0;
        }
        echo "<tr>";
        echo "<td><form name='frmCost$TrnNo' id='frmCost$TrnNo' method='post'>";
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<input type='hidden' name='TrnNo' value='$TrnNo'>";
        echo "<input type='hidden' name='mode' id='frmMode$TrnNo' value='saveFees'>";
        echo "</form></td>";

        echo "<td> $count </td><td> $TrnName </td>";
        // echo "<td> $cmpName </td>";
        // BEG cmp select
        echo "<td style='width:300px;' ><select  form='frmCost$TrnNo'  class='input-field' name='cmpTrn$TrnNo'><option value='-1'>الشركة</option>\r\n";
        foreach ($comps as $key => $value) {
          echo "<option value='$key'";
          if ($trnCompId == $key)
            echo "selected";
          echo ">$value</option>\r\n";
        }
        echo "</select></td>\r\n";
        // END cmp select
echo "<td>$TrnIdNo</td>";
        echo "<td style='width:70px;'><input  form='frmCost$TrnNo'  type='text' name='costTrn$TrnNo' id='cost$TrnNo' value='$TrnFees' class='costInput'></td>";
        echo "<td style='width:70px;'><input  form='frmCost$TrnNo'  type='text' name='crtTrn$TrnNo' id='crt$TrnNo' value='$TrnCertCount' class='costInput'></td>";
        //curr-section
        if (!isset($TrnCurrency)) {
          $TrnCurrency = '';
        }
        echo "<td style='width:125px;' ><select  form='frmCost$TrnNo'  class='input-field' name='cryTrn$TrnNo'><option value='-1'>العملة</option>\r\n";
        foreach ($currency as $key => $value) {
          echo "<option value='$key'";
          if ($TrnCurrency == $key)
            echo "selected";
          echo ">$value</option>\r\n";
        }
        echo "</select></td>\r\n";
        //curr
        echo "<td  style='width:150px;'><button form='frmCost$TrnNo' type='button' class='costBtn' style='width:150px;' onclick='submitForm(\"frmCost$TrnNo\",\"saveFees\",\"frmMode$TrnNo\");'>حفظ</button>";
        echo "<td style='width:100px;'><button form='frmCost$TrnNo' type='button' onclick='submitForm(\"frmCost$TrnNo\",\"remReg\",\"frmMode$TrnNo\");'  class='delBtn'>حذف</button></td>";
        echo "</tr>";
      }
      echo "</table>";
      echo "</div>";

?>
      <script>
        function submitForm(formID,sentMode,hiddenVar) {
          if(sentMode == "saveFees")
            {
            document.getElementById(hiddenVar).value=sentMode;
            //alert("sent mode is: "+ sentMode);
            document.getElementById(formID).submit();
            }
          else{
          var r = confirm('تاكيد حذف ');

          if (r == true) {
            document.getElementById(hiddenVar).value=sentMode;
            //alert("sent mode is: "+ sentMode);
            //alert("formModeId "+ hiddenVar);
            document.getElementById(formID).submit();
          }
          }
        }
      </script>

<?php

    }

    if($mode=="flipReg"){
      $q="SELECT `CoursRegClosed` FROM `Courses` WHERE `CoursId`=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $CoursId)) {
          if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_bind_result($stmt, $CoursRegClosed)) {
              if (mysqli_stmt_fetch($stmt)) {
                if($CoursRegClosed==1){
                  $newStatus=0;
                }else{
                  $newStatus=1;
                }
              }
            }
          }
        }
        mysqli_stmt_close($stmt);
      }
      $q="UPDATE `Courses` SET `CoursRegClosed`=? WHERE `CoursId`=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "ii", $newStatus,$CoursId)) {
            mysqli_stmt_execute($stmt);
          }
        mysqli_stmt_close($stmt);
      }
  
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
      $q = "SELECT CoursId,CoursBulletin,CrsName,CoursStatus,CoursDevidable,CoursRegClosed,CoursYear FROM Courses inner join CoursesGuide on CoursCrsId= CrsId WHERE CoursType=2 and (CoursYear=" . $user_year['Id'] . " OR (CoursYear=".$previousEduYear ." and CoursStatus <= ".$formCourseStatusLevel."))  order by CoursYear,CoursBulletin ";
      if (isset($perms['Developer'])) {
        echo $q;
      }
      $r = mysqli_query($dbc, $q);
      if ($r) {
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
          $rowNo++;
          $rowStyle = "";
          if ($CoursId == $HiliteCourseId) {
            $rowStyle = " style='background-color: DarkKhaki;'";
          }
          if ($CoursStatus == $formCourseStatusLevel - 1) {
            $startEnabled = $status[0];
            $viewEnabled = $status[1];
            $buttonStatus = $status[1];
            $regStatus = $status[1];
          } elseif ($CoursStatus == $formCourseStatusLevel) {
            $startEnabled = $status[1];
            $viewEnabled = $status[0];
            $buttonStatus = $status[0];
            $regStatus = $status[0];
          } else {
            $startEnabled = $status[1];
            $viewEnabled = $status[0];
            $buttonStatus = $status[1];
            $regStatus = $status[1];
          }

          if($CoursRegClosed==1){
            $regStatus = $status[0];
          }
          $dividable = courseIsDevidable($dbc, $CoursId);

          $message="";
          if($CoursYear == $previousEduYear){
            $message="<span style='color:red;'>[العام السابق]</span>";
          }

          echo "<tr $rowStyle><td>$CoursBulletin - $CrsName $message";
          if (isset($perms['Developer'])) {
            include('conractedDevInfo.php');
             echo "<br>Crs Stat: [$CoursStatus] - Crs Id: [$CoursId] - isDiv: [$boolarray[$dividable]] - Year:[$CoursYear]";
          }
          echo "</td>";
          echo "<td style='text-align:right';><form method='post'>";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          echo "<input type='hidden' name='CoursBulletin' value='$CoursBulletin'>";
          echo "<input type='hidden' name='CoursDevidable' value='$CoursDevidable'>";
          $allClosed = allPeriodsEnded($dbc, $CoursId, $formCourseStatusLevel);
          //echo "allClosed is :- ".$boolarray[$allClosed]."  ";
          if ($allClosed == false) {
            $endCourseCaption = "إنهاء فترة";
          } else {
            $endCourseCaption = "إنهاء الدورة";
          }
          if (isset($perms['Developer'])) {
            echo "<button type='submit' class='delBtn' name='mode' value='flipReg'>Flip Reg</button> ";  
          }
          echo "<button type='submit' class='delBtn' name='mode' value='finish'$buttonStatus>$endCourseCaption</button> ";
          //echo "<button type='submit' class='pwdBtn' name='mode' value='conReport' $buttonStatus >طباعة</button> ";
          echo "<button type='submit' class='pwdBtn' name='mode' value='conReport'>طباعة</button> ";
          echo "<button type='submit' class='attBtn' name='mode' value='attachment'$buttonStatus>مرفقات</button> ";
          echo "<button type='submit' class='genBtn' name='mode' value='lecs'$buttonStatus  >المحاضرين</button> ";
          echo "<button type='submit' class='startBtn' name='mode' value='start'$startEnabled>إفتتاح الدورة</button> ";
          echo "<button type='submit' class='startBtn' name='mode' value='register'$regStatus>التسجيل</button> ";
          //echo "<button type='submit' class='grpBtn' name='mode' value='nominees'>اﻷعداد</button> ";
          echo "<button type='submit' class='viewBtn' name='mode' value='view'>عرض</button> ";
          //echo "<button type='submit' class='delBtn' name='mode' value='deleteconfirm'$buttonStatus>الغاء</button> ";
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
