<?php
//uncomment those two lines for debugging
//ini_set('display_errors',1); 
//error_reporting(E_ALL);
session_start();
$__systemRoot="../";
include($__systemRoot.'functions.php');        //include system functions
include('appdb.php');             			   //include app database connection
//include('functions.php');         		   //uncomment this line if you have a local functions file
if(isset($_SESSION['__uid'])){        		   //check for session and set the sesOk variable
    $__uid=$_SESSION['__uid'];
    $sesOk=true;
}else{
    $sesOk=false;
}
$gdir="rtl";
$galign="right";
$title="الدبلومات";
$subtitle="";
?>
<!DOCTYPE html>
<html dir="<?php echo $gdir; ?>">
    <head>
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
        <style>
    		* {
				box-sizing: border-box;
			}
			body {
				direction: <?php echo $gdir; ?>;
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
				text-align: <?php echo $galign; ?>;
				padding: 12px;
			}
    		#masterTable tr {
				border-bottom: 1px solid #ddd;
			}
    		#masterTable tr.header, #masterTable tr:hover {
				background-color: #f1f1f1;
			}
			.disBtn {
				background-color: darkgrey;
				color: white;  
				padding: 10px 10px;  
				border: none;  
				cursor: pointer;  
				width: 100px;  
				opacity: 0.9;
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
    		.errorMessages{
				color: red;
				font-size: medium;
				text-align:center;
			}
			.infoMessages{
				color: green;
				font-size: medium;
				text-align:center;
			}
    		.frmButtons{
				text-align:center;
				margin: auto;
				width: 220px;
			}
		</style>
    </head>
    <body>
	<br><h2><?php echo $title; ?></h2><h3><?php echo $subtitle; ?></h3>
<?php
if($sesOk){
    //session is up check for user permission!...   
    $filename=basename(__FILE__);                               //get script name
    $mnuId=getCommandMenuId($filename);                         //get sreen id
    if(checkUserMenuItem($__uid,$mnuId)){                       //check for user permission to use the screen
        $prmOk=true;
    }else{
        $prmOk=false;
        echo "<br><div class='errorMessages' style='direction: ltr'>Access denied!...</div>";    
    }   
}else{
    //session not up display error!...
    include($__systemRoot.'expired.php');
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
        $mode="showList";
    }
	if(!isset($errorMessage)){
		$errorMessage="";
	}
	if(!isset($infoMessage)){
		$infoMessage="";
	}
	if(!isset($errorNo)){
		$errorNo = 0;
	}
	if(!isset($validationError)){
		$validationError=false;
	}


//*****************************************************************************************
//sec:edit-view-deleteConfirm read a record for view or edit
//*****************************************************************************************
if($mode=="edit" or $mode=="view" or $mode=="deleteConfirm" or $mode=="subjectList" or $mode=="viewSubject" or $mode == "addSubject" or $mode == "editSubject" or $mode=="deleteConfirmSubject" or $mode=="setupSemester"){
    $q="SELECT PrgCode,PrgName,semesterCount,PrgId FROM Programs WHERE PrgId =?";
    if($stmt=mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $PrgId )){
            if(mysqli_stmt_execute($stmt)){
                if(mysqli_stmt_bind_result($stmt, $PrgCode,$PrgName,$semesterCount,$PrgId)){
                    if(!mysqli_stmt_fetch($stmt)){
                        $errorMessage="خطأ في البيانات!...";
                        $mode="showList";
                    }
                }
            }    
        }
        mysqli_stmt_close($stmt);
    }
}

//*****************************************************************************************
//sec:edit-view-deleteConfirm read a record for view or edit subject
//*****************************************************************************************
if($mode=="viewSubject" or $mode == "editSubject" or $mode=="deleteConfirmSubject"){
    $q="SELECT `CrsName`,`CrsCode`, `CrsTHours`, `CrsNameEng` FROM `CoursesGuide` WHERE `CrsId`=?";
    if($stmt=mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CrsId)){
            if(mysqli_stmt_execute($stmt)){
                if(mysqli_stmt_bind_result($stmt, $CrsName,$CrsCode,$CrsTHours,$CrsNameEng)){
                    if(!mysqli_stmt_fetch($stmt)){
                        $errorMessage="خطأ في البيانات!...";
                        $mode="showList";
                    }
                }
            }    
        }
        mysqli_stmt_close($stmt);
    }
}

