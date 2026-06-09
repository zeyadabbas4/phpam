<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$__systemRoot="../";                        //path to system root
$__includeDir="../include";                //path to include directory
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
$formCourseStatusLevel=1;
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
    $trnYear=$user_year['Desc'];
    $YearId=$user_year['Id'];
    $pageTitle="متابعة الدورات لعام $trnYear - الدورات الحتمية";
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
    echo ".attBtn {background-color: DodgerBlue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".attBtn:hover {opacity: 1;}";
    echo ".filBtn {background-color: DarkViolet;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".filBtn:hover {opacity: 1;}";
    echo ".nflBtn {background-color: SteelBlue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".nflBtn:hover {opacity: 1;}";
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
/********************************************************************************************************************** */
//load ref data
/********************************************************************************************************************** */
    //include("readPrograms.php");
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

      $dists=array();
      $dists[0]=" ";
      $q="SELECT dist_id,dist_name FROM districts";
      if ($stmt = mysqli_prepare($dbc, $q)){
          if(mysqli_stmt_execute($stmt)){
              if(mysqli_stmt_bind_result($stmt, $dist_id,$dist_name)){
                  while(mysqli_stmt_fetch($stmt)){
                      $dists[$dist_id]=$dist_name;
                  }
              }
          }
          mysqli_stmt_close($stmt);
      }

    if($mode != "" and $mode != "saveStart" and $mode != "saveFinish"){
      if($showMode){
        showModeF($mode);
      }
      //read course data
      //include("readCourseData.php");
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
//sec:savelecs-savereg Data Validation for save lecs and save reg
//************************************************************************************************************* */
    if($mode=='savelecs' or $mode=='savereg'){
      //validation for new or old records
      if($mode == "savelecs"){
        if($fclhLecId=="0"){
          $errorMessage.="لا بد من اختيار المحاضر<br>";
        }
        if($fclhHoursP=="" and $fclhHoursT == ""){
          $errorMessage.="لا بد من ادخال عدد الساعات<br>";
        }  
        $q="select clhLecId,clhHoursT from CourseLecHours where clhCrsId=$CoursId";
        $r=mysqli_query($dbc,$q);
        if($r){
          while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            if($row['clhLecId']==$fclhLecId){
              $errorMessage="لقد تمت إضافة ". $lecs[$row['clhLecId']]." مسبقا بواقع ". $row['clhHours']." ساعات";
            }
          }
        }
      }
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
      include("$__includeDir/readCourseData.php");
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
      $CourseFlag=$formCourseStatusLevel+2;
      echo "<input type='hidden' name='CourseFlag' value='$CourseFlag'>";
      echo "<button type='submit' class='okBtn' name='mode' value='saveFinish'>حفظ</button> ";
      echo "<button type='submit' class='cnlBtn' name='mode' value=''>تراجع</button> ";
      echo "</form>";
      echo "</div>";
   }
