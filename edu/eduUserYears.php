<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$__systemRoot="../";                        //path to system root
include($__systemRoot."functions.php");     //system Functions don't remove
include('functions.php');                   //uncomment this line if you have a local functions file
include("appdb.php");                       //application database credentials
$__dir="rtl";
$pageTitle="User Years for Education";
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
if(isset($_POST['mode'])){
        $mode=$_POST['mode'];
}
if(isset($_POST['UYuid'])){
        $UYuid=$_POST['UYuid'];
}
if(isset($_POST['UYyid'])){
        $UYyid=$_POST['UYyid'];
}
if(isset($_POST['oldUYuid'])){
        $oldUYuid=$_POST['oldUYuid'];
}
if(isset($_POST['oldUYyid'])){
        $oldUYyid=$_POST['oldUYyid'];
}
$showtable=true;
$logged=false;
$labelWidth="100px";
$formWidth="1000px";
if(!isset($mode)){
  $mode="";
}
if(!isset($errorMessage)){
  $errorMessage="";
}

if(isset($_SESSION['__uid'])){
        $__uid=$_SESSION['__uid'];
        $fileName = basename($_SERVER['PHP_SELF']);
        $mnuId=getCommandMenuId($fileName);
        if(checkUserMenuItem($__uid,$mnuId)){
          $logged=true;
        }
        if($logged){
                echo "<!DOCTYPE html><html><head>";
                echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
                echo "<title>$pageTitle</title>";
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
                echo ".error{color:red; font-weight:bold;text-align:center;direction:rtl;}";
                echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";    
                echo "</style>";
                echo "</head>";
                echo "<body>";
                echo "<br><h2 style='text-align: center;color: RoyalBlue;'>$pageTitle</h2>";            

                if($mode == 'SaveNewUser'){
                        //Save new record
                        //data validation
                        $err=false;
                        echo "<div style='text-align: center; color: red;' dir='ltr'>";
                        echo "</div>";
                        if(!$err){
                                $q="insert into UserYears(UYuid,UYyid,UYsid) values(?,?,'E')";
                                if($stmt = mysqli_prepare($dbc, $q)){
                                        mysqli_stmt_bind_param($stmt, "ii", $UYuid,$UYyid);
                                        if(mysqli_stmt_execute($stmt)){
                                                $newid=mysqli_insert_id($dbc);
                                        }else{
                                                $errorMessage.= "Error saving data!...<br>";
                                        }
                                        mysqli_stmt_close($stmt);
                                }
                        }
                }
                if($mode == 'SaveEditUser')            
                {
                        //save edit data
                        $err=false;
                        echo "<div style='text-align: center; color: red;' dir='ltr'>";
                        echo "</div>";
                        if(!$err){
                                $q="update UserYears set UYuid=?,UYyid=? where UYuid=? and UYyid=? and UYsid='E'";
                                if($stmt = mysqli_prepare($dbc, $q)){
                                        mysqli_stmt_bind_param($stmt, "iiii", $UYuid,$UYyid,$oldUYuid,$oldUYyid);
                                        if(mysqli_stmt_execute($stmt)){
                                                $newid=mysqli_insert_id($dbc);
                                        }else{
                                                $errorMessage.= "Error saving data!...<br>";
                                        }
                                        mysqli_stmt_close($stmt);
                                }
                        }
                }
                if($mode == 'DelUser')            //Delete
                {
                        $q="delete from UserYears where UYuid=? and UYyid=? and UYsid='E'";
                        if($stmt = mysqli_prepare($dbc, $q)){
                                mysqli_stmt_bind_param($stmt, "ii", $UYuid,$UYyid);
                                if(!mysqli_stmt_execute($stmt)){
                                        $errorMessage.= "Error saving data!...<br>";
                                }
                                mysqli_stmt_close($stmt);
                        }
                }
                if($mode == 'NewUser' || $mode == 'EditUser'){      //Add/Edit forms
                        $showtable=false;
                        echo "<p style='font-size: large;font-weight: bold;text-align: center;'>Users' years</p>\r\n";
                        echo "<form method='post'>\r\n";
                        echo "<center>";
                        echo "<table style='width:500px;;border-spacing:3px;'>\r\n";
                        echo "<tr><td>User</td><td>:</td><td><select name='UYuid'>";
                        $users=getUserList();
                        $i=count($users);
                        for($j=0;$j<$i;$j++){
                        echo "<option value='".$users[$j]['id']."'";
                        if(isset($UYuid)){
                                if($UYuid==$users[$j]['id']) echo " selected";
                        }
                        echo ">".$users[$j]['name']."</option>";
                        }
                        echo "</select></td></tr>\r\n";
                        echo "<tr><td>Year</td><td>:</td><td><select name='UYyid'>";
                        include('connect.php');
                        $q="select * from Years where YearType='E' order by YearId";
                        $r=mysqli_query($dbc,$q);
                        if($r){
                                while($row=mysqli_fetch_array($r)){
                                        $YearId=$row['YearId'];
                                        $YearDesc=$row['YearDesc'];
                                        echo "<option value='$YearId'";
                                        if(isset($UYyid)){
                                                if($UYyid==$YearId) echo " selected";
                                        }
                                        echo ">$YearDesc</option>";                      
                                }
                        mysqli_free_result($r);
                        }
                        mysqli_close($dbc);            
                        echo "</select></td></tr>\r\n";
                        if($mode == 'NewUser')
                                $modevalue='SaveNewUser';   // set the save new mode value
                        else
                                $modevalue='SaveEditUser';    //set the edit mode value
                        echo "<tr><td align='center' colspan='3'>";
                        if(isset($UYuid)){
                                echo "<input type='hidden' name='oldUYuid' value='$UYuid'>";
                        }
                        if(isset($UYyid)){
                                echo "<input type='hidden' name='oldUYyid' value='$UYyid'>";
                        }
                        echo "<button type='submit' class='savBtn' name='mode' value='$modevalue'>Save</button> ";
                        echo "<button type='submit' class='cnlBtn' name='mode' value='0'>Cancel</button>";
                        echo "</td></tr></table></center></form>\r\n";
                }
                if($mode == 'DelUserConfirm'){
                        //Delete confirmation
                        $showtable=false;
                        $userDetails=getUserDetails($UYuid);
                        $username=$userDetails['usrFullName'];
                        echo "<p style='font-size: large;font-weight: bold;text-align: center;'>User year delete confirmation</p>";
                        echo "<form method='post'>";
                        echo "<table align='center' border='0' width='500px'>";
                        echo "<tr><td align='center'>&nbsp;Do you really want to delete the year for $username ?&nbsp;</td></tr>";
                        echo "<tr><td align='center'>";
                        echo "<input type='hidden' value='$UYuid' name='UYuid'>";
                        echo "<input type='hidden' value='$UYyid' name='UYyid'>";
                        echo "<button type='submit' class='savBtn' name='mode' value='DelUser'>Save</button> ";
                        echo "<button type='submit' class='cnlBtn' name='mode' value='0'>Cancel</button>";
                        echo "</td></tr>";
                        echo "</table></form>";
                }
                if($showtable){
                        //Show the list
                        //first add a new record form
                        //new form
                        echo "<div style='width: 110px; margin: auto;'><form method='post'>";
                        echo "<input type='hidden' name='mode' value='NewUser'>";
                        echo "<button type='submit' class='addBtn'>New</button>";
                        echo "</form></div><br>";
                        echo "<center>";
                        echo "<table id='mainTable'>";
                        echo "<tr class='header'><th> User Name - Year </th><th style='width:600px;text-align: center;'>&nbsp;</th></tr>";
                        $rowNo=0;
                        $q="select * from UserYears inner join Years on UYyid=YearId where UYsid='E' order by UYuid";
                                $r=mysqli_query($dbc,$q);
                                if($r){
                                        while($row=mysqli_fetch_array($r)){
                                                $UYuid=$row['UYuid'];
                                                $UYyid=$row['UYyid'];
                                                $YearDesc=$row['YearDesc'];
                                                $userDetails=getUserDetails($UYuid);
                                                $username=$userDetails['usrFullName'];
                                                $rowNo++;
                                                echo "<tr><td>$rowNo - $username - $YearDesc </td>\r\n";
                                                echo "<td style='text-align: right;'><form method='post'>";
                                                echo "<input type='hidden' name='UYuid' value='$UYuid'>";
                                                echo "<input type='hidden' name='UYyid' value='$UYyid'>";
                                                echo "<button type='submit' class='edtBtn' name='mode' value='EditUser'>Edit</button> ";
                                                echo "<button type='submit' class='delBtn' name='mode' value='DelUserConfirm'>Delete</button> ";
                                                echo "</form></td></tr>\r\n";                                      
                                        }
                                        mysqli_free_result($r);
                                }else{
                                echo '<div style="text-align: center; color: red;" dir="ltr">Database Error<br />'. mysqli_error($dbc) . '<br />Query : <br />' . $q . '</div>';
                                }
                        mysqli_close($dbc);
                        echo "</table></center>";
                }
                echo "</body>";
                echo "</html>";
        }
}
if(!$logged){
  include($__systemRoot."expired.php");
}
?>

