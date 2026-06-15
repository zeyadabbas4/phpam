<?php
//uncomment those two lines for debugging
ini_set('display_errors',1); 
error_reporting(E_ALL);
session_start();
$__systemRoot="../";
//include($__systemRoot.'sysdb.php');            //setup system databse connection
include($__systemRoot.'functions.php');        //include system functions
include('functions.php');	         		   //uncomment this line if you have a local functions file
include('appdb.php');             			   //include app database connection
if(isset($_SESSION['__uid'])){        		   //check for session and set the sesOk variable
    $__uid=$_SESSION['__uid'];
    $sesOk=true;
}else{
    $sesOk=false;
}
$formCourseStatusLevel=0;
$prmOk=false;
$perms=getUserPermissions($__uid);
?>
<!DOCTYPE html>
<html dir="<?php echo $gdir; ?>">
    <head>
        <meta charset="utf-8">
        <title> Years</title>
        <style>
    		* {
				box-sizing: border-box;
			}
			body {
				direction: rtl;
			}
			h2 {
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
    		.clsBtn {
				background-color: Olive;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.clsBtn:hover {
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
    		.periodBtn {
				background-color: Violet;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.periodBtn:hover {
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
	if(!isset($validationError)){
		$validationError=false;
	}

//*****************************************************************************************
//sec:saveedit-saveadd validate for saveedit or saveadd
//*****************************************************************************************
	if($mode=="saveedit" or $mode=="saveadd"){
		$errorMessage="";
		if($YearDesc==""){
			$errorMessage="لابد من ادخال اسم الدفعة<br>";
		}
		if($Yearstart==""){
			$errorMessage.="لابد من ادخال تاريخ البداية<br>";
		}
		if($YearEnd==""){
			$errorMessage.="لابد من ادخال تاريخ النهاية<br>";
		}

		if($errorMessage != ""){
			$mode=substr($mode,4);
		}
	}

//*****************************************************************************************
//sec:saveeditClass-saveaddClass validate for saveeditClass or saveaddClass
//*****************************************************************************************
	if($mode=="saveeditClass" or $mode=="saveaddClass"){
		$errorMessage="";
		if($CoursDescription==""){
			$errorMessage="لابد من ادخال اسم الفصل<br>";
		}
		if($CoursCrsId=="-1"){
			$errorMessage.="لابد من ادخال التخصص<br>";
		}

		if($errorMessage != ""){
			$mode=substr($mode,4);
		}
	}

//*****************************************************************************************
//sec:saveedit save edit Batch
//*****************************************************************************************
	if($mode=="saveedit"){
		$q="UPDATE Years SET YearDesc=?,Yearstart=?,YearEnd=? WHERE YearId=?";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,"sssi",$YearDesc,$Yearstart,$YearEnd,$YearId)){
				if(mysqli_stmt_execute($stmt)){
					$message="تم الحفظ!...";
				}    
			}
			mysqli_stmt_close($stmt);
		}
	}

//*****************************************************************************************
//sec:saveeditClass save edit data
//*****************************************************************************************
if($mode=="saveeditClass"){
	$q="UPDATE Courses SET CoursDescription=?,CoursCrsId=? WHERE CoursId=?";
	if ($stmt = mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt,"sii",$CoursDescription,$CoursCrsId,$CoursId)){
			if(mysqli_stmt_execute($stmt)){
				$message="تم الحفظ!...";
			}    
		}
		mysqli_stmt_close($stmt);
	}
	$mode="classes";
}

//*****************************************************************************************
//sec:saveeditClass save edit data
//*****************************************************************************************
if($mode=="saveeditPeriod"){
	$q="UPDATE coursPeriods SET crprDescription=?,crprFrom=?,crprTo=? WHERE crprId=?";
	if ($stmt = mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt,"sssi",$crprDescription,$crprFrom,$crprTo,$crprId)){
			if(mysqli_stmt_execute($stmt)){
				$message="تم الحفظ!...";
			}    
		}
		mysqli_stmt_close($stmt);
	}
	$mode="periods";
}


