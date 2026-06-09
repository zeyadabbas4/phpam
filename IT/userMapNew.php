<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$__systemRoot="../";                        //path to system root
include($__systemRoot."functions.php");     //system Functions don't remove
//include('functions.php');                   //uncomment this line if you have a local functions file
include("appdb.php");                       //application database credentials
$__dir="rtl";
$pageTitle="User Mapping";
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
if(isset($_POST['mode'])){
        $mode=$_POST['mode'];
}
if(isset($_POST['umpStaffId'])){
        $umpStaffId=$_POST['umpStaffId'];
}
if(isset($_POST['umpUserId'])){
        $umpUserId=$_POST['umpUserId'];
}
if(isset($_POST['oldumpStaffId'])){
        $oldumpStaffId=$_POST['oldumpStaffId'];
}
if(isset($_POST['oldumpUserId'])){
        $oldumpUserId=$_POST['oldumpUserId'];
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
    $fileName="storeAdd.php";
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

        ////////////////////////////////////////////////////////////////////////////
        // Save new record                                                        //
        ////////////////////////////////////////////////////////////////////////////
        if($mode == 'saveadd'){
            $err=false;
            echo "<div style='text-align: center; color: red;' dir='ltr'>";
            echo "</div>";
            if(!$err){
                $q="insert into  staffUserMap(umpStaffId,umpUserId) values(?,?)";
                if($stmt = mysqli_prepare($dbc, $q)){
                    mysqli_stmt_bind_param($stmt, "ii", $umpStaffId,$umpUserId);
                    if(mysqli_stmt_execute($stmt)){
                            $newid=mysqli_insert_id($dbc);
                    }else{
                            $errorMessage.= "Error saving data!...<br>";
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }

        ////////////////////////////////////////////////////////////////////////////
        //save edit data                                                          //
        ////////////////////////////////////////////////////////////////////////////
        if($mode == 'saveedit'){
            $err=false;
            echo "<div style='text-align: center; color: red;' dir='ltr'>";
            echo "</div>";
            if(!$err){
                $q="update staffUserMap set umpStaffId=?,umpUserId=? where umpStaffId=? and umpUserId=?";
                if($stmt = mysqli_prepare($dbc, $q)){
                    mysqli_stmt_bind_param($stmt, "iiii", $umpStaffId,$umpUserId,$oldumpStaffId,$oldumpUserId);
                    if(mysqli_stmt_execute($stmt)){
                            $newid=mysqli_insert_id($dbc);
                    }else{
                            $errorMessage.= "Error saving data!...<br>";
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }

        ////////////////////////////////////////////////////////////////////////////
        // delete                                                                 //
        ////////////////////////////////////////////////////////////////////////////
        if($mode == 'delete'){
            $q="delete from staffUserMap where umpStaffId=? and umpUserId=?";
            if($stmt = mysqli_prepare($dbc, $q)){
                mysqli_stmt_bind_param($stmt, "ii", $umpStaffId,$umpUserId);
                if(!mysqli_stmt_execute($stmt)){
                    $errorMessage.= "Error saving data!...<br>";
                }
                mysqli_stmt_close($stmt);
            }
        }

        ////////////////////////////////////////////////////////////////////////////
        // Add/edit forms                                                         //
        ////////////////////////////////////////////////////////////////////////////
        if($mode == 'add' || $mode == 'edit'){      
            $showtable=false;
            echo "<p style='font-size: large;font-weight: bold;text-align: center;'>Users' years</p>\r\n";
            echo "<form method='post'>\r\n";
            echo "<center>";
            echo "<table style='width:500px;;border-spacing:3px;'>\r\n";
            echo "<tr><td>User Name</td><td>:</td><td><select name='umpUserId'><option value='0'>-- حدد اسم المستخدم --</option>";
            $allusers=getUserList();
            $regusers=array();
            $q="select umpUserId from staffUserMap";
            $r=mysqli_query($dbc,$q);
            if($r){
                while($row=mysqli_fetch_array($r)){
                    $regUserId=$row['umpUserId'];
                    $regusers[]=$regUserId;
                }
            }
            $users=array_diff($allusers,$regusers);
            $i=count($users);
            for($j=0;$j<$i;$j++){
                echo "<option value='".$users[$j]['id']."'";
                if(isset($umpUserId)){
                    if($umpUserId==$users[$j]['id']) echo " selected";
                }
                echo ">".$users[$j]['name']."</option>";
            }
            echo "</select></td></tr>\r\n";
            echo "<tr><td>Staff Name</td><td>:</td><td><select name='umpStaffId'><option value='0'>-- حدد اسم الموظف --</option>";
            $q="select staffid,staffname from staff where staffdeleted=0 and staffid not in(select umpStaffId from staffUserMap) order by staffname";
            $r=mysqli_query($dbc,$q);
            if($r){
                while($row=mysqli_fetch_array($r)){
                    $staffid=$row['staffid'];
                    $staffname=$row['staffname'];
                    echo "<option value='$staffid'";
                    if(isset($umpStaffId)){
                        if($umpStaffId==$staffid) echo " selected";
                    }
                    echo ">$staffname</option>";                      
                }
                mysqli_free_result($r);
            }
            mysqli_close($dbc);            
            echo "</select></td></tr>\r\n";
            $modevalue="save$mode";
            echo "<tr><td align='center' colspan='3'>";
            if(isset($umpUserId)){
                    echo "<input type='hidden' name='oldumpUserId' value='$umpUserId'>";
            }
            if(isset($umpUserId)){
                    echo "<input type='hidden' name='oldumpStaffId' value='$umpStaffId'>";
            }
            echo "<button type='submit' class='savBtn' name='mode' value='$modevalue'>Save</button> ";
            echo "<button type='button' class='cnlBtn' onclick='window.location=\"userMapNew.php\"'>Cancel</button>";
            echo "</td></tr></table></center></form>\r\n";
        }

        ////////////////////////////////////////////////////////////////////////////
        // delete confirm                                                         //
        ////////////////////////////////////////////////////////////////////////////
        if($mode == 'deleteConfirm'){
            $showtable=false;
            $userDetails=getUserDetails($umpStaffId);
            $username=$userDetails['usrFullName'];
            echo "<p style='font-size: large;font-weight: bold;text-align: center;'>User year delete confirmation</p>";
            echo "<form method='post'>";
            echo "<table align='center' border='0' width='500px'>";
            echo "<tr><td align='center'>&nbsp;Do you really want to delete the year for $username ?&nbsp;</td></tr>";
            echo "<tr><td align='center'>";
            echo "<input type='hidden' value='$umpStaffId' name='umpStaffId'>";
            echo "<input type='hidden' value='$umpUserId' name='umpUserId'>";
            echo "<button type='submit' class='savBtn' name='mode' value='delete'>Save</button> ";
            echo "<button type='submit' class='cnlBtn' name='mode' value='0'>Cancel</button>";
            echo "</td></tr>";
            echo "</table></form>";
        }

        ////////////////////////////////////////////////////////////////////////////
        //Show the list                                                           //
        //first add a new record form                                             //
        //new form                                                                //
        ////////////////////////////////////////////////////////////////////////////
        if($showtable){
            echo "<div style='width: 110px; margin: auto;'><form method='post'>";
            echo "<input type='hidden' name='mode' value='add'>";
            echo "<button type='submit' class='addBtn'>New</button>";
            echo "</form></div><br>";
            echo "<center>";
            echo "<table id='mainTable'>";
            echo "<tr class='header'><th> # - Staff Name -> User Name </th><th style='width:600px;text-align: center;'>&nbsp;</th></tr>";
            $rowNo=0;
            $q="select umpStaffId,umpUserId,staffid,staffname from staffUserMap inner join staff on staffId=umpStaffId";
            $r=mysqli_query($dbc,$q);
            if($r){
                while($row=mysqli_fetch_array($r)){
                    $umpStaffId=$row['umpStaffId'];
                    $umpUserId=$row['umpUserId'];
                    $staffid=$row['staffid'];
                    $staffname=$row['staffname'];
                    $userDetails=getUserDetails($umpUserId);
                    $username=$userDetails['usrFullName'];
                    $rowNo++;
                    echo "<tr><td>$rowNo - $staffname -> $username </td>\r\n";
                    echo "<td style='text-align: right;'><form method='post'>";
                    echo "<input type='hidden' name='umpStaffId' value='$umpStaffId'>";
                    echo "<input type='hidden' name='umpUserId' value='$umpUserId'>";
                    echo "<button type='submit' class='edtBtn' name='mode' value='edit'>Edit</button> ";
                    echo "<button type='submit' class='delBtn' name='mode' value='deleteConfirm'>Delete</button> ";
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

