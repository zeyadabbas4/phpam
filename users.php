<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
include ("functions.php");
$logged=false;
if(isset($_SESSION['__uid'])){
  $__uid=$_SESSION['__uid'];
  $user=getUserDetails($__uid);
  if($__uid==0 or $user['usrClass']==2){
    $logged=true;
    $showList=true;
    $filename=basename(__FILE__); 
    foreach($_POST as $key => $value)
      $$key=$value;
    $dbc=sysdbConnect();
    if(!isset($mode))
      $mode="";
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
    echo ".permBtn {background-color: orange;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".permBtn:hover {opacity: 1;}";
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
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>System Users</h2>";
    
    //****************************************************************************
    //Save new record
    //****************************************************************************
    if($mode=='saveadd'){
      //save new user
      $showList=false;
      if($usrFullName==""){
        $errorMessage.="Must enter full name for the user<br>";
        $mode='add';
      }
      if($usrName==""){
        $errorMessage.="Must enter a user name<br>";
        $mode='add';
      }
      if($usrPassword==""){
        $errorMessage.="Must enter a password<br>";
        $mode='add';
      }
      if($mode=='saveadd'){
        $pwdhsh=password_hash($usrPassword, PASSWORD_DEFAULT);
        $q="insert into users(usrName,usrPassword,usrFullName,usrClass,usrAllowRemote) values(?,?,?,?,?)";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          mysqli_stmt_bind_param($stmt, "sssii", $usrName,$pwdhsh,$usrFullName,$usrClass,$usrAllowRemote);
          if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_close($stmt);
            $usrId=mysqli_insert_id($dbc);
            $grpId=getSystemValue("defaultGroupId");
            $q="insert into usergroups(usrgrpUser,usrgrpGroup) values(?,?)";
            if ($stmt = mysqli_prepare($dbc, $q)){
              mysqli_stmt_bind_param($stmt, "ii", $usrId,$grpId);
              mysqli_stmt_execute($stmt);
              mysqli_stmt_close($stmt);
            }
          }          
          echo "<form method='post' action='passprint.php' target='_blank' style='max-width:500px;margin:auto'>";
          echo "<h2 style='text-align: center;'>User Saved!...</h2>";
          echo "<div style='text-align: center;'>Print password?...</div><br>";
          echo "<input type='hidden' name='fullname' value='$usrFullName'>";
          echo "<input type='hidden' name='user' value='$usrName'>";
          echo "<input type='hidden' name='password' value='$usrPassword'>";
          echo "<div class='frmButtons'><button type='submit' class='savBtn'>Yes</button> <button type='button' class='cnlBtn' onclick='window.location=\"$filename\"'> Close </button></div>";
          echo "</form>";    
        }
      }
    }
    //****************************************************************************
    //New user form
    //****************************************************************************
    if($mode=='add'){
      $showList=false;
      echo "<form method='post' style='max-width:700px;margin:auto'>";
      echo "<h2 style='text-align: center;'>Add new</h2>";
      echo "<table width='100%'>";

      echo "<tr><td style='width: 125px;'>Full name:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='User Full Name' name='usrFullName'";
      if(isset($usrFullName))
        echo " value='$usrFullName'";
      echo ">";
      echo "</div></td></tr>";

      echo "<tr><td>User name:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='User Name' name='usrName'";
      if(isset($usrName))
        echo " value='$usrName'";
      echo ">";
      echo "</div></td></tr>";

      echo "<tr><td>Password:</td><td>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='Password' name='usrPassword' id='usrPassword'></div></td>";
      echo "<td style='width: 100px;'><div class='input-container'><button type='button' class='genBtn' onclick='genPassword();'>Generate</button>";
      echo "</div></td></tr>";

      echo "<tr><td>User class:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' placeholder='User Class' name='usrClass'>";
      $q="SELECT * FROM `userclass`";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          echo "<option value='$usrclsId'";
          if(isset($usrClass))
            if($usrClass==$usrclsId)
              echo " selected";
          echo ">$usrclsDescription</option>";
        }
        mysqli_free_result($r);
      }
      echo "</select>";
      echo "</div></td></tr>";

      echo "<tr><td>Remote login:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input type='hidden' name='usrAllowRemote' value='0'>";
      echo "<input class='input-field' type='checkbox' name='usrAllowRemote' value='1'";
      if(isset($usrAllowRemote))
        if($usrAllowRemote==1)
          echo " checked";
      echo ">";
      echo "</div></td></tr>";

      echo "<tr><td>Enabled:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input type='hidden' name='usrEnabled' value='0'>";
      echo "<input class='input-field' type='checkbox' name='usrEnabled' value='1'";
      if(isset($usrEnabled))
        if($usrEnabled==1)
          echo " checked";
      echo ">";
      echo "</div></td></tr>";

      echo "</table>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='saveadd'>Save</button> <button type='submit' class='cnlBtn' name='mode' value='0'>Cancel</button></div>";
      echo "</form>";
      echo "<div class='error'><p>$errorMessage</p></div>";
    }

    //****************************************************************************
    //Save edited record
    //****************************************************************************

    if($mode=='saveedit'){
      //save edited value
      if($usrFullName==""){
        $errorMessage.="Must enter full name for the user<br>";
        $mode='edit';
      }
      if($usrName==""){
        $errorMessage.="Must enter a user name<br>";
        $mode='edit';
      }
      if($mode=='saveedit'){
        $q="update users set usrName=?,usrFullName=?,usrClass=?,usrAllowRemote=?,usrEnabled=? where usrId=?";
        if ($stmt = mysqli_prepare($dbc, $q)){
          mysqli_stmt_bind_param($stmt, "ssiiii", $usrName,$usrFullName,$usrClass,$usrAllowRemote,$usrEnabled,$usrId);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
        }
      }
    }

    //****************************************************************************
    //Edit user form
    //****************************************************************************
    if($mode=='edit'){
      $showList=false;
      if($errorMessage ==""){
        $q="select usrName,usrFullName,usrClass,usrAllowRemote,usrEnabled from users where usrId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          mysqli_stmt_bind_param($stmt, "i", $usrId);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_bind_result($stmt, $usrName,$usrFullName,$usrClass,$usrAllowRemote,$usrEnabled);
          mysqli_stmt_fetch($stmt);
          mysqli_stmt_close($stmt);
        }  
      }
      echo "<form method='post' style='max-width:700px;margin:auto'>";
      echo "<h2 style='text-align: center;'>Edit User</h2>";
      echo "<table width='100%'>";

      echo "<tr><td style='width: 125px;'>Full name:</td><td>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='User Full Name' name='usrFullName'";
      if(isset($usrFullName))
        echo " value='$usrFullName'";
      echo ">";
      echo "</div></td></tr>";

      echo "<tr><td>User name:</td><td>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='User Name' name='usrName'";
      if(isset($usrName))
        echo " value='$usrName'";
      echo ">";
      echo "</div></td></tr>";

      echo "<tr><td>User class:</td><td>";
      echo "<div class='input-container'>";
      echo "<select class='input-field' placeholder='User Class' name='usrClass'>";
      $q="SELECT * FROM `userclass`";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          echo "<option value='$usrclsId'";
          if(isset($usrClass))
            if($usrClass==$usrclsId)
              echo " selected";
          echo ">$usrclsDescription</option>";
        }
        mysqli_free_result($r);
      }
      echo "</select>";
      echo "</div></td></tr>";

      echo "<tr><td>Remote login:</td><td>";
      echo "<div class='input-container'>";
      echo "<input type='hidden' name='usrAllowRemote' value='0'>";
      echo "<input class='input-field' type='checkbox' name='usrAllowRemote' value='1'";
      if(isset($usrAllowRemote))
        if($usrAllowRemote==1)
          echo " checked";
      echo ">";
      echo "</div></td></tr>";

      echo "<tr><td>Enabled:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input type='hidden' name='usrEnabled' value='0'>";
      echo "<input class='input-field' type='checkbox' name='usrEnabled' value='1'";
      if(isset($usrEnabled))
        if($usrEnabled==1)
          echo " checked";
      echo ">";
      echo "</div></td></tr>";

      echo "</table>";
      echo "<input type='hidden' name='usrId' value='$usrId'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='saveedit'>Save</button> <button type='submit' class='cnlBtn' name='mode' value='0'>Cancel</button></div>";
      echo "</form>";
      echo "<div class='error'><p>$errorMessage</p></div>";
    }

    //****************************************************************************
    //delete confirm
    //****************************************************************************
    if($mode=='deleteconfirm'){
      $showList=false;
      $q="select usrFullName from users where usrId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $usrId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $usrFullName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }
      echo "<form method='post' style='max-width:500px;margin:auto'>";
      echo "<h2 style='text-align: center;'>Delete</h2>";
      echo "<div style='text-align: center;'>Are you sure you want to delete $usrFullName ?...</div><br>";
      echo "<input type='hidden' name='usrId' value='$usrId'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='delete'>Yes</button> <button type='submit' class='cnlBtn' name='mode' value='0'>No</button></div>";
      echo "</form>";
    }

    //****************************************************************************
    //delete user
    //****************************************************************************
    if($mode=='delete'){
      $q="delete from users where usrId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $usrId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
    }
    //****************************************************************************
    //set password
    //****************************************************************************
    if($mode=="savepassword"){
      //save edited value
      if($usrPassword==""){
        $errorMessage.="Must enter a password<br>";
        $mode='password';
      }
      if($mode=='savepassword'){
        $showList=false;
        $pwdhsh=password_hash($usrPassword, PASSWORD_DEFAULT);
        $q="update users set usrPassword=? where usrId=?";
        if ($stmt = mysqli_prepare($dbc, $q)){
          mysqli_stmt_bind_param($stmt, "si", $pwdhsh,$usrId);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
          echo "<form method='post' action='passprint.php' target='_blank' style='max-width:500px;margin:auto'>";
          echo "<h2 style='text-align: center;'>User Saved!...</h2>";
          echo "<div style='text-align: center;'>Print password?...</div><br>";
          echo "<input type='hidden' name='fullname' value='$usrFullName'>";
          echo "<input type='hidden' name='user' value='$usrName'>";
          echo "<input type='hidden' name='password' value='$usrPassword'>";
          echo "<div class='frmButtons'><button type='submit' class='savBtn'>Yes</button> <button type='button' class='cnlBtn' onclick='window.location=\"$filename\"'> Close </button></div>";
          echo "</form>";
        }
      }
    }

    //****************************************************************************
    //set password
    //****************************************************************************
    if($mode=='password'){
      $showList=false;
      $q="select usrName,usrFullName from users where usrId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $usrId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $usrName,$usrFullName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }
      echo "<form method='post' style='max-width:700px;margin:auto'>";
      echo "<h2 style='text-align: center;'>Reset Password for $usrFullName</h2>";
      echo "<table width='100%'>";
      echo "<tr><td>Password:</td><td>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='New Password' name='usrPassword' id='usrPassword'></div></td>";
      echo "<td style='width: 100px;'><div class='input-container'><button type='button' class='genBtn' onclick='genPassword();'>Generate</button>";
      echo "</div></td></tr>";
      echo "</table>";
      echo "<input type='hidden' name='usrId' value='$usrId'>";
      echo "<input type='hidden' name='usrName' value='$usrName'>";
      echo "<input type='hidden' name='usrFullName' value='$usrFullName'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='savepassword'>Save</button> <button type='submit' class='cnlBtn' name='mode' value='0'>Cancel</button></div>";
      echo "</form>";
      echo "<div class='error'><p>$errorMessage</p></div>";
    }
    //****************************************************************************
    //View user
    //****************************************************************************
    if($mode=='view'){
      //View user
      $showList=false;
      if($errorMessage ==""){
        $q="select usrName,usrFullName,usrClass,usrAllowRemote,usrEnabled from users where usrId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          mysqli_stmt_bind_param($stmt, "i", $usrId);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_bind_result($stmt, $usrName,$usrFullName,$usrClass,$usrAllowRemote,$usrEnabled);
          mysqli_stmt_fetch($stmt);
          mysqli_stmt_close($stmt);
        }  
      }
      echo "<form method='post' style='max-width:700px;margin:auto'>";
      echo "<h2 style='text-align: center;'>View User</h2>";
      echo "<table width='100%'>";
      echo "<tr><td style='width: 125px;'>Full name:</td><td>";
      echo "<div class='input-container'>$usrFullName</div></td></tr>";
      echo "<tr><td>User name:</td><td>";
      echo "<div class='input-container'>$usrName</div></td></tr>";
      echo "<tr><td>User class:</td><td>";
      echo "<div class='input-container'>";
      $q="SELECT * FROM `userclass`";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          if($usrClass==$usrclsId)
              echo $usrclsDescription;
        }
        mysqli_free_result($r);
      }
      echo "</div></td></tr>";
      echo "<tr><td>Remote login:</td><td>";
      echo "<div class='input-container'>";
      if($usrAllowRemote==1)
        echo "Yes";
      else
        echo "No";
      echo "</div></td></tr>";
      echo "<tr><td>Enabled:</td><td>";
      echo "<div class='input-container'>";
      if($usrEnabled==1)
        echo "Yes";
      else
        echo "No";
      echo "</div></td></tr>";
      echo "</table>";
      echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode' value='0'>Ok</button></div>";
      echo "</form>";
    }

    //****************************************************************************
    //Add group
    //****************************************************************************
    if($mode=="addGroup"){
      if(isset($from)){
        $q="INSERT INTO `usergroups`(`usrgrpUser`,`usrgrpGroup`) VALUES(?,?)";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if(mysqli_stmt_bind_param($stmt, "ii", $usrId,$from)){
            mysqli_stmt_execute($stmt);
          }
          mysqli_stmt_close($stmt);
        }  
      }
      $mode="groups";
    }

    //****************************************************************************
    //Add All groups
    //****************************************************************************
    if($mode=="addAllGroups"){
      $q="INSERT INTO `usergroups`(`usrgrpUser`,`usrgrpGroup`) SELECT ? AS `usrId`,`grpId` FROM `groups` WHERE `grpId` NOT IN(SELECT `usrgrpGroup` FROM `usergroups` WHERE `usrgrpUser`=?)";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt, "ii", $usrId,$usrId)){
          mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
      }
      $mode="groups";
    }
    
    //****************************************************************************
    //remove group
    //****************************************************************************
    if($mode=="remGroup"){
      if(isset($to)){
        $q="DELETE FROM `usergroups` WHERE `usrgrpUser`=? AND `usrgrpGroup`=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if(mysqli_stmt_bind_param($stmt, "ii", $usrId,$to)){
            mysqli_stmt_execute($stmt);
          }
          mysqli_stmt_close($stmt);
        }  
      }
      $mode="groups";
    }
    
    //****************************************************************************
    //remove All groups
    //****************************************************************************
    if($mode=="remAllGroups"){
      $defGropup=getSystemValue("defaultGroupId");
      $q="DELETE FROM usergroups WHERE usrgrpUser=? AND usrgrpGroup != ?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt, "ii", $usrId,$defGropup)){
          mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
      }
      $mode="groups";
    }

    //****************************************************************************
    //groups
    //****************************************************************************
    if($mode=='groups'){
      $showList=false;
      $q="select usrName,usrFullName from users where usrId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt, "i", $usrId)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $usrName,$usrFullName)){
              mysqli_stmt_fetch($stmt);
            }
          }
        }
        mysqli_stmt_close($stmt);
      }
      echo "<h2 style='text-align: center;'>Group membership for $usrFullName</h2>";
      echo "<form method='post' style='max-width:700px;margin:auto'>";
      echo "<table width='100%'><tr>";
      echo "<td align='center'>";
      echo "<Select style='width:250px;height:250px;font-size: 18px;' size='10' name='from' ondblclick='addOne();'>";
      $q="SELECT `grpId`,`grpName` FROM `groups` WHERE `grpId` NOT IN(SELECT `usrgrpGroup` FROM `usergroups` WHERE `usrgrpUser`=?)";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt, "i", $usrId)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $grpId,$grpName)){
              while(mysqli_stmt_fetch($stmt)){
                echo "<option value='$grpId'>$grpName</option>";                
              }
            }
          }
        }
        mysqli_stmt_close($stmt);
      }
      echo "</select>";
      echo "</td>";
      echo "<td>";
      echo "<button type='submit' class='arrowBtn' name='mode' value='addGroup' id='addGroup'>&gt;</button><br><br>";
      echo "<button type='submit' class='arrowBtn' name='mode' value='addAllGroups'>&gt;&gt;</button><br><br>";
      echo "<button type='submit' class='arrowBtn' name='mode' value='remGroup' id='remGroup'>&lt;</button><br><br>";
      echo "<button type='submit' class='arrowBtn' name='mode' value='remAllGroups'>&lt;&lt;</button><br>";
      echo "</td>";
      echo "<td>";
      $defGropup=getSystemValue("defaultGroupId");
      echo "<Select style='width:250px;height:250px;font-size: 18px;' size='10' name='to' ondblclick='remOne();'>";
      $q="SELECT `grpId`,`grpName` FROM `groups` WHERE `grpId` IN(SELECT `usrgrpGroup` FROM `usergroups` WHERE `usrgrpUser`=?)";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt, "i", $usrId)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $grpId,$grpName)){
              while(mysqli_stmt_fetch($stmt)){
                $disabled="";
                if($defGropup==$grpId){
                  $disabled=" Disabled";
                }
                echo "<option value='$grpId'$disabled>$grpName</option>";    
              }
            }
          }
        }
        mysqli_stmt_close($stmt);
      }        
      echo "</select>";
      echo "</td>";
      echo "</tr></table><br>";
      echo "<input type='hidden' name='usrId' value='$usrId'>";
      echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode' value='0'>Ok</button></div>";
      echo "</form>";
    }

    //****************************************************************************
    //Permissions
    //****************************************************************************
    if($mode=='permissions'){
      $showList=false;
      //read user name
      $q="select usrFullName from users where usrId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $usrId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $usrFullName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }  
      //read user permissions
      $userPerms=array();
      $q="select usrpermValue,usrpermPermissionId,permName from permissions inner join userpermissions on permId=usrpermPermissionId where usrpermUserId=?";
      if($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $usrId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $usrpermValue,$usrpermPermissionId,$permName);
        while(mysqli_stmt_fetch($stmt)){
          $userPerms[$usrpermPermissionId]=$usrpermValue;
        }
        mysqli_stmt_close($stmt);
      }
      //read all permissions
      $allPerms=array();
      $q="select permId,permName from permissions order by permName";
      if($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $permId,$permName);
        while(mysqli_stmt_fetch($stmt)){
          $allPerms[$permId]=$permName;
        }
        mysqli_stmt_close($stmt);
      }

      echo "<h2 style='text-align: center;'>Permissions for $usrFullName</h2>";
      echo "<form method='post'>";
      echo "<table width='35%' align='center'>";
      foreach($allPerms as $key => $value){
        echo "<tr>";
        echo "<td>";
        $checked="";
        if(isset($userPerms[$key])){
          if($userPerms[$key] == 1){
            $checked=" checked";
          }
        }
        echo "<input type='hidden' name='usrpermValue[$key]' value='0'>";
        echo "<input type='checkbox' value='1' name='usrpermValue[$key]'$checked>";
        echo " $value";
        echo "</td>";
        echo "</tr>";
      }
      echo "</table>";
      echo "<br>";
      echo "<input type='hidden' name='usrId' value='$usrId'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='savepermissions'>Save</button> <button type='submit' class='cnlBtn' name='mode' value='0'>Cancel</button></div>";
      echo "</form>";
    }
    
    //****************************************************************************
    //Save Permissions
    //****************************************************************************
    if($mode=='savepermissions'){
      $q="delete from userpermissions where usrpermUserId=?";
      if($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $usrId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
      $q="insert into userpermissions(usrpermUserId,usrpermPermissionId,usrpermValue) values(?,?,1)";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        foreach($usrpermValue as $key => $value){
          if($value==1){
            mysqli_stmt_bind_param($stmt, "ii", $usrId,$key);
            mysqli_stmt_execute($stmt);
          }
        }
        mysqli_stmt_close($stmt);
      }
    }

    //****************************************************************************
    //Field List
    //****************************************************************************
    if($showList){
      $q="select * from users order by usrFullName";

      $r=mysqli_query($dbc,$q);
      if($r){
        //new user form
        echo "<div style='width: 110px; margin: auto;'><form method='post'>";
        echo "<input type='hidden' name='mode' value='add'>";
        echo "<button type='submit' class='addBtn'>Add new</button>";
        echo "</form></div><br>";
        //filter list form 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table id='mainTable'>";
        echo "<tr class='header'>";
        echo "<th>Name</th><th style='width:700px;text-align: center;'></th></tr>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          echo "<tr><td>$usrFullName</td>";
          echo "<td style='text-align: right;'><form method='post'>";
          echo "<input type='hidden' name='usrId' value='$usrId'>";
          echo "<button type='submit' class='edtBtn' name='mode' value='edit'>Edit</button> ";
          echo "<button type='submit' class='pwdBtn' name='mode' value='password'>Password</button> ";
          echo "<button type='submit' class='viewBtn' name='mode' value='view'>View</button> ";
          echo "<button type='submit' class='delBtn' name='mode' value='deleteconfirm'>Delete</button> ";
          echo "<button type='submit' class='grpBtn' name='mode' value='groups'>Groups</button> ";
          echo "<button type='submit' class='permBtn' name='mode' value='permissions'>Permissions</button> ";
          echo "</form></td></tr>";
        }
        echo "</table>";
        mysqli_free_result($r);
      }
    }
    mysqli_close($dbc);
    $pwdnums=getSystemValue("pwdMaxNums");
    $pwdcaps=getSystemValue("pwdMaxCaps");
    $pwdlwrs=getSystemValue("pwdMaxLwrs");
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
          function genPassword(){
            var maxnum=$pwdnums;
            var maxcaps=$pwdcaps;
            var maxlowers=$pwdlwrs;
            var maxlength=maxnum+maxcaps+maxlowers;
            var nums=0;
            var caps=0;
            var lowers=0;
            document.getElementById('usrPassword').value='';
            for(var i=0;i < maxlength;i++){
                var codegood=0;
                while (codegood != 1){
                    var numchr=Math.floor(Math.random()*3);
                    var my_num=Math.random();
                    if(numchr == 0){
                        if(nums < maxnum){
                        document.getElementById('usrPassword').value=document.getElementById('usrPassword').value + String.fromCharCode(Math.floor(my_num*9)+49);
                        nums++;
                        codegood=1;
                        }
                    }else if(numchr == 1){
                        if(lowers < maxlowers){
                            document.getElementById('usrPassword').value=document.getElementById('usrPassword').value + String.fromCharCode(Math.floor(my_num*26)+97);
                            lowers++;
                            codegood=1;
                        }
                    }else{
                        if(caps < maxcaps){
                            document.getElementById('usrPassword').value=document.getElementById('usrPassword').value + String.fromCharCode(Math.floor(my_num*26)+65);
                            caps++;
                            codegood=1;
                        }
                    }
                }
            }
        }
        function addOne(){
          document.getElementById('addGroup').click();
        }
        function remOne(){
          document.getElementById('remGroup').click();
        }
        </script>";
    echo "</body></html>";
  }
}
if(!$logged){
  include('expired.php');
}
?>