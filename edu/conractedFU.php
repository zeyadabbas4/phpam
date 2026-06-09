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

if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
$__dir="rtl";
$showMode = false;
$showList=true;
$logged=false;
$labelWidth="100px";
$formWidth="1000px";
$formCourseStatusLevel=2;
if(!isset($mode)){
  $mode="";
}
if(!isset($errorMessage)){
  $errorMessage="";
}
if(!isset($msg)){
  $msg="";
}
if(isset($_SESSION['__uid'])){
  $__uid=$_SESSION['__uid'];
  $fileName = basename($_SERVER['PHP_SELF']);
  $mnuId=getCommandMenuId($fileName);
  if(checkUserMenuItem($__uid,$mnuId)){
    $logged=true;
  }
  if($logged){
    $logDir=$__systemRoot."/logs";
    $logFile="edu.log";
    $user_year=getuseryear($__uid,$dbc);
    $pageTitle="متابعة الدورات التعاقدية ".$user_year['Desc'];
    echo "<!DOCTYPE html><html><head>";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
    echo "<title>$pageTitle</title>";
    echo "<style>";
    echo "*{ box-sizing: border-box;}";
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
    echo ".okBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".okBtn:hover {opacity: 1;}";
    echo ".startBtn {background-color: #7FFF00;  color: #191970;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".startBtn:hover {opacity: 1;}";
    echo ".grpBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".grpBtn:hover {opacity: 1;}";
    echo ".arrowBtn {background-color: DarkOrchid;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 50px;  opacity: 0.9;}";
    echo ".arrowBtn:hover {opacity: 1;}";
    echo ".disBtn {background-color: grey;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".disBtn:hover {opacity: 1;}";
    echo ".input-container {display: -ms-flexbox; /* IE10 */  display: flex;  width: 100%;  margin-bottom: 15px;}";
    echo ".input-field { width: 100%;  padding: 10px;  outline: none;}";
    echo ".input-field:focus {border: 2px solid dodgerblue;}";
    echo ".error{color:red; font-weight:bold;text-align:center;direction:rtl;}";
    echo ".noError{color:green; font-weight:bold;text-align:center;direction:rtl;}";
    echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";    
    echo "</style>";
    echo "</head>";
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
          function addrow(conttitle='',contname='',contjob='',conttels='',contemail=''){
            var table=document.getElementById(\"subForm\");
            var row=table.insertRow();
            rows++;
            row.id=\"row\"+rows;
            var cell=row.insertCell(0);
            cell.innerHTML=\"<input class='input-field' type='text' placeholder='اللقب' name='conttitle[\"+rows+\"]' value='\"+conttitle+\"'>\";
            var cell=row.insertCell(1);
            cell.innerHTML=\"<input class='input-field' type='text' placeholder='الاسم' name='contname[\"+rows+\"]' value='\"+contname+\"'>\";
            var cell=row.insertCell(2);
            cell.innerHTML=\"<input class='input-field' type='text' placeholder='الوظيفة' name='contjob[\"+rows+\"]' value='\"+contjob+\"'>\";
            var cell=row.insertCell(3);
            cell.innerHTML=\"<input class='input-field' type='text' placeholder='التليفونات' name='conttels[\"+rows+\"]' value='\"+conttels+\"'>\";
            var cell=row.insertCell(4);
            cell.innerHTML=\"<input class='input-field' type='text' placeholder='البريد الالكتروني' name='contemail[\"+rows+\"]' value='\"+contemail+\"'>\";
            var cell=row.insertCell(5);
            cell.innerHTML=\"<button type='button' class='cnlBtn' style='width:50px;' onclick='delRow(this);'> - </button>\";
            cell.style=\"text-align:center\";
          }
          function delRow(row) {
            var i = row.parentNode.parentNode.rowIndex;
            document.getElementById(\"subForm\").deleteRow(i);
          } 
        </script>";
	  echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>$pageTitle</h2>";

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

    $lecs=array();
    $q="select staffid,staffname from staff where staffislec=1 and staffdeleted=0 order by staffname";
    $r=mysqli_query($dbc,$q);
    if($r){
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $lecs[$row['staffid']]=$row['staffname'];
      }
    }

      $companiesList=array();
      $q="select  cmpId,cmpName from companies";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach ($row as $key => $value){
            $$key = $value;
          }
          $companiesList [$cmpId] = $cmpName;
        }
        mysqli_free_result($r);
      }

 function showModeF($mode){ // function to print the current mode
     echo "<br>";
     echo "mode= ".$mode;
   }


    if($mode != "" and $mode != "saveStart" and $mode != "saveFinish"){
      if($showMode){
        showModeF($mode);
      }
      $q="select CoursCrsId,CoursType,CoursBulletin,CoursFromPln,CouursToPln,CoursFromAct,CoursToAct,CoursSupervisorInt,CoursSupervisorExt,CoursYear,CoursArea,CoursSection,CoursGenRept,CoursAreaRept,CoursLocation,CoursStatus from Courses where CoursId=?";
      if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $CoursCrsId,$CoursType,$CoursBulletin,$CoursFromPln,$CouursToPln,$CoursFromAct,$CoursToAct,$CoursSupervisorInt,$CoursSupervisorExt,$CoursYear,$CoursArea,$CoursSection,$CoursGenRept,$CoursAreaRept,$CoursLocation,$CoursStatus)){
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


//************************************************************************************************************* */
//Scores form
//************************************************************************************************************* */
 if($mode== "scores"){
     if($showMode){
        showModeF($mode);
       }
    $showList=false;
    echo "<center>";
    echo "<form method='post'>";
    echo "<br><button class='cnlBtn' type='button' onclick='window.location=\"$fileName\";'>تراجع</button>&nbsp;&nbsp;&nbsp;<button type='submit' class='savBtn' name='mode' value='scoreSubmit'>حفظ</button><br><br>";
    echo "<table style='text-align:center;border-spacing:3px;direction:rtl;'>";
    //query to get the record to be displayed
    $q="SELECT TrnName,TrnNo, crstrnresultexam FROM coursetrainees INNER JOIN Trainees ON TrnNo=crstrntrainee WHERE crstrncourse=?";
    $r=mysqli_query($dbc,$q);
      if ($stmt = mysqli_prepare($dbc, $q)){
          if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
            if(mysqli_stmt_execute($stmt)){
              if(mysqli_stmt_bind_result($stmt,$TrnName,$TrnNo,$crstrnresultexam))
                {
                echo "<tr style='font-size:medium;font-weight:bold;'>";
                echo "<td>&nbsp;مسلسل&nbsp;</td>";
                echo "<td>&nbsp;إسم المتدرب&nbsp;</td>";
                echo "<td>&nbsp;</td>";     //empty cells for action buttons
                echo "</tr>";
                $c=0;
                while(mysqli_stmt_fetch($stmt))
                {
                  //column headers - Add columns as needed
                  $ser=$c+1;
                  echo "<tr style='font-size:medium;font-weight:bold;'>";
                  echo "<td style='text-align:center;'>&nbsp; $ser &nbsp;</td>";
                  echo "<td style='text-align:right;'>&nbsp; $TrnName &nbsp;</td>";
                  echo "<td>";
                  echo "<input type='hidden' name='TrnNo[$c]' value='$TrnNo'>";
                  echo "<input type='hidden' name='CoursId' value='$CoursId'>";
                  echo "<input type='hidden' name='saverec[$c]' value='No' id='saverec$c'>";
                  echo "<input type='number' class='input-field' min='0' max='80' name='crstrnresultexam[$c]' id='atn$c' placeholder='درجه الامتحان' value='$crstrnresultexam' onchange='toggleSave(\"saverec$c\");'>";
                  echo "</td>\r\n";
                  echo "</tr>\r\n";
                  $c++;
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
    echo "</table>";
    //echo "<input type='hidden' name='mode' value='scoreSubmit'>";
    echo "<br><button class='cnlBtn' type='button' onclick='window.location=\"$fileName\";'>تراجع</button>&nbsp;&nbsp;&nbsp;<button type='submit' class='savBtn' name='mode' value='scoreSubmit'>حفظ</button><br><br>";
    echo "</form>";
    echo "</center>";
?>
<script>
function toggleSave(varId){
	document.getElementById(varId).value="Yes";
}
</script>
<?php

   }

//************************************************************************************************************* */
//Score submit
//************************************************************************************************************* */
 if($mode== "scoreSubmit")
   {
     if($showMode)
      {
        showModeF($mode);
      }
        $hits=0;
        $miss=0;
			$q="UPDATE coursetrainees set crstrnresultexam=? where crstrntrainee=? and crstrncourse=?";
			if ($stmt = mysqli_prepare($dbc, $q)){
				foreach($saverec as $key => $value){
					if($value=="Yes"){
						if(mysqli_stmt_bind_param($stmt, "iii", $crstrnresultexam[$key],$TrnNo[$key],$CoursId)){
							if(mysqli_stmt_execute($stmt)){
								$hits++;
							}else{
								$miss++;
							}
						}
					}
				}
				mysqli_stmt_close($stmt);
			}

    }

//************************************************************************************************************* */
//attendance form
//************************************************************************************************************* */
 if($mode== "attendance"){
      if($showMode){
        showModeF($mode);
      }
      $showList=false;
      echo "<center>";
      echo "<form method='post'>";
      echo "<br><button class='cnlBtn' type='button' onclick='window.location=\"$fileName\";'>تراجع</button>&nbsp;&nbsp;&nbsp;<button type='submit' class='savBtn' name='mode' value='attnSubmit'>حفظ</button><br><br>";
      echo "<table style='text-align:center;border-spacing:3px;direction:rtl;'>";
      //query to get the record to be displayed
      $q="SELECT TrnName,TrnNo,crstrnresultAttend  FROM coursetrainees INNER JOIN Trainees ON TrnNo=crstrntrainee WHERE crstrncourse=?";
      $r=mysqli_query($dbc,$q);
        if ($stmt = mysqli_prepare($dbc, $q)){
            if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
              if(mysqli_stmt_execute($stmt)){
                if(mysqli_stmt_bind_result($stmt,$TrnName,$TrnNo,$crstrnresultAttend))
                  {
                  echo "<tr style='font-size:medium;font-weight:bold;'>";
                  echo "<td>&nbsp;مسلسل&nbsp;</td>";
                  echo "<td>&nbsp;إسم المتدرب&nbsp;</td>";
                  echo "<td>&nbsp;</td>";     //empty cells for action buttons
                  echo "</tr>";
                  $c=0;
                  while(mysqli_stmt_fetch($stmt)){
                    //column headers - Add columns as needed
                    $ser=$c+1;
                    echo "<tr style='font-size:medium;font-weight:bold;'>";
                    echo "<td style='text-align:center;'>&nbsp; $ser &nbsp;</td>";
                    echo "<td style='text-align:right;'>&nbsp; $TrnName &nbsp;</td>";
                    echo "<td>";
                    echo "<input type='hidden' name='TrnNo[$c]' value='$TrnNo'>";
                    echo "<input type='hidden' name='CoursId' value='$CoursId'>";
                    echo "<input type='hidden' name='saverec[$c]' value='No' id='saverec$c'>";
                    echo "<input type='number' min='0' max='20' class='input-field' name='crstrnresultAttend[$c]' id='atn$c' placeholder='درجه الغياب' value='$crstrnresultAttend' onchange='toggleSave(\"saverec$c\");'>";
                    echo "</td>\r\n";
                    echo "</tr>\r\n";
                    $c++;
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
      echo "</table>";
      //echo "<input type='hidden' name='mode' value='saveAtn'>";
      echo "<br><button class='cnlBtn' type='button' onclick='window.location=\"$fileName\";'>تراجع</button>&nbsp;&nbsp;&nbsp;<button type='submit' class='savBtn' name='mode' value='attnSubmit'>حفظ</button><br><br>";
      echo "</form>";
      echo "</center>";
?>
<script>
function toggleSave(varId){
	document.getElementById(varId).value="Yes";
}
</script>
<?php
    }

//************************************************************************************************************* */
//attendance submit
//************************************************************************************************************* */
 if($mode== "attnSubmit"){
     if($showMode){
        showModeF($mode);
      }
      $hits=0;
      $miss=0;
			$q="UPDATE coursetrainees set crstrnresultAttend=? where crstrntrainee=? and crstrncourse=?";
			if ($stmt = mysqli_prepare($dbc, $q)){
				foreach($saverec as $key => $value){
					if($value=="Yes"){
						if(mysqli_stmt_bind_param($stmt, "iii", $crstrnresultAttend[$key],$TrnNo[$key],$CoursId)){
							if(mysqli_stmt_execute($stmt)){
								$hits++;
							}else{
								$miss++;
							}
						}
					}
				}
				mysqli_stmt_close($stmt);
			}

    }

//************************************************************************************************************* */
//Data Validation for save lecs and save reg
//************************************************************************************************************* */
    if($mode=='savelecs' or $mode=='savereg'){
      //validation for new or old records
      if($mode == "savelecs"){
        if($clhLecId=="0"){
          $errorMessage.="لا بد من اختيار المحاضر<br>";
        }
        if($clhHoursP=="" and $clhHoursT == ""){
          $errorMessage.="لا بد من ادخال عدد الساعات<br>";
        }  
        $q="select clhLecId,clhHoursT from CourseLecHours where clhCrsId=$CoursId";
        $r=mysqli_query($dbc,$q);
        if($r){
          while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            if($row['clhLecId']==$clhLecId){
              $errorMessage="لقد تمت إضافة ". $lecs[$row['clhLecId']]." مسبقا بواقع ". $row['clhHours']." ساعات";
            }
          }
        }
      }
    }



//*************************************************************************************** */
// Check Id
//*************************************************************************************** */
    if($mode== "checkID"){
      if($showMode){
        showModeF($mode);
      }
      //echo "i'm here2";
      //echo $regId;
      $showList=false;
      if($TrnIdNo==""){
        $mode = "register";
        $message = "لابد من ادخال رقم تحقيق الشخصيه";
      }else{
        $message = "";
        $q="SELECT TrnNo,TrnName,TrnAddress,TrnTels,TrnCo from Trainees where TrnIdNo=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if(mysqli_stmt_bind_param($stmt, "i", $TrnIdNo)){
            if(mysqli_stmt_execute($stmt)){
              if(mysqli_stmt_bind_result($stmt, $TrnNo,$TrnName,$TrnAdress,$TrnTels,$TrnCo)){
                if(mysqli_stmt_fetch($stmt)){
                  // data found
                  $FoundTrainee = true;
                  // echo $FoundTrainee;
                  //echo "Found";
                  $mode="showTrnB4Reg";
                }else{
                  // not found
                  $FoundTrainee = false;
                  //echo $FoundTrainee;
                  $mode = "addNewTraineeForm";
                }
              }
            }
            mysqli_stmt_close($stmt);
          }
        }
      }
    }

//*************************************************************************************** */
// add New Trainee Form
//*************************************************************************************** */
    if($mode== "addNewTraineeForm"){
      if($showMode){
        showModeF($mode);
      }
      if(!$FoundTrainee){
         //echo "i'm here2";
        echo "<form method='post' style='text-align: center;'>";
        echo "<h3> إدخال بيانات متدرب</h3>";
        echo"<table style='margin:auto;direction:rtl;width: 70%;'>";
        echo"<tr><td><label for='TrnName'>اسم المتدرب:</label></td>";
        echo "<td><div class='input-container'>";
        echo "<input type='text' class='input-field' name='TrnName' id='TrnName'>";
        echo "</div></td></tr>";
        echo "<tr><td>";
        echo "<label for='TrnAddress'> عنوان المتدرب:</label>";
        echo "</td>";
        echo "<td><div class='input-container'>";
        echo "<input type='text' class='input-field' name='TrnAddress' id='TrnAddress'>";
        echo "</div></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>";
        echo "<label for='TrnTels'> التليفون:</label>";
        echo "</td>";
        echo "<td><div class='input-container'>";
        echo "<input type='text' class='input-field' name='TrnTels' id='TrnTels'>";
        echo "</div></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>";
        echo "<label for='TrnCo'> الشركه:</label>";
        echo "</td>";
        echo "<td><div class='input-container'>";
        dropdownlista($companiesList,'TrnCo',false,'',false,0,0,'input-field');
        echo "</div></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>";
        echo "<label for='TrnIdNo'> البطاقه</label>";
        echo "</td>";
        echo "<td><div class='input-container'>";
        echo "<input type='text' class='input-field' name='TrnIdNo' id='TrnIdNo' value='$TrnIdNo' maxlength='20'>";
        echo "</div></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td style='text-align:center' colspan='2'>";        
        echo "<input type='hidden' name='trnNationality' value='63'>";
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<button type='submit' name='mode' value='savReg' class='addBtn'>تسجيل</button> ";
        echo "<button type='submit' name='mode' value='register' class='cnlBtn'>تراجع</button>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        echo "</form>";
      }
    }

//*************************************************************************************** */
// Saves the new Trainne to the Table Trainee SM=reg2Course
//*************************************************************************************** */
    if($mode== "savReg"){ 
      if($showMode){
        showModeF($mode);
      }
      $showList=false;
      $lastId=0;
      $q="INSERT INTO Trainees (TrnName,TrnAddress,TrnTels,TrnCo,TrnIdNo,trnNationality) VALUES (?,?,?,?,?,?)";
          if($stmt = mysqli_prepare($dbc, $q)){
            if(mysqli_stmt_bind_param($stmt, "sssisi", $TrnName,$TrnAddress,$TrnTels,$TrnCo,$TrnIdNo,$trnNationality)){
              if(!mysqli_stmt_execute($stmt)){
                $errorMessage.= "Error saving data [050103".$__uid.date("YmdHis")."]!...<br>";
                appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"050103".$__uid.date("YmdHis"));
              }
            }else{
              $errorMessage.= "Error saving data [050102]".$__uid.date("YmdHis")."]!...<br>";
              appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"050102".$__uid.date("YmdHis"));
            }
            echo mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
          }else{
            $errorMessage.= "Error saving data [050101]".$__uid.date("YmdHis")."]!...<br>";
            appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"050101".$__uid.date("YmdHis"));
          }
          $lastId=$dbc->insert_id;
          if($lastId > 0){
            $mode="reg2Course";
            $TrnNo=$lastId;
          }
    }
//*************************************************************************************** */
// it only saves to joint table SM=register
//*************************************************************************************** */
    if($mode== "reg2Course"){
      if($showMode){
        showModeF($mode);
      }

      $q="INSERT INTO coursetrainees (crstrntrainee,crstrncourse) VALUES (?,?)";
      if($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "ii", $TrnNo ,$CoursId)){
          if(!mysqli_stmt_execute($stmt)){
            $errorMessage.= "Error saving data [050103".$__uid.date("YmdHis")."]!...<br>";
            appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"050103".$__uid.date("YmdHis"));
          }
        }else{
          $errorMessage.= "Error saving data [050102]".$__uid.date("YmdHis")."]!...<br>";
          appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"050102".$__uid.date("YmdHis"));
        }
        echo mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
      }else{
        $errorMessage.= "Error saving data [050101]".$__uid.date("YmdHis")."]!...<br>";
        appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"050101".$__uid.date("YmdHis"));
      }
      $lastId=$dbc->insert_id;
      if($lastId > 0){
        $mode="register";
      }
    }

