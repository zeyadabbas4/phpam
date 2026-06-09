<?php
//uncomment those two lines for debugging
//ini_set('display_errors',1); 
//error_reporting(E_ALL);
session_start();
$__systemRoot="../";
include($__systemRoot.'sysdb.php');            //setup system databse connection
include($__systemRoot.'functions.php');        //include system functions
//include('functions.php');         		   //uncomment this line if you have a local functions file
include('appdb.php');             			   //include app database connection
if(isset($_SESSION['__uid'])){        		   //check for session and set the sesOk variable
    $__uid=$_SESSION['__uid'];
    $sesOk=true;
}else{
    $sesOk=false;
}
?>
<!DOCTYPE html>
<html dir="<?php echo $gdir; ?>">
    <head>
        <meta charset="utf-8">
        <title>Supervisors</title>
        <style>
    		* {
				box-sizing: border-box;
			}
			body {
				direction: rtl;
			}
    		#filterBox {
				background-image: url('images/searchicon.png');
				background-position: 10px 10px;
				background-repeat: no-repeat;
				width: 100%;
				font-size: 16px;
				padding: 12px 20px 12px 40px;
				border: 1px solid #ddd;
				margin-bottom: 12px;
			}
    		#masterTable {
				border-collapse: collapse;
				width: 100%;
				border: 1px solid #ddd;
				font-size: 18px;
			}
    		#masterTable th, #masterTable td {
				text-align: right;
				padding: 12px;
			}
    		#masterTable tr {
				border-bottom: 1px solid #ddd;
			}
    		#masterTable tr.header, #masterTable tr:hover {
				background-color: #f1f1f1;
			}
    		.navBtn {
				background-color: dodgerblue;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.navBtn:hover {
				opacity: 1;
			}
    		.addBtn {
				background-color: MidnightBlue;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
				margin: auto;
			}
    		.addBtn:hover {
				opacity: 1;
			}
    		.edtBtn {
				background-color: teal;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.edtBtn:hover {
				opacity: 1;
			}
    		.pwdBtn {
				background-color: Blue;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.pwdBtn:hover {
				opacity: 1;
			}
    		.delBtn {
				background-color: darkred;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.delBtn:hover {
				opacity: 1;
			}
    		.savBtn {
				background-color: green;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.savBtn:hover {
				opacity: 1;
			}
    		.genBtn {
				background-color: DarkMagenta;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.genBtn:hover {
				opacity: 1;
			}
    		.viewBtn {
				background-color: Chocolate;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.viewBtn:hover {
				opacity: 1;
			}
    		.cnlBtn {
				background-color: red;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.cnlBtn:hover {
				opacity: 1;
			}
    		.okBtn {
				background-color: indigo;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.okBtn:hover {
				opacity: 1;
			}
    		.grpBtn {
				background-color: green;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.grpBtn:hover {
				opacity: 1;
			}
    		.arrowBtn {
				background-color: DarkOrchid;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 50px;
				opacity: 0.9;
			}
			.arrowBtn:hover {
				opacity: 1;
			}
    		.input-container {
				display: -ms-flexbox; /* IE10 */  
				display: flex;
				width: 100%;
			}
    		.input-field {
				width: 100%;
				padding: 10px;
				outline: none;
			}
    		.input-field:focus {
				border: 2px solid dodgerblue;
			}
    		.alertmessages{
				color: red;
				font-size: medium;
				text-align:center;
			}
    		.frmButtons{
				text-align:center;
				margin: auto;
				width: 220px;
			}
			.infomessages{
				color: green;
				font-size: medium;
				text-align:center;
			}
			.cmpSupervisors{
				width: 100%;
				border-style: solid;
				border-width: 1px;
				border-collapse: collapse;
			}
			.serial{
				width: 50px;
				border-style: solid;
				border-width: 1px;
				text-align: center;
			}
			.supSalutation{
				width: 150px;
				border-style: solid;
				border-width: 1px;	
				text-align: center;
			}
			.supName{
				width: 500px;
				border-style: solid;
				border-width: 1px;
				text-align: center;	
			}
			.supAmount{
				width: 100px;
				border-style: solid;
				border-width: 1px;
				text-align: center;	
			}
			.formTable{
				width: 800px;
				border-style: none;
				margin: auto;
			}
			.fieldLable{
				width: 100px;
			}
			.field{
				width: 650px;
			}
		</style>
    </head>
    <body>
	<br><h2 style='text-align: center;color: RoyalBlue;'>بيانات المشرفين الخارجيين</h2>
<?php
if($sesOk){
    //session is up check for user permission!...   
    $filename=basename(__FILE__);                               //get script name
    $mnuId=getCommandMenuId($filename);                         //get sreen id
    if(checkUserMenuItem($__uid,$mnuId)){                       //check for user permission to use the screen
        $prmOk=true;
    }else{
        $prmOk=false;
        echo "<br><div class='alertmessages' style='direction: ltr'>Access denied!...</div>";    
    }   
}else{
    //session not up display error!...
    echo "<br><div class='alertmessages' style='direction: ltr'>Session expired please re-login!...</div>";
}

if($prmOk){
	//read post data
    foreach($_POST as $key => $value){
        $$key=$value;
    }
	//initialize form variables and make database connection
	if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
		die("Could not connect to database please contact system admin...");
	}
    if(!isset($mode)){
        $mode="";
    }
    $showList=true;
	if(!isset($errorMessage)){
		$errorMessage="";
	}
	if(!isset($errorNo)){
		$errorNo = 0;
	}
	if(!isset($message)){
		$message="";
	}

//*****************************************************************************************
//read reference data
//*****************************************************************************************
$comps=array();
$q="SELECT cmpId,cmpName FROM companies WHERE cmpPlanCont=1 AND cmpId NOT IN(SELECT DISTINCT supCompany FROM Supervisors) ORDER BY cmpName";
if($stmt=mysqli_prepare($dbc, $q)){
	if(mysqli_stmt_execute($stmt)){
		if(mysqli_stmt_bind_result($stmt, $cmpId,$cmpName)){
			while(mysqli_stmt_fetch($stmt)){
				$comps[$cmpId]=$cmpName;
			}
		}
	}
	mysqli_stmt_close($stmt);
}

//*****************************************************************************************
//read max area supervision
//*****************************************************************************************
if(isset($supCompany)){
	$q="SELECT dstsupAmount FROM companies INNER JOIN districtSupervision on cmpDistrict = dstsupDistrict WHERE cmpId=?";
	if($stmt=mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt,"i",$supCompany)){
			if(mysqli_stmt_execute($stmt)){
				if(mysqli_stmt_bind_result($stmt, $dstsupAmount)){
					mysqli_stmt_fetch($stmt);
				}
			}	
		}
		mysqli_stmt_close($stmt);
	}
}

//*****************************************************************************************
//validate for saveedit or saveadd
//*****************************************************************************************
	if($mode=="saveedit" or $mode=="saveadd"){
		$errorMessage="";
		if($supCompany==-1){
			$errorMessage="لابد من اختيار الشركة<br>";
		}
		$totsup=0;
		for($i=0;$i<4;$i++){
			if($supName[$i] != ""){
				if($supSalutation[$i]==""){
					$errorMessage.="لابد من ادخال التحية<br>";
				}
				if($supAmount[$i]==""){
					$errorMessage.="لابد من ادخال القيمة<br>";
				}		
			}
			$totsup+=$supAmount[$i];
		}
		if($totsup > $dstsupAmount){
			$errorMessage="قيمة الاشراف أكبر من الحد اﻷعلى";
		}
		if($errorMessage != ""){
			$mode=substr($mode,4);
		}
	}

//*****************************************************************************************
//save edit data
//*****************************************************************************************
	if($mode=="saveedit"){
		$q="DELETE FROM Supervisors WHERE supCompany=?";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,"i",$supCompany)){
				mysqli_stmt_execute($stmt);
			}
			mysqli_stmt_close($stmt);
		}
		$q="INSERT INTO Supervisors(supCompany,supName,supSalutation,supAmount) VALUES(?,?,?,?)";
		if ($stmt = mysqli_prepare($dbc, $q)){
			for($i=0;$i<4;$i++){
				$supname=$supName[$i];
				$supsalutation=$supSalutation[$i];
				$supamount=$supAmount[$i];
				if($supname != ""){
					if(mysqli_stmt_bind_param($stmt,"issd",$supCompany,$supname,$supsalutation,$supamount)){
						if(mysqli_stmt_execute($stmt)){
							$message="تم الحفظ!...";
						}    
					}		
				}
			}
			mysqli_stmt_close($stmt);
		}
		$q="UPDATE companies SET cmpSupDpt = ? WHERE cmpId = ?";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,"si",$cmpSupDpt,$supCompany)){
				mysqli_stmt_execute($stmt);
			}
			mysqli_stmt_close($stmt);
		}
	}
	
//*****************************************************************************************
//Save added data
//*****************************************************************************************
	if($mode=="saveadd"){
		$q="INSERT INTO Supervisors(supCompany,supName,supSalutation,supAmount) VALUES(?,?,?,?)";
		if ($stmt = mysqli_prepare($dbc, $q)){
			for($i=0;$i<4;$i++){
				$supname=$supName[$i];
				$supsalutation=$supSalutation[$i];
				$supamount=$supAmount[$i];
				if($supname != ""){
					if(mysqli_stmt_bind_param($stmt,"issd",$supCompany,$supname,$supsalutation,$supamount)){
						if(mysqli_stmt_execute($stmt)){
							$message="تم الحفظ!...";
						}    
					}		
				}
			}
			mysqli_stmt_close($stmt);
		}
		$q="UPDATE companies SET cmpSupDpt = ? WHERE cmpId = ?";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,"si",$cmpSupDpt,$supCompany)){
				mysqli_stmt_execute($stmt);
			}
			mysqli_stmt_close($stmt);
		}
	}

