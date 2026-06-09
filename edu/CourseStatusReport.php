<?php
//uncomment those two lines for debugging
ini_set('display_errors',1); 
error_reporting(E_ALL);
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
$gdir="ltr";
$galign="left";
$title="موقف الدورات";
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
			@media print {
                #mainForm{
                    display:none;
                }
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
	$user_year = getuseryear($__uid, $dbc);
	$yearId = $user_year['Id'];

	//******************************************************************************************************* */
	// main form
	//******************************************************************************************************* */
	$width='850px';
	if(isset($submitted)){
		$width='1050px';
	}
	echo "<div style='margin: auto;width:$width;direction:rtl;' id='mainForm'>";
	echo "<form method='post'>";
	echo "<table>";
	echo "<tr>";
	echo "<td style='width:80px;'>";
	echo " نوع الدورة:";
	echo "</td>";
	echo "<td style='width:150px;'>";
	echo "<select name='CoursType' class='input-field'>";
	echo "<option value='2'>الدورات التعاقدية</option>";
	echo "<option value='1'>الدورات المخططة</option>";
	echo "</select> ";
	echo "</td>";
	echo "<td style='width:80px;'>";
	echo " العام التدريبي:";
	echo "</td>";
	echo "<td style='width:150px;'>";
	if(!isset($CoursYear)){
		$CoursYear=$yearId;
	}
	echo "<select name='CoursYear' class='input-field'><option value='-1'>حدد العام التدريبي</option>";
	$q="SELECT `YearId`,`YearDesc` FROM `Years` WHERE `YearType`='E'";
	if($stmt=mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_execute($stmt)){
			if(mysqli_stmt_bind_result($stmt, $YearId,$YearDesc)){
				while(mysqli_stmt_fetch($stmt)){
					echo "<option value='$YearId'";
					if(isset($CoursYear)){
						if($CoursYear==$YearId){
							echo " selected";
						}
					}
					echo ">$YearDesc</option>";
				}
			}
		}	
	}
	echo "</select> ";
	echo "</td>";
	echo "<td style='width:80px;'>";
	echo " رقم الدورة:";
	echo "</td>";
	echo "<td style='width:150px;'>";
	echo "<input type='text' name='CoursBulletin' class='input-field' autofocus> ";
	echo "</td>";
	echo "<td style='width:100px;'>";
	echo "<button class='viewBtn' type='submit'>عرض</button>";
	echo "</td>";
	if(isset($submitted)){
		echo "<td style='width:100px;'>";
		echo "<button class='okBtn' type='button' onclick='window.print();'>طباعة</button>";
		echo "</td>";
		echo "<td style='width:100px;'>";
		echo "<button class='cnlBtn' type='button' onclick='window.location=\"$filename\";'>تراجع</button>";
		echo "</td>";
	}
	echo "</tr>";
	echo "</table>";
	echo "<input type='hidden' name='submitted' value='-1'>";
	if(isset($CoursBulletins)){
		if(is_array($CoursBulletins)){
			foreach($CoursBulletins as $key => $value){
				echo "<input type='hidden' name='CoursBulletins[]' value='$value'>";
			}
		}
	}
	if(isset($CoursBulletin)){
		echo "<input type='hidden' name='CoursBulletins[]' value='$CoursBulletin'>";
	}
	if(isset($CoursYears)){
		if(is_array($CoursYears)){
			foreach($CoursYears as $key => $value){
				echo "<input type='hidden' name='CoursYears[]' value='$value'>";
			}
		}
	}
	if(isset($CoursYear)){
		echo "<input type='hidden' name='CoursYears[]' value='$CoursYear'>";
	}
	if(isset($CoursTypes)){
		if(is_array($CoursTypes)){
			foreach($CoursTypes as $key => $value){
				echo "<input type='hidden' name='CoursTypes[]' value='$value'>";
			}
		}
	}
	if(isset($CoursType)){
		echo "<input type='hidden' name='CoursTypes[]' value='$CoursType'>";
	}
	echo "</form>";
	echo "</div>";
	if(isset($submitted)){
		$CrsBulletin=$CoursBulletin;
		$CrsYear=$CoursYear;
		$CrsType=$CoursType;
		$q="SELECT `CoursId`,`CrsName`,`CoursBulletin`,`CoursFromAct`,`CoursToAct`,`CoursStatus`,`CrstpDescription`,`stepName`,`CoursDevidable` FROM `Courses` INNER JOIN `CoursesGuide` ON `CoursCrsId`=`CrsId` INNER JOIN `CourseTypes` ON `CoursType`=`CrstpId` LEFT JOIN `steps` ON `CoursStatus`=`stepNo` AND `CoursType`=`stepType` WHERE `CoursType`=? AND `CoursBulletin`=? AND `CoursYear`=?";
		$row=1;
		$courses=array();
		if(isset($CoursBulletins)){
			if($stmt=mysqli_prepare($dbc, $q)){
				foreach($CoursBulletins as $key => $value){
					$CBulletin=$value;
					$CType=$CoursTypes[$key];
					$CYear=$CoursYears[$key];
					if(mysqli_stmt_bind_param($stmt, "iii", $CType,$CBulletin,$CYear)){
						if(mysqli_stmt_execute($stmt)){
							if(mysqli_stmt_bind_result($stmt, $CoursId,$CrsName,$CoursBulletin,$CoursFromAct,$CoursToAct,$CoursStatus,$CrstpDescription,$stepName,$CoursDevidable)){
								if(mysqli_stmt_fetch($stmt)){
									$courses[$row]['CoursId']=$CoursId;
									$courses[$row]['CrsName']=$CrsName;
									$courses[$row]['CoursBulletin']=$CoursBulletin;
									$courses[$row]['CoursFromAct']=$CoursFromAct;
									$courses[$row]['CoursToAct']=$CoursToAct;
									$courses[$row]['CoursStatus']=$CoursStatus;
									$courses[$row]['CrstpDescription']=$CrstpDescription;
									$courses[$row]['stepName']=$stepName;
									$courses[$row]['CoursDevidable']=$CoursDevidable;
									$courses[$row]['CoursType']=$CType;
									$row++;
								}
							}
						}
					}
				}
				mysqli_stmt_close($stmt);
			}
		}
		if($stmt=mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt, "iii", $CrsType,$CrsBulletin,$CrsYear)){
				if(mysqli_stmt_execute($stmt)){
					if(mysqli_stmt_bind_result($stmt, $CoursId,$CrsName,$CoursBulletin,$CoursFromAct,$CoursToAct,$CoursStatus,$CrstpDescription,$stepName,$CoursDevidable)){
						if(mysqli_stmt_fetch($stmt)){
							$courses[$row]['CoursId']=$CoursId;
							$courses[$row]['CrsName']=$CrsName;
							$courses[$row]['CoursBulletin']=$CoursBulletin;
							$courses[$row]['CoursFromAct']=$CoursFromAct;
							$courses[$row]['CoursToAct']=$CoursToAct;
							$courses[$row]['CoursStatus']=$CoursStatus;
							$courses[$row]['CrstpDescription']=$CrstpDescription;
							$courses[$row]['stepName']=$stepName;
							$courses[$row]['CoursDevidable']=$CoursDevidable;
							$courses[$row]['CoursType']=$CrsType;
						}
					}
				}
			}
			mysqli_stmt_close($stmt);
		}
		$periods=array();
		$q="SELECT `crprDescription`,`crprFrom`,`crprTo`,`stepName` FROM `coursPeriods` INNER JOIN `steps` ON `crprStatus`= `stepNo` WHERE `crprCourseId`=? AND `stepType`=?";
		if($stmt=mysqli_prepare($dbc, $q)){
			foreach($courses as $key => $value){
			//for($index=1;$index<=$row;$index++){
				$crsId=$value['CoursId'];
				$crsType=$value['CoursType'];
				$crsDevidable=$value['CoursDevidable'];
				if($crsDevidable==1){
					if(mysqli_stmt_bind_param($stmt, "ii", $crsId,$crsType)){
						if(mysqli_stmt_execute($stmt)){
							if(mysqli_stmt_bind_result($stmt, $crprDescription,$crprFrom,$crprTo,$stepName)){
								$per=1;
								while(mysqli_stmt_fetch($stmt)){
									$periods[$key][$per]['crprDescription']=$crprDescription;
									$periods[$key][$per]['crprFrom']=$crprFrom;
									$periods[$key][$per]['crprTo']=$crprTo;
									$periods[$key][$per]['stepName']=$stepName;
									$per++;
								}
							}
						}
					}
				}
			}
			mysqli_stmt_close($stmt);
		}
		echo "<div style='margin: auto;width: 18cm;direction: rtl;'>";
		echo "<table style='width: 100%;'>";
		echo "<tr><td>م</td><td>رقم الدورة</td><td>اسم الدورة</td><td>تاريخ الدورة</td><td>موقف الدورة</td></tr>";
		$rowNo=1;
		foreach($courses as $ckey => $cvalue){
		//for($index=1;$index<=$row;$index++){
			$CoursBulletin=$cvalue['CoursBulletin'];
			$CrsName=$cvalue['CrsName'];
			$CoursFromAct=$cvalue['CoursFromAct'];
			$stepName=$cvalue['stepName'];
			$CoursDevidable=$cvalue['CoursDevidable'];
			echo "<tr><td>$rowNo</td><td>$CoursBulletin</td><td>$CrsName</td><td>$CoursFromAct</td><td>$stepName</td></tr>";
			$rowNo++;
			if($CoursDevidable==1){
				$crsPeriods=$periods[$ckey];
				foreach($crsPeriods as $key => $value){
					$crprDescription=$value['crprDescription'];
					$crprFrom=$value['crprFrom'];
					$crprTo=$value['crprTo'];
					$stepName=$value['stepName'];
					echo "<tr><td>&nbsp;</td><td>$crprDescription</td><td>$crprFrom</td><td>$crprTo</td><td>$stepName</td></tr>";
				}
			}
		}
		echo "</table>";
		echo "</div>";
	}
}

?>
    </body>
</html>
