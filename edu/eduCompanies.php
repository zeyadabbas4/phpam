<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$__systemRoot="../";                        //path to system root
include($__systemRoot."functions.php");     //system Functions don't remove
//include('functions.php');                 //uncomment this line if you have a local functions file
include("appdb.php");                       //application database credentials
foreach($_POST as $key => $value){
  $$key=$value;
}
$__dir="rtl";
$pageTitle="بيانات الشركات";
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
$showList=true;
$logged=false;
$labelWidth="100px";
$formWidth="1000px";
if(!isset($mode)){
  $mode=0;
}
if(!isset($errorMessage)){
  $errorMessage="";
}
if(isset($_SESSION['__uid'])){
  $__uid=$_SESSION['__uid'];
  $filename=basename(__FILE__);
  $mnuId=getCommandMenuId($filename);
  if(checkUserMenuItem($__uid,$mnuId)){
    $logged=true;
  }
  if($logged){
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

    if($mode==5 or $mode==6){
      //validation for new or old records
      //verify master
      if($cmpName==""){
        $errorMessage.="لا بد من ادخال اسم الشركة<br>";
      }
      //verify detail
      if(isset($conttitle)){
        foreach($conttitle as $key => $value){
          if($contname[$key]==""){
            $errorMessage.="لا بد من ادخال اسم مسئول الاتصال  $key<br>";
          }
          if($contjob[$key]==""){
            $errorMessage.="لا بد من ادخال الوظيفة لـ ".$contname[$key]."<br>";
          }
          /*if($conttels[$key]==""){
            $errorMessage.="لا بد من ادخال رقم التليفون لـ ".$contname[$key]."<br>";
          }
          if($contemail[$key]==""){
            $errorMessage.="لا بد من ادخال البريد الالكتروني لـ ".$contname[$key]."<br>";
          }*/
        }  
      }
    }

    if($mode==5){
      //save new
      if($errorMessage == ""){
        $newid=-1;
        //save master
        $q="INSERT INTO companies(cmpName,cmpSector,cmpAddress,cmpTels,cmpFax,cmpEmail,cmpSite,cmpNotes,cmpDistrict,cmpPlanCont) VALUES (?,?,?,?,?,?,?,?,?,?);";
        if($stmt = mysqli_prepare($dbc, $q)){
          mysqli_stmt_bind_param($stmt, "sissssssii", $cmpName,$cmpSector,$cmpAddress,$cmpTels,$cmpFax,$cmpEmail,$cmpSite,$cmpNotes,$cmpDistrict,$cmpPlanCont);
          if(mysqli_stmt_execute($stmt)){
            $newid=mysqli_insert_id($dbc);
          }else{
            $errorMessage.= "Error saving data!...<br>";
          }
          mysqli_stmt_close($stmt);
        }
        if($newid != -1){
          if(isset($conttitle)){
            foreach($conttitle as $key => $value){
              $q="INSERT INTO contacts(conttitle,contname,contjob,contcompany,conttels,contemail) VALUES (?,?,?,?,?,?);";
              if($stmt = mysqli_prepare($dbc, $q)){
                mysqli_stmt_bind_param($stmt, "sssiss", $conttitle[$key],$contname[$key],$contjob[$key],$newid,$conttels[$key],$contemail[$key]);
                if(!mysqli_stmt_execute($stmt)){
                  $errorMessage.= "Error saving data on line ".$contname[$key]."! ...<br>";
                }
                mysqli_stmt_close($stmt);
              }
            }  
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
      echo "<h2 style='text-align: center;'>شركة جديدة</h2>";
      if($errorMessage != ""){
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<table width='100%'>";
      echo "<tr><td style='width: $labelWidth;'>اسم الشركة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='اسم الشركة' name='cmpName'";
      if(isset($cmpName))
        echo " value='$cmpName'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>العنوان:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='العنوان' name='cmpAddress'";
      if(isset($cmpAddress))
        echo " value='$cmpAddress'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>القطاع :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      if(!isset($cmpSector)){
        $cmpSector="";
      }
      dropdownlist($dbc,'select * from Sectors order by Secname','Secid','Secname','cmpSector',$cmpSector,false,0,'100%');
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>المنطقة :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      if(!isset($cmpDistrict)){
        $cmpDistrict="";
      }
      dropdownlist($dbc,'select dist_id,dist_name from districts order by dist_name','dist_id','dist_name','cmpDistrict',false,$cmpDistrict,false,0,'100%');
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>التليفونات:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='التليفونات' name='cmpTels'";
      if(isset($cmpTels))
        echo " value='$cmpTels'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>الفاكس:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='الفاكس' name='cmpFax'";
      if(isset($cmpFax))
        echo " value='$cmpFax'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>البريد الالكتروني:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='البريد الالكتروني' name='cmpEmail'";
      if(isset($cmpEmail))
        echo " value='$cmpEmail'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>موقع الانترنت:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='موقع الانترنت' name='cmpSite'";
      if(isset($cmpSite))
        echo " value='$cmpSite'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>مساهمة بالخطة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input type='hidden' name='cmpPlanCont' value='0'>";
      echo "<input type='checkbox' name='cmpPlanCont' value='1'";
      if(isset($cmpPlanCont)){
        if($cmpPlanCont==1){
          echo " checked";
        }
      }
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>ملاحظات:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<textarea class='input-field' placeholder='ملاحظات' name='cmpNotes'>";
      if(isset($cmpNotes))
        echo $cmpNotes;
      echo "</textarea>";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>مسئولي الاتصال:</td><td colspan='2'></td></tr>";
      echo "</table>";
      echo "<table width='100%' id='subForm'>";
      echo "<tr><td style='color:white;background-color:grey;width:10%;text-align:center;'>اللقب</td><td style='color:white;background-color:grey;width:20%;text-align:center;'>الاسم</td>";
      echo "<td style='color:white;background-color:grey;width:20%;text-align:center;'>الوظيفة</td><td style='color:white;background-color:grey;width:20%;text-align:center;'>التليفونات</td>";
      echo "<td style='color:white;background-color:grey;width:20%;text-align:center;'>البريد الالكتروني</td><td style='width:10%;text-align:center;'><button type='button' class='savBtn' style='width:50px' onclick='addrow();'> + </button></td></tr>";
      echo "</table><br>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='5'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value='0'>تراجع</button></div>";
      echo "</form>";
      if($errorMessage != ""){
        if(isset($conttitle)){
          echo "<script>";
          foreach($conttitle as $key => $value){
            echo "addrow('".$conttitle[$key]."','".$contname[$key]."','".$contjob[$key]."','".$conttels[$key]."','".$contemail[$key]."');";
          }
          echo "</script>";
        }
      }
    }

    if($mode==6){
      //save edited value
      if($errorMessage==""){
        $q="UPDATE companies SET cmpName=?,cmpSector=?,cmpAddress=?,cmpTels=?,cmpFax=?,cmpEmail=?,cmpSite=?,cmpNotes=?,cmpDistrict=?,cmpPlanCont=? WHERE cmpId=?";
        if ($stmt = mysqli_prepare($dbc, $q)){
          mysqli_stmt_bind_param($stmt, "sissssssiii", $cmpName,$cmpSector,$cmpAddress,$cmpTels,$cmpFax,$cmpEmail,$cmpSite,$cmpNotes,$cmpDistrict,$cmpPlanCont,$cmpId);
          if(!mysqli_stmt_execute($stmt)){
            $errorMessage.= "Error saving data! ...<br>";
          }
          mysqli_stmt_close($stmt);
        }else{
          echo mysqli_error($dbc);
        }
        $q="delete from contacts where contcompany=?";
        if($stmt = mysqli_prepare($dbc, $q)){
          mysqli_stmt_bind_param($stmt, "i", $cmpId);
          if(!mysqli_stmt_execute($stmt)){
            $errorMessage.= "Error deleting old data!...<br>";
          }
          mysqli_stmt_close($stmt);
        }
        if(isset($conttitle)){
          foreach($conttitle as $key => $value){
            $q="INSERT INTO contacts(conttitle, contname, contjob, contcompany, conttels, contemail) VALUES (?,?,?,?,?,?)";
            if($stmt = mysqli_prepare($dbc, $q)){
              if(!mysqli_stmt_bind_param($stmt, "sssiss", $conttitle[$key], $contname[$key], $contjob[$key], $cmpId, $conttels[$key], $contemail[$key])){
                $errorMessage .= "Error saving data for ".$contname[$key]."! ...<br>";
                $errorMessage .= "<br>".mysqli_stmt_error($stmt);
              }
              if(!mysqli_stmt_execute($stmt)){
                $errorMessage .= "Error saving data for ".$contname[$key]."! ...<br>";
                $errorMessage .= "<br>".mysqli_stmt_error($stmt);
              }
              mysqli_stmt_close($stmt);
            }else{
              echo mysqli_error($dbc);
            }
          }  
        }
      }else{
        $mode=2;
      }
    }

    if($mode==2){
      $showList=false;
      if($errorMessage ==""){
        $q="SELECT cmpName,cmpSector,cmpAddress,cmpTels,cmpFax,cmpEmail,cmpSite,cmpNotes,cmpDistrict FROM companies WHERE cmpId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          mysqli_stmt_bind_param($stmt, "i", $cmpId);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_bind_result($stmt, $cmpName,$cmpSector,$cmpAddress,$cmpTels,$cmpFax,$cmpEmail,$cmpSite,$cmpNotes,$cmpDistrict);
          mysqli_stmt_fetch($stmt);
          mysqli_stmt_close($stmt);
        }  
      }
      echo "<form method='post' style='max-width:$formWidth;margin:auto;direction:$__dir;'>";
      echo "<input type='hidden' name='cmpId' value='$cmpId'>";
      echo "<h2 style='text-align: center;'>تعديل شركة</h2>";
      if($errorMessage != ""){
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<table width='100%'>";
      echo "<tr><td style='width: $labelWidth;'>اسم الشركة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='اسم الشركة' name='cmpName'";
      if(isset($cmpName))
        echo " value='$cmpName'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>العنوان:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='العنوان' name='cmpAddress'";
      if(isset($cmpAddress))
        echo " value='$cmpAddress'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>القطاع :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      if(!isset($cmpSector)){
        $cmpSector="";
      }
      dropdownlist($dbc,'select * from Sectors order by Secname','Secid','Secname','cmpSector',false,$cmpSector,false,0,'100%');
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>المنطقة :</td><td colspan='2'>";
      echo "<div class='input-container'>";
      if(!isset($cmpDistrict)){
        $cmpDistrict="";
      }
      dropdownlist($dbc,'select dist_id,dist_name from districts order by dist_name','dist_id','dist_name','cmpDistrict',false,$cmpDistrict,false,0,'100%');
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>التليفونات:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='التليفونات' name='cmpTels'";
      if(isset($cmpTels))
        echo " value='$cmpTels'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>الفاكس:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='الفاكس' name='cmpFax'";
      if(isset($cmpFax))
        echo " value='$cmpFax'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>البريد الالكتروني:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='البريد الالكتروني' name='cmpEmail'";
      if(isset($cmpEmail))
        echo " value='$cmpEmail'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>موقع الانترنت:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='موقع الانترنت' name='cmpSite'";
      if(isset($cmpSite))
        echo " value='$cmpSite'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>مساهمة بالخطة:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input type='hidden' name='cmpPlanCont' value='0'>";
      echo "<input type='checkbox' name='cmpPlanCont' value='1'";
      if(isset($cmpPlanCont)){
        if($cmpPlanCont==1){
          echo " checked";
        }
      }
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>ملاحظات:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<textarea class='input-field' placeholder='ملاحظات' name='cmpNotes'>";
      if(isset($cmpNotes))
        echo $cmpNotes;
      echo "</textarea>";
      echo "</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>مسئولي الاتصال:</td><td colspan='2'></td></tr>";
      echo "</table>";
      echo "<table width='100%' id='subForm'>";
      echo "<tr><td style='color:white;background-color:grey;width:10%;text-align:center;'>اللقب</td><td style='color:white;background-color:grey;width:20%;text-align:center;'>الاسم</td>";
      echo "<td style='color:white;background-color:grey;width:20%;text-align:center;'>الوظيفة</td><td style='color:white;background-color:grey;width:20%;text-align:center;'>التليفونات</td>";
      echo "<td style='color:white;background-color:grey;width:20%;text-align:center;'>البريد الالكتروني</td><td style='width:10%;text-align:center;'><button type='button' class='savBtn' style='width:50px' onclick='addrow();'> + </button></td></tr>";
      echo "</table><br>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='6'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value='0'>تراجع</button></div>";
      echo "</form>";
      //subform scripts
      echo "<script>\r\n";
      if($errorMessage ==""){
        $q="SELECT conttitle,contname,contjob,conttels,contemail FROM contacts WHERE contcompany=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          mysqli_stmt_bind_param($stmt, "i", $cmpId);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_bind_result($stmt, $conttitles,$contnames,$contjobs,$conttelss,$contemails);
          while(mysqli_stmt_fetch($stmt)){
            $conttitle[]=$conttitles;
            $contname[]=$contnames;
            $contjob[]=$contjobs;
            $conttels[]=$conttelss;
            $contemail[]=$contemails;
          }
          mysqli_stmt_close($stmt);
        }  
      }
      foreach($conttitle as $key => $value){
        echo "addrow('".$conttitle[$key]."','".$contname[$key]."','".$contjob[$key]."','".$conttels[$key]."','".$contemail[$key]."');";
      }
      echo "</script>\r\n";
    }

    if($mode==4){
      $showList=false;
      $q="select cmpName from companies where cmpId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $cmpId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $cmpName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }
      echo "<form method='post' style='max-width:500px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>الغاء</h2>";
      echo "<div style='text-align: center;direction:$__dir;'>سيتم الغاء  $cmpName هل انت متأكد?...</div><br>";
      echo "<input type='hidden' name='cmpId' value='$cmpId'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='7'>نعم</button> <button type='submit' class='cnlBtn' name='mode' value='0'>لا</button></div>";
      echo "</form>";
    }

    if($mode==7){
      $q="delete from companies where cmpId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $cmpId);
        if(!mysqli_stmt_execute($stmt)){
          $errorMessage.= "Error deleting data!...";
        }
        mysqli_stmt_close($stmt);
      }
      $q="delete from contacts where	contcompany=?";
      if($stmt = mysqli_prepare($dbc, $q)){
        mysqli_stmt_bind_param($stmt, "i", $cmpId);
        if(!mysqli_stmt_execute($stmt)){
          $errorMessage.= "Error deleting data!...";
        }
        mysqli_stmt_close($stmt);
      }
    }

    if($mode==3){
      //View form
      $showList=false;
      $cont=array("لا","نعم");
      if($errorMessage ==""){
        $q="SELECT cmpName, cmpFax, cmpAddress, cmpEmail, cmpTels, cmpSite, cmpNotes, Secname, dist_name, cmpPlanCont FROM companies INNER JOIN Sectors ON cmpSector = Secid LEFT JOIN districts ON dist_id=cmpDistrict WHERE cmpId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          mysqli_stmt_bind_param($stmt, "i", $cmpId);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_bind_result($stmt, $cmpName, $cmpFax, $cmpAddress, $cmpEmail, $cmpTels, $cmpSite, $cmpNotes, $Secname, $dist_name, $cmpPlanCont);
          mysqli_stmt_fetch($stmt);
          mysqli_stmt_close($stmt);
        }  
      }
      echo "<form method='post' style='max-width:$formWidth;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>عرض</h2>";
      echo "<table width='100%'>";
      echo "<tr><td style='width: $labelWidth;'>رقم الشركة:</td><td>";
      echo "<div class='input-container'>$cmpId</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>اسم الشركة:</td><td>";
      echo "<div class='input-container'>$cmpName</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>العنوان:</td><td>";
      echo "<div class='input-container'>$cmpAddress</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>القطاع</td><td>";
      echo "<div class='input-container'>$Secname</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>المنطقة</td><td>";
      echo "<div class='input-container'>$dist_name</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>التليفونات:</td><td>";
      echo "<div class='input-container'>$cmpTels</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>الفاكس:</td><td>";
      echo "<div class='input-container'>$cmpFax</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>البريد الالكتروني:</td><td>";
      echo "<div class='input-container'>$cmpEmail</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>موقع الانترنت:</td><td>";
      echo "<div class='input-container'>$cmpSite</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>موقع الانترنت:</td><td>";
      echo "<div class='input-container'>$cmpSite</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>مساهمة بالخطة:</td><td>";
      echo "<div class='input-container'>".$cont[$cmpPlanCont]."</div></td></tr>";
      echo "<tr><td style='width: $labelWidth;'>ملاحظات:</td><td>";
      echo "<div class='input-container'>$cmpNotes</div></td></tr>";
      echo "<tr><td colspan='2'>مسئولي الاتصال:</td></tr>";
      echo "</table>";
      //subform
      echo "<table width='100%' id='subForm'>";
      echo "<tr><td style='color:white;background-color:grey;width:10%;text-align:center;padding: 10px 10px;'>اللقب</td><td style='color:white;background-color:grey;width:30%;text-align:center;'>الاسم</td>";
      echo "<td style='color:white;background-color:grey;width:20%;text-align:center;'>الوظيفة</td><td style='color:white;background-color:grey;width:20%;text-align:center;'>التليفونات</td>";
      echo "<td style='color:white;background-color:grey;width:20%;text-align:center;'>البريد الالكتروني</td></tr>";
      $q="SELECT conttitle,contname,contjob,conttels,contemail FROM contacts WHERE contcompany=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $cmpId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $conttitle,$contname,$contjob,$conttels,$contemail);
        $line=1;
        while(mysqli_stmt_fetch($stmt)){
          if($line % 2 != 0){
            $background="#ffffff";
          }else{
            $background="#cccccc";
          }
          echo "<tr><td style='color:black;background-color:$background;text-align:center;padding: 10px 10px;'>$conttitle</td>";
          echo "<td style='color:black;background-color:$background;text-align:center;'>$contname</td>";
          echo "<td style='color:black;background-color:$background;text-align:center;'>$contjob</td>";
          echo "<td style='color:black;background-color:$background;text-align:center;'>$conttels</td>";
          echo "<td style='color:black;background-color:$background;text-align:center;'>$contemail</td></tr>";
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
      $q="select cmpId,cmpName,Secname from companies inner join Sectors on cmpSector = Secid order by cmpName";
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
        echo "<th>اسم الشركة</th><th style='width:600px;text-align: center;'></th></tr>";
        $rowNo=0;
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value){
            $$key=$value;
          }
          $rowNo++;
          echo "<tr><td>$rowNo - [$cmpId]-$cmpName - $Secname</td>";
          echo "<td style='text-align: right;'><form method='post'>";
          echo "<input type='hidden' name='cmpId' value='$cmpId'>";
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