<?php
echo "<!DOCTYPE html>";
echo "<html><head><meta charset=\"UTF-8\">";
echo "<title>System login</title>";
echo "<style>";
echo "body {font-family: Arial, Helvetica, sans-serif;text-align: center;} * {box-sizing: border-box;}";
echo ".btn {background-color: dodgerblue;  color: white;  padding: 15px 20px;  border: none;  cursor: pointer;  width: 100%;  opacity: 0.9;}";
echo ".btn:hover {opacity: 1;}";
echo "h2{color: #30589a;}";
echo ".error{color: red; font-size: small;text-align:center}";
echo "</style>";
echo "</head>";
echo "<body><h2>Create root password</h2>";
echo "<div style='width: 400px; margin: auto;'>";
foreach($_POST as $key => $value)
	$$key=$value;
$errorMessage="";
if(isset($submitted)){
	if($pwd1 != $pwd2){
		$errorMessage.="Passwords do not match!<br>";
		unset($submitted);
	}elseif($pwd1==""){
		$errorMessage.="Empty password!<br>";
		unset($submitted);
	}else{
		$pattern = '/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';
		if(!preg_match($pattern, $pwd1)){
			$errorMessage.="Password did not pass complexity check!<br>";
			unset($submitted);
		}
	}
	if($errorMessage == ""){
		$pwdhsh=password_hash($pwd1, PASSWORD_DEFAULT);
		$myfile = fopen("../rtph.php", "w") or die("Unable to open file!");
		$txt = "<?php \$rtph='$pwdhsh'; ?>";
		fwrite($myfile, $txt);
		fclose($myfile);
		echo "Password saved!...<br><br>";
		echo "<button type='button' class='btn' onclick='window.location=\"createsdb.php\"'> Next </button>";
	}
}
if(!isset($submitted)){
	echo "<form method='post'>";
	echo "<div style='width: 100%;text-align: left;'>Enter password:</div>";
	echo "<input type='password' name='pwd1' style='width: 100%;'>";
	echo "<br><br><div style='width: 100%;text-align: left;'>Verify password:</div>";
	echo "<input type='password' name='pwd2' style='width: 100%;'>";
	echo "<br><br><button type='submit' class='btn'>Create</button>";
	echo "<input type='hidden' name='submitted' value='-1'>";
	echo "</form></div>";
	echo "<div class='error'><p>$errorMessage</p></div>";
}
echo "</body></html>";
?>
