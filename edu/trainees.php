<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$__systemRoot="../";                        //path to system root
include($__systemRoot."functions.php");     //system Functions don't remove
include('functions.php');                   //uncomment this line if you have a local functions file
include("appdb.php");                       //application database credentials
foreach($_POST as $key => $value){
  $$key=$value;
}
//***********************************************************************************************
// control variables
//***********************************************************************************************
$__dir="rtl";
$pageTitle="المتدربين";
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

//***********************************************************************************************
// check session
//***********************************************************************************************
if(isset($_SESSION['__uid'])){
  $__uid=$_SESSION['__uid'];
  $fileName=basename($_SERVER['PHP_SELF']);;
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

//***********************************************************************************************
// form html header
//***********************************************************************************************
    echo "<!DOCTYPE html><html><head>";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
    echo "<title>$pageTitle</title>";
    echo "<style>";
    echo "* { box-sizing: border-box;}";
    echo "#filterBox {background-image: url('images/searchicon.png');background-position: 10px 10px;background-repeat: no-repeat;width: 100%;font-size: 16px;padding: 12px 20px 12px 40px;border: 1px solid #ddd;margin-bottom: 12px;}";
    echo "#masterTable {border-collapse: collapse;width: 100%;border: 1px solid #ddd;font-size: 18px;direction: rtl;}";
    echo "#masterTable th, #masterTable td {text-align: left;padding: 12px;}";
    echo "#masterTable tr {border-bottom: 1px solid #ddd;}";
    echo "#masterTable tr.header, #masterTable tr:hover {background-color: #f1f1f1;}";
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
    echo ".input-field:focus {border: 2px solid dodgerblue;}";
    echo ".error{color: red; font-size: small;text-align:center;}";
    echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";    
    echo "</style>";
	  echo "</head>";
	  echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>$pageTitle</h2>";

//*****************************************************************************************
//Validation
//*****************************************************************************************
    if($mode=="addSave" or $mode=="editSave"){
      if($TrnName == ""){
        $errorMessage .= "لابد من إدخال الاسم<br>";
      }
      if($TrnCo == "-1"){
        $errorMessage .= "لابد من إدخال الشركة<br>";
      }
      if($TrnIdNo == ""){
        $errorMessage .= "لابد من إدخال رقم البطاقة<br>";
      }
      if($trnNationality == ""){
        $errorMessage .= "لابد من إدخال الجنسية<br>";
      }
      if($trnBDate == ""){
        $errorMessage .= "لابد من إدخال تاريخ الميلاد<br>";
      }
      if($trnBGovernrate == ""){
        $errorMessage .= "لابد من إدخال محل الميلاد<br>";
      }
      if($trnBState == ""){
        $errorMessage .= "لابد من إدخال دولة الميلاد<br>";
      }
      if($trnNationality == ""){
        $errorMessage .= "لابد من إدخال الجنسية<br>";
      }

    }

//*****************************************************************************************
//Add save (new record)
//*****************************************************************************************
    if($mode=="addSave"){
      //save new record
      $q="INSERT INTO Trainees (TrnName, trnEname, TrnAddress, TrnTels, TrnWhatsApp, TrnCo, TrnIdNo, trnBDate, trnBGovernrate, trnBState, trnNationality, trnPassportNo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt,"sssssissiiis", $TrnName, $trnEname, $TrnAddress, $TrnTels, $TrnWhatsApp, $TrnCo, $TrnIdNo, $trnBDate, $trnBGovernrate, $trnBState, $trnNationality, $trnPassportNo)){
          if(!mysqli_stmt_execute($stmt)){
            $errorMessage="Can not insert record";
          }
        }
        mysqli_stmt_close($stmt);
      }
    }

