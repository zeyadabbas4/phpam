<?php
//uncomment those two lines for debugging
//ini_set('display_errors',1); 
//error_reporting(E_ALL);
session_start();
$__systemRoot="../";
include($__systemRoot.'functions.php');        //include system functions
include('appdb.php');             			   //include app database connection
include('functions.php');         		   //uncomment this line if you have a local functions file
if(isset($_SESSION['__uid'])){        		   //check for session and set the sesOk variable
    $__uid=$_SESSION['__uid'];
    $sesOk=true;
}else{
    $sesOk=false;
}
$gdir="rtl";
$__dir=$gdir;
$galign="right";
$title="الدبلومات";
$subtitle="";
?>
<!DOCTYPE html>
<html dir="<?php echo $gdir; ?>">
    <head>
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
		<script type="text/javascript">     
			function PrintDiv() {    
				var divToPrint = document.getElementById('divToPrint');
				var popupWin = window.open('', '_blank', 'width=800,height=600');
				popupWin.document.open();
				popupWin.document.write('<html><body class="body" onload="window.print()">' + divToPrint.innerHTML + '</html>');
				popupWin.document.close();
            }
		</script>
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
    		#masterTable, .masterTable {
				border-collapse: collapse;
				width: 100%;
				border: 1px solid #ddd;
				font-size: 18px;
			}
    		#masterTable th, #masterTable td, .masterTable th, .masterTable td {
				text-align: <?php echo $galign; ?>;
				padding: 12px;
			}
    		#masterTable tr, .masterTable tr {
				border-bottom: 1px solid #ddd;
			}
    		#masterTable tr.header, #masterTable tr:hover, .masterTable tr.header, .masterTable tr:hover {
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
    		.printBtn {
				background-color: #1a73e8;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.printBtn:hover {
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
				width: 30%;
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
//echo $mode;

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
	$perms=getUserPermissions($__uid);
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
	//read ref data
if(($mode == "studentAdmission") or ($mode == "addStudent2Batch") or ($mode == "addSaveStudent") or ($mode=="editSaveٍStudent") or ($mode == "removeStudentFromBatch") or ($mode == "doRemoveStudentFromBatch") or ($mode=="batchList") or ($mode=="importStudents") or ($mode=="processImportStudents") or ($mode == "transferStudent") or ($mode == "searchTransferStudent") or ($mode == "doTransferStudent") or ($mode == "doDeleteSemester") or ($mode == "doRegisterStudentSemester") or ($mode == "selectCourseToRegister") or ($mode == "registerCourseStudents") or ($mode == "doRegisterCourseStudents")){
	if(isset($PrgId)){
		$_SESSION['PrgId'] = $PrgId;
		if(isset($PrgName)){
			$_SESSION['PrgName'] = $PrgName;
		}
		if(isset($YearId)){
			$_SESSION['YearId'] = $YearId;
		}
		if(isset($YearDesc)){
			$_SESSION['YearDesc'] = $YearDesc;
		}
	}else{
		if(isset($_SESSION['PrgId'])){
			$PrgId = $_SESSION['PrgId'];
		}
		if(isset($_SESSION['PrgName'])){
			$PrgName = $_SESSION['PrgName'];
		}
		if(isset($_SESSION['YearId'])){
			$YearId = $_SESSION['YearId'];
		}
		if(isset($_SESSION['YearDesc'])){
			$YearDesc = $_SESSION['YearDesc'];
		}
	}
}
//*****************************************************************************************
//Retrive Diploma Information
//*****************************************************************************************
$PrgName = $PrgName ?? '';
$YearDesc = $YearDesc ?? '';

if($mode=="edit" or $mode=="view" or $mode=="deleteConfirm" or $mode=="batchList" or $mode=="viewBatch" or $mode =="addBatch" or $mode == "editBatch" or $mode=="deleteConfirmBatch" or $mode=="studentAdmission" or $mode == "studentOrder" or $mode == "studentOrderEnglish" or $mode == "addSemester" or $mode == "editSemester" or $mode == "ManageSemester" or $mode == "saveaddSemester" or $mode == "saveeditSemester" or $mode == "deleteSemester" or $mode=="registerStudentSemester" or $mode=="unregisterStudentSemester" or  $mode=="registeredCourses" or $mode == "courseScoreEntry" or $mode == "savecourseScoreEntry" or $mode=="showtranscript" or $mode == "PrintCourseScore" or $mode=="addStudentCourse" or $mode=="saveStudentCourse" or $mode=="removeStudentCourse" or $mode=="saveRemoveStudentCourse" or $mode=="approvalGrades" or $mode=="complaints" or $mode=="registerComplaint" or $mode=="saveRegisterComplaint" or $mode=="replyComplaint" or $mode=="saveReplyComplaint" or $mode=="complaintReports" or $mode=="batchReports" or $mode=="importStudents" or $mode=="processImportStudents" or $mode=="doRemoveStudentFromBatch" or $mode == "transferStudent" or $mode == "searchTransferStudent" or $mode == "doTransferStudent" or $mode == "doDeleteSemester" or $mode == "doRegisterStudentSemester" or $mode == "selectCourseToRegister" or $mode == "registerCourseStudents" or $mode == "doRegisterCourseStudents"){
    $q="SELECT PrgCode,PrgName,PrgId  FROM Programs  WHERE PrgId =?";
    if($stmt=mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $PrgId )){
            if(mysqli_stmt_execute($stmt)){
                if(mysqli_stmt_bind_result($stmt, $PrgCode,$PrgName,$PrgId)){
                    if(!mysqli_stmt_fetch($stmt)){
                        $errorMessage="1 خطأ في البيانات!...";
                        $mode="showList";
                    }
                }
            }    
        }
        mysqli_stmt_close($stmt);
    }
}
//*****************************************************************************************
//sec:edit-view-deleteConfirm read a record for view or edit Batch
//*****************************************************************************************
if($mode=="viewBatch" or $mode == "editBatch" or $mode=="deleteConfirmBatch" or $mode=="ManageSemester" or $mode == "addSemester" or $mode == "editSemester" or $mode == "saveaddSemester" or $mode == "saveeditSemester" or $mode == "deleteSemester" or $mode=="registerStudentSemester"  or $mode=="unregisterStudentSemester" or  $mode=="registeredCourses" or $mode == "courseScoreEntry" or $mode == "savecourseScoreEntry" or $mode=="showtranscript" or $mode == "PrintCourseScore" or $mode=="studentAdmission" or $mode == "studentOrder" or $mode == "studentOrderEnglish" or $mode=="addStudentCourse" or $mode=="saveStudentCourse" or $mode=="removeStudentCourse" or $mode=="saveRemoveStudentCourse" or $mode=="approvalGrades" or $mode=="complaints" or $mode=="registerComplaint" or $mode=="saveRegisterComplaint" or $mode=="replyComplaint" or $mode=="saveReplyComplaint" or $mode=="complaintReports" or $mode=="batchReports" or $mode=="importStudents" or $mode=="processImportStudents" or $mode=="doRemoveStudentFromBatch" or $mode == "transferStudent" or $mode == "searchTransferStudent" or $mode == "doTransferStudent" or $mode == "doDeleteSemester" or $mode == "doRegisterStudentSemester" or $mode == "selectCourseToRegister" or $mode == "registerCourseStudents" or $mode == "doRegisterCourseStudents"){
    $q="SELECT `YearDesc`,`YearStart`,`YearEnd` FROM `Years` WHERE `YearId`=?";
    if($stmt=mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $YearId)){
            if(mysqli_stmt_execute($stmt)){
                if(mysqli_stmt_bind_result($stmt, $YearDesc,$YearStart,$YearEnd)){
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
//Retrive student data for update and view
//*****************************************************************************************
if($mode=="editStudent" or $mode=="viewStudent"){
  $q="SELECT StName, StEname, StAddress, StTels, StWhatsApp, StCompany, StNationalID, StBDate, StNationality, StMaritimePassportNo FROM Students WHERE StId=?";
  if ($stmt = mysqli_prepare($dbc, $q)) {
    if(mysqli_stmt_bind_param($stmt,"i", $StId)){
      if(mysqli_stmt_execute($stmt)){
        if(mysqli_stmt_bind_result($stmt,$StName, $StEname, $StAddress, $StTels, $StWhatsApp, $StCompany, $StNationalID, $StBDate, $StNationality, $StMaritimePassportNo)){
          if(!mysqli_stmt_fetch($stmt)){
            $errorMessage="Can not read record";
          }
        }
      }
    }
    mysqli_stmt_close($stmt);
  }
}

//*****************************************************************************************
//ِAdd & Edit Form
//*****************************************************************************************
if($mode=="addStudent" or $mode=="editStudent"){
 
      if($mode=="addStudent"){
        $formLabel="طالب جديد";
      }else{
        $formLabel="تعديل بيانات طالب";
      }
      if(isset($errorMessage)){
        echo "<div class='error'><p>$errorMessage</p></div>";
      }
      echo "<form method='post' style='max-width:800px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>$formLabel</h2>";
      if(isset($PrgId)) echo "<input type='hidden' name='PrgId' value='$PrgId'>";
      if(isset($YearId)) echo "<input type='hidden' name='YearId' value='$YearId'>";
      if(isset($PrgName)) echo "<input type='hidden' name='PrgName' value='$PrgName'>";
      if(isset($YearDesc)) echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
      echo "<table width='100%'>";
      if($mode=="editStudent"){
        echo "<tr><td style='width: 225px;'>رقم الطالب :</td><td><div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='رقم المتدرب' name='StId' readonly";
        if(isset($StId)){
          echo " value='$StId'";
        } 
        echo "></div></td></tr>";    
      }

      echo "<tr><td style='width: 225px;'>اسم المتدريب (عريي):</td><td><div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='اسم المتدريب (عريي)' name='StName'";
      if(isset($StName)){
        echo " value='$StName'";
      } 
      echo "></div></td></tr>";  

      echo "<tr><td style='width: 225px;'>اسم المتدريب (إنجليزي):</td><td><div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='اسم المتدريب (انجليزي)' name='StEname'";
      if(isset($StEname)){
        echo " value='$StEname'";
      } 
      echo "></div></td></tr>";  

      echo "<tr><td style='width: 225px;'>رقم البطاقة:</td><td><div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='رقم البطاقة' name='StNationalID'";
      if(isset($StNationalID)){
        echo " value='$StNationalID'";
      } 
      echo "></div></td></tr>";  

      echo "<tr><td style='width: 225px;'>رقم جواز السفر:</td><td><div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='رقم جواز السفر' name='StMaritimePassportNo'";
      if(isset($StMaritimePassportNo)){
        echo " value='$StMaritimePassportNo'";
      } 
      echo "></div></td></tr>";  

      echo "<tr><td style='width: 225px;'>العنوان:</td><td><div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='العنوان' name='StAddress'";
      if(isset($StAddress)){
        echo " value='$StAddress'";
      } 
      echo "></div></td></tr>";  

      echo "<tr><td style='width: 225px;'>أرقام التليفون:</td><td><div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='أرقام التليفون' name='StTels'";
      if(isset($StTels)){
        echo " value='$StTels'";
      } 
      echo "></div></td></tr>";  

      echo "<tr><td style='width: 225px;'>رقم واتساب:</td><td><div class='input-container'>";
      echo "<input class='input-field' type='text' placeholder='رقم واتساب' name='StWhatsApp'";
      if(isset($StWhatsApp)){
        echo " value='$StWhatsApp'";
      } 
      echo "></div></td></tr>";  

      $comps=readCompanies($dbc);
      echo "<tr><td style='width: 225px;'>الشركة:</td><td><div class='input-container'>";
      echo "<select class='input-field' name='StCompany'><option value='-1'>حدد الشركة</option>";
      foreach($comps as $key => $value){
        echo "<option value='$key'";
        if(isset($StCompany)){
          if($key == $StCompany){
            echo " selected";
          }
        }
        echo ">$value</option>";
      }
      echo "</select></div></td></tr>";  

      echo "<tr><td style='width: 225px;'>تاريخ الميلاد:</td><td><div class='input-container'>";
      echo "<input class='input-field' type='date' placeholder='تاريخ الميلاد' name='StBDate'";
      if(isset($StBDate)){
        echo " value='$StBDate'";
      } 
      echo "></div></td></tr>";  
      
      $Countries=readCoutries($dbc);
      echo "<tr><td style='width: 225px;'>الجنسية:</td><td><div class='input-container'>";
      echo "<select class='input-field' name='StNationality'><option value='-1'>الجنسية</option>";
      foreach($Countries as $key => $value){
        echo "<option value='$key'";
        if(isset($StNationality)){
          if($key == $StNationality){
            echo " selected";
          }
        }
        echo ">$value</option>";
      }
      echo "</select></div></td></tr>";  

      if($mode=="addStudent"){
		$newMode="addSaveStudent";
      }else{
        $newMode="editSaveٍStudent";
      }
      echo "</table>";

      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'> حفظ </button>  <button type='submit' class='cnlBtn' name='mode' value='studentAdmission'> تراجع </button></div>";
      echo "</form>";
}
//*****************************************************************************************
//Add save (new record)
//*****************************************************************************************
if($mode=="addSaveStudent"){
      //save new record
      $q="INSERT INTO Students (`StName`, `StEname`, `StAddress`, `StTels`, `StWhatsApp`, `StCompany`, `StNationalID`, `StBDate`, `StNationality`, `StMaritimePassportNo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt,"sssssissis", $StName, $StEname, $StAddress, $StTels, $StWhatsApp, $StCompany, $StNationalID, $StBDate,  $StNationality, $StMaritimePassportNo)){
          if(!mysqli_stmt_execute($stmt)){
            $errorMessage="Can not insert record: " . mysqli_error($dbc);
          } else {
            $newStId = mysqli_insert_id($dbc);
            if(isset($PrgId) && isset($YearId) && $PrgId != "" && $YearId != "") {
                $RegistrationNumber = "";
                if(isset($YearDesc)) {
                    $RegistrationNumber = substr($YearDesc, 2) . str_pad($newStId, 3, '0', STR_PAD_LEFT);
                }
                $q_enroll = "INSERT INTO ProgramStudents (`StId`, `PrgId`, `YearId`, `RegistrationNumber`, `FINAL_GPA`) VALUES (?, ?, ?, ?, 0)";
                if ($stmt_enroll = mysqli_prepare($dbc, $q_enroll)) {
                    mysqli_stmt_bind_param($stmt_enroll, "iiis", $newStId, $PrgId, $YearId, $RegistrationNumber);
                    if (!mysqli_stmt_execute($stmt_enroll)) {
                        $errorMessage = "تم إنشاء الطالب لكن فشل تسجيله بالدفعة: " . mysqli_error($dbc);
                    }
                    mysqli_stmt_close($stmt_enroll);
                }
            }
          }
        }
        mysqli_stmt_close($stmt);
      }
	  $mode="studentAdmission";
    }
//*****************************************************************************************
//Edit Save (save edited record)
//*****************************************************************************************
 if($mode=="editSaveٍStudent"){
      //save edited value
      $q="UPDATE Students SET StName=?, StEname=?, StAddress=?, StTels=?, StWhatsApp=?, StCompany=?, StNationalID=?, StBDate=?, StNationality=?, StMaritimePassportNo=? WHERE StId=?";
      if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt,"sssssissisi", $StName, $StEname, $StAddress, $StTels, $StWhatsApp, $StCompany, $StNationalID, $StBDate,  $StNationality, $StMaritimePassportNo,$StId)){
          if(!mysqli_stmt_execute($stmt)){
            $errorMessage="Can not Update record";
          }
        }
        mysqli_stmt_close($stmt);
      }
		$mode="studentAdmission";
    }

//*****************************************************************************************
//sec:Add a specific student to a batch
//*****************************************************************************************

if ($mode == "addStudent2Batch"){
	
//	echo "Program ID =$PrgId - Program Name= $PrgName - Year ID = $YearId - Year Description =$YearDesc - Student ID = $StId<br>";
	
	
$q="INSERT INTO ProgramStudents (`StId`, `PrgId`, `YearId`, `RegistrationNumber`, `FINAL_GPA`) VALUES (?, ?, ?, ?, 0)";
	  
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt,"iiis", $StId, $PrgId, $YearId,$RegistrationNumber)){

			if(!mysqli_stmt_execute($stmt)){
				$errorMessage="Can not insert record: " . mysqli_error($dbc);
			}
        }
        mysqli_stmt_close($stmt);
      }
	  $mode = "studentAdmission";	
	
}



//*****************************************************************************************
//sec:Un Register a specific student
//*****************************************************************************************
//*****************************************************************************************
//sec:confirmRemoveStudentFromBatch
//*****************************************************************************************
if ($mode == "removeStudentFromBatch"){
	$StName = "";
	$q_st = "SELECT StName FROM Students WHERE StID = ?";
	if ($stmt_st = mysqli_prepare($dbc, $q_st)) {
		mysqli_stmt_bind_param($stmt_st, "i", $StId);
		if (mysqli_stmt_execute($stmt_st)) {
			mysqli_stmt_bind_result($stmt_st, $temp_StName);
			if (mysqli_stmt_fetch($stmt_st)) {
				$StName = $temp_StName;
			}
		}
		mysqli_stmt_close($stmt_st);
	}
	
	echo "<center>";
	echo "<h3>إلغاء تسجيل طالب من الدفعة</h3>";
	echo "<div style='text-align: center;'>هل أنت متأكد من إلغاء تسجيل الطالب <strong>$StName</strong> من هذه الدفعة؟<br>";
	echo "<span style='color:red;'>تنبيه: سيتم حذف جميع تسجيلات المواد والدرجات الخاصة بهذا الطالب في هذه الدفعة أيضاً!</span></div><br>";
	echo "<form method='post' style='max-width:500px;margin:auto;'>";
	echo "<input type='hidden' name='StId' value='$StId'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	if(isset($PrgName)){
		echo "<input type='hidden' name='PrgName' value='$PrgName'>";
	}
	if(isset($YearDesc)){
		echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
	}
	echo "<div class='frmButtons'>";
	echo "<button type='submit' class='savBtn' name='mode' value='doRemoveStudentFromBatch'> نعم </button> ";
	echo "<button type='submit' class='cnlBtn' name='mode' value='studentAdmission'> لا </button>";
	echo "</div>";
	echo "</form>";
	echo "</center>";
}

//*****************************************************************************************
//sec:doRemoveStudentFromBatch
//*****************************************************************************************
if ($mode == "doRemoveStudentFromBatch") {
    $error = false;
    
    // First, delete from programstudentscourses
    $q1 = "DELETE FROM programstudentscourses WHERE StId=? AND PrgId=? AND YearId=?";
    if ($stmt1 = mysqli_prepare($dbc, $q1)) {
        if (mysqli_stmt_bind_param($stmt1, "iii", $StId, $PrgId, $YearId)) {
            if (!mysqli_stmt_execute($stmt1)) {
                $error = true;
                $errorMessage = "تعذر حذف تسجيلات المواد والدرجات: " . mysqli_error($dbc);
            }
        } else {
            $error = true;
            $errorMessage = "تعذر ربط معلمات حذف المواد والدرجات.";
        }
        mysqli_stmt_close($stmt1);
    } else {
        $error = true;
        $errorMessage = "تعذر إعداد استعلام حذف المواد والدرجات: " . mysqli_error($dbc);
    }
    
    // Then, delete from ProgramStudents
    if (!$error) {
        $q2 = "DELETE FROM ProgramStudents WHERE StId=? AND PrgId=? AND YearId=?";
        if ($stmt2 = mysqli_prepare($dbc, $q2)) {
            if (mysqli_stmt_bind_param($stmt2, "iii", $StId, $PrgId, $YearId)) {
                if (!mysqli_stmt_execute($stmt2)) {
                    $errorMessage = "تعذر حذف الطالب من الدفعة: " . mysqli_error($dbc);
                }
            } else {
                $errorMessage = "تعذر ربط معلمات حذف الطالب.";
            }
            mysqli_stmt_close($stmt2);
        } else {
            $errorMessage = "تعذر إعداد استعلام حذف الطالب: " . mysqli_error($dbc);
        }
    }
    
    $mode = "studentAdmission";
}

