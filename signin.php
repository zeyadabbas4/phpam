<?php
//ini_set('display_errors',1); 
//error_reporting(E_ALL);
session_start();
if(isset($_POST['usrnam']))
	$usrnam=$_POST['usrnam'];
if(isset($_POST['passwd']))
	$passwd=$_POST['passwd'];
$errorMessage="";
$error=false;
$target="";
$usrId=-1;
include ("functions.php");
$dbc=sysdbConnect();
if(isset($usrnam)){
	if($usrnam!=""){
		if(isset($passwd)){
			if($passwd!=""){
				if($usrnam=="root"){
					//check for root password
					include("rtph.php");
					if(password_verify($passwd, $rtph)){
						$target="root.php";
						$usrId=0;
					}else{
						$errorMessage.="Invalid password<br>";
						$error=true;
					}
				}else{
					//chesk for user and password
					$q="select * from users where usrName=?";
					if ($stmt = mysqli_prepare($dbc, $q)){
						mysqli_stmt_bind_param($stmt, "s", $usrnam);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_bind_result($stmt,$usrId,$usrName,$usrPassword,$usrFullName,$usrClass,$usrAllowRemote,$usrEnabled);
						mysqli_stmt_fetch($stmt);
						mysqli_stmt_close($stmt);
						if($usrEnabled==1){
							if(password_verify($passwd, $usrPassword)){
								$target="main.php";
								//$usrId=$usrId;
							}else{
								$errorMessage.="Invalid password for $usrnam<br>";
								$error=true;
							}	
						}else{
							$errorMessage.="$usrnam is disabled...<br>";
							$error=true;
						}
					}else{
						$errorMessage.="Database error!...<br>";
						$error=true;
					}
				}	
			}else{
				$errorMessage.="Missing password<br>";
				$error=true;
			}
		}else{
			$error=true;
		}
	}else{
		$errorMessage.="Missing user name<br>";
		$error=true;
	}
}else{
	$error=true;
}
if($error){
	$_SESSION['usrnam']=$usrnam;
	$_SESSION['errorMessage']=$errorMessage;
	$target="login.php";
}else{
	$_SESSION['__uid']=$usrId;
}
//echo "$target<br>$usrnam<br>$errorMessage";
//echo mysqli_error($dbc);
header("Location: $target");
?>
