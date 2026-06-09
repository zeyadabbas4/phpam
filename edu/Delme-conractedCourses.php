<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$__systemRoot="../";                        //path to system root
include($__systemRoot."functions.php");     //system Functions don't remove
//include('functions.php');                   //uncomment this line if you have a local functions file
include("appdb.php");                       //application database credentials
foreach($_POST as $key => $value){
  $$key=$value;
}
//***********************************************************************************************
// control variables
//***********************************************************************************************
$__dir="rtl";
$pageTitle="البرامج التعاقدية";
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
//***********************************************************************************************
// get the training year
//***********************************************************************************************
    $thisYear = intval(date("Y"));
    $thisMonth = intval(date("m"));
    $nextYear = $thisYear + 1;
    $prevYear = $thisYear - 1;
    if($thisMonth < 7){
      $trnYear= $prevYear . " / " . $thisYear;
      $yearStart = $prevYear . "-07-01";
      $yearEnd = $thisYear . "-06-30";
    }else{
      $trnYear= $thisYear . " / " . $nextYear;
      $yearStart = $thisYear . "-07-01";
      $yearEnd = $nextYear . "-06-30";
    }
    $found=false;
    $q="select YearId,YearDesc from Years where YearStart='$yearStart' and YearType='E'";
    $r=mysqli_query($dbc,$q);
    if($r){
      if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value){
          $$key=$value;
        }
        $found=true;
      }
    }
    if(!$found){
      $q="insert into Years(YearDesc,YearStart,YearEnd,YearType) values('$trnYear','$yearStart','$yearEnd','E')";
      $r=mysqli_query($dbc,$q);
      $YearId=mysqli_insert_id($dbc);
    }
//***********************************************************************************************
// get supervisor
//***********************************************************************************************
    $q="select educValue from EduConstants where educName='ConSuper'";
    $r=mysqli_query($dbc,$q);
    if($r){
      if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value){
          $$key=$value;
        }
        $intSuper=$educValue;
      }
    }

//***********************************************************************************************
// get sections
//***********************************************************************************************


    $secs=array();
    $q="select PrgId,PrgName from Programs";
    $r=mysqli_query($dbc,$q);
    if($r){
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $secs[$row['PrgId']]=$row['PrgName'];
      }
    }