if ($mode == "transferStudent") {
    echo "<center>";
    echo "<h3>تحويل طالب من دفعة أخرى</h3>";
    echo "<h3>الدفعة المستهدفة: $PrgName - $YearDesc</h3>";
    if (isset($errorMessage) && $errorMessage != "") {
        echo "<div class='errorMessages'>$errorMessage</div><br>";
    }
    echo "<form method='post' style='max-width:500px;margin:auto;'>";
    echo "<input type='hidden' name='PrgId' value='$PrgId'>";
    echo "<input type='hidden' name='YearId' value='$YearId'>";
    echo "<input type='hidden' name='PrgName' value='$PrgName'>";
    echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
    echo "<table width='100%'>";
    echo "<tr><td style='width: 150px;'>رقم تسجيل الطالب:</td><td><div class='input-container'>";
    echo "<input class='input-field' type='text' placeholder='رقم التسجيل' name='SearchRegNum' value='" . (isset($SearchRegNum) ? htmlspecialchars($SearchRegNum) : "") . "' style='width:100%' required>";
    echo "</div></td></tr>";
    echo "</table>";
    echo "<div class='frmButtons'>";
    echo "<button type='submit' class='savBtn' name='mode' value='transferStudent'> بحث </button> ";
    echo "<button type='submit' class='cnlBtn' name='mode' value='studentAdmission'> تراجع </button>";
    echo "</div>";
    echo "</form>";

    if (isset($SearchRegNum) && trim($SearchRegNum) != "") {
        $SearchRegNum = trim($SearchRegNum);
        $found = false;
        $multipleMatches = false;
        $results = [];

        $q_search = "SELECT ps.StId, ps.PrgId, ps.YearId, ps.RegistrationNumber, s.StName, s.StNationalID, y.YearDesc FROM ProgramStudents ps INNER JOIN Students s ON ps.StId = s.StID INNER JOIN Years y ON ps.YearId = y.YearId WHERE ps.RegistrationNumber = ?";
        if ($stmt_search = mysqli_prepare($dbc, $q_search)) {
            mysqli_stmt_bind_param($stmt_search, "s", $SearchRegNum);
            if (mysqli_stmt_execute($stmt_search)) {
                mysqli_stmt_store_result($stmt_search);
                if (mysqli_stmt_num_rows($stmt_search) > 1) {
                    $multipleMatches = true;
                }
                if (mysqli_stmt_num_rows($stmt_search) > 0) {
                    mysqli_stmt_bind_result($stmt_search, $foundStId, $foundPrgId, $foundYearId, $foundRegNum, $foundStName, $foundStNationalID, $foundYearDesc);
                    while (mysqli_stmt_fetch($stmt_search)) {
                        $results[] = [
                            'StId' => $foundStId,
                            'PrgId' => $foundPrgId,
                            'YearId' => $foundYearId,
                            'RegistrationNumber' => $foundRegNum,
                            'StName' => $foundStName,
                            'StNationalID' => $foundStNationalID,
                            'YearDesc' => $foundYearDesc
                        ];
                    }
                    $found = true;
                }
            }
            mysqli_stmt_close($stmt_search);
        }

        echo "<br><hr style='width: 80%;'><br>";

        if (!$found) {
            echo "<div class='errorMessages'>لم يتم العثور على طالب بهذا الرقم ($SearchRegNum)</div><br>";
        } elseif ($multipleMatches) {
            echo "<div class='errorMessages'>تعذر إتمام العملية: تم العثور على أكثر من طالب مسجل بنفس رقم التسجيل ($SearchRegNum). يرجى مراجعة قاعدة البيانات.</div><br>";
        } else {
            $student = $results[0];
            $canTransfer = true;
            $errorMsg = "";

            if ($student['PrgId'] != $PrgId) {
                $canTransfer = false;
                $errorMsg = "لا يمكن النقل بين دبلومات مختلفة";
            } elseif ($student['YearId'] == $YearId) {
                $canTransfer = false;
                $errorMsg = "الطالب مسجل بالفعل في هذه الدفعة";
            }

            echo "<table class='masterTable' style='max-width: 600px; margin: auto;'>";
            echo "<tr><td>اسم الطالب</td><td>" . htmlspecialchars($student['StName']) . "</td></tr>";
            echo "<tr><td>رقم البطاقة</td><td>" . htmlspecialchars($student['StNationalID']) . "</td></tr>";
            echo "<tr><td>الدفعة الحالية</td><td>" . htmlspecialchars($student['YearDesc']) . "</td></tr>";
            echo "<tr><td>رقم التسجيل</td><td>" . htmlspecialchars($student['RegistrationNumber']) . "</td></tr>";
            echo "</table><br>";

            if (!$canTransfer) {
                echo "<div class='errorMessages'>$errorMsg</div><br>";
            } else {
                echo "<form method='post' style='max-width:500px;margin:auto;'>";
                echo "<input type='hidden' name='StId' value='{$student['StId']}'>";
                echo "<input type='hidden' name='PrgId' value='$PrgId'>";
                echo "<input type='hidden' name='OldYearId' value='{$student['YearId']}'>";
                echo "<input type='hidden' name='YearId' value='$YearId'>";
                echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
                echo "<input type='hidden' name='PrgName' value='$PrgName'>";
                echo "<div class='frmButtons' style='width: auto; display: flex; justify-content: center; gap: 10px;'>";
                echo "<button type='submit' class='savBtn' name='mode' value='doTransferStudent' style='width: auto; padding: 10px 15px;'>تحويل من {$student['YearDesc']} الى $YearDesc</button> ";
                echo "<button type='submit' class='cnlBtn' name='mode' value='transferStudent'> إلغاء </button>";
                echo "</div>";
                echo "</form>";
            }
        }
    }
    echo "</center>";
}

//*****************************************************************************************
//sec:doTransferStudent
//*****************************************************************************************
if ($mode == "doTransferStudent") {
    $error = false;
    $skippedCourses = [];
    
    // Start transaction
    mysqli_begin_transaction($dbc);

    // Fetch Old Year Courses
    $oldCourses = [];
    $q_old = "SELECT CrsId FROM programstudentscourses WHERE StId=? AND PrgId=? AND YearId=?";
    if ($stmt_old = mysqli_prepare($dbc, $q_old)) {
        mysqli_stmt_bind_param($stmt_old, "iii", $StId, $PrgId, $OldYearId);
        if (mysqli_stmt_execute($stmt_old)) {
            mysqli_stmt_bind_result($stmt_old, $cId);
            while (mysqli_stmt_fetch($stmt_old)) {
                $oldCourses[] = $cId;
            }
        } else {
            $error = true;
            $errorMessage = "خطأ في قراءة مواد الطالب القديمة: " . mysqli_error($dbc);
        }
        mysqli_stmt_close($stmt_old);
    } else {
        $error = true;
        $errorMessage = "فشل في إعداد استعلام قراءة المواد القديمة.";
    }

    // Fetch New Year Courses (conflicting list)
    $newCourses = [];
    if (!$error) {
        $q_new = "SELECT CrsId FROM programstudentscourses WHERE StId=? AND PrgId=? AND YearId=?";
        if ($stmt_new = mysqli_prepare($dbc, $q_new)) {
            mysqli_stmt_bind_param($stmt_new, "iii", $StId, $PrgId, $YearId);
            if (mysqli_stmt_execute($stmt_new)) {
                mysqli_stmt_bind_result($stmt_new, $cId);
                while (mysqli_stmt_fetch($stmt_new)) {
                    $newCourses[] = $cId;
                }
            } else {
                $error = true;
                $errorMessage = "خطأ في قراءة مواد الطالب في الدفعة الجديدة: " . mysqli_error($dbc);
            }
            mysqli_stmt_close($stmt_new);
        } else {
            $error = true;
            $errorMessage = "فشل في إعداد استعلام قراءة المواد الجديدة.";
        }
    }

    // Process Course Transfers
    if (!$error) {
        foreach ($oldCourses as $crsId) {
            if (in_array($crsId, $newCourses)) {
                // Conflict: Fetch course code/name to display in skipped courses message
                $crsCode = "";
                $q_info = "SELECT CrsCode FROM CoursesGuide WHERE CrsId = ?";
                if ($stmt_info = mysqli_prepare($dbc, $q_info)) {
                    mysqli_stmt_bind_param($stmt_info, "i", $crsId);
                    if (mysqli_stmt_execute($stmt_info)) {
                        mysqli_stmt_bind_result($stmt_info, $tempCode);
                        if (mysqli_stmt_fetch($stmt_info)) {
                            $crsCode = $tempCode;
                        }
                    }
                    mysqli_stmt_close($stmt_info);
                }
                $skippedCourses[] = $crsCode ? $crsCode : "ID: $crsId";
            } else {
                // No conflict, safe to update
                $q_update = "UPDATE programstudentscourses SET YearId=? WHERE StId=? AND PrgId=? AND YearId=? AND CrsId=?";
                if ($stmt_up = mysqli_prepare($dbc, $q_update)) {
                    mysqli_stmt_bind_param($stmt_up, "iiiii", $YearId, $StId, $PrgId, $OldYearId, $crsId);
                    if (!mysqli_stmt_execute($stmt_up)) {
                        $error = true;
                        $errorMessage = "خطأ أثناء تحديث مادة ($crsId): " . mysqli_error($dbc);
                        mysqli_stmt_close($stmt_up);
                        break;
                    }
                    mysqli_stmt_close($stmt_up);
                } else {
                    $error = true;
                    $errorMessage = "فشل إعداد استعلام تحديث المواد.";
                    break;
                }
            }
        }
    }

    // Update ProgramStudents
    if (!$error) {
        $q_ps = "UPDATE ProgramStudents SET YearId=? WHERE StId=? AND PrgId=? AND YearId=?";
        if ($stmt_ps = mysqli_prepare($dbc, $q_ps)) {
            mysqli_stmt_bind_param($stmt_ps, "iiii", $YearId, $StId, $PrgId, $OldYearId);
            if (!mysqli_stmt_execute($stmt_ps)) {
                $error = true;
                $errorMessage = "خطأ أثناء تحديث بيانات تسجيل الدفعة للطالب: " . mysqli_error($dbc);
            }
            mysqli_stmt_close($stmt_ps);
        } else {
            $error = true;
            $errorMessage = "فشل إعداد استعلام تحديث تسجيل الدفعة.";
        }
    }

    // Commit or Rollback
    if ($error) {
        mysqli_rollback($dbc);
    } else {
        mysqli_commit($dbc);
        if (count($skippedCourses) == 0) {
            $infoMessage = "تم تحويل الطالب ومواده ودرجاته بنجاح (رقم التسجيل لم يتغير).";
        } else {
            $infoMessage = "تم التحويل، لكن تعذر نقل " . count($skippedCourses) . " مادة بسبب تعارض في التسجيل (" . implode(', ', $skippedCourses) . ") (رقم التسجيل لم يتغير).";
        }
    }

    $mode = "studentAdmission";
}

//*****************************************************************************************
//sec:Save a manually added student course
//*****************************************************************************************
if ($mode == "saveStudentCourse") {
	$errorMessage = "";
	if (empty($StId))   $errorMessage .= "يجب اختيار الطالب<br>";
	if (empty($CrsId))  $errorMessage .= "يجب اختيار المادة<br>";
	if (empty($Semester) || !is_numeric($Semester)) $errorMessage .= "يجب إدخال رقم الفصل الدراسي<br>";

	if ($errorMessage != "") {
		$mode = "addStudentCourse";
	} else {
		$q = "INSERT IGNORE INTO programstudentscourses (StId, PrgId, YearId, CrsId, Semester) VALUES (?, ?, ?, ?, ?)";
		if ($stmt = mysqli_prepare($dbc, $q)) {
			if (mysqli_stmt_bind_param($stmt, "iiiii", $StId, $PrgId, $YearId, $CrsId, $Semester)) {
				if (mysqli_stmt_execute($stmt)) {
					if (mysqli_stmt_affected_rows($stmt) > 0) {
						$infoMessage = "تمت الإضافة بنجاح!";
						// Clear the form fields on success
						$StId = "";
						$CrsId = "";
						$Semester = "";
					} else {
						$errorMessage = "الطالب مسجل في هذه المادة مسبقاً!";
					}
				} else {
					$errorMessage = "لم يتم الحفظ: " . mysqli_error($dbc);
				}
			}
			mysqli_stmt_close($stmt);
		}
		$mode = "addStudentCourse";
	}
}

//*****************************************************************************************
//sec:Save removal of a student's course
//*****************************************************************************************
if ($mode == "saveRemoveStudentCourse") {
	$errorMessage = "";
	if (empty($StId))   $errorMessage .= "يجب اختيار الطالب<br>";
	if (empty($CrsId))  $errorMessage .= "يجب اختيار المادة<br>";

	if ($errorMessage != "") {
		$mode = "removeStudentCourse";
	} else {
		$q = "DELETE FROM programstudentscourses WHERE StId=? AND PrgId=? AND YearId=? AND CrsId=?";
		if ($stmt = mysqli_prepare($dbc, $q)) {
			if (mysqli_stmt_bind_param($stmt, "iiii", $StId, $PrgId, $YearId, $CrsId)) {
				if (mysqli_stmt_execute($stmt)) {
					if (mysqli_stmt_affected_rows($stmt) > 0) {
						$infoMessage = "تم الحذف بنجاح!";
						$StId = "";
						$CrsId = "";
					} else {
						$errorMessage = "الطالب غير مسجل في هذه المادة!";
					}
				} else {
					$errorMessage = "لم يتم الحذف: " . mysqli_error($dbc);
				}
			}
			mysqli_stmt_close($stmt);
		}
		$mode = "removeStudentCourse";
	}
}

//*****************************************************************************************
//sec:Add a course for a specific student manually
//*****************************************************************************************
if ($mode == "addStudentCourse") {
	if(isset($errorMessage) && $errorMessage != ""){
		echo "<div class='errorMessages'>$errorMessage</div><br>";
	}
	if(isset($infoMessage) && $infoMessage != ""){
		if ($infoMessage == "تمت الإضافة بنجاح!") {
			echo "
			<div id='successPopup' style='position:fixed; top:20px; left:50%; transform:translateX(-50%); background-color:#4CAF50; color:white; padding:15px 30px; border-radius:5px; font-size:18px; font-weight:bold; z-index:9999; box-shadow: 0 4px 8px rgba(0,0,0,0.2); direction:rtl; text-align:center;'>
				تمت الإضافة بنجاح!
			</div>
			<script>
				setTimeout(function() {
					var popup = document.getElementById('successPopup');
					if (popup) {
						popup.style.transition = 'opacity 0.3s ease';
						popup.style.opacity = '0';
						setTimeout(function() {
							popup.parentNode.removeChild(popup);
						}, 300);
					}
				}, 1000);
			</script>
			";
		} else {
			echo "<div class='infoMessages'>$infoMessage</div><br>";
		}
	}

	// Get all courses with their designated semesters for the program to use in JavaScript filtering
	$programCourses = array();
	$courseSemesterQ = "SELECT CrsId, Semester FROM ProgramCoursesSemester WHERE PrgId = ?";
	if ($stmt_cs = mysqli_prepare($dbc, $courseSemesterQ)) {
		mysqli_stmt_bind_param($stmt_cs, "i", $PrgId);
		if (mysqli_stmt_execute($stmt_cs)) {
			mysqli_stmt_bind_result($stmt_cs, $cs_CrsId, $cs_Semester);
			while (mysqli_stmt_fetch($stmt_cs)) {
				$programCourses[$cs_CrsId] = $cs_Semester;
			}
		}
		mysqli_stmt_close($stmt_cs);
	}

	echo "<center>";
	echo "<h3>اضافة مادة لطالب - $PrgName - $YearDesc</h3>";
	echo "<form method='post' style='max-width:700px;margin:auto;direction:rtl;'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<input type='hidden' name='PrgName' value='$PrgName'>";
	echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
	echo "<table width='100%'>";

	// Student dropdown - only students enrolled in this batch
	$qq_st = "SELECT Students.StID, Students.StName FROM Students INNER JOIN ProgramStudents ON Students.StID = ProgramStudents.StID WHERE ProgramStudents.PrgId=? AND ProgramStudents.YearId=? ORDER BY Students.StName";
	echo "<tr><td style='width:200px;'>الطالب:</td><td><div class='input-container'>";
	echo "<select class='input-field' name='StId'><option value=''>-- اختر الطالب --</option>";
	if ($stmt_st = mysqli_prepare($dbc, $qq_st)) {
		if (mysqli_stmt_bind_param($stmt_st, "ii", $PrgId, $YearId)) {
			if (mysqli_stmt_execute($stmt_st)) {
				if (mysqli_stmt_bind_result($stmt_st, $st_StId, $st_StName)) {
					while (mysqli_stmt_fetch($stmt_st)) {
						$sel = (isset($StId) && $StId == $st_StId) ? " selected" : "";
						echo "<option value='$st_StId'$sel>$st_StName</option>";
					}
				}
			}
		}
		mysqli_stmt_close($stmt_st);
	}
	echo "</select></div></td></tr>";

	// Semester Dropdown
	echo "<tr><td style='width:200px;'>الفصل الدراسي:</td><td><div class='input-container'>";
	echo "<select class='input-field' name='Semester' id='semesterSelect' onchange='filterCourses()'><option value=''>-- اختر الفصل الدراسي --</option>";
	// Fetch all distinct semesters available for this program in ProgramCoursesSemester 
	$semQ = "SELECT DISTINCT Semester FROM ProgramCoursesSemester WHERE PrgId = ? ORDER BY Semester";
	if ($stmt_sem = mysqli_prepare($dbc, $semQ)) {
		mysqli_stmt_bind_param($stmt_sem, "i", $PrgId);
		if (mysqli_stmt_execute($stmt_sem)) {
			mysqli_stmt_bind_result($stmt_sem, $semVal);
			while (mysqli_stmt_fetch($stmt_sem)) {
				$sel = (isset($Semester) && $Semester == $semVal) ? " selected" : "";
				echo "<option value='$semVal'$sel>فصل دراسي $semVal</option>";
			}
		}
		mysqli_stmt_close($stmt_sem);
	}
	echo "</select></div></td></tr>";

	// Course dropdown - only courses belonging to this program
	$qq_cr = "SELECT CrsId, CrsCode, CrsName FROM CoursesGuide WHERE CrsProgram=? ORDER BY CrsCode";
	echo "<tr><td style='width:200px;'>المادة:</td><td><div class='input-container'>";
	echo "<select class='input-field' name='CrsId' id='courseSelect'><option value=''>-- اختر المادة --</option>";
	if ($stmt_cr = mysqli_prepare($dbc, $qq_cr)) {
		if (mysqli_stmt_bind_param($stmt_cr, "i", $PrgId)) {
			if (mysqli_stmt_execute($stmt_cr)) {
				if (mysqli_stmt_bind_result($stmt_cr, $cr_CrsId, $cr_CrsCode, $cr_CrsName)) {
					while (mysqli_stmt_fetch($stmt_cr)) {
						$courseSem = isset($programCourses[$cr_CrsId]) ? $programCourses[$cr_CrsId] : '0';
						$sel = (isset($CrsId) && $CrsId == $cr_CrsId) ? " selected" : "";
						echo "<option value='$cr_CrsId' data-semester='$courseSem'$sel>$cr_CrsName ($cr_CrsCode)</option>";
					}
				}
			}
		}
		mysqli_stmt_close($stmt_cr);
	}
	echo "</select></div></td></tr>";

	echo "</table>";
	echo "<div class='frmButtons'>";
	echo "<button type='submit' class='savBtn' name='mode' value='saveStudentCourse'> حفظ </button>  ";
	echo "<button type='submit' class='cnlBtn' name='mode' value='batchList'> تراجع </button>";
	echo "</div></form></center>";

	// JavaScript for dynamic filtering
	?>
	<script>
		function filterCourses() {
			var semesterSelect = document.getElementById('semesterSelect');
			var courseSelect = document.getElementById('courseSelect');
			var selectedSemester = semesterSelect.value;
			
			// Show all option if no semester selected, else filter
			for (var i = 0; i < courseSelect.options.length; i++) {
				var option = courseSelect.options[i];
				if (option.value === "") {
					option.style.display = "";
					continue;
				}
				var courseSemester = option.getAttribute('data-semester');
				if (selectedSemester === "" || courseSemester === selectedSemester) {
					option.style.display = "";
				} else {
					option.style.display = "none";
				}
			}
			
			// Reset course select value if currently selected option becomes hidden
			if (courseSelect.selectedIndex >= 0) {
				var selectedOption = courseSelect.options[courseSelect.selectedIndex];
				if (selectedOption.style.display === "none") {
					courseSelect.value = "";
				}
			}
		}
		// Run once on page load to apply correct initial filter state
		document.addEventListener('DOMContentLoaded', function() {
			filterCourses();
		});
	</script>
	<?php
}

//*****************************************************************************************
//sec:Remove a course from a specific student
//*****************************************************************************************
if ($mode == "removeStudentCourse") {
	if(isset($errorMessage) && $errorMessage != ""){
		echo "<div class='errorMessages'>$errorMessage</div><br>";
	}
	if(isset($infoMessage) && $infoMessage != ""){
		if ($infoMessage == "تم الحذف بنجاح!") {
			echo "
			<div id='successPopup' style='position:fixed; top:20px; left:50%; transform:translateX(-50%); background-color:#4CAF50; color:white; padding:15px 30px; border-radius:5px; font-size:18px; font-weight:bold; z-index:9999; box-shadow: 0 4px 8px rgba(0,0,0,0.2); direction:rtl; text-align:center;'>
				تم الحذف بنجاح!
			</div>
			<script>
				setTimeout(function() {
					var popup = document.getElementById('successPopup');
					if (popup) {
						popup.style.transition = 'opacity 0.3s ease';
						popup.style.opacity = '0';
						setTimeout(function() {
							popup.parentNode.removeChild(popup);
						}, 300);
					}
				}, 1000);
			</script>
			";
		} else {
			echo "<div class='infoMessages'>$infoMessage</div><br>";
		}
	}

	echo "<center>";
	echo "<h3>الغاء مادة لطالب - $PrgName - $YearDesc</h3>";
	echo "<form method='post' style='max-width:700px;margin:auto;direction:rtl;'>";
	echo "<input type='hidden' name='mode' value='removeStudentCourse'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<input type='hidden' name='PrgName' value='$PrgName'>";
	echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
	echo "<table width='100%'>";

	// Student dropdown - only students enrolled in this batch
	$qq_st = "SELECT Students.StID, Students.StName FROM Students INNER JOIN ProgramStudents ON Students.StID = ProgramStudents.StID WHERE ProgramStudents.PrgId=? AND ProgramStudents.YearId=? ORDER BY Students.StName";
	echo "<tr><td style='width:200px;'>الطالب:</td><td><div class='input-container'>";
	echo "<select class='input-field' name='StId' onchange='this.form.submit()'><option value=''>-- اختر الطالب --</option>";
	if ($stmt_st = mysqli_prepare($dbc, $qq_st)) {
		if (mysqli_stmt_bind_param($stmt_st, "ii", $PrgId, $YearId)) {
			if (mysqli_stmt_execute($stmt_st)) {
				if (mysqli_stmt_bind_result($stmt_st, $st_StId, $st_StName)) {
					while (mysqli_stmt_fetch($stmt_st)) {
						$sel = (isset($StId) && $StId == $st_StId) ? " selected" : "";
						echo "<option value='$st_StId'$sel>$st_StName</option>";
					}
				}
			}
		}
		mysqli_stmt_close($stmt_st);
	}
	echo "</select></div></td></tr>";

	// Course dropdown - courses belonging to the selected student
	echo "<tr><td style='width:200px;'>المادة:</td><td><div class='input-container'>";
	echo "<select class='input-field' name='CrsId'><option value=''>-- اختر المادة --</option>";
	if (isset($StId) && $StId != "") {
		$qq_cr = "SELECT programstudentscourses.CrsId, CoursesGuide.CrsCode, CoursesGuide.CrsName FROM programstudentscourses INNER JOIN CoursesGuide ON programstudentscourses.CrsId = CoursesGuide.CrsId WHERE programstudentscourses.PrgId=? AND programstudentscourses.YearId=? AND programstudentscourses.StId=? ORDER BY CoursesGuide.CrsCode";
		if ($stmt_cr = mysqli_prepare($dbc, $qq_cr)) {
			if (mysqli_stmt_bind_param($stmt_cr, "iii", $PrgId, $YearId, $StId)) {
				if (mysqli_stmt_execute($stmt_cr)) {
					if (mysqli_stmt_bind_result($stmt_cr, $cr_CrsId, $cr_CrsCode, $cr_CrsName)) {
						while (mysqli_stmt_fetch($stmt_cr)) {
							$sel = (isset($CrsId) && $CrsId == $cr_CrsId) ? " selected" : "";
							echo "<option value='$cr_CrsId'$sel>$cr_CrsName</option>";
						}
					}
				}
			}
			mysqli_stmt_close($stmt_cr);
		}
	} else {
		echo "<option value='' disabled>-- اختر الطالب أولاً --</option>";
	}
	echo "</select></div></td></tr>";

	echo "</table>";
	echo "<div class='frmButtons'>";
	echo "<button type='submit' class='delBtn' name='mode' value='saveRemoveStudentCourse'> حذف </button>  ";
	echo "<button type='submit' class='cnlBtn' name='mode' value='batchList'> تراجع </button>";
      echo "</div></form></center>";
}



