<?php
//uncomment those two lines for debugging
//ini_set('display_errors',1); 
//error_reporting(E_ALL);
session_start();
if(isset($_SESSION['uid']))
{
  include('../sysdb.php');
  include('../functions.php');
  //include('functions.php');               //uncomment this line if you have a local functions file
  $usrid=$_SESSION['uid'];
  $filename=basename($_SERVER["SCRIPT_NAME"]);
  $scrid=getscreenid($filename);
  if(checkuserscreen($usrid,$scrid)){
    $PageTitle="";			   //write the title of your page
    include('../top.php');
    include('../title.php');
    foreach ($_POST as $key => $value){
		$$key=$value;
	}
		
    // Your code goes below this line


	include('connect.php');

    //read reference data

    $banks=array();
    $q="SELECT BankId,BankName FROM Banks";
	$r=mysqli_query($dbc,$q);
	if($r){
		while($row=mysqli_fetch_assoc($r)){
            foreach ($row as $key => $value){
                $$key=$value;
            }
            $banks[$BankId]=$BankName;
        }
    }
	$hits=0;
	$miss=0;

	if(isset($mode)){
		if($mode=="savedata"){
			$q="UPDATE Payroll SET pyrlBankCode=?, pyrlAcountNo=? WHERE pyrlStaff=?";
			if ($stmt = mysqli_prepare($dbc, $q)){
				foreach($saverec as $key => $value){
					if($value=="Yes"){
						if(mysqli_stmt_bind_param($stmt, "isi", $BankCode[$key],$AcountNo[$key],$staff[$key])){
							if(mysqli_stmt_execute($stmt)){
								$hits++;
							}else{
								$miss++;
							}
						}
					}
				}
				mysqli_stmt_close($stmt);
			}
		}
	}

	//Show the list
	echo "<center>";
	echo "<form method='post'>";
	echo "<br><button type='submit'>حفظ</button>&nbsp;&nbsp;&nbsp;<button type='button' onclick='window.location=\"salbanks.php\";'>تراجع</button><br><br>";
	echo "<table style='text-align:center;border-spacing:3px;border-color:$tblborderclr;'>";
	//query to get the record to be displayed
	$q="SELECT staffid,staffname,pyrlCode,IFNULL(pyrlBankCode,0) AS pyrlBankCode,IFNULL(pyrlAcountNo,'') AS pyrlAcountNo FROM staff INNER JOIN Payroll ON staffid = pyrlStaff ORDER BY pyrlCode";
	$r=mysqli_query($dbc,$q);
	if($r){
		//column headers - Add columns as needed
		echo "<tr style='font-size:medium;font-weight:bold;background-color:$tblheadbg;'>";
		echo "<td>&nbsp;مسلسل&nbsp;</td>";
		echo "<td>&nbsp;رقم ملف المرتب&nbsp;</td>";
		echo "<td>&nbsp;إسم الموظف&nbsp;</td>";
		echo str_repeat("<td>&nbsp;</td>",2);     //empty cells for action buttons
		echo "</tr>";
		$c=0;
		while($row=mysqli_fetch_array($r)){
			foreach ($row as $key => $value)
				if(!is_numeric($key))
					$$key=$value;
			$c++;
			echo "<tr style='font-size:medium;font-weight:bold;background-color:$tblcellbg;'>";
			echo "<td style='text-align:center;'>&nbsp; $c &nbsp;</td>";
			echo "<td style='text-align:center;'>&nbsp; $pyrlCode &nbsp;</td>";
			echo "<td style='text-align:right;'>&nbsp; $staffname &nbsp;</td>";
			echo "<td>";
			echo "<input type='hidden' name='staff[$c]' value='$staffid'>";
			echo "<input type='hidden' name='saverec[$c]' value='No' id='saverec$c'>";
			echo "<select name='BankCode[$c]' id='bnk$c' onchange='toggleSave(\"saverec$c\");clearAct($c);'><option value='0'";
			if($pyrlBankCode == 0){
				echo " selected";
			}
			echo ">خزينة المعهد</option>";
			foreach($banks as $key => $value){
				echo "<option value='$key'";
				if($pyrlBankCode == $key){
					echo " selected";
				}	
				echo ">$value</option>";
			}
			echo "</select>";
			echo "</td>\r\n";
			echo "<td>";
			echo "<input type='text' name='AcountNo[$c]' id='act$c' placeholder='رقم الحساب' value='$pyrlAcountNo' onchange='toggleSave(\"saverec$c\");'>";
			echo "</td>\r\n";
			echo "</tr>\r\n";
		}
		mysqli_free_result($r);
	}
	echo "</table>";
	echo "<input type='hidden' name='mode' value='savedata'>";
	echo "<br><button type='submit'>حفظ</button>&nbsp;&nbsp;&nbsp;<button type='button' onclick='window.location=\"salbanks.php\";'>تراجع</button><br><br>";
	echo "</form>";
	echo "</center>";
?>
<script>
function toggleSave(varId){
	document.getElementById(varId).value="Yes";
}
function clearAct(c){
	bank = "bnk" + c;
	acct = "act" + c;
	if(document.getElementById(bank).selectedIndex == 0){
		document.getElementById(acct).value="";
	}
}
</script>
<?php

	mysqli_close($dbc);
    // Your code ends above this line
    include('../bot.php');
  }else{
	$target="../index.php";
    include('../redirect.php');
  }
}else{
  $target="../index.php";
  include('../redirect.php');
}
?>
