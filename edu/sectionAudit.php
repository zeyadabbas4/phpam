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
$logDir=$__systemRoot."/logs";
$logFile="edu.log";
$__dir="rtl";
$showList=true;
$logged=false;
$labelWidth="200px";
$formWidth="1000px";
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
    $user_year=getuseryear($__uid,$dbc);
    $pageTitle="الدورات المخططة لعام ".$user_year['Desc'];
    $pageSubTitle="مراجعة رئيس القسم";
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
    echo ".lecTable{border-style:solid;border-color:black;border-width:1px;border-spacing:0px;}" ;
    echo ".lecTable td{border-style:solid;border-color:black;border-width:1px;}";
    echo ".lecTable th{border-style:solid;border-color:black;border-width:1px;text-align:center;}";
    echo "</style>";
    echo "</head>";
	  echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>$pageTitle</h2>";
    echo "<br><h3 style='text-align: center;color: RoyalBlue;'>$pageSubTitle</h3>";

/***********************************************************************************
View Course Data
************************************************************************************/    
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
      
      $q="select CoursCrsId,CoursType,CoursBulletin,CoursFromPln,CouursToPln,CoursSupervisorInt,CoursSupervisorExt,CoursYear,CoursArea,CoursSection,CoursGenRept,CoursAreaRept,CoursLocation,CoursStatus from Courses where CoursId=?";
      if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $CoursCrsId,$CoursType,$CoursBulletin,$CoursFromPln,$CouursToPln,$CoursSupervisorInt,$CoursSupervisorExt,$CoursYear,$CoursArea,$CoursSection,$CoursGenRept,$CoursAreaRept,$CoursLocation,$CoursStatus)){
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
      echo "<tr><td>خلال الفترة من: $CoursFromPln الى: $CouursToPln </td><td>مكان اﻹنعقاد: ".$comps[$CoursLocation]." </td><td>اﻹشراف: ".$staff[$CoursSupervisorInt]."</td></tr>"; //الاشراف الخارجي." / ".$comps[$CoursSupervisorExt]
      echo "</table>";
      echo "<div style='text-align: right;font-weight: bold;text-decoration:underline;'>بيانات المحاضرين:</div>";
      echo "<table width='80%' class='lecTable'>";
      echo "<tr><th> م </th><th> اسم المحاضر </th><th> ساعات نظري </th><th> ساعات عملي </th><th> اجمالي المحاضر </th></tr>";
      $totThours=0;
      $totPhours=0;
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
      echo "<form method='post'>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      if($CoursStatus <= 3){
        echo "<button type='submit' name='mode' value='agreeConfirm' class='yesBtn'>موافق</button> "; 
        echo "<button type='submit' name='mode' value='rejectConfirm' class='noBtn'>غير موافق</button> ";  
      }
      echo "<button type='button' onclick='window.location=\"admissionAudit.php\";' class='okBtn'>إغلاق</button>";
      echo "</form>";
      echo "</center>";
      echo "</div>";
    }

/***********************************************************************************
Confirm new course status
************************************************************************************/
    if($mode=="agreeConfirm" or $mode=="rejectConfirm"){
      $showList=false;
      echo "<div style='width:800px,margin: auto;text-align: center;'>";
      echo "هل أنت متأكد...";
      echo "<center>";
      echo "<br><br>";
      $newMode=str_replace("Confirm","Save",$mode);
      echo "<form method='post'>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      echo "<button type='submit' name='mode' value='$newMode' class='yesBtn'>نعم</button> "; 
      echo "<button type='submit' name='mode' value='view' class='noBtn'>لا</button> ";
      echo "</form>";
      echo "</center>";
      echo "</div>";
    }

/***********************************************************************************
save and set new course status
************************************************************************************/
    if($mode=="agreeSave"){
      $newFlag=4;
      $mode="Save";
    }

    if($mode=="rejectSave"){
      $newFlag=2;
      $mode="Save";
    }

    if($mode=="Save"){
      $q="update Courses set CoursStatus=? where CoursId=?";
      if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "ii", $newFlag,$CoursId)){
          if(!mysqli_stmt_execute($stmt)){
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
/*********************************************************************************************************** */
//courses list
/*********************************************************************************************************** */
    if($showList){
      if($errorMessage != ""){
        echo "<div class='error'>$errorMessage<br><br></div>";
      }
      $q="SELECT SecId,SecName,SecHead FROM EduSections INNER JOIN staffUserMap ON SecHead=umpStaffId WHERE umpUserId=$__uid";
      $q="select umpStaffId from staffUserMap where umpUserId=$__uid";
      $r=mysqli_query($dbc,$q);
      if($r){
        if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          $umpStaffId=$row['umpStaffId'];
        }
      }
      $q="SELECT CoursId,CoursBulletin,CrsName,CoursStatus FROM Courses inner join CoursesGuide on CoursCrsId=CrsId inner join EduSections on CoursSection=SecId WHERE SecHead=$umpStaffId and CoursType=1 and CoursYear=".$user_year['Id']." and CoursStatus >2 order by CoursSection,CoursBulletin ";
      $r=mysqli_query($dbc,$q);
      if($r){
        //filter list form 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table id='mainTable'>";
        echo "<tr class='header'>";
        echo "<th>المنشور - اسم الدورة</th><th style='width:900px;text-align: center;'></th></tr>";
        $rowNo=0;
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value){
            $$key=$value;
          }
          if($CoursStatus > 3){
            $buttonCaption="عرض";
            $buttonClass="edtBtn";
          }else{
            $buttonCaption="مراجعة";
            $buttonClass="viewBtn";
          }
          $rowNo++;
          echo "<tr><td>$CoursBulletin - $CrsName</td>";
          echo "<td style='text-align: right;'><form method='post'>";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          echo "<button type='submit' class='$buttonClass' name='mode' value='view'>$buttonCaption</button> ";
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