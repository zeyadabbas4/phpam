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
$formCourseStatusLevel=1;
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

//************************************************************************************************************* */
// sec:savelecs Save Lecturers
//************************************************************************************************************* */
if($mode=="savelecs"){
	$lecs=readLecturerNames($dbc);
	if($fclhLecId=="0"){
		$errorMessage.="لا بد من اختيار المحاضر<br>";
	}
	if($fclhHoursP=="" and $fclhHoursT == ""){
		$errorMessage.="لا بد من ادخال عدد الساعات<br>";
	}  
	if($clhPeriod=="0"){
		$errorMessage.="لا بد من اختيار الفترة<br>";
	}
	$q="select count(*) as lecCount from CourseLecHours where clhCrsId=? and clhPeriod=? and clhLecId=?";
	if($stmt = mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt, "iii", $clsId,$clhPeriod,$fclhLecId)){
			if(mysqli_stmt_execute($stmt)){
				if(mysqli_stmt_bind_result($stmt, $lecCount)){
                    if(mysqli_stmt_fetch($stmt)){
						if($lecCount > 0 ){
							$errorMessage.="لقد تمت إضافة ". $lecs[$clhLecIdv]." مسبقا";
						}
					}
				}
			}
		}		
		mysqli_stmt_close($stmt);
	}
	echo "Course = $clsId,Period = $clhPeriod,Lecturer = $fclhLecId";
	if($errorMessage==""){
	  	$q="INSERT INTO CourseLecHours(clhLecId,clhCrsId,clhHoursP,clhHoursT,clhNights,clhratio,clhDistrictFrom,clhDistrictTo,clhDays,clhReturn,clhPeriod) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
	  	if($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt, "iiddiiiiiii", $fclhLecId,$clsId,$fclhHoursP,$fclhHoursT,$fclhNights,$fclhratio,$fclhDistrictFrom,$fclhDistrictTo,$fclhDays,$fclhReturn,$clhPeriod)){
		  		if(!mysqli_stmt_execute($stmt)){
					$errorMessage.= "Error saving data [050103".$__uid.date("YmdHis")."]!...<br>";
					appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"050103".$__uid.date("YmdHis"));
		  		}  
			}else{
		  		$errorMessage.= "Error saving data [050102]".$__uid.date("YmdHis")."]!...<br>";
		  		appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"050102".$__uid.date("YmdHis"));
			}
			mysqli_stmt_close($stmt);
	  	}else{
			$errorMessage.= "Error saving data [050101]".$__uid.date("YmdHis")."]!...<br>";
			appenmr_mkedLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"050101".$__uid.date("YmdHis"));
	  	}
	} 
	$mode="lecs";
}
  
//************************************************************************************************************* */
// sec:delLecs Delete Lecs
//************************************************************************************************************* */
if($mode == "delLecs"){
	$q="delete from CourseLecHours where clhLecId=? and clhCrsId=? and clhPeriod=?";
	if($stmt = mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt, "iii", $LecId,$clsId,$clhPeriod)){
		if(!mysqli_stmt_execute($stmt)){
			$errorMessage.= "Error saving data [060103".$__uid.date("YmdHis")."]!...<br>";
			appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"060103".$__uid.date("YmdHis"));
		}  
		}else{
		$errorMessage.= "Error saving data [060102]".$__uid.date("YmdHis")."]!...<br>";
		appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"060102".$__uid.date("YmdHis"));
		}
		mysqli_stmt_close($stmt);
	}else{
		$errorMessage.= "Error saving data [060101]".$__uid.date("YmdHis")."]!...<br>";
		appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"060101".$__uid.date("YmdHis"));
	}
	$mode="lecs";
  }

