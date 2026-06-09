<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$__includeDir="../include";                //path to include directory
$__systemRoot="../";                        //path to system root
include($__systemRoot."functions.php");     //system Functions don't remove
include('functions.php');                   //uncomment this line if you have a local functions file
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
$formCourseStatusLevel=0;
$status=array(""," disabled style='background-color: lightgrey;'");
if(!isset($newCourse)){
  $newCourse=false;
}
if(!isset($mode)){
  $mode='';
}
if(!isset($errorMessage)){
  $errorMessage="";
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
    $yearStart=$user_year['Start'];
    $yearEnd=$user_year['End'];
    echo "<!DOCTYPE html><html><head>";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
    echo "<title>$pageTitle</title>";
    echo "<style>";
    echo "* { box-sizing: border-box;}";
    echo "#filterBox {background-image: url('images/searchicon.png');background-position: 10px 10px;background-repeat: no-repeat;width: 100%;font-size: 16px;padding: 12px 20px 12px 40px;border: 1px solid #ddd;margin-bottom: 12px;}";
    echo "#mainTable {border-collapse: collapse;width: 100%;border: 1px solid #ddd;font-size: 18px;}";
    echo "#mainTable th, #mainTable td {text-align: left;padding: 12px;}";
    echo "#mainTable tr {border-bottom: 1px solid #ddd;}";
    echo "#mainTable tr.header, #mainTable tr:hover {background-color: #f1f1f1;}";
    echo ".navBtn {background-color: dodgerblue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 90px;  opacity: 0.9;}";
    echo ".navBtn:hover {opacity: 1;}";
    echo ".addBtn {background-color: MidnightBlue ;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 90px;  opacity: 0.9;margin: auto;}";
    echo ".addBtn:hover {opacity: 1;}";
    echo ".edtBtn {background-color: teal;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 90px;  opacity: 0.9;}";
    echo ".edtBtn:hover {opacity: 1;}";
    echo ".pwdBtn {background-color: Blue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 90px;  opacity: 0.9;}";
    echo ".pwdBtn:hover {opacity: 1;}";
    echo ".rsltBtn {background-color: Blue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100x;  opacity: 0.9;}";
    echo ".rsltBtn:hover {opacity: 1;}";
    echo ".delBtn {background-color: darkred;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 90px;  opacity: 0.9;}";
    echo ".delBtn:hover {opacity: 1;}";
    echo ".savBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 90px;  opacity: 0.9;}";
    echo ".savBtn:hover {opacity: 1;}";
    echo ".genBtn {background-color: DarkMagenta ;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 90px;  opacity: 0.9;}";
    echo ".genBtn:hover {opacity: 1;}";
    echo ".viewBtn {background-color: Chocolate;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 90px;  opacity: 0.9;}";
    echo ".viewBtn:hover {opacity: 1;}";
    echo ".cnlBtn {background-color: red;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 90px;  opacity: 0.9;}";
    echo ".cnlBtn:hover {opacity: 1;}";
    echo ".okBtn {background-color: indigo;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 90px;  opacity: 0.9;}";
    echo ".okBtn:hover {opacity: 1;}";
    echo ".grpBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 90px;  opacity: 0.9;}";
    echo ".grpBtn:hover {opacity: 1;}";
    echo ".arrowBtn {background-color: DarkOrchid;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 50px;  opacity: 0.9;}";
    echo ".arrowBtn:hover {opacity: 1;}";
    echo ".input-container {display: -ms-flexbox; /* IE10 */  display: flex;  width: 100%;  margin-bottom: 15px;}";
    echo ".input-field { width: 100%;  padding: 10px;  outline: none;}";
    echo ".input-field:focus {border: 2px solid dodgerblue;}";
    echo ".error{color:red; font-weight:bold;text-align:center;direction:rtl;}";
    echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";    
    echo "</style>";
    echo "</head>";
	  echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>$pageTitle</h2>";

//**************************************************************************************************************
//load ref data
//**************************************************************************************************************
    include("$__includeDir/readDistricts.php");
    include("$__includeDir/readSections.php");
    include("$__includeDir/readPrograms.php");
    $comps=readCompanies($dbc,true);
    $intSup=array();
    $q="select staffid,staffname from staff where (staffcontract=1 or staffcontract=4 or staffcontract=2) and staffislec=1 and staffdeleted=0 order by staffname";
    $r=mysqli_query($dbc,$q);
    if($r){
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $intSup[$row['staffid']]=$row['staffname'];
      }
    }
    