//*****************************************************************************************
//sec:saveadd Save added batch
//*****************************************************************************************
	if($mode=="saveadd"){
	$q="INSERT INTO Years(YearDesc,Yearstart,YearEnd,YearType) VALUES(?,?,?,'B')";
	if ($stmt = mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt,"sss",$YearDesc,$Yearstart,$YearEnd)){
			if(mysqli_stmt_execute($stmt)){
				$message="تم الحفظ!...";
				$YearId = mysqli_insert_id($dbc); // capture generated ID
			}
		}
		mysqli_stmt_close($stmt);
	}
}
	
//*****************************************************************************************
//sec:saveaddClass Save added Class
//*****************************************************************************************
	if($mode=="saveaddClass"){
		$q="INSERT INTO Courses(CoursDescription,CoursCrsId,CoursYear,CoursType,CoursArea,CoursSection,CoursDevidable,CoursLocation) VALUES(?,?,?,5,2,11,1,92)";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,"sii",$CoursDescription,$CoursCrsId,$YearId)){
				if(mysqli_stmt_execute($stmt)){
					$message="تم الحفظ!...";
				}    
			}
			mysqli_stmt_close($stmt);
		}
		$mode="classes";
	}

//*****************************************************************************************
//sec:saveaddPeriod Save added Periods
//*****************************************************************************************
	if($mode=="saveaddPeriod"){
		$q="INSERT INTO coursPeriods(crprDescription,crprFrom,crprTo,crprCourseId) VALUES(?,?,?,?)";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,"sssi",$crprDescription,$crprFrom,$crprTo,$CoursId)){
				if(mysqli_stmt_execute($stmt)){
					$message="تم الحفظ!...";
				}    
			}
			mysqli_stmt_close($stmt);
		}
		echo mysqli_error($dbc);
		$mode="periods";
	}

//*****************************************************************************************
//sec:delete delete batch
//*****************************************************************************************
	if($mode == "delete"){
		$q="DELETE FROM Years WHERE YearId=?";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,"i",$YearId)){
				if(mysqli_stmt_execute($stmt)){
					$message="تم الالغاء!...";
				}    
			}
			mysqli_stmt_close($stmt);
		}
	}

//*****************************************************************************************
//sec:delete delete class
//*****************************************************************************************
	if($mode == "deleteClass"){
		$q="DELETE FROM Courses WHERE CoursId=?";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,"i",$CoursId)){
				if(mysqli_stmt_execute($stmt)){
					$message="تم الالغاء!...";
				}    
			}
			mysqli_stmt_close($stmt);
		}
		$mode="classes";
	}

//*****************************************************************************************
//sec:delete delete class
//*****************************************************************************************
if($mode == "deletePeriod"){
	$q="DELETE FROM coursPeriods WHERE crprId=?";
	if ($stmt = mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt,"i",$crprId)){
			if(mysqli_stmt_execute($stmt)){
				$message="تم الالغاء!...";
			}    
		}
		mysqli_stmt_close($stmt);
	}
	$mode="periods";
}