//********************************************************************************************************* */	
//sec:lecs lecturers form
//********************************************************************************************************* */
	if($mode=="lecs"){
		if($clhPeriod == 0){
			$errorMessage = "لابد من تحديد الفترة";
		}else{
			$showList=false;
			echo "<div class=''>";
			$class=readClass($dbc,$clsId);
			$batch=readBatch($dbc,$batchId);
			$lecNames=readLecturerNames($dbc); 
			$lecs=readCourseLecturers($dbc,$clsId,$clhPeriod);
			$dists=readDistrcts($dbc);
			if($errorMessage != ""){
				echo "<div class='alertmessages'><p>$errorMessage</p></div>";
			  }
			  echo "<div style='max-width:1000px;margin:auto;direction:$__dir;'>";
			  echo "<h2 style='text-align: center;'>محاضري فصل: ".$class["Description"]." دفعة: ".$batch["Description"]."</h2>";
			  $periods=readPeriods($dbc,$clsId); 
			  echo "<div style='width: 600px; margin: auto;'><form method='post' id='periodSelect'>";
			  echo "<h3>للفترة<select name='clhPeriod' class='input-field' onchange='document.getElementById(\"periodSelect\").submit();'><option value='0'>حدد الفترة</option>";
			  foreach($periods as $key => $value){
				echo "<option value='$key'";
				if($key==$clhPeriod){
					echo " Selected";
				}
				$tag="";
				if($value['Status']>$formCourseStatusLevel){
					$tag="(مغلقة)";
				}
				echo ">".$value['Description']." (".$value['From']." - ".$value['To'].")$tag</option>";
			  }
			  echo "</select></h3>";
			  echo "<input type='hidden' name='clsId' value='$clsId'>";
			  echo "<input type='hidden' name='batchId' value='$batchId'>";
			  echo "<input type='hidden' name='mode' value='lecs'>";
			  echo "</form></div><br>";
			  $buttonsDisabled="";
			  $delButtonsClass="delBtn";
			  $addBttonClass="savBtn";
			  $state=getPrdStat($dbc, $clhPeriod);
			  if($state > $formCourseStatusLevel){
				$buttonsDisabled=" disabled";
				$delButtonsClass="disabledBtn";
				$addBttonClass="disabledBtn";
			  }
			  echo "<table  width='100%'>";
			  echo "<tr><td style='text-align: center;width: 50px;'> الملف </td>";
			  echo "<td style='text-align: center;width: 250px;'> المحاضر </td>";
			  echo "<td style='text-align: center;width: 75px;'>نظري</td>";
			  echo "<td style='text-align: center;width: 75px;'>عملي</td>";
			  echo "<td style='text-align: center;width: 75px;'>الليالي</td>";
			  echo "<td style='text-align: center;width: 75px;'>النسبة</td>";
			  echo "<td style='text-align: center;width: 60px;'>من</td>";
			  echo "<td style='text-align: center;width: 60px;'>الى</td>";
			  echo "<td style='text-align: center;width: 60px;'>العدد</td>";
			  echo "<td style='text-align: center;width: 75px;'>العودة</td>";
			  echo "<td>&nbsp;</td></tr>\r\n";
			  $totT=0;
			  $totP=0;
			  foreach($lecs as $key => $value){
				$lecId=$value['lecId'];
				$lecName=$lecNames[$value['lecId']];
				$thhrs=$value['thhrs'];
				$prhrs=$value['prhrs'];
				$Nights=$value['Nights'];
				$ratio=$value['ratio'];
				if($value['From'] !=0){
					$From=$dists[$value['From']]['Name'];
				}else{
					$From="";
				}
				if($value['To'] !=0){
					$To=$dists[$value['To']]['Name'];
				}else{
					$To="";
				}
				$Days=$value['Days'];
				echo "<tr><td style='text-align:right;'>$lecId</td>";
				echo "<td style='text-align: right;'>$lecName</td>";
				echo "<td style='text-align: center;'>$thhrs</td>";
				echo "<td style='text-align: center;'>$prhrs</td>";
				echo "<td style='text-align: center;'>$Nights</td>";
				echo "<td style='text-align: center;'>$ratio%</td>";
				echo "<td style='text-align: center;'>$From</td>";
				echo "<td style='text-align: center;'>$To</td>";
				echo "<td style='text-align: center;'>$Days مرة</td>";
				$checked="";
				if($value['Return']==1){
				  $checked=" checked";
				}
				echo "<td style='text-align: center;'><input type='checkbox' $checked disabled></td>";
				echo "<td><form method='post'><button type='submit' class='$delButtonsClass' name='mode' value='delLecs'$buttonsDisabled>الغاء</button>";
				echo "<input type='hidden' name='clhPeriod' value='$clhPeriod'>";
				echo "<input type='hidden' name='clsId' value='$clsId'>";
				echo "<input type='hidden' name='batchId' value='$batchId'>";
				echo "<input type='hidden' name='LecId' value='".$value['lecId']."'>";
				echo "</form></td></tr>";
				$totT+=$value['thhrs'];
				$totP+=$value['prhrs'];
			  }
			  $tot=$totT+$totP;
			  echo "<tr>";
			  echo "<td width='250px' style='text-align:right;font-weight:bold;' colspan='2'>إجمالي الساعات</td>";
			  echo "<td width='75px'style='text-align:center;font-weight:bold;'>".number_format($totT,2)."</td>";
			  echo "<td width='75px'style='text-align:center;font-weight:bold;'>".number_format($totP,2)."</td>";
			  echo "<td width='450px'style='text-align:right;' colspan='6'>&nbsp;</td>";
			  echo "<td width='100px'style='text-align:center;font-weight:bold;'>".number_format($tot,2)."</td>";
			  echo "</tr>";
			  
			  echo "<tr><td colspan='11'><form method='post' id='addLec'><table width='100%' border='1'>";
			  echo "<tr>";
			  echo "<td width='50'><input class='input-field' type='text' id='lecNo' onchange='selectLec();'></td>";
			  echo "<td width='250px'><select class='input-field' name='fclhLecId' id='lecList'><option value='0'>حدد المحاضر</option>\r\n";
 			  foreach($lecNames as $key => $value){
				echo "<option value='$key'";
				if(isset($fclhLecId)){
				  if($fclhLecId == $key){
					echo " selected";
				  }
				}
				echo ">$value</option>\r\n";
			  }
			  echo "</select></td>\r\n";
		
			  echo "<td style='vertical-align: middle;' width='75px'>";
			  echo "<input class='input-field' type='text' placeholder='نظري' name='fclhHoursT'";
			  if(isset($fclhHoursT))
				echo " value='$fclhHoursT'";
			  echo "></td>";
		
			  echo "<td style='vertical-align: middle;' width='75px'>";
			  echo "<input class='input-field' type='text' placeholder='عملي' name='fclhHoursP'";
			  if(isset($fclhHoursP))
				echo " value='$fclhHoursP'";
			  echo "></td>\r\n";
		
			  echo "<td style='vertical-align: middle;' width='75px'>";
			  echo "<input class='input-field' type='text' placeholder='الليالي' name='fclhNights'";
			  if(isset($fclhNights))
				echo " value='$fclhNights'";
			  echo "></td>\r\n";
		
			  echo "<td width='80px'><select class='input-field' name='fclhratio'>";
			  echo "<option value='0'>النسبة</option>";
			  echo "<option value='100'";
			  if(isset($fclhratio)){
				if($fclhratio == "100"){
				  echo " selected";
				}
			  }
			  echo ">100%</option>";
			  echo "<option value='50'";
			  if(isset($fclhratio)){
				if($fclhratio == "50"){
				  echo " selected";
				}
			  }
			  echo ">50%</option>";
			  echo "</select></td>\r\n";
		
		
			  echo "<td width='60px'><select class='input-field' name='fclhDistrictFrom'><option value='0'>من</option>\r\n";
			  foreach($dists as $key => $value){
				echo "<option value='$key'";
				if(isset($fclhDistrictFrom)){
				  if($fclhDistrictFrom == $key){
					echo " selected";
				  }
				}
				echo ">".$value['Name']."</option>\r\n";
			  }
			  echo "</select></td>\r\n";
			  
			  echo "<td width='60px'><select class='input-field' name='fclhDistrictTo'><option value='0'>الى</option>\r\n";
			  foreach($dists as $key => $value){
				echo "<option value='$key'";
				if(isset($fclhDistrictTo)){
				  if($fclhDistrictTo == $key){
					echo " selected";
				  }
				}
				echo ">".$value['Name']."</option>\r\n";
			  }
			  echo "</select></td>\r\n";
		
			  echo "<td style='vertical-align: middle;' width='60px'>";
			  echo "<input class='input-field' type='text' placeholder='العدد' name='fclhDays'";
			  if(isset($fclhDays))
				echo " value='$fclhDays'";
			  echo "></td>\r\n";
		
			  echo "<td style='vertical-align: middle;' width='75px'>";
			  echo "<input type='hidden' name='fclhReturn' value='0'>";
			  echo "العودة:<input type='checkbox' name='fclhReturn' value='1'";
			  if(isset($fclhReturn))
				if($fclhReturn == 1){
				  echo " checked";
				}
			  echo "></td>\r\n";          
			  
			  echo "<td style='text-align: center;vertical-align: middle;'><button type='submit' class='$addBttonClass' name='mode' value='savelecs'$buttonsDisabled> إضافة </button>\r\n";
			  echo "<input type='hidden' name='clhPeriod' value='$clhPeriod'>";
			  echo "<input type='hidden' name='clsId' value='$clsId'>";
			  echo "<input type='hidden' name='batchId' value='$batchId'>";
			  echo "</td></tr></table></form>";
			  
			  echo "</td></tr></table>\r\n";
		
			  echo "<div class='frmButtons'><br><form method='post'><input type='hidden' name='clhPeriod' value='$clhPeriod'><input type='hidden' name='batchId' value='$batchId'><button type='submit' class='addBtn'> العودة </button></div>\r\n";
			  echo "</div>";
			echo "</div>";
		}
		?>
		<script>
		function selectLec(){
		  var fileNo=document.getElementById("lecNo").value;
		  var lecSelect=document.getElementById("lecList");
		  var lecCount=lecSelect.options.length;
		  var found=0;
		  var i;
		  var lec;
		  for(i=0;i<lecCount;i++){
			if(fileNo == lecSelect.options[i].value){
			  lecSelect.selectedIndex=i;
			  found=1;
			  break;
			}
		  }
		  if(found == 0){
			alert("Not Found!...");
		  }
		}
		</script>
		<?php		
	}


	  //**************************************************************************************************************
	  // sec:delete-nominee
	  //**************************************************************************************************************
	  if ($mode == "delNominee") {
		if ($errorMessage == "") {
		  $concrsId = $clsId;
		  $q = "delete from conCourses where concrsCmp=? and concrsId=?";
		  if ($stmt = mysqli_prepare($dbc, $q)) {
			if (mysqli_stmt_bind_param($stmt, "ii", $concrsCmp, $concrsId)) {
			  if (!mysqli_stmt_execute($stmt)) {
				$errorMessage .= "Error saving data [060103" . $__uid . date("YmdHis") . "]!...<br>";
				appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "060103" . $__uid . date("YmdHis"));
			  }
			} else {
			  $errorMessage .= "Error saving data [060102]" . $__uid . date("YmdHis") . "]!...<br>";
			  appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "060102" . $__uid . date("YmdHis"));
			}
			mysqli_stmt_close($stmt);
		  } else {
			$errorMessage .= "Error saving data [060101]" . $__uid . date("YmdHis") . "]!...<br>";
			appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "060101" . $__uid . date("YmdHis"));
		  }
		}
		//$mode = "nominees";
		$mode = "addNominee";
	  }

    //**************************************************************************************************************
    // sec:add-nominee
    //**************************************************************************************************************
    if ($mode == "addNominee") {
		if ($errorMessage == "") {
		  $concrsId = $clsId;
		  $q = "INSERT INTO conCourses(concrsId,concrsCmp,concrsCost,concrsCurrency,concrsTrnCount,concrsTrnCerts) VALUES (?,?,?,?,?,?)";
		  if ($stmt = mysqli_prepare($dbc, $q)) {
			if (mysqli_stmt_bind_param($stmt, "iiiiii", $concrsId, $concrsCmp, $concrsCost, $concrsCurrency, $concrsTrnCount, $concrsTrnCerts)) {
			  if (!mysqli_stmt_execute($stmt)) {
				$errorMessage .= "Error saving data [050103" . $__uid . date("YmdHis") . "]!...<br>";
				appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050103" . $__uid . date("YmdHis"));
			  }
			} else {
			  $errorMessage .= "Error saving data [050102]" . $__uid . date("YmdHis") . "]!...<br>";
			  appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050102" . $__uid . date("YmdHis"));
			}
			mysqli_stmt_close($stmt);
		  } else {
			$errorMessage .= "Error saving data [050101]" . $__uid . date("YmdHis") . "]!...<br>";
			appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "050101" . $__uid . date("YmdHis"));
		  }
		}
		//$mode = "nominees";
	  }
  
    //**************************************************************************************************************
    // sec:nominees
    //**************************************************************************************************************
    if ($mode == "nominees") {
		$showList = false;
		$nominees = array();
		$prgs=readCourseNames($dbc);
		$class=readClass($dbc,$clsId);
		$classStatus=$class['Status'];
		$currency=readCurrnecy($dbc);
		$comps=readcompanies($dbc);
		$q = "select concrsId,concrsCmp,concrsTrnCount,concrsCost,concrsCurrency,concrsTrnCerts from conCourses where concrsId = $clsId";
		$r = mysqli_query($dbc, $q);
		if ($r) {
		  while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$nominees[$row['concrsCmp']] = $row;
		  }
		}
		if ($errorMessage != "") {
		  echo "<div class='error'><p>$errorMessage</p></div>";
		}
		if(count($nominees)>0){
			$concrsTrnCount=$nominees[1]['concrsTrnCount'];
		}else{
			$concrsTrnCount = '0';
		}
		echo "<div style='max-width:800px;margin:auto;direction:$__dir;'>";
		echo "<h2 style='text-align: center;'>عدد الطلبة في فصل " . $class['Description'] ."</h2><hr>\r\n";
		echo "<form method='post'>";
		echo "<table width='50%' style='margin:auto;'><tr>";
		echo "<td>عدد الطلبة:-</td>";
		echo "<td><input class='input-field' type='text' name='concrsTrnCount' value='$concrsTrnCount'></td>";
		echo "<td><button type='submit' class='savBtn' name='mode' value='delNominee'> حفظ </button></td>";
		echo "</tr></table><br>";
		echo "<input type='hidden' name='concrsCmp' value='1'>";
		echo "<input type='hidden' name='concrsCost' value='0'>";
		echo "<input type='hidden' name='concrsCurrency' value='1'>";
		echo "<input type='hidden' name='concrsTrnCerts' value='0'>";
		echo "<input type='hidden' name='clsId' value='$clsId'>";
		echo "<input type='hidden' name='batchId' value='$batchId'>";
		echo "</form>";
		/*
		disabled form with companies in case a course from a company is held
		echo "<table width='100%'>";
		echo "<tr>";
		echo    "<td style='text-align: center;width: 300px;'> الشركة </td>";
		echo    "<td style='text-align: center;width: 100px;'> تكلفة المتدرب </td>";
		echo    "<td style='text-align: center;width: 100px;'> العملة </td>";
		echo    "<td style='text-align: center;width: 100px;'> اﻷعداد المرشحة </td>";
		echo    "<td style='text-align: center;width: 100px;'> إجمالي الشهادات </td>";
		echo    "<td style='text-align: center;width: 150px;'> &nbsp;</td>";
		echo "</tr>\r\n";
		$tot = 0;
		foreach ($nominees as $key => $value) {
		  echo "<tr><td colspan='6'><form method='post'><table width='100%'>";
		  echo "<tr><td width='300px' style='text-align:right;'>" . $comps[$key] . "</td>";
		  echo "<td width='100px'>" . $value['concrsCost'] . "</td>";
		  echo "<td width='100px'>" . $currency[$value['concrsCurrency']] . "</td>";
		  echo "<td width='100px'>" . $value['concrsTrnCount'] . "</td>";
		  echo "<td width='100px'>" . $value['concrsTrnCerts'] * $value['concrsTrnCount'] . "</td>";
		  echo "<td>";
		  if ($classStatus > $formCourseStatusLevel) {
			echo "&nbsp;";
		  } else {
			echo "<button type='submit' class='delBtn' name='mode' value='delNominee'>الغاء</button>";
		  }
		  echo "</td>";
		  echo "<input type='hidden' name='clsId' value='$clsId'>";
		  echo "<input type='hidden' name='batchId' value='$batchId'>";
		  echo "<input type='hidden' name='concrsCmp' value='$key'>";
		  echo "</table></form></td></tr>";
		  $tot += $value['concrsTrnCount'];
		}
		echo "<tr><td colspan='4'><table border='0' width='100%'>";
		echo "<tr><td width='600px' style='text-align:right;'>اﻹجمـــــــالي</td>";
		echo "<td width='100px' style='text-align:center';>$tot</td>";
		echo "<td>&nbsp;</td>";
		echo "</table></td></tr>";
  
		echo "<tr><td colspan='6'>";
		if ($classStatus > $formCourseStatusLevel) {
		  echo "&nbsp;";
		} else {
		  	// adding
		  	echo "<form method='post'><table width='100%'>";
		  	echo "<tr>";
			$concrsCmp=1;
			echo "<td width='800px'><select class='input-field' name='concrsCmp'>\r\n";
			foreach ($comps as $key => $value) {
				echo "<option value='$key'";
				if (isset($concrsCmp)) {
				if ($concrsCmp == $key) {
					echo " selected";
				}
				}
				echo ">$value</option>\r\n";
			}
	
			echo "</select></td>\r\n";
			if (!isset($concrsCost)) {
				$concrsCost = '0';
			}
			echo "<td style='vertical-align: middle;' width='300px'><input class='input-field' type='text' placeholder='تكلفة المتدرب' name='concrsCost' value='$concrsCost' ></td>";
			// start curr
			if (!isset($concrsCurrency)) {
				$concrsCurrency = '1';
			}
			echo "<td width='300px'><select class='input-field' name='concrsCurrency'><option value='-1'>حدد العملة</option>\r\n";
			foreach ($currency as $key => $value) {
				echo "<option value='$key'";
				if ($concrsCurrency == $key)
				echo "selected";
				echo ">$value</option>\r\n";
			}
			echo "</select></td>\r\n";
			if (!isset($concrsTrnCount)) {
				$concrsTrnCount = '0';
			}
			if (!isset($concrsTrnCerts)) {
				$concrsTrnCerts = '0';
			}
			echo "<td style='vertical-align: middle;' width='250px'><input class='input-field' type='text' placeholder='العدد الفعلي' name='concrsTrnCount' value='$concrsTrnCount'></td>";
			echo "<td style='vertical-align: middle;' width='270px'><input class='input-field' type='text' placeholder='عدد الشهادات' name='concrsTrnCerts' value='$concrsTrnCerts'></td>";	
			echo "<td style='text-align:center;vertical-align: middle;'>";
			echo "<button type='submit' class='savBtn' name='mode' value='addNominee'> إضافة </button>\r\n";
			echo "<input type='hidden' name='clsId' value='$clsId'>";
			echo "<input type='hidden' name='batchId' value='$batchId'>";
			echo "</td></tr></table></form>";
		}
		echo "</td></tr></table><br>\r\n";
		*/
		echo "<div class='frmButtons'><form method='post'>";
		echo "<input type='hidden' name='clhPeriod' value='$clhPeriod'>";
		echo "<input type='hidden' name='clsId' value='$clsId'>";
		echo "<input type='hidden' name='batchId' value='$batchId'>";
		echo "<button type='submit' class='addBtn' name='mode'> إغلاق </button> ";
		echo "</form></div>\r\n";
		echo "</div>";
	  }
  

