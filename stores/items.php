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
$pageTitle="اﻷصناف";
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
$showList=true;
$logged=false;
if(!isset($mode)){
  $mode=0;
}
if(!isset($errorMessage)){
  $errorMessage="";
}
if(isset($_SESSION['__uid'])){
  $__uid=$_SESSION['__uid'];
  $fileName="items.php";
  $mnuId=getCommandMenuId($fileName);
  if(checkUserMenuItem($__uid,$mnuId)){
    $logged=true;
  }
  echo "$__uid";
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
    echo ".error{color: red; font-size: small;text-align:center;}";
    echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";    
    echo "</style>";
	  echo "</head>";
	  echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>$pageTitle</h2>";

    if($mode==5){
      //حفظ new
      if($itmdesc==""){
        $errorMessage.="لا بد من ادخال اسم الصنف<br>";
        $mode=1;
      }
      if($mode==5){
        $q="insert into items(itmdesc) values(?)";
        if($stmt = mysqli_prepare($dbc, $q)){
          mysqli_stmt_bind_param($stmt, "s", $itmdesc);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
        }
      }
    }

    if($mode==1){
      $showList=false;
      echo "<form method='post' style='max-width:800px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>جديد</h2>";
      echo "<table width='100%'>";
      echo "<tr><td style='width: 225px;'>اسم الصنف:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='اسم الصنف' name='itmdesc'";
      if(isset($itmdesc))
        echo " value='$itmdesc'";
      echo ">";
      echo "</div></td></tr>";
      echo "</table>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='5'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value='0'>تراجع</button></div>";
      echo "</form>";
      echo "<div class='error'><p>$errorMessage</p></div>";
    }

    if($mode==6){
      //save edited value
      if($itmdesc==""){
        $errorMessage.="لا بد من ادخال اسم الصنف<br>";
        $mode=2;
      }
      if($mode==6){
        $q="update items set itmdesc=? where itmid=?";
        if ($stmt = mysqli_prepare($dbc, $q)){
          mysqli_stmt_bind_param($stmt, "si", $itmdesc,$itmid);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
        }
      }
    }

    if($mode==2){
      $showList=false;
      if($errorMessage ==""){
        $q="select itmdesc from items where itmid=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          mysqli_stmt_bind_param($stmt, "i", $itmid);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_bind_result($stmt, $itmdesc);
          mysqli_stmt_fetch($stmt);
          mysqli_stmt_close($stmt);
        }  
      }
      echo "<form method='post' style='max-width:800px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>تعديل</h2>";
      echo "<table width='100%'>";
      echo "<tr><td style='width: 225px;'> اسم الصنف:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='اسم الصنف' name='itmdesc'";
      if(isset($itmdesc))
        echo " value='$itmdesc'";
      echo ">";
      echo "</div></td></tr>";
      echo "</table>";
      echo "<input type='hidden' name='itmid' value='$itmid'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='6'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value='0'>تراجع</button></div>";
      echo "</form>";
      echo "<div class='error'><p>$errorMessage</p></div>";
    }

    if($mode==4){
      $showList=false;
      $q="select itmdesc from items where itmid=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $itmid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $itmdesc);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }
      echo "<form method='post' style='max-width:500px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>الغاء</h2>";
      echo "<div style='text-align: center;direction:$__dir;'>سيتم الغاء $itmdesc هل انت متأكد?...</div><br>";
      echo "<input type='hidden' name='itmid' value='$itmid'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='7'>نعم</button> <button type='submit' class='cnlBtn' name='mode' value='0'>لا</button></div>";
      echo "</form>";
    }

    if($mode==7){
      $q="delete from items where itmid=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $itmid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
    }

    if($mode==3){
      //View form
      $showList=false;
      if($errorMessage ==""){
        $q="select itmdesc from items where itmid=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          mysqli_stmt_bind_param($stmt, "i", $itmid);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_bind_result($stmt, $itmdesc);
          mysqli_stmt_fetch($stmt);
          mysqli_stmt_close($stmt);
        }  
      }
      echo "<form method='post' style='max-width:800px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>عرض</h2>";
      echo "<table width='100%'>";
      echo "<tr><td style='width: 225px;'>اسم الصنف:</td><td>";
      echo "<div class='input-container'>$itmdesc</div></td></tr>";
      echo "</table>";
      echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode' value='0'>موافق</button></div>";
      echo "</form>";
    }

    if($showList){
      $q="select * from items order by itmdesc";
      $r=mysqli_query($dbc,$q);
      if($r){
        //new form
        echo "<div style='width: 110px; margin: auto;'><form method='post'>";
        echo "<input type='hidden' name='mode' value='1'>";
        echo "<button type='submit' class='addBtn'>إضافه</button>";
        echo "</form></div><br>";
        //filter list form 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table id='mainTable'>";
        echo "<tr class='header'>";
        echo "<th>اسم الصنف</th><th style='width:600px;text-align: center;'></th></tr>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          echo "<tr><td>$itmdesc</td>";
          echo "<td style='text-align: right;'><form method='post'>";
          echo "<input type='hidden' name='itmid' value='$itmid'>";
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
    echo "<script>
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
