<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$__systemRoot="../";                        //path to system root
include($__systemRoot."functions.php");     //system Functions don't remove
include('../edu/functions.php');                   //uncomment this line if you have a local functions file
include("appdb.php");                       //application database credentials
foreach($_POST as $key => $value){
  $$key=$value;
}

if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
  die("Could not connect to database please contact system admin...");
}
$logDir=$__systemRoot."/logs";
$logFile="edu.log";
$__dir="rtl";
$showForm1=true;
$showForm2=false;
$logged=false;
$formCourseStatusLevel=6;
if(!isset($mode)){
  $mode='';
}
if(!isset($errorMessage)){
    $errorMessage="";
  }
if(!isset($message)){
    $message="";
}
if(isset($_SESSION['__uid'])){
    $__uid=$_SESSION['__uid'];
    $fileName = basename($_SERVER['PHP_SELF']);
    $mnuId=getCommandMenuId($fileName);
    if(checkUserMenuItem($__uid,$mnuId)){
        $logged=true;
    }
    if($logged){
        $user_year=getuseryear($__uid,$dbc);
        $pageTitle=" بدل الانتقال للمناطق ";
        $pageSubTitle="";
        echo "<!DOCTYPE html><html><head>";
        echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
        echo "<title>$pageTitle</title>";
        echo "<style>";
        echo ":root{direction: rtl;text-align: right;}";
        echo "* { box-sizing: border-box;}";
        echo "#filterBox {background-image: url('images/searchicon.png');background-position: 10px 10px;background-repeat: no-repeat;width: 100%;font-size: 16px;padding: 12px 20px 12px 40px;border: 1px solid #ddd;margin-bottom: 12px;}";
        echo "#mainTable {border-collapse: collapse;width: 100%;border: 1px solid #ddd;font-size: 18px;}";
        echo "#mainTable th, #mainTable td {text-align: right;padding: 12px;}";
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
        echo ".yesBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
        echo ".yesBtn:hover {opacity: 1;}";
        echo ".noBtn {background-color: red;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
        echo ".noBtn:hover {opacity: 1;}";
        echo ".genBtn {background-color: DarkMagenta ;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
        echo ".genBtn:hover {opacity: 1;}";
        echo ".viewBtn {background-color: Chocolate;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
        echo ".viewBtn:hover {opacity: 1;}";
        echo ".cnlBtn {background-color: red;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
        echo ".cnlBtn:hover {opacity: 1;}";
        echo ".disabledBtn {background-color: grey;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
        echo ".disabledBtn:hover {opacity: 1;}";
        echo ".okBtn {background-color: indigo;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
        echo ".okBtn:hover {opacity: 1;}";
        echo ".grpBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
        echo ".grpBtn:hover {opacity: 1;}";
        echo ".arrowBtn {background-color: DarkOrchid;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 50px;  opacity: 0.9;}";
        echo ".arrowBtn:hover {opacity: 1;}";
        echo ".dstSelect {padding: 10px 10px;width: 150px;}";
        echo ".trvlTextBox {padding: 10px 10px;width: 150px;}";
        echo ".input-container {display: -ms-flexbox; /* IE10 */  display: flex;  width: 100%;  margin-bottom: 15px;}";
        echo ".input-field { width: 100%;  padding: 10px;  outline: none;}";
        echo ".input-field:focus {border: 2px solid dodgerblue;}";
        echo ".error{color:red; font-weight:bold;text-align:center;direction:rtl;}";
        echo ".success{color:green; font-weight:bold;text-align:center;direction:rtl;}";
        echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";   
        echo ".lecTable{border-style:solid;border-color:black;border-width:1px;border-spacing:0px;}" ;
        echo ".lecTable td{border-style:solid;border-color:black;border-width:1px;}";
        echo ".lecTable th{border-style:solid;border-color:black;border-width:1px;text-align:center;}";
        echo ".mainDoc{text-align: center;width: 98%;margin: auto;background-color:#ddd; border-radius:5px;}";
        echo ".nameHeader{width:74%;float:right;background-color:#fff;text-align: center;border-radius:5px;margin:2px;padding:10px;font-size:large;font-weight:bold;}";
        echo ".amountHeader{width:10%;float:right;background-color:#fff;text-align: center;border-radius:5px;margin:2px;padding:10px;font-size:large;font-weight:bold;}";
        echo ".nameCell{width:74%;float:right;background-color:#fff;text-align: right;border-radius:5px;margin:2px;padding:19px;}";
        echo ".amountCell{width:10%;float:right;background-color:#fff;text-align: center;border-radius:5px;margin:2px;padding:19px;}";
        echo ".nameField{width:74%;float:right;background-color:#fff;text-align: right;border-radius:5px;margin:2px;padding:10px;}";
        echo ".amountField{width:10%;float:right;background-color:#fff;text-align: center;border-radius:5px;margin:2px;padding:10px;}";
        echo ".buttons{width:15%;float:right;background-color:#fff;text-align: center;border-radius:5px;margin:2px;padding:10px;font-size:large;font-weight:bold;}";
        echo ".totalLabel{width:74%;float:right;background-color:#fff;text-align: center;border-radius:5px;margin:2px;padding:10px;font-size:large;font-weight:bold;}";
        echo ".totalAmount{width:10%;float:right;background-color:#fff;text-align: center;border-radius:5px;margin:2px;padding:10px;font-size:large;font-weight:bold;}";
        echo ".bottomButtons{width:100%;text-align:center;padding-bottom:10px;}";
        echo "body{direction:rtl;font-size:large;font-weight:bold;text-align: center;}";
        echo "</style>";
        echo "</head>";
        echo "<body>";
        echo "<br><h2 style='text-align: center;color: RoyalBlue;'>$pageTitle</h2>";
        echo "<br><h3 style='text-align: center;color: RoyalBlue;'>$pageSubTitle</h3>";
/*********************************************************************************************************** */
//Load ref data
/*********************************************************************************************************** */
        $dists=array();
        $q="SELECT dist_id,dist_name FROM districts";
        if ($stmt = mysqli_prepare($dbc, $q)){
            if(mysqli_stmt_execute($stmt)){
                if(mysqli_stmt_bind_result($stmt, $dist_id,$dist_name)){
                    while(mysqli_stmt_fetch($stmt)){
                        $dists[$dist_id]=$dist_name;
                    }
                }
            }
            mysqli_stmt_close($stmt);
        }

/*********************************************************************************************************** */
//validation
/*********************************************************************************************************** */
        if($mode=='show'){
            if($fromDistrict == -1){
                $errorMessage .= "لابد من اختيار المدينة اﻷولى<br>";
            }
            if($toDistrict == -1){
                $errorMessage .= "لابد من اختيار المدينة الثانية<br>";
            }
            if($fromDistrict == $toDistrict){
                $errorMessage .= "لابد من اختيار مدينتين مختلفتين<br>";
            }
        }
        if($mode=="SaveFee"){
            if($forthFee == 0){
                $errorMessage .= "لابد من ادخال بدل الانتقال في حالة الذهاب<br>";
            }
            if($backFee == 0){
                $errorMessage .= "لابد من ادخال بدل الانتقال في حالة العودة<br>";
            }            
        }

/*********************************************************************************************************** */
//get fees
/*********************************************************************************************************** */
        if($mode == "show"){
            if($errorMessage == ""){
                $q="SELECT dstrAmount FROM districtTravel WHERE dstrFrom=? AND dstrTo=?";
                if ($stmt = mysqli_prepare($dbc, $q)){
                    if(mysqli_stmt_bind_param($stmt, "ii", $fromDistrict,$toDistrict)){
                        if(mysqli_stmt_execute($stmt)){
                            if(mysqli_stmt_bind_result($stmt,$dstrAmount)){
                                if(mysqli_stmt_fetch($stmt)){
                                    $forthFee=$dstrAmount;
                                }
                            }
                        }
                    }
                    mysqli_stmt_close($stmt);
                }
                $q="SELECT dstrAmount FROM districtTravel WHERE dstrFrom=? AND dstrTo=?";
                if ($stmt = mysqli_prepare($dbc, $q)){
                    if(mysqli_stmt_bind_param($stmt, "ii", $toDistrict,$fromDistrict)){
                        if(mysqli_stmt_execute($stmt)){
                            if(mysqli_stmt_bind_result($stmt,$dstrAmount)){
                                if(mysqli_stmt_fetch($stmt)){
                                    $backFee=$dstrAmount;
                                }
                            }
                        }
                    }
                    mysqli_stmt_close($stmt);
                }
                $showForm1=false;
                $showForm2=true;    
            }
        }
        
/*********************************************************************************************************** */
//save fees
/*********************************************************************************************************** */
        if($mode == "SaveFee"){
            if($errorMessage == ""){
                $forthSaved=false;
                $backSaved=false;
                if(isset($updateForth)){
                    $q="UPDATE districtTravel SET dstrAmount=? WHERE dstrFrom=? AND dstrTo=?";
                    $param=array($forthFee,$fromDistrict,$toDistrict);
                    $types="dii";
                }else{
                    $q="INSERT INTO districtTravel(dstrFrom,dstrTo,dstrAmount) VALUES(?,?,?)";
                    $param=array($fromDistrict,$toDistrict,$forthFee);
                    $types="iid";
                }                
                if ($stmt = mysqli_prepare($dbc, $q)){
                    if(mysqli_stmt_bind_param($stmt, $types, ...$param)){
                        if(mysqli_stmt_execute($stmt)){
                            $forthSaved=true;
                        }
                    }
                    mysqli_stmt_close($stmt);
                }
                if(isset($updateBack)){
                    $q="UPDATE districtTravel SET dstrAmount=? WHERE dstrFrom=? AND dstrTo=?";
                    $param=array($backFee,$toDistrict,$fromDistrict);
                    $types="dii";
                }else{
                    $q="INSERT INTO districtTravel(dstrFrom,dstrTo,dstrAmount) VALUES(?,?,?)";
                    $param=array($toDistrict,$fromDistrict,$backFee);
                    $types="iid";
                }
                if ($stmt = mysqli_prepare($dbc, $q)){
                    if(mysqli_stmt_bind_param($stmt, $types, ...$param)){
                        if(mysqli_stmt_execute($stmt)){
                            $backSaved=true;
                        }
                    }
                    mysqli_stmt_close($stmt);
                }
                if($forthSaved and $backSaved){
                    $message="تم الحفظ بنجاح";
                }else{
                    $errorMessage="خطأ في الحفظ";
                }

            }else{
                $showForm1=false;
                $showForm2=true;                
            }
        }

/*********************************************************************************************************** */
//list
/*********************************************************************************************************** */
        if($errorMessage != ""){
            echo "<div class='error'>$errorMessage<br><br></div>";
        }
        if($message != ""){
            echo "<div class='success'>$message<br><br></div>";
        }
        if($showForm1){
            echo "<form method='post'>";
            echo "<p>&nbsp;&nbsp;&nbsp;بدل الانتقال بين:&nbsp;&nbsp;";
            echo "<select name='fromDistrict' id='fromDistrict' class='dstSelect'><option value='-1'>---</option>";
            foreach($dists as $key => $value){
                echo "<option value='$key'";
                if(isset($fromDistrict)){
                    if($fromDistrict==$key){
                        echo " selected";
                    }
                }
                echo ">$value</option>";
            }
            echo "</select>";
            echo "&nbsp;&nbsp;و&nbsp;&nbsp;";
            echo "<select name='toDistrict' id='toDistrict' class='dstSelect'><option value='-1'>---</option>";
            foreach($dists as $key => $value){
                echo "<option value='$key'";
                if(isset($toDistrict)){
                    if($toDistrict==$key){
                        echo " selected";
                    }
                }
                echo ">$value</option>";
            }
            echo "</select>&nbsp;&nbsp;&nbsp;";
            echo "<button type='submit' class='viewBtn' name='mode' value='show'>عرض</button>";
            echo "</p>";
            echo "</form>";
    
        }
        if($showForm2){
            echo "<form method='post'>";
            echo "<p>&nbsp;&nbsp;&nbsp;بدل الانتقال بين:&nbsp;&nbsp;".$dists[$fromDistrict]."&nbsp;&nbsp;و&nbsp;&nbsp;" . $dists[$toDistrict];
            echo "<p>&nbsp;&nbsp;&nbsp;ذهاب:&nbsp;&nbsp;";
            echo "<input type='number' class='trvlTextBox' name='forthFee' min='0' max='10000'";
            if(isset($forthFee)){
                echo " value='$forthFee'";
                
            }
            echo ">";
            echo "&nbsp;&nbsp;عودة:&nbsp;&nbsp;";
            echo "<input type='number' class='trvlTextBox' name='backFee' min='0' max='10000'";
            if(isset($backFee)){
                echo " value='$backFee'";
            }
            echo ">";
            echo "&nbsp;&nbsp;&nbsp;";
            if(isset($forthFee)){
                echo "<input type='hidden' name='updateForth' value='1'>";    
            }
            if(isset($backFee)){
                echo "<input type='hidden' name='updateBack' value='1'>";    
            }
            echo "<input type='hidden' name='fromDistrict' value='$fromDistrict'>";
            echo "<input type='hidden' name='toDistrict' value='$toDistrict'>";
            echo "<button type='submit' class='savBtn' name='mode' value='SaveFee'>حفظ</button> ";
            echo "<button type='submit' class='cnlBtn' onclick='window.location=\"$fileName\";'>تراجع</button> ";
            echo "</p>";
            echo "</form>";  
        }
    }
    mysqli_close($dbc);
    echo "</body></html>";
}
if(!$logged){
include($__systemRoot."expired.php");
}
?>