//***********************************************************************************************
// get programs
//***********************************************************************************************
    $prgs=array();
    $q="select CrsId,CrsProgram,CrsCode,CrsName from CoursesGuide";
    $r=mysqli_query($dbc,$q);
    if($r){
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $prgs[$row['CrsId']]['Name']=$row['CrsName'];
        $prgs[$row['CrsId']]['Code']=$row['CrsCode'];
        $prgs[$row['CrsId']]['Program']=$row['CrsProgram'];
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
// form html header
//***********************************************************************************************
    echo "<!DOCTYPE html><html><head>";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
    echo "<title>$pageTitle - $trnYear</title>";
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
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>$pageTitle للعام $trnYear</h2>";
    
//*****************************************************************************************
//Validation
//*****************************************************************************************
    if($mode=="saveadd" or $mode=="saveedit"){
      if($CoursCrsId == '-1'){
        $errorMessage="لابد من اختيار برنامج";
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
      }else{
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
      $q="insert into Courses(CoursCrsId,CoursType,CoursBulletin,CoursArea,CoursSection,CoursYear,CoursSupervisorInt,CoursFromPln) values($CoursCrsId,2,$CoursBulletin,$CoursArea,13,$YearId,$intSuper,'$CoursFromPln')";
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
        $q="UPDATE Courses SET CoursCrsId='$CoursCrsId', CoursBulletin=$CoursBulletin, CoursArea=$CoursArea, CoursFromPln='$CoursFromPln' WHERE CoursId=$CoursId";
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
  $q="select CoursId,CoursCrsId,CrsName,CoursBulletin,CoursStatus,CoursArea,CoursFromPln from CoursesGuide inner join Courses on CoursCrsId=CrsId where CoursId=$CoursId";
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

      echo "<tr><td style='width: 225;'>القسم:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='CoursSection' onchange='filterPrg(this.value)'\r\n<option value='0'>القسم المختص</option>\r\n";
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

//////
  echo "</div></td></tr>";
      echo "<tr><td style='width: 225;'>'البرنامج':</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='CoursCrsId' id='CoursCrsId'>\r\n<option value='0'>البرنامج</option>\r\n";
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

/////



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
        echo ">".$value['Program']."</option>";  
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
      echo "</table>";
      if($mode=='edit'){
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      }
      $newMode="save".$mode;
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'>$saveLabel</button> <button type='submit' class='cnlBtn' name='mode' value=''>$cancelLabel</button></div>";
      echo "</form>";
      echo "<div class='error'><p>$errorMessage</p></div>";
echo "
<script>
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


function getNumber(prgCode){
  const prgMaxs=[]; ";
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
echo 'document.getElementById("CoursBulletin").value=prgMaxs[prgCode] + 1;';
echo "</script>";
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
      echo "<tr><td style='width: 225px;'>تاريخ البداية:</td><td colspan='2'><div class='input-container'>$CoursFromPln</div></td></tr>";
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

//*****************************************************************************************
//save registeration details
//*****************************************************************************************

    if($mode=="saveRegDetails" or $mode=="saveEditDetails"){
      //validation
      if($mode=="saveRegDetails"){
        $errMode="regDetails";
      }else{
        $errMode="editTrnInfo";
      }
      $saveRegDetailserrorMessage="";
      if($TrnName == ""){
        $saveRegDetailserrorMessage .= "لابد من ادخال اسم المتدرب<br>";
        $mode=$errMode;
      }
      if($trnEname == ""){
        $saveRegDetailserrorMessage .= "لابد من ادخال اسم المتدرب باللغة الانجليزية<br>";
        $mode=$errMode;
      }
      if($TrnAddress == ""){
        $saveRegDetailserrorMessage .= "لابد من ادخال عنوان المتدرب<br>";
        $mode=$errMode;
      }
      if($TrnTels == ""){
        $saveRegDetailserrorMessage .= "لابد من ادخال تليفون المتدرب<br>";
        $mode=$errMode;
      }
      if($TrnCo == -1){
        $saveRegDetailserrorMessage .= "لابد من ادخال جهة عمل المتدرب<br>";
        $mode=$errMode;
      }
      if($trnBDate == ""){
        $saveRegDetailserrorMessage .= "لابد من ادخال تاريخ ميلاد المتدرب<br>";
        $mode=$errMode;
      }else{
        $today = date("Y-m-d");
        $diff = date_diff(date_create($trnBDate), date_create($today));
        $age=$diff->format('%y');
        if($age < 18){
          $saveRegDetailserrorMessage .= "سن المتدرب لا يجب أن يقل عن 18 عاما<br>";
          $mode=$errMode;
        }
      }
      if($trnBGovernrate == -1 and $trnBState == -1){
        $saveRegDetailserrorMessage .= "لابد من ادخال جهة ميلاد المتدرب<br>";
        $mode=$errMode;
      }
      if($trnNationality == -1){
        $saveRegDetailserrorMessage .= "لابد من ادخال جنسية المتدرب<br>";
        $mode=$errMode;
      }
      if($trnNationality == 63 and strlen($TrnIdNo) < 14 ){
        $saveRegDetailserrorMessage .= "لابد من ادخال الرقم القومي للمتدربين المصريين<br>";
        $mode=$errMode;
      }
      if($trnNationality != 63 and $trnPassportNo =="" ){
        $saveRegDetailserrorMessage .= "لابد من ادخال رقم الحواز للمتدربين غير المصريين<br>";
        $mode=$errMode;
      }
      if($trnjobName == ""){
        $saveRegDetailserrorMessage .= "لابد من ادخال وظيفة المتدرب<br>";
        $mode=$errMode;
      }
      if($trncrtName == ""){
        $saveRegDetailserrorMessage .= "لابد من ادخال مؤهل المتدرب<br>";
        $mode=$errMode;
      }

      if(isset($TrnNo) and $mode=="saveRegDetails"){
        $q="SELECT ifnull(COUNT(crstrnid),0) crsCount FROM coursetrainees WHERE crstrntrainee=$TrnNo and crstrncourse=$CoursId";
        $r=mysqli_query($dbc,$q);
        if($r){
          if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            $crsCount=$row['crsCount'];  
          }
        }
        if($crsCount != 0){
          $saveRegDetailserrorMessage .= "لقد تم تسجيل المتدرب في الدورة مسبقا<br>";
          echo "<div class='error'><p>لقد تم تسجيل المتدرب في الدورة مسبقا</p></div>";
          $mode="";
        }
      }

      if($saveRegDetailserrorMessage == ""){
        if(isset($TrnNo)){
          $q="update Trainees set TrnName='$TrnName',trnEname='$trnEname',TrnAddress='$TrnAddress',TrnTels='$TrnTels',TrnWhatsApp='$TrnWhatsApp',TrnCo=$TrnCo,trnBDate='$trnBDate',trnNationality=$trnNationality";
          if($trnBGovernrate != -1){
            $q.=",trnBGovernrate=$trnBGovernrate";
          }
          if($trnBState != -1){
            $q.=",trnBState=$trnBState";
          }
          if($trnPassportNo != ""){
            $q.=",trnPassportNo='$trnPassportNo'";
          }
          $q .= " where TrnNo=$TrnNo";
        }else{
          $q="INSERT INTO Trainees (TrnName, trnEname, TrnAddress, TrnTels, TrnWhatsApp, TrnCo, TrnIdNo, trnBDate, trnNationality";
          if($trnBGovernrate != -1){
            $q.=", trnBGovernrate";
          }
          if($trnBState != -1){
            $q.=", trnBState";
          }
          if($trnPassportNo != ""){
            $q.=", trnPassportNo";
          }
          $q .= ") values('$TrnName', '$trnEname', '$TrnAddress', '$TrnTels', $TrnWhatsApp, $TrnCo, '$TrnIdNo', '$trnBDate', $trnNationality";
          if($trnBGovernrate != -1){
            $q.=", $trnBGovernrate";
          }
          if($trnBState != -1){
            $q.=", $trnBState";
          }
          if($trnPassportNo != ""){
            $q.=", '$trnPassportNo'";
          }
          $q.= ")";
        }
        $r=mysqli_query($dbc,$q);
        if($r){
          if(!isset($TrnNo)){
            $TrnNo=mysqli_insert_id($dbc);
          }
          
          //read last job
          $q="select trnjobName as lastjobname from TraineeJobs where trnjobTrainee=$TrnNo order by trnjobDate desc";
          $r=mysqli_query($dbc,$q);
          if($r){
            if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
              $lastjob=$row['lastjobname'];  
            }
          }
          $savejob=true;
          if(isset($lastjob)){
            if($lastjob == $trnjobName){
              $savejob=false;
            }  
          }
          if($savejob){
            $jobDate=date("Y-m-d");
            $q="INSERT INTO TraineeJobs (trnjobTrainee, trnjobName, trnjobDate) VALUES ($TrnNo, '$trnjobName', '$jobDate')";
            $r=mysqli_query($dbc,$q);
          }

          //read last certificate
          $q="select trncrtName as lastCert from TraineeCerts where trncrtTrn=$TrnNo order by trncrtDate desc";
          $r=mysqli_query($dbc,$q);
          if($r){
            if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
              $lastCert=$row['lastCert'];
            }
          }
          $savecert=true;
          if(isset($lastCert)){
            if($lastCert == $trncrtName){
              $savecert=false;
            }  
          }
          if($savecert){
            $certDate=date("Y-m-d");
            $q="INSERT INTO TraineeCerts (trncrtTrn, trncrtDate, trncrtName) VALUES ($TrnNo, '$certDate', '$trncrtName')";
            $r=mysqli_query($dbc,$q);
          }

          if($TrnCopurses !=""){
            $courses = array();
            $courses = explode("\n",$TrnCopurses);
            $crsDate=date("Y-m-d");
            print_r($courses);
            foreach($courses as $key => $value){
              $q="INSERT INTO TraineeCourses (trncrsTrn, trncrsDate, trncrsName) VALUES ($TrnNo, '$crsDate', '$value')";
              echo "<br>$q";
              $r=mysqli_query($dbc,$q);
            }
          }
          if($mode=="saveRegDetails"){
            $q="INSERT INTO coursetrainees ( crstrntrainee, crstrncourse, crstrnStatus) VALUES ($TrnNo, $CoursId, '1');";
            $r=mysqli_query($dbc,$q);
  
            $_SESSION['TrnNo']=$TrnNo;
            $_SESSION['CoursId']=$CoursId;
            echo "<script>window.open('trnInfo.php');</script>";
            echo "<script>window.open('invoice.php');</script>";
          }
          if($mode=="saveEditDetails"){
            $mode="showTList";
          }
        }else{
          echo "<div class='error'><p>خطأ في حفظ البيانات</p></div>";
        }

      }
    }


//*****************************************************************************************
//register details form
//*****************************************************************************************
    if($mode=='regDetails' or $mode=='editTrnInfo'){
      $showList=false;

      //validation
      if($mode=='regDetails'){
        if(!isset($saveRegDetailserrorMessage)){
          if($TemptrnNationality == -1){
            $errorMessage .= "لابد من ادخال جنسية المتدرب<br>";
            $mode="register";
          }
          if($TemptrnNationality == 63 and strlen($TempTrnIdNo) < 14 ){
            $errorMessage .= "لابد من ادخال الرقم القومي للمتدربين المصريين<br>";
            $mode="register";
          }
          if($TemptrnNationality != 63 and $TempTrnIdNo =="" ){
            $errorMessage .= "لابد من ادخال رقم الهوية للمتدربين غير المصريين<br>";
            $mode="register";
          }  
        }  
      }

      if($errorMessage == ""){
        //read trainee info
        if($mode=='regDetails'){
          $trnNationality=$TemptrnNationality;
          $trnBState=$TemptrnNationality;
          $TrnIdNo=$TempTrnIdNo;
          $q="SELECT TrnNo,TrnName,trnEname,TrnAddress,TrnTels,TrnWhatsApp,TrnCo,TrnIdNo,trnBDate,trnBGovernrate,trnBState,trnNationality,trnPassportNo FROM Trainees WHERE TrnIdNo='$TrnIdNo'";
        }else{
          $q="SELECT TrnNo,TrnName,trnEname,TrnAddress,TrnTels,TrnWhatsApp,TrnCo,TrnIdNo,trnBDate,trnBGovernrate,trnBState,trnNationality,trnPassportNo FROM Trainees WHERE TrnNo='$TrnNo'";
        }
        $r=mysqli_query($dbc,$q);
        if($r){
          if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
              $$key=$value;
            }
          }
        }

        if(isset($TrnNo)){
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
          $q="select trncrtName from TraineeCerts where trncrtTrn=$TrnNo order by trncrtDate desc";
          $r=mysqli_query($dbc,$q);
          if($r){
            if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
              foreach($row as $key => $value){
                $$key=$value;
              }  
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
          $q="select CrsName from (CoursesGuide inner join Courses on  CrsId=CoursCrsId) inner join coursetrainees on CoursId=crstrncourse where crstrntrainee=$TrnNo";
          $r=mysqli_query($dbc,$q);
          if($r){
            while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
              foreach($row as $key => $value){
                $$key=$value;
              }
              $crsList[]="[$CrsName]";
            }
          }

        }else{
          if($TemptrnNationality == 63){
            $century=substr($TrnIdNo,0,1);
            $year=substr($TrnIdNo,1,2);
            $month=substr($TrnIdNo,3,2);
            $day=substr($TrnIdNo,5,2);
            $gov=substr($TrnIdNo,7,2);
            if($century=="2"){
              $year="19".$year;
            }elseif($century=="3"){
              $year="20".$year;
            }
            $trnBDate="$year-$month-$day";
            $q="SELECT GovId from Governrates WHERE GovNatId='$gov'";
            $r=mysqli_query($dbc,$q);
            if($r){
              if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
                foreach($row as $key => $value){
                  $$key=$value;
                }
                $trnBGovernrate=$GovId;
              }
            }  
          }
        }  
        echo "<form method='post' style='max-width:800px;margin:auto;direction:$__dir;'>";
        echo "<h2 style='text-align: center;'>تسجيل بيانات مشارك</h2>";
        if(isset($saveRegDetailserrorMessage)){
          echo "<div class='error'><p>$saveRegDetailserrorMessage</p></div>";
        }
        echo "<table width='100%'>";
        echo "<tr id='idNo' ><td style='width: 225px;'>الاسم (عربي):</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' name='TrnName'";
        if(isset($TrnName)){
          echo " value='$TrnName'";
        }
        echo " placeholder='الاسم (عربي)'>";
        echo "</div></td></tr>";  
        echo "<tr id='idNo' ><td style='width: 225px;'>الاسم (انجليزي):</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' name='trnEname'";
        if(isset($trnEname)){
          echo " value='$trnEname'";
        }
        echo " placeholder='الاسم (انجليزي)'>";
        echo "</div></td></tr>";  
        echo "<tr id='idNo' ><td style='width: 225px;'>العنوان:</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' name='TrnAddress'";
        if(isset($TrnAddress)){
          echo " value='$TrnAddress'";
        }
        echo " placeholder='العنوان'>";
        echo "</div></td></tr>";  
        echo "<tr id='idNo' ><td style='width: 225px;'>التليفون:</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' name='TrnTels'";
        if(isset($TrnTels)){
          echo " value='$TrnTels'";
        }
        echo " placeholder='التليفون'>";
        echo "</div></td></tr>";  
        echo "<tr id='idNo' ><td style='width: 225px;'>تليفون واتساب:</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' name='TrnWhatsApp'";
        if(isset($TrnWhatsApp)){
          echo " value='$TrnWhatsApp'";
        }
        echo " placeholder='تليفون واتساب'>";
        echo "</div></td></tr>";  
        echo "<tr id='idNo' ><td style='width: 225px;'>الجهة التابع لها:</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<select name='TrnCo' class='input-field'><option value='-1'>الجهة التابع لها</option>";
        $q="select cmpId,cmpName from companies order by cmpName";
        $r=mysqli_query($dbc,$q);
        if($r){
          while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
              $$key=$value;
            }
            echo "<option value='$cmpId'";
            if(isset($TrnCo)){
              if($cmpId == $TrnCo){
                echo " selected";
              }
            }
            echo ">$cmpName</option>";
          }
        }
        echo "</select>";
        echo "</div></td></tr>";  
        echo "<tr id='idNo' ><td style='width: 225px;'>تاريخ الميلاد:</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='date' name='trnBDate'";
        if(isset($trnBDate)){
          echo " value='$trnBDate'";
        }
        echo " placeholder='تاريخ الميلاد'>";
        echo "</div></td></tr>";  
        echo "<tr id='idNo' ><td style='width: 225px;'>جهة الميلاد:</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<select name='trnBGovernrate' class='input-field'><option value='-1'>جهة الميلاد</option>";
        $q="select GovId,GovName from Governrates order by GovName";
        $r=mysqli_query($dbc,$q);
        if($r){
          while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
              $$key=$value;
            }
            echo "<option value='$GovId'";
            if(isset($trnBGovernrate)){
              if($GovId == $trnBGovernrate){
                echo " selected";
              }
            }
            echo ">$GovName</option>";
          }
        }
        echo "</select>";
        echo "<select name='trnBState' class='input-field'><option value='-1'>دولة الميلاد</option>";
        $q="select cntId,cntArabName from Countries order by cntNumCode,cntArabName";
        $r=mysqli_query($dbc,$q);
        if($r){
          while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
              $$key=$value;
            }
            echo "<option value='$cntId'";
            if(isset($trnBState)){
              if($trnBState == $cntId){
                echo " selected";
              }
            }
            echo ">$cntArabName</option>";
          }
        }
        echo "</select>";
        echo "</div></td></tr>";  
        echo "<tr id='idNo' ><td style='width: 225px;'>الجنسية:</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<select name='trnNationality' class='input-field'><option value='-1'>الجنسية</option>";
        $q="select cntId,cntArabName from Countries order by cntNumCode,cntArabName";
        $r=mysqli_query($dbc,$q);
        if($r){
          while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
              $$key=$value;
            }
            echo "<option value='$cntId'";
            if(isset($trnNationality)){
              if($cntId == $trnNationality){
                echo " selected";
              }
            }
            echo ">$cntArabName</option>";
          }
        }
        echo "</select>";
        echo "</div></td></tr>";  
        echo "<tr id='idNo' ><td style='width: 225px;'>الرقم القومي/رقم الهوية:</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' name='TrnIdNo'";
        if(isset($TrnIdNo)){
          echo " value='$TrnIdNo'";
        }
        echo " placeholder='الرقم القومي/رقم الهوية'>";
        echo "</div></td></tr>";  
        echo "<tr id='idNo' ><td style='width: 225px;'>رقم جواز السفر البحري:</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' name='trnPassportNo'";
        if(isset($trnPassportNo)){
          echo " value='$trnPassportNo'";
        }
        echo " placeholder='رقم جواز السفر البحري'>";
        echo "</div></td></tr>"; 
        echo "<tr id='idNo' ><td style='width: 225px;'>الوظيفة الحالية:</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' name='trnjobName'";
        if(isset($trnjobName)){
          echo " value='$trnjobName'";
        }
        echo " placeholder='الوظيفة الحالية'>";
        echo "</div></td></tr>";  
        echo "<tr id='idNo' ><td style='width: 225px;'>المؤهلات العلمية:</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' name='trncrtName'";
        if(isset($trncrtName)){
          echo " value='$trncrtName'";
        }
        echo " placeholder='المؤهلات العلمية'>";
        echo "</div></td></tr>";  
        echo "<tr id='idNo'><td style='width: 225px;' valign='top'>الدورات الحاصل عليها:</td><td colspan='2'>";
        if(isset($TrnNo)){
          if(count($crsList) > 0){
            foreach($crsList as $value){
              echo "$value<br>";
            }
          }  
        }
        echo "<div class='input-container'>";
        echo "<textarea class='input-field' rows='5' name='TrnCopurses'>";
        echo "</textarea>";
        echo "</div></td></tr>";  
  
        echo "</table>";
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        if($mode=='regDetails'){
          echo "<input type='hidden' name='TemptrnNationality' value='$TemptrnNationality'>";
          echo "<input type='hidden' name='TempTrnIdNo' value='$TempTrnIdNo'>";
        }
        if(isset($TrnNo)){
          echo "<input type='hidden' name='TrnNo' value='$TrnNo'>";
        }
        if($mode=='regDetails'){
          $cancelMode="";
          $saveMode="saveRegDetails";
        }else{
          $cancelMode="showTList";
          $saveMode="saveEditDetails";
        }
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$saveMode'>تسجيل</button> <button type='submit' class='cnlBtn' name='mode' value='$cancelMode'>$cancelLabel</button></div>";
        echo "</form>";
      }
    }