//*****************************************************************************************
//sec:Manage Semester for a specific batch
//*****************************************************************************************
if ($mode == "showtranscript") {

	// Retrieve which semesters for this batch/program are approved by the Dean (Query executed first to avoid out-of-sync error)
	$approvedSemesters = array();
	$appQ = "SELECT `Semester`, `IsApproved` FROM `ProgramYearSemester` WHERE `YearId` = ? AND `PrgId` = ?";
	if ($appStmt = mysqli_prepare($dbc, $appQ)) {
		mysqli_stmt_bind_param($appStmt, "ii", $YearId, $PrgId);
		if (mysqli_stmt_execute($appStmt)) {
			mysqli_stmt_bind_result($appStmt, $tempSem, $tempApp);
			while (mysqli_stmt_fetch($appStmt)) {
				$approvedSemesters[$tempSem] = $tempApp;
			}
		}
		mysqli_stmt_close($appStmt);
	}

	$qq = "SELECT Students.StName, Students.StEname, Students.StBDate, Students.StNationalID, ProgramStudents.RegistrationNumber, Countries.CntArabNationalty, Countries.cntNationality, CoursesGuide.CrsCode, CoursesGuide.CrsName, CoursesGuide.CrsNameEng, CoursesGuide.CrsTHours, programstudentscourses.Score, programstudentscourses.Semester FROM Students, Countries, ProgramStudents, programstudentscourses, CoursesGuide WHERE Students.StID = $StId AND Students.StID = programstudentscourses.StID AND Students.StNationality=Countries.cntId AND Students.StID=programstudentscourses.StID AND programstudentscourses.PrgId=$PrgId AND programstudentscourses.YearId=$YearId AND Students.StID=ProgramStudents.StID AND ProgramStudents.PrgId=$PrgId AND ProgramStudents.YearId=$YearId AND programstudentscourses.CrsId=CoursesGuide.CrsId Order BY programstudentscourses.Semester, CoursesGuide.CrsCode ASC;";
	
	//echo $qq;

	$header = 0;
	$oldSemester = 0;
	$totalCredit = 0;
	$totalPoints = 0;
	$DisplayScore = 0;
	$DisplaytotalPoints = 0;
	
	if ($stmt = mysqli_prepare($dbc, $qq)){
	   if(mysqli_stmt_execute($stmt)){
		   if(mysqli_stmt_bind_result($stmt, $StName, $StEname, $StBDate, $StNationalID, $RegistrationNumber, $CntArabNationalty, $cntNationality, $CrsCode, $CrsName, $CrsNameEng, $CrsTHours, $Score, $Semester)){
			   while(mysqli_stmt_fetch($stmt)){
				if($header == 0){
					echo "<form method='post' style='max-width:800px;margin:auto;direction:$__dir;'>";
					echo "<div class='frmButtons'><button type='button' class='printBtn' onclick='PrintDiv();'>طباعة</button>  <button type='submit' class='cnlBtn' name='mode' value='studentAdmission'>عودة</button></div>";					
					echo "</div></form>";	
					echo "<div id='divToPrint' dir='rtl'>";
?>
					<style> 
						body {
							direction: <?php echo $gdir; ?>;
						}					
						.transcriptHeader { 
							direction: rtl; 
							border: 0px solid black; 
							border-collapse: collapse; 
							dir: rtl; 
							width: 100%; 
						} 
						.transcriptTable { 
							direction: ltr; 
							border: 1px solid black; 
							border-collapse: collapse; 
							dir: rtl; 
							width: 100%; 
						} 
						.transcriptTableCourses { 
							border: 1px solid black; 
							border-collapse: collapse; 
							text-align: left; 
							border-top: 0; 
							border-left: 1; 
							border-bottom: 0; 
							border-right: 1; 
						} 
						.transcriptTableNonCourses { 
							text-align: center; 
							border: 1px solid black; 
							border-collapse: collapse; 
							border-top: 0; 
							border-left: 1; 
							border-bottom: 0; 
							border-right: 1; 
							} 
						.transcriptTableSemester { 
							border: 1px solid black; 
							border-collapse: collapse; 
							background-color: #e6e8fa; 
							text-align:center; 
							border-top: 0; 
							font-style: italic; 
							} 
						.transcriptTableSemesterHead { 
							border: 1px solid black; 
							border-collapse: collapse; 
							background-color: #e6e8fa; 
							text-align:center; 
							border-top: 2; 
							font-style: italic; 
							} 							
						.transcriptTableHeader { 
							border: 2px solid black; 
							background-color: #848482; 
							} 
						.SmallText{
							font-size: 12px;
						}
						
						.NormalText{
							font-size: 13px;
							font-weight: normal;
						}
						.BigText{
							font-size: 17px;
							font-weight: bold;
						}
					</style> 
<?php
					$EnglishName = ucwords(strtolower($StEname));
					echo "<center>";
					echo "<br><br><br><br>";						
					echo "<h3 align='center'>Official Transript</h3>";
					echo "<table width='100%' class'transcriptHeader'>";
					echo "<tr class'transcriptHeader'><td width='50%' colspan='3' align='right' class='BigText'>معهد تدريب الموانىء</td><td width='50%' colspan='3' align='left' class='BigText'>Port Training Institute<br></td></tr>";
					echo "<tr class'transcriptHeader'><td width='20%'>رقم التسجيل</td><td>:</td><td width='30%' align='right'>$RegistrationNumber</td><td width='30%' align='left'>$RegistrationNumber</td><td>:</td><td width='20%' align='left'>Registration No</td></tr>";
					echo "<tr class'transcriptHeader'><td>الاسم</td><td>:</td><td align='right'>$StName</td><td align='left'>$EnglishName</td><td>:</td><td  align='left'>Name</td></tr>";
					echo "<tr><td>تاريخ الميلاد	</td><td>:</td><td align='right'>$StBDate</td><td align='left'>$StBDate</td><td>:</td><td align='left'>Date of Birth</td></tr>";
					echo "<tr><td>رقم البطاقة </td><td>:</td><td align='right'>$StNationalID</td><td align='left'>$StNationalID</td><td>:</td><td align='left'>National ID</td></tr>";
					echo "<tr><td>الجنسية	</td><td>:</td><td align='right'>$CntArabNationalty</td><td align='left'>$cntNationality</td><td>:</td><td align='left'>Nationality</td></tr>";
					echo "<tr><td>الدبلومة </td><td>:</td><td align='right'>$PrgName</td><td align='left'>Professional Diploma in Ports and Logistics</td><td>:</td><td align='left'>Diploma</td></tr>";
					echo "</table>";

					echo "<table class='transcriptTable'>";
					echo "<tr><th width='15%' class='transcriptTableHeader'>Course Code</th><th width='50%' class='transcriptTableHeader'>Course Title</th><th width='7%' class='transcriptTableHeader'>CR. ATT.</th><th width='7%' class='transcriptTableHeader'>GR</th><th width='7%' class='transcriptTableHeader'>PTS</th><th width='7%' class='transcriptTableHeader'>CR. ACD.</th><th width='7%' class='transcriptTableHeader'>GPA</th></tr>";
					$header = 1;
				}
				$isSemApproved = isset($approvedSemesters[$Semester]) && $approvedSemesters[$Semester] == 1;

				if($Semester != $oldSemester){
					if($oldSemester != 0){
						$oldSemApproved = isset($approvedSemesters[$oldSemester]) && $approvedSemesters[$oldSemester] == 1;
						if ($oldSemApproved) {
							echo "<tr><td class='transcriptTableNonCourses'></td><td class='transcriptTableCourses'></td><td  class='transcriptTableSemester'>$totalCredit</td><td  class='transcriptTableSemester'></td><td  class='transcriptTableSemester'>$DisplaytotalPoints</td><td  class='transcriptTableSemester'>$totalCredit</td><td  class='transcriptTableSemester'>$totalGPA</td></tr>";
						} else {
							echo "<tr><td class='transcriptTableNonCourses'></td><td class='transcriptTableCourses'></td><td  class='transcriptTableSemester'>-</td><td  class='transcriptTableSemester'>-</td><td  class='transcriptTableSemester'>-</td><td  class='transcriptTableSemester'>-</td><td  class='transcriptTableSemester'>-</td></tr>";
						}
					}
					echo "<tr><th colspan='7' class='transcriptTableSemesterHead'>Semester No. ($Semester)</th></tr>";
					$oldSemester = $Semester;
			   }
				if($Score > 89){
					$ScoreGrade = "A";
					$ScorePoints = 12;
				}else
					if($Score > 84){
						$ScoreGrade = "A-";
						$ScorePoints =11;
					}else
						if($Score > 79){
							$ScoreGrade = "B+";
							$ScorePoints =10;
						}else
							if($Score > 74){
								$ScoreGrade = "B";
								$ScorePoints =9;
							}else
								if($Score > 69){
									$ScoreGrade = "B-";
									$ScorePoints =8;
								}else	
									if($Score > 64){
										$ScoreGrade = "C+";
										$ScorePoints =7;
									}else
										if($Score > 59){
											$ScoreGrade = "C";
											$ScorePoints =6;
										}else{
											$ScoreGrade = "F";
											$ScorePoints =0;
										}
				$ScorePoints = ($ScorePoints/3) * $CrsTHours;
				$DisplayScore = number_format($ScorePoints, 2);
				$totalGPADisplay = 0;
				
				if ($isSemApproved) {
					echo "<tr><td class='transcriptTableNonCourses'>$CrsCode</td><td class='transcriptTableCourses'>$CrsNameEng</td><td class='transcriptTableNonCourses'>$CrsTHours</td><td class='transcriptTableNonCourses'>$ScoreGrade</td><td class='transcriptTableNonCourses'>$DisplayScore</td><td class='transcriptTableNonCourses'>$CrsTHours</td><td class='transcriptTableNonCourses'></td></tr>";
				} else {
					echo "<tr><td class='transcriptTableNonCourses'>$CrsCode</td><td class='transcriptTableCourses'>$CrsNameEng</td><td class='transcriptTableNonCourses'>-</td><td class='transcriptTableNonCourses'>-</td><td class='transcriptTableNonCourses'>-</td><td class='transcriptTableNonCourses'>-</td><td class='transcriptTableNonCourses'>-</td></tr>";
				}
				$totalCredit = $totalCredit + $CrsTHours;
				$totalPoints = $totalPoints + $ScorePoints;
				$DisplaytotalPoints =  number_format($totalPoints, 2);
				if($totalPoints ==0){
					$totalGPA = 0;
				}else{
					$totalGPA = $totalPoints / $totalCredit;
					$totalGPADisplay = number_format($totalGPA, 2);
				}
			   }
				if($oldSemester != 0){
					$oldSemApproved = isset($approvedSemesters[$oldSemester]) && $approvedSemesters[$oldSemester] == 1;
					if ($oldSemApproved) {
						echo "<tr><td class='transcriptTableNonCourses'></td><td class='transcriptTableCourses'></td><td  class='transcriptTableSemester'>$totalCredit</td><td  class='transcriptTableSemester'></td><td  class='transcriptTableSemester'>$DisplaytotalPoints</td><td  class='transcriptTableSemester'>$totalCredit</td><td  class='transcriptTableSemester'>$totalGPADisplay</td></tr>";
					} else {
						echo "<tr><td class='transcriptTableNonCourses'></td><td class='transcriptTableCourses'></td><td  class='transcriptTableSemester'>-</td><td  class='transcriptTableSemester'>-</td><td  class='transcriptTableSemester'>-</td><td  class='transcriptTableSemester'>-</td><td  class='transcriptTableSemester'>-</td></tr>";
					}
				}
				echo "</table><div class='SmallText' align='left'>Date:" .date("d/m/Y") ."</div><br>";
				echo "<table width='100%' dir>";
				echo "<tr><td width='50%' align='center'>Dean of Port Training Institute<br><br><br><br></td><td width='50%'></td></tr>";
				echo "</table>";
				echo "<br><br><br><br><br><br><hr>";					
				echo "<table width='100%' dir='ltr'>";
				echo "<tr><td width='60%' align='center' class='SmallText'>Grading System</td><td width='40%' align='center' class='SmallText'>GPA System</td></tr>";
				echo "<tr><td align='center'>";
					echo "<table width='100%'>";
					echo "<tr><td width='45%' align='right' class='SmallText'>A =12/3</td><td width='10%' align='left'></td><td width='45%' align='left' class='SmallText'>B-=8/3</td></tr>";
					echo "<tr><td width='45%' align='right' class='SmallText'>A-=11/3</td><td width='10%' align='left'></td><td width='45%' align='left' class='SmallText'>C+=7/3</td></tr>";
					echo "<tr><td width='45%' align='right' class='SmallText'>B+=10/3</td><td width='10%' align='left'></td><td width='45%' align='left' class='SmallText'>C =6/3</td></tr>";
					echo "<tr><td width='45%' align='right' class='SmallText'>B =9/3</td><td width='10%' align='left'></td><td width='45%' align='left' class='SmallText'>F =0</td></tr>";
					echo "</table>";					
				echo "</td><td>";
					echo "<table width='100%'>";
					echo "<tr><td width='45%' align='right' class='SmallText'>Excellent</td><td width='10%' align='left'></td><td width='45%' align='left' class='SmallText'>3.4 - 4.0</td></tr>";
					echo "<tr><td width='45%' align='right' class='SmallText'>Very Good</td><td width='10%' align='left'></td><td width='45%' align='left' class='SmallText'>2.8 - 3.4</td></tr>";
					echo "<tr><td width='45%' align='right' class='SmallText'>Good</td><td width='10%' align='left'></td><td width='45%' align='left' class='SmallText'>2.4 - 2.8</td></tr>";
					echo "<tr><td width='45%' align='right' class='SmallText'>Pass</td><td width='10%' align='left'></td><td width='45%' align='left' class='SmallText'>2.0 - 2.4</td></tr>";
					echo "</table>";				
				echo "</td></tr>";
				echo "</table><br><br>";


      $q="UPDATE ProgramStudents SET FINAL_GPA=? WHERE StId=? AND PrgId=?";
      if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt,"dii", $totalGPA, $StId, $PrgId)){
          if(!mysqli_stmt_execute($stmt)){
            $errorMessage="Can not Update record";
          }
        }
        mysqli_stmt_close($stmt);
      }
		
				echo "</div>";

					
				}
		   }
	   }
	
}

//*****************************************************************************************
//sec:Show students to register to a batch under a diploma
//*****************************************************************************************
if ($mode == "studentAdmission") {
	if(isset($errorMessage)){
        echo "<div class='error'><p>$errorMessage</p></div>";
    }

    $formTitle = "اضاقة طالب لدفعة";
    echo "<h3>$formTitle</h3>";
	echo "<h3>$PrgName - $YearDesc</h3>";
	
      echo "<table style='width: 580px; margin: auto;'>";
      echo "<tr><td style='width: 125px; margin: auto;'><form method='post'>";
      echo "<input type='hidden' name='PrgId' value='$PrgId'>";
      echo "<input type='hidden' name='YearId' value='$YearId'>";
      echo "<input type='hidden' name='PrgName' value='$PrgName'>";
      echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
      echo "<input type='hidden' name='mode' value='addStudent'>";
      echo "<button type='submit' class='addBtn'>اضاقة طالب</button>";
      echo "</form></td>";
      echo "<td style='width: 180px; margin: auto;'><form method='post'>";
      echo "<input type='hidden' name='PrgId' value='$PrgId'>";
      echo "<input type='hidden' name='YearId' value='$YearId'>";
      echo "<input type='hidden' name='PrgName' value='$PrgName'>";
      echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
      echo "<input type='hidden' name='mode' value='transferStudent'>";
      echo "<button type='submit' class='addBtn' style='width: auto; padding: 10px 15px;'>اضافة طالب من دفعة اخرى</button>";
      echo "</form></td>";
      echo "<td style='width: 130px; margin: auto;'><form method='post'>";
      echo "<input type='hidden' name='PrgId' value='$PrgId'>";
      echo "<input type='hidden' name='YearId' value='$YearId'>";
      echo "<input type='hidden' name='PrgName' value='$PrgName'>";
      echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
      echo "<input type='hidden' name='mode' value='importStudents'>";
      echo "<button type='submit' class='addBtn' style='background-color:#28a745;'>رفع Excel</button>";
      echo "</form></td>";
      echo "<td style='width: 125px; margin: auto;'><form method='post'>";
      echo "<input type='hidden' name='PrgId' value='$PrgId'>";
      echo "<input type='hidden' name='YearId' value='$YearId'>";
      echo "<input type='hidden' name='PrgName' value='$PrgName'>";
      echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
      echo "<input type='hidden' name='mode' value='batchList'>";
      echo "<button type='submit' class='addBtn'>صفحة الدفعه</button>";
      echo "</form></td></tr></table>";
      //filter list form 
      echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
      //list table
      echo "<table id='masterTable'>";
      echo "<tr class='header'><th style='text-align: right;'> م - اﻹسم - رقم التسجيل </th><th style='width:600px;text-align: center;'></th></tr>";
      $lineNo=1;

      $qq = "SELECT `StId` FROM ProgramStudents where `PrgId`=? AND `YearId`=?";
      if($stmt=mysqli_prepare($dbc, $qq)){
		if(mysqli_stmt_bind_param($stmt, "ii", $PrgId, $YearId)){
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
		}
	  }


      $q="SELECT s.StId, s.StName, s.StNationalID, s.StMaritimePassportNo, ps.RegistrationNumber FROM Students s INNER JOIN ProgramStudents ps ON s.StID = ps.StId WHERE ps.PrgId = $PrgId AND ps.YearId = $YearId ORDER BY s.StName ASC";
      $r=mysqli_query($dbc,$q);
      if($r){
		
		$RS = mysqli_fetch_all($result, MYSQLI_NUM);
		$numberOfRegistered = count($RS);
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
		  foreach($row as $key => $value){
            $$key=$value;
          }
		  
          echo "<tr><td style='text-align: right;'> $lineNo - $StName - [$RegistrationNumber]</td>";
          echo "<td style='text-align: left;'><form method='post'>";
          echo "<input type='hidden' name='StId' value='$StId'>";
		  echo "<input type='hidden' name='PrgId' value='$PrgId'>";
		  echo "<input type='hidden' name='YearId' value='$YearId'>";
		  echo "<input type='hidden' name='RegistrationNumber' value='" .substr($YearDesc,2) .str_pad($StId, 3, '0', STR_PAD_LEFT) ."'>";
          echo "<button type='submit' class='edtBtn' name='mode' value='editStudent'>تعديل</button> ";
		  if($numberOfRegistered == 0){
				$NotRegistered = 1;
		  }else{
				for ($i = 0; $i < count($RS); $i++) {
					if($StId == $RS[$i][0]){
						echo "<button type='submit' class='delBtn' name='mode' value='removeStudentFromBatch'>الغاء تسجيل</button> ";
						echo "<button type='submit' class='delBtn' name='mode' value='showtranscript'>Transcript</button> ";
						$numberOfRegistered = $numberOfRegistered -1;
						$NotRegistered = 0;
						break;
					}else{
						$NotRegistered = 1;
					}
				}
		  }
          if($NotRegistered == 1){
			echo "<button type='submit' class='viewBtn' name='mode' value='addStudent2Batch'>تسجيل</button> ";
		  }
		  $NotRegistered =2;
		  echo "</form></td></tr>";
          $lineNo++;
        }
        echo "</table>";
        mysqli_free_result($r);
      }
    }