//********************************************************************************************************* */
// show Trainee information and show Register Button SM=reg2Course
//********************************************************************************************************* */
if($mode== "showTrnB4Reg"){ 
      if($showMode){
        showModeF($mode);
      }
      $trainees=array();
      // echo "in showTrnB4Reg";
      $showList=false;
      $q="SELECT TrnNo,TrnName,TrnAddress,TrnTels,TrnCo from Trainees where TrnIdNo='$TrnIdNo'";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach ($row as $key => $value){
              $$key = $value;
          }
          $trainees[$TrnNo] = $TrnNo;
          $trainees[$TrnName] = $TrnName;
          $trainees[$TrnAddress] = $TrnAddress;
          $trainees[$TrnTels] = $TrnTels;
          $trainees[$TrnCo] = $TrnCo;
          $trainees[$TrnIdNo] = $TrnIdNo;
        }
        mysqli_free_result($r);
      }
      // if same user is register before cancel the insert opertaion
      $closeReg = false;
      $q="SELECT cmpName,TrnNo FROM ((Courses inner join coursetrainees on crstrncourse=CoursId) inner join Trainees on TrnNo = crstrntrainee) inner join companies on cmpId = TrnCo where TrnNo=$TrnNo and crstrncourse=$CoursId";
      $r=mysqli_query($dbc,$q);
      if($r)
        if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          $closeReg = true;
        }
        mysqli_free_result($r);
        echo "<br>";
        echo "<form method='post' style='text-align: center;'>";
        echo "<h3> عرض بيانات متدرب</h3>";
        echo"<table style='margin:auto;direction:rtl'>";
        echo"<tr>";
        echo"<td>";
        echo "<label for='TrnName'>اسم المتدرب:</label>";
        echo"</td>";
        echo"<td><div class='input-container'>";
        echo "<input type='text' class='input-field' disabled name='TrnName' id='TrnName' value='$trainees[$TrnName]' maxlength='20' autofocus>";
        echo"</div></td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td>";
        echo "<label for='TrnAddress'> عنوان المتدرب:</label>";
        echo"</td>";
        echo"<td><div class='input-container'>";
        echo "<input type='text' class='input-field' disabled name='TrnAddress' id='TrnAddress' value='$trainees[$TrnAddress]' maxlength='20'>";
        echo"</div></td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td>";
        echo "<label for='TrnTels'> التليفون:</label>";
        echo"</td>";
        echo"<td><div class='input-container'>";
        echo "<input type='text' class='input-field' disabled  name='TrnTels' id='TrnTels' value='$trainees[$TrnTels]' maxlength='20'>";
        echo"</div></td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td>";
        echo "<label for='TrnCo'> الشركه:</label>";
        echo"</td>";
        echo"<td><div class='input-container'>";
        dropdownlista($companiesList,$trainees[$TrnCo],true,'',false,0,0,'input-field'); 
        echo"</div></td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td>";
        echo "<label for='TrnIdNo'> البطاقه</label>";
        echo"</td>";
        echo"<td><div class='input-container'>";
        echo "<input type='text' class='input-field' disabled name='TrnIdNo' id='TrnIdNo' value='$trainees[$TrnIdNo]' maxlength='20'>";
        echo"</div></td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td style='text-align:center' colspan='2'>";
        if ($closeReg){
           $regDis = " disabled";
           $btnClass = "disBtn";
           $messageFound = "المتدرب تم تسجيله سابقا";
        }
        else{
           $regDis = "";
           $btnClass = "addBtn";
           $messageFound = " ";
        }
        echo "<div style='color:red;text-align:center;direction:rtl;'>$messageFound</div>";
        echo "<button type='submit' name='mode' value='reg2Course' class='$btnClass'$regDis>تسجيل</button> ";
        echo "<button type='submit' name='mode' value='register' class='cnlBtn'>تراجع</button>";
        echo "<input type='hidden' name='TrnNo' value='$TrnNo'>";
        echo "<input type='hidden' name='CoursId' value='$CoursId'></form></td>";
        echo"</td>";
        echo"</tr>";
        echo"</table>";
      echo "</form>";
    }