//*****************************************************************************************
//sec:saveedit-saveadd validate for saveedit or saveadd
//*****************************************************************************************
if ($mode == "saveedit" || $mode == "saveadd") {
    $errorMessage = "";

    // التحقق من إدخال وصف الدبلومة
    if ($PrgName == "") {
        $errorMessage .= "لابد من إدخال اسم الدبلومة<br>";
    }

	if ($errorMessage != "") {
        $mode = substr($mode, 4); 
    }
}

//*****************************************************************************************
//sec:saveedit save edit data
//*****************************************************************************************
if ($mode == "saveedit") {
    $q = "UPDATE Programs SET PrgName=?,PrgCode=?,semesterCount=? WHERE PrgId=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "ssii", $PrgName, $PrgCode, $semesterCount, $PrgId)) {
            try {
                if (mysqli_stmt_execute($stmt)) {
                    $infoMessage = "تم الحفظ بنجاح!";
                }
            } catch (Exception $e) {
                $errorMessage = "حدث خطأ أثناء الحفظ: " . $e->getMessage();
            }
        }
        mysqli_stmt_close($stmt); 
    }
    $mode = "view";
}

//*****************************************************************************************
//sec:saveedit save edit subject data
//*****************************************************************************************
if ($mode == "saveeditSubject") {
    $q = "UPDATE CoursesGuide SET CrsCode=?,CrsName=?,CrsNameEng=?,CrsTHours=? WHERE CrsId =?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "sssii", $CrsCode,$CrsName,$CrsNameEng,$CrsTHours,$CrsId)) {
            try {
                if (mysqli_stmt_execute($stmt)) {
                    $infoMessage = "تم الحفظ بنجاح!";
                }
            } catch (Exception $e) {
                $errorMessage = "حدث خطأ أثناء الحفظ: " . $e->getMessage();
            }
        }
        mysqli_stmt_close($stmt); 
    }
    $mode = "subjectList";
}

//*****************************************************************************************
//sec:saveadd Save added data
//*****************************************************************************************
if ($mode == "saveadd") {
    $q = "INSERT INTO Programs(PrgCode,PrgName,semesterCount) VALUES(?,?,?)";
//	echo $q ."-" .$PrgCode."-" .$PrgName."-" .$semesterCount;

    if ($stmt = mysqli_prepare($dbc, $q)){
        if (mysqli_stmt_bind_param($stmt, "ssi", $PrgCode,$PrgName,$semesterCount)) {
            if (mysqli_stmt_execute($stmt)) {
                $infoMessage = "تمت الإضافة بنجاح!";
            } else {
                $errorMessage = "لم يتم الحفظ<br>" . mysqli_error($dbc) . "!";
            }
        }
        mysqli_stmt_close($stmt);
    }
    $mode = "showList";
}

//*****************************************************************************************
//sec:saveadd Save added subject data
//*****************************************************************************************
if ($mode == "saveaddSubject") {
    $q = "INSERT INTO CoursesGuide(CrsProgram ,CrsCode,CrsName, CrsNameEng, CrsTrnType, CrsTHours) VALUES(?,?,?,?,1,?)";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if (mysqli_stmt_bind_param($stmt, "isssi", $PrgId,$CrsCode,$CrsName,$CrsNameEng,$CrsTHours)) {

	if (mysqli_stmt_execute($stmt)) {
                $infoMessage = "تمت الإضافة بنجاح!";
            } else {
                $errorMessage = "لم يتم الحفظ<br>" . mysqli_error($dbc) . "!";
            }
        }
        mysqli_stmt_close($stmt);
    }

   
   $mode = "subjectList";
}