//*****************************************************************************************
//Edit Save (save edited record)
//*****************************************************************************************
    if($mode=="editSave"){
      //save edited value
      $q="UPDATE Trainees SET TrnName=?, trnEname=?, TrnAddress=?, TrnTels=?, TrnWhatsApp=?, TrnCo=?, TrnIdNo=?, trnBDate=?, trnBGovernrate=?, trnBState=?, trnNationality=?, trnPassportNo=? WHERE TrnNo=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt,"sssssissiiisi", $TrnName, $trnEname, $TrnAddress, $TrnTels, $TrnWhatsApp, $TrnCo, $TrnIdNo, $trnBDate, $trnBGovernrate, $trnBState, $trnNationality, $trnPassportNo,$TrnNo)){
          if(!mysqli_stmt_execute($stmt)){
            $errorMessage="Can not Update record";
          }
        }
        mysqli_stmt_close($stmt);
      }
    }

//*****************************************************************************************
//Read Record
//*****************************************************************************************
if($mode=="edit" or $mode=="view" or $mode=="deleteConfirm"){
  $q="SELECT TrnNo,TrnName,trnEname,TrnAddress,TrnTels,TrnWhatsApp,TrnCo,TrnIdNo,trnBDate,trnBGovernrate,trnBState,trnNationality,trnPassportNo FROM Trainees WHERE TrnNo=?";
  if ($stmt = mysqli_prepare($dbc, $q)) {
    if(mysqli_stmt_bind_param($stmt,"i", $TrnNo)){
      if(mysqli_stmt_execute($stmt)){
        if(mysqli_stmt_bind_result($stmt,$TrnNo,$TrnName,$trnEname,$TrnAddress,$TrnTels,$TrnWhatsApp,$TrnCo,$TrnIdNo,$trnBDate,$trnBGovernrate,$trnBState,$trnNationality,$trnPassportNo)){
          if(!mysqli_stmt_fetch($stmt)){
            $errorMessage="Can not read record";
          }
        }
      }
    }
    mysqli_stmt_close($stmt);
  }
}