//*************************************************************************************** */
// Remove Registration
//*************************************************************************************** */
    if($mode== "remReg"){
      if($showMode){
        showModeF($mode);
      }
      $showList=false;
      if($errorMessage==""){
        $clhCrsId=$CoursId;
        $q="delete from coursetrainees where crstrncourse=? and crstrntrainee=?";
        if($stmt = mysqli_prepare($dbc, $q)){
          if(mysqli_stmt_bind_param($stmt, "ii", $CoursId,$TrnNo)){
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
      $mode="register";
    }

//************************************************************************************************************* */
// register
//************************************************************************************************************* */
    if($mode == "register"){
      //hide course list
      $showList=false;
      if($showMode){
        showModeF($mode);
      }
      //get course name
      $q="SELECT CrsName FROM Courses inner join CoursesGuide on CoursCrsId= CrsId WHERE CoursId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $CrsName)){
              mysqli_stmt_fetch($stmt);
            }
          }
        }
        mysqli_stmt_close($stmt);
      }
      // register form
      echo "<div style='text-align:center;width:75%;Margin: auto;direction: $__dir;'>";
      echo "<h3> تسجيل المتدربين في دورة: $CrsName</h3>";
      echo "<form method='post'>";
      echo "<label for='regId'>أدخل رقم تحقيق الشخصية (الرقم القومي / رقم جواز السفر لﻷجانب):</label>";
      echo "<input type='text' class='input-field' style='width:40%;' name='TrnIdNo' id='TrnIdNo' maxlength='20' autofocus><br><br> ";
      echo "<button type='submit' name='mode' value='checkID' class='addBtn'>تسجيل</button> ";
      echo "<button type='button' class='cnlBtn' onclick= 'window.location=\"$fileName\"'>تراجع</button>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "</form>";
      if(!isset($message)){
        $message="";
      } 
      echo "<div style='color:red;text-align:center;direction:rtl;'>$message</div>";

      echo "<br><br><table style='width:75%;margin:auto;'>";
      $q="SELECT cmpName,TrnName,TrnNo,TrnIdNo FROM ((Courses inner join coursetrainees on crstrncourse=CoursId) inner join Trainees on TrnNo = crstrntrainee) inner join companies on cmpId = TrnCo WHERE CoursId=? order by cmpId";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $cmpName,$TrnName,$TrnNo,$TrnIdNo)){
              $count=1;
              while(mysqli_stmt_fetch($stmt)){
                echo "<tr><td> $count </td><td> $TrnName </td><td> $cmpName </td><td>$TrnNo</td><td>$TrnIdNo</td><td>";
                echo " <form id='delTrn$TrnNo' method='post' ><button type='button' onclick='delConfirmationF(\"delTrn$TrnNo\");'  class='delBtn'>حذف</button>";
                echo "<input type='hidden' name='CoursId' value='$CoursId'>";
                echo "<input type='hidden' name='mode' value='remReg'>";
                echo "<input type='hidden' name='TrnNo' value='$TrnNo'>";
                echo "</form></td>";
                $count++;

              }
            }
          }
        }
        mysqli_stmt_close($stmt);
      }
      echo "</table>";
      echo "</div>";

