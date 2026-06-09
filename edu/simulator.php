<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$__systemRoot="../";                        //path to system root
include($__systemRoot."functions.php");     //system Functions don't remove
include('functions.php');                   //uncomment this line if you have a local functions file
include("appdb.php");                       //application database credentials
$__includeDir="../include";                //path to include directory
foreach($_POST as $key => $value){
  $$key=$value;
}
$formCourseStatusLevel=0;
$CoursType=3;
$PrgType=12;
$EduSection=9;
//***********************************************************************************************
// control variables
//***********************************************************************************************
$__dir="rtl";
$pageTitle="البرامج التدريبية";
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
$showList=true;
$logged=false;
if(!isset($mode)){
  $mode="";
}
if(!isset($errorMessage)){
  $errorMessage="";
}
if(!isset($errorNo)){
  $errorNo = 0;
}
if(!isset($validationError)){
  $validationError=false;
}
/*
//***********************************************************************************************
// user code
//***********************************************************************************************
  $q="select max(strtrnNo) as nextaddno from storesTrnMs where strtrntype=1";
  $r=mysqli_query($dbc,$q);
  if($r){
    while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
      foreach($row as $key => $value){
        $$key=$value;
      }
      $nextadd=$nextaddno+1;
    }
  }
*/
//***********************************************************************************************
// form variables
//***********************************************************************************************
$addFormLabel="جديد";
$editFormLabel="تعديل";
$saveLabel="حفظ";
$cancelLabel="تراجع";
$okLabel="حسنا";
$showAddButton=true;
$addButtonLabel="إضافة دورة جديدة";
$viewFormLabel="عرض";
$deleteConfirmationLabel="الغاء";
$deleteConfirmationMessage="هل أنت متأكد انك تريد الغاء  ";
$deleteConfirmationField=2;
$yesButton="نعم";
$noButton="لا";
//***********************************************************************************************
// check session
//***********************************************************************************************
if(isset($_SESSION['__uid'])){
  $__uid=$_SESSION['__uid'];
  //$fileName="programs.php";
  $fileName=basename(__FILE__);
  $mnuId=getCommandMenuId($fileName);
  if(checkUserMenuItem($__uid,$mnuId)){
    $logged=true;
  }
  if($logged){
    $logged=true;
    if(!isset($mode)){
      $mode=0;
    }
    if(!isset($errorMessage)){
      $errorMessage="";
    }
    $userPerms=getUserPermissions($__uid);
    $user_year=getuseryear($__uid,$dbc);
    $trnYear=$user_year['Desc'];
    $YearId=$user_year['Id'];
    $yearStart=$user_year['Start'];
    $yearEnd=$user_year['End'];

//***********************************************************************************************
// get supervisor
//***********************************************************************************************
    $q="SELECT SecHead FROM EduSections WHERE SecId=$EduSection";
    $r=mysqli_query($dbc,$q);
    if($r){
      if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value){
          $$key=$value;
        }
        $intSuper=$SecHead;
      }
    }

//***********************************************************************************************
// get programs
//***********************************************************************************************
    $prgs=array();
    $q="select CrsId,CrsName from CoursesGuide where CrsProgram=$PrgType order by CrsName";
    $r=mysqli_query($dbc,$q);
    if($r){
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value){
          $$key=$value;
        }
        $prgs[$CrsId]=$CrsName;
      }
    }

//***********************************************************************************************
// get districts
//***********************************************************************************************
    $dists=array();
    $q="SELECT dist_id,dist_name from districts ORDER BY dist_name";
    $r=mysqli_query($dbc,$q);
    if($r){
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value){
          $$key=$value;
        }
        $dists[$dist_id]=$dist_name;
      }
    }

//***********************************************************************************************
// get companies
//***********************************************************************************************
    $comps=array();
    $q="select cmpId,cmpName from companies order by cmpName";
    $r=mysqli_query($dbc,$q);
    if($r){
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach ($row as $key => $value){
          $$key = $value;
        }
        $comps[$cmpId] = $cmpName;
      }
      mysqli_free_result($r);
    }