//*****************************************************************************************
//sec:delete record
//*****************************************************************************************
if ($mode == "delete") {
    $q = "DELETE FROM Programs WHERE PrgId =?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $PrgId )) { // ربط معرّف الدبلومة

			
		try {
			if (mysqli_stmt_execute($stmt)) {
                $infoMessage = "تم الإلغاء بنجاح!";
            }
			} catch (mysqli_sql_exception $e) {
					$infoMessage = "لم يتم الالغاء لوجود مواد دراسية مرتبطه بالدبلومة";
					}
	
        }
        mysqli_stmt_close($stmt); 
    }
    $mode = "showList";  
}

//*****************************************************************************************
//sec:delete subject record
//*****************************************************************************************
if ($mode == "deleteSubject") {
    $q = "DELETE FROM CoursesGuide WHERE CrsId =?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $CrsId )) { 
  		try {
          if (mysqli_stmt_execute($stmt)) {
                $infoMessage = "تم الإلغاء بنجاح!";
            }
			} catch (mysqli_sql_exception $e) {
					$infoMessage = "لم يتم الالغاء لتسجيل طالب او اكثر للماده";
			}

			
        }
        mysqli_stmt_close($stmt); 
    }
    $mode = "subjectList";  
}

//*****************************************************************************************
//sec:add-edit Add/Edit Subject form 
//*****************************************************************************************
if ($mode == "addSubject" or $mode == "editSubject") {
    if (isset($errorMessage)) {
        echo "<div class='errorMessages'>$errorMessage</div>";
    }
    if ($mode == "addSubject") {
        $formTitle = " إضافة مادة جديدة";
    } else {
        $formTitle = "تعديل مادة";
    }

    echo "<h3>$formTitle</h3>";
	echo "<h3>$PrgName</h3>";
    echo "<center><form method='post'>";
    echo "<table>";

	echo "<tr><td style='width: 100px;'>كود الماده:</td><td style='width:650px;'>";
    echo "<div class='input-container'>";
    echo "<input class='input-field' type='text' placeholder='كود الماده' max='7' min='0' name='CrsCode'";
    if (isset($CrsCode)) {
        echo " value='$CrsCode'";
    }
    echo "></div></td></tr>";
	
	echo "<tr><td style='width: 100px;'>اسم الماده:</td><td style='width:650px;'>";
    echo "<div class='input-container'>";
    echo "<input class='input-field' type='text' placeholder='اسم الماده' max='50' min='0' name='CrsName'";
    if (isset($CrsName)) {
        echo " value='$CrsName'";
    }
    echo "></div></td></tr>";
	
	echo "<tr><td style='width: 100px;'>اسم الماده باللغة الانجليزية:</td><td style='width:650px;'>";
    echo "<div class='input-container'>";
    echo "<input class='input-field' type='text' placeholder='اسم الماده باللغة الانجليزية' max='50' min='0' name='CrsNameEng'";
    if (isset($CrsNameEng)) {
        echo " value='$CrsNameEng'";
    }
    echo "></div></td></tr>";

	echo "<tr><td style='width: 100px;'>عدد الساعات المعتمدة:</td><td style='width:650px;'>";
    echo "<div class='input-container'>";
    echo "<input class='input-field' type='text' placeholder='عدد الساعات المعتمدة' max='50' min='0' name='CrsTHours'";
    if (isset($CrsTHours)) {
        echo " value='$CrsTHours'";
    }
    echo "></div></td></tr>";

    echo "</table>";
    if (isset($CrsId)) {
        echo "<input type='hidden' name='CrsId' value='$CrsId'>";
    }	
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
    echo "<input type='hidden' name='PrgName' value='$PrgName'>";

    $newMode = "save$mode";
    // Save and cancel buttons
    echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'> حفظ </button> <button type='submit' class='okBtn' name='mode' value='subjectList'> تراجع </button></div>";
    echo "</form>";
    echo "</center>";
}