//*****************************************************************************************
//read a record for view or edit
//*****************************************************************************************
if($mode=="edit" or $mode=="view" or $mode=="deleteconfirm"){
	$q="SELECT supId,supName,supCompany,supSalutation,supAmount,cmpName,cmpSupDpt FROM Supervisors INNER JOIN companies ON supCompany=cmpId WHERE supCompany= ?";
	if($stmt=mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt, "i", $supCompany)){
			if(mysqli_stmt_execute($stmt)){
				if(mysqli_stmt_bind_result($stmt,$supid,$supname,$supcompany,$supsalutation,$supamount,$cmpName,$cmpSupDpt)){
					$i=0;
					while(mysqli_stmt_fetch($stmt)){
						$supId[$i]=$supid;
						$supName[$i]=$supname;
						$supSalutation[$i]=$supsalutation;
						$supAmount[$i]=$supamount;
						$i++;
					}
				}
			}    
		}
		mysqli_stmt_close($stmt);
	}
}

//*****************************************************************************************
//delete record
//*****************************************************************************************
	if($mode == "delete"){
		$q="DELETE FROM Supervisors WHERE supCompany=?";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,"i",$supCompany)){
				mysqli_stmt_execute($stmt);
			}
			mysqli_stmt_close($stmt);
		}
	}