//***********************************************************************************************
// form html header
//***********************************************************************************************
    echo "<!DOCTYPE html><html><head>";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
    echo "<title>$pageTitle - $trnYear - دورات المحاكي</title>";
    echo "<style>";
    echo "* { box-sizing: border-box;}";
    echo "#filterBox {background-image: url('images/searchicon.png');background-position: 10px 10px;background-repeat: no-repeat;width: 100%;font-size: 16px;padding: 12px 20px 12px 40px;border: 1px solid #ddd;margin-bottom: 12px;}";
    echo "#masterTable {border-collapse: collapse;width: 100%;border: 1px solid #ddd;font-size: 18px;}";
    echo "#masterTable th, #masterTable td {text-align: right;padding: 12px;}";
    echo "#masterTable tr {border-bottom: 1px solid #ddd;}";
    echo "#masterTable tr.header, #masterTable tr:hover {background-color: #f1f1f1;}";
    echo ".navBtn {background-color: dodgerblue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".navBtn:hover {opacity: 1;}";
    echo ".regBtn {background-color: DarkViolet;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".regBtn:hover {opacity: 1;}";
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
    echo ".infBtn {background-color: brown;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".infBtn:hover {opacity: 1;}";
    echo ".cnlBtn {background-color: red;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".cnlBtn:hover {opacity: 1;}";
    echo ".trnBtn {background-color: CadetBlue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".trnBtn:hover {opacity: 1;}";
    echo ".okBtn {background-color: indigo;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".okBtn:hover {opacity: 1;}";
    echo ".grpBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".grpBtn:hover {opacity: 1;}";
    echo ".arrowBtn {background-color: DarkOrchid;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 50px;  opacity: 0.9;}";
    echo ".arrowBtn:hover {opacity: 1;}";
    echo ".invoiceBtn {background-color: CadetBlue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".invoiceBtn:hover {opacity: 1;}";
    echo ".input-container {display: -ms-flexbox; /* IE10 */  display: flex;  width: 100%;  margin-bottom: 15px;}";
    echo ".input-field { width: 100%;  padding: 10px;  outline: none;}";
    echo ".input-field:focus {border: 2px solid dodgerblue;}";
    echo ".error{color: red; font-size: medium;text-align:center;font-weight: bold;}";
    echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";    
    echo "body{direction: rtl;}";
    echo ".partLst{width: 100%;}";
    echo ".partLst th{text-align: center; color: white; background-color :grey;}";
    echo "</style>";
	  echo "</head>";
	  echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>$pageTitle للعام $trnYear  - دورات المحاكي</h2>";

//*****************************************************************************************
//Validation
//*****************************************************************************************
    if($mode=="saveadd" or $mode=="saveedit"){
      if($CoursCrsId == '-1'){
        $errorMessage="لابد من اختيار برنامج";
        $mode=substr($mode,4);
      }
      if($CoursSupervisorExt == '-1'){
        $errorMessage="لابد من اختيار جهة التعاقد";
        $mode=substr($mode,4);
      }
      if($CoursArea == '-1'){
        $errorMessage="لابد من تحديد المنطقة";
        $mode=substr($mode,4);
      }
      if($CoursFromPln == ''){
        $errorMessage="لابد من تحديد التاريخ";
        $mode=substr($mode,4);
      }
      if($CoursFromPln < $yearStart){
        $errorMessage.="تاريخ البداية قبل بداية العام التدريبي<br>";
        $mode=substr($mode,4);
      }
      if($CoursFromPln > $yearEnd){
        $errorMessage.="تاريخ البداية بعد نهاية العام التدريبي<br>";
        $mode=substr($mode,4);
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

      if($mode=="saveadd"){
        $q="select ifnull(count(CoursId),0) as crsCount from Courses where CoursBulletin=$CoursBulletin and CoursType=4 and CoursCrsId=$CoursCrsId";
        $r=mysqli_query($dbc,$q);
        if($r){
          while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
              $$key=$value;
            }
            if($crsCount > 0){
              $errorMessage="رقم الدورة موجود مسبقا";
              $mode=substr($mode,4);
            }
          }
        }  
      }elseif($mode=="saveedit"){
        $q="select ifnull(count(CoursId),0) as crsCount from Courses where CoursBulletin=$CoursBulletin and CoursType=4 and CoursCrsId=$CoursCrsId and CoursId<>$CoursId";
        $r=mysqli_query($dbc,$q);
        if($r){
          while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
              $$key=$value;
            }
            if($crsCount > 0){
              $errorMessage="رقم الدورة موجود مسبقا";
              $mode=substr($mode,4);
            }
          }
        }  
      }
    }