//*****************************************************************************************
//sec:delete subject from Semester record
//*****************************************************************************************
if ($mode == "removeCourseSemester") {
    $q = "DELETE FROM ProgramCoursesSemester WHERE CrsId =? AND PrgId=? AND Semester=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "iii", $CrsId, $PrgId, $Semester)) { 
  		try {
          if (mysqli_stmt_execute($stmt)) {
                $infoMessage = "تم الإلغاء بنجاح!";
            }
			} catch (mysqli_sql_exception $e) {
					$infoMessage = "لم يتم الالغاء لتسجيل طالب او اكثر للماده";
			}

			
        }
        mysqli_stmt_close($stmt); 
    }
    $mode = "setupSemester";  
}
//*****************************************************************************************
//sec:saveadd Save semester subject data
//*****************************************************************************************
if ($mode == "saveSemester") {
    $q = "INSERT INTO ProgramCoursesSemester(PrgId, CrsId, Semester) VALUES(?,?,?)";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if (mysqli_stmt_bind_param($stmt, "iii", $PrgId,$CrsId,$Semester)) {

	if (mysqli_stmt_execute($stmt)) {
                $infoMessage = "تمت الإضافة بنجاح!";
            } else {
                $errorMessage = "لم يتم الحفظ<br>" . mysqli_error($dbc) . "!";
            }
        }
        mysqli_stmt_close($stmt);
    }

   
   $mode = "setupSemester";
}
//*****************************************************************************************
//sec:Semester setup with courses assigned to this program 
//*****************************************************************************************
if ($mode == "setupSemester") {
    if (isset($errorMessage)) {
        echo "<div class='errorMessages'>$errorMessage</div>";
    }


    echo "<h3>-الفصول الدراسية لدبلومة $PrgName </h3>";
    echo "<center><form method='post'>";
    echo "<input type='hidden' name='PrgId' value='$PrgId'>";
    echo "<input type='hidden' name='PrgName' value='$PrgName'>";	
    echo "<table>";

    // اسم الدبلومة
    echo "<tr><td style='width: 100px;'>الفصل الدراسي:</td><td style='width:650px;'>";
    echo "<div class='input-container'>";
    echo "<input class='input-field' type='text' placeholder='الفصل الدراسي' maxlength='255' name='Semester'";
	echo "></div></td></tr>";	
	echo "<tr><td style='width: 225px;'>الماده الدراسية:</td><td><div class='input-container'>";
    echo "<select class='input-field' name='CrsId'><option value='-1'>اختار الماده</option>";
    
	
	$q="SELECT `CrsName`,`CrsCode` , `CrsId` FROM `CoursesGuide` WHERE `CrsProgram`=$PrgId ORDER BY `CrsCode`";

	if ($stmt = mysqli_prepare($dbc, $q)){
	   if(mysqli_stmt_execute($stmt)){
		   if(mysqli_stmt_bind_result($stmt, $CrsName, $CrsCode, $CrsId )){
			   while(mysqli_stmt_fetch($stmt)){
					echo "<option value='$CrsId'>$CrsCode - $CrsName</option>";
			   }
		   }
	   }
	   mysqli_stmt_close($stmt);
   }
     echo "</select></div></td></tr>";  


    echo "</table>";
    // Save and cancel buttons
    echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='saveSemester'> حفظ </button> <button type='submit' class='cnlBtn' name='mode' value='showList'> تراجع </button></div>";
    echo "</form>";
    echo "</center>";

      echo "<table id='masterTable'>";
      $lineNo=1;
	  $TempSemester = 0;

      $qq = "SELECT ProgramCoursesSemester.PrgId as PrgId, ProgramCoursesSemester.CrsId as CrsId, ProgramCoursesSemester.Semester as Semester, CoursesGuide.CrsCode as CrsCode, CoursesGuide.CrsName as CrsName FROM ProgramCoursesSemester,Programs,CoursesGuide where ProgramCoursesSemester.PrgId=$PrgId AND ProgramCoursesSemester.PrgId=Programs.PrgId AND ProgramCoursesSemester.CrsId = CoursesGuide.CrsId AND CoursesGuide.CrsProgram = Programs.PrgId ORDER BY ProgramCoursesSemester.Semester, CoursesGuide.CrsCode ASC;";
 
	if ($stmt = mysqli_prepare($dbc, $qq)){
	   if(mysqli_stmt_execute($stmt)){
		   if(mysqli_stmt_bind_result($stmt, $PrgId, $CrsId, $Semester, $CrsCode, $CrsName)){
			   while(mysqli_stmt_fetch($stmt)){
				   if($TempSemester != $Semester){
					echo "<tr><th style='text-align: right;'>الفصل الدراسي ($Semester)</th>";
					echo "<th style='text-align: left;'></th></tr>";
					$TempSemester = $Semester;
					$lineNo=1;
				   }
					echo "<tr><td style='text-align: right;'> $lineNo - $CrsCode - $CrsName</td>";
					echo "<td style='text-align: left;'><form method='post'>";
					echo "<input type='hidden' name='CrsId' value='$CrsId'>";
					echo "<input type='hidden' name='PrgId' value='$PrgId'>";
					echo "<input type='hidden' name='Semester' value='$Semester'>";					
					echo "<input type='hidden' name='PrgName' value='$PrgName'>";
					echo "<button type='submit' class='delBtn' name='mode' value='removeCourseSemester'>ازالة</button> ";
					echo "</form></td></tr>";
					$lineNo++;
				}
		   }
	   }
	}
    echo "</table>";
	
}
//*****************************************************************************************
//sec:add-edit Add/Edit form 
//*****************************************************************************************
if ($mode == "add" or $mode == "edit") {
    if (isset($errorMessage)) {
        echo "<div class='errorMessages'>$errorMessage</div>";
    }
    if ($mode == "add") {
        $formTitle = " دبلومة جديدة";
    } else {
        $formTitle = "تعديل الدبلومة";
    }

    echo "<h3>$formTitle</h3>";
    echo "<center><form method='post'>";
    echo "<table>";

    // اسم الدبلومة
    echo "<tr><td style='width: 100px;'>اسم الدبلومة:</td><td style='width:650px;'>";
    echo "<div class='input-container'>";
    echo "<input class='input-field' type='text' placeholder='اسم الدبلومة' maxlength='255' name='PrgName'";
    if (isset($PrgName)) {
        echo " value='$PrgName'";
    }

	echo "></div></td></tr>";
    echo "<tr><td style='width: 100px;'>كود الدبلومة:</td><td style='width:650px;'>";
    echo "<div class='input-container'>";
    echo "<input class='input-field' type='text' placeholder='كود الدبلومة' maxlength='1' name='PrgCode'";
    if (isset($PrgCode)) {
        echo " value='$PrgCode'";
    }
    echo "</div></td></tr>";
	
    echo "<tr><td style='width: 100px;'>عدد الفصول الدراسية</td><td style='width:650px;'>";
    echo "<div class='input-container'>";
    echo "<input class='input-field' type='text' placeholder='عدد الفصول الدراسية' maxlength='1' name='semesterCount'";
    if (isset($semesterCount)) {
        echo " value='$semesterCount'";
    }
    echo "</div></td></tr>";


    echo "</table>";
    if ($mode == 'edit') {
        echo "<input type='hidden' name='PrgId' value='$PrgId'>";
    }
    $newMode = "save$mode";
    // Save and cancel buttons
    echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'> حفظ </button> <button type='submit' class='cnlBtn' name='mode' value='showList'> تراجع </button></div>";
    echo "</form>";
    echo "</center>";
}

