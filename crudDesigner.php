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
    echo ".input-field {width: 100%;  padding: 10px;  outline: none;}";
    echo ".input-field:focus {border: 2px solid dodgerblue;}";
    echo ".small-input-field {width: 250px;  padding: 10px;  outline: none;}";
    echo ".small-input-field:focus {border: 2px solid dodgerblue;}";
    echo ".error{color: red; font-size: small;text-align:center;}";
    echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";
    echo ".tab {overflow: hidden; border: 1px solid #ccc; background-color: #f1f1f1;}";
    echo ".tab button {background-color: inherit; float: left; border: none; outline: none; cursor: pointer; padding: 14px 16px; transition: 0.3s; font-size: 17px;}";
    echo ".tab button:hover {background-color: #ddd;}";
    echo ".tab button.active {background-color: #ccc;}";
    echo ".tabcontent {display: none;padding: 6px 12px;border: 1px solid #ccc;border-top: none;}";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    echo "<h2 style='text-align: center;color: RoyalBlue;'>Form Builder</h2>";
    echo "<form method='post'>";

    if(!isset($currentTab)){
        $currentTab="tab1";
    }

    $activeStyle['tab1']="";
    $activeStyle['tab2']="";
    $activeStyle['tab3']="";
    $activeStyle['tab4']="";
    $activeStyle['tab5']="";
    $activeStyle[$currentTab]=" active";

    echo "<div class='tab'>";
    echo "    <button class='tablinks".$activeStyle['tab1']."' type='submit' value='tab1' name='currentTab'>Select form type</button>";
    echo "    <button class='tablinks".$activeStyle['tab2']."' type='submit' value='tab2' name='currentTab'>Database</button>";
    echo "    <button class='tablinks".$activeStyle['tab3']."' type='submit' value='tab3' name='currentTab'>Labels and titles</button>";
    echo "    <button class='tablinks".$activeStyle['tab4']."' type='submit' value='tab4' name='currentTab'>Columns</button>";
    echo "    <button class='tablinks".$activeStyle['tab5']."' type='submit' value='tab5' name='currentTab'>Finish</button>";
    echo "</div>";

    $tabStyle['tab1']="";
    $tabStyle['tab2']="";
    $tabStyle['tab3']="";
    $tabStyle['tab4']="";
    $tabStyle['tab5']="";
    $tabStyle[$currentTab]=" style='display:block;'";

    echo "<div id='tab1' class='tabcontent'".$tabStyle['tab1'].">";
    echo "<h3>Select form Type:</h3>";
    echo "<select name='formType'>";
    echo "<option value='crud'";
    if($formType=="crud") echo " selected";
    echo ">CRUD Form</option>";
    echo "<option value='mstr'";
    if($formType=="mstr") echo " selected";
    echo ">Master Detail</option>";
    echo "<option value='slct'";
    if($formType=="slct") echo " selected";
    echo ">Selector Form</option>";
    echo "</select>";
    echo "<div style='margin:auto;width:220px;'>";
    echo "<br>";
    echo "<button class='savBtn' type='submit' name='currentTab' value='tab2'>Next</button>";
    echo "</div>";
    echo "</div>";

    echo "<div id='tab2' class='tabcontent'".$tabStyle['tab2'].">";
    echo "<h3>Database server data:</h3>";
    echo "Server address:<br><input class='input-field' type='text' name='serveradr' value='$serveradr'>";
    echo "User name:<br><input class='input-field' type='text' name='username' value='$username'>";
    echo "Password:<br><input class='input-field' type='text' name='password' value='$password'><br><br><br>";
    echo "<div style='margin:auto;width:220px;'>";
    echo "<br>";
    echo "<button class='savBtn' type='submit' name='currentTab' value='tab1'>Previous</button> ";
    echo "<button class='savBtn' type='submit' name='currentTab' value='tab3'>Next</button>";
    echo "</div>";
    echo "</div>";

    echo "<div id='tab3' class='tabcontent'".$tabStyle['tab3'].">";
    echo "<h3>Titles and Labels</h3>";
    switch($formType){
        case 'crud':
        echo "<div style='width:500px;margin:auto'>";
        echo "Form title: <input class='small-input-field' type='text' name='frmTitle' value='$frmTitle'>";
        echo "</div>";
        echo "<table style='width:1000px;margin:auto;'><tr><td style='width:50%;'>";
        echo "<h4>Buttons</h4>";
        echo "<table>";
        echo "<tr><td>Add button label</td><td>:</td><td><input class='small-input-field' type='text' name='addLabel' value='$addLabel'></td></tr>";
        echo "<tr><td>Edit button label</td><td>:</td><td><input class='small-input-field' type='text' name='editLabel' value='$editLabel'></td></tr>";
        echo "<tr><td>View button label</td><td>:</td><td><input class='small-input-field' type='text' name='viewLabel' value='$viewLabel'></td></tr>";
        echo "<tr><td>Delete button label</td><td>:</td><td><input class='small-input-field' type='text' name='deleteLabel' value='$deleteLabel'></td></tr>";
        echo "<tr><td>Save button label</td><td>:</td><td><input class='small-input-field' type='text' name='saveLabel' value='$saveLabel'></td></tr>";
        echo "<tr><td>Cancel button label</td><td>:</td><td><input class='small-input-field' type='text' name='cancelLabel' value='$cancelLabel'></td></tr>";
        echo "<tr><td>Ok button label</td><td>:</td><td><input class='small-input-field' type='text' name='okLabel' value='$okLabel'></td></tr>";
        echo "<tr><td>Yes button label</td><td>:</td><td><input class='small-input-field' type='text' name='yesLabel' value='$yesLabel'></td></tr>";
        echo "<tr><td>No button label</td><td>:</td><td><input class='small-input-field' type='text' name='noLabel' value='$noLabel'></td></tr>";
        echo "</table>";
        echo "</td><td valign='top'>";
        echo "<h4>show/hide</h4>";
        echo "<input type='hidden' name='showAdd' value='no'>";
        echo "<input type='hidden' name='showEdit' value='no'>";
        echo "<input type='hidden' name='showView' value='no'>";
        echo "<input type='hidden' name='showDelete' value='no'>";
        echo "<input type='hidden' name='showSave' value='no'>";
        echo "<input type='hidden' name='showCancel' value='no'>";
        echo "<input type='hidden' name='showOk' value='no'>";
        echo "<input type='hidden' name='showYes' value='no'>";
        echo "<input type='hidden' name='showNo' value='no'>";
        if($showAdd=='yes') $showAddChecked=" checked";
        if($showEdit=='yes') $showEditChecked=" checked";
        if($showView=='yes') $showViewChecked=" checked";
        if($showDelete=='yes') $showDeleteChecked=" checked";
        if($showSave=='yes') $showSaveChecked=" checked";
        if($showCancel=='yes') $showCancelChecked=" checked";
        if($showOk=='yes') $showOkChecked=" checked";
        if($showYes=='yes') $showYesChecked=" checked";
        if($showNo=='yes') $showNoChecked=" checked";
        echo "<table>";
        echo "<tr><td>Show add button<br><br></td><td>:<br><br></td><td><input class='small-input-field' type='checkbox' name='showAdd' value='yes'$showAddChecked><br><br></td></tr>";
        echo "<tr><td>Show Edit button<br><br></td><td>:<br><br></td><td><input class='small-input-field' type='checkbox' name='showEdit' value='yes'$showEditChecked><br><br></td></tr>";
        echo "<tr><td>Show View button<br><br></td><td>:<br><br></td><td><input class='small-input-field' type='checkbox' name='showView' value='yes'$showViewChecked><br><br></td></tr>";
        echo "<tr><td>Show Delete button<br><br></td><td>:<br><br></td><td><input class='small-input-field' type='checkbox' name='showDelete' value='yes'$showDeleteChecked><br><br></td></tr>";
        echo "<tr><td>Show Save button<br><br></td><td>:<br><br></td><td><input class='small-input-field' type='checkbox' name='showSave' value='yes'$showSaveChecked><br><br></td></tr>";
        echo "<tr><td>Show Cancel button<br><br></td><td>:<br><br></td><td><input class='small-input-field' type='checkbox' name='showCancel' value='yes'$showCancelChecked><br><br></td></tr>";
        echo "<tr><td>Show Ok button<br><br></td><td>:<br><br></td><td><input class='small-input-field' type='checkbox' name='showOk' value='yes'$showOkChecked><br><br></td></tr>";
        echo "<tr><td>Show Yes button<br><br></td><td>:<br><br></td><td><input class='small-input-field' type='checkbox' name='showYes' value='yes'$showYesChecked><br><br></td></tr>";
        echo "<tr><td>Show No button<br><br></td><td>:<br><br></td><td><input class='small-input-field' type='checkbox' name='showNo' value='yes'$showNoChecked><br><br></td></tr>";
        echo "</table>";
        echo "</td></tr></table>";
        break;
        case 'mstr':
        break;
        case 'slct':
    }
    echo "<div style='margin:auto;width:220px;'>";
    echo "<br><br>";
    echo "<button class='savBtn' type='submit' name='currentTab' value='tab2'>Previous</button> ";
    echo "<button class='savBtn' type='submit' name='currentTab' value='tab4'>Next</button>";
    echo "</div>";
    echo "</div>";

    echo "<div id='tab4' class='tabcontent'".$tabStyle['tab4'].">";
    echo "Tab4";
    echo $formType;
    echo "</div>";

    echo "<div id='tab5' class='tabcontent'".$tabStyle['tab5'].">";
    echo "Tab5";
    echo $formType;
    echo "</div>";
/*
    include("dbpk.php");
    $ciphertext = sodium_crypto_secretbox($password, $nonce, $dbpk);
    $encoded = base64_encode($ciphertext);
    var_dump($encoded);
    $decoded = base64_decode($encoded);
    $plaintext = sodium_crypto_secretbox_open($decoded, $nonce, $dbpk);
    var_dump($plaintext);
*/
    echo "</form>";
    echo "</body></html>";
  }
}
if(!$logged){
  include('expired.php');
}
?>