//*****************************************************************************************
//sec:Show students to register to a batch under a diploma
//*****************************************************************************************
if ($mode == "studentOrder") {
	if(isset($errorMessage)){
        echo "<div class='error'><p>$errorMessage</p></div>";
    }

    $formTitle = "ترتيب الطلاب في الدفعة";
    echo "<h3>$formTitle</h3>";
	echo "<h3>$PrgName - $YearDesc</h3>";
	
	$lineNo =0;
	$CurrentStudentID =0;
	$CurrentStudentName = "";

	echo "<div style='display:flex; gap:10px; justify-content:center; margin-bottom:10px;'>";
	echo "<form method='post' style='display:inline;'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='PrgName' value='$PrgName'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
	echo "<button type='submit' class='cnlBtn' name='mode' value='batchList'>العودة</button>";
	echo "</form>";
	echo "<button type='button' class='printBtn' onclick='PrintDiv();'>طباعة</button>";
	echo "</div>";
	echo "<div id='divToPrint' dir='rtl'>";
	?>
					<style> 
						body {
							direction: <?php echo $gdir; ?>;
						}					
						.transcriptHeader { 
							direction: rtl; 
							border: 0px solid black; 
							border-collapse: collapse; 
							dir: rtl; 
							width: 100%; 
						} 
						.transcriptTable { 
							direction: ltr; 
							border: 1px solid black; 
							border-collapse: collapse; 
							dir: rtl; 
							width: 100%; 
						} 
						.transcriptTableCourses { 
							border: 1px solid black; 
							border-collapse: collapse; 
							text-align: left; 
							border-top: 0; 
							border-left: 1; 
							border-bottom: 0; 
							border-right: 1; 
						} 
						.transcriptTableNonCourses { 
							text-align: center; 
							border: 1px solid black; 
							border-collapse: collapse; 
							border-top: 0; 
							border-left: 1; 
							border-bottom: 0; 
							border-right: 1; 
							} 
						.transcriptTableSemester { 
							border: 1px solid black; 
							border-collapse: collapse; 
							background-color: #e6e8fa; 
							text-align:center; 
							border-top: 0; 
							font-style: italic; 
							} 
						.transcriptTableSemesterHead { 
							border: 1px solid black; 
							border-collapse: collapse; 
							background-color: #e6e8fa; 
							text-align:center; 
							border-top: 2; 
							font-style: italic; 
							} 							
						.transcriptTableHeader { 
							border: 2px solid black; 
							background-color: #848482; 
							} 
						.SmallText{
							font-size: 12px;
						}
						
						.NormalText{
							font-size: 13px;
							font-weight: normal;
						}
						.BigText{
							font-size: 17px;
							font-weight: bold;
						}
					</style> 
<?php
	echo "<table id='masterTable'  dir='rtl' border='1'>";
    echo "<tr class='header'><th style='width:100px;text-align: center;'>الترتيب</th><th style='width:100px;text-align: center;'>رقم التسجيل</th><th style='width:300px;text-align: center;'>اسم الطالب</th><th style='width:300px;text-align: center;'>اسم الطالب</th><th style='width:100px;text-align: center;'>المعدل التراكمي</th><th style='width:100px;text-align: center;'>التقدير العام</th></tr>";


	$qq = "SELECT Students.StName, Students.StEName, ProgramStudents.RegistrationNumber, ProgramStudents.FINAL_GPA from Students, ProgramStudents WHERE Students.StId=ProgramStudents.StId AND ProgramStudents.PrgId=$PrgId AND ProgramStudents.YearId=$YearId ORDER BY ProgramStudents.FINAL_GPA DESC;";

	$prev_GPA = 5;
	$redundent = 1;
	$Grade = '';
	$Grade_Excelent =0;
	$Grade_VeryGood =0;
	$Grade_Good =0;
	$Grade_Pass =0;
	$Grade_Fail =0;
	if ($stmt = mysqli_prepare($dbc, $qq)){
	   if(mysqli_stmt_execute($stmt)){
		   if(mysqli_stmt_bind_result($stmt, $StName, $StEName, $RegistrationNumber, $FINAL_GPA)){
			   while(mysqli_stmt_fetch($stmt)){
					if($prev_GPA > $FINAL_GPA){
						$lineNo = $lineNo + $redundent;
						$redundent = 1;
					}else{
						$redundent = $redundent +1;
					}
					echo "<tr><td style='text-align: center;'> $lineNo</td>";
					echo "<td style='text-align: center;'> $RegistrationNumber</td>";
					echo "<td style='text-align: right;'> $StName</td>";
					echo "<td style='text-align: right;'> $StEName</td>";
					echo "<td style='text-align: center;'> $FINAL_GPA</td>";
					if($FINAL_GPA >= 3.4){
							$Grade = 'امتياز';
							$Grade_Excelent = $Grade_Excelent + 1;
					}else
						if($FINAL_GPA >= 2.8){
							$Grade = 'جيد جدا';
							$Grade_VeryGood = $Grade_VeryGood + 1;
						}else
							if($FINAL_GPA >= 2.4){
								$Grade = 'جيد';
								$Grade_Good = $Grade_Good +1;
							}else
								if($FINAL_GPA >= 3.4){
									$Grade = 'مقبول';
									$Grade_Pass =$Grade_Pass + 1;
							}else{
								$Grade = 'ساقط';
								$Grade_Fail =$Grade_Fail + 1;
							}
					echo "<td style='text-align: center;'> $Grade</td></tr>";
					$prev_GPA = $FINAL_GPA;

				}
			}
		}
	}
    echo "</table></br>";
	echo "<table id='masterTable'  dir='rtl' border='1'>";
		echo "<tr class='header'><th style='width:100px;text-align: center;'>التقدير العام</th><th style='width:100px;text-align: center;'>الاعداد</th><th style='width:100px;text-align: center;'>النسبة</th></tr>";
		echo "<tr><td style='text-align: center;'> امتياز</td>";
			echo "<td style='text-align: center;'> $Grade_Excelent</td>";
			echo "<td style='text-align: center;'>" .number_format(($Grade_Excelent/$lineNo)*100,2) ."%</td></tr>";
		echo "<tr><td style='text-align: center;'> جيد جدا</td>";
			echo "<td style='text-align: center;'> $Grade_VeryGood</td>";
			echo "<td style='text-align: center;'>" .number_format(($Grade_VeryGood/$lineNo)*100,2) ."%</td></tr>";
		echo "<tr><td style='text-align: center;'> جيد</td>";
			echo "<td style='text-align: center;'> $Grade_Good</td>";
			echo "<td style='text-align: center;'>" .number_format(($Grade_Good/$lineNo)*100,2) ."%</td></tr>";
		echo "<tr><td style='text-align: center;'> مقبول</td>";
			echo "<td style='text-align: center;'> $Grade_Pass</td>";
			echo "<td style='text-align: center;'>" .number_format(($Grade_Pass/$lineNo)*100,2) ."%</td></tr>";
    echo "</table></br>";	
	echo "</div>";

	
	
	
	
	
	
	
	}

//*****************************************************************************************
if ($mode == "studentOrderEnglish") {
	if(isset($errorMessage)){
        echo "<div class='error'><p>$errorMessage</p></div>";
    }

    $formTitle = "ترتيب الطلاب في الدفعة";
    echo "<h3>$formTitle</h3>";
	echo "<h3>$PrgName - $YearDesc</h3>";
	
	$lineNo =0;
	$CurrentStudentID =0;
	$CurrentStudentName = "";

	echo "<div style='display:flex; gap:10px; justify-content:center; margin-bottom:10px;'>";
	echo "<form method='post' style='display:inline;'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='PrgName' value='$PrgName'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
	echo "<button type='submit' class='cnlBtn' name='mode' value='batchList'>العودة</button>";
	echo "</form>";
	echo "<button type='button' class='printBtn' onclick='PrintDiv();'>Print</button>";
	echo "</div>";
	echo "<div id='divToPrint' dir='rtl'>";
	?>
					<style> 
						body {
							direction: <?php echo $gdir; ?>;
						}					
						.transcriptHeader { 
							direction: rtl; 
							border: 0px solid black; 
							border-collapse: collapse; 
							dir: rtl; 
							width: 100%; 
						} 
						.transcriptTable { 
							direction: ltr; 
							border: 1px solid black; 
							border-collapse: collapse; 
							dir: rtl; 
							width: 100%; 
						} 
						.transcriptTableCourses { 
							border: 1px solid black; 
							border-collapse: collapse; 
							text-align: left; 
							border-top: 0; 
							border-left: 1; 
							border-bottom: 0; 
							border-right: 1; 
						} 
						.transcriptTableNonCourses { 
							text-align: center; 
							border: 1px solid black; 
							border-collapse: collapse; 
							border-top: 0; 
							border-left: 1; 
							border-bottom: 0; 
							border-right: 1; 
							} 
						.transcriptTableSemester { 
							border: 1px solid black; 
							border-collapse: collapse; 
							background-color: #e6e8fa; 
							text-align:center; 
							border-top: 0; 
							font-style: italic; 
							} 
						.transcriptTableSemesterHead { 
							border: 1px solid black; 
							border-collapse: collapse; 
							background-color: #e6e8fa; 
							text-align:center; 
							border-top: 2; 
							font-style: italic; 
							} 							
						.transcriptTableHeader { 
							border: 2px solid black; 
							background-color: #848482; 
							} 
						.SmallText{
							font-size: 12px;
						}
						
						.NormalText{
							font-size: 13px;
							font-weight: normal;
						}
						.BigText{
							font-size: 17px;
							font-weight: bold;
						}
					</style> 
<?php
	echo "<table id='masterTable'  dir='ltr' border='1'>";
    echo "<tr class='header'>
	<th style='width:50px;text-align: center;'>No.</th>
	<th style='width:100px;text-align: center;'>Registeration No.</th>
	<th style='width:300px;text-align: center;'>Student Name</th>
	<th style='width:200px;text-align: center;'>Nationality</th>
	<th style='width:100px;text-align: center;'>Birth Date</th>
	<th style='width:100px;text-align: center;'>ID</th>	
	<th style='width:100px;text-align: center;'>GPA</th></tr>";


	$qq = "SELECT Students.StEname, Students.StBDate, Students.StNationalID, ProgramStudents.RegistrationNumber, ProgramStudents.FINAL_GPA, Countries.cntNationality from Students, ProgramStudents, Countries WHERE Students.StId=ProgramStudents.StId AND ProgramStudents.PrgId=$PrgId AND ProgramStudents.YearId=$YearId AND Students.StNationality=Countries.cntId ORDER BY ProgramStudents.FINAL_GPA DESC;";
	
	$prev_GPA = 5;
	$redundent = 1;
	$Grade = '';
	$Grade_Excelent =0;
	$Grade_VeryGood =0;
	$Grade_Good =0;
	$Grade_Pass =0;
	$Grade_Fail =0;
	if ($stmt = mysqli_prepare($dbc, $qq)){
	   if(mysqli_stmt_execute($stmt)){
		   if(mysqli_stmt_bind_result($stmt, $StEname, $StBDate, $StNationalID, $RegistrationNumber, $FINAL_GPA, $cntNationality)){
			   while(mysqli_stmt_fetch($stmt)){
					if($prev_GPA > $FINAL_GPA){
						$lineNo = $lineNo + $redundent;
						$redundent = 1;
					}else{
						$redundent = $redundent +1;
					}
					echo "<tr><td style='text-align: center;'> $lineNo</td>";
					echo "<td style='text-align: center;'> $RegistrationNumber</td>";
					echo "<td style='text-align: left;'> $StEname</td>";
					echo "<td style='text-align: center;'> $cntNationality</td>";
					echo "<td style='text-align: center;'> $StBDate</td>";
					echo "<td style='text-align: center;'> $StNationalID</td>";
					echo "<td style='text-align: center;'> $FINAL_GPA</td>";
					$prev_GPA = $FINAL_GPA;

				}
			}
		}
	}
    echo "</table></br>";

	}

//*****************************************************************************************
//sec:saveedit-saveadd validate for saveedit or saveadd
//*****************************************************************************************
if ($mode == "saveedit" || $mode == "saveadd") {
    $errorMessage = "";

    // التحقق من إدخال وصف الدبلومة
    if ($trackName == "") {
        $errorMessage .= "لابد من إدخال وصف الدبلومة<br>";
    }

	if ($errorMessage != "") {
        $mode = substr($mode, 4); 
    }
}

//*****************************************************************************************
//sec:saveedit save edit Batch data
//*****************************************************************************************
if ($mode == "saveeditBatch") {
    $q = "UPDATE `Years` SET `YearDesc`=?,`YearStart`=?, `YearEnd`=? WHERE `YearId`=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "sssi", $YearDesc, $YearStart, $YearEnd, $YearId)) {
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
    $mode = "viewBatch";
}

//*****************************************************************************************
//sec:saveadd Save added Batch data
//*****************************************************************************************
if ($mode == "saveaddBatch") {
    $q = "INSERT INTO `Years`(`YearDesc`,`YearStart`,`YearEnd`,`YearType`) VALUES(?,?,?,'D')";
	// echo $q ."-" .$YearDesc ."-" .$YearStart ."-" .$YearEnd;
    if ($stmt = mysqli_prepare($dbc, $q)){
        if (mysqli_stmt_bind_param($stmt, "sss", $YearDesc,$YearStart,$YearEnd)) {
            if (mysqli_stmt_execute($stmt)) {
                $YearId = mysqli_insert_id($dbc);
                $infoMessage = "تمت الإضافة بنجاح!";
            } else {
                $errorMessage = "لم يتم الحفظ<br>" . mysqli_error($dbc) . "!";
            }
        }
        mysqli_stmt_close($stmt);
    }
    $mode = "viewBatch";
}


//*****************************************************************************************
//sec:delete record
//*****************************************************************************************
if ($mode == "delete") {
    $q = "DELETE FROM `Years` WHERE `YearId`=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $YearId)) { // ربط معرّف الدبلومة
            if (mysqli_stmt_execute($stmt)) {
                $infoMessage = "تم الإلغاء بنجاح!";
            }
        }
        mysqli_stmt_close($stmt); 
    }
    $mode = "showList";  
}

//*****************************************************************************************
//sec:delete Batch record
//*****************************************************************************************
if ($mode == "deleteBatch") {
    $q = "DELETE FROM `Years` WHERE `YearId`=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $YearId)) { 
            if (mysqli_stmt_execute($stmt)) {
                $infoMessage = "تم الإلغاء بنجاح!";
            }
        }
        mysqli_stmt_close($stmt); 
    }
    $mode = "viewBatch";  
}