//*****************************************************************************************
//sec:view display track
//*****************************************************************************************
if ($mode == "view") {
    echo "<center>";
    echo "<h3>بيانات الدبلومة</h3>";
    echo "<table>";
    echo "<tr><td>كود الدبلومة</td><td>:</td><td align='right'> $PrgCode </td></tr>";
    echo "<tr><td>اسم الدبلومة</td><td>:</td><td align='right'> $PrgName </td></tr>";
    echo "<tr><td>عدد الفصول الدراسية</td><td>:</td><td align='right'> $semesterCount </td></tr>";
    echo "</table>";
	echo "<br>";
	$numberOfButtons = 3;
    $buttonCellWidth = $numberOfButtons * 115;
    $buttonCellWidth .= "px";
    
	echo "<div style='width: $buttonCellWidth;'>";
    echo "<form method='post'>";
		echo "<input type='hidden' name='PrgId' value='$PrgId'>";
		echo "<input type='hidden' name='PrgName' value='$PrgName'>";
		echo "<button type='button' class='okBtn' onclick='window.location=\"$filename\";'> حسنا </button> ";
		echo "<button type='submit' class='edtBtn' name='mode' value='edit'>تعديل</button> ";
		echo "<button type='submit' class='delBtn' name='mode' value='deleteConfirm'>حذف</button> ";	
	echo "</form>";
	echo "</div>";
    echo "</center>";
}