//*****************************************************************************************
// add - Edit form
//*****************************************************************************************
    if($mode=="add" or $mode=="edit"){
      $showList=false;
      if($mode=="add"){
        $formLabel="متدرب جديد";
      }else{
        $formLabel="تعديل متدرب";
      }
      if(isset($errorMessage)){
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<form method='post' style='max-width:800px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>$formLabel</h2>";
      echo "<table width='100%'>";
      if($mode=="edit"){
        echo "<tr><td style='width: 225px;'>رقم المتدريب:</td><td><div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='رقم المتدرب' name='TrnNo' readonly";
        if(isset($TrnNo)){
          echo " value='$TrnNo'";
        } 
        echo "></div></td></tr>";    
      }

      echo "<tr><td style='width: 225px;'>اسم المتدريب (عريي):</td><td><div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='اسم المتدريب (عريي)' name='TrnName'";
      if(isset($TrnName)){
        echo " value='$TrnName'";
      } 
      echo "></div></td></tr>";  

      echo "<tr><td style='width: 225px;'>اسم المتدريب (إنجليزي):</td><td><div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='اسم المتدريب (انجليزي)' name='trnEname'";
      if(isset($trnEname)){
        echo " value='$trnEname'";
      } 
      echo "></div></td></tr>";  

      echo "<tr><td style='width: 225px;'>رقم البطاقة:</td><td><div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='رقم البطاقة' name='TrnIdNo'";
      if(isset($TrnIdNo)){
        echo " value='$TrnIdNo'";
      } 
      echo "></div></td></tr>";  

      echo "<tr><td style='width: 225px;'>رقم جواز السفر:</td><td><div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='رقم جواز السفر' name='trnPassportNo'";
      if(isset($trnPassportNo)){
        echo " value='$trnPassportNo'";
      } 
      echo "></div></td></tr>";  

      echo "<tr><td style='width: 225px;'>العنوان:</td><td><div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='العنوان' name='TrnAddress'";
      if(isset($TrnAddress)){
        echo " value='$TrnAddress'";
      } 
      echo "></div></td></tr>";  

      echo "<tr><td style='width: 225px;'>أرقام التليفون:</td><td><div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='أرقام التليفون' name='TrnTels'";
      if(isset($TrnTels)){
        echo " value='$TrnTels'";
      } 
      echo "></div></td></tr>";  

      echo "<tr><td style='width: 225px;'>رقم واتساب:</td><td><div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='رقم واتساب' name='TrnWhatsApp'";
      if(isset($TrnWhatsApp)){
        echo " value='$TrnWhatsApp'";
      } 
      echo "></div></td></tr>";  

      $comps=readCompanies($dbc);
      echo "<tr><td style='width: 225px;'>الشركة:</td><td><div class='input-container'>";
      echo "<select class='input-field' name='TrnCo'><option value='-1'>حدد الشركة</option>";
      foreach($comps as $key => $value){
        echo "<option value='$key'";
        if(isset($TrnCo)){
          if($key == $TrnCo){
            echo " selected";
          }
        }
        echo ">$value</option>";
      }
      echo "</select></div></td></tr>";  

      echo "<tr><td style='width: 225px;'>تاريخ الميلاد:</td><td><div class='input-container'>";
      echo "<input class='input-field' type='date' placeholder='تاريخ الميلاد' name='trnBDate'";
      if(isset($trnBDate)){
        echo " value='$trnBDate'";
      } 
      echo "></div></td></tr>";  
      
      $governs=readGovernrates($dbc);
      echo "<tr><td style='width: 225px;'>محل الميلاد:</td><td><div class='input-container'>";
      echo "<select class='input-field' name='trnBGovernrate'><option value='-1'>محل الميلاد</option>";
      foreach($governs as $key => $value){
        echo "<option value='$key'";
        if(isset($trnBGovernrate)){
          if($key == $trnBGovernrate){
            echo " selected";
          }
        }
        echo ">$value</option>";
      }
      echo "</select></div></td></tr>";  

      $Countries=readCoutries($dbc);
      echo "<tr><td style='width: 225px;'>دولة الميلاد:</td><td><div class='input-container'>";
      echo "<select class='input-field' name='trnBState'><option value='-1'>دولة الميلاد</option>";
      foreach($Countries as $key => $value){
        echo "<option value='$key'";
        if(isset($trnBState)){
          if($key == $trnBState){
            echo " selected";
          }
        }
        echo ">$value</option>";
      }
      echo "</select></div></td></tr>";  

      echo "<tr><td style='width: 225px;'>الجنسية:</td><td><div class='input-container'>";
      echo "<select class='input-field' name='trnNationality'><option value='-1'>الجنسية</option>";
      foreach($Countries as $key => $value){
        echo "<option value='$key'";
        if(isset($trnNationality)){
          if($key == $trnNationality){
            echo " selected";
          }
        }
        echo ">$value</option>";
      }
      echo "</select></div></td></tr>";  

      $newMode=$mode."Save";
      echo "</table>";

      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'> حفظ </button> <button type='button' class='cnlBtn' onclick='window.location=\"$fileName\";'> تراجع </button></div>";
      echo "</form>";
    }

//*****************************************************************************************
//Delete Confirmation
//*****************************************************************************************
    if($mode=="deleteConfirm"){
      $showList=false;
      echo "<form method='post' style='max-width:500px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>الغاء متدرب</h2>";
      echo "<div style='text-align: center;direction:$__dir;'> سيتم الغاء $TrnName ... ,<br> هل أنت متأكد؟</div><br>";
      echo "<input type='hidden' name='TrnNo' value='$TrnNo'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='delete'> نعم </button> <button type='submit' class='cnlBtn' name='mode' value=''> لا </button></div>";
      echo "</form>";
    }

//*****************************************************************************************
//Delete
//*****************************************************************************************
    if($mode=="delete"){
      $q="DELETE FROM Trainees WHERE TrnNo=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt, "i", $TrnNo)){
          if(!mysqli_stmt_execute($stmt)){
            $errorMessage="Error deleting record";
          }
        }
        mysqli_stmt_close($stmt);
      }
    }

