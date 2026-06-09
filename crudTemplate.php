<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$logged=false;
if(true){
  //$__uid=$_SESSION['__uid'];
  if(true){
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

//*************************************************************************************************
//control variable section
//*************************************************************************************************
    $mainTable="reports";
    $mainTableKey="rptId";
    $mainTableFieldCount=2;
    $mainTableFields[0]="rptName";
    $mainTableFields[1]="rptType";
    $mainTableFieldsShowInList[0]=true;
    $mainTableFieldsShowInList[1]=false;
    $mainTableFieldsType[0]="s";
    $mainTableFieldsType[1]="i";
    $orderList=true;
    $orderFields=array(1);
    $insertQueryFildList="";
    $insertQueryParameterList="";
    $insertQueryBindTypeList="";
    $listFilds=array(0,1);
    for($i=0;$i<$mainTableFieldCount;$i++){
      $insertQueryFildList.=$mainTableFields[$i];
      $insertQueryParameterList.="?";
      $insertQueryBindTypeList.=$mainTableFieldsType[$i];
      $updateQueryFieldList.=$mainTableFields[$i] ."=?";
      if($mainTableFieldsShowInList[$i]){
        $listFields.=$mainTableFields[$i];
      } 
      if($i < $mainTableFieldCount-1){
        $insertQueryFildList.=",";
        $insertQueryParameterList.=",";
        $updateQueryFieldList.=",";
      }
    }
    if($orderList){
      $orderClause = "order by";
      for(i=0;$i<count($orderFields);$i++){
        $orderClause .= $mainTableFields[$orderFields[$i]] ;
        if($i < count($orderFields)-1){
          $orderClause .=",";
        }
      }
    }
    $insertQuery="insert into $mainTable($insertQueryFildList) values($insertQueryParameterList)";
    $updateQuery="update $mainTable set $updateQueryFieldList where $mainTableKey=?";
    $deleteQuery="delete from $mainTable where $mainTableKey=?";
    $readQuery="select * from $mainTable where $mainTableKey=?";
    $listQuery="select $listFieldList from $mainTable $orderClause";

    echo $insertQuery;
    echo $insertQueryBindTypeList;
    exit();

//*************************************************************************************************
//control variable end don't edit below if you want standard form
//*************************************************************************************************

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
    echo ".rptButtons{text-align:center; margin: auto; width: 220px;}";    
    echo "</style>";
	  echo "</head>";
	  echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>System reports</h2>";

//*************************************************************************************************
// Validation
//*************************************************************************************************
    if($mode=="Save_New" or $mode=="Save_Edited"){

      if($rptName==""){
        $validationErrorMessage.="Must enter a name for the report<br>";
        $validationError=true;
      }
    }

//*************************************************************************************************
// Run Query
//*************************************************************************************************
    if(($mode=="Save_New" or $mode=="Save_Edited" or $mode=="Delete_Record") and !$validationError){
      $q=$insertQuery;
      if($stmt = mysqli_prepare($dbc, $q)){
        mysqli_stmt_bind_param($stmt, $insertQueryBindTypeList, $rptName,$rptType);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
    }

//*************************************************************************************************
// Show form
//*************************************************************************************************
if($mode=="Show_Add" or $mode=="Show_Edit"){
      $showList=false;
      echo "<form method='post' style='max-width:700px;margin:auto'>";
      echo "<h2 style='text-align: center;'>Add new</h2>";
      echo "<table width='100%'>";
      echo "<tr><td style='width: 125px;'>report name:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='report Name' name='rptName'";
      if(isset($rptName))
        echo " value='$rptName'";
      echo ">";
      echo "</div></td></tr>";
      echo "<tr><td>report Type:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' placeholder='report type' name='rptType'>";
      $q="SELECT * FROM rptTypes";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          echo "<option value='$rptTypeId'";
          if(isset($rptType))
            if($rptType==$rptTypeId)
              echo " selected";
          echo ">$rptTypeName</option>";
        }
        mysqli_free_result($r);
      }
      echo "</select>";
      echo "</div></td></tr>";
      echo "</table>";
      echo "<div class='rptButtons'><button type='submit' class='savBtn' name='mode' value='5'>Save</button> <button type='submit' class='cnlBtn' name='mode' value='0'>Cancel</button></div>";
      echo "</form>";
      echo "<div class='error'><p>$errorMessage</p></div>";
    }

//*************************************************************************************************
// Delete Confirmation
//*************************************************************************************************
    if($mode==4){
      $showList=false;
      $q="select rptName from reports where rptId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $rptId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $rptName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }
      echo "<form method='post' style='max-width:500px;margin:auto'>";
      echo "<h2 style='text-align: center;'>Delete</h2>";
      echo "<div style='text-align: center;'>Are you sure you want to delete $rptName ?...</div><br>";
      echo "<input type='hidden' name='rptId' value='$rptId'>";
      echo "<div class='rptButtons'><button type='submit' class='savBtn' name='mode' value='7'>Yes</button> <button type='submit' class='cnlBtn' name='mode' value='0'>No</button></div>";
      echo "</form>";
    }

//*************************************************************************************************
// Delete Record
//*************************************************************************************************
    if($mode==7){
      $q="delete from reports where rptId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $rptId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
    }

//*************************************************************************************************
// View Record
//*************************************************************************************************
    if($mode==3){
      //View report
      $showList=false;
      if($errorMessage ==""){
        $q="select rptName,rptType from reports where rptId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          mysqli_stmt_bind_param($stmt, "i", $rptId);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_bind_result($stmt, $rptName,$rptType);
          mysqli_stmt_fetch($stmt);
          mysqli_stmt_close($stmt);
        }  
      }
      echo "<form method='post' style='max-width:700px;margin:auto'>";
      echo "<h2 style='text-align: center;'>View report</h2>";
      echo "<table width='100%'>";
      echo "<tr><td style='width: 125px;'>report name:</td><td>";
      echo "<div class='input-container'>$rptName</div></td></tr>";
      echo "<tr><td>report Type:</td><td>";
      echo "<div class='input-container'>$rptType - ";
      $q="SELECT * FROM rptTypes";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          if($rptType==$rptTypeId)
              echo $rptTypeName;
        }
        mysqli_free_result($r);
      }
      echo "</div></td></tr>";
      echo "</table>";
      echo "<div class='rptButtons'><button type='submit' class='okBtn' name='mode' value='0'>Ok</button></div>";
      echo "</form>";
    }