//**************************************************************************************************************
// Data Validation
//**************************************************************************************************************
    if($mode == 'saveadd' or $mode == 'saveedit' or $mode == "addNominee"){
      //validation for new or old records
      if($mode == "addNominee"){
        if($crscmpCompany=="0"){
          $errorMessage.="لا بد من اختيار الشركة<br>";
        }
        if($crscmpPlanned==""){
          $errorMessage.="لا بد من ادخال عدد المتدربين<br>";
        }  
        $q="select crscmpCompany,crscmpPlanned from courseCompanies where crscmpCourse=$CoursId";
        $r=mysqli_query($dbc,$q);
        if($r){
          while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            if($row['crscmpCompany']==$crscmpCompany){
              $errorMessage="لقد تمت إضافة ". $comps[$row['crscmpCompany']]." مسبقا بواقع ". $row['crscmpPlanned']." مرشحين";
            }
          }
        }
      }else{
        if($CoursArea=="0"){
          $errorMessage.="لا بد من ادخال المنطقة<br>";
        }
        if($CoursSection=="0"){
          $errorMessage.="لا بد من ادخال القسم<br>";
        }
        if($CoursCrsId=="0"){
          $errorMessage.="لا بد من ادخال الدورة<br>";
        }
        if($CoursFromPln==""){
          $errorMessage.="لا بد من ادخال تاريخ البداية<br>";
        }
        if($CoursFromPln < $yearStart){
          $errorMessage.="تاريخ البداية قبل بداية العام التدريبي<br>";
        }
        if($CoursFromPln > $yearEnd){
          $errorMessage.="تاريخ البداية بعد نهاية العام التدريبي<br>";
        }
        if($CouursToPln==""){
          $errorMessage.="لا بد من ادخال تاريخ النهاية<br>";
        }
        if($CouursToPln < $yearStart){
          $errorMessage.="تاريخ النهاية قبل بداية العام التدريبي<br>";
        }
        if($CouursToPln > $yearEnd){
          $errorMessage.="تاريخ النهاية بعد نهاية العام التدريبي<br>";
        }
        if($CoursGenRept==""){
          $errorMessage.="لا بد من ادخال الترتيب العام<br>";
        }
        if($CoursAreaRept==""){
          $errorMessage.="لا بد من ادخال ترتيب المنطقة<br>";
        }
        if($CoursLocation=="0"){
          $errorMessage.="لا بد من ادخال مكان الانعقاد<br>";
        }
        if($CoursSupervisorInt=="0"){
          $errorMessage.="لا بد من ادخال المشرف<br>";
        }
        if($CoursSupervisorExt=="0"){
          $errorMessage.="لا بد من ادخال جهة الاشراف<br>";
        }
        if($CoursBulletin==""){
          $errorMessage.="لا بد من ادخال رقم المنشور<br>";
        }  
      }
    }


//**************************************************************************************************************
// Add nominee
//**************************************************************************************************************
    if($mode == "addNominee"){
      if($errorMessage==""){
        $crscmpCourse=$CoursId;
        $q="INSERT INTO courseCompanies(crscmpCompany,crscmpCourse,crscmpPlanned) VALUES (?,?,?)";
        if($stmt = mysqli_prepare($dbc, $q)){
          if(mysqli_stmt_bind_param($stmt, "iii", $crscmpCompany,$crscmpCourse,$crscmpPlanned)){
            if(!mysqli_stmt_execute($stmt)){
              $errorMessage.= "Error saving data [050103".$__uid.date("YmdHis")."]!...<br>";
              appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"050103".$__uid.date("YmdHis"));
            }  
          }else{
            $errorMessage.= "Error saving data [050102]".$__uid.date("YmdHis")."]!...<br>";
            appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"050102".$__uid.date("YmdHis"));
          }
          mysqli_stmt_close($stmt);
        }else{
          $errorMessage.= "Error saving data [050101]".$__uid.date("YmdHis")."]!...<br>";
          appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"050101".$__uid.date("YmdHis"));
        }
      }
      $mode="nominees";
    }


//**************************************************************************************************************
// Delete nominee
//**************************************************************************************************************
    if($mode == "delNominee"){
      if($errorMessage==""){
        $crscmpCourse=$CoursId;
        $q="delete from courseCompanies where crscmpCompany=? and crscmpCourse=?";
        if($stmt = mysqli_prepare($dbc, $q)){
          if(mysqli_stmt_bind_param($stmt, "ii", $crscmpCompany,$crscmpCourse)){
            if(!mysqli_stmt_execute($stmt)){
              $errorMessage.= "Error saving data [060103".$__uid.date("YmdHis")."]!...<br>";
              appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"060103".$__uid.date("YmdHis"));
            }  
          }else{
            $errorMessage.= "Error saving data [060102]".$__uid.date("YmdHis")."]!...<br>";
            appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"060102".$__uid.date("YmdHis"));
          }
          mysqli_stmt_close($stmt);
        }else{
          $errorMessage.= "Error saving data [060101]".$__uid.date("YmdHis")."]!...<br>";
          appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"060101".$__uid.date("YmdHis"));
        }
      }
      $mode="nominees";
    }