//************************************************************************************************************* */
// Save Lecs
//************************************************************************************************************* */
if($mode=="savelecs"){
  if($showMode){
    showModeF($mode);
  }
  if($errorMessage==""){
    $q="INSERT INTO CourseLecHours(clhLecId,clhCrsId,clhHoursP,clhHoursT,clhNights,clhratio,clhDistrictFrom,clhDistrictTo,clhDays,clhReturn) VALUES (?,?,?,?,?,?,?,?,?,?)";
    if($stmt = mysqli_prepare($dbc, $q)){
      if(mysqli_stmt_bind_param($stmt, "iiddiiiiii", $fclhLecId,$CoursId,$fclhHoursP,$fclhHoursT,$fclhNights,$fclhratio,$fclhDistrictFrom,$fclhDistrictTo,$fclhDays,$fclhReturn)){
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
      if(mysqli_stmt_bind_param($stmt, "ii", $LecId,$CoursId)){
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
//sec:lecs Lecturers form
//************************************************************************************************************* */
   if($mode == "lecs"){
      if($showMode){
        showModeF($mode);
      }
      $showList=false;
      include("$__includeDir/readPrograms.php");
      include("$__includeDir/readCourseData.php");
      include("$__includeDir/readCourseLecs.php");
      include("$__includeDir/readLecNames.php");
      if($errorMessage != ""){
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<div style='max-width:1000px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>محاضري دورة ".$prgs[$CoursCrsId]["Name"]."<br>منشور رقم : $CoursBulletin</h2><hr>\r\n";
      echo "<table  width='100%'>";
      echo "<tr><td style='text-align: center;width: 50px;'> الملف </td>";
      echo "<td style='text-align: center;width: 250px;'> المحاضر </td>";  
      echo "<td style='text-align: center;width: 75px;'>نظري</td>";
      echo "<td style='text-align: center;width: 75px;'>عملي</td>";
      echo "<td style='text-align: center;width: 75px;'>الليالي</td>";
      echo "<td style='text-align: center;width: 75px;'>النسبة</td>";
      echo "<td style='text-align: center;width: 75px;'>من</td>";
      echo "<td style='text-align: center;width: 75px;'>الى</td>";
      echo "<td style='text-align: center;width: 75px;'>العدد</td>";
      echo "<td style='text-align: center;width: 75px;'>العودة</td>";
      echo "<td>&nbsp;</td></tr>\r\n";
      $totT=0;
      $totP=0;
      foreach($lecs as $key => $value){
        echo "<tr><td style='text-align:right;'>".$value['lecId']."</td>";
        echo "<td style='text-align: right;'>".$lecNames[$value['lecId']]."</td>";
        echo "<td style='text-align: center;'>".$value['thhrs']."</td>";
        echo "<td style='text-align: center;'>".$value['prhrs']."</td>";
        echo "<td style='text-align: center;'>".$value['Nights']."</td>";
        echo "<td style='text-align: center;'>".$value['ratio']."%</td>";
        echo "<td style='text-align: center;'>".$dists[$value['From']]."</td>";
        echo "<td style='text-align: center;'>".$dists[$value['To']]."</td>";
        echo "<td style='text-align: center;'>".$value['Days']." مرة</td>";
        $checked="";
        if($value['Return']==1){
          $checked=" checked";
        }
        echo "<td style='text-align: center;'><input type='checkbox' $checked disabled></td>";
        echo "<td><form method='post'><button type='submit' class='delBtn' name='mode' value='delLecs'>الغاء</button>";
        echo "<input type='hidden' name='CoursId' value='$CoursId'>";
        echo "<input type='hidden' name='LecId' value='".$value['lecId']."'>";
        echo "</form></td></tr>";
        $totT+=$value['thhrs'];
        $totP+=$value['prhrs'];
      }
      $tot=$totT+$totP;
      echo "<tr>";
      echo "<td width='250px' style='text-align:right;font-weight:bold;' colspan='2'>إجمالي الساعات</td>";
      echo "<td width='75px'style='text-align:center;font-weight:bold;'>".number_format($totT,2)."</td>";
      echo "<td width='75px'style='text-align:center;font-weight:bold;'>".number_format($totP,2)."</td>";
      echo "<td width='450px'style='text-align:right;' colspan='6'>&nbsp;</td>";
      echo "<td width='100px'style='text-align:center;font-weight:bold;'>".number_format($tot,2)."</td>";
      echo "</tr>";

      echo "<tr><td colspan='11'><form method='post'><table width='100%' border='1'>";
      echo "<tr>";
      echo "<td width='50'><input class='input-field' type='text' id='lecNo' onchange='selectLec();'></td>";
      echo "<td width='250px'><select class='input-field' name='fclhLecId' id='lecList'><option value='0'>حدد المحاضر</option>\r\n";
      foreach($lecNames as $key => $value){
        echo "<option value='$key'";
        if(isset($fclhLecId)){
          if($fclhLecId == $key){
            echo " selected";
          }
        }
        echo ">$value</option>\r\n";
      }
      echo "</select></td>\r\n";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input class='input-field' type='text' placeholder='نظري' name='fclhHoursT'";
      if(isset($fclhHoursT))
        echo " value='$fclhHoursT'";
      echo "></td>";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input class='input-field' type='text' placeholder='عملي' name='fclhHoursP'";
      if(isset($fclhHoursP))
        echo " value='$fclhHoursP'";
      echo "></td>\r\n";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input class='input-field' type='text' placeholder='الليالي' name='fclhNights'";
      if(isset($fclhNights))
        echo " value='$fclhNights'";
      echo "></td>\r\n";

      echo "<td width='80px'><select class='input-field' name='fclhratio'>";
      echo "<option value='0'>النسبة</option>";
      echo "<option value='100'";
      if(isset($fclhratio)){
        if($fclhratio == "100"){
          echo " selected";
        }
      }
      echo ">100%</option>";
      echo "<option value='50'";
      if(isset($fclhratio)){
        if($fclhratio == "50"){
          echo " selected";
        }
      }
      echo ">50%</option>";
      echo "</select></td>\r\n";


      echo "<td width='60px'><select class='input-field' name='fclhDistrictFrom'><option value='0'>من</option>\r\n";
      foreach($dists as $key => $value){
        echo "<option value='$key'";
        if(isset($fclhDistrictFrom)){
          if($fclhDistrictFrom == $key){
            echo " selected";
          }
        }
        echo ">$value</option>\r\n";
      }
      echo "</select></td>\r\n";
      
      echo "<td width='60px'><select class='input-field' name='fclhDistrictTo'><option value='0'>الى</option>\r\n";
      foreach($dists as $key => $value){
        echo "<option value='$key'";
        if(isset($fclhDistrictTo)){
          if($fclhDistrictTo == $key){
            echo " selected";
          }
        }
        echo ">$value</option>\r\n";
      }
      echo "</select></td>\r\n";

      echo "<td style='vertical-align: middle;' width='75px'>";
      echo "<input class='input-field' type='text' placeholder='العدد' name='fclhDays'";
      if(isset($fclhDays))
        echo " value='$fclhDays'";
      echo "></td>\r\n";

      echo "<td style='vertical-align: middle;' width='60px'>";
      echo "<input type='hidden' name='fclhReturn' value='0'>";
      echo "العودة:<input type='checkbox' name='fclhReturn' value='1'";
      if(isset($fclhReturn))
        if($fclhReturn == 1){
          echo " checked";
        }
      echo "></td>\r\n";          

      echo "<td style='text-align: center;vertical-align: middle;'><button type='submit' class='savBtn' name='mode' value='savelecs'> إضافة </button>\r\n";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "</td></tr></table></form>";
      
      echo "</td></tr></table>\r\n";

      echo "<div class='frmButtons'><br><button type='button' class='addBtn' name='mode' onclick='window.location=\"$fileName \"'> العودة </button></div>\r\n";
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
      include("$__includeDir/readCourseData.php");
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
    $showList=false;
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
    include("$__includeDir/courseDetails.php");
    echo "<br><br>";
  
    //بيانات المحاضرين
    //include("$__includeDir/lecturersTable.php");
    drawCourseLecturerTable($dbc,$CoursId,false);
    echo "<br><br>";

    //بيانات بدل السفر والانتقال
    //include("$__includeDir/travelTransportationTable.php");
    drawTravelTable($dbc,$CoursId);
    echo "<br><br>";

    //بيانات المتدربين  
    include("$__includeDir/traineesTable.php");
    echo "<br><br>";

    //بيانات المرفقات
    include("$__includeDir/attachmentTable.php");
    echo "<br><br>";

    echo "<button type='button' onclick='window.location=\"$fileName\";' class='okBtn'>إغلاق</button>";
    echo "</center>";
    echo "</div>";
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
      $q="SELECT CoursId,CrsName,CoursBulletin,CoursStatus,IFNULL(count(crstrnid),0) AS trnCount,CrsProgram,dist_name,CoursFromPln,CoursType,CoursYear FROM CoursesGuide INNER JOIN Courses ON CoursCrsId=CrsId LEFT JOIN coursetrainees ON CoursId=crstrncourse INNER JOIN districts ON dist_id=CoursArea GROUP BY CoursId HAVING CoursType=4 AND CoursYear=$YearId AND CoursStatus >= $formCourseStatusLevel-1 order by CoursStatus,CrsName,CoursId";
      $r=mysqli_query($dbc,$q);
      $status=array(""," disabled style='background-color: lightgrey;'");
      if($r){
        //filter list form 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث باسم الدورة او رقم المنشور..' title='بحث باسم الدورة او رقم المنشور'>";
        echo "<table id='mainTable'>";
        echo "<tr class='header'>";
        echo "<th>المنشور - اسم الدورة</th><th style='width:900px;text-align: center;'></th></tr>";
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
            $startEnabled=$status[1];
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
          echo "<button type='submit' class='attBtn' name='mode' value='attachment'$buttonStatus>مرفقات</button> ";
          echo "<button type='submit' class='grpBtn' name='mode' value='lecs'$buttonStatus>المحاضرين</button> ";
          echo "<button type='submit' class='edtBtn' name='mode' value='scores'$buttonStatus>النتيجة</button> ";
          echo "<button type='submit' class='genBtn' name='mode' value='attendance'$buttonStatus>المواظبة</button> ";
          echo "<button type='submit' class='startBtn' name='mode' value='start'$$startEnabled>إفتتاح الدورة</button> ";
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
