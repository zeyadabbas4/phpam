<?php
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getUserPermissions($userId){
    $userPerms=array();
    $dbc = sysdbconnect();
    if($dbc){
        $q="select usrpermValue,usrpermPermissionId,permName from permissions inner join userpermissions on permId=usrpermPermissionId where usrpermUserId=?";
        if($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $usrpermValue,$usrpermPermissionId,$permName);
        while(mysqli_stmt_fetch($stmt)){
            $userPerms[$permName]=$usrpermValue;
        }
        mysqli_stmt_close($stmt);
        }
    }
    return $userPerms;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function appendLog($logDir,$logFile,$message,$incidentId){
    if($myfile = fopen("$logDir/$logFile", "a")){
        $timStamp=date("d-m-Y H:i:s");
        $txt = "<incident>\r\n<timestamp>$timStamp</timestamp>\r\n<incidentId>$incidentId</incidentId>\r\n<message>$message</message>\r\n</incident>\r\n";
        fwrite($myfile, $txt);
        fclose($myfile);            
    }else{
        return false;
    }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function radioGroup($optionsArray,$groupName,$orintation,$defaultIndex=-1){
    if(is_array($optionsArray)){
        foreach($optionsArray as $key => $value){
            echo $value['label']." ";
            echo "<input type='radio' name='$groupName' value='".$value['value']."'";
            if($key==$defaultIndex) echo " checked";
            echo "> ";
            if($orintation=='V' or $orintation='v') echo "<br>";
        }
    }else{
        echo "array expected found a single value";
    }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function checkboxGroup($optionsArray){
    if(is_array($optionsArray)){
        foreach($optionsArray as $key => $value){
            echo $value['label']." ";
            checkbox($value['controlName'],$value['checkedValue'],$value['nonCheckedValue'],$value['checked']);
            echo "<br>";
        }
    }else{
        echo "array expected found a single value";
    }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function checkbox($controlName,$checkedValue,$nonCheckedValue,$checked=false){
    echo "<input type='hidden' name='$controlName' value='$nonCheckedValue'>";
    echo "<input type='checkbox' name='$controlName' value='$checkedValue'";
    if($checked){
        echo " checked";
    }
    echo ">"; 
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function dropdownlist($dbc,$sql,$valueField,$labelField,$controlName,$disabled=false,$defaultValue='',$showBlank=false,$blankValue=0,$controlWidth=0){
    echo "<select name='$controlName' id='$controlName'";
    if($controlWidth!=0)
        echo " style='width:".$controlWidth."'";
    if($disabled==true)
        echo " disabled";
    echo ">";
    if($showBlank==true)
        echo "<option value='$blankValue'>&nbsp;&nbsp;&nbsp;&nbsp;</option>";
    $q=$sql;
    $r=mysqli_query($dbc,$q);
    if($r)
    {
      while($row=mysqli_fetch_array($r))
      {
        $$labelField=$row[$labelField];
        $$valueField=$row[$valueField];
        echo "<option value='".$$valueField."'";
        if($defaultValue==$$valueField) echo " selected";
        echo ">".$$labelField . "</option>";
      }
      mysqli_free_result($r);
      echo "</select>";
    } 
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function dropdownlista($labelArray,$controlName,$disabled=false,$defaultValue='',$showBlank=false,$blankValue=0,$controlWidth=0,$class=''){
    echo "<select name='$controlName' id='$controlName'";
    if($controlWidth!=0)
        echo " style='width:".$controlWidth."'";
    if($class !=  '')
        echo " Class='$class'";
    if($disabled)
        echo " disabled";
    echo ">";
    if($showBlank){
        echo "<option value='$blankValue'>&nbsp;&nbsp;&nbsp;&nbsp;</option>";
    }
    foreach($labelArray as $key => $value){
        echo "<option value='$key'";
        if($defaultValue==$key) echo " selected";
        echo ">$value</option>";
    }
    echo "</select>";
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function dropdownlistv($labelArray,$controlName,$disabled=false,$defaultValue='',$showBlank=false,$blankValue=0,$controlWidth=0){
    echo "<select name='$controlName' id='$controlName'";
    if($controlWidth!=0)
        echo " style='width:".$controlWidth."'";
    if($disabled)
        echo " disabled";
    echo ">";
    if($showBlank){
        echo "<option value='$blankValue'>&nbsp;&nbsp;&nbsp;&nbsp;</option>";
    }
    foreach($labelArray as $value){
        echo "<option value='$value'";
        if($defaultValue==$value) echo " selected";
        echo ">$value</option>";
    }
    echo "</select>";
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function dbConnect($db_host,$db_schema,$db_user,$db_password){
    $dbc=false;
    $dbc=@mysqli_connect($db_host,$db_user,$db_password,$db_schema) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
    if (!mysqli_set_charset($dbc, "utf8")){
        printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
    }
    return $dbc;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getCommandMenuId($commandFileName){
    $mnuId=-1;
    $dbc = sysdbconnect();
    $q="select * from menus where mnuCommand='$commandFileName'";
    $r=mysqli_query($dbc,$q);
    if($r){
        if($row=mysqli_fetch_array($r)){
            $mnuId=$row['mnuId'];
        }
        mysqli_free_result($r);
    }
    mysqli_close($dbc);
    return($mnuId);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function checkUserMenuItem($usrId,$mnuId){
  $result=0;
  $dbc = sysdbconnect();
  $q="select * from usermenus where usrmnuUser=$usrId and usrmnuMenu=$mnuId";
  $r=mysqli_query($dbc,$q);
    if($r){
	  if($row=mysqli_fetch_array($r)){
	      $result=-1;
	  }
	  mysqli_free_result($r);
    }
    mysqli_close($dbc);
    return($result);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function setUserColors($userId,$colorArray){
    $dbc = sysdbconnect();
    if($dbc){
        $q="delete from usercolors where usrclrUserId=?";
        if ($stmt = mysqli_prepare($dbc,$q)){
            mysqli_stmt_bind_param($stmt, "i", $userId);
            mysqli_stmt_execute($stmt);    
            mysqli_stmt_close($stmt);
        }
        $n=count($colorArray);
        $q="insert into usercolors(usrclrUserId,usrclrColorId,usrclrColorValue) values(?,?,?)";
        if ($stmt = mysqli_prepare($dbc,$q)){
            for($i=0;$i<$n;$i++){
                mysqli_stmt_bind_param($stmt, "iis", $userId,$colorArray[$i]['Id'],$colorArray[$i]['Value']);
                mysqli_stmt_execute($stmt);    
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($dbc);    
    }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getusercolors($uid){
    $dbc = sysdbconnect();
    if($dbc){
        $q="select usrclrColorValue,clrVariable from usercolors inner join colornames on clrId=usrclrColorId where usrclrUserId=?";
        if ($stmt = mysqli_prepare($dbc, $q)){
            mysqli_stmt_bind_param($stmt, "s", $uid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt,$usrclrColorValue,$clrVariable);
            while(mysqli_stmt_fetch($stmt)){
                $usercolors[$clrVariable]=$usrclrColorValue;
            }
            mysqli_stmt_close($stmt);
        }
        if(!isset($usercolors)){
            $q="select clrVariable from colornames";
            if ($stmt = mysqli_prepare($dbc, $q)){
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$clrVariable);
                while(mysqli_stmt_fetch($stmt)){
                    $usercolors[$clrVariable]=getSystemValue($clrVariable);
                }
                mysqli_stmt_close($stmt);
            }            
        }    
        mysqli_close($dbc);
    }
    if(!isset($usercolors)){
        $usercolors=false;
    }
    return($usercolors);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getSystemVersion(){
    return "1.2";
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getUserDetails($uid){
    $dbc = sysdbconnect();
    $usrDetails=array();
    if($dbc){
        $q="select usrId,usrName,usrFullName,usrClass,usrAllowRemote,usrclsDescription from users inner join userclass on usrClass=usrclsId where usrId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $uid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt,$usrDetails['usrId'],$usrDetails['usrName'],$usrDetails['usrFullName'],$usrDetails['usrClass'],$usrDetails['usrAllowRemote'],$usrDetails['usrclsDescription']);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }
        mysqli_close($dbc);
    }
    return $usrDetails;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getUserClass($uid){
    $usrClass=false;
    $dbc = sysdbconnect();
    if($dbc){
        $q="select usrClass from users where usrId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "s", $uid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $usrClass);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }
        mysqli_close($dbc);
    }
    return $usrClass;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getSystemValue($sysvalname){
    $sysvalue=false;
    $dbc = sysdbconnect();
    if($dbc){
        $q="select svlValue from sysvalues where svlName=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "s", $sysvalname);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $sysvalue);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }
        mysqli_close($dbc);
    }
    return $sysvalue;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function setsystemvalue($valuename,$value){
    $vid=-1;
    $svlValue="";
    $dbc = sysdbconnect();
    if($dbc){
        $q="select svlValue from sysvalues where svlName=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "s", $valuename);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $svlValue);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }
        if($svlValue!=""){
            $q="update sysvalues set svlValue=? where svlName=?";
        }else{
            $q="insert into sysvalues(svlValue,svlName) values(?,?)";
        }
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "ss", $value,$valuename);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        mysqli_close($dbc);
    }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function sysdbConnect(){
    $dbc=false;
    if(isset($GLOBALS['__systemRoot'])){
        $sysRoot=$GLOBALS['__systemRoot'];
    }else{
        $sysRoot="";
    }
    $sysdb=$sysRoot."sys.db";
    if(file_exists($sysdb)){
        $handle=fopen($sysdb, "r");
        if($handle){
            $DB_HOST=substr(fgets($handle),0,-1);
            $DB_USER=substr(fgets($handle),0,-1);
            $DB_PASSWORD=substr(fgets($handle),0,-1);
            $DB_NAME=substr(fgets($handle),0,-1);
            $dbc=@mysqli_connect($DB_HOST,$DB_USER,$DB_PASSWORD,$DB_NAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
            if (!mysqli_set_charset($dbc, "utf8")){
              printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
            }
            fclose($handle);
        }
    }
    return $dbc;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getUserList(){
    $i=0;
    $dbc = sysdbconnect();
    $q="select * from users order by usrFullName";
    $r=mysqli_query($dbc,$q);
    if($r){
            while($row=mysqli_fetch_array($r)){
                $users[$i]['id']=$row['usrId'];
                $users[$i]['name']=$row['usrFullName'];
                $i++;
            }
            mysqli_free_result($r);
    }
    mysqli_close($dbc);
    return($users);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Older functions
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getrows($dbc,$q){
  $i=0;
  $rows=false;
  $r=mysqli_query($dbc,$q);
  if($r){
    while($row=mysqli_fetch_array($r,MYSQLI_ASSOC))
    {
      $rows[$i]=$row;
      $i++;
    }
    mysqli_free_result($r);
  }
  return $rows;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getfield($dbc,$sql,$name)
{
    $$name=FALSE;
    $q=$sql;
    $r=mysqli_query($dbc,$q);
    if($r)
    {
      if($row=mysqli_fetch_array($r))
      {
        $$name=$row[$name];
      }
      mysqli_free_result($r);
    }
    return $$name;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

function runquery($dbc,$sql)
{
    $err=TRUE;
    $q=$sql;
    $r=mysqli_query($dbc,$q);
    if(!$r)
        $err=FALSE;
    return $err;    
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function ReadQuery($Query,$Key,$Data,$dbc){
        $q=$Query;
        $r=mysqli_query($dbc,$q);
        if($r)
        {
            while($row=mysqli_fetch_array($r))
              {
                foreach ($row as $key => $value)
                    $$key=$value;
                $Arr[$$Key]=$$Data;
              }
              mysqli_free_result($r);
        }
        else
        {
              echo '<div style="text-align: center; color: red;" dir="ltr">Database Error<br />'. mysqli_error($dbc) . '<br />Query : <br />' . $q . '</div>';
        }
        return $Arr;
}//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function ReadTable($Table,$Pkey,$Desc,$dbc){
        $q="SELECT * FROM $Table";
        $r=mysqli_query($dbc,$q);
        if($r)
        {
            while($row=mysqli_fetch_array($r))
              {
                foreach ($row as $key => $value)
                    $$key=$value;
                $Arr[$$Pkey]=$$Desc;
              }
              mysqli_free_result($r);
        }
        else
        {
              echo '<div style="text-align: center; color: red;" dir="ltr">Database Error<br />'. mysqli_error($dbc) . '<br />Query : <br />' . $q . '</div>';
        }
        return $Arr;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getformtypeid($frmtype)
{
    $typeid=-1;
    $dbc= @mysqli_connect(SYSDBSERVER,SYSDBDBUSER,decrypt(SYSDBEDBPASS),SYSDBDBASE) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
    if (!mysqli_set_charset($dbc, "utf8")) 
    {
             printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
    }
    $q="select * from frmtypes where frmtpname='$frmtype'";
    $r=mysqli_query($dbc,$q);
    if($r)
    {
            if($row=mysqli_fetch_array($r))
            {
                $typeid=$row['frmtpid'];
            }
            mysqli_free_result($r);
    }
    mysqli_close($dbc);
    return($typeid);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getformid($frmname)
{
    $frmid=-1;
    $dbc= @mysqli_connect(SYSDBSERVER,SYSDBDBUSER,decrypt(SYSDBEDBPASS),SYSDBDBASE) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
    if (!mysqli_set_charset($dbc, "utf8")) 
    {
             printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
    }
    $q="select * from forms where frmname='$frmname'";
    $r=mysqli_query($dbc,$q);
    if($r)
    {
            if($row=mysqli_fetch_array($r))
            {
                $frmid=$row['frmid'];
            }
            mysqli_free_result($r);
    }
    mysqli_close($dbc);
    return($frmid);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function checkform($frmname)
{
    $formexists=False;
    $dbc= @mysqli_connect(SYSDBSERVER,SYSDBDBUSER,decrypt(SYSDBEDBPASS),SYSDBDBASE) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
    if (!mysqli_set_charset($dbc, "utf8")) 
    {
             printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
    }
    $q="select * from forms where frmname='$frmname'";
    $r=mysqli_query($dbc,$q);
    if($r)
    {
            if($row=mysqli_fetch_array($r))
            {
                $formexists=True;
            }
            mysqli_free_result($r);
    }
    mysqli_close($dbc);
    return($formexists);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function checkformtype($frmtype)
{
    $typeexists=False;
    $dbc= @mysqli_connect(SYSDBSERVER,SYSDBDBUSER,decrypt(SYSDBEDBPASS),SYSDBDBASE) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
    if (!mysqli_set_charset($dbc, "utf8")) 
    {
             printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
    }
    $q="select * from frmtypes where frmtpname='$frmtype'";
    $r=mysqli_query($dbc,$q);
    if($r)
    {
            if($row=mysqli_fetch_array($r))
            {
                $typeexists=True;
            }
            mysqli_free_result($r);
    }
    mysqli_close($dbc);
    return($typeexists);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function setfrmvar($varname,$varvalue,$frmid,$varindex,$quiet=0,$disableenc=0)
{
    //get variable id
    $dbc= @mysqli_connect(SYSDBSERVER,SYSDBDBUSER,decrypt(SYSDBEDBPASS),SYSDBDBASE) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
    if (!mysqli_set_charset($dbc, "utf8")) 
    {
             printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
    }
    $q="select * from frmvarnames where frmvarnmname='$varname'";
    $r=mysqli_query($dbc,$q);
    if($r)
    {
            if($row=mysqli_fetch_array($r))
            {
                $varid=$row['frmvarnmid'];
                $enc=$row['frmvarnmenc'];
            }
            mysqli_free_result($r);
    }
    mysqli_close($dbc);
    if($varid != '')
    {
        //see if the variable is already set
        $dbc= @mysqli_connect(SYSDBSERVER,SYSDBDBUSER,decrypt(SYSDBEDBPASS),SYSDBDBASE) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
        if (!mysqli_set_charset($dbc, "utf8")) 
        {
                 printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
        }
        $q="select * from frmvars where frmvarvar=$varid and frmvarfrm=$frmid and frmvarindex=$varindex";
        $r=mysqli_query($dbc,$q);
        if($r)
        {
                if($row=mysqli_fetch_array($r))
                {
                    $frmvarvar=$row['frmvarvar'];
                }
                mysqli_free_result($r);
        }
        mysqli_close($dbc);
        if($disableenc==0)
            if($enc==1)
                $varvalue=encrypt ($varvalue);
        if($frmvarvar == $varid)
            $q="update frmvars set frmvarvalue='$varvalue' where frmvarvar=$varid and frmvarfrm=$frmid and frmvarindex=$varindex";
        else
            $q="insert into frmvars(frmvarvar,frmvarfrm,frmvarvalue,frmvarindex) values($varid,$frmid,'$varvalue',$varindex)";
        $dbc= @mysqli_connect(SYSDBSERVER,SYSDBDBUSER,decrypt(SYSDBEDBPASS),SYSDBDBASE) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
        if (!mysqli_set_charset($dbc, "utf8")) 
        {
                 printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
        }
        $r=mysqli_query($dbc,$q);
        if($r)
            if($quiet == 0)
                echo "Form Variable $varname($varindex)  set to $varvalue<br>";
        else
            if($quiet == 0)
                echo "Error Setting Form Variable $varname($varindex) set to $varvalue<br>$q<br>";
        mysqli_close($dbc);
    }
    else
        if($quiet == 0)
            echo "No such variable $varname($varindex)<br>";
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getformhandler($frmid){
    $dbc= @mysqli_connect(SYSDBSERVER,SYSDBDBUSER,decrypt(SYSDBEDBPASS),SYSDBDBASE) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
    if (!mysqli_set_charset($dbc, "utf8")) 
    {
             printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
    }
    $q="select * from forms,frmtypes where frmtpid=frmtype and frmid=$frmid";
    $r=mysqli_query($dbc,$q);
    if($r)
    {
            if($row=mysqli_fetch_array($r))
            {
                    $frmhandler=$row['frmtphandler'];

            }
            mysqli_free_result($r);
    }
    mysqli_close($dbc);
    return($frmhandler);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getusergroup($usrid){
    $dbc= @mysqli_connect(SYSDBSERVER,SYSDBDBUSER,decrypt(SYSDBEDBPASS),SYSDBDBASE) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
    if (!mysqli_set_charset($dbc, "utf8")) 
    {
             printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
    }
    $q="Select * from users where usrid=$usrid";
    $r=mysqli_query($dbc,$q);
    if($r)
    {
            if($row=mysqli_fetch_array($r))
            {
                    $usrgroup=$row['usrgroup'];

            }
            mysqli_free_result($r);
    }
    mysqli_close($dbc);
    return($usrgroup);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getuserremote($usrid){
    $dbc= @mysqli_connect(SYSDBSERVER,SYSDBDBUSER,decrypt(SYSDBEDBPASS),SYSDBDBASE) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
    if (!mysqli_set_charset($dbc, "utf8")) 
    {
             printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
    }
    $q="Select * from users where usrid=$usrid";
    $r=mysqli_query($dbc,$q);
    if($r)
    {
            if($row=mysqli_fetch_array($r))
            {
                    $usrallowremote=$row['usrallowremote'];

            }
            mysqli_free_result($r);
    }
    mysqli_close($dbc);
    if($usrallowremote=='Yes')
      return(-1);
    else
      return(0);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function filecreate($file)
{
  $handle = fopen($file, 'w');
  $r=False;
  if($handle)
  {
    $r=True;
    fclose($handle);
  }
  return($r);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function fileappend($file,$data)
{
  $handle = fopen($file, 'a');

  if($handle)
  {
    if(!fwrite($handle, $data . "\r\n"))
      $r=False;
    else
      $r=True;
    fclose($handle);
  }
  return($r);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function encrypt($plaintext){
$cyphertext1="";
for($i=0;$i<strlen($plaintext);$i++){
    $ch=substr($plaintext,$i,1);
    $chc=ord($ch);
    if($chc >=65 && $chc <=88 )
        $cyphertext1 .= chr($chc+1) . chr($chc+2) . "A";
    elseif($chc ==89)
        $cyphertext1 .= "ZAA";
    elseif($chc ==90)
        $cyphertext1 .= "ABA";
    elseif($chc >=97 && $chc <=120 )
        $cyphertext1 .= chr($chc-31) . chr($chc-30) . "B";
    elseif($chc ==121)
        $cyphertext1 .= "ZAB";
    elseif($chc ==122)
        $cyphertext1 .= "ABB";
    elseif($chc >=48 && $chc <=57 )
        $cyphertext1 .= chr($chc+17) . chr($chc+18) . "N";
    elseif($chc >=32 && $chc <=47 )
        $cyphertext1 .= chr($chc+33) . chr($chc+34) . "C";
    elseif($chc >=58 && $chc <=64 )
        $cyphertext1 .= chr($chc+7) . chr($chc+8) . "D";
    elseif($chc >=91 && $chc <=96 )
        $cyphertext1 .= chr($chc-26) . chr($chc-25) . "E";
    elseif($chc >=123 && $chc <=126 )
        $cyphertext1 .= chr($chc-58) . chr($chc-57) . "F";
    else
        $cyphertext1 .= chr($chc) . chr($chc) . "G";
    }
    $cypher2_1="";
    $cypher2_2="";
    $cypher2_3="";
    for($i=0;$i<strlen($cyphertext1);$i++){
        if($i % 3 == 0)
            $cypher2_1 .= substr($cyphertext1,$i,1);
        if($i % 3 == 1)
            $cypher2_2 .= substr($cyphertext1,$i,1);
        if($i % 3 == 2)
            $cypher2_3 .= substr($cyphertext1,$i,1);
    }
    $cyphertext2 = $cypher2_1 . $cypher2_2 . $cypher2_3;
    return($cyphertext2);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function decrypt($cyphertext){
$cypherlen=strlen($cyphertext);
$plain2_1=substr($cyphertext,0,$cypherlen/3);
$plain2_2=substr($cyphertext,$cypherlen/3,$cypherlen/3);
$plain2_3=substr($cyphertext,$cypherlen/3*2,$cypherlen/3);
for($i=0;$i < $cypherlen/3;$i++){
    $cyphertext2 .=substr($plain2_1,$i,1).substr($plain2_2,$i,1).substr($plain2_3,$i,1);
}
$plaintext1="";
for($i=0;$i<strlen($cyphertext2)/3;$i++){
    $ch=substr($cyphertext2,$i*3,3);
    $chg=substr($ch,2,1);
    if($chg == "A"){
        if(substr($ch,0,1) == "A")
                $plaintext1 .= "Z";
        else
               $plaintext1 .= chr(ord($ch)-1);
    }
    elseif($chg == "B"){
        if(substr($ch,0,1) == "A")
                $plaintext1 .= "z";
        else
               $plaintext1 .= chr(ord($ch)+31);
    }
    elseif($chg == "N")
        $plaintext1 .= chr(ord($ch)-17);
    elseif($chg == "C")
        $plaintext1 .= chr(ord($ch)-33);
    elseif($chg == "D")
        $plaintext1 .= chr(ord($ch)-7);
    elseif($chg == "E")
        $plaintext1 .= chr(ord($ch)+26);
    elseif($chg == "F")
        $plaintext1 .= chr(ord($ch)+58);
    else
      $plaintext1 .= chr(ord($ch));
}
return($plaintext1);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function filereadline($file,$line)
{
$handel = fopen($file, 'r');
for($i=1;$i<=$line;$i++)
{
	$theData = "";
	$theData = fgets($handel);
}
fclose($handle);
return($theData);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getuserip(){
  return $_SERVER['REMOTE_ADDR'];
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getserverlocalip(){
return getsystemvalue('ServerLocalIP');
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getserverlocalSubnet(){
return getsystemvalue('ServerLocalSubnet');
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function calcnetworkaddress($ip,$subnet){
$SubnetBytes=explode('.',$subnet);
$IPBytes=explode('.',$ip);
for($i=0;$i<=3;$i++){
  if($SubnetBytes[$i]=='0')
    $networkaddress.='0';
  elseif($SubnetBytes[$i]=='255')
    $networkaddress .= $IPBytes[$i];
  else {
  	$SubnetByteValue=intval($SubnetBytes[$i]) & intval($IPBytes[$i]);
  	$networkaddress .= $SubnetByteValue;
  }
  if($i !=3)
    $networkaddress .= '.';
}
return $networkaddress;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function isremoteuser(){
  $usrip=getuserip();
  $srvip=getserverlocalip();
  $srvsubnet=getserverlocalSubnet();
  $srvnetwork=calcnetworkaddress($srvip,$srvsubnet);
  $usrnetwork=calcnetworkaddress($usrip,$srvsubnet);
  if($usrnetwork==$srvnetwork)
    return 0;
   else 
    return -1;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function cr2break($string)
{
	for($i=0; $i < strlen($string); $i++)
	{
		$c=substr($string,$i,1);
		if($c == chr(13))
			$c='<br />';
		if($c != chr(10))
			$r = $r . $c;
	}
	return($r);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getuserfullname($usrid)
{
    $dbc= @mysqli_connect(SYSDBSERVER,SYSDBDBUSER,decrypt(SYSDBEDBPASS),SYSDBDBASE) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
    if (!mysqli_set_charset($dbc, "utf8")) 
    {
       printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
    } 
    $q="Select * from users where usrid=$usrid";
    $r=mysqli_query($dbc,$q);
    if($r)
    {
            if($row=mysqli_fetch_array($r))
            {
                    $usrfullname=$row['usrfullname'];

            }
            mysqli_free_result($r);
    }
    mysqli_close($dbc);
    return($usrfullname);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getusermessagecount($usrid)
{
    $dbc= @mysqli_connect(SYSDBSERVER,SYSDBDBUSER,decrypt(SYSDBEDBPASS),SYSDBDBASE) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
    if (!mysqli_set_charset($dbc, "utf8")) 
    {
       printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
    } 
    $q="Select count(usrmsgid) as msgcount from usermessages where trim(usrmsgto)='$usrid' and usrmsgread=0";
    $r=mysqli_query($dbc,$q);
    if($r)
    {
            if($row=mysqli_fetch_array($r))
            {
                    $msgcount=$row['msgcount'];
            }
            mysqli_free_result($r);
    }
    mysqli_close($dbc);
    return($msgcount);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function getuserid($username){
    $dbc= @mysqli_connect(SYSDBSERVER,SYSDBDBUSER,decrypt(SYSDBEDBPASS),SYSDBDBASE) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
    if (!mysqli_set_charset($dbc, "utf8")) 
    {
       printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
    } 
    $q="Select * from users where usrname='$username'";
    $r=mysqli_query($dbc,$q);
    if($r)
    {
            if($row=mysqli_fetch_array($r))
            {
                    $usrid=$row['usrid'];

            }
            mysqli_free_result($r);
    }
    mysqli_close($dbc);
    return($usrid);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function sendmessage($from,$to,$msg){
    $dbc= @mysqli_connect(SYSDBSERVER,SYSDBDBUSER,decrypt(SYSDBEDBPASS),SYSDBDBASE) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
    if (!mysqli_set_charset($dbc, "utf8")) 
    {
       printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
    } 
	$q="insert into usermessages(usrmsgfrom,usrmsgto,usrmsgmessage) values($from,$to,'$msg')";				
    $r=mysqli_query($dbc,$q);
    if($r)
		return(true);
    else
		return(false);
    mysqli_close($dbc);
}
?>