//**************************************************************************************************************
// Read course data
//**************************************************************************************************************
    if($mode=='view' or $mode=='edit' or $mode == 'deleteconfirm' or $mode=='nominees' or $mode=='lecs'){
      $q="select CoursCrsId,CoursType,CoursBulletin,CoursFromPln,CouursToPln,CoursSupervisorInt,CoursSupervisorExt,CoursYear,CoursArea,CoursSection,CoursGenRept,CoursAreaRept,CoursLocation,CoursStatus,CoursNotesLink from Courses where CoursId=?";
      if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $CoursCrsId,$CoursType,$CoursBulletin,$CoursFromPln,$CouursToPln,$CoursSupervisorInt,$CoursSupervisorExt,$CoursYear,$CoursArea,$CoursSection,$CoursGenRept,$CoursAreaRept,$CoursLocation,$CoursStatus,$CoursNotesLink)){
              if(!mysqli_stmt_fetch($stmt)){
                $errorMessage.= "<span dir='ltr'>Error reading data [030105".$__uid.date("YmdHis")."]!...</span><br>";
                appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030105".$__uid.date("YmdHis"));        
              }
            }else{
              $errorMessage.= "Error reading data [030104".$__uid.date("YmdHis")."]!...<br>";
              appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030104".$__uid.date("YmdHis"));      
            }
          }else{
            $errorMessage.= "Error saving reading [030103".$__uid.date("YmdHis")."]!...<br>";
            appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030103".$__uid.date("YmdHis"));    
          }
        }else{
          $errorMessage.= "Error saving reading [030102".$__uid.date("YmdHis")."]!...<br>";
          appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030102".$__uid.date("YmdHis"));
        }
        mysqli_stmt_close($stmt);
      }else{
        $errorMessage.= "Error saving reading [030101".$__uid.date("YmdHis")."]!...<br>";
        appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030101".$__uid.date("YmdHis"));
      }      
    }

//**************************************************************************************************************
// Save New Course data
//**************************************************************************************************************
    if($mode == 'saveadd'){
      if($errorMessage == ""){
        $CoursType=1;
        $CoursYear=$user_year['Id'];
        $CoursStatus=$formCourseStatusLevel;
        $q="INSERT INTO Courses(CoursCrsId,CoursType,CoursBulletin,CoursFromPln,CouursToPln,CoursSupervisorInt,CoursSupervisorExt,CoursYear,CoursArea,CoursSection,CoursGenRept,CoursAreaRept,CoursLocation,CoursStatus,CoursNotesLink) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
        if($stmt = mysqli_prepare($dbc, $q)){
          if(mysqli_stmt_bind_param($stmt, "iiissiiiiiiiiis", $CoursCrsId,$CoursType,$CoursBulletin,$CoursFromPln,$CouursToPln,$CoursSupervisorInt,$CoursSupervisorExt,$CoursYear,$CoursArea,$CoursSection,$CoursGenRept,$CoursAreaRept,$CoursLocation,$CoursStatus,$CoursNotesLink)){
            if(mysqli_stmt_execute($stmt)){
              $CoursId=mysqli_insert_id($dbc);
            }else{
              $errorMessage.= "Error saving data [010103".$__uid.date("YmdHis")."]!...<br>";
              appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"010103".$__uid.date("YmdHis"));
            }  
          }else{
            $errorMessage.= "Error saving data [010102]".$__uid.date("YmdHis")."]!...<br>";
            appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"010102".$__uid.date("YmdHis"));
          }
          mysqli_stmt_close($stmt);
        }else{
          $errorMessage.= "Error saving data [010101]".$__uid.date("YmdHis")."]!...<br>";
          appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"010101".$__uid.date("YmdHis"));
        }
        $mode="nominees";
      }else{
        $mode="add";
      }
      //$errorMessage.=mysqli_error($dbc);
      //echo "$CoursCrsId<br>$CoursType<br>$CoursBulletin<br>$CoursFromPln<br>$CouursToPln<br>$CoursSupervisorInt<br>$CoursSupervisorExt<br>$CoursYear<br>$CoursArea<br>$CoursSection<br>$CoursGenRept<br>$CoursAreaRept<br>$CoursLocation<br>$CoursStatus<br>";
    }