//*****************************************************************************************
//Add save (new record)
//*****************************************************************************************
    if($mode=="saveadd"){
      $q="insert into Courses(CoursCrsId,CoursType,CoursBulletin,CoursArea,CoursSection,CoursYear,CoursSupervisorInt,CoursFromPln,CouursToPln,CoursLocation,CoursSupervisorExt) values($CoursCrsId,$CoursType,$CoursBulletin,$CoursArea,$EduSection,$YearId,$intSuper,'$CoursFromPln','$CouursToPln',$CoursLocation,$CoursSupervisorExt)";
      $r=mysqli_query($dbc,$q);
      if($r){
        echo "<div style='color: green;text-align: center;'>تم الحفظ بنجاح</div>";
      }
    }

//*****************************************************************************************
//Edit Save (save edited record)
//*****************************************************************************************
    if($mode=="saveedit"){
      //save edited value
      if(!$validationError){
        $q="UPDATE Courses SET CoursCrsId='$CoursCrsId', CoursBulletin=$CoursBulletin, CoursArea=$CoursArea, CoursFromPln='$CoursFromPln', CouursToPln='$CouursToPln', CoursLocation=$CoursLocation, CoursSupervisorExt=$CoursSupervisorExt WHERE CoursId=$CoursId";
        $r=mysqli_query($dbc,$q);
        if($r){
          echo "<div style='color: green;text-align: center;'>تم الحفظ بنجاح</div>";
        }
      }else{
        $mode="edit";
      }
    }

//*****************************************************************************************
//Read Record for edit, view and delete
//*****************************************************************************************
if(($mode=="edit" or $mode=="view" or $mode=="deleteConfirm" or $mode=='saveedit') and $errorMessage == ""){
  $q="select CoursId,CoursCrsId,CrsName,CoursBulletin,CoursStatus,CoursArea,CoursFromPln,CouursToPln,CoursLocation,CoursSupervisorExt from CoursesGuide inner join Courses on CoursCrsId=CrsId where CoursId=$CoursId";
  $r=mysqli_query($dbc,$q);
  if($r){
    if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
      foreach($row as $key => $value){
        $$key=$value;
      }
    }
  }
}

//*****************************************************************************************
// add - Edit form
//*****************************************************************************************
    if($mode=="add" or $mode=="edit"){
      $showList=false;
      //get all course numbers

      if($mode=="add"){
        $formLabel=$addFormLabel;
      }else{
        $formLabel=$editFormLabel;
      }
      echo "<form method='post' style='max-width:800px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>$formLabel</h2>";
      echo "<table width='100%'>";

      $maxs=array();
      foreach($prgs as $key => $value){
        $q="select ifnull(max(CoursBulletin),0) as maxNo from Courses where CoursCrsId = $key";
        $r=mysqli_query($dbc,$q);
        if($r){
          if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $fieldKey => $fieldValue){
              $$fieldKey=$fieldValue;
            }
            $maxs[$key]=$maxNo;
          }
        }
      }

      echo "<tr><td style='width: 225px;'>البرنامج:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='CoursCrsId' onchange='getNumber(this.value);'><option value='-1'>---</option>";
      foreach($prgs as $key => $value){
        echo "<option value='$key'";
        if(isset($CoursCrsId)){
          if($CoursCrsId == $key){
            echo " selected";
          }
        }
        echo ">$value</option>";  
      }
      echo "</select>";
      echo "</div></td></tr>";  
      echo "<tr><td style='width: 225px;'>رقم  الدورة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='number' name='CoursBulletin' placeholder='رقم الدورة' min='1' max='10000' id='CoursBulletin'";
      if(isset($CoursBulletin)){
        echo " value='$CoursBulletin'";
      }
      echo ">";
      echo "</div></td></tr>";
      
      echo "<tr><td style='width: 225px;'>المنطقة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='CoursArea'><option value='-1'>---</option>";
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

      echo "<tr><td>الجهة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='CoursSupervisorExt'><option value='-1'>جهة التعاقد</option>";
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


      if(!isset($CoursFromPln)){
        $CoursFromPln=date("Y-m-d");
      }

      echo "<tr><td style='width: 225px;'>مكان الاتعقاد:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='CoursLocation'><option value='-1'>---</option>";
      foreach($comps as $key => $value){
        echo "<option value='$key'";
        if(isset($CoursLocation )){
          if($CoursLocation  == $key){
            echo " selected";
          }
        }
        echo ">$value</option>";  
      }
      echo "</select>";
      echo "</div></td></tr>";  
      if(!isset($CoursFromPln)){
        $CoursFromPln=date("Y-m-d");
      }


      echo "<tr><td style='width: 225px;'>تاريخ البداية:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='date' name='CoursFromPln' placeholder='تاريخ البداية'";
      if(isset($CoursFromPln)){
        echo " value='$CoursFromPln'";
      }
      echo ">";
      echo "</div></td></tr>";
      
      echo "<tr><td style='width: 225px;'>تاريخ النهاية:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='date' name='CouursToPln' placeholder='تاريخ النهاية'";
      if(isset($CouursToPln)){
        echo " value='$CouursToPln'";
      }
      echo ">";
      echo "</div></td></tr>";
      echo "</table>";
      if($mode=='edit'){
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      }
      $newMode="save".$mode;
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'>$saveLabel</button> <button type='submit' class='cnlBtn' name='mode' value=''>$cancelLabel</button></div>";
      echo "</form>";
      echo "<div class='error'><p>$errorMessage</p></div>";