//*****************************************************************************************
//sec:add-edit Add/Edit Batch form 
//*****************************************************************************************
if ($mode == "addBatch" or $mode == "editBatch") {
    if (isset($errorMessage)) {
        echo "<div class='errorMessages'>$errorMessage</div>";
    }
    if ($mode == "addBatch") {
        $formTitle = " إضافة دفعة جديدة";
    } else {
        $formTitle = "تعديل دفعة";
    }

    echo "<h3>$formTitle</h3>";
	echo "<h3>$PrgName</h3>";
    echo "<center><form method='post'>";
    echo "<table>";

	if($mode=="editBatch"){
		echo "<tr><td style='width: 100px;'>كود الدفعة:</td><td style='width:650px;'>";
		echo "<div class='input-container'>";
		echo "<input class='input-field' type='text' readonly placeholder='كود الدفعة' maxlength='255' name='YearId' readonly";
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
	echo "<input class='input-field' type='date' placeholder='تاريخ البداية' name='YearStart'";
	if(isset($YearStart)){
		echo " value='$YearStart'";
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
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	if(isset($PrgName)){
		echo "<input type='hidden' name='PrgName' value='$PrgName'>";
	}

    $newMode = "save$mode";
    // Save and cancel buttons
    echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'> حفظ </button> <button type='submit' class='cnlBtn' name='mode' value='batchList'> تراجع </button></div>";
    echo "</form>";
    echo "</center>";
}


//*****************************************************************************************
//sec:view display Batch
//*****************************************************************************************
if ($mode == "viewBatch") {
    echo "<center>";
    echo "<h3>بيانات دفعه: $PrgName - $YearDesc</h3>";
    echo "<table>";
    echo "<tr><td>اسم الدفعة</td><td>:</td><td align='right'> $YearDesc </td></tr>";
    echo "<tr><td>تاريخ البداية</td><td>:</td><td align='right'> $YearStart </td></tr>";
	echo "<tr><td>تاريخ النهاية</td><td>:</td><td align='right'> $YearEnd </td></tr>";
    echo "</table>";

	// Query and display all courses of this program
	$q_courses = "SELECT CrsCode, CrsName, CrsTHours FROM CoursesGuide WHERE CrsProgram=? ORDER BY CrsCode";
	if ($stmt_courses = mysqli_prepare($dbc, $q_courses)) {
		if (mysqli_stmt_bind_param($stmt_courses, "i", $PrgId)) {
			if (mysqli_stmt_execute($stmt_courses)) {
				if (mysqli_stmt_bind_result($stmt_courses, $c_CrsCode, $c_CrsName, $c_CrsTHours)) {
					echo "<br><h4>المواد الدراسية للدبلومة</h4>";
					echo "<table id='masterTable' style='max-width:800px; margin: 15px auto;'>";
					echo "<tr class='header'>";
					echo "<th style='text-align: center; width: 10%;'>م</th>";
					echo "<th style='text-align: right; width: 25%;'>كود المادة</th>";
					echo "<th style='text-align: right; width: 45%;'>اسم المادة</th>";
					echo "<th style='text-align: center; width: 20%;'>الساعات</th>";
					echo "</tr>";
					
					$c_idx = 1;
					while (mysqli_stmt_fetch($stmt_courses)) {
						echo "<tr>";
						echo "<td style='text-align: center;'>$c_idx</td>";
						echo "<td style='text-align: right;'>$c_CrsCode</td>";
						echo "<td style='text-align: right;'>$c_CrsName</td>";
						echo "<td style='text-align: center;'>$c_CrsTHours</td>";
						echo "</tr>";
						$c_idx++;
					}
					echo "</table><br>";
				}
			}
		}
		mysqli_stmt_close($stmt_courses);
	}

	echo "<br>";
	$numberOfButtons = 3;
    $buttonCellWidth = $numberOfButtons * 115;
    $buttonCellWidth .= "px";
    echo "<div style='width: $buttonCellWidth'><form method='post'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<button type='submit' class='okBtn' name='mode' value='batchList'> حسنا </button> ";
	echo "<button type='submit' class='edtBtn' name='mode' value='editBatch'>تعديل</button> ";
	echo "<button type='submit' class='delBtn' name='mode' value='deleteConfirmBatch'>حذف</button> </form></div>";	
    echo "</center>";
}

//*****************************************************************************************
//sec:deleteConfirmBatch delete confirm
//*****************************************************************************************
if ($mode == "deleteConfirmBatch") {
    echo "<center>";
    echo "<h3>إلغاء مادة</h3>";
    echo "<div style='text-align: center;'>سيتم حذف  $YearDesc <br> هل أنت متأكد؟...</div><br>";
    echo "<form method='post' style='max-width:500px;margin:auto;'>";
    echo "<input type='hidden' name='PrgId' value='$PrgId'>"; 
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<input type='hidden' name='PrgName' value='$PrgName'>";		
    echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='deleteBatch'> نعم </button> <button type='submit' class='cnlBtn' name='mode' value='viewBatch'> لا </button></div>";
    echo "</form>";
    echo "</center>";
}
//*****************************************************************************************
//sec:delete Semester record
//*****************************************************************************************
if ($mode == "deleteSemester") {
    $affectedCount = 0;
    $q_count = "SELECT COUNT(DISTINCT StId) FROM programstudentscourses WHERE PrgId=? AND YearId=? AND Semester=?";
    if ($stmt_count = mysqli_prepare($dbc, $q_count)) {
        if (mysqli_stmt_bind_param($stmt_count, "iii", $PrgId, $YearId, $Semester)) {
            if (mysqli_stmt_execute($stmt_count)) {
                mysqli_stmt_bind_result($stmt_count, $affectedCount);
                mysqli_stmt_fetch($stmt_count);
            }
        }
        mysqli_stmt_close($stmt_count);
    }

    $SemesterStart = "";
    $SemesterEnd = "";
    $q_dates = "SELECT SemesterStart, SemesterEnd FROM ProgramYearSemester WHERE PrgId=? AND YearId=? AND Semester=?";
    if ($stmt_dates = mysqli_prepare($dbc, $q_dates)) {
        if (mysqli_stmt_bind_param($stmt_dates, "iii", $PrgId, $YearId, $Semester)) {
            if (mysqli_stmt_execute($stmt_dates)) {
                mysqli_stmt_bind_result($stmt_dates, $SemesterStart, $SemesterEnd);
                mysqli_stmt_fetch($stmt_dates);
            }
        }
        mysqli_stmt_close($stmt_dates);
    }

    echo "<center>";
    echo "<h3>إزالة فصل دراسي</h3>";
    
    if ($affectedCount > 0) {
        echo "<div style='text-align: center; color: red; font-weight: bold; max-width: 600px; margin: auto;'>";
        echo "تنبيه: يوجد $affectedCount طالب لديهم مواد ودرجات مسجلة في هذا الفصل الدراسي. حذف الفصل لن يحذف هذه البيانات تلقائياً، لكنها ستصبح غير مرتبطة بفصل دراسي معرّف.";
        echo "</div><br>";
    }
    
    echo "<div style='text-align: center;'>سيتم حذف الفصل الدراسي رقم $Semester ($SemesterStart - $SemesterEnd). هل أنت متأكد؟</div><br>";
    echo "<form method='post' style='max-width:500px;margin:auto;'>";
    echo "<input type='hidden' name='PrgId' value='$PrgId'>"; 
    echo "<input type='hidden' name='YearId' value='$YearId'>";
    echo "<input type='hidden' name='Semester' value='$Semester'>";
    if (isset($PrgName)) {
        echo "<input type='hidden' name='PrgName' value='$PrgName'>";
    }
    if (isset($YearDesc)) {
        echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
    }
    echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='doDeleteSemester'> نعم </button> <button type='submit' class='cnlBtn' name='mode' value='ManageSemester'> لا </button></div>";
    echo "</form>";
    echo "</center>";
}

if ($mode == "doDeleteSemester") {
    $q = "DELETE FROM `ProgramYearSemester` WHERE `PrgId`=? AND `YearId`=? AND `Semester`=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "iii", $PrgId, $YearId, $Semester)) { 
            if (mysqli_stmt_execute($stmt)) {
                $infoMessage = "تم الإلغاء بنجاح!";
            } else {
                $errorMessage = "تعذر حذف الفصل الدراسي: " . mysqli_error($dbc);
            }
        }
        mysqli_stmt_close($stmt); 
    }
    $mode = "ManageSemester";  
}
//*****************************************************************************************
//sec:saveedit save edit ٍSemester data
//*****************************************************************************************
if ($mode == "saveeditSemester") {
    $q = "UPDATE `ProgramYearSemester` SET `SemesterStart`=?, `SemesterEnd`=? WHERE `PrgId`=? AND `YearId`=? AND `Semester`=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "ssiii", $SemesterStart, $SemesterEnd, $PrgId, $YearId, $Semester)) {
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
    $mode = "ManageSemester";
}
//*****************************************************************************************
//sec:saveadd Save added Batch data
//*****************************************************************************************
if ($mode == "saveaddSemester") {
    $q = "INSERT INTO `ProgramYearSemester`(`PrgId`,`YearId`, `Semester`, `SemesterStart`, `SemesterEnd`) VALUES(?,?,?,?,?)";
	// echo $q ."-" .$PrgId ."," .$YearId."," .$Semester."," .$SemesterStart."," .$SemesterEnd;
    if ($stmt = mysqli_prepare($dbc, $q)){
        if (mysqli_stmt_bind_param($stmt, "iiiss", $PrgId, $YearId, $Semester, $SemesterStart, $SemesterEnd)) {
            if (mysqli_stmt_execute($stmt)) {
                $infoMessage = "تمت الإضافة بنجاح!";
            } else {
                $errorMessage = "لم يتم الحفظ<br>" . mysqli_error($dbc) . "!";
            }
        }
        mysqli_stmt_close($stmt);
    }
    $mode = "ManageSemester";
}
//*****************************************************************************************
//sec:saveedit ٍStudent Score Entry and Update
//*****************************************************************************************
if ($mode == "savecourseScoreEntry") {
    $q = "UPDATE `programstudentscourses` SET `Score`=? WHERE PrgId=$PrgId AND YearId=$YearId AND CrsId=$CrsId AND Semester=$Semester AND StId=$StId";
	// echo $q;
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $Score)) {
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
    $mode = "courseScoreEntry";
}
//*****************************************************************************************
//sec:Manage Semester for a specific batch
//*****************************************************************************************
if ($mode == "courseScoreEntry") {
	$CrsName = "";
	$CrsCode = "";
	$q_crs = "SELECT CrsCode, CrsName FROM CoursesGuide WHERE CrsId = ?";
	if ($stmt_crs = mysqli_prepare($dbc, $q_crs)) {
		mysqli_stmt_bind_param($stmt_crs, "i", $CrsId);
		if (mysqli_stmt_execute($stmt_crs)) {
			mysqli_stmt_bind_result($stmt_crs, $temp_CrsCode, $temp_CrsName);
			if (mysqli_stmt_fetch($stmt_crs)) {
				$CrsCode = $temp_CrsCode;
				$CrsName = $temp_CrsName;
			}
		}
		mysqli_stmt_close($stmt_crs);
	}

    echo "<center>";
    echo "<h3>بيانات دفعه: $PrgName - $YearDesc</h3>";
    echo "<table>";
    echo "<tr><td>اسم الدفعة</td><td>:</td><td align='right'> $YearDesc </td></tr>";
    echo "<tr><td>الفصل الدراسي</td><td>:</td><td align='right'> $Semester </td></tr>";
    echo "<tr><td>المادة</td><td>:</td><td align='right'> $CrsName ($CrsCode) </td></tr>";
    echo "<tr><td>تاريخ البداية</td><td>:</td><td align='right'> $YearStart </td></tr>";
    echo "<tr><td>تاريخ النهاية</td><td>:</td><td align='right'> $YearEnd </td></tr>";
    echo "</table>";
	echo "<br>";
	$numberOfButtons = 2;
    $buttonCellWidth = $numberOfButtons * 115;
    $buttonCellWidth .= "px";
    echo "<div style='width: $buttonCellWidth'><form method='post'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<input type='hidden' name='Semester' value='$Semester'>";		
	echo "<button type='submit' class='okBtn' name='mode' value='registeredCourses'> حسنا </button> ";
	echo "</form></div>";	
    echo "</center>";

	echo "<table id='masterTable'>";
	$qq = "SELECT Students.StName, Students.StId, programstudentscourses.Score from Students, programstudentscourses WHERE Students.StId=programstudentscourses.StId AND programstudentscourses.PrgId=$PrgId AND programstudentscourses.YearId=$YearId AND programstudentscourses.CrsId=$CrsId AND programstudentscourses.Semester=$Semester ORDER BY Students.StName ASC;";
	
	//echo $qq;
	$NewScorelineNo =0;
	$OldScorelineNo =0;
	$UpdateStudent = Array();
	
	if ($stmt = mysqli_prepare($dbc, $qq)){
	   if(mysqli_stmt_execute($stmt)){
		   if(mysqli_stmt_bind_result($stmt, $StName, $StId, $Score)){
			   while(mysqli_stmt_fetch($stmt)){
					if(isset($Score)){
						$OldScorelineNo++;
						array_push($UpdateStudent ,$StId);
					}else{
						$NewScorelineNo++;
						echo "<form method='post'>";
						echo "<tr><td style='text-align: right;'> $NewScorelineNo</td>";
						echo "<td style='text-align: right;'> $StName</td>";
						echo "<td style='text-align: left;'>";
						echo "<input type='hidden' name='YearId' value='$YearId'>";
						echo "<input type='hidden' name='PrgId' value='$PrgId'>";
						echo "<input type='hidden' name='Semester' value='$Semester'>";	
						echo "<input type='hidden' name='StId' value='$StId'>";
						echo "<input type='hidden' name='CrsId' value='$CrsId'>";
						echo "<input class='input-field' type='text' placeholder='' maxlength='20' name='Score'>";
						echo "<button type='submit' class='edtBtn' name='mode' value='savecourseScoreEntry'>حفظ</button> ";					
						echo "</form></td></tr>";
					}	
				}
		   }
	   }
	}

	if($OldScorelineNo > 0){
		$ScorelineNo =0;
		if ($stmt = mysqli_prepare($dbc, $qq)){
			if(mysqli_stmt_execute($stmt)){
				if(mysqli_stmt_bind_result($stmt, $StName, $StId, $Score)){
					foreach ($UpdateStudent as $x => $y) {
						//echo "no of saved student scores = " .$OldScorelineNo;
						while(mysqli_stmt_fetch($stmt)){
							if($y == $StId){
								$ScorelineNo++;
								echo"<form method='post'>";
								echo "<tr><td style='text-align: right;'> $ScorelineNo</td>";
								echo "<td style='text-align: right;'> $StName</td>";
								echo "<td style='text-align: left;'>";
								echo "<input type='hidden' name='YearId' value='$YearId'>";
								echo "<input type='hidden' name='PrgId' value='$PrgId'>";
								echo "<input type='hidden' name='Semester' value='$Semester'>";	
								echo "<input type='hidden' name='StId' value='$StId'>";
								echo "<input type='hidden' name='CrsId' value='$CrsId'>";
								echo "<input class='input-field' type='text' placeholder='' maxlength='20' name='Score' value='$Score'>";
								echo "<button type='submit' class='edtBtn' name='mode' value='savecourseScoreEntry'>تعديل</button> ";
								echo "</form></td></tr>";
								break;
							}		
						}
					}
				}
			}
		}
	}

    echo "</table>";

}
//*****************************************************************************************
//sec:Manage Semester for a specific batch
//*****************************************************************************************
if ($mode == "registeredCourses") {
    echo "<center>";
    echo "<h3>بيانات دفعه: $PrgName - $YearDesc</h3>";
    echo "<table>";
    echo "<tr><td>اسم الدفعة</td><td>:</td><td align='right'> $YearDesc </td></tr>";
    echo "<tr><td>تاريخ البداية</td><td>:</td><td align='right'> $YearStart </td></tr>";
	echo "<tr><td>تاريخ النهاية</td><td>:</td><td align='right'> $YearEnd </td></tr>";
    echo "</table>";
	echo "<br>";
	$numberOfButtons = 2;
    $buttonCellWidth = $numberOfButtons * 115;
    $buttonCellWidth .= "px";
    echo "<div style='width: $buttonCellWidth'><form method='post'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<button type='submit' class='okBtn' name='mode' value='ManageSemester'> حسنا </button> ";
	echo "</form></div>";	
    echo "</center>";
	
	$lineNo =1;

	echo "<table id='masterTable'>";
	$qq = "SELECT CoursesGuide.CrsId, CoursesGuide.CrsCode, CoursesGuide.CrsName FROM `ProgramCoursesSemester`,`CoursesGuide` WHERE ProgramCoursesSemester.PrgId = CoursesGuide.CrsProgram AND ProgramCoursesSemester.CrsId = CoursesGuide.CrsId AND ProgramCoursesSemester.PrgId = $PrgId AND ProgramCoursesSemester.Semester = $Semester ORDER BY Semester ASC";
	if ($stmt = mysqli_prepare($dbc, $qq)){
	   if(mysqli_stmt_execute($stmt)){
		   if(mysqli_stmt_bind_result($stmt, $CrsId, $CrsCode, $CrsName)){
			   while(mysqli_stmt_fetch($stmt)){
					echo "<form method='post'>";
					echo "<tr><td style='text-align: right;'> $lineNo</td>";
					echo "<td style='text-align: right;'> $CrsCode</td>";
					echo "<td style='text-align: right;'> $CrsName</td>";					
					echo "<td style='text-align: left;'>";
					echo "<input type='hidden' name='YearId' value='$YearId'>";
					echo "<input type='hidden' name='PrgId' value='$PrgId'>";
					echo "<input type='hidden' name='Semester' value='$Semester'>";	
					echo "<input type='hidden' name='CrsId' value='$CrsId'>";						
					echo "<button type='submit' class='edtBtn' name='mode' value='courseScoreEntry'>ادخال درجات</button> ";
//					echo "<button type='submit' class='edtBtn' name='mode' value='PrintCourseScore'>طباعة درجات</button> ";					
					echo "</form></td></tr>";
					$lineNo++;
				}
		   }
	   }
	}
    echo "</table>";
}
//*****************************************************************************************
//sec:Manage Semester for a specific batch
//*****************************************************************************************
if ($mode == "PrintCourseScore") {
    echo "<center>";
    echo "<h3>بيانات دفعه: $PrgName - $YearDesc</h3>";
    echo "<table>";
    echo "<tr><td>اسم الدفعة</td><td>:</td><td align='right'> $YearDesc </td></tr>";
    echo "<tr><td>تاريخ البداية</td><td>:</td><td align='right'> $YearStart </td></tr>";
	echo "<tr><td>تاريخ النهاية</td><td>:</td><td align='right'> $YearEnd </td></tr>";
    echo "</table>";
	echo "<br>";
	$numberOfButtons = 2;
    $buttonCellWidth = $numberOfButtons * 115;
    $buttonCellWidth .= "px";
    echo "<div style='width: $buttonCellWidth'><form method='post'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<button type='submit' class='okBtn' name='mode' value='ManageSemester'> حسنا </button> ";
	echo "</form></div>";	
    echo "</center>";
	
	$lineNo =0;
	$CurrentStudentID =0;
	$CurrentStudentName = "";
	$Courses = Array();
	$Scores = Array();

	echo "<div>";
	echo "<button type='button' class='printBtn' onclick='PrintDiv();'>طباعة</button>";
	echo "</div>";
	echo "<div id='divToPrint' dir='rtl'>";
	echo "<table id='masterTable'  dir='rtl'>";

	$qq = "SELECT programstudentscourses.Score, CoursesGuide.CrsId, CoursesGuide.CrsCode, CoursesGuide.CrsName, Students.StName, Students.StID FROM `ProgramStudents`, `programstudentscourses`, `CoursesGuide`, `Students` WHERE ProgramStudents.PrgId = $PrgId AND ProgramStudents.StId = Students.StID AND programstudentscourses.PrgId = $PrgId AND programstudentscourses.YearId = $YearId AND  programstudentscourses.Semester = $Semester AND programstudentscourses.CrsId = CoursesGuide.CrsId AND programstudentscourses.PrgId = CoursesGuide.CrsProgram AND programstudentscourses.StId = Students.StID ORDER BY programstudentscourses.StId,programstudentscourses.CrsId ASC";
	
	
	if ($stmt = mysqli_prepare($dbc, $qq)){
	   if(mysqli_stmt_execute($stmt)){
		   if(mysqli_stmt_bind_result($stmt, $Score, $CrsId, $CrsCode, $CrsName, $StName, $StID)){
			   while(mysqli_stmt_fetch($stmt)){
					if($lineNo == 0){
						array_push($Courses, $CrsName);
						if($CurrentStudentID == 0){
							$CurrentStudentID = $StID;
							$CurrentStudentName = $StName;
						}
					}
					if($CurrentStudentID == $StID){
						array_push($Scores, $Score);					   
					}else{
						if($lineNo == 0){
							array_pop($Courses);
							echo "<tr><th style='text-align: right;'>Serial</th>";
							echo "<th style='text-align: right;'>Student Name</th>";
							foreach ($Courses as $x => $y) {
								echo "<th style='text-align: center;'>$y</th>";
							}				
							echo "</tr>";
							$lineNo = $lineNo +1;
						}
						echo "<tr><td style='text-align: right;'> $lineNo</td>";
						echo "<td style='text-align: right;'>$CurrentStudentName</td>";
						foreach ($Scores as $x => $y) {
							echo "<td style='text-align: center;'>$y</td>";
						}				
						echo "</tr>";
						$CurrentStudentID = $StID;
						$CurrentStudentName = $StName;
						$lineNo = $lineNo +1;
						$Scores = [];
						array_push($Scores, $Score);
					}
				}
				if($lineNo > 0){
					echo "<tr><td style='text-align: right;'> $lineNo</td>";
					echo "<td style='text-align: right;'>$CurrentStudentName</td>";
					foreach ($Scores as $x => $y) {
						echo "<td style='text-align: center;'>$y</td>";
					}				
					echo "</tr>";
				}
		   }
	   }
	}
    echo "</table>";
	echo "</div>";

}
//*****************************************************************************************
//sec:saveadd Save added Batch data
//*****************************************************************************************
if ($mode == "registerStudentSemester") {
    $studentCount = 0;
    $q_st = "SELECT COUNT(*) FROM ProgramStudents WHERE PrgId=? AND YearId=?";
    if ($stmt_st = mysqli_prepare($dbc, $q_st)) {
        if (mysqli_stmt_bind_param($stmt_st, "ii", $PrgId, $YearId)) {
            if (mysqli_stmt_execute($stmt_st)) {
                mysqli_stmt_bind_result($stmt_st, $studentCount);
                mysqli_stmt_fetch($stmt_st);
            }
        }
        mysqli_stmt_close($stmt_st);
    }

    $courseCount = 0;
    $q_cr = "SELECT COUNT(*) FROM ProgramCoursesSemester WHERE PrgId=? AND Semester=?";
    if ($stmt_cr = mysqli_prepare($dbc, $q_cr)) {
        if (mysqli_stmt_bind_param($stmt_cr, "ii", $PrgId, $Semester)) {
            if (mysqli_stmt_execute($stmt_cr)) {
                mysqli_stmt_bind_result($stmt_cr, $courseCount);
                mysqli_stmt_fetch($stmt_cr);
            }
        }
        mysqli_stmt_close($stmt_cr);
    }

    echo "<center>";
    echo "<h3>تسجيل مواد الفصل الدراسي</h3>";
    echo "<div style='text-align: center; max-width: 600px; margin: auto;'>سيتم تسجيل جميع طلاب الدفعة ($studentCount طالب) في جميع مواد الفصل الدراسي رقم $Semester ($courseCount مادة). هل أنت متأكد؟</div><br>";
    echo "<div style='text-align: center; color: #d35400; font-weight: bold; max-width: 600px; margin: auto;'>تنبيه: هذا الإجراء يسجل كل الطلاب في الدفعة دفعة واحدة، وليس طالباً بعينه.</div><br>";
    echo "<form method='post' style='max-width:500px;margin:auto;'>";
    echo "<input type='hidden' name='PrgId' value='$PrgId'>";
    echo "<input type='hidden' name='YearId' value='$YearId'>";
    echo "<input type='hidden' name='Semester' value='$Semester'>";
    if (isset($PrgName)) {
        echo "<input type='hidden' name='PrgName' value='$PrgName'>";
    }
    if (isset($YearDesc)) {
        echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
    }
    echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='doRegisterStudentSemester'> نعم </button> <button type='submit' class='cnlBtn' name='mode' value='ManageSemester'> لا </button></div>";
    echo "</form>";
    echo "</center>";
}

if ($mode == "doRegisterStudentSemester") {
   	$new_q = "";
	$CrsId_Array = Array();
	
	$q_courses="SELECT `CrsId` FROM `ProgramCoursesSemester` WHERE `PrgId`=$PrgId AND `Semester`=$Semester ORDER BY `CrsId`";
	//echo $q_courses;
	if ($stmt_courses = mysqli_prepare($dbc, $q_courses)){
	   		if(mysqli_stmt_execute($stmt_courses)){
		   		if(mysqli_stmt_bind_result($stmt_courses, $CrsId)){
			   		while(mysqli_stmt_fetch($stmt_courses)){
						array_push($CrsId_Array, $CrsId);
					}
				}
			}
	}
	
	foreach ($CrsId_Array as $x => $y) {
		$q_student="SELECT `StId` FROM `ProgramStudents` WHERE `PrgId`=$PrgId AND `YearId`=$YearId ORDER BY `StId`";
		//echo $q_student ."<br>";
		if ($stmt = mysqli_prepare($dbc, $q_student)){
			if(mysqli_stmt_execute($stmt)){
				if(mysqli_stmt_bind_result($stmt, $StId)){
					while(mysqli_stmt_fetch($stmt)){
						if($new_q==""){
							$new_q = "insert ignore into programstudentscourses(StId,PrgId,YearId,CrsId,Semester) values ($StId,$PrgId,$YearId,$y,$Semester)";
						}else{
							$new_q = $new_q .",($StId,$PrgId,$YearId,$y,$Semester)";
						}
					}
				}
			}
		}
	}				
//	echo $new_q;
	if ($new_q != "" && $stmt = mysqli_prepare($dbc, $new_q)){
            if (mysqli_stmt_execute($stmt)) {
                $infoMessage = "تمت الإضافة بنجاح!";
            } else {
                $errorMessage = "لم يتم الحفظ<br>" . mysqli_error($dbc) . "!";
            }
         mysqli_stmt_close($stmt);
    } else {
        if ($new_q == "") {
            $errorMessage = "لا يوجد طلاب أو مواد مسجلة لهذا الفصل الدراسي!";
        }
    }
    $mode = "ManageSemester";

}
//*****************************************************************************************
//sec:saveadd Save added Batch data
//*****************************************************************************************
if ($mode == "unregisterStudentSemester") {
    $affectedCount = 0;
    $q_count = "SELECT COUNT(DISTINCT StId) FROM `programstudentscourses` WHERE `PrgId`=? AND `YearId`=? AND `Semester`=?";
    if ($stmt_count = mysqli_prepare($dbc, $q_count)) {
        if (mysqli_stmt_bind_param($stmt_count, "iii", $PrgId, $YearId, $Semester)) {
            if (mysqli_stmt_execute($stmt_count)) {
                mysqli_stmt_bind_result($stmt_count, $affectedCount);
                mysqli_stmt_fetch($stmt_count);
            }
        }
        mysqli_stmt_close($stmt_count);
    }

    echo "<center>";
    echo "<h3>إلغاء تسجيل فصل دراسي</h3>";
    echo "<div style='text-align: center; color: red;'>سيتم حذف تسجيل المواد والدرجات لـ $affectedCount طالب في هذا الفصل الدراسي. هذا الإجراء لا يمكن التراجع عنه!</div><br>";
    echo "<form method='post' style='max-width:500px;margin:auto;'>";
    echo "<input type='hidden' name='PrgId' value='$PrgId'>";
    echo "<input type='hidden' name='YearId' value='$YearId'>";
    echo "<input type='hidden' name='Semester' value='$Semester'>";
    if (isset($PrgName)) {
        echo "<input type='hidden' name='PrgName' value='$PrgName'>";
    }
    if (isset($YearDesc)) {
        echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
    }
    echo "<div class='frmButtons'>";
    echo "<button type='submit' class='savBtn' name='mode' value='doUnregisterStudentSemester'> نعم </button> ";
    echo "<button type='submit' class='cnlBtn' name='mode' value='ManageSemester'> لا </button>";
    echo "</div>";
    echo "</form>";
    echo "</center>";
}

if ($mode == "doUnregisterStudentSemester") {
    $q = "DELETE FROM `programstudentscourses` WHERE `PrgId`=? AND `YearId`=? AND `Semester`=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "iii", $PrgId, $YearId, $Semester)) { 
            if (mysqli_stmt_execute($stmt)) {
                $infoMessage = "تم الإلغاء بنجاح!";
            } else {
                $errorMessage = "تعذر إلغاء التسجيل: " . mysqli_error($dbc);
            }
        }
        mysqli_stmt_close($stmt); 
    }
    $mode = "ManageSemester";
}