//*****************************************************************************************
// sec: add-edit New Period form
//*****************************************************************************************
if($mode=="addPeriod" or $mode=="editPeriod"){
	$showList=false;
	$batch=readBatch($dbc,$YearId);
	$class=readClass($dbc,$CoursId);
	if(isset($errorMessage)){
		echo "<div class='alertmessages'>$errorMessage</div>";
	}
	if($mode=='editPeriod'){
		echo "<h2>تعديل بيانات فترة في فصل ".$class['Description']." في دفعة: ".$batch['Description']."</h2>";
		$period=readPeriod($dbc,$crprId);
		$crprDescription=$period['Description'];
		$crprFrom=$period['From'];
		$crprTo=$period['To'];
	}else{
		echo "<h2>فترة جديدة في فصل ".$class['Description']." في دفعة: ".$batch['Description']."</h2>";
	}
	echo "<center><form method='post'>";
	echo "<table>";
	if($mode=="editPeriod"){
		echo "<tr><td style='width: 100px;'>كود الفترة:</td><td style='width:650px;'>";
		echo "<div class='input-container'>";
		echo "<input class='input-field' type='text' readonly placeholder='كود الفصل' maxlength='255' name='crprId'";
		if(isset($crprId)){
			echo " value='$crprId'";
		} 
		echo "></div></td></tr>";  	
	}

	echo "<tr><td style='width: 100px;'>اسم الفترة:</td><td style='width:650px;'>";
	echo "<div class='input-container'>";
	echo "<input class='input-field' type='text' placeholder='اسم الفترة' maxlength='255' name='crprDescription'";
	if(isset($crprDescription)){
		echo " value='$crprDescription'";
	} 
	echo "></div></td></tr>";  

	echo "<tr><td style='width: 100px;'>الفترة من:</td><td style='width:650px;'>";
	echo "<div class='input-container'>";
	echo "<input class='input-field' type='date' placeholder='الفترة من' name='crprFrom'";
	if(isset($crprFrom)){
		echo " value='$crprFrom'";
	} 
	echo "></div></td></tr>";  

	echo "<tr><td style='width: 100px;'>الفترة الى:</td><td style='width:650px;'>";
	echo "<div class='input-container'>";
	echo "<input class='input-field' type='date' placeholder='الفترة الى' name='crprTo'";
	if(isset($crprTo)){
		echo " value='$crprTo'";
	} 
	echo "></div></td></tr>";  

	echo "</table>";

	echo "<input type='hidden' name='CoursId' value='$CoursId'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	$newMode="save$mode";
	//save and cancel buttons
	echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'> حفظ </button> <button type='submit' class='cnlBtn' name='mode' value='periods'> تراجع </button></div>";
	echo "</form>";
	echo "</center>";	
}

//*****************************************************************************************
// sec: add-edit New class form
//*****************************************************************************************
if($mode=="addClass" or $mode=="editClass"){
	$showList=false;
	$batch=readBatch($dbc,$YearId);
	if(isset($errorMessage)){
		echo "<div class='alertmessages'>$errorMessage</div>";
	}
	if($mode=='editClass'){
		echo "<h2>تعديل بيانات فصل في دفعة: ".$batch['Description']."</h2>";
		$class=readClass($dbc,$CoursId);
		$CoursDescription=$class['Description'];
		$CoursCrsId=$class['Speciality'];
	}else{
		echo "<h2>فصل جديد</h2>";
	}
	echo "<center><form method='post'>";
	echo "<table>";
	if($mode=="editClass"){
		echo "<tr><td style='width: 100px;'>كود الفصل:</td><td style='width:650px;'>";
		echo "<div class='input-container'>";
		echo "<input class='input-field' type='text' readonly placeholder='كود الفصل' maxlength='255' name='CoursId'";
		if(isset($CoursId)){
			echo " value='$CoursId'";
		} 
		echo "></div></td></tr>";  	
	}
	echo "<tr><td style='width: 100px;'>اسم الفصل:</td><td style='width:650px;'>";
	echo "<div class='input-container'>";
	echo "<input class='input-field' type='text' placeholder='اسم الفصل' maxlength='255' name='CoursDescription'";
	if(isset($CoursDescription)){
		echo " value='$CoursDescription'";
	} 
	echo "></div></td></tr>";  

	$spc=readSpecialities($dbc);
	echo "<tr><td style='width: 100px;'>التخصص:</td><td style='width:650px;'>";
	echo "<div class='input-container'>";
	echo "<select class='input-field' name='CoursCrsId'><option value='-1'>حدد التخصص</option>";
	foreach($spc as $key => $value){
		echo "<option value='$key'";
		if(isset($CoursCrsId)){
			if($CoursCrsId == $key){
				echo " selected";
			}
		}
		echo ">$value</option>";
	}
	echo "</select></div></td></tr>";

	echo "</table>";

	echo "<input type='hidden' name='YearId' value='$YearId'>";
	$newMode="save$mode";
	//save and cancel buttons
	echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'> حفظ </button> <button type='submit' class='cnlBtn' name='mode' value='classes'> تراجع </button></div>";
	echo "</form>";
	echo "</center>";	
}

