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
    
    //Menu and status bar
    $defaultColors['topBarFG']="#ffffff";
    $defaultColors['topBarBG']="#30589a";
    $defaultColors['topBarActiveFG']="#ffffff";
    $defaultColors['topBarActiveBG']="#ff0000";
    $defaultColors['menuBarFG']="#ffffff";
    $defaultColors['menuBarBG']="#ff0000";
    $defaultColors['menuBarActiveFG']="#000000";
    $defaultColors['menuBarActiveBG']="#ffffff";
    $defaultColors['dropdownFG']="#000000";
    $defaultColors['dropdownBG']="#ffffff";
    $defaultColors['dropdownActiveFG']="#000000";
    $defaultColors['dropdownActiveBG']="#eeeeee";
    $defaultColors['statusBarFG']="#ffffff";
    $defaultColors['StatusBarBG']="#30589a";
    //Application area
    $defaultColors['txtColor']="#000000";
    $defaultColors['bgColor']="#ffffff";
    $defaultColors['listFG']="#000000";
    $defaultColors['listBG']="#ffffff";
    $defaultColors['lstHdFG']="#000000";
    $defaultColors['lstHdBG']="#f1f1f1";
    //Buttons
    $defaultColors['okButtonFg']="#ffffff";
    $defaultColors['okButtonBg']="#4B0082";
    $defaultColors['newButtonFg']="#ffffff";
    $defaultColors['newButtonBg']="#191970";
    $defaultColors['editButtonFg']="#ffffff";
    $defaultColors['editButtonBg']="#008080";
    $defaultColors['deleteButtonFg']="#ffffff";
    $defaultColors['deleteButtonBg']="#8B0000";
    $defaultColors['saveButtonFg']="#ffffff";
    $defaultColors['saveButtonBg']="#008000";
    $defaultColors['cancelButtonFg']="#ffffff";
    $defaultColors['cancelButtonBg']="#ff0000";
    $defaultColors['viewButtonFg']="#ffffff";
    $defaultColors['viewButtonBg']="#d2691e";
    $defaultColors['yesButtonFg']="#ffffff";
    $defaultColors['yesButtonBg']="#008000";
    $defaultColors['noButtonFg']="#ffffff";
    $defaultColors['noButtonBg']="#ff0000";
    //login
    $defaultColors['loginMessageText']="#30589a";
    $defaultColors['loginFormIconsBG']="#1e90ff";
    $defaultColors['loginFormIconsFG']="#ffffff";
    $defaultColors['loginFormFields']="#1e90ff";
    $defaultColors['loginFormButtonFG']="#ffffff";
    $defaultColors['loginFormButtonBG']="#1e90ff";
    $defaultColors['loginStatusBarFG']="#ffffff";
    $defaultColors['loginStatusBarBG']="#1e90ff";

    //Load Color Names
    $q="select * from colornames";
    $r=mysqli_query($dbc,$q);
    if($r){
      $i=0;
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value){
          $colors[$i][$key]=$value;
        }
        $i++;
      }
      mysqli_free_result($r);
    }
    $noColors=$i-1;

    //Load Active Colors
    for($i=0;$i<=$noColors;$i++){
        $activeColors[$colors[$i]['clrVariable']]=getSystemValue($colors[$i]['clrVariable']);
    }
      
    //Load users
    $q="select usrId,usrName from users";
    $r=mysqli_query($dbc,$q);
    if($r){
      $i=0;
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value)
          $userList[$i][$key]=$value;
        $i++;
      }
      mysqli_free_result($r);
    }
    $noUsers=$i-1;

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
    echo ".defBtn {background-color: #B22222;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 150px;  opacity: 0.9;}";
    echo ".defBtn:hover {opacity: 1;}";
    echo ".input-container {display: -ms-flexbox; /* IE10 */  display: flex;  width: 100%;  margin-bottom: 15px;}";
    echo ".input-field {  width: 100%;  padding: 10px;  outline: none;}";
    echo ".input-field:focus {border: 2px solid dodgerblue;}";
    echo ".error{color: red; font-size: small;text-align:center;}";   
    echo "</style>";
	  echo "</head>";
	  echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>System Colors</h2>";
    if(isset($mode)){
        if($mode==1){
            if($targetUsers==0){
                for($i=0;$i<=$noColors;$i++){
                    $clrVariable=$colors[$i]['clrVariable'];
                    setSystemValue($clrVariable,$$clrVariable);
                }       
            }else{
                for($i=0;$i<=$noColors;$i++){
                    $clrVariable=$colors[$i]['clrVariable'];
                    $clrs[$i]['Id']=$colors[$i]['clrId'];
                    $clrs[$i]['Value']=$$clrVariable;
                }
                setUserColors($targetUser,$clrs);
            }
        }
        if($mode==2){
            for($i=0;$i<=$noColors;$i++){
                $activeColors[$colors[$i]['clrVariable']]=$defaultColors[$colors[$i]['clrVariable']];
            }            
        }
    }
    //The List
    echo "<form method='post'>";
    echo "<h3 style='text-align:center;color:#B22222;'>Change system colors</h3>";
    echo "<div style='width:500px;height:65px;margin:auto;'>";
    echo "<div style='width:250px;height:65px;float:left;'>";
    echo "<input type='radio' value='0' name='targetUsers' checked> For all users<br>";
    echo "<input type='radio' value='1' name='targetUsers'> For: <select name='targetUser'>";
    for($i=0;$i<=$noUsers;$i++){
    echo "<option value='".$userList[$i]['usrId']."'>".$userList[$i]['usrName']."</option>";
    }
    echo "</select></div>";
    echo "<div style='width:250px;text-align:right;height:65px;float:right;padding-top:15px;'>";
    echo "<button type='submit' class='defBtn' name='mode' value='2'>Restore Defaults</button>";
    echo "</div></div><br>";
    echo "<table id='mainTable'>";
    for($i=0;$i<=$noColors;$i++){
        echo "<tr><td>".$colors[$i]['clrName']."</td>";
        echo "<td><input type='color' name='".$colors[$i]['clrVariable']."' value='".$activeColors[$colors[$i]['clrVariable']]."'></td>";
        echo "</tr>";
    }
    echo "</table><input type='hidden' name='submitted' value='-1'>";
    echo "<br><div style='text-align:center;'><button type='submit' class='savBtn' name='mode' value='1'>Save</button> <button type='submit' class='cnlBtn' name='mode' value='0'>Cancel</button></div>";
    echo "</form><br><br>";
    echo "</body></html>";
    mysqli_close($dbc);
  }
}
if(!$logged){
  include('expired.php');
}
?>