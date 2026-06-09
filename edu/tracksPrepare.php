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
if(($mode == "studentAdmission") or ($mode == "addStudent2Batch") or ($mode == "addSaveStudent") or ($mode=="editSaveٍStudent") or ($mode == "removeStudentFromBatch") or ($mode=="batchList")){
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

if($mode=="edit" or $mode=="view" or $mode=="deleteConfirm" or $mode=="batchList" or $mode=="viewBatch" or $mode =="addBatch" or $mode == "editBatch" or $mode=="deleteConfirmBatch" or $mode=="studentAdmission" or $mode == "studentOrder" or $mode == "studentOrderEnglish" or $mode == "addSemester" or $mode == "editSemester" or $mode == "ManageSemester" or $mode == "saveaddSemester" or $mode == "saveeditSemester" or $mode == "deleteSemester" or $mode=="registerStudentSemester" or $mode=="unregisterStudentSemester" or  $mode=="registeredCourses" or $mode == "courseScoreEntry" or $mode == "savecourseScoreEntry" or $mode=="showtranscript" or $mode == "PrintCourseScore"){
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
if($mode=="viewBatch" or $mode == "editBatch" or $mode=="deleteConfirmBatch" or $mode=="ManageSemester" or $mode == "addSemester" or $mode == "editSemester" or $mode == "saveaddSemester" or $mode == "saveeditSemester" or $mode == "deleteSemester" or $mode=="registerStudentSemester"  or $mode=="unregisterStudentSemester" or  $mode=="registeredCourses" or $mode == "courseScoreEntry" or $mode == "savecourseScoreEntry" or $mode=="showtranscript" or $mode == "PrintCourseScore" or $mode=="studentAdmission" or $mode == "studentOrder" or $mode == "studentOrderEnglish"){
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
            $errorMessage="Can not insert record";
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
	
	
      $q="INSERT INTO ProgramStudents (`StId`, `PrgId`, `YearId`, `RegistrationNumber`) VALUES (?, ?, ?, ?)";
//	  echo "<br>INSERT INTO ProgramStudents (`StId`, `PrgId`, `YearId`) VALUES (" .$StId ."," .$PrgId ."," .$YearId .")";
	  
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt,"iiis", $StId, $PrgId, $YearId,$RegistrationNumber)){

			if(!mysqli_stmt_execute($stmt)){
				$errorMessage="Can not insert record";
			}
        }
        mysqli_stmt_close($stmt);
      }
	  $mode="studentAdmission";	
	
}



