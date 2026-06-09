<?php
//ini_set('display_errors',1);
//error_reporting(E_ALL);
session_start();
include ("functions.php");
$logged=false;
if(isset($_SESSION['__uid'])){
  $__uid=$_SESSION['__uid'];
  $user=getUserDetails($__uid);
  if($__uid==0 or $user['usrClass']==2){
    $logged=true;
    $showList=true;
    foreach($_POST as $key => $value)
      $$key=$value;
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
    echo ".disBtn {background-color: darkgrey;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
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
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>System Groups</h2>";
    if($mode==5){
      //save new group
      if($grpName==""){
        $errorMessage.="Must enter a name for the group<br>";
        $mode=1;
      }
      if($mode==5){
        $q="insert into groups(grpName) values(?)";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          mysqli_stmt_bind_param($stmt, "s", $grpName);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
        }
      }
    }
    if($mode==1){
      $showList=false;
      echo "<form method='post' style='max-width:700px;margin:auto'>";
      echo "<h2 style='text-align: center;'>Add new</h2>";
      echo "<table width='100%'>";
      echo "<tr><td style='width: 125px;'>Group name:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='Group Name' name='grpName'";
      if(isset($grpName))
        echo " value='$grpName'";
      echo ">";
      echo "</div></td></tr>";
      echo "</table>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='5'>Save</button> <button type='submit' class='cnlBtn' name='mode' value='0'>Cancel</button></div>";
      echo "</form>";
      echo "<div class='error'><p>$errorMessage</p></div>";
    }
    if($mode==6){
      //save edited group
      if($grpName==""){
        $errorMessage.="Must enter a name for the group<br>";
        $mode=2;
      }
      if($mode==6){
        $q="update groups set grpName=? where grpId=?";
        if ($stmt = mysqli_prepare($dbc, $q)){
          mysqli_stmt_bind_param($stmt, "si", $grpName,$grpId);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
        }
      }
    }
    if($mode==2){
      $showList=false;
      if($errorMessage ==""){
        $q="select grpName from groups where grpId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          mysqli_stmt_bind_param($stmt, "i", $grpId);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_bind_result($stmt, $grpName);
          mysqli_stmt_fetch($stmt);
          mysqli_stmt_close($stmt);
        }  
      }
      echo "<form method='post' style='max-width:700px;margin:auto'>";
      echo "<h2 style='text-align: center;'>Edit group</h2>";
      echo "<table width='100%'>";
      echo "<tr><td style='width: 125px;'>Group name:</td><td>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='Group Name' name='grpName'";
      if(isset($grpName))
        echo " value='$grpName'";
      echo ">";
      echo "</div></td></tr>";
      echo "</table>";
      echo "<input type='hidden' name='grpId' value='$grpId'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='6'>Save</button> <button type='submit' class='cnlBtn' name='mode' value='0'>Cancel</button></div>";
      echo "</form>";
      echo "<div class='error'><p>$errorMessage</p></div>";
    }
    if($mode==4){
      $showList=false;
      $q="select grpName from groups where grpId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $grpId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $grpName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }
      echo "<form method='post' style='max-width:500px;margin:auto'>";
      echo "<h2 style='text-align: center;'>Delete</h2>";
      echo "<div style='text-align: center;'>Are you sure you want to delete $grpName ?...</div><br>";
      echo "<input type='hidden' name='grpId' value='$grpId'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='7'>Yes</button> <button type='submit' class='cnlBtn' name='mode' value='0'>No</button></div>";
      echo "</form>";
    }
    if($mode==7){
      $q="delete from groups where grpId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $grpId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
    }
    if($mode==11){
      $q="insert into usergroups(usrgrpUser,usrgrpGroup) values(?,?)";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "ii", $from,$grpId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
      $mode=10;
    }
    if($mode==12){
      $q="insert into usergroups(usrgrpUser,usrgrpGroup) select usrId,? as grpId from users where usrId not in(select usrgrpUser from usergroups where usrgrpGroup=?)";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "ii", $grpId,$grpId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
      $mode=10;
    }
    if($mode==13){
      $q="delete from usergroups where usrgrpUser=? and usrgrpGroup=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "ii", $to,$grpId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
      $mode=10;
    }
    if($mode==14){
      $q="delete from usergroups where usrgrpGroup=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $grpId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
      $mode=10;
    }
    if($mode==10){
      $showList=false;
      $q="select grpName from groups where grpId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $grpId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $grpName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }
      echo "<h2 style='text-align: center;'>Members of $grpName</h2>";
      echo "<form method='post' style='max-width:700px;margin:auto'>";
      echo "<table width='100%'><tr>";
      echo "<td align='center'>";
      $q="select usrId,usrFullName from users where usrId not in(select usrgrpUser from usergroups where usrgrpGroup=$grpId)";
      $r=mysqli_query($dbc,$q);
      if($r){
        echo "<Select style='width:250px;font-size: 18px;' size='10' name='from'>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          echo "<option value='$usrId'>$usrFullName</option>";
        }
        echo "</select>";
        mysqli_free_result($r);
      }
      echo "</td>";
      echo "<td>";
      echo "<button type='submit' class='arrowBtn' name='mode' value='11'>></button><br><br>";
      echo "<button type='submit' class='arrowBtn' name='mode' value='12'>>></button><br><br>";
      echo "<button type='submit' class='arrowBtn' name='mode' value='13'><</button><br><br>";
      echo "<button type='submit' class='arrowBtn' name='mode' value='14'><<</button><br>";
      echo "</td>";
      echo "<td>";
      $q="select usrId,usrFullName from users where usrId in(select usrgrpUser from usergroups where usrgrpGroup=$grpId)";
      $r=mysqli_query($dbc,$q);
      if($r){
        echo "<Select style='width:250px;font-size: 18px;' size='10' name='to'>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          echo "<option value='$usrId'>$usrFullName</option>";
        }
        echo "</select>";
        mysqli_free_result($r);
      }
      echo "</td>";
      echo "</tr></table><br>";
      echo "<input type='hidden' name='grpId' value='$grpId'>";
      echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode' value='0'>Ok</button></div>";
      echo "</form>";
    }
    if($showList){
      $q="select * from groups order by grpName";
      $r=mysqli_query($dbc,$q);
      if($r){
        echo "<div style='width: 110px; margin: auto;'><form method='post'><input type='hidden' name='mode' value='1'><button type='submit' class='addBtn'>Add new</button></form></div><br>";
        echo "<input type=\"text\" id=\"filterBox\" onkeyup=\"filterList()\" placeholder=\"Filter list..\" title=\"Type in a name\">";
        echo "<table id=\"mainTable\">";
        echo "<tr class=\"header\">";
        echo "<th>Name</th><th style=\"width:600px;text-align: center;\"></th></tr>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          echo "<tr><td>$grpName</td>";
          echo "<td style='text-align: right;'><form method='post'><input type='hidden' name='grpId' value='$grpId'>";
          $defGropup=getSystemValue("defaultGroupId");
          $disabled="";
          $delClass="delBtn";
          $grpClass="grpBtn";
          $edtClass="edtBtn";
          if($defGropup==$grpId){
            $disabled=" Disabled";
            $delClass="disBtn";
            $grpClass="disBtn";
            $edtClass="disBtn";
          }
          echo "<button type='submit' class='$edtClass' name='mode' value='2'$disabled>Edit</button> ";
          echo "<button type='submit' class='$delClass' name='mode' value='4'$disabled>Delete</button> ";
          echo "<button type='submit' class='$grpClass' name='mode' value='10'$disabled>Members</button> ";
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
  include('expired.php');
}
?>