//*****************************************************************************************
//sec:add-edit Add/Edit new batch form
//*****************************************************************************************
	if($mode=="add" or $mode=="edit"){
        $showList=false;
        if(isset($errorMessage)){
            echo "<div class='alertmessages'>$errorMessage</div>";
        }
		if($mode=='edit'){
			echo "<h2>تعديل بيانات دفعة</h2>";
			$batch=readBatch($dbc,$YearId);
			$YearDesc=$batch['Description'];
			$Yearstart=$batch['Start'];
			$YearEnd=$batch['End'];
		}else{
			echo "<h2>دفعة جديدة</h2>";
		}
		echo "<center><form method='post'>";
		echo "<table>";
		if($mode=="edit"){
			echo "<tr><td style='width: 100px;'>كود الدفعة:</td><td style='width:650px;'>";
			echo "<div class='input-container'>";
			echo "<input class='input-field' type='text' readonly placeholder='كود الدفعة' maxlength='255' name='YearId'";
			if(isset($YearId)){
				echo " value='$YearId'";
			} 
			echo "></div></td></tr>";  	
		}
		echo "<tr><td style='width: 100px;'>اسم الدفعة:</td><td style='width:650px;'>";
		echo "<div class='input-container'>";
		echo "<input class='input-field' type='text' placeholder='اسم الدفعة' maxlength='255' name='YearDesc'";
		if(isset($YearDesc)){
			echo " value='$YearDesc'";
		} 
		echo "></div></td></tr>";  

		echo "<tr><td style='width: 100px;'>تاريخ البداية:</td><td style='width:650px;'>";
		echo "<div class='input-container'>";
		echo "<input class='input-field' type='date' placeholder='تاريخ البداية' name='Yearstart'";
		if(isset($Yearstart)){
			echo " value='$Yearstart'";
		} 
		echo "></div></td></tr>";  

		echo "<tr><td style='width: 100px;'>تاريخ النهاية:</td><td style='width:650px;'>";
		echo "<div class='input-container'>";
		echo "<input class='input-field' type='date' placeholder='تاريخ النهاية' name='YearEnd'";
		if(isset($YearEnd)){
			echo " value='$YearEnd'";
		} 
		echo "></div></td></tr>";  

		echo "</table>";
		$newMode="save$mode";
		//save and cancel buttons
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'> حفظ </button> <button type='button' class='cnlBtn' onclick='window.location=\"$filename\";'> تراجع </button></div>";
		echo "</form>";
		echo "</center>";	
	}

//*****************************************************************************************
//sec:view display batch
//*****************************************************************************************
	if($mode == "view"){
		$showList=false;
		$batch=readBatch($dbc,$YearId);
		echo "<center>";
		echo "<h2> بيانات دفعة </h2>";
		echo "<table>";
		echo "<tr><td style='width: 100px;'>كود الدفعة:</td><td style='width:650px;'><div class='input-container'>$YearId</div></td></tr>";
		echo "<tr><td style='width: 100px;'>اسم الدفعة:</td><td style='width:650px;'><div class='input-container'>".$batch['Description']."</div></td></tr>";
		echo "<tr><td style='width: 100px;'>تاريخ البداية:</td><td style='width:650px;'><div class='input-container'>".$batch['Start']."</div></td></tr>";
		echo "<tr><td style='width: 100px;'>تاريخ النهاية:</td><td style='width:650px;'><div class='input-container'>".$batch['End']."</div></td></tr>";
		echo "</table>";
		echo "<div class='frmButtons'><button type='button' class='okBtn' onclick='window.location=\"$filename\";'> حسنا </button></div>";
		echo "</center>";	
	}