//**************************************************************************************************************
// Save edited Course data
//**************************************************************************************************************
    if($mode == 'saveedit'){
      //save new
      if($errorMessage == ""){
        //save master
        $q="update Courses set CoursCrsId=?,CoursBulletin=?,CoursFromPln=?,CouursToPln=?,CoursSupervisorInt=?,CoursSupervisorExt=?,CoursArea=?,CoursSection=?,CoursGenRept=?,CoursAreaRept=?,CoursLocation=?,CoursNotesLink=? where CoursId=?;";
        if($stmt = mysqli_prepare($dbc, $q)){
          if(mysqli_stmt_bind_param($stmt, "iissiiiiiiisi", $CoursCrsId,$CoursBulletin,$CoursFromPln,$CouursToPln,$CoursSupervisorInt,$CoursSupervisorExt,$CoursArea,$CoursSection,$CoursGenRept,$CoursAreaRept,$CoursLocation,$CoursNotesLink,$CoursId)){
            if(!mysqli_stmt_execute($stmt)){
              $errorMessage.= "Error saving data [020103".$__uid.date("YmdHis")."]!...<br>";
              appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"020103".$__uid.date("YmdHis"));
            }  
          }else{
            $errorMessage.= "Error saving data [020102]".$__uid.date("YmdHis")."]!...<br>";
            appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"020102".$__uid.date("YmdHis"));
          }
          mysqli_stmt_close($stmt);
        }else{
          $errorMessage.= "Error saving data [020101]".$__uid.date("YmdHis")."]!...<br>";
          appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"020101".$__uid.date("YmdHis"));
        }
      }else{
        $mode='edit';
      }
    }

//**************************************************************************************************************
// Delete Course
//**************************************************************************************************************
    if($mode=="delete"){
      $q="delete from Courses where CoursId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
          if(!mysqli_stmt_execute($stmt)){
            $errorMessage.= "Error saving data [040103".$__uid.date("YmdHis")."]!...<br>";
            appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"040103".$__uid.date("YmdHis"));
          }
          mysqli_stmt_close($stmt);  
        }else{
          $errorMessage.= "Error saving data [040102]".$__uid.date("YmdHis")."]!...<br>";
          appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"040102".$__uid.date("YmdHis"));
        }
      }else{
        $errorMessage.= "Error saving data [040101]".$__uid.date("YmdHis")."]!...<br>";
        appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"040101".$__uid.date("YmdHis"));
      }
    }

