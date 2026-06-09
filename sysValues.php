<?php
//ini_set('display_errors',1);
//error_reporting(E_ALL);
session_start();
$logged=false;
if(isset($_SESSION['__uid'])){
  $__uid=$_SESSION['__uid'];
  if($__uid==0){
    $logged=true;
    $showList=true;
    foreach($_POST as $key => $value)
      $$key=$value;
    include ("functions.php");
    $dbc=sysdbConnect();
    if(!isset($mode))
      $mode=0;
    if(!isset($errorMessage))
      $errorMessage="";
    echo "<!DOCTYPE html><html><head>";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
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
    echo ".delBtn {background-color: darkred;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".delBtn:hover {opacity: 1;}";
    echo ".savBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".savBtn:hover {opacity: 1;}";
    echo ".cnlBtn {background-color: red;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".cnlBtn:hover {opacity: 1;}";
    echo ".okBtn {background-color: indigo;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".oklBtn:hover {opacity: 1;}";
    echo ".input-container {display: -ms-flexbox; /* IE10 */  display: flex;  width: 100%;  margin-bottom: 15px;}";
    echo ".input-field {  width: 100%;  padding: 10px;  outline: none;}";
    echo ".input-field:focus {border: 2px solid dodgerblue;}";
    echo ".error{color: red; font-size: small;text-align:center;}";
    echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";    
    echo "</style>";
	  echo "</head>";
	  echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>System Values</h2>";
    if($mode==4){
      //save new value
      if($sysvalname==""){
        $errorMessage.="Must set name for the system value<br>";
        $mode=1;
      }
      if($sysvalname==""){
        $errorMessage.="Must set a value for the system value<br>";
        $mode=1;
      }
      if($mode==4){
        $q="insert into sysvalues(svlName,svlValue) values(?,?)";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          mysqli_stmt_bind_param($stmt, "ss", $sysvalname,$sysvalvalue);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
        }
      }
    }
    if($mode==1){
      $showList=false;
      echo "<form method='post' style='max-width:500px;margin:auto'>";
      echo "<h2 style='text-align: center;'>Add new</h2>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='System Value Name' name='sysvalname'";
      if(isset($sysvalname))
        echo " value='$sysvalname'";
      echo ">";
      echo "</div>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='System Value' name='sysvalvalue'";
      if(isset($sysvalvalue))
        echo " value='$sysvalvalue'";
      echo ">";
      echo "</div>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='4'>Save</button> <button type='submit' class='cnlBtn' name='mode' value='0'>Cancel</button></div>";
      echo "</form>";
      echo "<div class='error'><p>$errorMessage</p></div>";
    }
    if($mode==5){
      //save new value
      if($sysvalname==""){
        $errorMessage.="Must set name for the system value<br>";
        $mode=2;
      }
      if($sysvalname==""){
        $errorMessage.="Must set a value for the system value<br>";
        $mode=2;
      }
      if($mode==5){
        $q="update sysvalues set svlName=?,svlValue=? where svlId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          mysqli_stmt_bind_param($stmt, "ssi", $sysvalname,$sysvalvalue,$sysvalid);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
        }
      }
    }
    if($mode==2){
      $showList=false;
      $q="select svlName,svlValue from sysvalues where svlId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $sysvalid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $sysvalname,$sysvalvalue);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }
      echo "<form method='post' style='max-width:500px;margin:auto'>";
      echo "<h2 style='text-align: center;'>Edit</h2>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='System Value Name' name='sysvalname'";
      if(isset($sysvalname))
        echo " value='$sysvalname'";
      echo ">";
      echo "</div>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='System Value' name='sysvalvalue'";
      if(isset($sysvalvalue))
        echo " value='$sysvalvalue'";
      echo ">";
      echo "</div>";
      echo "<input type='hidden' name='sysvalid' value='$sysvalid'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='5'>Save</button> <button type='submit' class='cnlBtn' name='mode' value='0'>Cancel</button></div>";
      echo "</form>";
      echo "<div class='error'><p>$errorMessage</p></div>";
    }
    if($mode==3){
      $showList=false;
      $q="select svlName,svlValue from sysvalues where svlId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $sysvalid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $sysvalname,$sysvalvalue);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }
      echo "<form method='post' style='max-width:500px;margin:auto'>";
      echo "<h2 style='text-align: center;'>Delete</h2>";
      echo "<div style='text-align: center;'>Are you sure you want to delete $sysvalname ?...</div><br>";
      echo "<input type='hidden' name='sysvalid' value='$sysvalid'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='6'>Yes</button> <button type='submit' class='cnlBtn' name='mode' value='0'>No</button></div>";
      echo "</form>";
    }
    if($mode==6){
      $q="delete from sysvalues where svlId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $sysvalid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
    }
    if($showList){
      $q="select * from sysvalues order by svlName";
      $r=mysqli_query($dbc,$q);
      if($r){
        echo "<div style='width: 110px; margin: auto;'><form method='post'><input type='hidden' name='mode' value='1'><button type='submit' class='addBtn'>Add new</button></form></div><br>";
        echo "<input type=\"text\" id=\"filterBox\" onkeyup=\"filterList()\" placeholder=\"Filter list..\" title=\"Type in a name\">";
        echo "<table id=\"mainTable\">";
        echo "<tr class=\"header\">";
        echo "<th>Name</th><th>Value</th><th style=\"width:300px;text-align: center;\"></th></tr>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          echo "<tr><td>$svlName</td><td>$svlValue</td>";
          echo "<td><form method='post'><input type='hidden' name='sysvalid' value='$svlId'><button type='submit' class='edtBtn' name='mode' value='2'>Edit</button> <button type='submit' class='delBtn' name='mode' value='3'>Delete</button></form></td>";
          echo "</tr>";
        }
        echo "</table>";
        mysqli_free_result($r);
      }
    }
    mysqli_close($dbc);
    echo "<script>";
    echo "function filterList() {";
    echo "  var input, filter, table, tr, td, i, txtValue;";
    echo "  input = document.getElementById(\"filterBox\");";
    echo "  filter = input.value.toUpperCase();";
    echo "  table = document.getElementById(\"mainTable\");";
    echo "  tr = table.getElementsByTagName(\"tr\");";
    echo "  for (i = 0; i < tr.length; i++) {";
    echo "    td = tr[i].getElementsByTagName(\"td\")[0];";
    echo "    if (td) {";
    echo "      txtValue = td.textContent || td.innerText;";
    echo "      if (txtValue.toUpperCase().indexOf(filter) > -1) {";
    echo "        tr[i].style.display = \"\";";
    echo "      } else {";
    echo "        tr[i].style.display = \"none\";";
    echo "      }";
    echo "    }";   
    echo "  }";
    echo "}";
    echo "</script>";
    echo "</body></html>";
  }
}
if(!$logged){
  include('expired.php');
}
?>