//*****************************************************************************************
//sec: selectCourseToRegister, registerCourseStudents, doRegisterCourseStudents
//*****************************************************************************************
if ($mode == "selectCourseToRegister") {
    echo "<center>";
    if (isset($errorMessage) && $errorMessage != "") {
        echo "<div class='errorMessages'>$errorMessage</div><br>";
    }
    if (isset($infoMessage) && $infoMessage != "") {
        echo "<div class='infoMessages'>$infoMessage</div><br>";
    }
    echo "<h3>اختيار مادة للتسجيل: $PrgName - $YearDesc</h3>";
    echo "<table>";
    echo "<tr><td>اسم الدفعة</td><td>:</td><td align='right'> $YearDesc </td></tr>";
    echo "<tr><td>الفصل الدراسي</td><td>:</td><td align='right'> $Semester </td></tr>";
    echo "</table>";
    echo "<br>";
    echo "<div style='width: 115px;'><form method='post'>";
    echo "<input type='hidden' name='PrgId' value='$PrgId'>";
    echo "<input type='hidden' name='YearId' value='$YearId'>";
    echo "<input type='hidden' name='Semester' value='$Semester'>";
    if (isset($PrgName)) {
        echo "<input type='hidden' name='PrgName' value='$PrgName'>";
    }
    if (isset($YearDesc)) {
        echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
    }
    echo "<button type='submit' class='cnlBtn' name='mode' value='ManageSemester'> تراجع </button> ";
    echo "</form></div>";
    echo "</center>";

    echo "<table id='masterTable'>";
    echo "<tr>";
    echo "<th>كود المادة</th>";
    echo "<th>اسم المادة</th>";
    echo "<th>العملية</th>";
    echo "</tr>";

    $q = "SELECT cg.CrsId, cg.CrsCode, cg.CrsName
          FROM ProgramCoursesSemester pcs
          INNER JOIN CoursesGuide cg ON pcs.CrsId = cg.CrsId
          WHERE pcs.PrgId = ? AND pcs.Semester = ?
          ORDER BY cg.CrsCode";

    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "ii", $PrgId, $Semester)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $CrsId, $CrsCode, $CrsName)) {
                    while (mysqli_stmt_fetch($stmt)) {
                        echo "<tr>";
                        echo "<td style='text-align: right;'>$CrsCode</td>";
                        echo "<td style='text-align: right;'>$CrsName</td>";
                        echo "<td style='text-align: left;'>";
                        echo "<form method='post'>";
                        echo "<input type='hidden' name='PrgId' value='$PrgId'>";
                        echo "<input type='hidden' name='YearId' value='$YearId'>";
                        echo "<input type='hidden' name='Semester' value='$Semester'>";
                        if (isset($PrgName)) {
                            echo "<input type='hidden' name='PrgName' value='$PrgName'>";
                        }
                        if (isset($YearDesc)) {
                            echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
                        }
                        echo "<input type='hidden' name='CrsId' value='$CrsId'>";
                        echo "<button type='submit' class='edtBtn' name='mode' value='registerCourseStudents'>تسجيل الطلاب</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    echo "</table>";
}

if ($mode == "registerCourseStudents") {
    $CrsId = isset($_POST['CrsId']) ? intval($_POST['CrsId']) : 0;
    $CrsCode = "";
    $CrsName = "";
    
    // Get course info
    $q_crs = "SELECT CrsCode, CrsName FROM CoursesGuide WHERE CrsId = ?";
    if ($stmt_crs = mysqli_prepare($dbc, $q_crs)) {
        mysqli_stmt_bind_param($stmt_crs, "i", $CrsId);
        if (mysqli_stmt_execute($stmt_crs)) {
            mysqli_stmt_bind_result($stmt_crs, $CrsCode, $CrsName);
            mysqli_stmt_fetch($stmt_crs);
        }
        mysqli_stmt_close($stmt_crs);
    }

    echo "<center>";
    echo "<h3>تسجيل الطلاب في مادة: $CrsName ($CrsCode)</h3>";
    if (isset($errorMessage) && $errorMessage != "") {
        echo "<div class='errorMessages'>$errorMessage</div><br>";
    }
    echo "<table>";
    echo "<tr><td>اسم الدفعة</td><td>:</td><td align='right'> $YearDesc </td></tr>";
    echo "<tr><td>الفصل الدراسي</td><td>:</td><td align='right'> $Semester </td></tr>";
    echo "</table>";
    echo "<br>";
    echo "</center>";

    // JavaScript functions
    echo "<script>
    function toggleSelectAll() {
        var master = document.getElementById('selectAllChk');
        var boxes = document.querySelectorAll('.studentChk');
        boxes.forEach(function(cb){ cb.checked = master.checked; });
    }

    function updateMasterCheckbox() {
        var master = document.getElementById('selectAllChk');
        var boxes = document.querySelectorAll('.studentChk');
        var allChecked = true;
        if (boxes.length === 0) allChecked = false;
        boxes.forEach(function(cb) {
            if (!cb.checked) {
                allChecked = false;
            }
        });
        master.checked = allChecked;
    }
    </script>";

    // Query to get all registered students in this course
    $registeredStudents = array();
    $q_reg = "SELECT StId FROM programstudentscourses WHERE PrgId = ? AND YearId = ? AND CrsId = ? AND Semester = ?";
    if ($stmt_reg = mysqli_prepare($dbc, $q_reg)) {
        mysqli_stmt_bind_param($stmt_reg, "iiii", $PrgId, $YearId, $CrsId, $Semester);
        if (mysqli_stmt_execute($stmt_reg)) {
            mysqli_stmt_bind_result($stmt_reg, $regStId);
            while (mysqli_stmt_fetch($stmt_reg)) {
                $registeredStudents[$regStId] = true;
            }
        }
        mysqli_stmt_close($stmt_reg);
    }

    // Query all students of the batch/program
    $students = array();
    $q_std = "SELECT s.StID, s.StName
              FROM ProgramStudents ps
              INNER JOIN Students s ON ps.StID = s.StID
              WHERE ps.PrgId = ? AND ps.YearId = ?
              ORDER BY s.StName ASC";
    if ($stmt_std = mysqli_prepare($dbc, $q_std)) {
        mysqli_stmt_bind_param($stmt_std, "ii", $PrgId, $YearId);
        if (mysqli_stmt_execute($stmt_std)) {
            mysqli_stmt_bind_result($stmt_std, $StID, $StName);
            while (mysqli_stmt_fetch($stmt_std)) {
                $students[] = array('StID' => $StID, 'StName' => $StName);
            }
        }
        mysqli_stmt_close($stmt_std);
    }

    echo "<form method='post' action='tracksPrepare.php'>";
    echo "<input type='hidden' name='PrgId' value='$PrgId'>";
    echo "<input type='hidden' name='YearId' value='$YearId'>";
    echo "<input type='hidden' name='Semester' value='$Semester'>";
    echo "<input type='hidden' name='CrsId' value='$CrsId'>";
    if (isset($PrgName)) {
        echo "<input type='hidden' name='PrgName' value='$PrgName'>";
    }
    if (isset($YearDesc)) {
        echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
    }
    
    // Checkbox for Select All
    echo "<div style='text-align: right; max-width: 600px; margin: 10px auto;'>";
    echo "<input type='checkbox' id='selectAllChk' onchange='toggleSelectAll()'> <label for='selectAllChk' style='font-weight: bold;'>تحديد الكل</label>";
    echo "</div>";

    echo "<table id='masterTable'>";
    echo "<tr>";
    echo "<th>#</th>";
    echo "<th>اسم الطالب</th>";
    echo "<th>تحديد</th>";
    echo "</tr>";

    $count = 1;
    $allChecked = count($students) > 0;
    foreach ($students as $student) {
        $st_id = $student['StID'];
        $st_name = $student['StName'];
        $checked = isset($registeredStudents[$st_id]) ? "checked" : "";
        if (!$checked) {
            $allChecked = false;
        }
        echo "<tr>";
        echo "<td style='text-align: right;'>$count</td>";
        echo "<td style='text-align: right;'>$st_name</td>";
        echo "<td style='text-align: center;'>";
        echo "<input type='checkbox' name='StudentIds[]' value='$st_id' class='studentChk' onchange='updateMasterCheckbox()' $checked>";
        echo "</td>";
        echo "</tr>";
        $count++;
    }
    echo "</table>";

    // Update master checkbox initial state if all are checked
    if ($allChecked) {
        echo "<script>document.getElementById('selectAllChk').checked = true;</script>";
    }

    echo "<br>";
    echo "<div class='frmButtons' style='text-align: center;'>";
    echo "<button type='submit' class='savBtn' name='mode' value='doRegisterCourseStudents'>تأكيد</button> ";
    echo "<button type='submit' class='cnlBtn' name='mode' value='selectCourseToRegister'>تراجع</button>";
    echo "</div>";
    echo "</form>";
}

if ($mode == "doRegisterCourseStudents") {
    $CrsId = isset($_POST['CrsId']) ? intval($_POST['CrsId']) : 0;
    if (!isset($_POST['StudentIds']) || empty($_POST['StudentIds'])) {
        $errorMessage = "لم يتم اختيار أي طالب!";
        $mode = "registerCourseStudents";
    } else {
        $StudentIds = $_POST['StudentIds'];
        $success = true;
        
        $q_ins = "INSERT IGNORE INTO programstudentscourses (StId, PrgId, YearId, CrsId, Semester) VALUES (?, ?, ?, ?, ?)";
        if ($stmt_ins = mysqli_prepare($dbc, $q_ins)) {
            foreach ($StudentIds as $StId) {
                $StId_int = intval($StId);
                if (mysqli_stmt_bind_param($stmt_ins, "iiiii", $StId_int, $PrgId, $YearId, $CrsId, $Semester)) {
                    if (!mysqli_stmt_execute($stmt_ins)) {
                        $success = false;
                    }
                }
            }
            mysqli_stmt_close($stmt_ins);
        } else {
            $success = false;
        }

        if ($success) {
            $infoMessage = "تم تسجيل الطلاب المحددين في المادة بنجاح!";
        } else {
            $errorMessage = "حدث خطأ أثناء تسجيل بعض الطلاب.";
        }
        $mode = "ManageSemester";
    }
}

//*****************************************************************************************
//sec:edit-view-deleteConfirm read a record for view or edit Batch
//*****************************************************************************************
if($mode == "editSemester"){
    $q="SELECT `SemesterStart`,`SemesterEnd` FROM `ProgramYearSemester` WHERE `YearId`=? AND PrgId=? AND Semester=?";
    if($stmt=mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "iii", $YearId,$PrgId,$Semester)){
            if(mysqli_stmt_execute($stmt)){
                if(mysqli_stmt_bind_result($stmt, $SemesterStart,$SemesterEnd)){
                    if(!mysqli_stmt_fetch($stmt)){
                        $errorMessage="خطأ في البيانات!...";
                        $mode="ManageSemester";
                    }
                }
            }    
        }
        mysqli_stmt_close($stmt);
    }
}
//*****************************************************************************************
//sec:add-edit Add/Edit Semester form 
//*****************************************************************************************
if ($mode == "addSemester" or $mode == "editSemester") {
    if (isset($errorMessage)) {
        echo "<div class='errorMessages'>$errorMessage</div>";
    }
    if ($mode == "addSemester") {
        $formTitle = "اضاقة  مواعيد فصل دراسي";
    } else {
        $formTitle = "تعديل مواعيد قصل دراسي";
    }

    echo "<h3>$formTitle</h3>";
	echo "<h3>$PrgName - $YearDesc</h3>";
    echo "<center><form method='post'>";
    echo "<table>";

	echo "<tr><td style='width: 100px;'>الفصل الدراسي:</td><td style='width:650px;'>";
	echo "<div class='input-container'>";
	echo "<select class='input-field' name='Semester'>";
	$sel1 = (isset($Semester) && $Semester == 1) ? " selected" : "";
	$sel2 = (isset($Semester) && $Semester == 2) ? " selected" : "";
	echo "<option value='1'$sel1>الفصل الدراسي الأول</option>";
	echo "<option value='2'$sel2>الفصل الدراسي الثاني</option>";
	echo "</select>";
	echo "</div></td></tr>";  

	echo "<tr><td style='width: 100px;'>تاريخ البداية:</td><td style='width:650px;'>";
	echo "<div class='input-container'>";
	echo "<input class='input-field' type='date' placeholder='تاريخ البداية' name='SemesterStart'";
	if(isset($SemesterStart)){
		echo " value='$SemesterStart'";
	} 
	echo "></div></td></tr>";  

	echo "<tr><td style='width: 100px;'>تاريخ النهاية:</td><td style='width:650px;'>";
	echo "<div class='input-container'>";
	echo "<input class='input-field' type='date' placeholder='تاريخ النهاية' name='SemesterEnd'";
	if(isset($SemesterEnd)){
		echo " value='$SemesterEnd'";
	} 
	echo "></div></td></tr>";  

    echo "</table>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	
	if(isset($PrgName)){
		echo "<input type='hidden' name='PrgName' value='$PrgName'>";
	}

    $newMode = "save$mode";
    // Save and cancel buttons
    echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'> حفظ </button> <button type='submit' class='cnlBtn' name='mode' value='batchList'> تراجع </button></div>";
    echo "</form>";
    echo "</center>";
}
//*****************************************************************************************
//sec:Manage Semester for a specific batch
//*****************************************************************************************
if ($mode == "ManageSemester") {
    echo "<center>";
    if (isset($errorMessage) && $errorMessage != "") {
        echo "<div class='errorMessages'>$errorMessage</div><br>";
    }
    if (isset($infoMessage) && $infoMessage != "") {
        echo "<div class='infoMessages'>$infoMessage</div><br>";
    }
    echo "<h3>بيانات دفعه: $PrgName - $YearDesc</h3>";
    echo "<table>";
    echo "<tr><td>اسم الدفعة</td><td>:</td><td align='right'> $YearDesc </td></tr>";
    echo "<tr><td>تاريخ البداية</td><td>:</td><td align='right'> $YearStart </td></tr>";
	echo "<tr><td>تاريخ النهاية</td><td>:</td><td align='right'> $YearEnd </td></tr>";
    echo "</table>";
	echo "<br>";
	$numberOfButtons = 4;
    $buttonCellWidth = $numberOfButtons * 115;
    $buttonCellWidth .= "px";
    echo "<div style='width: $buttonCellWidth'><form method='post'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<button type='submit' class='okBtn' name='mode' value='batchList'> حسنا </button> ";
	echo "<button type='submit' class='edtBtn' name='mode' value='addSemester'>مواعيد فصل دراسي</button> ";
	echo "</form></div>";	
    echo "</center>";
	
	$Semester_Array = Array();
	$q_Semester="SELECT Distinct(Semester) FROM `programstudentscourses` WHERE `YearId`=$YearId AND PrgId=$PrgId";
//	echo $q_Semester;
	if ($stmt = mysqli_prepare($dbc, $q_Semester)){
		if(mysqli_stmt_execute($stmt)){
			if(mysqli_stmt_bind_result($stmt, $Temp_Semester)){
				while(mysqli_stmt_fetch($stmt)){
					array_push($Semester_Array, $Temp_Semester);
				}
			}
		}
	}
//	echo $q_Semester;
 
    if (isset($_POST['action']) && $_POST['action'] == 'deanApprove' && isset($_POST['Semester'])) {
        $deanSemester = $_POST['Semester'];
        if (isset($perms['ApproveTermGradesByDean']) && $perms['ApproveTermGradesByDean'] == 1) {
            $approveQ = "UPDATE `ProgramYearSemester` SET `IsApproved` = 1 WHERE `YearId` = ? AND `PrgId` = ? AND `Semester` = ?";
            if ($approveStmt = mysqli_prepare($dbc, $approveQ)) {
                mysqli_stmt_bind_param($approveStmt, "iii", $YearId, $PrgId, $deanSemester);
                if (mysqli_stmt_execute($approveStmt)) {
                    echo "<div class='infoMessages'>تم اعتماد الترم من قبل العميد بنجاح.</div>";
                } else {
                    echo "<div class='errorMessages'>حدث خطأ أثناء اعتماد الترم من قبل العميد.</div>";
                }
                mysqli_stmt_close($approveStmt);
            }
        }
    }

	echo "<table id='masterTable'>";
	$qq = "SELECT `SemesterStart`, `SemesterEnd`, `Semester`, `IsCompleted`, `IsApproved` FROM `ProgramYearSemester` where `YearId`=$YearId AND `PrgId`= $PrgId ORDER BY Semester ASC;";
	if ($stmt = mysqli_prepare($dbc, $qq)){
	   if(mysqli_stmt_execute($stmt)){
		   if(mysqli_stmt_bind_result($stmt, $SemesterStart, $SemesterEnd, $Semester, $IsCompleted, $IsApproved)){
			   while(mysqli_stmt_fetch($stmt)){
					echo "<tr><td style='text-align: right;'> $Semester</td>";
					echo "<td style='text-align: right;'> $SemesterStart</td>";
					echo "<td style='text-align: right;'> $SemesterEnd</td>";					
					echo "<td style='text-align: left;'><form method='post'>";
					echo "<input type='hidden' name='YearId' value='$YearId'>";
					echo "<input type='hidden' name='PrgId' value='$PrgId'>";
					echo "<input type='hidden' name='Semester' value='$Semester'>";	
					echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
					echo "<input type='hidden' name='PrgName' value='$PrgName'>";
					echo "<button type='submit' class='edtBtn' name='mode' value='editSemester'>تعديل</button> ";
					echo "<button type='submit' class='delBtn' name='mode' value='deleteSemester'>ازالة</button> ";
					$NumberOfRecords = 0;
foreach ($Semester_Array as $x => $y) {
//						echo $y ."<br>";
    if($y == $Semester){

        $NumberOfRecords = 1;
    }
}
if($NumberOfRecords != 0){
    echo "<button type='submit' class='pwdBtn' name='mode' value='unregisterStudentSemester'>الغاء التسجيل</button> ";
    echo "<button type='submit' class='savBtn' name='mode' value='registeredCourses'>درجات</button> ";
    echo "<button type='submit' class='edtBtn' name='mode' value='PrintCourseScore'>طباعة درجات</button> ";
    if(isset($perms['ApproveTermGrades']) && $perms['ApproveTermGrades'] == 1){
        echo "<button type='submit' class='okBtn' name='mode' value='approvalGrades'>اعتماد الترم</button> ";
    }
    if($IsCompleted == 1 && isset($perms['ApproveTermGradesByDean']) && $perms['ApproveTermGradesByDean'] == 1){
        if($IsApproved == 1){
            echo "<button type='button' class='disBtn' disabled>تم اعتماد العميد</button> ";
        } else {
            echo "<input type='hidden' name='action' value='deanApprove'>";
            echo "<button type='submit' class='savBtn' name='mode' value='ManageSemester'>اعتماد العميد</button> ";
        }
    }
    echo "<button type='submit' class='savBtn' name='mode' value='selectCourseToRegister'>تسجيل المواد</button> ";
} else {
    echo "<button type='submit' class='savBtn' name='mode' value='selectCourseToRegister'>تسجيل المواد</button> ";
}
					echo "</form></td></tr>";
				}
		   }
	   }
	} else {
		echo "<tr><td colspan='4' style='color:red;text-align:center;'>SQL Error: " . mysqli_error($dbc) . "</td></tr>";
	}
    echo "</table>";
}