//*************************************************************************************************
// List View
//*************************************************************************************************
    if($showList){
      $q=$listQuery;
      $r=mysqli_query($dbc,$q);
      if($r){
        //new report report
        echo "<div style='width: 110px; margin: auto;'><form method='post'>";
        echo "<input type='hidden' name='mode' value='1'>";
        echo "<button type='submit' class='addBtn'>Add new</button>";
        echo "</form></div><br>";
        //filter list report 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table id='mainTable'>";
        echo "<tr class='header'>";
        echo "<th>Name</th><th style='width:600px;text-align: center;'></th></tr>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          echo "<tr><td>$rptName</td>";
          echo "<td style='text-align: right;'><form method='post'>";
          echo "<input type='hidden' name='rptId' value='$rptId'>";
          echo "<button type='submit' class='edtBtn' name='mode' value='2'>Edit</button> ";
          echo "<button type='submit' class='viewBtn' name='mode' value='3'>View</button> ";
          echo "<button type='submit' class='delBtn' name='mode' value='4'>Delete</button> ";
          echo "</form></td></tr>";
        }
        echo "</table>";
        mysqli_free_result($r);
      }
    }
    mysqli_close($dbc);

//*************************************************************************************************
// Page Script
//*************************************************************************************************
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

//*************************************************************************************************
// Session Expired
//*************************************************************************************************
if(!$logged){
  include('expired.php');
}
?>