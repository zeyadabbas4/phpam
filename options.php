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
    echo "#mainTable {border-collapse: collapse;width: 500px;border: 1px solid #ddd;font-size: 18px;margin:auto;}";
    echo "#mainTable th, #mainTable td {text-align: center;padding: 12px;}";
    echo "#mainTable tr {border-bottom: 1px solid #ddd;}";
    echo "#mainTable tr.header, #mainTable tr:hover {background-color: #f1f1f1;}";
    echo ".savBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".savBtn:hover {opacity: 1;}";
    echo ".cnlBtn {background-color: red;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".cnlBtn:hover {opacity: 1;}";
    echo ".input-container {display: -ms-flexbox; /* IE10 */  display: flex;  width: 100%;  margin-bottom: 15px;}";
    echo ".input-field {  width: 100%;  padding: 10px;  outline: none;}";
    echo ".input-field:focus {border: 2px solid dodgerblue;}";
    echo ".error{color: red; font-size: small;text-align:center;}";
    echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";    
    echo "</style>";
	  echo "</head>";
	  echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>System Options</h2>";
    if(isset($mode)){
      if($mode==1){
        setSystemValue("systemName",$systemName);
        setSystemValue("systemLogo",$systemLogo);
        setSystemValue("loginMessage",$loginMessage);
        setSystemValue("statusBarMessage",$statusBarMessage);
        setSystemValue("showStatusBarMessage",$showStatusBarMessage);
        setSystemValue("databaseManager",$databaseManager);
        setSystemValue("databaseManagerTarget",$databaseManagerTarget);
        setSystemValue("defaultGroupId",$defaultGroupId);
        setSystemValue("globalAlign",$globalAlign);
        setSystemValue("globalDirection",$globalDirection);
        setSystemValue("pwdMaxCaps",$pwdMaxCaps);
        setSystemValue("pwdMaxLwrs",$pwdMaxLwrs);
        setSystemValue("pwdMaxNums",$pwdMaxNums);
      }
    }

    $images=scandir("images");
    $systemName=getSystemValue("systemName");
    $systemLogo=getSystemValue("systemLogo");
    $loginMessage=getSystemValue("loginMessage");
    $statusBarMessage=getSystemValue("statusBarMessage");
    $showStatusBarMessage=getSystemValue("showStatusBarMessage");
    $databaseManager=getSystemValue("databaseManager");
    $databaseManagerTarget=getSystemValue("databaseManagerTarget");
    $defaultGroupId=getSystemValue("defaultGroupId");
    $globalAlign=getSystemValue("globalAlign");
    $globalDirection=getSystemValue("globalDirection");
    $pwdMaxCaps=getSystemValue("pwdMaxCaps");
    $pwdMaxLwrs=getSystemValue("pwdMaxLwrs");
    $pwdMaxNums=getSystemValue("pwdMaxNums");
    
    echo "<form method='post'><table id='mainTable'>";
    echo "<tr><td>System Name</td><td><input type='text' name='systemName' value='$systemName'></td></tr>";
    echo "<tr><td>System Logo</td><td><select name='systemLogo'>";
    foreach($images as $key => $value){
      if($value!="." and $value!=".."){
        echo "<option value='$value'";
        if($systemLogo==$value){
          echo " selected";
        }
        echo ">$value</option>";
      }
    }
    echo "</td></tr>";
    echo "<tr><td>Login Message</td><td><input type='text' name='loginMessage' value='$loginMessage'></td></tr>";
    echo "<tr><td>Status bar message</td><td><input type='text' name='statusBarMessage' value='$statusBarMessage'></td></tr>";
    echo "<tr><td>Show Status Bar Message</td><td><input type='hidden' name='showStatusBarMessage' value='No'>";
    echo "<input type='checkbox' name='showStatusBarMessage' value='Yes'";
    if($showStatusBarMessage=="Yes"){
      echo " checked";
    }
    echo "></td></tr>";
    echo "<tr><td>Database Manager</td><td><input type='text' name='databaseManager' value='$databaseManager'></td></tr>";
    echo "<tr><td>Database Manager Target</td><td>";
    echo "<input type='radio' name='databaseManagerTarget' value='_self'";
    if($databaseManagerTarget=="_self"){
      echo "checked";
    }
    echo "> _self ";
    echo "<input type='radio' name='databaseManagerTarget' value='_self'";
    if($databaseManagerTarget=="_blank"){
      echo "checked";
    }
    echo "> _blank ";
    echo "</td></tr>";
    echo "<tr><td>Default Group</td><td><select name='defaultGroupId'>";
    $q="select * from groups";
    if ($stmt = mysqli_prepare($dbc, $q)) {
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $grpId,$grpName);
      while(mysqli_stmt_fetch($stmt)){
        echo "<option value='$grpId'";
        if($defaultGroupId==$grpId){
          echo " selected";
        }
        echo ">$grpName</option>";
      }
      mysqli_stmt_close($stmt);
    }

    echo "</td></tr>";
    echo "<tr><td>Global Align</td><td>";
    echo "<input type='radio' name='globalAlign' value='right'";
    if($globalAlign=="right"){
      echo "checked";
    }
    echo "> right ";
    echo "<input type='radio' name='globalAlign' value='left'";
    if($globalAlign=="left"){
      echo "checked";
    }
    echo "> left ";
    echo "</td></tr>";
    echo "<tr><td>Global Direction</td><td>";
    echo "<input type='radio' name='globalDirection' value='rtl'";
    if($globalDirection=="rtl"){
      echo "checked";
    }
    echo "> rtl ";
    echo "<input type='radio' name='globalDirection' value='ltr'";
    if($globalDirection=="ltr"){
      echo "checked";
    }
    echo "> ltr ";
    echo "</td></tr>";
    echo "<tr><td>Password Max Caps</td><td><input type='text' name='pwdMaxCaps' value='$pwdMaxCaps'></td></tr>";
    echo "<tr><td>Password Max Lowers</td><td><input type='text' name='pwdMaxLwrs' value='$pwdMaxLwrs'></td></tr>";
    echo "<tr><td>Password Max Numbers</td><td><input type='text' name='pwdMaxNums' value='$pwdMaxNums'></td></tr>";
    echo "</table><input type='hidden' name='submitted' value='-1'>";
    echo "<br><div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='1'>Save</button> <button type='submit' class='cnlBtn' name='mode' value='0'>Cancel</button></div>";
    echo "</form><br><br>";
    echo "</body></html>";
  }
}
if(!$logged){
  include('expired.php');
}
?>