//**************************************************************************************** */
//sec:print call the print lecturer report per period
//**************************************************************************************** */
if($mode == 'print'){
	if($clhPeriod == 0){
		$errorMessage="لابد من تحديد الفترة";
	}else{
		$_SESSION['clsId']=$clsId;
		$_SESSION['batchId']=$batchId;
		$_SESSION['clhPeriod']=$clhPeriod;
		echo "<script>window.open('basicStudiesLecRpt.php');</script>";	
	}
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
		drawCourseLecturerTable($dbc, $clsId, false, $clhPeriod);
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
  
		  if (courseIsDevidable($dbc, $clsId)) {
			$periodStatus = readPeriod($dbc, $clhPeriod)['Status'];
		  } else {
			$periodStatus = -1;
		  }
		  echo "<form method='post'>";
		  echo "<input type='hidden' name='batchId' value='$batchId'>";
		  echo "<input type='hidden' name='clsId' value='$clsId'>";
		  echo "<input type='hidden' name='clhPeriod' value='$clhPeriod'>";
		  /*if ($CoursStatus == $formCourseStatusLevel or $periodStatus == $formCourseStatusLevel) {
			echo "<button type='submit' name='mode' value='agreeConfirm' class='yesBtn'>موافق</button> ";
			echo "<button type='submit' name='mode' value='rejectConfirm' class='noBtn'>غير موافق</button> ";
		  }*/
		  echo "<button type='submit' name='mode' class='okBtn'> إغلاق </button>";
		  echo "</form>";
		  echo "</center>";
		  echo "</div>";
		  
		}
	}
  