//*****************************************************************************************
//View Record
//*****************************************************************************************
    if($mode=="view"){
      //View form
      $showList=false;
      $formLabel="بيانات متدرب ";
      echo "<form method='post' style='max-width:800px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>$formLabel</h2>";
      echo "<table width='100%'>";
      echo "<tr><td style='width: 225px;'>رقم المتدريب:</td><td><div class='input-container'> $TrnNo </div></td></tr>";    
      echo "<tr><td style='width: 225px;'>اسم المتدريب (عريي):</td><td><div class='input-container'>$TrnName</div></td></tr>";  
      echo "<tr><td style='width: 225px;'>اسم المتدريب (إنجليزي):</td><td><div class='input-container'>$trnEname</div></td></tr>";  
      echo "<tr><td style='width: 225px;'>رقم البطاقة:</td><td><div class='input-container'>$TrnIdNo</div></td></tr>";  
      echo "<tr><td style='width: 225px;'>رقم جواز السفر:</td><td><div class='input-container'>$trnPassportNo</div></td></tr>";  
      echo "<tr><td style='width: 225px;'>العنوان:</td><td><div class='input-container'>$TrnAddress</div></td></tr>";  
      echo "<tr><td style='width: 225px;'>أرقام التليفون:</td><td><div class='input-container'>$TrnTels</div></td></tr>";  
      echo "<tr><td style='width: 225px;'>رقم واتساب:</td><td><div class='input-container'>$TrnWhatsApp</div></td></tr>";  
      $comps=readCompanies($dbc);
      $comps[0]="";
      if(!isset($TrnCo)) $TrnCo=0;
      echo "<tr><td style='width: 225px;'>الشركة:</td><td><div class='input-container'>$comps[$TrnCo]</div></td></tr>";  
      echo "<tr><td style='width: 225px;'>تاريخ الميلاد:</td><td><div class='input-container'>$trnBDate</div></td></tr>";  
      $governs=readGovernrates($dbc);
      $governs[0]="";
      if(!isset($trnBGovernrate)) $trnBGovernrate=0;
      echo "<tr><td style='width: 225px;'>محل الميلاد:</td><td><div class='input-container'>$governs[$trnBGovernrate]</div></td></tr>";  
      $Countries=readCoutries($dbc);
      $Countries[0]="";
      if(!isset($trnBState)) $trnBState=0;
      if(!isset($trnNationality)) $trnNationality=0;
      echo "<tr><td style='width: 225px;'>دولة الميلاد:</td><td><div class='input-container'>$Countries[$trnBState]</div></td></tr>";
      echo "<tr><td style='width: 225px;'>الجنسية:</td><td><div class='input-container'>$Countries[$trnNationality]</div></td></tr>";  
      echo "</table>";
      echo "<div class='frmButtons'><button type='button' class='okBtn' onclick='window.location=\"$fileName\";'> حسنا </button></div>";
      echo "</form>";
    }

//*****************************************************************************************
//Record List
//*****************************************************************************************
    if($showList){
      if(isset($errorMessage)){
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      //new form
      echo "<div style='width: 110px; margin: auto;'><form method='post'>";
      echo "<input type='hidden' name='mode' value='add'>";
      echo "<button type='submit' class='addBtn'>إضافة متدريب</button>";
      echo "</form></div><br>";
      //filter list form 
      echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
      //list table
      echo "<table id='masterTable'>";
      echo "<tr class='header'><th style='text-align: right;'> م - اﻹسم - رقم البطاقة - رقم الجواز </th><th style='width:600px;text-align: center;'></th></tr>";
      $lineNo=1;
      $q="SELECT TrnNo,TrnName,TrnIdNo,trnPassportNo FROM Trainees ORDER BY TrnName";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value){
            $$key=$value;
          }
          echo "<tr><td style='text-align: right;'> $lineNo - $TrnName - [$TrnIdNo] - [$trnPassportNo]</td>";
          echo "<td style='text-align: left;'><form method='post'>";
          echo "<input type='hidden' name='TrnNo' value='$TrnNo'>";
          echo "<button type='submit' class='edtBtn' name='mode' value='edit'>تعديل</button> ";
          echo "<button type='submit' class='viewBtn' name='mode' value='view'>عرص</button> ";
          echo "<button type='submit' class='delBtn' name='mode' value='deleteConfirm'>الغاء</button> ";
          echo "</form></td></tr>";
          $lineNo++;
        }
        echo "</table>";
        mysqli_free_result($r);
      }
    }
    mysqli_close($dbc);

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
  }
}

//*****************************************************************************************
//log in error
//*****************************************************************************************
if(!$logged){
  include($__systemRoot."expired.php");
}
?>