?>
<script>
function getNumber(prgCode){
  const prgMaxs=[];
<?php
//get the course maxs
foreach($prgs as $key => $value){
  echo "prgMaxs[$key]=";
  if(array_key_exists($key,$maxs)){
    echo $maxs[$key];
  }else{
    echo 0;
  }
  echo ";\n";
}
?>
document.getElementById("CoursBulletin").value=prgMaxs[prgCode] + 1;
}
</script>
<?php
    }

//*****************************************************************************************
//Delete Confirmation
//*****************************************************************************************
    if($mode=="deleteConfirm"){
      $showList=false;
      echo "<form method='post' style='max-width:500px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>الغاء</h2>";
      echo "<div style='text-align: center;direction:$__dir;'>سيتم الغاء  $CrsName - $CoursBulletin - " . $dists[$CoursArea] . " - $CoursFromPln هل أنت متأكد ?...</div><br>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='delete'>نعم</button> <button type='submit' class='cnlBtn' name='mode' value=''>لا</button></div>";
      echo "</form>";
    }

//*****************************************************************************************
//Delete
//*****************************************************************************************
    if($mode=="delete"){
      $q="DELETE FROM Courses WHERE CoursId=$CoursId";
      $r=mysqli_query($dbc,$q);
      if($r){
        echo "<div style='color: green; text-align: center;'>لقد تم الالغاء ...</div>";
      }
    }

//*****************************************************************************************
//View Record
//*****************************************************************************************
    if($mode=="view"){
      //View form
      $showList=false;
      echo "<h2 style='text-align: center;'>عرض</h2>";
      echo "<table width='100%'>";
      echo "<tr><td style='width: 225px;'>البرنامج:</td><td colspan='2'><div class='input-container'>".$prgs[$CoursCrsId]."</div></td></tr>";
      echo "<tr><td style='width: 225px;'>رقم  الدورة:</td><td colspan='2'><div class='input-container'>$CoursBulletin</div></td></tr>";
      echo "<tr><td style='width: 225px;'>المنطقة:</td><td colspan='2'><div class='input-container'>".$dists[$CoursArea]."</div></td></tr>";  
      echo "<tr><td style='width: 225px;'>جهة التعاقد:</td><td colspan='2'><div class='input-container'>".$comps[$CoursSupervisorExt]."</div></td></tr>";  
      echo "<tr><td style='width: 225px;'>تاريخ البداية:</td><td colspan='2'><div class='input-container'>$CoursFromPln</div></td></tr>";
      echo "<tr><td style='width: 225px;'>تاريخ النهاية:</td><td colspan='2'><div class='input-container'>$CouursToPln</div></td></tr>";
      echo "<tr><td style='width: 225px;'>مكان الانعقاد:</td><td colspan='2'><div class='input-container'>".$comps[$CoursLocation]."</div></td></tr>";
      echo "</table>";
      echo "<table width='100%' id='partLst'>";
      echo "<tr><th>م</th><th>اﻹسم باللغة العربية</th><th>اﻹسم باللغة اﻹنجليزية</th><th>الرقم القومي</th><th>تاريخ الميلاد</th><th>جهة الميلاد</th><th>الجنسية</th></tr>";

      $partList=array();
      $q="SELECT TrnNo,TrnName,trnEname,TrnIdNo,trnBDate,GovName,CntArabNationalty FROM Trainees INNER JOIN coursetrainees ON TrnNo = crstrntrainee LEFT JOIN Governrates ON trnBGovernrate=GovId LEFT JOIN Countries ON trnNationality=cntId WHERE crstrncourse=$CoursId";
      $r=mysqli_query($dbc,$q);
      if($r){
        $i=1;
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $fieldKey => $fieldValue){
            $$fieldKey=$fieldValue;
          }
          echo "<tr><td>$i</td><td>$TrnName</td><td>$trnEname</td><td>$TrnIdNo</td><td>$trnBDate</td><td>$GovName</td><td>$CntArabNationalty</td></tr>";
          $i++;
        }
      }

      echo "</table>";
      echo "<form method='post' style='max-width:800px;margin:auto;direction:$__dir;'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value=''>العودة</button></div>";
      echo "</form>";
    }