//**************************************************************************************************************
// New, Edit or View form 
//**************************************************************************************************************
    if($mode == 'add' or $mode == 'edit' or $mode=='view'){
      $showList=false;
      if(isset($CoursId)){
        $_SESSION['CourseId']=array($CoursId,$mnuId);
      }
      if(!isset($CoursBulletin)){
        // get new manshour no
        $q="select ifnull(max(CoursBulletin),0)+1 as newManshour from Courses where CoursYear=".$user_year['Id']. " and CoursType=1";
        $r=mysqli_query($dbc,$q);
        if($r){
          if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            $CoursBulletin=$row['newManshour'];
          }
        }
      }
      if($mode == 'view'){
        $disabled=" disabled";
      }else{
        $disabled="";
      }
      echo "<form method='post' style='max-width:$formWidth;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>إضافة دورة لخطة ".$user_year['Desc']."</h2>";
      if($errorMessage != ""){
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<table width='100%'>";
      echo "<tr><td style='width: $labelWidth;'>المنطقة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='CoursArea'$disabled><option value='0'>منطقة الانعقاد</option>";
      foreach($dists as $key => $value){
        echo "<option value='$key'";
        if(isset($CoursArea)){
          if($CoursArea == $key){
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
      foreach($secs as $key => $value){
        echo "<option value='$key'";
        if(isset($CoursSection)){
          if($CoursSection == $key){
            echo " selected";
          }
        }
        echo ">$value</option>\r\n";
      }
      echo "</select>\r\n";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>البرنامج:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='CoursCrsId' id='CoursCrsId'$disabled>\r\n<option value='0'>البرنامج</option>\r\n";
      foreach($prgs as $key => $value){
        if($value['Program'] == $CoursSection){
          echo "<option value='$key'";
          if(isset($CoursCrsId)){
            if($CoursCrsId == $key){
              echo " selected";
            }
          }
          echo ">".$value['Code']." - ".$value['Name']."</option>\r\n";  
        }
      }
      echo "</select>\r\n";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>تاريخ بداية الدورة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='date' name='CoursFromPln'$disabled";
      if(isset($CoursFromPln))
        echo " value='$CoursFromPln'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>تاريخ نهاية الدورة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='date' name='CouursToPln'$disabled";
      if(isset($CouursToPln))
        echo " value='$CouursToPln'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>رقم المنشور:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='رقم المنشور' name='CoursBulletin'$disabled";
      if(isset($CoursBulletin))
        echo " value='$CoursBulletin'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>مسلسل تكرار الدورة(عام):</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='مسلسل تكرار الدورة(عام)' name='CoursGenRept'$disabled";
      if(isset($CoursGenRept))
        echo " value='$CoursGenRept'";
      echo ">";
      echo "</div></td></tr>";

      echo "<tr><td style='width: $labelWidth;'>مسلسل تكرار الدورة(للمنطقة):</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='مسلسل تكرار الدورة(للمنطقة)' name='CoursAreaRept'$disabled";
      if(isset($CoursAreaRept))
        echo " value='$CoursAreaRept'";
      echo ">";
      echo "</div></td></tr>";

      echo "<tr><td style='width: $labelWidth;'>مكان الانعقاد:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='CoursLocation'$disabled><option value='0'>مكان الانعقاد</option>\r\n";
      foreach($comps as $key => $value){
        echo "<option value='$key'";
        if(isset($CoursLocation)){
          if($CoursLocation == $key){
            echo " selected";
          }
        }
        echo ">$value</option>";
      }
      echo "</select>";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>المشرف الداخلي:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='CoursSupervisorInt'$disabled><option value='0'>المشرف الداخلي</option>\r\n";
      foreach($intSup as $key => $value){
        echo "<option value='$key'";
        if(isset($CoursSupervisorInt)){
          if($CoursSupervisorInt == $key){
            echo " selected";
          }
        }
        echo ">$value</option>";
      }
      echo "</select>";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>جهة الاشراف:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='CoursSupervisorExt'$disabled><option value='0'>جهة الاشراف</option>\r\n";
      foreach($comps as $key => $value){
        echo "<option value='$key'";
        if(isset($CoursSupervisorExt)){
          if($CoursSupervisorExt == $key){
            echo " selected";
          }
        }
        echo ">$value</option>";
      }
      echo "</select>";
      echo "</div></td></tr>";

      echo "<tr><td style='width: $labelWidth;'>وصلة المادة العلمية:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='وصلة المادة العلمية' name='CoursNotesLink'$disabled";
      if(isset($CoursNotesLink))
        echo " value='$CoursNotesLink'";
      echo ">";
      echo "</div></td></tr>";
      
      echo "</table>";
      echo "<br>";
      if($mode=='add'){
        $newCourse=true;
        echo "<input type='hidden' name='newCourse' value='$newCourse'>";
      }
      if($mode=='edit'){
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      }
      if($mode=='view'){
        echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode' value=''>موافق</button></div>";
      }else{
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='save$mode'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value=''>تراجع</button></div>";
      }
      echo "</form>";

      echo "<script>
      var prgsId = [];\r\n
      var prgsName = [];\r\n
      var prgsPrg = [];\r\n
      ";
      foreach($prgs as $key => $value){
        echo "prgsId.push('$key');\r\n";
        echo "prgsName.push('".$value['Code'] . " - " . $value['Name']."');\r\n";
        echo "prgsPrg.push('".$value['Program']."');\r\n";
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
      </script>";
    }

//**************************************************************************************************************
// Delete Confirmation
//**************************************************************************************************************    
    if($mode=='deleteconfirm'){
      $_SESSION['CourseId']=array($CoursId,$mnuId);
      $showList=false;
      echo "<form method='post' style='max-width:500px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>الغاء</h2>";
      echo "<div style='text-align: center;direction:$__dir;'>سيتم الغاء دورة ".$prgs[$CoursCrsId]["Name"]." منشور رقم  $CoursBulletin هل انت متأكد?...</div><br>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='delete'>نعم</button> <button type='submit' class='cnlBtn' name='mode' value=''>لا</button></div>";
      echo "</form>";
    }

//**************************************************************************************************************
// Nominees
//**************************************************************************************************************
    if($mode == "nominees"){
      $_SESSION['CourseId']=array($CoursId,$mnuId);;
      $showList=false;

      $nominees=array();
      $q="select crscmpCompany,crscmpPlanned from courseCompanies where crscmpCourse=$CoursId";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          $nominees[$row['crscmpCompany']]=$row['crscmpPlanned'];
        }
      }
      if($errorMessage != ""){
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<div style='max-width:800px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>اﻷعداد المرشحة لدورة ".$prgs[$CoursCrsId]["Name"]."<br>منشور رقم : $CoursBulletin</h2><hr>\r\n";
      echo "<table width='100%'>";
      echo "<tr><td style='text-align: center;width: 600px;'> الشركة </td><td style='text-align: center;width: 100px;'> اﻷعداد المرشحة </td><td>&nbsp;</td></tr>\r\n";
      $tot=0;
      foreach($nominees as $key => $value){
        echo "<tr><td colspan='3'><form method='post'><table width='100%'>";
        echo "<tr><td width='600px' style='text-align:right;'>";
        if(array_key_exists($key,$comps)){
          echo $comps[$key];
        }else{
          echo "الشركة [$key] غير مساهمة";
        }
        echo "</td>";
        echo "<td width='100px'>$value</td>";
        echo "<td>";
        if($CoursStatus > $formCourseStatusLevel){
          echo "&nbsp;";
        }else{
          echo "<button type='submit' class='delBtn' name='mode' value='delNominee'>الغاء</button>";
        }
        echo "</td>";
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<input type='hidden' name='newCourse' value='$newCourse'>";
        echo "<input type='hidden' name='crscmpCompany' value='$key'>";
        echo "</table></form></td></tr>";
        $tot+=$value;
      }
      echo "<tr><td colspan='3'><table width='100%'>";
      echo "<tr><td width='600px' style='text-align:right;'>اﻹجمـــــــالي</td>";
      echo "<td width='100px'>$tot</td>";
      echo "<td>&nbsp;</td>";
      echo "</table></td></tr>";

      echo "<tr><td colspan='3'>";
      if($CoursStatus > $formCourseStatusLevel){
        echo "&nbsp;";
      }else{
        echo "<form method='post'><table width='100%'>";
        echo "<tr><td width='600px'><select class='input-field' name='crscmpCompany'><option value='0'>حدد الشركة</option>\r\n";
        foreach($comps as $key => $value){
          echo "<option value='$key'";
          if(isset($crscmpCompany)){
            if($crscmpCompany == $key){
              echo " selected";
            }
          }
          echo ">$value</option>\r\n";
        }
        echo "</select></td>\r\n";
        echo "<td style='vertical-align: middle;' width='100px'>";
        echo "<input class='input-field' type='text' placeholder='العدد' name='crscmpPlanned'";
        if(isset($crscmpPlanned))
          echo " value='$crscmpPlanned'";
        echo "></td>\r\n";
        echo "<td style='text-align:center;vertical-align: middle;'>";
        echo "<button type='submit' class='savBtn' name='mode' value='addNominee'> إضافة </button>\r\n";
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<input type='hidden' name='newCourse' value='$newCourse'>";
        echo "</td></tr></table></form>";
      }
      echo "</td></tr></table><br>\r\n";

      echo "<div class='frmButtons'><form method='post'>";
      if($newCourse){
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<input type='hidden' name='newCourse' value='$newCourse'>";
        echo "<button type='submit' class='addBtn' name='mode' value='lecs'> المحاضرين </button> ";  
      }
      echo "<button type='button' class='addBtn' name='mode' onclick='window.location=\"planned.php\"'> إغلاق </button> ";
      echo "</form></div>\r\n";
      echo "</div>";
    }

//**************************************************************************************************************
// Save Lecs
//**************************************************************************************************************
    if($mode=="savelecs"){
      $q="delete from CoursLecs where CrslecCrsId=?";
      if($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
          if(!mysqli_stmt_execute($stmt)){
            $errorMessage.= "Error saving data [070103".$__uid.date("YmdHis")."]!...<br>";
            appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"070103".$__uid.date("YmdHis"));
          }  
        }else{
          $errorMessage.= "Error saving data [070102]".$__uid.date("YmdHis")."]!...<br>";
          appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"070102".$__uid.date("YmdHis"));
        }
        mysqli_stmt_close($stmt);
      }else{
        $errorMessage.= "Error saving data [070101]".$__uid.date("YmdHis")."]!...<br>";
        appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"070101".$__uid.date("YmdHis"));
      }
      $q="insert into CoursLecs(CrslecCrsId,CrslecPrgId,CrslecTpcId,CrslecLecId) values(?,?,?,?)";
      if($stmt = mysqli_prepare($dbc, $q)){
        for($i=0;$i<count($CrslecLecId);$i++){
          if($CrslecLecId[$i]!=0){
            if(mysqli_stmt_bind_param($stmt, "iiii", $CoursId,$CoursCrsId,$CrslecTpcId[$i],$CrslecLecId[$i])){
              if(!mysqli_stmt_execute($stmt)){
                $errorMessage.= "Error saving data [080103".$__uid.date("YmdHis")."]!...<br>";
                appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"080103".$__uid.date("YmdHis"));
              }else{
                if(isset($perms['Developer'])){
                  echo "saved $CoursId,$CoursCrsId,$CrslecTpcId[$i],$CrslecLecId[$i] <br>";
                }
              }
            }else{
              $errorMessage.= "Error saving data [080102]".$__uid.date("YmdHis")."]!...<br>";
              appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"080102".$__uid.date("YmdHis"));
            }
          }
        }
        mysqli_stmt_close($stmt);
      }else{
        $errorMessage.= "Error saving data [080101]".$__uid.date("YmdHis")."]!...<br>";
        appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"080101".$__uid.date("YmdHis"));
      }
    }