?>
<script>
    function delConfirmationF(formID)
      {
        var txt;
        var r = confirm('تاكيد حذف');
        if (r == true)
        {
          document.getElementById(formID).submit();

        }
      }
</script>
<?php

    }

//************************************************************************************************************* */
// Save Lecs
//************************************************************************************************************* */
    if($mode== "savelecs"){
      if($showMode){
        showModeF($mode);
      }
      if($errorMessage==""){
        $clhCrsId=$CoursId;
        $q="INSERT INTO CourseLecHours(clhLecId,clhCrsId,clhHoursP,clhHoursT) VALUES (?,?,?,?)";
        if($stmt = mysqli_prepare($dbc, $q)){
          if(mysqli_stmt_bind_param($stmt, "iiii", $clhLecId,$clhCrsId,$clhHoursP,$clhHoursT)){
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

      $mode="lecs";
    }

//************************************************************************************************************* */
// Delete Lecs
//************************************************************************************************************* */
    if($mode == "delLecs"){
      if($showMode){
        showModeF($mode);
      }
      if($errorMessage==""){
        $clhCrsId=$CoursId;
        $clhLecId=$LecId;
        $q="delete from CourseLecHours where clhLecId=? and clhCrsId=?";
        if($stmt = mysqli_prepare($dbc, $q)){
          if(mysqli_stmt_bind_param($stmt, "ii", $clhLecId,$clhCrsId)){
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
      $mode="lecs";
    }

//************************************************************************************************************* */
//Finish Course
//************************************************************************************************************* */
  if($mode== "saveFinish"){
    if($showMode)
      {
        showModeF($mode);
      }
      if(!isset($CoursToAct)){
        $errorMessage="لابد من ادخال التاريخ...";
        $mode="start";
      }else{
        $q="UPDATE Courses set CoursStatus=?,CoursToAct=? where CoursId=?";
        if ($stmt = mysqli_prepare($dbc, $q)){
          if(mysqli_stmt_bind_param($stmt, "isi",$CourseFlag,$CoursToAct,$CoursId)){
            if(mysqli_stmt_execute($stmt)){
              //$hits++;
            }else{
              $miss++;
            }
          }
        }
      }
    }

//************************************************************************************************************* */
//finish course
//************************************************************************************************************* */
    if($mode == "finish"){
      if($showMode){
        showModeF($mode);
      }
      $showList=false;
      echo "<div style='text-align: center;direction: rtl;'>";
      echo "إنهاء دورة \"$CrsName\" منشور رقم: $CoursBulletin ...<br><br>";
      echo "<form method='post'>";
      echo "<table width='100%'>";
      if(!isset($CoursToAct)){
        $CoursToAct=$CouursToPln;
      }
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>تاريخ نهاية الدورة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='date' name='CoursToAct'";
      if(isset($CoursToAct))
        echo " value='$CoursToAct'";
      echo ">";
      echo "</div></td></tr>";

      echo "</table>";
      echo "<br>";

      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      $CourseFlag=$formCourseStatusLevel+1;
      echo "<input type='hidden' name='CourseFlag' value='$CourseFlag'>";
      echo "<button type='submit' class='okBtn' name='mode' value='saveFinish'>حفظ</button> ";
      echo "<button type='submit' class='cnlBtn' name='mode' value=''>تراجع</button> ";
      echo "</form>";
      echo "</div>";
   }

//************************************************************************************************************* */
//Lecturers form
//************************************************************************************************************* */
   if($mode == "lecs"){
      if($showMode)
      {
        showModeF($mode);
      }
      $showList=false;
      $lecHrsP=array();
      $lecHrsT=array();
      $q="select clhLecId,clhHoursP, clhHoursT from CourseLecHours where clhCrsId=$CoursId";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          $lecHrsP[$row['clhLecId']]=$row['clhHoursP'];
          $lecHrsT[$row['clhLecId']]=$row['clhHoursT'];
        }
      }
      if($errorMessage != ""){
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<div style='max-width:800px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>محاضري دورة ".$prgs[$CoursCrsId]["Name"]."<br>منشور رقم : $CoursBulletin</h2><hr>\r\n";
      echo "<table  width='100%'>";
      echo "<tr><td style='text-align: center;width: 500px;'> المحاضر </td><td style='text-align: center;width: 100px;'>نظري</td><td style='text-align: center;width: 100px;'>عملي</td><td>&nbsp;</td></tr>\r\n";

      $totT=0;
      $totP=0;
      foreach($lecHrsT as $key => $value){
        echo "<tr><td width='600px' style='text-align:right;'>".$lecs[$key]."</td>";
        echo "<td width='100px'>$value</td><td width='100px'>".$lecHrsP[$key]."</td>";
        echo "<td><form method='post'><button type='submit' class='delBtn' name='mode' value='delLecs'>الغاء</button>";
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<input type='hidden' name='LecId' value='$key'>";
        echo "<input type='hidden' name='crscmpCompany' value='$key'>";
        echo "</form></td></tr>";
        $totT+=$value;
        $totP+=$lecHrsP[$key];
      }
      $tot=$totT+$totP;
      echo "<tr>";
      echo "<td width='600px' style='text-align:right;'>إجمالي الساعات</td>";
      echo "<td width='100px'style='text-align:right;'>".number_format($totT,2)."</td>";
      echo "<td width='100px'style='text-align:right;'>".number_format($totP,2)."</td>";
      echo "<td width='100px'style='text-align:right;'>".number_format($tot,2)."</td>";
      echo "</tr>";

      echo "<tr><td colspan='4'><form method='post'><table width='100%'>";
      echo "<tr><td width='600px'><select class='input-field' name='clhLecId'><option value='0'>حدد المحاضر</option>\r\n";
      foreach($lecs as $key => $value){
        echo "<option value='$key'";
        if(isset($clhLecId)){
          if($clhLecId == $key){
            echo " selected";
          }
        }
        echo ">$value</option>\r\n";
      }
      echo "</select></td>\r\n";
      echo "<td style='vertical-align: middle;' width='100px'>";
      echo "<input class='input-field' type='text' placeholder='نظري' name='clhHoursT'";
      if(isset($clhHoursT))
        echo " value='$clhHoursT'";
      echo "></td>";
      echo "<td style='vertical-align: middle;' width='100px'>";
      echo "<input class='input-field' type='text' placeholder='عملي' name='clhHoursP'";
      if(isset($clhHoursP))
        echo " value='$clhHoursP'";
      echo "></td>\r\n<td style='text-align: center;vertical-align: middle;'><button type='submit' class='savBtn' name='mode' value='savelecs'> إضافة </button>\r\n";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "</td></tr></table></form>";
      
      echo "</td></tr></table>\r\n";

      echo "<div class='frmButtons'><br><button type='button' class='addBtn' name='mode' onclick='window.location=\"plannedFU.php\"'> العودة </button></div>\r\n";
      echo "</div>";
    }

//************************************************************************************************************* */
// save course start
//************************************************************************************************************* */
  if($mode=="saveStart"){
    
    if(!isset($CoursFromAct)){
      $errorMessage="لابد من ادخال التاريخ...";
      $mode="start";
    }else{
      $q="update Courses set CoursFromAct=?,CoursStatus=? WHERE CoursId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt, "sii", $CoursFromAct,$formCourseStatusLevel,$CoursId)){
          if(mysqli_stmt_execute($stmt)){
            $msg="تم افتتاح الدورة";
          }
        }
        mysqli_stmt_close($stmt);
      }  
    }
  }

//************************************************************************************************************* */
// Start Course
//************************************************************************************************************* */
    if($mode == "start"){
      $showList=false;
      echo "<form method='post' style='max-width:$formWidth;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>إفتتاح دورة في خطة ".$user_year['Desc']."</h2>";
      if($errorMessage != ""){
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<table width='100%'>";
      if(!isset($CoursFromAct)){
        $CoursFromAct=$CoursFromPln;
      }
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>تاريخ بداية الدورة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='date' name='CoursFromAct'";
      if(isset($CoursFromAct))
        echo " value='$CoursFromAct'";
      echo ">";
      echo "</div></td></tr>";

      echo "</table>";
      echo "<br>";

      echo "<input type='hidden' name='CoursId' value='$CoursId'>";

      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='saveStart'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value=''>تراجع</button></div>";

      echo "</form>";

    }
//************************************************************************************ */
//View Course Data
//************************************************************************************ */    
  if($mode=='view' ){
    //load ref data
    $dists=array();
    $q="select dist_id,dist_name from districts";
    $r=mysqli_query($dbc,$q);
    if($r){
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $dists[$row['dist_id']]=$row['dist_name'];
      }
    }
    $staff=array();
    $q="select staffid,staffname from staff";
    $r=mysqli_query($dbc,$q);
    if($r){
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $staff[$row['staffid']]=$row['staffname'];
      }
    }
    $secs=array();
    $q="select PrgId,PrgName from Programs";
    $r=mysqli_query($dbc,$q);
    if($r){
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $secs[$row['PrgId']]=$row['PrgName'];
      }
    }

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

    $comps=array();
    $q="select cmpId,cmpName from companies order by cmpName";
    $r=mysqli_query($dbc,$q);
    if($r){
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $comps[$row['cmpId']]=$row['cmpName'];
      }
    }
    
    $lecs=array();
    $q="select clhLecId,clhHoursP, clhHoursT from CourseLecHours where clhCrsId=$CoursId";
    $r=mysqli_query($dbc,$q);
    if($r){
      $i=0;
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $lecs[$i]['lecId']=$row['clhLecId'];
        $lecs[$i]['thhrs']=$row['clhHoursT'];
        $lecs[$i]['prhrs']=$row['clhHoursP'];
        $i++;
      }
    }

    $trainees=array();
    $q="SELECT TrnName,TrnNo,crstrnresultAttend,crstrnresultexam,cmpName  FROM coursetrainees INNER JOIN Trainees ON TrnNo=crstrntrainee INNER JOIN companies on cmpId=TrnCo WHERE crstrncourse=$CoursId";
    $r=mysqli_query($dbc,$q);
    if($r){
      $i=0;
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $trainees[$i]['name']=$row['TrnName'];
        $trainees[$i]['attendance']=$row['crstrnresultAttend'];
        $trainees[$i]['score']=$row['crstrnresultexam'];
        $trainees[$i]['company']=$row['cmpName'];
        $i++;
      }
    }
    
    $q="select CoursCrsId,CoursType,CoursBulletin,CoursFromAct,CouursToAct,CoursSupervisorInt,CoursSupervisorExt,CoursYear,CoursArea,CoursSection,CoursGenRept,CoursAreaRept,CoursLocation,CoursStatus from Courses where CoursId=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
      if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
        if(mysqli_stmt_execute($stmt)){
          if(mysqli_stmt_bind_result($stmt, $CoursCrsId,$CoursType,$CoursBulletin,$CoursFromAct,$CouursToAct,$CoursSupervisorInt,$CoursSupervisorExt,$CoursYear,$CoursArea,$CoursSection,$CoursGenRept,$CoursAreaRept,$CoursLocation,$CoursStatus)){
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

    $showList=false;
    echo "<div style='direction: rtl; text-align: right; width:1100px;margin: auto;'>";
    echo "<center>";
    echo "<table width='80%'>";
    echo "<tr><td>اسم الدورة: </td><td>". $prgs[$CoursCrsId]["Name"]."</td><td>رقم المنشور: </td><td>$CoursBulletin</td><td>المنطقة: </td><td>".$dists[$CoursArea]."</td></tr>";
    echo "</table>";
    echo "<table width='100%'>";
    echo "<tr><td>خلال الفترة من: $CoursFromAct الى: $CoursToAct </td><td>مكان اﻹنعقاد: ".$comps[$CoursLocation]." </td><td>اﻹشراف: ".$staff[$CoursSupervisorInt]."</td></tr>";  // الاشراف الخارجي." / ".$comps[$CoursSupervisorExt]
    echo "</table>";
    echo "<div style='text-align: right;font-weight: bold;text-decoration:underline;'>بيانات المحاضرين:</div>";
    echo "<table width='80%' class='lecTable'>";
    echo "<tr><th> م </th><th> اسم المحاضر </th><th> ساعات نظري </th><th> ساعات عملي </th><th> اجمالي المحاضر </th></tr>";
    $totThours=0;
    $totPhours=0;
    $totCrsHours=0;
    foreach($lecs as $key => $value){
      $ser=$key+1;
      $lecName=$staff[$value['lecId']];
      $tHours=$value['thhrs'];
      $pHours=$value['prhrs'];
      $totLecHours=$tHours+$pHours;
      $totThours+=$tHours;
      $totPhours+=$pHours;
      $totCrsHours=$totThours+$totPhours;
      echo "<tr><td> $ser </td><td> $lecName </td><td> $tHours </td><td> $pHours </td><td> $totLecHours </td></tr>";
    }
    echo "<tr><td colspan='2'>اﻹجمالي</td><td>$totThours</td><td>$totPhours</td><td>$totCrsHours</td></tr>";
    echo "</table>";

    echo "<div style='text-align: right;font-weight: bold;text-decoration:underline;'>بيانات المتدربين:</div>";
    echo "<table width='80%' class='lecTable'>";
    echo "<tr><th> م </th><th> اسم المتدريب </th><th>الجهة</th><th> درجة اﻹختبار </th><th> درجة المواظبة </th><th> اجمالي الدرجة </th></tr>";
    foreach($trainees as $key => $value){
      $ser=$key+1;
      $Name=$value['name'];
      $attend=$value['attendance'];
      $score=$value['score'];
      $company=$value['company'];
      $totScore=$attend+$score;
      echo "<tr><td> $ser </td><td> $Name </td><td> $company </td><td> $score </td><td> $attend </td><td> $totScore </td></tr>";
    }
    echo "</table>";


    echo "<br><br>";
    echo "<button type='button' onclick='window.location=\"$fileName\";' class='okBtn'>إغلاق</button>";
    echo "</center>";
    echo "</div>";
  }

//************************************************************************************************************* */
//Show List
//************************************************************************************************************* */
    if($showList){
      if($errorMessage != ""){
        echo "<div class='error'>$errorMessage<br><br></div>";
      }
      if($msg != ""){
        echo "<div class='noError'>$msg<br><br></div>";
      }
      if($showMode){
        showModeF($mode);
      }
      $q="SELECT CoursId,CoursBulletin,CrsName,CoursStatus FROM Courses inner join CoursesGuide on CoursCrsId= CrsId WHERE CoursType=2 and ((CoursStatus >= $formCourseStatusLevel-1 and CoursYear=".$user_year['Id'].") OR CoursStatus <= $formCourseStatusLevel) order by CoursBulletin ";
      $r=mysqli_query($dbc,$q);
      $status=array(""," disabled style='background-color: lightgrey;'");
      if($r){
        //filter list form 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table id='mainTable'>";
        echo "<tr class='header'>";
        echo "<th>المنشور - اسم الدورة</th><th style='width:800px;text-align: center;'></th></tr>";
        $rowNo=0;
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value){
            $$key=$value;
          }
          if($CoursStatus == $formCourseStatusLevel-1){
            $startEnabled=$status[0];
            $viewEnabled=$status[1];
            $buttonStatus=$status[1];
          }elseif($CoursStatus == $formCourseStatusLevel){
            $startEnabled=$status[0];
            $viewEnabled=$status[0];
            $buttonStatus=$status[0];
          }else{
            $startEnabled=$status[1];
            $viewEnabled=$status[0];
            $buttonStatus=$status[1];
          }
          $rowNo++;
          echo "<tr><td>$CoursBulletin - $CrsName</td>";
          echo "<td style='text-align: right;'><form method='post'>";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          echo "<input type='hidden' name='CrsName' value='$CrsName'>";
          echo "<input type='hidden' name='CoursBulletin' value='$CoursBulletin'>";
          echo "<button type='submit' class='viewBtn' name='mode' value='view'$viewEnabled>عرض</button> ";
          echo "<button type='submit' class='delBtn' name='mode' value='finish'$buttonStatus>إنهاء الدورة</button> ";
          echo "<button type='submit' class='grpBtn' name='mode' value='lecs'$buttonStatus>المحاضرين</button> ";
          echo "<button type='submit' class='edtBtn' name='mode' value='scores'$buttonStatus>النتيجة</button> ";
          echo "<button type='submit' class='genBtn' name='mode' value='attendance'$buttonStatus>المواظبة</button> ";
          echo "<button type='submit' class='pwdBtn' name='mode' value='register'$buttonStatus>التسجيل</button> ";
          echo "<button type='submit' class='startBtn' name='mode' value='start'$startEnabled>إفتتاح الدورة</button> ";
          echo "</form></td></tr>";
        }
        echo "</table>";
        mysqli_free_result($r);
      }
    }
    mysqli_close($dbc);
    echo "</body></html>";
  }
}
if(!$logged){
  include($__systemRoot."expired.php");
}
?>