//*****************************************************************************************
//sec:view display subject
//*****************************************************************************************
if ($mode == "viewSubject") {
    echo "<center>";
    echo "<h3>بيانات مواد: $PrgName</h3>";
    echo "<table>";
    echo "<tr><td>كود المادة</td><td>:</td><td align='right'> $CrsCode </td></tr>";
    echo "<tr><td>اسم المادة</td><td>:</td><td align='right'> $CrsName </td></tr>";
	echo "<tr><td>الساعات المعتمدة</td><td>:</td><td align='right'> $CrsTHours </td></tr>";
    echo "</table>";
	echo "<br>";
    echo "<div class='frmButtons'><form method='post'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<button type='submit' class='okBtn' name='mode' value='subjectList'> حسنا </button></form></div>";
    echo "</center>";
}

//*****************************************************************************************
//sec:deleteConfirm delete confirm
//*****************************************************************************************
if ($mode == "deleteConfirm") {
    echo "<center>";
    echo "<h3>إلغاء الدبلومة</h3>";
    echo "<div style='text-align: center;'>سيتم إلغاء  $PrgName <br> هل أنت متأكد؟...</div><br>";
    echo "<form method='post' style='max-width:500px;margin:auto;'>";
    echo "<input type='hidden' name='PrgId' value='$PrgId'>"; 
    echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='delete'> نعم </button> <button type='submit' class='cnlBtn' name='mode' value='view'> لا </button></div>";
    echo "</form>";
    echo "</center>";
}

//*****************************************************************************************
//sec:deleteConfirmSubject delete confirm
//*****************************************************************************************
if ($mode == "deleteConfirmSubject") {
    echo "<center>";
    echo "<h3>إلغاء مادة</h3>";
    echo "<div style='text-align: center;'>سيتم حذف  $CrsName من $PrgName <br> هل أنت متأكد؟...</div><br>";
    echo "<form method='post' style='max-width:500px;margin:auto;'>";
    echo "<input type='hidden' name='PrgId' value='$PrgId'>"; 
    echo "<input type='hidden' name='PrgName' value='$PrgName'>"; 	
	echo "<input type='hidden' name='CrsId' value='$CrsId'>";
    echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='deleteSubject'> نعم </button> <button type='submit' class='cnlBtn' name='mode' value='subjectList'> لا </button></div>";
    echo "</form>";
    echo "</center>";
}