//**************************************************************************************************************
// Lecturers
//**************************************************************************************************************
    if($mode=='lecs'){
      $_SESSION['CourseId']=array($CoursId,$mnuId);;
      $showList=false;
      $topics=array();
      $toplec=array();
      if($CoursStatus > $formCourseStatusLevel){
        $formStatus=$status[1];
      }else{
        $formStatus=$status[0];
      }
      $q="SELECT tpcId,tpcDesc,CrslecLecId FROM CourseTopics INNER JOIN Courses ON CourseTopics.tpccourse = Courses.CoursCrsId LEFT JOIN CoursLecs ON Courses.CoursId = CoursLecs.CrslecCrsId WHERE Courses.CoursId=$CoursId ORDER BY tpcId";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          $topics[$row['tpcId']]=$row['tpcDesc'];
          $toplec[$row['tpcId']]=$row['CrslecLecId'];
        }
      }
      $lecs=array();
      $q="select staffid,staffname from staff where staffislec=1 and staffdeleted=0 order by staffname";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          $lecs[$row['staffid']]=$row['staffname'];
        }
      }
      if($errorMessage != ""){
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<div style='max-width:800px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>المحاضرين المرشحين لدورة ".$prgs[$CoursCrsId]["Name"]."<br>منشور رقم : $CoursBulletin</h2><hr>\r\n";
      echo "<form method='post'><table width='100%'>";
      echo "<tr><td style='text-align: center;width: 400px;'> الموضوع </td><td style='text-align: center;width: 400px;'> المحاضر </td></tr>\r\n";
      foreach($topics as $key => $value){
        echo "<tr><td>$value</td><td><input type='hidden' name='CrslecTpcId[]' value='$key'>";
        if($CoursStatus > $formCourseStatusLevel){
          echo $lecs[$toplec[$key]];
        }else{
          echo "<select class='input-field' name='CrslecLecId[]'><option value='0'>أدخل المحاضر</option>";
          foreach($lecs as $keyi => $valuei){
            echo "<option value='$keyi'";
            if($toplec[$key] == $keyi) echo " selected";
              echo ">$valuei</option>";
          }
          echo "</select>";  
        }
        echo "</td></tr>";
      }
      echo "</table>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<input type='hidden' name='CoursCrsId' value='$CoursCrsId'>";
      if($CoursStatus > $formCourseStatusLevel){
        echo "<div class='frmButtons'><br><button type='button' class='cnlBtn' onclick=window.location=\"$fileName\";>إغلاق</button></div>";
      }else{
        echo "<div class='frmButtons'><br><button type='submit' class='savBtn' name='mode' value='savelecs'>حفظ</button> <button type='button' class='cnlBtn' onclick=window.location=\"$fileName\";>تراجع</button></div>";
      }
      echo "</form>";
      echo "</div>";
    }