//*****************************************************************************************
//register form
//*****************************************************************************************
    if($mode=='register'){
      $showList=false;
      echo "<form method='post' style='max-width:800px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>تسجيل مشارك</h2>";
      echo "<div class='error'><p>$errorMessage</p></div>";
      echo "<table width='100%'>";

      $countries=array();
      $q="SELECT cntId,cntArabName FROM Countries ORDER BY cntNumCode,cntArabName";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value){
            $$key=$value;
          }
          $countries[$cntId]=$cntArabName;
        }
      }
      
      echo "<tr><td style='width: 225px;'>الجنسية:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' name='TemptrnNationality' onchange='setNextField(this.value);'><option value='-1'>حدد الجنسية</option>";
      foreach($countries as $key => $value){
        echo "<option value='$key'";
        if(isset($TemptrnNationality)){
          if($TemptrnNationality == $key){
            echo " selected";
          }
        }
        echo ">$value</option>";
      }
      echo "</slect>";
      echo "</div></td></tr>";  

      echo "<tr><td style='width: 225px;'>الرقم القومي/رقم الهوية:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' name='TempTrnIdNo' placeholder='الرقم القومي/رقم الهوية'";
      if(isset($TempTrnIdNo)){
        echo " value='$TempTrnIdNo'";
      }
      echo ">";
      echo "</div></td></tr>";    
      echo "</table>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='regDetails'>تسجيل</button> <button type='submit' class='cnlBtn' name='mode' value=''>$cancelLabel</button></div>";
      echo "</form>";
    }

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
          echo "<input type='hidden' name='mode' value='register'>";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          echo "<button type='submit' class='addBtn' style='width: 150px;'>تسجبل</button> ";
          echo "<button type='button' class='cnlBtn' style='width: 150px;' onclick='window.location=\"unplannedCourses.php\"'>العودة</button>";
          echo "</form>";
          echo "<br>";
          echo "</div><br>";
        }
        //filter list form 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table id='masterTable'>";
        echo "<tr class='header'>";
        echo "<th>المتدرب</th><th style='width:600px;text-align: center;'></th></tr>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          echo "<tr><td>$TrnName</td>";
          echo "<td style='text-align: right;'><form method='post'>";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          echo "<input type='hidden' name='TrnNo' value='$TrnNo'>";
          if(isset($userPerms['UnplannedUrRegisterTrainee'])){
            echo "<button type='submit' class='regBtn' name='mode' value='unregister'>الغاء التسجيل</button> ";
          }
          if(isset($userPerms['UnplannedShowTraineeInfo'])){
            echo "<button type='submit' class='infBtn' name='mode' value='trnInfo'>بيان مشارك</button> ";
          }
          if(isset($userPerms['UnplannedPrintInvoice'])){
            echo "<button type='submit' class='invoiceBtn' name='mode' value='printInvoice'>الحافظة</button> ";
          }
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

      $q="select CoursId,CrsName,CoursBulletin,CoursStatus,ifnull(count(crstrnid),0) as trnCount,CrsProgram,dist_name,CoursFromPln from CoursesGuide inner join Courses on CoursCrsId=CrsId LEFT JOIN coursetrainees ON CoursId=crstrncourse INNER JOIN districts ON dist_id=CoursArea GROUP BY CoursId having CoursType=2 and CoursStatus=0 order by CrsName,CoursId";
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
          echo "<tr><td>$CrsName - $CoursBulletin - $dist_name - $CoursFromPln (عدد المتدربين: $trnCount)</td>";
          echo "<td style='text-align: right;'><form method='post'>";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          if($CoursStatus == 0){
            if(isset($userPerms['UnplannedRegisterTrainee'])){
              echo "<button type='submit' class='regBtn' name='mode' value='register'>تسجيل</button> ";
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