//***************************************************************************************** */
// Registration
//***************************************************************************************** */
include("registration.php");

//*****************************************************************************************
//Trainee info
//*****************************************************************************************
if($mode=="trnInfo"){
  $_SESSION['TrnNo']=$TrnNo;
  $_SESSION['CoursId']=$CoursId;
  echo "<script>window.open('trnInfo.php');</script>";
  $mode="showTList";
}

//*****************************************************************************************
//Trainee info
//*****************************************************************************************
if($mode=="printInvoice"){
  $_SESSION['TrnNo']=$TrnNo;
  $_SESSION['CoursId']=$CoursId;
  echo "<script>window.open('invoice.php');</script>";
  $mode="showTList";
}

//*****************************************************************************************
//unregister
//*****************************************************************************************
    if($mode=="unregister"){
      $q="DELETE FROM coursetrainees WHERE crstrntrainee=$TrnNo AND crstrncourse=$CoursId";
      $r=mysqli_query($dbc,$q);
      if($r){
        $tlMessage="<div style='color: green; text-align: center;font-weight: bold;'>تم الغاء التسجيل</div>";
      }else{
        $tlMessage="<div style='color: red; text-align: center;font-weight: bold;'>خطأ في الحفظ</div>";
      }
      $mode='showTList';
    }
//*****************************************************************************************
//Trainee List
//*****************************************************************************************
    if($mode=='showTList'){
      $showList=false;
      //read course name
      $q="select CrsName,CoursBulletin from CoursesGuide inner join Courses on CrsId=CoursCrsId where CoursId=$CoursId";
      $r=mysqli_query($dbc,$q);
      if($r){
        if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value){
            $$key=$value;
          }
        }
      }
      $q="SELECT TrnNo,TrnName FROM  Trainees INNER JOIN coursetrainees ON TrnNo = crstrntrainee WHERE crstrncourse=$CoursId";
      $r=mysqli_query($dbc,$q);
      if($r){
        if(isset($tlMessage)){
          echo $tlMessage;
        }
        echo "<div style='text-align:center;font-size:large;color: blue;font-weight: bold;'>المسجلين في دورة $CrsName رقم $CoursBulletin</div><br>";
        if(isset($userPerms['UnplannedRegisterTrainee'])){
          //new form
          echo "<div style='width: 400px; margin: auto;text-align: center;'><form method='post'>";
          //echo "<input type='hidden' name='mode' value='registeration'>";
          //echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          //echo "<button type='submit' class='addBtn' style='width: 150px;'>تسجبل</button> ";
          echo "<button type='button' class='cnlBtn' style='width: 150px;' onclick='window.location=\"$fileName\"'>العودة</button>";
          echo "</form>";
          echo "<br>";
          echo "</div><br>";
        }
        
        //filter list form 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table id='masterTable'>";
        echo "<tr class='header'>";
        echo "<th>المتدرب</th><th style='width:400px;text-align: center;'></th></tr>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          echo "<tr><td>$TrnName</td>";
          echo "<td style='text-align: right;'><form method='post'>";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          echo "<input type='hidden' name='TrnNo' value='$TrnNo'>";
          echo "<input type='hidden' name='returnMode' value='showTList'>";
          if(isset($userPerms['UnplannedUrRegisterTrainee'])){
            echo "<button type='submit' class='regBtn' name='mode' value='unregister'>الغاء التسجيل</button> ";
          }
          if(isset($userPerms['UnplannedShowTraineeInfo'])){
            echo "<button type='submit' class='infBtn' name='mode' value='trnInfo'>بيان مشارك</button> ";
          }
/*          if(isset($userPerms['UnplannedPrintInvoice'])){
            echo "<button type='submit' class='invoiceBtn' name='mode' value='printInvoice'>الحافظة</button> ";
          }*/
          if(isset($userPerms['UnplannedEditTraineeInfo'])){
            echo "<button type='submit' class='edtBtn' name='mode' value='editTrnInfo'>تعديل بيانات</button> ";
          }
          /*echo "<button type='submit' class='viewBtn' name='mode' value='view'>عرص</button> ";
          echo "<button type='submit' class='delBtn' name='mode' value='deleteConfirm'>الغاء</button> ";*/
          echo "</form></td></tr>";
        }
        echo "</table>";
        mysqli_free_result($r);
      }
    }

    
