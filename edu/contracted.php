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
$__dir="rtl";
$pageTitle="الدورات التعاقدية";
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
$showList=true;
$logged=false;
$labelWidth="100px";
$formWidth="1000px";
if(!isset($mode)){
  $mode=1;
}
if(!isset($errorMessage)){
  $errorMessage="";
}
if(isset($_SESSION['__uid'])){
  $__uid=$_SESSION['__uid'];
  $fileName="contracted.php";
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
          function addrow(tpcdesc='',tpcthrs='',tpcphrs=''){
            var table=document.getElementById(\"subForm\");
            var row=table.insertRow();
            rows++;
            row.id=\"row\"+rows;
            var cell=row.insertCell(0);
            cell.innerHTML=\"<input class='input-field' type='text' placeholder='الموضوع' name='tpcdesc[\"+rows+\"]' value='\"+tpcdesc+\"'>\";
            var cell=row.insertCell(1);
            cell.innerHTML=\"<input class='input-field' type='text' placeholder='نظري' name='tpcthrs[\"+rows+\"]' value='\"+tpcthrs+\"'>\";
            var cell=row.insertCell(2);
            cell.innerHTML=\"<input class='input-field' type='text' placeholder='عملي' name='tpcphrs[\"+rows+\"]' value='\"+tpcphrs+\"'>\";
            var cell=row.insertCell(3);
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

    if($mode==5 or $mode==6){
      //validation for new or old records
      //verify master
      if($CrsName==""){
        $errorMessage.="لا بد من ادخال اسم البرنامج<br>";
      }
      if($CrsCode==""){
        $errorMessage.="لا بد من ادخال كود البرنامج<br>";
      }
      if($CrsDescription==""){
        $errorMessage.="لا بد من ادخال أهداف البرنامج<br>";
      }
      if($CrsPrerequisits==""){
        $errorMessage.="لا بد من ادخال شروط الالتحاق<br>";
      }
      if($CrsQualification==""){
        $errorMessage.="لا بد من ادخال المؤهلات<br>";
      }
      if($CrsTHours==""){
        $CrsTHours="0";
        if($CrsPHours==""){
          $CrsPHours="0";
          $errorMessage.="لا بد من ادخال الساعات النظرية أو العملية<br>";
        }
      }
      //verify detail
      if(isset($tpcdesc)){
        foreach($tpcdesc as $key => $value){
          if($tpcdesc[$key]==""){
            $errorMessage.="لا بد من ادخال اسم الموضوع  $key<br>";
          }
          if($tpcthrs[$key]==""){
            if($tpcphrs[$key]==""){
            $errorMessage.="لا بد من ادخال الساعات النظرية أو العملية لـ ".$tpcdesc[$key]."<br>";
            }
          }
        }  
      }
    }

    if($mode==5){
      //save new
      if($errorMessage == ""){
        $newid=-1;
        //save master
        $q="INSERT INTO CoursesGuide(CrsProgram, CrsCode, CrsName, CrsDescription, CrsQualification, CrsPrerequisits, CrsTHours, CrsPHours) VALUES (?,?,?,?,?,?,?,?);";
        if($stmt = mysqli_prepare($dbc, $q)){
          mysqli_stmt_bind_param($stmt, "isssssii", $CrsProgram,$CrsCode,$CrsName,$CrsDescription,$CrsQualification,$CrsPrerequisits,$CrsTHours,$CrsPHours);
          if(mysqli_stmt_execute($stmt)){
            $newid=mysqli_insert_id($dbc);
          }else{
            $errorMessage.= "Error saving data!...<br>";
            $errorMessage.= mysqli_error($dbc)."<br>";
          }
          mysqli_stmt_close($stmt);
        }
        if($newid != -1){
          $q="INSERT INTO CourseTopics(tpcid,tpccourse, tpcdesc, tpcthrs, tpcphrs) VALUES (?,?,?,?,?);";
          if($stmt = mysqli_prepare($dbc, $q)){
            foreach($tpcdesc as $key => $value){
              mysqli_stmt_bind_param($stmt, "iisii", $key,$newid,$tpcdesc[$key],$tpcthrs[$key],$tpcphrs[$key]);
              if(!mysqli_stmt_execute($stmt)){
                $errorMessage.= "Error saving data on line ".$key."! ...<br>";
                $errorMessage.= mysqli_error($dbc)."<br>";
              }
            }
            mysqli_stmt_close($stmt);
          }
        }
      }else{
        $mode=1;
      }
    }

    if($mode==1){
      $showList=false;
      if($errorMessage != ""){
        echo "<div class='error'><p>$errorMessage</p></div>\r\n";
      }
      echo "<form method='post' style='max-width:$formWidth;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>دورة جديدة</h2>";
      if($errorMessage != ""){
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<table width='100%'>";
      echo "<tr><td style='width: $labelWidth;'>البرنامج :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      if(!isset($CoursCrsId)){
        $CoursCrsId="";
      }
      dropdownlist($dbc,"SELECT CONCAT(CrsCode,' - ',CrsName) AS crsName,CrsId FROM CoursesGuide ORDER BY CrsProgram,CrsCode",'CrsId','crsName','CoursCrsId',$CoursCrsId,false,0,'100%');
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>كود البرنامج :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='كود البرنامج' name='CrsCode'";
      if(isset($CrsCode))
        echo " value='$CrsCode'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>القسم :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      if(!isset($CrsProgram)){
        $CrsProgram="";
      }
      dropdownlist($dbc,'select * from Programs order by PrgName','PrgId','PrgName','CrsProgram',$CrsProgram,false,0,'100%');
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>أهداف الدورة :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='أهداف الدورة' name='CrsDescription'";
      if(isset($CrsDescription))
        echo " value='$CrsDescription'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>شروط الالتحاق :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='شروط الالتحاق' name='CrsPrerequisits'";
      if(isset($CrsPrerequisits))
        echo " value='$CrsPrerequisits'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>المؤهلات :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='المؤهلات' name='CrsQualification'";
      if(isset($CrsQualification))
        echo " value='$CrsQualification'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>الساعات النظرية :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='الساعات النظرية' name='CrsTHours'";
      if(isset($CrsTHours))
        echo " value='$CrsTHours'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>الساعات العملية :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='الساعات العملية' name='CrsPHours'";
      if(isset($CrsPHours))
        echo " value='$CrsPHours'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>موضوعات الدورة :</td><td colspan='2'></td></tr>";
      echo "</table>";
      echo "<table width='100%' id='subForm'>";
      echo "<tr><td style='color:white;background-color:grey;width:60%;text-align:center;padding: 10px 10px;'>الموضوع</td><td style='color:white;background-color:grey;width:15%;text-align:center;'>نظرى</td>";
      echo "<td style='color:white;background-color:grey;width:15%;text-align:center;'>عملي</td><td style='width:10%;text-align:center;'><button type='button' class='savBtn' style='width:50px' onclick='addrow();'> + </button></td></tr>";
      echo "</table><br>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='5'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value='0'>تراجع</button></div>";
      echo "</form>";
      if($errorMessage != ""){
        if(isset($tpcdesc)){
          echo "<script>";
          foreach($tpcdesc as $key => $value){
            echo "addrow('".$tpcdesc[$key]."','".$tpcthrs[$key]."','".$tpcphrs[$key]."');";
          }
          echo "</script>";
        }
      }
    }

    if($mode==6){
      //save edited value
      if($errorMessage==""){
        $q="UPDATE CoursesGuide SET CrsProgram=?,CrsCode=?,CrsName=?,CrsDescription=?,CrsQualification=?,CrsPrerequisits=?,CrsTHours=?,CrsPHours=? WHERE CrsId=?";
        if ($stmt = mysqli_prepare($dbc, $q)){
          mysqli_stmt_bind_param($stmt, "isssssiii", $CrsProgram,$CrsCode,$CrsName,$CrsDescription,$CrsQualification,$CrsPrerequisits,$CrsTHours,$CrsPHours,$CrsId);
          if(!mysqli_stmt_execute($stmt)){
            $errorMessage.= "Error saving data! ...<br>";
            $errorMessage.= mysqli_error($dbc)."<br>";
          }
          mysqli_stmt_close($stmt);
        }else{
          echo mysqli_error($dbc);
        }
        $q="DELETE FROM CourseTopics WHERE tpccourse=?";
        if($stmt = mysqli_prepare($dbc, $q)){
          mysqli_stmt_bind_param($stmt, "i", $CrsId);
          if(!mysqli_stmt_execute($stmt)){
            $errorMessage.= "Error deleting old data!...<br>";
          }
          mysqli_stmt_close($stmt);
        }
        if(isset($tpcdesc) and is_array($tpcdesc)){
          $q="INSERT INTO CourseTopics(tpcid,tpccourse, tpcdesc, tpcthrs, tpcphrs) VALUES (?,?,?,?,?);";
          if($stmt = mysqli_prepare($dbc, $q)){
            foreach($tpcdesc as $key => $value){
              mysqli_stmt_bind_param($stmt, "iisii", $key,$CrsId,$tpcdesc[$key],$tpcthrs[$key],$tpcphrs[$key]);
              if(!mysqli_stmt_execute($stmt)){
                $errorMessage.= "Error saving data on line ".$key."! ...<br>";
                $errorMessage.= mysqli_error($dbc)."<br>";
              }
            }
            mysqli_stmt_close($stmt);
          }
        }
      }else{
        $mode=2;
      }
    }

    if($mode==2){
      $showList=false;
      if($errorMessage ==""){
        $q="SELECT CrsProgram,CrsCode,CrsName,CrsDescription,CrsQualification,CrsPrerequisits,CrsTHours,CrsPHours FROM CoursesGuide WHERE CrsId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          mysqli_stmt_bind_param($stmt, "i", $CrsId);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_bind_result($stmt, $CrsProgram,$CrsCode,$CrsName,$CrsDescription,$CrsQualification,$CrsPrerequisits,$CrsTHours,$CrsPHours);
          mysqli_stmt_fetch($stmt);
          mysqli_stmt_close($stmt);
        }
      }
      echo "<form method='post' style='max-width:$formWidth;margin:auto;direction:$__dir;'>";
      echo "<input type='hidden' name='CrsId' value='$CrsId'>";
      echo "<h2 style='text-align: center;'>تعديل دورة</h2>";
      if($errorMessage != ""){
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<table width='100%'>";
      echo "<tr><td style='width: $labelWidth;'>اسم البرنامج :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='اسم البرنامج' name='CrsName'";
      if(isset($CrsName))
        echo " value='$CrsName'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>كود البرنامج :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='كود البرنامج' name='CrsCode'";
      if(isset($CrsCode))
        echo " value='$CrsCode'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>القسم :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      if(!isset($CrsProgram)){
        $CrsProgram="";
      }
      dropdownlist($dbc,'select * from Programs order by PrgName','PrgId','PrgName','CrsProgram',$CrsProgram,false,0,'100%');
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>أهداف الدورة :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='أهداف الدورة' name='CrsDescription'";
      if(isset($CrsDescription))
        echo " value='$CrsDescription'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>شروط الالتحاق :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='شروط الالتحاق' name='CrsPrerequisits'";
      if(isset($CrsPrerequisits))
        echo " value='$CrsPrerequisits'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>المؤهلات :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='المؤهلات' name='CrsQualification'";
      if(isset($CrsQualification))
        echo " value='$CrsQualification'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>الساعات النظرية :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='الساعات النظرية' name='CrsTHours'";
      if(isset($CrsTHours))
        echo " value='$CrsTHours'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>الساعات العملية :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='الساعات العملية' name='CrsPHours'";
      if(isset($CrsPHours))
        echo " value='$CrsPHours'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>موضوعات الدورة :</td><td colspan='2'></td></tr>";
      echo "</table>";
      echo "<table width='100%' id='subForm'>";
      echo "<tr><td style='color:white;background-color:grey;width:60%;text-align:center;padding: 10px 10px;'>الموضوع</td><td style='color:white;background-color:grey;width:15%;text-align:center;'>نظرى</td>";
      echo "<td style='color:white;background-color:grey;width:15%;text-align:center;'>عملي</td><td style='width:10%;text-align:center;'><button type='button' class='savBtn' style='width:50px' onclick='addrow();'> + </button></td></tr>";
      echo "</table><br>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='6'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value='0'>تراجع</button></div>";
      echo "</form>";
      //subform scripts
      echo "<script>\r\n";
      if($errorMessage ==""){
        $q="SELECT tpcdesc,tpcthrs,tpcphrs FROM CourseTopics WHERE tpccourse=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          mysqli_stmt_bind_param($stmt, "i", $CrsId);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_bind_result($stmt, $tpcdescs,$tpcthrss,$tpcphrss);
          while(mysqli_stmt_fetch($stmt)){
            $tpcdesc[]=$tpcdescs;
            $tpcthrs[]=$tpcthrss;
            $tpcphrs[]=$tpcphrss;
          }
          mysqli_stmt_close($stmt);
        }  
      }
      foreach($tpcdesc as $key => $value){
        echo "addrow('".$tpcdesc[$key]."','".$tpcthrs[$key]."','".$tpcphrs[$key]."');";
      }
      echo "</script>\r\n";
    }

    if($mode==4){
      $showList=false;
      $q="SELECT CrsCode,CrsName FROM CoursesGuide WHERE CrsId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $CrsId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $CrsCode,$CrsName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }
      echo "<form method='post' style='max-width:500px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>الغاء</h2>";
      echo "<div style='text-align: center;direction:$__dir;'>سيتم الغاء  $CrsCode - $CrsName هل انت متأكد?...</div><br>";
      echo "<input type='hidden' name='CrsId' value='$CrsId'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='7'>نعم</button> <button type='submit' class='cnlBtn' name='mode' value='0'>لا</button></div>";
      echo "</form>";
    }

    if($mode==7){
      $q="delete from CourseTopics where	tpccourse=?";
      if($stmt = mysqli_prepare($dbc, $q)){
        mysqli_stmt_bind_param($stmt, "i", $CrsId);
        if(!mysqli_stmt_execute($stmt)){
          $errorMessage.= "Error deleting data!...";
          $errorMessage.= mysqli_error($dbc)."<br>";
        }
        mysqli_stmt_close($stmt);
      }
      $q="delete from CoursesGuide where CrsId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $CrsId);
        if(!mysqli_stmt_execute($stmt)){
          $errorMessage.= "Error deleting data!...";
          $errorMessage.= mysqli_error($dbc)."<br>";
        }
        mysqli_stmt_close($stmt);
      }
    }

    if($mode==3){
      //View form
      $showList=false;
      $q="SELECT CrsCode,CrsName,CrsDescription,CrsQualification,CrsPrerequisits,CrsTHours,CrsPHours,PrgName FROM CoursesGuide INNER JOIN Programs ON CrsProgram = PrgId WHERE CrsId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $CrsId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $CrsCode,$CrsName,$CrsDescription,$CrsQualification,$CrsPrerequisits,$CrsTHours,$CrsPHours,$PrgName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }
      echo "<form method='post' style='max-width:$formWidth;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>عرض</h2>";
      echo "<table width='100%'>";
      echo "<tr><td style='width: $labelWidth;'>اسم الدورة :</td><td>";
      echo "<div class='input-container'>$CrsName</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>كود الدورة :</td><td>";
      echo "<div class='input-container'>$CrsCode</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>القسم :</td><td>";
      echo "<div class='input-container'>$PrgName</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>أهداف الدورة :</td><td>";
      echo "<div class='input-container'>$CrsDescription</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>شروط الالتحاق :</td><td>";
      echo "<div class='input-container'>$CrsPrerequisits</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>المؤهلات :</td><td>";
      echo "<div class='input-container'>$CrsQualification</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>الساعات النظرية :</td><td>";
      echo "<div class='input-container'>$CrsTHours</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>الساعات العملية :</td><td>";
      echo "<div class='input-container'>$CrsPHours</div></td></tr>";
      echo "<tr><td colspan='2'>موضوعات الدورة :</td></tr>";
      echo "</table>";
      //subform
      echo "<table width='100%' id='subForm'>";
      echo "<tr><td style='color:white;background-color:grey;width:60%;text-align:center;padding: 10px 10px;'>الموضوع</td><td style='color:white;background-color:grey;width:20%;text-align:center;'>نظرى</td>";
      echo "<td style='color:white;background-color:grey;width:20%;text-align:center;'>عملي</td></tr>";
      $q="SELECT tpcdesc,tpcthrs,tpcphrs FROM CourseTopics WHERE tpccourse=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $CrsId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $tpcdesc,$tpcthrs,$tpcphrs);
        $line=1;
        while(mysqli_stmt_fetch($stmt)){
          if($line % 2 != 0){
            $background="#ffffff";
          }else{
            $background="#cccccc";
          }
          echo "<tr><td style='color:black;background-color:$background;text-align:center;padding: 10px 10px;'>$tpcdesc</td>";
          echo "<td style='color:black;background-color:$background;text-align:center;'>$tpcthrs</td>";
          echo "<td style='color:black;background-color:$background;text-align:center;'>$tpcphrs</td></tr>";
          $line++;
        }
        mysqli_stmt_close($stmt);
      }  
      echo "</table><br>";
      echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode' value='0'>موافق</button></div>";
      echo "</form>";
    }

    if($showList){
      if($errorMessage != ""){
        echo "<div class='error'>$errorMessage<br><br></div>";
      }
      $q="select CrsId,CrsName,CrsCode from CoursesGuide order by CrsCode";
      $r=mysqli_query($dbc,$q);
      if($r){
        //new form
        echo "<div style='width: 110px; margin: auto;'><form method='post'>";
        echo "<input type='hidden' name='mode' value='1'>";
        echo "<button type='submit' class='addBtn'>جديد</button>";
        echo "</form></div><br>";
        //filter list form 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table id='mainTable'>";
        echo "<tr class='header'>";
        echo "<th>اسم البرنامج</th><th style='width:600px;text-align: center;'></th></tr>";
        $rowNo=0;
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value){
            $$key=$value;
          }
          $rowNo++;
          echo "<tr><td>$CrsCode - $CrsName</td>";
          echo "<td style='text-align: right;'><form method='post'>";
          echo "<input type='hidden' name='CrsId' value='$CrsId'>";
          echo "<button type='submit' class='edtBtn' name='mode' value='2'>تعديل</button> ";
          echo "<button type='submit' class='viewBtn' name='mode' value='3'>عرص</button> ";
          echo "<button type='submit' class='delBtn' name='mode' value='4'>الغاء</button> ";
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