//*****************************************************************************************
//Add/Edit form
//*****************************************************************************************
	if($mode=="add" or $mode=="edit"){
        $showList=false;
        if(isset($errorMessage)){
            echo "<div class='alertmessages'>$errorMessage</div>";
        }
		echo "<center><form method='post'>";
		echo "<table class='formTable'>";

		if($mode=="add"){
			echo "<tr><td class='fieldLable'>الشركة:</td><td class='field'>";
			echo "<div class='input-container'>";
			echo "<select class='input-field' name='supCompany'><option value='-1'>الشركة</option>";
			foreach($comps as $key => $value){
				echo "<option value='$key'";
				if(isset($supCompany)){
					if($supCompany == $key){
						echo " selected";
					}
				}
				echo ">$value</option>";
			}
			echo "</select></div></td></tr>";	
		}else{
			echo "<tr><td class='fieldLable' style='margine-bottom: 25px;'>الشركة:</td><td class='fieldLable' style='margine-bottom: 25px;'>$cmpName<input type='hidden' name='supCompany' value='$supCompany'></td></tr>";
		}
		
		echo "<tr><td class='fieldLable'>اﻹدارة:</td><td class='field'>";
		echo "<div class='input-container'>";
		echo "<input class='input-field' type='text' placeholder='اﻹدارة' maxlength='255' name='cmpSupDpt'";
		if(isset($cmpSupDpt)){
			echo " value='$cmpSupDpt'";
		}
		echo "></div></td></tr>";	

		echo "<tr><td colspan='2'><table class='cmpSupervisors'>";
		echo "<tr>";
		echo "<td class='serial'>م</td>";
		echo "<td class='supSalutation'>التحية</td>";
		echo "<td class='supName'>الاسم</td>";
		echo "<td class='supAmount'>المبلغ</td>";
		echo "</tr>";
		for($i=0;$i<4;$i++){
			$ser=$i+1;
			echo "<tr>";
			echo "<td class='serial'>$ser</td>";
			echo "<td class='supSalutation'><div class='input-container'><input class='input-field' type='text' placeholder='التحية' maxlength='255' name='supSalutation[$i]'";
			if(isset($supSalutation[$i])){
				echo " value='".$supSalutation[$i]."'";
			}
			echo "></div></td>";
			echo "<td class='supName'><div class='input-container'><input class='input-field' type='text' placeholder='اسم المشرف' maxlength='255' name='supName[$i]'";
			if(isset($supName[$i])){
				echo " value='".$supName[$i]."'";
			}
			echo "></div></td>";
			echo "<td class='supAmount'><div class='input-container'><input class='input-field supValue' type='number' placeholder='القيمة' maxlength='255' onchange='sumSup();' name='supAmount[$i]'";
			if(isset($supAmount[$i])){
				echo " value='".$supAmount[$i]."'";
			}
			echo "></div></td>";
			echo "</tr>";	
		}
		echo "<tr>";
		echo "<td class='serial' colspan='3'>الاجمالي</td>";
		echo "<td class='supAmount'><div class='input-container'><input class='input-field' type='text' placeholder='الاجمالى' id='totSup'></div></td>";
		echo "</tr>";

		echo "</table></td></tr>";
		echo "</table>";
		$newMode="save$mode";
		//save and cancel buttons
        echo "<br><div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'> حفظ </button> <button type='button' class='cnlBtn' onclick='window.location=\"$filename\";'> تراجع </button></div>";
		echo "</form>";
		echo "</center>";
		echo "
		<script>
		function sumSup(){
			var i=0;
			var tot=0;
			const sups=document.getElementsByClassName('supValue');
			for(i=0;i<4;i++){
				tot += Number(sups[i].value);
			}
			document.getElementById('totSup').value=tot;
		}
		</script>
		";
	}