//****************************************************************************************
//sec:list the subject record list
//****************************************************************************************
if ($mode == "subjectList") {
    if ($errorMessage != "") {
        echo "<div class='errorMessages'>$errorMessage</div>";
        echo "<br><br>";
    }
    if ($infoMessage != "") {
        echo "<div class='infoMessages'>$infoMessage</div>";
        echo "<br><br>";
    }
	echo "<h3>مواد: $PrgName</h3>";
    //نموذج إضافة مادة جديدة
    echo "<div style='width: 110px; margin: auto;'><form method='post'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
    echo "<button type='submit' class='addBtn' name='mode' value='addSubject'> مادة جديدة</button>";
    echo "</form></div><br>";

    $numberOfButtons = 3;
    $buttonCellWidth = $numberOfButtons * 115;
    $buttonCellWidth .= "px";

   //filter list form 
   echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث...'>";
   echo "<table id='masterTable'><tr class='header'><th> المادة </th><th style='width: $buttonCellWidth'>&nbsp;</th></tr>";
   $q="SELECT `CrsName`,`CrsCode` , `CrsId` FROM `CoursesGuide` WHERE `CrsProgram`=$PrgId ORDER BY `CrsCode`";

   if ($stmt = mysqli_prepare($dbc, $q)){
	   if(mysqli_stmt_execute($stmt)){
		   if(mysqli_stmt_bind_result($stmt, $CrsName, $CrsCode, $CrsId )){
			   while(mysqli_stmt_fetch($stmt)){
				   echo "<tr>";
				   echo "<td> $CrsCode - $CrsName</td>";
				   echo "<td style='width: $buttonCellWidth;'>";
				   echo "<form method='post'>";
				   echo "<input type='hidden' name='CrsId' value='$CrsId'>";
				   echo "<input type='hidden' name='PrgId' value='$PrgId'>";
				   echo "<input type='hidden' name='PrgName' value='$PrgName'>";
				   echo "<button type='submit' class='viewBtn' name='mode' value='viewSubject'>عرض</button> ";
				   echo "<button type='submit' class='edtBtn' name='mode' value='editSubject'>تعديل</button> ";
				   echo "<button type='submit' class='delBtn' name='mode' value='deleteConfirmSubject'>حذف</button> ";
				   echo "</form>";
				   echo "</td>";
				   echo "</tr>";
			   }
		   }
	   }
	   mysqli_stmt_close($stmt);
   }
   echo "</table>";
   echo "<br>";
   echo "<center><button type='button' class='cnlBtn' onclick='window.location=\"$filename\";'> العودة </button></center>";
}

//****************************************************************************************
//sec:list the record list
//****************************************************************************************
if ($mode == "showList") {
    if ($errorMessage != "") {
        echo "<div class='errorMessages'>$errorMessage</div>";
        echo "<br><br>";
    }
    if ($infoMessage != "") {
        echo "<div class='infoMessages'>$infoMessage</div>";
        echo "<br><br>";
    }
    //نموذج إضافة دبلومة جديدة
    echo "<div style='width: 110px; margin: auto;'><form method='post'>";
    echo "<button type='submit' class='addBtn' name='mode' value='add'> دبلومة جديدة</button>";
    echo "</form></div><br>";

    $numberOfButtons = 4;
    $buttonCellWidth = $numberOfButtons * 115;
    $buttonCellWidth .= "px";

   //filter list form 
   echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث...'>";
   echo "<table id='masterTable'><tr class='header'><th> الدبلومة </th><th style='width: $buttonCellWidth'>&nbsp;</th></tr>";
   $q="SELECT PrgId, PrgName FROM Programs ORDER BY PrgName";
  // echo $q."-";
   if ($stmt = mysqli_prepare($dbc, $q)){
	   if(mysqli_stmt_execute($stmt)){
		   if(mysqli_stmt_bind_result($stmt, $PrgId , $PrgName)){
			   while(mysqli_stmt_fetch($stmt)){
				   echo "<tr>";
				   echo "<td> $PrgName </td>";
				   echo "<td style='width: $buttonCellWidth;'>";
				   echo "<form method='post'>";
				   echo "<input type='hidden' name='PrgId' value='$PrgId'>";
				   echo "<button type='submit' class='viewBtn' name='mode' value='view'>بيانات الدبلومة</button> ";
				   echo "<button type='submit' class='grpBtn' name='mode' value='subjectList'>المواد</button> ";
				   echo "<button type='submit' class='edtBtn' name='mode' value='setupSemester'>الفصول الدراسية</button> ";
				   echo "</form>";
				   echo "</td>";
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