//*****************************************************************************************
//sec:Un Register a specific student
//*****************************************************************************************
if ($mode == "removeStudentFromBatch"){
	
//	echo "Program ID =$PrgId - Program Name= $PrgName - Year ID = $YearId - Year Description =$YearDesc - Student ID = $StId<br>";
	
	
      $q="delete from ProgramStudents where (`StId`=? AND `PrgId`=? AND `YearId`=?)";
//	  echo "<br>INSERT INTO ProgramStudents (`StId`, `PrgId`, `YearId`) VALUES (" .$StId ."," .$PrgId ."," .$YearId .")";
	  
      if ($stmt = mysqli_prepare($dbc, $q)) {
        if(mysqli_stmt_bind_param($stmt,"iii", $StId, $PrgId, $YearId)){

			if(!mysqli_stmt_execute($stmt)){
				$errorMessage="Can not insert record";
			}
        }
        mysqli_stmt_close($stmt);
      }
	  $mode="studentAdmission";	
	
}
//*****************************************************************************************
//sec:Manage Semester for a specific batch
//*****************************************************************************************
if ($mode == "showtranscript") {

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
					echo "<div class='frmButtons'><button type='button' class='savBtn'  value='print' onclick='PrintDiv();'> طباعة </button>  <button type='submit' class='cnlBtn' name='mode' value='studentAdmission'>عودة</button></div>";					
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
					echo "<br>";
					echo "<table class='transcriptTable'>";
					echo "<tr><th width='15%' class='transcriptTableHeader'>Course Code</th><th width='50%' class='transcriptTableHeader'>Course Title</th><th width='7%' class='transcriptTableHeader'>CR. ATT.</th><th width='7%' class='transcriptTableHeader'>GR</th><th width='7%' class='transcriptTableHeader'>PTS</th><th width='7%' class='transcriptTableHeader'>CR. ACD.</th><th width='7%' class='transcriptTableHeader'>GPA</th></tr>";
					$header = 1;
				}
				if($Semester != $oldSemester){
					if($oldSemester != 0){
						echo "<tr><td class='transcriptTableNonCourses'></td><td class='transcriptTableCourses'></td><td  class='transcriptTableSemester'>$totalCredit</td><td  class='transcriptTableSemester'></td><td  class='transcriptTableSemester'>$DisplaytotalPoints</td><td  class='transcriptTableSemester'>$totalCredit</td><td  class='transcriptTableSemester'>$totalGPA</td></tr>";
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
														
				echo "<tr><td class='transcriptTableNonCourses'>$CrsCode</td><td class='transcriptTableCourses'>$CrsNameEng</td><td class='transcriptTableNonCourses'>$CrsTHours</td><td class='transcriptTableNonCourses'>$ScoreGrade</td><td class='transcriptTableNonCourses'>$DisplayScore</td><td class='transcriptTableNonCourses'>$CrsTHours</td><td class='transcriptTableNonCourses'></td></tr>";
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
						echo "<tr><td class='transcriptTableNonCourses'></td><td class='transcriptTableCourses'></td><td  class='transcriptTableSemester'>$totalCredit</td><td  class='transcriptTableSemester'></td><td  class='transcriptTableSemester'>$DisplaytotalPoints</td><td  class='transcriptTableSemester'>$totalCredit</td><td  class='transcriptTableSemester'>$totalGPADisplay</td></tr>";
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
	
	//new form
      echo "<table style='width: 250px; margin: auto;'>";
      echo "<tr><td style='width: 125px; margin: auto;'><form method='post'>";
      echo "<input type='hidden' name='mode' value='addStudent'>";
      echo "<button type='submit' class='addBtn'>اضاقة طالب</button>";
      echo "</form></td>";
      echo "<td style='width: 125px; margin: auto;'><form method='post'>";
      echo "<input type='hidden' name='mode' value='batchList'>";
      echo "<button type='submit' class='addBtn'>صفحة الدفعه</button>";
      echo "</form></td></tr></table>";
      //filter list form 
      echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
      //list table
      echo "<table id='masterTable'>";
      echo "<tr class='header'><th style='text-align: right;'> م - اﻹسم - رقم البطاقة - رقم الجواز </th><th style='width:600px;text-align: center;'></th></tr>";
      $lineNo=1;

      $qq = "SELECT `StId` FROM ProgramStudents where `PrgId`=? AND `YearId`=?";
      if($stmt=mysqli_prepare($dbc, $qq)){
		if(mysqli_stmt_bind_param($stmt, "ii", $PrgId, $YearId)){
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
		}
	  }


      $q="SELECT StId,StName,StNationalID,StMaritimePassportNo FROM Students ORDER BY StId";
      $r=mysqli_query($dbc,$q);
      if($r){
		
		$RS = mysqli_fetch_all($result, MYSQLI_NUM);
		$numberOfRegistered = count($RS);
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
		  foreach($row as $key => $value){
            $$key=$value;
          }
		  
          echo "<tr><td style='text-align: right;'> $lineNo - $StName - [$StNationalID]</td>";
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

	echo "<div>";
	echo "<input type='button' value='print' onclick='PrintDiv();' />";
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

	echo "<div>";
	echo "<input type='button' value='print' onclick='PrintDiv();' />";
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
	echo $q ."-" .$YearDesc ."-" .$YearStart ."-" .$YearEnd;
    if ($stmt = mysqli_prepare($dbc, $q)){
        if (mysqli_stmt_bind_param($stmt, "sss", $YearDesc,$YearStart,$YearEnd)) {
            if (mysqli_stmt_execute($stmt)) {
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
    $q = "DELETE FROM `ProgramYearSemester` WHERE `PrgId`=? AND `YearId`=? AND `Semester`=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "iii", $PrgId, $YearId, $Semester)) { 
            if (mysqli_stmt_execute($stmt)) {
                $infoMessage = "تم الإلغاء بنجاح!";
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
	echo $q ."-" .$PrgId ."," .$YearId."," .$Semester."," .$SemesterStart."," .$SemesterEnd;
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
	echo $q;
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
	$qq = "SELECT CoursesGuide.CrsId, CoursesGuide.CrsCode, CoursesGuide.CrsName FROM `ProgramCoursesSemester`,`CoursesGuide` WHERE ProgramCoursesSemester.PrgId = CoursesGuide.CrsProgram AND ProgramCoursesSemester.CrsId= CoursesGuide.CrsId AND ProgramCoursesSemester.PrgId=$PrgId AND ProgramCoursesSemester.Semester=$Semester ORDER BY Semester ASC";
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
	echo "<input type='button' value='print' onclick='PrintDiv();' />";
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
							$new_q = "insert into programstudentscourses(StId,PrgId,YearId,CrsId,Semester) values ($StId,$PrgId,$YearId,$y,$Semester)";
						}else{
							$new_q = $new_q .",($StId,$PrgId,$YearId,$y,$Semester)";
						}
					}
				}
			}
		}
	}				
//	echo $new_q;
	if ($stmt = mysqli_prepare($dbc, $new_q)){
            if (mysqli_stmt_execute($stmt)) {
                $infoMessage = "تمت الإضافة بنجاح!";
            } else {
                $errorMessage = "لم يتم الحفظ<br>" . mysqli_error($dbc) . "!";
            }
         mysqli_stmt_close($stmt);
    }
    $mode = "ManageSemester";

}
//*****************************************************************************************
//sec:saveadd Save added Batch data
//*****************************************************************************************
if ($mode == "unregisterStudentSemester") {
    $q = "DELETE FROM `programstudentscourses` WHERE `PrgId`=? AND `YearId`=? AND `Semester`=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "iii", $PrgId, $YearId, $Semester)) { 
            if (mysqli_stmt_execute($stmt)) {
                $infoMessage = "تم الإلغاء بنجاح!";
            }
        }
        mysqli_stmt_close($stmt); 
    }
    $mode = "ManageSemester";
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
	echo "<input class='input-field' type='text' placeholder='اسم الدفعة' maxlength='255' name='Semester'";
	if(isset($Semester)){
		echo " value='$Semester'";
	} 
	echo "></div></td></tr>";  

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
 
	echo "<table id='masterTable'>";
	$qq = "SELECT `SemesterStart`, `SemesterEnd`, `Semester` FROM `ProgramYearSemester` where `YearId`=$YearId AND `PrgId`= $PrgId ORDER BY Semester ASC;";
	if ($stmt = mysqli_prepare($dbc, $qq)){
	   if(mysqli_stmt_execute($stmt)){
		   if(mysqli_stmt_bind_result($stmt, $SemesterStart, $SemesterEnd, $Semester)){
			   while(mysqli_stmt_fetch($stmt)){
					echo "<tr><td style='text-align: right;'> $Semester</td>";
					echo "<td style='text-align: right;'> $SemesterStart</td>";
					echo "<td style='text-align: right;'> $SemesterEnd</td>";					
					echo "<td style='text-align: left;'><form method='post'>";
					echo "<input type='hidden' name='YearId' value='$YearId'>";
					echo "<input type='hidden' name='PrgId' value='$PrgId'>";
					echo "<input type='hidden' name='Semester' value='$Semester'>";					
					echo "<button type='submit' class='edtBtn' name='mode' value='editSemester'>تعديل</button> ";
					echo "<button type='submit' class='delBtn' name='mode' value='deleteSemester'>ازالة</button> ";
					$NumberOfRecords = 0;
					foreach ($Semester_Array as $x => $y) {
//						echo $y ."<br>";
						if($y == $Semester){

							$NumberOfRecords = 1;
						}
					}
					if($NumberOfRecords == 0){
						echo "<button type='submit' class='navBtn' name='mode' value='registerStudentSemester'>تسجيل</button> ";
					}else{
						echo "<button type='submit' class='pwdBtn' name='mode' value='unregisterStudentSemester'>الغاء التسجيل</button> ";
						echo "<button type='submit' class='savBtn' name='mode' value='registeredCourses'>درجات</button> ";
						echo "<button type='submit' class='edtBtn' name='mode' value='PrintCourseScore'>طباعة درجات</button> ";											
					}
					echo "</form></td></tr>";
				}
		   }
	   }
	}
    echo "</table>";
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

    $numberOfButtons = 4;
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