//**************************************************************************************************************
// Result Letter
//**************************************************************************************************************
  if($mode=='resultLetter'){
    $printEnabled=0;
    echo "<script> window.open('resultLetter.php?CoursId=$CoursId');</script>";
  }

//**************************************************************************************************************
// Cover Letter
//**************************************************************************************************************
    if($mode=='letter'){
      echo "<script> window.open('coverLetter.php?CoursId=$CoursId');</script>";
    }

//**************************************************************************************************************
// Manshour
//**************************************************************************************************************
    if($mode=='manshour'){
      echo "<script> window.open('manshour.php?CoursId=$CoursId');</script>";
    }

//**************************************************************************************************************
// Time table
//**************************************************************************************************************
if($mode=='table'){
  echo "<script> window.open('table.php?CoursId=$CoursId');</script>";
}

//**************************************************************************************************************
// QR Code
//**************************************************************************************************************
if($mode=='qr'){
  echo "<script> window.open('qrSheet.php?CoursId=$CoursId');</script>";
}

//**************************************************************************************************************
// Course list
//**************************************************************************************************************
    if($showList){
      $newCourse=false;
      if($errorMessage != ""){
        echo "<div class='error'>$errorMessage<br><br></div>";
      }
      $HiliteCourseId="";
      if(isset($CoursId)){
        $HiliteCourseId=$CoursId;
      }elseif(isset($_SESSION['CourseId'])){
        $sessionCourseId=$_SESSION['CourseId'];
        if($sessionCourseId[1] == $mnuId){
          $HiliteCourseId=$sessionCourseId[0];
        }
      }
      $q="SELECT CoursId,CoursBulletin,CrsName,CoursStatus FROM Courses inner join CoursesGuide on CoursCrsId= CrsId WHERE CoursType=1 and CoursYear=".$user_year['Id']." order by CoursStatus,CoursBulletin";
      $r=mysqli_query($dbc,$q);
      if($r){
        //new form
        if(isset($perms['plannedAddNew'])){
          echo "<div style='width: 110px; margin: auto;'><form method='post'>";
          echo "<input type='hidden' name='mode' value='add'>";
          echo "<button type='submit' class='addBtn'>جديد</button>";
          echo "</form></div>";  
        }
        echo "<br>";
        //filter list form 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table id='mainTable'>";
        echo "<tr class='header'>";
        echo "<th>المنشور - اسم الدورة</th><th style='width:1000px;text-align: center;'></th></tr>";
        $rowNo=0;
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value){
            $$key=$value;
          }
          $resultStatus=$status[1];
          if($CoursStatus > $formCourseStatusLevel +2){
            $resultStatus=$status[0];
          }

          if($CoursStatus == $formCourseStatusLevel + 1 ){
            $printButtonStatus=$status[0];
          }else{
            $printButtonStatus=$status[1];
          }
          if($CoursStatus > $formCourseStatusLevel){
            $buttonStatus=$status[1];
          }else{
            $buttonStatus=$status[0];
          }
          $rowStyle="";
          if($CoursId == $HiliteCourseId){
            $rowStyle=" style='background-color: DarkKhaki;'";
          }
          $rowNo++;
          echo "<tr$rowStyle><td>$CoursBulletin - $CrsName";
          $deleteDisabled=" disabled";
          if(isset($perms['Developer'])){
            echo "<br>Course Status: [$CoursStatus] - Course Id: [$CoursId]";
            $deleteDisabled="";
          }
          echo "</td>";
          echo "<td style='text-align: right;'><form method='post'>";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          //echo $CoursStatus;
          if(isset($perms['plannedPrint'])){
            echo "<button type='submit' class='pwdBtn' name='mode' value='qr'>QR</button> ";
          }
          if(isset($perms['plannedPrint'])){
            echo "<button type='submit' class='rsltBtn' name='mode' value='resultLetter'$resultStatus>خطاب النتيجة</button> ";
          }
          if(isset($perms['plannedPrint'])){
            echo "<button type='submit' class='pwdBtn' name='mode' value='table'$printButtonStatus>الجدول</button> ";
          }
          if(isset($perms['plannedPrint'])){
            echo "<button type='submit' class='pwdBtn' name='mode' value='manshour'$printButtonStatus>المنشور</button> ";
          }
          if(isset($perms['plannedPrint'])){
            echo "<button type='submit' class='pwdBtn' name='mode' value='letter'$printButtonStatus>الخطاب</button> ";
          }
          if(isset($perms['plannedLecturers'])){
            echo "<button type='submit' class='genBtn' name='mode' value='lecs'>المحاضرين</button> ";
          }
          if(isset($perms['plannedNumbers'])){
            echo "<button type='submit' class='grpBtn' name='mode' value='nominees'>اﻷعداد</button> ";
          }
          if(isset($perms['plannedEdit'])){
            echo "<button type='submit' class='edtBtn' name='mode' value='edit'$buttonStatus>تعديل</button> ";
          }
          echo "<button type='submit' class='viewBtn' name='mode' value='view'>عرص</button> ";
          if(isset($perms['plannedDelete'])){
            echo "<button type='submit' class='delBtn' name='mode' value='deleteconfirm'$buttonStatus $deleteDisabled>الغاء</button> ";
          }
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