//*****************************************************************************************
//sec:viewClass display class
//*****************************************************************************************
	if($mode == "viewClass"){
		$showList=false;
		$class=readClass($dbc,$CoursId);
		$batch=readBatch($dbc,$YearId);
		$spc=readSpecialities($dbc);
		$bat=readBatchs($dbc);
		echo "<center>";
		echo "<h2> بيانات فصل في دفعة: ".$batch['Description']."</h2>";
		echo "<table>";
		echo "<tr><td style='width: 100px;'>كود الفصل:</td><td style='width:650px;'><div class='input-container'>$CoursId</div></td></tr>";
		echo "<tr><td style='width: 100px;'>اسم الفصل:</td><td style='width:650px;'><div class='input-container'>".$class['Description']."</div></td></tr>";
		echo "<tr><td style='width: 100px;'>التخصص:</td><td style='width:650px;'><div class='input-container'>".$spc[$class['Speciality']]."</div></td></tr>";
		echo "<tr><td style='width: 100px;'>الدفعة:</td><td style='width:650px;'><div class='input-container'>".$bat[$class['Batch']]."</div></td></tr>";
		echo "</table>";
		echo "<form method='post'>";
		echo "<input type='hidden' name='YearId' value='$YearId'>";
		echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode' value='classes'> حسنا </button></div>";
		echo "</form>";
		echo "</center>";	
	}

//*****************************************************************************************
//sec:viewClass display Period
//*****************************************************************************************
if($mode == "viewPeriod"){
	$showList=false;
	$class=readClass($dbc,$CoursId);
	$batch=readBatch($dbc,$YearId);
	$period=readPeriod($dbc,$crprId);
	$spc=readSpecialities($dbc);
	$bat=readBatchs($dbc);
	echo "<center>";
	echo "<h2>بيانات الفترة ".$period['Description']." في فصل ".$class['Description']." في دفعة: ".$batch['Description']."</h2>";
	echo "<table>";
	echo "<tr><td style='width: 100px;'>كود الفترة:</td><td style='width:650px;'><div class='input-container'>$crprId</div></td></tr>";
	echo "<tr><td style='width: 100px;'>اسم الفترة:</td><td style='width:650px;'><div class='input-container'>".$period['Description']."</div></td></tr>";
	echo "<tr><td style='width: 100px;'>من:</td><td style='width:650px;'><div class='input-container'>".$period['From']."</div></td></tr>";
	echo "<tr><td style='width: 100px;'>إلى:</td><td style='width:650px;'><div class='input-container'>".$period['To']."</div></td></tr>";
	echo "</table>";
	echo "<form method='post'>";
	echo "<input type='hidden' name='crprId' value='$crprId'>";
	echo "<input type='hidden' name='CoursId' value='$CoursId'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode' value='periods'> حسنا </button></div>";
	echo "</form>";
	echo "</center>";	
}

//*****************************************************************************************
//sec:deleteconfirm delete batch confirm 
//*****************************************************************************************
	if($mode=="deleteconfirm"){
		$showList=false;
		$batch=readBatch($dbc,$YearId);
		echo "<center>";
		echo "<h2> الغاء دفعة </h2>";
		echo "<div style='text-align: center;'>سيتم الغاء ".$batch['Description']." <br> هل أنت متأكد؟...</div><br>";
		echo "<form method='post' style='max-width:500px;margin:auto;'>";
		echo "<input type='hidden' name='YearId' value='$YearId'>";
		echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='delete'> نعم </button> <button type='button' class='cnlBtn' name='mode' onclick='window.location=\"$filename\";'> لا </button></div>";
		echo "</form>";
  		echo "</center>";
	}

//*****************************************************************************************
//sec:deleteClassConfirm delete class confirm
//*****************************************************************************************
	if($mode=="deleteClassConfirm"){
		$showList=false;
		$class=readClass($dbc,$CoursId);
		echo "<center>";
		echo "<h2> الغاء فصل </h2>";
		echo "<div style='text-align: center;'>سيتم الغاء ".$class['Description']." <br> هل أنت متأكد؟...</div><br>";
		echo "<form method='post' style='max-width:500px;margin:auto;'>";
		echo "<input type='hidden' name='CoursId' value='$CoursId'>";
		echo "<input type='hidden' name='YearId' value='$YearId'>";
		echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='deleteClass'> نعم </button> <button class='cnlBtn' type='submit' name='mode' value='classes'> لا </button></div>";
		echo "</form>";
		echo "</center>";
	}

