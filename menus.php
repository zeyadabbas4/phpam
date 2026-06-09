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

    $pageTitle="Menu Editor";

    if(!isset($level)){
      $level=0;
    }

    $pageSubTitle[0]="Applications";
    $pageSubTitle[1]="Menus";
    $pageSubTitle[2]="Commands";
    $newButtonLabel[0]="New Application";
    $newButtonLabel[1]="New Menu";
    $newButtonLabel[2]="New Command";
    $childButton[0]="Menus";
    $childButton[1]="Commands";
    $addFormLabel[0]="Add new application";
    $addFormLabel[1]="Add new Menu";
    $addFormLabel[2]="Add new Command";
    $mnuNameTitle[0]="Application Name";
    $mnuNameTitle[1]="Menu Name";
    $mnuNameTitle[2]="Command Name";
    $viewFormLabel[0]="Application Details";
    $viewFormLabel[1]="Menu Details";
    $viewFormLabel[2]="Command Details";

    echo "<!DOCTYPE html><html><head>";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
    echo "<style>";
    echo "* { box-sizing: border-box;}";
    echo "#mainTable {border-collapse: collapse;width: 100%;border: 1px solid #ddd;font-size: 18px;}";
    echo "#mainTable th, #mainTable td {text-align: left;padding: 12px;}";
    echo "#mainTable tr {border-bottom: 1px solid #ddd;}";
    echo "#mainTable tr.header, #mainTable tr:hover {background-color: #f1f1f1;}";
    echo ".disBtn {background-color: darkgrey;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".navBtn {background-color: dodgerblue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".navBtn:hover {opacity: 1;}";
    echo ".addBtn {background-color: MidnightBlue ;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 150px;  opacity: 0.9;margin: auto;}";
    echo ".addBtn:hover {opacity: 1;}";
    echo ".edtBtn {background-color: teal;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".edtBtn:hover {opacity: 1;}";
    echo ".digBtn {background-color: #7B68EE;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".digBtn:hover {opacity: 1;}";
    echo ".srtBtn {background-color: Blue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 50px;  opacity: 0.9;}";
    echo ".srtBtn:hover {opacity: 1;}";
    echo ".delBtn {background-color: darkred;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".delBtn:hover {opacity: 1;}";
    echo ".savBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".savBtn:hover {opacity: 1;}";
    echo ".genBtn {background-color: DarkMagenta ;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".genBtn:hover {opacity: 1;}";
    echo ".viwBtn {background-color: Chocolate;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".viwBtn:hover {opacity: 1;}";
    echo ".cnlBtn {background-color: red;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".cnlBtn:hover {opacity: 1;}";
    echo ".okBtn {background-color: indigo;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".okBtn:hover {opacity: 1;}";
    echo ".grpBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".grpBtn:hover {opacity: 1;}";
    echo ".arrowBtn {background-color: DarkOrchid;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 50px;  opacity: 0.9;}";
    echo ".arrowBtn:hover {opacity: 1;}";
    echo ".usrBtn {background-color: Crimson;  color: white;  padding: 10px 10px;  border: 1px; border-color: black; cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".usrBtn:hover {opacity: 1;}";
    echo ".activeTabBtn {background-color: darkgrey;  color: black;  padding: 10px 10px;  border: 1px; border-color: black; cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".activeTabBtn:hover {opacity: 1;}";
    echo ".input-container {display: -ms-flexbox; /* IE10 */  display: flex;  width: 100%;  margin-bottom: 15px;}";
    echo ".input-field { width: 100%;  padding: 10px;  outline: none;}";
    echo ".input-field:focus {border: 2px solid dodgerblue;}";
    echo ".error{color: red; font-size: small;text-align:center;}";
    echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";    
    echo "</style>";
	  echo "</head>";
	  echo "<body>";
    echo "<h2 style='text-align: center;color: RoyalBlue;'>$pageTitle</h2>";
    
    if($mode==7){
      $level++;
    }

    if($level==1){
      $mnuParent=$parent[0];
    }elseif($level==2){
      $mnuParent=$parent[1];
    }

    if(isset($mnuParent)){
      //set parent name
      $q="select mnuName from menus where mnuId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $mnuParent);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $parentName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }
    }

    echo "<h3 style='text-align: center;color: RoyalBlue;'>".$pageSubTitle[$level];
    if($level!=0)
      echo " of $parentName";
    echo "</h3>";

    if($mode==10){
      //save new record
      if($mnuName==""){
        $errorMessage.="Name not set<br>";
        $mode=1;
      }
      if($level==0){
        if($mnuDirectory==""){
          $errorMessage.="Directory not set<br>";
          $mode=1;
        }
      }
      if($level==2){
        if($mnuCmdType==0){
          if($mnuCommand==""){
            $errorMessage.="Command file name not set<br>";
            $mode=1;
          }
        }
      }

      if($mode==10){
        if($level==0)
          $q="insert into menus(mnuName,mnuLevel,mnuDirectory) values(?,0,?)";
        elseif($level==1)
          $q="insert into menus(mnuName,mnuLevel,mnuParent) values(?,1,?)";
        else
          $q="insert into menus(mnuName,mnuLevel,mnuParent,mnuCmdType,mnuCommand) values(?,2,?,?,?)";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          if($level==0)
            mysqli_stmt_bind_param($stmt, "ss", $mnuName,$mnuDirectory);
          elseif($level==1)
            mysqli_stmt_bind_param($stmt, "sd", $mnuName,$mnuParent);
          else
            mysqli_stmt_bind_param($stmt, "sdds", $mnuName,$mnuParent,$mnuCmdType,$mnuCommand);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
        }
      }
    }

    if($mode==1){
      //New item form
      $showList=false;
      echo "<form method='post' style='max-width:700px;margin:auto'>";
      echo "<h3 style='text-align: center;'>".$addFormLabel[$level]."</h3>";
      echo "<table width='100%'>";
      echo "<tr><td style='width: 185px;'>".$mnuNameTitle[$level]."</td><td>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='".$mnuNameTitle[$level]."' name='mnuName' maxlength='45'";
      if(isset($mnuName))
        echo " value='$mnuName'";
      echo "></div></td></tr>";        
      if($level==0){
        echo "<tr><td style='width: 185px;'>Working Directory</td><td>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='Working Directory' name='mnuDirectory'";
        if(isset($mnuDirectory))
          echo " value='$mnuDirectory'";
        echo "></div></td></tr>";  
      }
      if($level==2){
        echo "<tr><td style='width: 185px;'>Command Type</td><td>";
        echo "<div class='input-container'>";
        echo "<select class='input-field' type='text' placeholder='File name' name='mnuCmdType'>";
        echo "<option value='0'";
        if(isset($mnuCmdType))
          if($mnuCmdType==0)
            echo " selected";
        echo ">File</option>";
        echo "<option value='1'";
        if(isset($mnuCmdType))
          if($mnuCmdType==1)
            echo " selected";
        echo ">Built-in Form</option>";
        echo "</select></div></td></tr>";
        echo "<tr><td style='width: 185px;'>File Name</td><td>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='File name' name='mnuCommand'";
        if(isset($mnuCommand))
          echo " value='$mnuCommand'";
        echo "></div></td></tr>";  
      }
      echo "</table>";
      echo "<input type='hidden' name='level' value='$level'>";
      if(isset($parent[0]))
        echo "<input type='hidden' name='parent[0]' value='".$parent[0]."'>";
      if(isset($parent[1]))
        echo "<input type='hidden' name='parent[1]' value='".$parent[1]."'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='10'>Save</button> <button type='submit' class='cnlBtn' name='mode' value='0'>Cancel</button></div>";
      echo "</form>";
      echo "<div class='error'><p>$errorMessage</p></div>";
    }

    if($mode==11){
      //save edited record
      if($mnuName==""){
        $errorMessage.="Name not set<br>";
        $mode=1;
      }
      if($level==0){
        if($mnuDirectory==""){
          $errorMessage.="Directory not set<br>";
          $mode=1;
        }
      }
      if($level==2){
        if($mnuCmdType==0){
          if($mnuCommand==""){
            $errorMessage.="Command file name not set<br>";
            $mode=1;
          }
        }
      }

      if($mode==11){
        if($level==0)
          $q="update menus set mnuName=?,mnuDirectory=? where mnuId=?";
        elseif($level==1)
          $q="update menus set mnuName=? where mnuId=?";
        else
          $q="update menus set mnuName=?,mnuDirectory=?,mnuCmdType=?,mnuCommand=? where mnuId=?";
        if ($stmt = mysqli_prepare($dbc, $q)){
          if($level==0)
            mysqli_stmt_bind_param($stmt, "ssi", $mnuName,$mnuDirectory,$mnuId);
          elseif($level==1)
            mysqli_stmt_bind_param($stmt, "si", $mnuName,$mnuId);
          else
            mysqli_stmt_bind_param($stmt, "ssisi", $mnuName,$mnuDirectory,$mnuCmdType,$mnuCommand,$mnuId);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);
        }
      }
    }

    if($mode==2){
      $showList=false;
      if($errorMessage ==""){
        if($level==0)
          $q="select mnuName,mnuDirectory from menus where mnuId=?";
        elseif($level==1)
          $q="select mnuName from menus where mnuId=?";
        else
          $q="select mnuName,mnuDirectory,mnuCmdType,mnuCommand from menus where mnuId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
          mysqli_stmt_bind_param($stmt, "i", $mnuId);
          mysqli_stmt_execute($stmt);
          if($level==0)
            mysqli_stmt_bind_result($stmt, $mnuName,$mnuDirectory);
          elseif($level==1)
            mysqli_stmt_bind_result($stmt, $mnuName);
          else
            mysqli_stmt_bind_result($stmt, $mnuName,$mnuDirectory,$mnuCmdType,$mnuCommand);
          mysqli_stmt_fetch($stmt);
          mysqli_stmt_close($stmt);
        }
      }
      echo "<form method='post' style='max-width:700px;margin:auto'>";
      echo "<h3 style='text-align: center;'>".$addFormLabel[$level]."</h3>";
      echo "<table width='100%'>";
      echo "<tr><td style='width: 185px;'>".$mnuNameTitle[$level]."</td><td>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='".$mnuNameTitle[$level]."' name='mnuName' maxlength='45'";
      if(isset($mnuName))
        echo " value='$mnuName'";
      echo "></div></td></tr>";        
      if($level==0){
        echo "<tr><td style='width: 185px;'>Working Directory</td><td>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='Working Directory' name='mnuDirectory'";
        if(isset($mnuDirectory))
          echo " value='$mnuDirectory'";
        echo "></div></td></tr>";  
      }
      if($level==2){
        echo "<tr><td style='width: 185px;'>Command Type</td><td>";
        echo "<div class='input-container'>";
        echo "<select class='input-field' type='text' placeholder='File name' name='mnuCmdType'>";
        echo "<option value='0'";
        if(isset($mnuCmdType))
          if($mnuCmdType==0)
            echo " selected";
        echo ">File</option>";
        echo "<option value='1'";
        if(isset($mnuCmdType))
          if($mnuCmdType==1)
            echo " selected";
        echo ">Built-in Form</option>";
        echo "</select></div></td></tr>";
        echo "<tr><td style='width: 185px;'>File Name</td><td>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='File name' name='mnuCommand'";
        if(isset($mnuCommand))
          echo " value='$mnuCommand'";
        echo "></div></td></tr>";  
      }
      echo "</table>";
      echo "<input type='hidden' name='mnuId' value='$mnuId'>";
      echo "<input type='hidden' name='level' value='$level'>";
      if(isset($parent[0]))
        echo "<input type='hidden' name='parent[0]' value='".$parent[0]."'>";
      if(isset($parent[1]))
        echo "<input type='hidden' name='parent[1]' value='".$parent[1]."'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='11'>Save</button> <button type='submit' class='cnlBtn' name='mode' value='0'>Cancel</button></div>";
      echo "</form>";
      echo "<div class='error'><p>$errorMessage</p></div>";
    }

    if($mode==3){
      //view record
      if($level==0)
        $q="select mnuName,mnuDirectory from menus where mnuId=?";
      elseif($level==1)
        $q="select mnuName from menus where mnuId=?";
      else
        $q="select mnuName,mnuDirectory,mnuCmdType,mnuCommand from menus where mnuId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $mnuId);
        mysqli_stmt_execute($stmt);
        if($level==0)
          mysqli_stmt_bind_result($stmt, $mnuName,$mnuDirectory);
        elseif($level==1)
          mysqli_stmt_bind_result($stmt, $mnuName);
        else
          mysqli_stmt_bind_result($stmt, $mnuName,$mnuDirectory,$mnuCmdType,$mnuCommand);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }
      $showList=false;
      $cmdType[0]="File";
      $cmdType[1]="Built-in Form";
      echo "<form method='post' style='max-width:700px;margin:auto'>";
      echo "<h3 style='text-align: center;'>".$viewFormLabel[$level]."</h3>";
      echo "<table width='100%'>";
      echo "<tr><td style='width: 185px;'>".$mnuNameTitle[$level]."</td><td>";
      echo "<div class='input-container'>$mnuName</div></td></tr>";        
      if($level==0){
        echo "<tr><td style='width: 185px;'>Working Directory</td><td>";
        echo "<div class='input-container'>$mnuDirectory</div></td></tr>";  
      }
      if($level==2){
        echo "<tr><td style='width: 185px;'>Command Type</td><td>";
        echo "<div class='input-container'>".$cmdType[$mnuCmdType]."</div></td></tr>";
        echo "<tr><td style='width: 185px;'>File Name</td><td>";
        echo "<div class='input-container'>$mnuCommand</div></td></tr>";  
      }
      echo "</table>";
      echo "<input type='hidden' name='level' value='$level'>";
      if(isset($parent[0]))
        echo "<input type='hidden' name='parent[0]' value='".$parent[0]."'>";
      if(isset($parent[1]))
        echo "<input type='hidden' name='parent[1]' value='".$parent[1]."'>";
      echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode' value='0'>Ok</button></div>";
      echo "</form>";
      echo "<div class='error'><p>$errorMessage</p></div>";
    }

    if($mode==4){
      //delete confirmation
      $showList=false;
      $q="select mnuName from menus where mnuId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $mnuId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $mnuName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }
      echo "<form method='post' style='max-width:500px;margin:auto'>";
      echo "<h2 style='text-align: center;'>Delete</h2>";
      echo "<div style='text-align: center;'>Are you sure you want to delete $mnuName ?...</div><br>";
      echo "<input type='hidden' name='mnuId' value='$mnuId'>";
      echo "<input type='hidden' name='level' value='$level'>";
      if(isset($parent[0]))
        echo "<input type='hidden' name='parent[0]' value='".$parent[0]."'>";
      if(isset($parent[1]))
        echo "<input type='hidden' name='parent[1]' value='".$parent[1]."'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='12'>Yes</button> <button type='submit' class='cnlBtn' name='mode' value='0'>No</button></div>";
      echo "</form>";
    }

    if($mode==12){
      $q="delete from menus where mnuId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $mnuId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
    }

    if($mode==8){
      //move up
      $movedItem=$mnuId;
      $parentClause="";
      if($level!=0){
        $parentClause="and mnuParent=".$parent[$level-1];
      }
      $listQuery="select mnuId,mnuOrder from menus where mnuLevel=$level $parentClause order by mnuOrder";
      $r=mysqli_query($dbc,$listQuery);
      if($r){
        $o=1;
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          $menu[$o]['id']=$mnuId;
          $menu[$o]['order']=$mnuOrder;
          $o++;
        }
      }
      for($i=1;$i<$o;$i++){
        if($menu[$i]['id']==$movedItem){ //if this is the item to be moved up
          if($i!=1){ // if not first item
            //shuffle
            $ti=$menu[$i]['id'];
            $to=$menu[$i]['order'];
            $menu[$i]['id']=$menu[$i-1]['id'];
            $menu[$i]['order']=$menu[$i-1]['order'];
            $menu[$i-1]['id']=$ti;
            $menu[$i-1]['order']=$to;
          }
        }
        $menu[$i]['newOrder']=$i;
      }
      for($i=1;$i<$o;$i++){
        if($menu[$i]['order']!=$menu[$i]['newOrder']){
          $q="update menus set mnuOrder=? where mnuId=?";
          if ($stmt = mysqli_prepare($dbc, $q)){
            mysqli_stmt_bind_param($stmt, "ii", $menu[$i]['newOrder'],$menu[$i]['id']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
          }  
        }
      }
    }
    if($mode==9){
      //move down
      $movedItem=$mnuId;
      $parentClause="";
      if($level!=0){
        $parentClause="and mnuParent=".$parent[$level-1];
      }
      $listQuery="select mnuId,mnuOrder from menus where mnuLevel=$level $parentClause order by mnuOrder";
      $r=mysqli_query($dbc,$listQuery);
      if($r){
        $o=1;
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          $menu[$o]['id']=$mnuId;
          $menu[$o]['order']=$mnuOrder;
          $menu[$o]['newOrder']=$o;
          $o++;
        }
      }
      for($i=1;$i<$o;$i++){
        if($menu[$i]['id']==$movedItem){ //if this is the item to be moved down
          if($i!=$o-1){ // if not first item
            //shuffle
            $ti=$menu[$i]['id'];
            $to=$menu[$i]['order'];
            $menu[$i]['id']=$menu[$i+1]['id'];
            $menu[$i]['order']=$menu[$i+1]['order'];
            $menu[$i+1]['id']=$ti;
            $menu[$i+1]['order']=$to;
            $i++;
          }
        }
      }
      for($i=1;$i<$o;$i++){
        if($menu[$i]['order']!=$menu[$i]['newOrder']){
          $q="update menus set mnuOrder=? where mnuId=?";
          if ($stmt = mysqli_prepare($dbc, $q)){
            mysqli_stmt_bind_param($stmt, "ii", $menu[$i]['newOrder'],$menu[$i]['id']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
          }  
        }
      }
    }
    
    if($mode==13){
      $q="insert into usermenus(usrmnuUser,usrmnuMenu) values(?,?)";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "ii", $from,$mnuId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
      $mode=5;
    }
    if($mode==14){
      $q="insert into usermenus(usrmnuUser,usrmnuMenu) select usrId,? as mnuId from users where usrId not in(select usrmnuUser from usermenus where usrmnuMenu=?)";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "ii", $mnuId,$mnuId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
      $mode=5;
    }
    if($mode==15){
      $q="delete from usermenus where usrmnuUser=? and usrmnuMenu=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "ii", $to,$mnuId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
      $mode=5;
    }
    if($mode==16){
      $q="delete from usermenus where usrmnuMenu=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $mnuId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
      $mode=5;
    }
    
    if($mode==5){
      $showList=false;
      $q="select mnuName from menus where mnuId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt, "i", $mnuId)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $mnuName)){
              mysqli_stmt_fetch($stmt);
            }
          }
        }
        mysqli_stmt_close($stmt);
      }
      echo "<h3 style='text-align: center;'>Users with access to $mnuName</h3>";
      echo "<form method='post' style='max-width:700px;margin:auto'>";
      echo "<table width='100%'><tr>";
      echo "<td align='center'>";
      $q="SELECT usrId,usrFullName FROM users WHERE usrId NOT IN(SELECT usrmnuUser FROM usermenus WHERE usrmnuMenu=?) ORDER BY usrFullName";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt, "i", $mnuId)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $usrId,$usrFullName)){
              echo "<Select style='width:250px;font-size: 18px;' size='10' name='from'>";
              while(mysqli_stmt_fetch($stmt)){
                echo "<option value='$usrId'>$usrFullName</option>";
              }
            }
          }
        }
        mysqli_stmt_close($stmt);
      }
      echo "</select>";
      echo "</td>";
      echo "<td>";
      echo "<button type='submit' class='arrowBtn' name='mode' value='13'>></button><br><br>";
      echo "<button type='submit' class='arrowBtn' name='mode' value='14'>>></button><br><br>";
      echo "<button type='submit' class='arrowBtn' name='mode' value='15'><</button><br><br>";
      echo "<button type='submit' class='arrowBtn' name='mode' value='16'><<</button><br>";
      echo "</td>";
      echo "<td>";
      $q="SELECT usrId,usrFullName FROM users WHERE usrId IN(SELECT usrmnuUser FROM usermenus WHERE usrmnuMenu=?) ORDER BY usrFullName";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt, "i", $mnuId)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $usrId,$usrFullName)){
              echo "<Select style='width:250px;font-size: 18px;' size='10' name='to'>";
              while(mysqli_stmt_fetch($stmt)){
                echo "<option value='$usrId'>$usrFullName</option>";
              }
            }
          }
        }
        mysqli_stmt_close($stmt);
      }
      echo "</select>";
      echo "</td>";
      echo "</tr></table><br>";
      echo "<input type='hidden' name='level' value='$level'>";
      if(isset($parent[0]))
        echo "<input type='hidden' name='parent[0]' value='".$parent[0]."'>";
      if(isset($parent[1]))
        echo "<input type='hidden' name='parent[1]' value='".$parent[1]."'>";
      echo "<input type='hidden' name='mnuId' value='$mnuId'>";
      echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode' value='0'>Ok</button></div>";
      echo "</form>";
    }

    if($mode==17){
      $q="insert into groupmenus(grpmnuGroup,grpmnuMenu) values(?,?)";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "ii", $from,$mnuId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
      $mode=6;
    }
    if($mode==18){
      $q="insert into groupmenus(grpmnuGroup,grpmnuMenu) select grpId,? as mnuId from groups where grpId not in(select grpmnuUser from grpmenus where grpmnuMenu=?)";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "ii", $mnuId,$mnuId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
      $mode=6;
    }
    if($mode==19){
      $q="delete from groupmenus where grpmnuGroup=? and grpmnuMenu=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "ii", $to,$mnuId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
      $mode=6;
    }
    if($mode==20){
      $q="delete from groupmenus where grpmnuMenu=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $mnuId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
      $mode=6;
    }
    
    if($mode==6){
      $showList=false;
      $q="select mnuName from menus where mnuId=?";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $mnuId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $mnuName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }
      echo "<h3 style='text-align: center;'>Groups with access to $mnuName</h3>";
      echo "<form method='post' style='max-width:700px;margin:auto'>";
      echo "<table width='100%'><tr>";
      echo "<td align='center'>";
      $q="select grpId,grpName from groups where grpId not in(select grpmnuGroup from groupmenus where grpmnuMenu=$mnuId)";
      $r=mysqli_query($dbc,$q);
      if($r){
        echo "<Select style='width:250px;font-size: 18px;' size='10' name='from'>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          echo "<option value='$grpId'>$grpName</option>";
        }
        echo "</select>";
        mysqli_free_result($r);
      }
      echo "</td>";
      echo "<td>";
      echo "<button type='submit' class='arrowBtn' name='mode' value='17'>></button><br><br>";
      echo "<button type='submit' class='arrowBtn' name='mode' value='18'>>></button><br><br>";
      echo "<button type='submit' class='arrowBtn' name='mode' value='19'><</button><br><br>";
      echo "<button type='submit' class='arrowBtn' name='mode' value='20'><<</button><br>";
      echo "</td>";
      echo "<td>";
      $q="select grpId,grpName from groups where grpId in(select grpmnuGroup from groupmenus where grpmnuMenu=$mnuId)";
      $r=mysqli_query($dbc,$q);
      if($r){
        echo "<Select style='width:250px;font-size: 18px;' size='10' name='to'>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          echo "<option value='$grpId'>$grpName</option>";
        }
        echo "</select>";
        mysqli_free_result($r);
      }
      echo "</td>";
      echo "</tr></table><br>";
      echo "<input type='hidden' name='level' value='$level'>";
      if(isset($parent[0]))
        echo "<input type='hidden' name='parent[0]' value='".$parent[0]."'>";
      if(isset($parent[1]))
        echo "<input type='hidden' name='parent[1]' value='".$parent[1]."'>";
      echo "<input type='hidden' name='mnuId' value='$mnuId'>";
      echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode' value='0'>Ok</button></div>";
      echo "</form>";
    }

    if($showList){

      $parentClause="";
      if($level!=0){
        $parentClause="and mnuParent=".$parent[$level-1];
      }
      $listQuery="select mnuId,mnuName from menus where mnuLevel=$level $parentClause order by mnuOrder";

      if($__uid==0){
        echo "<div style='text-align:center;margin:auto;'><form method='post'>";
        echo "<input type='hidden' name='mode' value='1'>";
        echo "<input type='hidden' name='level' value='$level'>";
        if($level==1){
          echo "<input type='hidden' name='parent[0]' value='".$parent[0]."'>";
        }elseif($level==2){
          echo "<input type='hidden' name='parent[0]' value='".$parent[0]."'>";
          echo "<input type='hidden' name='parent[1]' value='".$parent[1]."'>";
        }
        echo "<button type='submit' class='addBtn'>".$newButtonLabel[$level]."</button>";
        echo "</form></div><br>";  
      }

      $r=mysqli_query($dbc,$listQuery);
      if($r){
        echo "<table id='mainTable'>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
            echo "<tr><td>$mnuName</td>";
            echo "<td style='text-align: right;'><form method='post'>";
            echo "<input type='hidden' name='mnuId' value='$mnuId'>";
            echo "<input type='hidden' name='level' value='$level'>";
            if($level==0){
              echo "<input type='hidden' name='parent[0]' value='".$mnuId."'>";
            }elseif($level==1){
              echo "<input type='hidden' name='parent[0]' value='".$parent[0]."'>";
              echo "<input type='hidden' name='parent[1]' value='".$mnuId."'>";
            }else{
              echo "<input type='hidden' name='parent[0]' value='".$parent[0]."'>";
              echo "<input type='hidden' name='parent[1]' value='".$parent[1]."'>";              
            }
            if($__uid==0){
              echo "<button type='submit' class='edtBtn' name='mode' value='2'>Edit</button> ";
              echo "<button type='submit' class='viwBtn' name='mode' value='3'>View</button> ";
              echo "<button type='submit' class='delBtn' name='mode' value='4'>Delete</button> ";  
            }
            echo "<button type='submit' class='usrBtn' name='mode' value='5'>Users</button> ";
            echo "<button type='submit' class='grpBtn' name='mode' value='6'>Groups</button> ";
            if($level!=2){
              echo "<button type='submit' class='digBtn' name='mode' value='7'>".$childButton[$level]."</button> ";
            }
            if($__uid==0){
              echo "<button type='submit' class='srtBtn' name='mode' value='8'>&and;</button> ";
              echo "<button type='submit' class='srtBtn' name='mode' value='9'>&or;</button> ";  
            }
            echo "</form></td>";
            echo "</tr>";    
        }
        echo "</table>";        
        mysqli_free_result($r);

        if($level!=0){
          echo "<br><div style='text-align:center;margin:auto;'><form method='post'>";
          echo "<input type='hidden' name='mode' value='0'>";
          $prevLevel=$level-1;
          echo "<input type='hidden' name='level' value='$prevLevel'>";
          if(isset($parent[0]))
            echo "<input type='hidden' name='parent[0]' value='".$parent[0]."'>";
          if(isset($parent[1]))
            echo "<input type='hidden' name='parent[1]' value='".$parent[1]."'>";
          echo "<button type='submit' class='addBtn'>Back</button>";
          echo "</form></div>";    
        }
      }
    }
    mysqli_close($dbc);
    echo "</body></html>";
  }
}
if(!$logged){
  include('expired.php');
}
?>