//****************************************************************************************
//sec:Approval Grades List
//****************************************************************************************
if ($mode == "approvalGrades") {
    if (!isset($perms['ApproveTermGrades']) || $perms['ApproveTermGrades'] != 1) {
        $errorMessage = "غير مصرح لك باستعراض اعتماد الترم.";
        $mode = "ManageSemester";
    }
}
if ($mode == "approvalGrades") {
?>
<style> 
    .transcriptHeader { 
        direction: rtl; 
        border: 0px solid black; 
        border-collapse: collapse; 
        width: 100%; 
    } 
    .transcriptTable { 
        direction: ltr; 
        border: 1px solid black; 
        border-collapse: collapse; 
        width: 100%; 
    } 
    .transcriptTableCourses { 
        border: 1px solid black; 
        border-collapse: collapse; 
        text-align: right; 
        padding: 5px;
        direction: rtl;
    } 
    .transcriptTableNonCourses { 
        text-align: center; 
        border: 1px solid black; 
        border-collapse: collapse; 
        padding: 5px;
    } 
    .transcriptTableHeader { 
        border: 2px solid black; 
        background-color: #848482; 
        color: white;
        padding: 8px;
        direction: rtl;
    } 
    .BigText{
        font-size: 17px;
        font-weight: bold;
    }
</style>
<?php
    echo "<center><br><br>";
    echo "<table width='80%' class='transcriptHeader'>";
    echo "<tr><td align='right' class='BigText'>الدبلومة: $PrgName</td></tr>";
    echo "<tr><td align='right' class='BigText'>الدفعة: $YearDesc</td></tr>";
    echo "<tr><td align='right' class='BigText'>الفصل الدراسي: $Semester</td></tr>";
    echo "</table><br>";

    $q = "SELECT Students.StName, Students.StEname, CoursesGuide.CrsName, CoursesGuide.CrsNameEng, CoursesGuide.CrsCode, CoursesGuide.CrsTHours, programstudentscourses.Score 
          FROM Students 
          INNER JOIN programstudentscourses ON Students.StID = programstudentscourses.StID 
          INNER JOIN CoursesGuide ON programstudentscourses.CrsId = CoursesGuide.CrsId 
          WHERE programstudentscourses.PrgId = ? 
            AND programstudentscourses.YearId = ? 
            AND programstudentscourses.Semester = ?
          ORDER BY Students.StName, CoursesGuide.CrsCode";
          
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "iii", $PrgId, $YearId, $Semester)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $StName, $StEname, $CrsName, $CrsNameEng, $CrsCode, $CrsTHours, $Score)) {
                    
                    echo "<table width='95%' class='transcriptTable' style='margin:auto;'>";
                    echo "<tr>";
                    echo "<th class='transcriptTableHeader'>اسم الطالب</th>";
                    echo "<th class='transcriptTableHeader'>اسم الطالب بالانجليزية</th>";
                    echo "<th class='transcriptTableHeader'>Course Code</th>";
                    echo "<th class='transcriptTableHeader'>Course Title</th>";
                    echo "<th class='transcriptTableHeader'>CR. ATT.</th>";
                    echo "<th class='transcriptTableHeader'>GR</th>";
                    echo "<th class='transcriptTableHeader'>PTS</th>";
                    echo "<th class='transcriptTableHeader'>CR. ACD.</th>";
                    echo "</tr>";
                    
                    $hasRows = false;
                    while (mysqli_stmt_fetch($stmt)) {
                        $hasRows = true;
                        
                        if($Score > 89){
                            $ScoreGrade = "A";
                            $ScorePoints = 12;
                        }else if($Score > 84){
                            $ScoreGrade = "A-";
                            $ScorePoints = 11;
                        }else if($Score > 79){
                            $ScoreGrade = "B+";
                            $ScorePoints = 10;
                        }else if($Score > 74){
                            $ScoreGrade = "B";
                            $ScorePoints = 9;
                        }else if($Score > 69){
                            $ScoreGrade = "B-";
                            $ScorePoints = 8;
                        }else if($Score > 64){
                            $ScoreGrade = "C+";
                            $ScorePoints = 7;
                        }else if($Score > 59){
                            $ScoreGrade = "C";
                            $ScorePoints = 6;
                        }else{
                            $ScoreGrade = "F";
                            $ScorePoints = 0;
                        }
                        
                        if ($Score === null) {
                            $ScoreGrade = "-";
                            $DisplayScore = "-";
                            $CrAcd = "-";
                        } else {
                            $ScorePoints = ($ScorePoints / 3) * $CrsTHours;
                            $DisplayScore = number_format($ScorePoints, 2);
                            $CrAcd = ($ScoreGrade === "F") ? 0 : $CrsTHours;
                        }
                        
                        $dispEname = ucwords(strtolower($StEname));

                        echo "<tr>";
                        echo "<td class='transcriptTableCourses'>$StName</td>";
                        echo "<td class='transcriptTableCourses' style='direction:ltr; text-align:left;'>$dispEname</td>";
                        echo "<td class='transcriptTableNonCourses'>$CrsCode</td>";
                        echo "<td class='transcriptTableNonCourses' style='direction:ltr; text-align:left;'>$CrsNameEng</td>";
                        echo "<td class='transcriptTableNonCourses'>$CrsTHours</td>";
                        echo "<td class='transcriptTableNonCourses'>$ScoreGrade</td>";
                        echo "<td class='transcriptTableNonCourses'>$DisplayScore</td>";
                        echo "<td class='transcriptTableNonCourses'>$CrAcd</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    
                    if (!$hasRows) {
                        echo "<p>لا يوجد طلاب مسجلين في هذا الفصل.</p>";
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
    }

    if (isset($_POST['action']) && $_POST['action'] == 'completeSemester') {
        $updateQ = "UPDATE `ProgramYearSemester` SET `IsCompleted` = 1 WHERE `YearId` = ? AND `PrgId` = ? AND `Semester` = ?";
        if ($updateStmt = mysqli_prepare($dbc, $updateQ)) {
            mysqli_stmt_bind_param($updateStmt, "iii", $YearId, $PrgId, $Semester);
            if (mysqli_stmt_execute($updateStmt)) {
                $infoMessage = "تم إتمام الفصل الدراسي بنجاح.";
                echo "<div class='infoMessages'>$infoMessage</div>";
            } else {
                $errorMessage = "حدث خطأ أثناء إتمام الفصل الدراسي.";
                echo "<div class='errorMessages'>$errorMessage</div>";
            }
            mysqli_stmt_close($updateStmt);
        }
    }

    // Check if current semester is already completed
    $isSemesterCompleted = 0;
    $checkQ = "SELECT `IsCompleted` FROM `ProgramYearSemester` WHERE `YearId` = ? AND `PrgId` = ? AND `Semester` = ?";
    if ($checkStmt = mysqli_prepare($dbc, $checkQ)) {
        mysqli_stmt_bind_param($checkStmt, "iii", $YearId, $PrgId, $Semester);
        if (mysqli_stmt_execute($checkStmt)) {
            mysqli_stmt_bind_result($checkStmt, $isSemesterCompleted);
            mysqli_stmt_fetch($checkStmt);
        }
        mysqli_stmt_close($checkStmt);
    }

    echo "<div style='margin-top:20px; text-align:center; display:flex; justify-content:center; gap:10px;'>";
    echo "<form method='post' style='display:inline;'>";
    echo "<input type='hidden' name='mode' value='ManageSemester'>";
    echo "<input type='hidden' name='PrgId' value='$PrgId'>";
    echo "<input type='hidden' name='YearId' value='$YearId'>";
    echo "<input type='hidden' name='PrgName' value='$PrgName'>";
    echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
    echo "<button type='submit' class='cnlBtn'>عودة</button>";
    echo "</form>";
    echo "<form method='post' style='display:inline;'>";
    echo "<input type='hidden' name='mode' value='approvalGrades'>";
    echo "<input type='hidden' name='PrgId' value='$PrgId'>";
    echo "<input type='hidden' name='YearId' value='$YearId'>";
    echo "<input type='hidden' name='Semester' value='$Semester'>";
    echo "<input type='hidden' name='PrgName' value='$PrgName'>";
    echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
    echo "<input type='hidden' name='action' value='completeSemester'>";
    if ($isSemesterCompleted == 1) {
        echo "<button type='submit' class='disBtn' disabled>تم الإتمام</button>";
    } else {
        echo "<button type='submit' class='okBtn'>اعتماد</button>";
    }
    echo "</form>";
    echo "</div></center>";
}

//****************************************************************************************
//sec:Save Register Complaint
//****************************************************************************************
if ($mode == "saveRegisterComplaint") {
	$complaintReason = isset($_POST['complaintReason']) ? trim($_POST['complaintReason']) : "";
	if (!isset($StId) || $StId == "" || !isset($CrsId) || $CrsId == "" || $complaintReason == "") {
		$errorMessage = "يرجى ملء جميع الحقول المطلوبة!";
		$mode = "registerComplaint";
	} else {
		$complaintDate = date('Y-m-d');
		$q = "INSERT INTO complaints (complaintStudentId, complaintYearId, complaintCourseId, complaintReason, complaintDate) VALUES (?, ?, ?, ?, ?)";
		if ($stmt = mysqli_prepare($dbc, $q)) {
			if (mysqli_stmt_bind_param($stmt, "iiiss", $StId, $YearId, $CrsId, $complaintReason, $complaintDate)) {
				if (mysqli_stmt_execute($stmt)) {
					$infoMessage = "تم تسجيل التظلم بنجاح";
					$StId = "";
					$CrsId = "";
					$complaintReason = "";
				} else {
					$errorMessage = "حدث خطأ أثناء تسجيل التظلم!";
				}
			}
			mysqli_stmt_close($stmt);
		}
		$mode = "registerComplaint";
	}
}

//****************************************************************************************
//sec:Register Complaint form
//****************************************************************************************
if ($mode == "registerComplaint") {
	echo "<center>";
	if ($errorMessage != "") {
		echo "<div class='errorMessages'>$errorMessage</div><br>";
	}
	if ($infoMessage != "") {
		if ($infoMessage == "تم تسجيل التظلم بنجاح") {
			echo "
			<div id='successPopup' style='position:fixed; top:20px; left:50%; transform:translateX(-50%); background-color:#4CAF50; color:white; padding:15px 30px; border-radius:5px; font-size:18px; font-weight:bold; z-index:9999; box-shadow: 0 4px 8px rgba(0,0,0,0.2); direction:rtl; text-align:center;'>
				تم تسجيل التظلم بنجاح
			</div>
			<script>
				setTimeout(function() {
					var popup = document.getElementById('successPopup');
					if (popup) {
						popup.style.transition = 'opacity 0.3s ease';
						popup.style.opacity = '0';
						setTimeout(function() {
							popup.parentNode.removeChild(popup);
						}, 300);
					}
				}, 1000);
			</script>
			";
		} else {
			echo "<div class='infoMessages'>$infoMessage</div><br>";
		}
	}
	echo "<h3>تسجيل تظلم - الدفعة: $PrgName - $YearDesc</h3>";
	echo "<form method='post'>";
	echo "<input type='hidden' name='mode' value='registerComplaint'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='PrgName' value='$PrgName'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
	echo "<table>";

	// Student dropdown
	$qq_st = "SELECT Students.StID, Students.StName FROM Students INNER JOIN ProgramStudents ON Students.StID = ProgramStudents.StID WHERE ProgramStudents.PrgId=? AND ProgramStudents.YearId=? ORDER BY Students.StName";
	echo "<tr><td style='width:200px;'>الطالب:</td><td><div class='input-container'>";
	echo "<select class='input-field' name='StId' onchange='this.form.submit()' style='width:400px;'>";
	echo "<option value=''>-- اختر الطالب --</option>";
	if ($stmt_st = mysqli_prepare($dbc, $qq_st)) {
		if (mysqli_stmt_bind_param($stmt_st, "ii", $PrgId, $YearId)) {
			if (mysqli_stmt_execute($stmt_st)) {
				if (mysqli_stmt_bind_result($stmt_st, $st_StId, $st_StName)) {
					while (mysqli_stmt_fetch($stmt_st)) {
						$sel = (isset($StId) && $StId == $st_StId) ? " selected" : "";
						echo "<option value='$st_StId'$sel>$st_StName</option>";
					}
				}
			}
		}
		mysqli_stmt_close($stmt_st);
	}
	echo "</select></div></td></tr>";

	// Course dropdown - courses belonging to the selected student
	echo "<tr><td style='width:200px;'>المادة:</td><td><div class='input-container'>";
	echo "<select class='input-field' name='CrsId' style='width:400px;'><option value=''>-- اختر المادة --</option>";
	if (isset($StId) && $StId != "") {
		$qq_cr = "SELECT programstudentscourses.CrsId, CoursesGuide.CrsCode, CoursesGuide.CrsName FROM programstudentscourses INNER JOIN CoursesGuide ON programstudentscourses.CrsId = CoursesGuide.CrsId WHERE programstudentscourses.PrgId=? AND programstudentscourses.YearId=? AND programstudentscourses.StId=? ORDER BY CoursesGuide.CrsCode";
		if ($stmt_cr = mysqli_prepare($dbc, $qq_cr)) {
			if (mysqli_stmt_bind_param($stmt_cr, "iii", $PrgId, $YearId, $StId)) {
				if (mysqli_stmt_execute($stmt_cr)) {
					if (mysqli_stmt_bind_result($stmt_cr, $cr_CrsId, $cr_CrsCode, $cr_CrsName)) {
						while (mysqli_stmt_fetch($stmt_cr)) {
							$sel = (isset($CrsId) && $CrsId == $cr_CrsId) ? " selected" : "";
							echo "<option value='$cr_CrsId'$sel>$cr_CrsName</option>";
						}
					}
				}
			}
			mysqli_stmt_close($stmt_cr);
		}
	} else {
		echo "<option value='' disabled>-- اختر الطالب أولاً --</option>";
	}
	echo "</select></div></td></tr>";

	// Complaint reason text area
	echo "<tr><td style='width:200px;'>التظلم بخصوص:</td><td><div class='input-container'>";
	echo "<textarea class='input-field' name='complaintReason' rows='5' style='width:100%; resize:vertical;'>";
	if (isset($complaintReason) && $complaintReason != "") {
		echo htmlspecialchars($complaintReason);
	}
	echo "</textarea></div></td></tr>";

	echo "</table>";

	echo "<div class='frmButtons'>";
	echo "<button type='submit' class='savBtn' name='mode' value='saveRegisterComplaint'> حفظ </button> ";
	echo "<button type='submit' class='cnlBtn' name='mode' value='complaints'> تراجع </button>";
	echo "</div>";
	echo "</form>";
	echo "</center>";
}

//****************************************************************************************
//sec:Complaints page for a specific batch
//****************************************************************************************
if ($mode == "complaints") {
	echo "<center>";
	echo "<h3>تظلمات الدفعة: $PrgName - $YearDesc</h3>";
	echo "<br>";
	$numberOfButtons = 3;
	$buttonCellWidth = $numberOfButtons * 160;
	$buttonCellWidth .= "px";
	echo "<div style='width: $buttonCellWidth; display:flex; justify-content:center; gap:15px;'>";
	echo "<form method='post' style='display:inline;'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='PrgName' value='$PrgName'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
	echo "<button type='submit' class='addBtn' name='mode' value='registerComplaint'>تسجيل التظلمات</button>";
	echo "</form>";
	echo "<form method='post' style='display:inline;'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='PrgName' value='$PrgName'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
	echo "<button type='submit' class='edtBtn' name='mode' value='replyComplaint'>الرد علي التظلمات</button>";
	echo "</form>";
	echo "<form method='post' style='display:inline;'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='PrgName' value='$PrgName'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
	echo "<button type='submit' class='viewBtn' name='mode' value='complaintReports'>تقارير التظلمات</button>";
	echo "</form>";
	echo "</div>";
	echo "<br><br>";
	echo "<form method='post'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='PrgName' value='$PrgName'>";
	echo "<button type='submit' class='cnlBtn' name='mode' value='batchList'>العودة</button>";
	echo "</form>";
	echo "</center>";
}

//****************************************************************************************
//sec:Save Reply Complaint
//****************************************************************************************
if ($mode == "saveReplyComplaint") {
	$complaintId = isset($_POST['complaintId']) ? intval($_POST['complaintId']) : 0;
	$complaintResponse = isset($_POST['complaintResponse']) ? trim($_POST['complaintResponse']) : "";
	if ($complaintId > 0 && $complaintResponse != "") {
		$responseDate = date('Y-m-d');
		$q = "UPDATE complaints SET complaintResponse = ?, complaintResponseDate = ? WHERE complaintId = ?";
		if ($stmt = mysqli_prepare($dbc, $q)) {
			if (mysqli_stmt_bind_param($stmt, "ssi", $complaintResponse, $responseDate, $complaintId)) {
				if (mysqli_stmt_execute($stmt)) {
					$infoMessage = "تم حفظ الرد بنجاح";
				} else {
					$errorMessage = "حدث خطأ أثناء حفظ الرد!";
				}
			}
			mysqli_stmt_close($stmt);
		}
	} else {
		$errorMessage = "الرجاء كتابة الرد!";
	}
	$mode = "replyComplaint";
}

//****************************************************************************************
//sec:Reply Complaint List & Form
//****************************************************************************************
if ($mode == "replyComplaint") {
	echo "<center>";
	if ($errorMessage != "") {
		echo "<div class='errorMessages'>$errorMessage</div><br>";
	}
	if ($infoMessage != "") {
		if ($infoMessage == "تم حفظ الرد بنجاح") {
			echo "
			<div id='successPopup' style='position:fixed; top:20px; left:50%; transform:translateX(-50%); background-color:#4CAF50; color:white; padding:15px 30px; border-radius:5px; font-size:18px; font-weight:bold; z-index:9999; box-shadow: 0 4px 8px rgba(0,0,0,0.2); direction:rtl; text-align:center;'>
				تم حفظ الرد بنجاح
			</div>
			<script>
				setTimeout(function() {
					var popup = document.getElementById('successPopup');
					if (popup) {
						popup.style.transition = 'opacity 0.3s ease';
						popup.style.opacity = '0';
						setTimeout(function() {
							popup.parentNode.removeChild(popup);
						}, 300);
					}
				}, 1000);
			</script>
			";
		} else {
			echo "<div class='infoMessages'>$infoMessage</div><br>";
		}
	}

	echo "<h3>الرد علي التظلمات - الدفعة: $PrgName - $YearDesc</h3><br>";

	$q = "SELECT c.complaintId, s.StName, y.YearDesc, cg.CrsName, c.complaintReason, c.complaintDate
		  FROM complaints c
		  INNER JOIN Students s ON c.complaintStudentId = s.StID
		  INNER JOIN Years y ON c.complaintYearId = y.YearId
		  INNER JOIN CoursesGuide cg ON c.complaintCourseId = cg.CrsId
		  INNER JOIN ProgramStudents ps ON s.StID = ps.StID AND ps.YearId = c.complaintYearId
		  WHERE ps.PrgId = ? AND c.complaintYearId = ? AND (c.complaintResponse IS NULL OR c.complaintResponse = '')
		  ORDER BY c.complaintDate DESC";

	$hasComplaints = false;

	if ($stmt = mysqli_prepare($dbc, $q)) {
		if (mysqli_stmt_bind_param($stmt, "ii", $PrgId, $YearId)) {
			if (mysqli_stmt_execute($stmt)) {
				if (mysqli_stmt_bind_result($stmt, $c_id, $stName, $yrDesc, $crsName, $reason, $cDate)) {
					while (mysqli_stmt_fetch($stmt)) {
						$hasComplaints = true;
						echo "<table class='masterTable' style='width: 80%; margin: auto; direction: rtl;'>";
						echo "<tr class='header'><th>الاسم</th><th>الدفعة</th><th>المادة</th></tr>";
						echo "<tr><td>$stName</td><td>$yrDesc</td><td>$crsName</td></tr>";
						echo "<tr><td colspan='3' style='text-align: right; background-color: #f9f9f9; padding: 10px;'><strong>التظلم:</strong> " . htmlspecialchars($reason) . " <span style='font-size: 0.8em; color: #888;'>($cDate)</span></td></tr>";
						echo "<tr><td colspan='3' style='text-align: right; background-color: #f2f2f2; padding: 10px;'>";
						echo "<form method='post' style='margin: 0;'>";
						echo "<input type='hidden' name='mode' value='saveReplyComplaint'>";
						echo "<input type='hidden' name='complaintId' value='$c_id'>";
						echo "<input type='hidden' name='PrgId' value='$PrgId'>";
						echo "<input type='hidden' name='PrgName' value='$PrgName'>";
						echo "<input type='hidden' name='YearId' value='$YearId'>";
						echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
						echo "<strong>الرد على التظلم:</strong><br>";
						echo "<textarea name='complaintResponse' rows='3' style='width: 100%; margin-top: 5px; resize: vertical;' required></textarea><br>";
						echo "<button type='submit' class='savBtn' style='margin-top: 5px;'>حفظ الرد</button>";
						echo "</form>";
						echo "</td></tr>";
						echo "</table><br><br>";
					}
				}
			}
		}
		mysqli_stmt_close($stmt);
	}

	if (!$hasComplaints) {
		echo "<p>لا توجد تظلمات بحاجة للرد حالياً.</p><br>";
	}

	echo "<form method='post'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='PrgName' value='$PrgName'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
	echo "<button type='submit' class='cnlBtn' name='mode' value='complaints'>العودة</button>";
	echo "</form>";
	echo "</center>";
}

//****************************************************************************************
//sec:Complaint Reports
//****************************************************************************************
if ($mode == "complaintReports") {
	echo "<center>";
	echo "<h3>تقارير التظلمات - الدفعة: $PrgName - $YearDesc</h3><br>";

	// --- SECTION 1: Unanswered Complaints ---
	echo "<h4>تظلمات قيد الانتظار (لم يتم الرد عليها)</h4>";
	
	$q1 = "SELECT s.StName, cg.CrsName, c.complaintReason, c.complaintDate
		   FROM complaints c
		   INNER JOIN Students s ON c.complaintStudentId = s.StID
		   INNER JOIN CoursesGuide cg ON c.complaintCourseId = cg.CrsId
		   INNER JOIN ProgramStudents ps ON s.StID = ps.StID AND ps.YearId = c.complaintYearId
		   WHERE ps.PrgId = ? AND c.complaintYearId = ? AND (c.complaintResponse IS NULL OR c.complaintResponse = '')
		   ORDER BY c.complaintDate DESC";

	$hasUnanswered = false;
	if ($stmt1 = mysqli_prepare($dbc, $q1)) {
		if (mysqli_stmt_bind_param($stmt1, "ii", $PrgId, $YearId)) {
			if (mysqli_stmt_execute($stmt1)) {
				if (mysqli_stmt_bind_result($stmt1, $stName, $crsName, $reason, $cDate)) {
					echo "<table class='masterTable' style='width: 90%; margin: auto; direction: rtl;'>";
					echo "<tr class='header'><th style='width: 25%;'>الاسم</th><th style='width: 20%;'>المادة</th><th style='width: 15%;'>تاريخ التظلم</th><th>سبب التظلم</th></tr>";
					while (mysqli_stmt_fetch($stmt1)) {
						$hasUnanswered = true;
						echo "<tr><td>$stName</td><td>$crsName</td><td>$cDate</td><td style='text-align: right;'>" . htmlspecialchars($reason) . "</td></tr>";
					}
					echo "</table>";
				}
			}
		}
		mysqli_stmt_close($stmt1);
	}
	if (!$hasUnanswered) {
		echo "<p>لا توجد تظلمات قيد الانتظار.</p>";
	}
	echo "<br><br>";

	// --- SECTION 2: Answered Complaints ---
	echo "<h4>تظلمات تم الرد عليها</h4>";

	$q2 = "SELECT s.StName, cg.CrsName, c.complaintReason, c.complaintDate, c.complaintResponse, c.complaintResponseDate
		   FROM complaints c
		   INNER JOIN Students s ON c.complaintStudentId = s.StID
		   INNER JOIN CoursesGuide cg ON c.complaintCourseId = cg.CrsId
		   INNER JOIN ProgramStudents ps ON s.StID = ps.StID AND ps.YearId = c.complaintYearId
		   WHERE ps.PrgId = ? AND c.complaintYearId = ? AND (c.complaintResponse IS NOT NULL AND c.complaintResponse <> '')
		   ORDER BY c.complaintResponseDate DESC";

	$hasAnswered = false;
	if ($stmt2 = mysqli_prepare($dbc, $q2)) {
		if (mysqli_stmt_bind_param($stmt2, "ii", $PrgId, $YearId)) {
			if (mysqli_stmt_execute($stmt2)) {
				if (mysqli_stmt_bind_result($stmt2, $stName, $crsName, $reason, $cDate, $response, $rDate)) {
					echo "<table class='masterTable' style='width: 90%; margin: auto; direction: rtl;'>";
					echo "<tr class='header'><th style='width: 20%;'>الاسم</th><th style='width: 15%;'>المادة</th><th style='width: 12%;'>تاريخ التظلم</th><th>سبب التظلم</th><th>الرد</th><th style='width: 12%;'>تاريخ الرد</th></tr>";
					while (mysqli_stmt_fetch($stmt2)) {
						$hasAnswered = true;
						echo "<tr><td>$stName</td><td>$crsName</td><td>$cDate</td><td style='text-align: right;'>" . htmlspecialchars($reason) . "</td><td style='text-align: right;'>" . htmlspecialchars($response) . "</td><td>$rDate</td></tr>";
					}
					echo "</table>";
				}
			}
		}
		mysqli_stmt_close($stmt2);
	}
	if (!$hasAnswered) {
		echo "<p>لا توجد تظلمات تم الرد عليها بعد.</p>";
	}
	echo "<br><br>";

	// Back Button
	echo "<form method='post'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='PrgName' value='$PrgName'>";
	echo "<input type='hidden' name='YearId' value='$YearId'>";
	echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
	echo "<button type='submit' class='cnlBtn' name='mode' value='complaints'>العودة</button>";
	echo "</form>";
	echo "</center>";
}

//****************************************************************************************
//sec:Batch Reports
//****************************************************************************************


//****************************************************************************************
//sec:Batch Reports
//****************************************************************************************
if ($mode == "batchReports") {
    $subMode = $_POST['subMode'] ?? '';
    echo "<center>";
    echo "<h3>التقارير للدفعة: $PrgName - $YearDesc</h3><br>";
    
    if ($subMode == "") {
        // Show the three buttons
        echo "<div style='display: flex; gap: 15px; justify-content: center; margin-top: 20px;'>";
        
        echo "<form method='post'>";
        echo "<input type='hidden' name='PrgId' value='$PrgId'>";
        echo "<input type='hidden' name='PrgName' value='$PrgName'>";
        echo "<input type='hidden' name='YearId' value='$YearId'>";
        echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
        echo "<input type='hidden' name='mode' value='batchReports'>";
        echo "<input type='hidden' name='subMode' value='registrationStatus'>";
        echo "<button type='submit' class='viewBtn' style='font-size: 16px; padding: 10px 20px;'>موقف التسجيل</button>";
        echo "</form>";
        
        echo "<form method='post'>";
        echo "<input type='hidden' name='PrgId' value='$PrgId'>";
        echo "<input type='hidden' name='PrgName' value='$PrgName'>";
        echo "<input type='hidden' name='YearId' value='$YearId'>";
        echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
        echo "<input type='hidden' name='mode' value='batchReports'>";
        echo "<input type='hidden' name='subMode' value='failingList'>";
        echo "<button type='submit' class='viewBtn' style='font-size: 16px; padding: 10px 20px;'>كشف الراسبين</button>";
        echo "</form>";
        
        echo "<form method='post'>";
        echo "<input type='hidden' name='PrgId' value='$PrgId'>";
        echo "<input type='hidden' name='PrgName' value='$PrgName'>";
        echo "<input type='hidden' name='YearId' value='$YearId'>";
        echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
        echo "<input type='hidden' name='mode' value='batchReports'>";
        echo "<input type='hidden' name='subMode' value='droppedList'>";
        echo "<button type='submit' class='viewBtn' style='font-size: 16px; padding: 10px 20px;'>كشف المتسربين</button>";
        echo "</form>";
        
        echo "</div>";
        echo "<br><br>";
        
        echo "<form method='post'>";
        echo "<input type='hidden' name='PrgId' value='$PrgId'>";
        echo "<input type='hidden' name='PrgName' value='$PrgName'>";
        echo "<button type='submit' class='cnlBtn' name='mode' value='batchList'>العودة للدفعة</button>";
        echo "</form>";
    } else {
        if ($subMode == "registrationStatus") {
            echo "<h4>تقرير موقف التسجيل</h4>";
            
            // Total students in the batch (ProgramStudents)
            $totalBatchStudents = 0;
            $q_total = "SELECT COUNT(*) FROM ProgramStudents WHERE PrgId = ? AND YearId = ?";
            if ($stmt = mysqli_prepare($dbc, $q_total)) {
                if (mysqli_stmt_bind_param($stmt, "ii", $PrgId, $YearId)) {
                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_bind_result($stmt, $totalBatchStudents);
                        mysqli_stmt_fetch($stmt);
                    }
                }
                mysqli_stmt_close($stmt);
            }

            // Total students registered for courses/semester in the batch (programstudentscourses)
            $totalRegisteredSemester = 0;
            $q_reg = "SELECT COUNT(DISTINCT StId) FROM programstudentscourses WHERE PrgId = ? AND YearId = ?";
            if ($stmt = mysqli_prepare($dbc, $q_reg)) {
                if (mysqli_stmt_bind_param($stmt, "ii", $PrgId, $YearId)) {
                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_bind_result($stmt, $totalRegisteredSemester);
                        mysqli_stmt_fetch($stmt);
                    }
                }
                mysqli_stmt_close($stmt);
            }

            // Display statistics
            echo "<table class='masterTable' style='width: 50%; margin: auto; direction: rtl;'>";
            echo "<tr class='header'><th colspan='2' style='text-align: center;'>إحصائيات الدفعة</th></tr>";
            echo "<tr><td style='width: 60%; font-weight: bold;'>عدد طلاب الدفعة (المقبولين):</td><td style='text-align: center; font-weight: bold;'>$totalBatchStudents</td></tr>";
            echo "<tr><td style='font-weight: bold;'>عدد الطلاب المسجلين للمواد/الفصول:</td><td style='text-align: center; font-weight: bold;'>$totalRegisteredSemester</td></tr>";
            echo "</table><br><br>";

            // Enrolled list
            echo "<h4>الطلاب المقبولين بالدفعة</h4>";
            $q_enrolled = "SELECT s.StName, s.StNationalID, s.StMaritimePassportNo, s.StTels, s.StWhatsApp, s.StCompany 
                           FROM ProgramStudents ps
                           INNER JOIN Students s ON ps.StId = s.StID
                           WHERE ps.PrgId = ? AND ps.YearId = ?
                           ORDER BY s.StName ASC";

            $hasEnrolled = false;
            if ($stmt = mysqli_prepare($dbc, $q_enrolled)) {
                if (mysqli_stmt_bind_param($stmt, "ii", $PrgId, $YearId)) {
                    if (mysqli_stmt_execute($stmt)) {
                        if (mysqli_stmt_bind_result($stmt, $stName, $nationalId, $passport, $tels, $whatsapp, $company)) {
                            echo "<table class='masterTable' style='width: 90%; margin: auto; direction: rtl;'>";
                            echo "<tr class='header'>
                                    <th style='width: 5%;'>م</th>
                                    <th style='width: 30%;'>الاسم</th>
                                    <th style='width: 15%;'>رقم البطاقة</th>
                                    <th style='width: 15%;'>رقم الجواز</th>
                                    <th style='width: 15%;'>الهاتف</th>
                                    <th style='width: 10%;'>واتساب</th>
                                  </tr>";
                            $i = 1;
                            while (mysqli_stmt_fetch($stmt)) {
                                $hasEnrolled = true;
                                echo "<tr>
                                        <td style='text-align: center;'>$i</td>
                                        <td>$stName</td>
                                        <td>$nationalId</td>
                                        <td>$passport</td>
                                        <td>$tels</td>
                                        <td>$whatsapp</td>
                                      </tr>";
                                $i++;
                            }
                            echo "</table>";
                        }
                    }
                }
                mysqli_stmt_close($stmt);
            }
            if (!$hasEnrolled) {
                echo "<p>لا يوجد طلاب مقبولين في هذه الدفعة حالياً.</p>";
            }
            
        } elseif ($subMode == "failingList") {
            echo "<h4>كشف الراسبين (لكل مادة)</h4>";
            
            $q_failing = "SELECT s.StName, cg.CrsCode, cg.CrsName, psc.Score, psc.Semester
                          FROM programstudentscourses psc
                          INNER JOIN Students s ON psc.StID = s.StID
                          INNER JOIN CoursesGuide cg ON psc.CrsId = cg.CrsId
                          WHERE psc.PrgId = ? AND psc.YearId = ? AND psc.Score <= 59
                          ORDER BY s.StName, cg.CrsName";
            
            $hasFailing = false;
            if ($stmt = mysqli_prepare($dbc, $q_failing)) {
                if (mysqli_stmt_bind_param($stmt, "ii", $PrgId, $YearId)) {
                    if (mysqli_stmt_execute($stmt)) {
                        if (mysqli_stmt_bind_result($stmt, $stName, $crsCode, $crsName, $score, $semester)) {
                            echo "<table class='masterTable' style='width: 90%; margin: auto; direction: rtl;'>";
                            echo "<tr class='header'>
                                    <th style='width: 5%; text-align: center;'>م</th>
                                    <th style='width: 35%; text-align: right; padding-right: 10px;'>اسم الطالب</th>
                                    <th style='width: 15%; text-align: center;'>كود المادة</th>
                                    <th style='width: 25%; text-align: right; padding-right: 10px;'>المادة</th>
                                    <th style='width: 10%; text-align: center;'>الفصل</th>
                                    <th style='width: 10%; text-align: center;'>الدرجة</th>
                                  </tr>";
                            $i = 1;
                            while (mysqli_stmt_fetch($stmt)) {
                                $hasFailing = true;
                                echo "<tr>
                                        <td style='text-align: center;'>$i</td>
                                        <td style='text-align: right; padding-right: 10px;'>$stName</td>
                                        <td style='text-align: center;'>$crsCode</td>
                                        <td style='text-align: right; padding-right: 10px;'>$crsName</td>
                                        <td style='text-align: center;'>$semester</td>
                                        <td style='text-align: center; color: red; font-weight: bold;'>$score</td>
                                      </tr>";
                                $i++;
                            }
                            echo "</table>";
                        }
                    }
                }
                mysqli_stmt_close($stmt);
            }
            
            if (!$hasFailing) {
                echo "<p>لا يوجد طلاب راسبين في أي مادة لهذه الدفعة حالياً.</p>";
            }
            
        } elseif ($subMode == "droppedList") {
            echo "<h4>كشف المتسربين (سجلوا في الفصل الأول ولم يسجلوا في الثاني)</h4>";
            
            $q_dropped = "SELECT DISTINCT s.StName, s.StNationalID, s.StMaritimePassportNo, s.StTels, s.StWhatsApp, ps.RegistrationNumber, co.cmpName
                          FROM programstudentscourses psc1
                          INNER JOIN Students s ON psc1.StID = s.StID
                          INNER JOIN ProgramStudents ps ON s.StID = ps.StId AND ps.PrgId = psc1.PrgId AND ps.YearId = psc1.YearId
                          LEFT JOIN companies co ON s.StCompany = co.cmpId
                          WHERE psc1.PrgId = ? AND psc1.YearId = ? AND psc1.Semester = 1
                            AND psc1.StID NOT IN (
                                SELECT psc2.StID 
                                FROM programstudentscourses psc2 
                                WHERE psc2.PrgId = ? AND psc2.YearId = ? AND psc2.Semester = 2
                            )
                          ORDER BY s.StName ASC";
            
            $hasDropped = false;
            if ($stmt = mysqli_prepare($dbc, $q_dropped)) {
                if (mysqli_stmt_bind_param($stmt, "iiii", $PrgId, $YearId, $PrgId, $YearId)) {
                    if (mysqli_stmt_execute($stmt)) {
                        if (mysqli_stmt_bind_result($stmt, $stName, $nationalId, $passport, $tels, $whatsapp, $regNum, $compName)) {
                            echo "<table class='masterTable' style='width: 95%; margin: auto; direction: rtl;'>";
                            echo "<tr class='header'>
                                    <th style='width: 5%; text-align: center;'>م</th>
                                    <th style='width: 12%; text-align: center;'>رقم التسجيل</th>
                                    <th style='width: 25%; text-align: right; padding-right: 10px;'>اسم الطالب</th>
                                    <th style='width: 15%; text-align: center;'>الرقم القومي</th>
                                    <th style='width: 13%; text-align: center;'>رقم الجواز</th>
                                    <th style='width: 10%; text-align: center;'>جهة العمل</th>
                                    <th style='width: 10%; text-align: center;'>الهاتف</th>
                                    <th style='width: 10%; text-align: center;'>واتساب</th>
                                  </tr>";
                            $i = 1;
                            while (mysqli_stmt_fetch($stmt)) {
                                $hasDropped = true;
                                echo "<tr>
                                        <td style='text-align: center;'>$i</td>
                                        <td style='text-align: center;'>" . htmlspecialchars($regNum ?? '') . "</td>
                                        <td style='text-align: right; padding-right: 10px;'>" . htmlspecialchars($stName ?? '') . "</td>
                                        <td style='text-align: center;'>" . htmlspecialchars($nationalId ?? '') . "</td>
                                        <td style='text-align: center;'>" . htmlspecialchars($passport ?? '') . "</td>
                                        <td style='text-align: center;'>" . htmlspecialchars($compName ?? '') . "</td>
                                        <td style='text-align: center;'>" . htmlspecialchars($tels ?? '') . "</td>
                                        <td style='text-align: center;'>" . htmlspecialchars($whatsapp ?? '') . "</td>
                                      </tr>";
                                $i++;
                            }
                            echo "</table>";
                        }
                    }
                }
                mysqli_stmt_close($stmt);
            }
            
            if (!$hasDropped) {
                echo "<p style='text-align: center; color: #666;'>لا يوجد طلاب متسربين في هذه الدفعة حالياً.</p>";
            }
        }
        
        echo "<br><br>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='PrgId' value='$PrgId'>";
        echo "<input type='hidden' name='PrgName' value='$PrgName'>";
        echo "<input type='hidden' name='YearId' value='$YearId'>";
        echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
        echo "<input type='hidden' name='mode' value='batchReports'>";
        echo "<button type='submit' class='cnlBtn'>العودة للتقارير</button>";
        echo "</form>";
    }
    echo "</center>";
}

