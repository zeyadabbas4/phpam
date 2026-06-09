<?php
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


//Load Active Colors
$q="select clrId,clrGroup,clrgrpNameAr,clrArName,clrVariable,usrclrColorValue from (usercolors inner join colornames on clrId=usrclrColorId) inner join colorgroups on clrGroup=clrgrpId where clrGroup<4 and usrclrUserId=$__uid order by clrGroup";
$r=mysqli_query($dbc,$q);
$i=0;
if($r){
  while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
    foreach($row as $key => $value){
      $userColors[$i][$key]=$value;
    }
    $i++;
  }
  mysqli_free_result($r);
}
$noUserColors=$i-1;

if($noUserColors<0){
  //Load Color Names
  $q="select clrId,clrGroup,clrgrpNameAr,clrArName,clrVariable from colornames inner join colorgroups on clrGroup=clrgrpId where clrGroup<4 order by clrGroup";
  $r=mysqli_query($dbc,$q);
  $i=0;
  if($r){
    while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
      foreach($row as $key => $value){
        $userColors[$i][$key]=$value;
      }
      $userColors[$i]['usrclrColorValue']=getSystemValue($userColors[$i]['clrVariable']);
      $i++;
    }
    mysqli_free_result($r);
  }
  $noUserColors=$i-1;
}
  
if(isset($mode)){
  if($mode==1){
    //load submitted user colors into array
    for($i=0;$i<=$noUserColors;$i++){
      $colorVariable=$userColors[$i]['clrVariable'];
      $userColors[$i]['usrclrColorValue']=$$colorVariable;
      $colorArray[$i]['Id']=$userColors[$i]['clrId'];
      $colorArray[$i]['Value']=$$colorVariable;
    }
    setUserColors($__uid,$colorArray);
  }
  if($mode==2){
    for($i=0;$i<=$noUserColors;$i++){
      $userColors[$i]['usrclrColorValue']=$defaultColors[$userColors[$i]['clrVariable']];
    }
  }
}
//The List
echo "<form method='post'>";
echo "<h3 style='text-align:center;color:#B22255;'>تعديل ألوان النظام</h3>";
echo "<div style='width:500px;height:65px;margin:auto;text-align:center;'>";
echo "<button type='submit' class='defBtn' name='mode' value='2'>اﻷلوان الافتراضية</button>";
echo "</div><br>";
echo "<table id='colorsTable'>";  
$i=0;
$oldGroup=0;
for($i=0;$i<=$noUserColors;$i++){
    if($oldGroup != $userColors[$i]['clrGroup']){
        echo "<tr style='border-bottom: 1px solid #ddd;'><td colspan='2' style='text-align:center;font-weight:bold;text-decoration:underline;'>".$userColors[$i]['clrgrpNameAr']."</td></tr>";
        $oldGroup = $userColors[$i]['clrGroup'];
    }
    echo "<tr style='border-bottom: 1px solid #ddd;'><td>".$userColors[$i]['clrArName']."</td>";
    echo "<td><input type='color' name='".$userColors[$i]['clrVariable']."' value='".$userColors[$i]['usrclrColorValue']."'></td>";
    echo "</tr>";
}
echo "</table><input type='hidden' name='submitted' value='-1'>";
echo "<br><div style='text-align:center;'><button type='submit' class='savBtn' name='mode' value='1'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value='0'>تراجع</button></div>";
echo "</form><br><br>";
echo "</body></html>";
mysqli_close($dbc);
?>