//************************************************************************************************************* */
//sec:viewAttachment Attachment
//************************************************************************************************************* */
	if ($mode == "viewAttachment") {
	$showList = false;
	include("$__includeDir/readPrograms.php");
	include("$__includeDir/readCourseData.php");
	include("$__includeDir/viewAttachment.php");
	}


//**************************************************************************************** */
//sec:start Course Data
//**************************************************************************************** */
if($mode == 'start'){
	incCrsStat($dbc,$clsId);
}

//**************************************************************************************** */
//sec:finish Course Data
//**************************************************************************************** */
if($mode == 'finish'){
	$periods=readPeriods($dbc,$clsId);
	$allClosed=0;
	foreach($periods as $key => $value){
		$allClosed=($value['Status'] == $formCourseStatusLevel) ? 0 : 1;
	}
	if($allClosed==1){
		incCrsStat($dbc,$clsId);
	}else{
		$errorMessage="توجد فترات غير منتهية";
	}
	
}

//**************************************************************************************** */
//sec:finishPeriod Course Data
//**************************************************************************************** */
if($mode == 'finishPeriod'){
	$state=getPrdStat($dbc, $clhPeriod);
	if($state>$formCourseStatusLevel){
		$errorMessage="تم إختتام الفترة مسبقا";
	}else{
		incPerStat($dbc,$clhPeriod);
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
			$classes=readClasses($dbc,$batchId);
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
				$startDisabled="";
				$periodListDisabled="";
				$registerDisabled="";
				$nomineesDisabled="";
				$attendanceDisabled="";
				$scoresDisabled="";
				$lecsDisabled="";
				$finishDisabled="";
				$printDisabled="";
				$attachmentdisabled="";
				$startClass="startBtn";
				$registerClass="pwdBtn";
				$nomineesClass="pwdBtn";
				$attendanceClass="genBtn";
				$scoresClass="edtBtn";
				$lecsClass="grpBtn";
				$finishClass="delBtn";
				$printClass="prtBtn";
				$attachmentClass="attBtn";
				if($clsStatus<$formCourseStatusLevel){
					$periodListDisabled=" disabled";
					$registerDisabled=" disabled";
					$nomineesDisabled=" disabled";
					$attendanceDisabled=" disabled";
					$scoresDisabled=" disabled";
					$lecsDisabled=" disabled";
					$finishDisabled=" disabled";
					$printDisabled=" disabled";
					$attachmentdisabled=" disabled";
					$registerClass="disabledBtn";
					$nomineesClass="disabledBtn";
					$attendanceClass="disabledBtn";
					$scoresClass="disabledBtn";
					$lecsClass="disabledBtn";
					$finishClass="disabledBtn";
					$printClass="disabledBtn";
					$attachmentClass="disabledBtn";
				}
				if($clsStatus==$formCourseStatusLevel){
					$startDisabled=" disabled";
					$startClass="disabledBtn";
				}
				if($clsStatus>$formCourseStatusLevel){
					$startDisabled=" disabled";
					$periodListDisabled=" disabled";
					$registerDisabled=" disabled";
					$nomineesDisabled=" disabled";
					$attendanceDisabled=" disabled";
					$scoresDisabled=" disabled";
					$lecsDisabled=" disabled";
					$finishDisabled=" disabled";
					$printDisabled=" disabled";
					$attachmentdisabled=" disabled";
					$startClass="disabledBtn";
					$registerClass="disabledBtn";
					$nomineesClass="disabledBtn";
					$attendanceClass="disabledBtn";
					$scoresClass="disabledBtn";
					$lecsClass="disabledBtn";
					$finishClass="disabledBtn";
					$printClass="disabledBtn";
					$attachmentClass="disabledBtn";
				}
				echo "<button type='submit' class='viewBtn' name='mode' value='view'>عرض</button> ";
				echo "<button type='submit' class='$startClass' name='mode' value='start'$startDisabled>إفتتاح الفصل</button> ";
				echo "<button type='submit' class='$nomineesClass' name='mode' value='nominees'$nomineesDisabled>الطلبة</button> ";
				echo "<button type='submit' class='$attendanceClass' name='mode' value='attendance'$attendanceDisabled>المواظبة</button> ";
				echo "<button type='submit' class='$scoresClass' name='mode' value='scores'$scoresDisabled>النتيجة</button> ";
				echo "<input type='hidden' name='clhPeriod' value='0'>";
				$periods=readPeriods($dbc,$clsId);
				$closeText="إختتام الفصل";
				$closeValue="finish";
				echo "<select name='clhPeriod' style='width: 260px; height: 37px;' class='pSelect' id='periodSelect$clsId' onchange='setCloseButton(\"periodSelect$clsId\",\"closeButton$clsId\");'$periodListDisabled><option value='0'>حدد الفترة</option>";
		  		foreach($periods as $key => $value){
					echo "<option value='$key'";
					if(isset($clhPeriod)){
						if($clhPeriod == $key){
							echo " selected";
							$closeText="إختتام الفترة";
							$closeValue="finishPeriod";
						}
					}
					$tag="";
					if($value['Status']>$formCourseStatusLevel){
						$tag="(مغلقة)";
					}
					echo ">".$value['Description']." (".$value['From']." - ".$value['To'].")$tag</option>";
		  		}
		  		echo "</select> ";
				//echo "<button type='submit' class='$registerClass' name='mode' value='register'$registerDisabled>التسجيل</button> ";
				echo "<button type='submit' class='$lecsClass' name='mode' value='lecs'$lecsDisabled>المحاضرين</button> ";
				echo "<button type='submit' class='$finishClass' name='mode' value='$closeValue' id='closeButton$clsId'$finishDisabled>$closeText</button> ";
				echo "<button type='submit' class='$printClass' name='mode' value='print'$printDisabled>طباعة</button> ";
				//echo "<button type='submit' class='$attachmentClass' name='mode' value='attachment'$attachmentdisabled>مرفقات</button> ";
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
			function setCloseButton(periodSelect,closeButton){
				periodSelectElement=document.getElementById(periodSelect);
				closeButtonElement=document.getElementById(closeButton);
				if(periodSelectElement.value != 0){
					closeButtonElement.innerText="إختتام الفترة";
					closeButtonElement.value="finishPeriod";
					closeButtonElement.className="finBtn";
				}else{
					closeButtonElement.innerText="إختتام الفصل";
					closeButtonElement.value="finish";
					closeButtonElement.className="delBtn";
				}
			}
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