//****************************************************************************************
//sec:list the batch record list
//****************************************************************************************
if ($mode == "batchList") {
    if ($errorMessage != "") {
        echo "<div class='errorMessages'>$errorMessage</div>";
        echo "<br><br>";
    }
    if ($infoMessage != "") {
        echo "<div class='infoMessages'>$infoMessage</div>";
        echo "<br><br>";
    }
	echo "<h3>دفعات: $PrgName</h3>";
    //نموذج إضافة دفعة جديدة
    echo "<div style='width: 110px; margin: auto;'><form method='post'>";
	echo "<input type='hidden' name='PrgId' value='$PrgId'>";
	echo "<input type='hidden' name='PrgName' value='$PrgName'>";	
    echo "<button type='submit' class='addBtn' name='mode' value='addBatch'> دفعة جديدة</button>";
    echo "</form></div><br>";

    $numberOfButtons = 8;
    $buttonCellWidth = $numberOfButtons * 115;
    $buttonCellWidth .= "px";

   //filter list form 
   	echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث...'>";
  	echo "<table id='masterTable'><tr class='header'><th> الدفعة </th><th style='width: $buttonCellWidth'>&nbsp;</th></tr>";
   	$q="SELECT `YearId`,`YearDesc` FROM `Years` WHERE `YearType`='D' ORDER BY `YearDesc`";
   	if ($stmt = mysqli_prepare($dbc, $q)){
	   		if(mysqli_stmt_execute($stmt)){
		   		if(mysqli_stmt_bind_result($stmt, $YearId, $YearDesc)){
			   		while(mysqli_stmt_fetch($stmt)){
						echo "<tr>";
						echo "<td> $YearDesc </td>";
						echo "<td style='width: $buttonCellWidth; text-align: left;'>";
						echo "<form method='post'>";
						echo "<input type='hidden' name='PrgId' value='$PrgId'>";
						echo "<input type='hidden' name='PrgName' value='$PrgName'>";	
						echo "<input type='hidden' name='YearId' value='$YearId'>";
						echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";						
						echo "<button type='submit' class='viewBtn' name='mode' value='viewBatch'>بيانات الدفعه</button> ";
						echo "<button type='submit' class='grpBtn' name='mode' value='studentAdmission'>الطلاب</button> ";
						echo "<button type='submit' class='edtBtn' name='mode' value='ManageSemester'>الفصول الدراسية</button> ";
						echo "<button type='submit' class='edtBtn' name='mode' value='studentOrder'>ترتيب الدفعة</button> ";						
						echo "<button type='submit' class='navBtn' name='mode' value='addStudentCourse'>اضافة مادة لطالب</button> ";
						echo "<button type='submit' class='delBtn' name='mode' value='removeStudentCourse'>الغاء المادة لطالب</button> ";
						echo "<button type='submit' class='viewBtn' name='mode' value='complaints'>التظلمات</button> ";
						echo "<button type='submit' class='viewBtn' name='mode' value='batchReports'>التقارير</button> ";
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

	$numberOfButtons = 1;
    $buttonCellWidth = $numberOfButtons * 115;
    $buttonCellWidth .= "px";

   //filter list form 
   echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث...'>";
   echo "<table id='masterTable'><tr class='header'><th> الدبلومة </th><th style='width: $buttonCellWidth'>&nbsp;</th></tr>";
   $q="SELECT PrgId, PrgName FROM Programs where semesterCount <> 0 ORDER BY PrgName";
   //echo $q;
   if ($stmt = mysqli_prepare($dbc, $q)){
	   if(mysqli_stmt_execute($stmt)){
		   if(mysqli_stmt_bind_result($stmt, $PrgId , $PrgName)){
			   while(mysqli_stmt_fetch($stmt)){
				   echo "<tr>";
				   echo "<td> $PrgName </td>";
				   echo "<td style='width: $buttonCellWidth;'>";
				   echo "<form method='post'>";
				   echo "<input type='hidden' name='PrgId' value='$PrgId'>";
				   echo "<button type='submit' class='grpBtn' name='mode' value='batchList'>الدفعات</button> ";
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
//sec:Import Students from Excel
//*****************************************************************************************
if ($mode == "importStudents") {
    echo "<h3>استيراد طلاب للدفعة: $PrgName - $YearDesc</h3>";
    echo "<form method='post' enctype='multipart/form-data' style='max-width:600px;margin:auto;text-align:right;'>";
    echo "<p>يرجى اختيار ملف بصيغة <b>.xlsx</b>. يجب أن يحتوي الملف على البيانات بالترتيب التالي :</p>";
    echo "<ol style='padding-right:20px;'>";
    echo "<li>الاسم بالعربية (مطلوب)</li><li>الاسم بالإنجليزية</li><li>العنوان</li><li>الهواتف</li><li>الواتساب</li><li>رقم/كود جهة العمل (رقم)</li><li>الرقم القومي / الهوية</li><li>تاريخ الميلاد (YYYY-MM-DD)</li><li>رقم/كود الجنسية (رقم)</li><li>رقم جواز السفر</li>";
    echo "</ol>";
    echo "<input type='hidden' name='PrgId' value='$PrgId'>";
    echo "<input type='hidden' name='YearId' value='$YearId'>";
    echo "<input type='hidden' name='PrgName' value='$PrgName'>";
    echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
    echo "<input type='hidden' name='mode' value='processImportStudents'>";
    echo "<div class='input-container' style='padding:15px; border:1px dashed #ccc; display:flex; align-items:center; gap:10px;'><input type='file' name='excel_file' accept='.xlsx' required id='excel_file_input'><button type='button' class='cnlBtn' onclick=\"document.getElementById('excel_file_input').value='';\" style='white-space:nowrap;'>إلغاء</button></div><br>";
    echo "<div class='frmButtons'>";
    echo "<button type='submit' class='savBtn' style='background-color:#28a745;'>رفع </button> ";
    echo "<button type='submit' class='cnlBtn' name='mode' value='studentAdmission' formnovalidate>العودة</button>";
    echo "</div>";
    echo "</form>";
}

if ($mode == "processImportStudents") {
    echo "<h3>نتيجة الاستيراد للدفعة: $PrgName - $YearDesc</h3>";
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
require_once __DIR__ . '/../include/SimpleXLSX.php';
        if ($xlsx = Shuchkin\SimpleXLSX::parse($_FILES['excel_file']['tmp_name'])) {
            $rows = $xlsx->rows();
            $importedCount = 0;
            $failedCount = 0;
            $errors = [];
            
            // Query to insert student
            $q_insert_student = "INSERT INTO Students (`StName`, `StEname`, `StAddress`, `StTels`, `StWhatsApp`, `StCompany`, `StNationalID`, `StBDate`, `StNationality`, `StMaritimePassportNo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            // Query to enroll student
            $q_enroll = "INSERT INTO ProgramStudents (`StId`, `PrgId`, `YearId`, `RegistrationNumber`, `FINAL_GPA`) VALUES (?, ?, ?, ?, 0)";
            
            foreach ($rows as $index => $row) {
                // Check if row is completely empty
                if (empty(array_filter($row))) continue;
                
                // Skip header row: if column 5 (company) is not numeric it's a header
                if ($index === 0 && (!isset($row[5]) || !is_numeric($row[5]) || $row[5] == "")) {
                    continue; 
                }
                
                // Map columns
                $StName = trim($row[0] ?? '');
                $StEname = trim($row[1] ?? '');
                $StAddress = trim($row[2] ?? '');
                $StTels = trim($row[3] ?? '');
                $StWhatsApp = trim($row[4] ?? '');
                $StCompany = (int)($row[5] ?? 0);
                
                // Fix: National ID may come as scientific notation float (e.g. 2.90015E+13)
                // Convert to full integer string
                $rawNationalID = $row[6] ?? '';
                if (is_numeric($rawNationalID)) {
                    $StNationalID = number_format((float)$rawNationalID, 0, '.', '');
                } else {
                    $StNationalID = trim($rawNationalID);
                }
                
                // Handle Date: could be Excel serial number (int), text "YYYY-MM-DD", or text "DD-MM-YYYY"
                $StBDate = $row[7] ?? '';
                if (is_numeric($StBDate) && $StBDate > 1000) {
                    // Excel serial number -> convert to Y-m-d
                    $StBDate = gmdate("Y-m-d", (intval($StBDate) - 25569) * 86400);
                } elseif (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', trim($StBDate), $m)) {
                    // DD-MM-YYYY format -> convert to YYYY-MM-DD
                    $StBDate = $m[3] . '-' . str_pad($m[2],2,'0',STR_PAD_LEFT) . '-' . str_pad($m[1],2,'0',STR_PAD_LEFT);
                } else {
                    $StBDate = trim($StBDate);
                }
                
                $StNationality = (int)($row[8] ?? 0);
                $StMaritimePassportNo = trim($row[9] ?? '');
                
                if ($StName == '') continue; // Skip empty rows
                
                if ($stmt = mysqli_prepare($dbc, $q_insert_student)) {
                    mysqli_stmt_bind_param($stmt, "sssssissis", $StName, $StEname, $StAddress, $StTels, $StWhatsApp, $StCompany, $StNationalID, $StBDate, $StNationality, $StMaritimePassportNo);
                    if (mysqli_stmt_execute($stmt)) {
                        $newStId = mysqli_insert_id($dbc);
                        mysqli_stmt_close($stmt);
                        
                        // Enroll in batch
                        $RegistrationNumber = substr($YearDesc, 2) . str_pad($newStId, 3, '0', STR_PAD_LEFT);
                        if ($stmt_enroll = mysqli_prepare($dbc, $q_enroll)) {
                            mysqli_stmt_bind_param($stmt_enroll, "iiis", $newStId, $PrgId, $YearId, $RegistrationNumber);
                            if (!mysqli_stmt_execute($stmt_enroll)) {
                                $errors[] = "سطر " . ($index + 1) . " (" . htmlspecialchars($StName) . "): تم إنشاء الطالب لكن فشل تسجيله بالدفعة: " . mysqli_error($dbc);
                                $failedCount++;
                            } else {
                                $importedCount++;
                            }
                            mysqli_stmt_close($stmt_enroll);
                        }
                    } else {
                        $failedCount++;
                        $errors[] = "سطر " . ($index + 1) . " (" . htmlspecialchars($StName) . "): " . mysqli_error($dbc);
                        mysqli_stmt_close($stmt);
                    }
                }
            }
            
            echo "<div class='success'><p>تم استيراد $importedCount طالب بنجاح!</p></div>";
            if ($failedCount > 0) {
                echo "<div class='error'><p>فشل استيراد $failedCount طالب.</p>";
                foreach($errors as $e) { echo "<p>$e</p>"; }
                echo "</div>";
            }
        } else {
            echo "<div class='error'><p>خطأ في قراءة ملف الإكسيل: " . Shuchkin\SimpleXLSX::parseError() . "</p></div>";
        }
    } else {
        echo "<div class='error'><p>حدث خطأ أثناء رفع الملف. يرجى التأكد من اختيار ملف بصيغة .xlsx.</p></div>";
    }
    
    echo "<center><form method='post'>";
    echo "<input type='hidden' name='PrgId' value='$PrgId'>";
    echo "<input type='hidden' name='YearId' value='$YearId'>";
    echo "<input type='hidden' name='PrgName' value='$PrgName'>";
    echo "<input type='hidden' name='YearDesc' value='$YearDesc'>";
    echo "<button type='submit' class='addBtn' name='mode' value='studentAdmission'>العودة للدفعة</button>";
    echo "</form></center>";
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