//*****************************************************************************************
//sec:deletePeriodConfirm delete Period confirm
//*****************************************************************************************
if($mode=="deletePeriodConfirm"){
	$showList=false;
	$period=readPeriod($dbc,$crprId);
	echo "<center>";
	echo "<h2> الغاء فترة </h2>";
	echo "<div style='text-align: center;'>سيتم الغاء ".$period['Description']." <br> هل أنت متأكد؟...</div><br>";
	echo "<form method='post' style='max-width:500px;margin:auto;'>";
	echo "<input type='hidden' name='crprId' value='$crprId'>";
	echo "<input type='hidden' name='CoursId' value='$CoursId'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='deletePeriod'> نعم </button> <button class='cnlBtn' type='submit' name='mode' value='periods'> لا </button></div>";
	echo "</form>";
	echo "</center>";
}

//****************************************************************************************
//sec:periods the periods list
//****************************************************************************************
if($mode == "periods"){
	$showList=false;
	$batch=readBatch($dbc,$YearId);
	$class=readClass($dbc,$CoursId);
	if(isset($errorMessage)){
		echo "<div class='alertmessages'>$errorMessage</div>";
		echo "<br><br>";
	}
	if(isset($message)){
		echo "<div class='infomessages'>$message</div>";
		echo "<br><br>";
	}
	echo "<h2>فترات فصل ".$class['Description']." دفعة ".$batch['Description']."</h2>";
	//new form
	echo "<div style='width: 110px; margin: auto;'><form method='post'>";
	echo "<button type='submit' class='addBtn' name='mode' value='addPeriod'>فترة جديدة</button>";
	echo "<input type='hidden' name='CoursId' value='$CoursId'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "</form></div><br>";
	$numberOfButtons=4;
	$buttonCellWidth=$numberOfButtons * 115;
	$buttonCellWidth .= "px";
	//filter list form 
	echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث...'>";
	echo "<table id='masterTable'><tr class='header'><th> الفترة </th><th style='width: $buttonCellWidth'>&nbsp;</th></tr>";
	$periods=readPeriods($dbc,$CoursId);
	foreach($periods as $key => $value){
		$description=$value['Description'];
		$status=$value['Status'];
		$devInfo="";
		if(isset($perms['Developer'])){
			$devInfo="[Id=$key - Status=$status]";
		}
		$editDisabled="";
		$deleteDisabled="";
		$editClass="edtBtn";
		$deleteClass="delBtn";
		if($status>$formCourseStatusLevel){
			$editDisabled=" disabled";
			$deleteDisabled=" disabled";
			$editClass="disabledBtn";
			$deleteClass="disabledBtn";
		}
		echo "<tr>";
		echo "<td>$description $devInfo</td>";
		echo "<td style='width: $buttonCellWidth;'>";
		echo "<form method='post'>";
		echo "<input type='hidden' name='crprId' value='$key'>";
		echo "<input type='hidden' name='CoursId' value='$CoursId'>";
		echo "<input type='hidden' name='YearId' value='$YearId'>";
		echo "<button type='submit' class='$editClass' name='mode' value='editPeriod'$editDisabled>تعديل</button> ";
		echo "<button type='submit' class='viewBtn' name='mode' value='viewPeriod'>عرض</button> ";
		echo "<button type='submit' class='$deleteClass' name='mode' value='deletePeriodConfirm'$deleteDisabled>الغاء</button> ";
		echo "</form>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<div class='frmButtons'><form method='post'><input type='hidden' name='CoursId' value='$CoursId'><input type='hidden' name='YearId' value='$YearId'><button type='submit' class='cnlBtn' name='mode' value='classes'> رجوع </button></div>";
}

//****************************************************************************************
//sec:classes the classes list
//****************************************************************************************
	if($mode == "classes"){
		$showList=false;
		$batch=readBatch($dbc,$YearId);
		if(isset($errorMessage)){
			echo "<div class='alertmessages'>$errorMessage</div>";
			echo "<br><br>";
		}
		if(isset($message)){
			echo "<div class='infomessages'>$message</div>";
			echo "<br><br>";
		}
		echo "<h2>فصول ".$batch['Description']."</h2>";
		//new form
		echo "<div style='width: 110px; margin: auto;'><form method='post'>";
		echo "<button type='submit' class='addBtn' name='mode' value='addClass'>فصل جديدة</button>";
		echo "<input type='hidden' name='YearId' value='$YearId'>";
		echo "</form></div><br>";
		$numberOfButtons=4;
		$buttonCellWidth=$numberOfButtons * 115;
		$buttonCellWidth .= "px";
		//filter list form 
		echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث...'>";
		echo "<table id='masterTable'><tr class='header'><th> الفصل </th><th style='width: $buttonCellWidth'>&nbsp;</th></tr>";
		$classes=readclasses($dbc,$YearId);
		foreach($classes as $key => $value){
			$periods=readPeriods($dbc,$key);
			$description=$value['Description'];
			$status=$value['Status'];
			$devInfo="";
			if(isset($perms['Developer'])){
				$devInfo="[Id=$key - Periods=".count($periods)." - Status=$status]";
			}
			$editDisabled="";
			$deleteDisabled="";
			$editClass="edtBtn";
			$deleteClass="delBtn";
			if($status>$formCourseStatusLevel){
				$editDisabled=" disabled";
				$deleteDisabled=" disabled";
				$editClass="disabledBtn";
				$deleteClass="disabledBtn";
			}
			echo "<tr>";
			echo "<td> $description $devInfo</td>";
			echo "<td style='width: $buttonCellWidth;'>";
			echo "<form method='post'>";
			echo "<input type='hidden' name='CoursId' value='$key'>";
			echo "<input type='hidden' name='YearId' value='$YearId'>";
			echo "<button type='submit' class='$editClass' name='mode' value='editClass'$editDisabled>تعديل</button> ";
			echo "<button type='submit' class='viewBtn' name='mode' value='viewClass'>عرض</button> ";
			echo "<button type='submit' class='$deleteClass' name='mode' value='deleteClassConfirm'$deleteDisabled>الغاء</button> ";
			echo "<button type='submit' class='periodBtn' name='mode' value='periods'>الفترات</button> ";
			echo "</form>";
			echo "</tr>";
		}
		echo "</table>";
		echo "<div class='frmButtons'><button type='button' class='cnlBtn' name='mode' onclick='window.location=\"$filename\";'> رجوع </button></div>";
	}

//****************************************************************************************
//sec:list the batch list
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
		echo "<button type='submit' class='addBtn' name='mode' value='add'>دفعة جديدة</button>";
		echo "</form></div><br>";
		$numberOfButtons=4;
		$buttonCellWidth=$numberOfButtons * 115;
		$buttonCellWidth .= "px";
		//filter list form 
		$batchs=readBatchs($dbc);
		echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث...'>";
		echo "<table id='masterTable'><tr class='header'><th> الدفعة </th><th style='width: $buttonCellWidth'>&nbsp;</th></tr>";
		foreach($batchs as $key => $value){
			$classes=readClasses($dbc,$key);
			$editDisabled="";
			$deleteDisabled="";
			$editClass="edtBtn";
			$deleteClass="delBtn";
			if(count($classes)>0){
				$editDisabled=" disabled";
				$deleteDisabled=" disabled";
				$editClass="disabledBtn";
				$deleteClass="disabledBtn";
			}
			echo "<tr>";
			$devInfo="";
			if(isset($perms['Developer'])){
				$devInfo="[Id=$key - Classes=".count($classes)."]";
			}
			echo "<td> $value $devInfo</td>";
			echo "<td style='width: $buttonCellWidth;'>";
			echo "<form method='post'>";
			echo "<input type='hidden' name='YearId' value='$key'>";
			echo "<button type='submit' class='$editClass' name='mode' value='edit'$editDisabled>تعديل</button> ";
			echo "<button type='submit' class='viewBtn' name='mode' value='view'>عرض</button> ";
			echo "<button type='submit' class='$deleteClass' name='mode' value='deleteconfirm'$deleteDisabled>الغاء</button> ";
			echo "<button type='submit' class='clsBtn' name='mode' value='classes'>الفصول</button> ";
			echo "</form>";
			echo "</tr>";

		}
		echo "</table>";
	}
}
//*****************************************************************************************
//sec:javascript Form scripts
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