//*****************************************************************************************
//course List
//*****************************************************************************************
    if($showList){
      if(!isset($userPerms['UnplannedAddNewCourse'])){
        $showAddButton=false;
      }
      if($showAddButton){
        //new form
        echo "<div style='width: 110px; margin: auto;'><form method='post'>";
        echo "<input type='hidden' name='mode' value='add'>";
        echo "<button type='submit' class='addBtn' style='width: 150px;'>$addButtonLabel</button>";
        echo "</form></div><br>";
      }
      $q="select CoursId,CrsName,CoursBulletin,CoursStatus,ifnull(count(crstrnid),0) as trnCount,CrsProgram,dist_name,CoursFromPln,CoursType,CoursYear from CoursesGuide inner join Courses on CoursCrsId=CrsId LEFT JOIN coursetrainees ON CoursId=crstrncourse INNER JOIN districts ON dist_id=CoursArea GROUP BY CoursId having CoursType=$CoursType AND CoursYear=$YearId order by CoursStatus,CrsName,CoursId";
      $r=mysqli_query($dbc,$q);
      if($r){
        //filter list form 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table id='masterTable'>";
        echo "<tr class='header'>";
        echo "<th>الدورة</th><th style='width:600px;text-align: center;'></th></tr>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          $devInfo="";
          if(isset($userPerms['Developer'])){
            $devInfo="Status:$CoursStatus - ID:$CoursId";
          }
          echo "<tr><td>$CrsName - $CoursBulletin - $dist_name - $CoursFromPln (عدد المتدربين: $trnCount) [$devInfo]</td>";
          echo "<td style='text-align: right;'><form method='post'>";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          if($CoursStatus == $formCourseStatusLevel){
            if(isset($userPerms['UnplannedRegisterTrainee'])){
              echo "<input type='hidden' name='returnMode' value=''>";
              echo "<input type='hidden' name='cancelLabel' value='تراجع'>";
              echo "<input type='hidden' name='afterSaveMode' value='registeration'>";
              echo "<button type='submit' class='regBtn' name='mode' value='registeration'>تسجيل</button> ";
            }
            if(isset($userPerms['UnplannedViewTrainees'])){
              echo "<button type='submit' class='trnBtn' name='mode' value='showTList'>المتدربين</button> ";
            }
            if(isset($userPerms['UnplannedEditCourse'])){
              echo "<button type='submit' class='edtBtn' name='mode' value='edit'>تعديل</button> ";
            }
          }
          if(isset($userPerms['UnplannedViewCourse'])){
            echo "<button type='submit' class='viewBtn' name='mode' value='view'>عرض</button> ";
          }
          if($CoursStatus == 0){
            if(isset($userPerms['UnplannedDelCourse'])){
              if($trnCount != 0){
                $btnStatus=" disabled";
                $btnStyle=" style='background-color: grey;'";
              }else{
                $btnStatus="";
                $btnStyle="";
              }
              echo "<button type='submit' class='delBtn' name='mode'$btnStyle value='deleteConfirm'$btnStatus>الغاء</button> ";
            }
          }
          echo "</form></td></tr>";
        }
        echo "</table>";
        mysqli_free_result($r);
      }
    }

//*****************************************************************************************
//Form scripts
//*****************************************************************************************
    echo "<script>
          function filterList() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById(\"filterBox\");
            filter = input.value.toUpperCase();
            table = document.getElementById(\"masterTable\");
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
    mysqli_close($dbc);
  }
}

//*****************************************************************************************
//log in error
//*****************************************************************************************
if(!$logged){
  include($__systemRoot."expired.php");
}
?>