//*****************************************************************************************
//display code
//*****************************************************************************************
 	if($mode == "view"){
		$showList=false;
		echo "<center>";
		echo "<table class='formTable'>";
		echo "<tr><td class='fieldLable' style='padding-bottom: 25px;'>الشركة:</td><td class='fieldLable' style='padding-bottom: 25px;'>$cmpName<input type='hidden' name='supCompany' value='$supCompany'></td></tr>";
		echo "<tr><td class='fieldLable' style='padding-bottom: 25px;'>اﻹدارة:</td><td class='fieldLable' style='padding-bottom: 25px;'>$cmpSupDpt</td></tr>";	
		echo "<tr><td colspan='2'><table class='cmpSupervisors'>";
		echo "<tr>";
		echo "<td class='serial'>م</td>";
		echo "<td class='supSalutation'>التحية</td>";
		echo "<td class='supName'>الاسم</td>";
		echo "<td class='supAmount'>المبلغ</td>";
		echo "</tr>";
		for($i=0;$i<4;$i++){
			$ser=$i+1;
			echo "<tr>";
			echo "<td class='serial'>$ser</td>";
			echo "<td class='supSalutation'><div class='input-container'>".$supSalutation[$i]."</div></td>";
			echo "<td class='supName'><div class='input-container'>".$supName[$i]."</div></td>";
			echo "<td class='supAmount'><div class='input-container'>".$supAmount[$i]."</div></td>";
			echo "</tr>";	
		}
		echo "</table></td></tr>";
		echo "</table>";
        echo "<br><div class='frmButtons'><button type='button' class='cnlBtn' onclick='window.location=\"$filename\";'> حسنا </button></div>";
		echo "</center>";	
	}

