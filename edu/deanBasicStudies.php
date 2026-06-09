<?php
//uncomment those two lines for debugging
ini_set('display_errors',1); 
error_reporting(E_ALL);
session_start();
$__systemRoot="../";
//include($__systemRoot.'sysdb.php');            //setup system databse connection
include($__systemRoot.'functions.php');        //include system functions
include('functions.php');         		   //uncomment this line if you have a local functions file
include('appdb.php');             			   //include app database connection
if(isset($_SESSION['__uid'])){        		   //check for session and set the sesOk variable
    $__uid=$_SESSION['__uid'];
    $sesOk=true;
}else{
    $sesOk=false;
}
$__dir="rtl";
$logDir="../logs";
$logFile="edu.log";
$formCourseStatusLevel=5;
$prmOk=false;
?>
<!DOCTYPE html>
<html dir="<?php echo $__dir; ?>">
    <head>
        <meta charset="utf-8">
        <title>Authors</title>
        <style>
    		* {
				box-sizing: border-box;
			}
			body {
				direction: rtl;
			}
			h2{
				text-align: center;
				color: RoyalBlue;}
			h3{
				text-align: center;
				color: RoyalBlue;
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
    		.disabledBtn {
				background-color: grey;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
				margin: auto;
			}
    		.disabledBtn:hover {
				opacity: 1;
			}
			.startBtn {
				background-color: #7FFF00;  
				color: #191970;  
				padding: 10px 10px;  
				border: none;  
				cursor: pointer;  
				width: 100px;  
				opacity: 0.9;
			}
		    .startBtn:hover {
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
			.prtBtn {
				background-color: DarkOliveGreen;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.prtBtn:hover {
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
			.yesBtn {
				background-color: green;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.yesBtn:hover {
				opacity: 1;
			}
    		.noBtn {
				background-color: red;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.noBtn:hover {
				opacity: 1;
			}
    		.finBtn {
				background-color: DarkSlateGray;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.finBtn:hover {
				opacity: 1;
			}
    		.input-container {
				display: -ms-flexbox; /* IE10 */  
				display: flex;
				width: 100%;
				margin-bottom: 15px;
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
			.lecTable{
				border-style:solid;
				border-color:black;
				border-width:1px;
				border-spacing:0px;
			}
    		.lecTable td{
				border-style:solid;
				border-color:black;
				border-width:1px;
			}
    		.lecTable th{
				border-style:solid;
				border-color:black;
				border-width:1px;
				text-align:center;
			}
		</style>
    </head>
    <body>
	<br><h2>الدراسات اﻷساسية للبحارة</h2>
<?php
if($sesOk){
    //session is up check for user permission!...   
	$perms=getUserPermissions($__uid);
    $filename=basename(__FILE__);                               //get script name
    $mnuId=getCommandMenuId($filename);                         //get sreen id
    if(checkUserMenuItem($__uid,$mnuId)){                       //check for user permission to use the screen
		$prmOk=true;
    }else{
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
	if(!isset($validationError)){
		$validationError=false;
	}


//**************************************************************************************** */
//sec:View Course Data
//**************************************************************************************** */
if($mode == 'view'){
	$showList = false;
	$class=readClass($dbc,$clsId);
	$batch=readBatch($dbc,$batchId);
	$spc=readSpecialities($dbc);
	$bat=readBatchs($dbc);
	$periods=readPeriods($dbc,$clsId);
	echo "<center>";
	echo "<h2> بيانات فصل في: ".$batch['Description']."</h2>";
	if ($clhPeriod == 0) {
		echo "<table>";
		echo "<tr><td style='width: 100px;'>كود الفصل:</td><td style='width:650px;'><div class='input-container'>$clsId</div></td></tr>";
		echo "<tr><td style='width: 100px;'>اسم الفصل:</td><td style='width:650px;'><div class='input-container'>".$class['Description']."</div></td></tr>";
		echo "<tr><td style='width: 100px;'>التخصص:</td><td style='width:650px;'><div class='input-container'>".$spc[$class['Speciality']]."</div></td></tr>";
		echo "<tr><td style='width: 100px;'>الدفعة:</td><td style='width:650px;'><div class='input-container'>".$bat[$class['Batch']]."</div></td></tr>";
		echo "</table>";
		if(count($periods) > 0){
			$n=1;
			echo "<table border='1' style='border-collapse: collapse;' cellpadding='5'>";
			echo "<tr>";
			echo "<td> مسلسل </td>";
			echo "<td> اسم الفترة </td>";
			echo "<td> تاريخ البداية </td>";
			echo "<td> تاريخ النهاية </td>";
			if(isset($perms['Developer'])){
				echo "<td> موقف الفترة </td>";
				echo "<td> تاريخ اﻹغلاق </td>";		
			}
			echo "</tr>";				
			foreach($periods as $key => $value){
				echo "<tr>";
				echo "<td>";
				echo $n++;
				echo " - </td>";
				echo "<td>";
				echo $value['Description'];
				echo "</td>";
				echo "<td>";
				echo $value['From'];
				echo "</td>";
				echo "<td>";
				echo $value['To'];
				echo "</td>";
				if(isset($perms['Developer'])){
					echo "<td>";
					echo $value['Status'];
					echo "</td>";
					echo "<td>";
					echo $value['Close'];
					echo "</td>";		
				}
				echo "</tr>";				
			}
			echo "</table>";
			echo "<br>";
		}
		echo "<form method='post'>";
		echo "<input type='hidden' name='batchId' value='$batchId'>";
		echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode'> إغلاق </button></div>";
	
	} else {
		$__includeDir="../include";
		//load ref data
		$dists = readDistrcts($dbc);
		include("$__includeDir/readSections.php");
		include("$__includeDir/readPrograms.php");
		include("$__includeDir/readCompanies.php");
		include("$__includeDir/readCourseData.php");
		include("$__includeDir/readStaff.php");

		$lecs=readCourseLecturers($dbc,$clsId,$clhPeriod);
  
		echo "<div style='direction: rtl; text-align: right; width:1100px;margin: auto;'>";
		echo "<center>";
  
		//Course details
		displayCourseDetails($dbc, $clsId, $batchId,$clhPeriod);
		echo "<br><br>";
  
		//بيانات المحاضرين
		drawCourseLecturerTable($dbc, $clsId, true, $clhPeriod);
		echo "<br><br>";
  
		//بيانات بدل السفر والانتقال
		drawTravelTable($dbc, $clsId, $clhPeriod);
		echo "<br><br>";

		// بيانات المتدربين
		drawCourseTraineesConracted($dbc, $clsId);
		echo "<br><br>";

		//بيانات المرفقات
		if (!courseIsDevidable($dbc, $clsId)) {
		include("$__includeDir/attachmentTable.php");
		echo "<br><br>";
		}
		$periodStatus = readPeriod($dbc, $clhPeriod)['Status'];
		echo "<form method='post'>";
		echo "<input type='hidden' name='batchId' value='$batchId'>";
		echo "<input type='hidden' name='clsId' value='$clsId'>";
		echo "<input type='hidden' name='clhPeriod' value='$clhPeriod'>";
		if ($periodStatus == $formCourseStatusLevel) {
			echo "<button type='submit' name='mode' value='approvePeriod' class='yesBtn'>موافق</button> ";
			echo "<button type='submit' name='mode' value='rejectPeriod' class='noBtn'>غير موافق</button> ";
		}
		echo "<button type='submit' name='mode' class='okBtn'> إغلاق </button>";
		echo "</form>";
		echo "</center>";
		echo "</div>";
		  
		}
	}
  
//**************************************************************************************** */
//sec:approvePeriod approves a period and if all peiods move Course
//**************************************************************************************** */
if($mode == 'approvePeriod'){
	incPerStat($dbc,$clhPeriod);
	$periods=readPeriods($dbc,$clsId);
	$allClosed=1;
	foreach($periods as $key => $value){
		if($value['Status'] == $formCourseStatusLevel){
			$allClosed=0;
			break;
		}
	}
	if($allClosed==1){
		//incCrsStat($dbc,$clsId);
	}		
}

//**************************************************************************************** */
//sec:rejectPeriod rejects a period and if Course is closed reopen it
//**************************************************************************************** */
if($mode == 'rejectPeriod'){
	decPerStat($dbc,$clhPeriod);
    $status=getCrsStat($dbc,$clsId);
    if($status == $formCourseStatusLevel){
        setCrsStat($dbc,$clsId,$formCourseStatusLevel - 1);
    }
}

//****************************************************************************************
//sec:list the record list
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
		//select batch form
		echo "<div style='width: 600px; margin: auto;'><form method='post' id='batchSelect'>";
		$bat=readBatchs($dbc);
		echo "<select name='batchId' class='input-field' onchange='document.getElementById(\"batchSelect\").submit();'><option value='-1'>حدد الدفعة</option>";
		foreach($bat as $key => $value){
			echo "<option value='$key'";
			if(isset($batchId)){
				if($batchId == $key){
					echo " selected";
				}	
			}
			echo ">$value</option>";
		}
		echo "</select>";
		echo "</form></div><br>";
		$numberOfButtons=11;
		$buttonCellWidth=$numberOfButtons * 115;
		$buttonCellWidth .= "px";
		$buttonCellWidth = "1250px";
		//filter list form 
		echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث...'>";
		echo "<table id='masterTable'><tr class='header'><th> الفصل </th><th style='width: $buttonCellWidth'>&nbsp;</th></tr>";
		if(isset($batchId)){
			$classes=array();
			$q="SELECT CoursId,CoursDescription,CoursCrsId,CoursStatus FROM Courses WHERE CoursType=5 AND (CoursStatus >= ? OR CoursId IN (SELECT crprCourseId FROM coursPeriods WHERE crprStatus >= ?)) AND CoursYear = ?";
			if($stmt = mysqli_prepare($dbc, $q)){
				if(mysqli_stmt_bind_param($stmt, "iii", $formCourseStatusLevel,$formCourseStatusLevel,$batchId)){
					if(mysqli_stmt_execute($stmt)){
						if(mysqli_stmt_bind_result($stmt,$CoursId,$CoursDescription,$CoursCrsId,$CoursStatus)){
							while(mysqli_stmt_fetch($stmt)){
								$classes[$CoursId]['Description']=$CoursDescription;
								$classes[$CoursId]['Speciality']=$CoursCrsId;
								$classes[$CoursId]['Status']=$CoursStatus;
							}
						}
					}
				}		
				mysqli_stmt_close($stmt);
			}

			foreach($classes as $key => $value){
				$periods=readPeriods($dbc,$key);
				$clsId=$key;
				$clsDescription=$value['Description'];
				$clsSpeciality=$value['Speciality'];
				$clsStatus=$value['Status'];
				$devInfo="";
				if(isset($perms['Developer'])){
					$devInfo="[Id=$key - Status=$clsStatus]";
				}	
				echo "<tr>";
				echo "<td>$clsDescription $devInfo</td>";
				echo "<td style='width: $buttonCellWidth;text-align: left;'>";
				echo "<form method='post'>";
				echo "<input type='hidden' name='clsId' value='$clsId'>";
				echo "<input type='hidden' name='batchId' value='$batchId'>";
				$periodListDisabled="";
				if($clsStatus<$formCourseStatusLevel){
				}
				if($clsStatus==$formCourseStatusLevel){
				}
				if($clsStatus>$formCourseStatusLevel){
					$periodListDisabled=" disabled";
				}
				echo "<input type='hidden' name='clhPeriod' value='0'>";
				$periods=readPeriods($dbc,$clsId);
				$buttonCaption="عرض";
				$buttonClass="edtBtn";
				echo "<select name='clhPeriod' style='width: 260px; height: 37px;' class='pSelect' id='periodSelect$clsId' onchange='setCloseButton(\"periodSelect$clsId\",\"closeButton$clsId\");'$periodListDisabled><option value='0'>حدد الفترة</option>";
		  		foreach($periods as $key => $value){
					if($value['Status'] >= $formCourseStatusLevel){
						echo "<option value='$key'";
						if(isset($clhPeriod)){
							if($clhPeriod == $key){
								echo " selected";
							}
						}
						$tag="";
						if($value['Status']==$formCourseStatusLevel){
							$tag="(جديد)";
							$buttonCaption="مراجعة";
							$buttonClass="viewBtn";
						}elseif($value['Status']>$formCourseStatusLevel){
							$tag="(تمت المراجعة)";
						}
						echo ">".$value['Description']." (".$value['From']." - ".$value['To'].")$tag</option>";	
					}
				}
				echo "</select> ";
				echo "<button type='submit' class='$buttonClass' name='mode' value='view'>$buttonCaption</button> ";
				echo "</form>";
				echo "</tr>";
			}
			
		}
		echo "</table>";
//*****************************************************************************************
//sec:javascript Form List scripts
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
<?php		
	}
}
?>
    </body>
</html>