//*****************************************************************************************
//delete confirm
//*****************************************************************************************
	if($mode=="deleteconfirm"){
		$showList=false;
		echo "<center>";
		echo "<h2 style='text-align: center;'> الغاء مشرف </h2>";
		echo "<div style='text-align: center;'> سيتم الغاء  $cmpName <br> هل أنت متأكد؟...</div><br>";
		echo "<form method='post' style='max-width:500px;margin:auto;'>";
		echo "<input type='hidden' name='supCompany' value='$supCompany'>";
		echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='delete'> نعم </button> <button type='button' class='cnlBtn' name='mode' onclick='window.location=\"$filename\";'> لا </button></div>";
		echo "</form>";
  		echo "</center>";
	}

//****************************************************************************************
//the record list
//****************************************************************************************
	if($showList){
		if(isset($errorMessage)){
			echo "<div class='alertmessages'>$errorMessage</div>";
			echo "<br><br>";
		}
		if(isset($message)){
			echo "<div class='infomessages'>$message</div>";
			echo "<br><br>";
		}
		//new form
		echo "<div style='width: 110px; margin: auto;'><form method='post'>";
		echo "<button type='submit' class='addBtn' name='mode' value='add' style='width:150px;'>إضافة شركة جديد</button>";
		echo "</form></div><br>";
		$numberOfButtons=3;
		$buttonCellWidth=$numberOfButtons * 120;
		$buttonCellWidth .= "px";
		//filter list form 
		echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث...'>";
		echo "<table id='masterTable'><tr class='header'><th> الشركة </th><th style='width: $buttonCellWidth'>&nbsp;</th></tr>";
		$q="SELECT DISTINCT supCompany,cmpName FROM Supervisors INNER JOIN companies ON supCompany=cmpId ORDER BY cmpName";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_execute($stmt)){
				if(mysqli_stmt_bind_result($stmt, $supCompany,$cmpName)){
					while(mysqli_stmt_fetch($stmt)){
						echo "<tr>";
						echo "<td> $cmpName </td>";
						echo "<td style='width: $buttonCellWidth;'>";
						echo "<form method='post'>";
						echo "<input type='hidden' name='supCompany' value='$supCompany'>";
						echo "<button type='submit' class='edtBtn' name='mode' value='edit'>تعديل</button> ";
						echo "<button type='submit' class='viewBtn' name='mode' value='view'>عرض</button> ";
						echo "<button type='submit' class='delBtn' name='mode' value='deleteconfirm'>الغاء</button> ";
						echo "</form>";
						echo "</tr>";
					}
				}
			}
			mysqli_stmt_close($stmt);
		}
		echo "</table>";
	}
}
//*****************************************************************************************
//Form scripts
//*****************************************************************************************
?>
		<script>
			function filterList() {
				var input, filter, table, tr, td, i, txtValue;
				input = document.getElementById("filterBox");
				filter = input.value.toUpperCase();
				table = document.getElementById("masterTable");
				tr = table.getElementsByTagName("tr");
				for (i = 0; i < tr.length; i++) {
					td = tr[i].getElementsByTagName("td")[0];
					if (td) {
						txtValue = td.textContent || td.innerText;
						if (txtValue.toUpperCase().indexOf(filter) > -1) {
							tr[i].style.display = "";
						} else {
							tr[i].style.display = "none";
						}
					}   
				}
			}
        </script>
    </body>
</html>