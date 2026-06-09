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
$title="Workflows";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $title?></title>
        <style>
    		* {
				box-sizing: border-box;
			}
			body {
				direction: ltr;
			}
    		#filterBox {
				background-image: url('img/searchicon.png');
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
				text-align: left;
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
    		.upBtn {
				background-color: DarkMagenta;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.upBtn:hover {
				opacity: 1;
			}
    		.dnBtn {
				background-color: DarkMagenta;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.dnBtn:hover {
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
    		.desBtn {
				background-color: green;
				color: white;
				padding: 10px 10px;
				border: none;
				cursor: pointer;
				width: 100px;
				opacity: 0.9;
			}
    		.desBtn:hover {
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
			.infomessage{
				color: green;
				font-size: medium;
				text-align:center;
			}
			h2{
				text-align: center;
				color: RoyalBlue;
			}
			h3{
				text-align: center;
				color: cornflowerblue;
			}
		</style>
    </head>
    <body>
	<br><h2><?php echo $title?></h2>
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
	include($__systemRoot."expired.php");
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
        $mode="showlist";
    }
	if(!isset($errorMessage)){
		$errorMessage="";
	}
	if(!isset($errorNo)){
		$errorNo = 0;
	}
	if(!isset($validationError)){
		$validationError=false;
	}
//echo $mode;
//*****************************************************************************************
//read a record for view or edit
//*****************************************************************************************
    if($mode=="edit" or $mode=="view" or $mode=="deleteconfirm" or $mode=="steplist" or $mode=="stepadd" or $mode=="stepedit"){
        $q="SELECT wfDescription FROM workflows WHERE wfId=?";
        if($stmt=mysqli_prepare($dbc, $q)){
            if(mysqli_stmt_bind_param($stmt, "i", $wfId)){
                if(mysqli_stmt_execute($stmt)){
                    if(mysqli_stmt_bind_result($stmt, $wfDescription)){
                        if(!mysqli_stmt_fetch($stmt)){
                            $errorMessage="Data error!...";
                            $mode="showlist";
                        }
                    }
                }    
            }
            mysqli_stmt_close($stmt);
        }
    }

//*****************************************************************************************
//read a step record for view or edit
//*****************************************************************************************
if($mode=="stepedit" or $mode=="stepView" or $mode=="stepdeleteconfirm" or $mode=="stepdelete"){
	$q="SELECT wfsDescription,wfsPrevStep,wfsNextStep FROM workflowSteps WHERE wfsId=?";
	if($stmt=mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt, "i", $wfsId)){
			if(mysqli_stmt_execute($stmt)){
				if(mysqli_stmt_bind_result($stmt, $wfsDescription,$wfsPrevStep,$wfsNextStep)){
					if(!mysqli_stmt_fetch($stmt)){
						$errorMessage="Data error!...";
						$mode="steplist";
					}
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
		if($wfDescription==""){
			$errorMessage="Workflow name must be entered<br>";
		}

		if($errorMessage != ""){
			$mode=substr($mode,4);
		}
	}

//*****************************************************************************************
//validate for savestepadd or savestepedit
//*****************************************************************************************
if($mode=="saveedit" or $mode=="saveadd"){
	$errorMessage="";
	if($wfDescription==""){
		$errorMessage="Workflow name must be entered<br>";
	}

	if($errorMessage != ""){
		$mode=substr($mode,4);
	}
}


//*****************************************************************************************
//save edited workflow
//*****************************************************************************************
	if($mode=="saveedit"){
		$q="UPDATE workflows SET wfDescription=? WHERE wfId=?";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,"si",$wfDescription,$wfId)){
				if(mysqli_stmt_execute($stmt)){
					$infomessage="Saved!...";
				}
			}
			mysqli_stmt_close($stmt);
		}
		$mode="showlist";
	}

//*****************************************************************************************
//Save new workflow
//*****************************************************************************************
if($mode=="saveadd"){
	$q="INSERT INTO workflows(wfDescription) VALUES(?)";
	if ($stmt = mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt,"s",$wfDescription)){
			if(mysqli_stmt_execute($stmt)){
				$infomessage="Saved!...";
			}    
		}
		mysqli_stmt_close($stmt);
	}
	$mode="showlist";
}

//*****************************************************************************************
//Save Edited step
//*****************************************************************************************
if($mode=="savestepedit"){
	$q="UPDATE workflowSteps SET wfsDescription=? WHERE wfsId=?";
	if ($stmt = mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt,"si",$wfsDescription,$wfsId)){
			if(mysqli_stmt_execute($stmt)){
				$infomessage="Saved!...";
			}    
		}
		mysqli_stmt_close($stmt);
	}
	$mode="steplist";
}

//*****************************************************************************************
//Save new step
//*****************************************************************************************
if($mode=="savestepadd"){
	//add the new item
	$fieldList="";
	$valueList="";
	$typeList="";
	$parameterList=array();
	if($wfsPrevStep=="-1" and $wfsNextStep=="-1"){
		$fieldList="wfsDescription,wfsWorkflow";
		$valueList="?,?";
		$typeList="si";
		$parameterList=array($wfsDescription,$wfId);
	}elseif($wfsPrevStep=="-1" and $wfsNextStep!="-1"){
		$fieldList="wfsDescription,wfsWorkflow,wfsNextStep";
		$valueList="?,?,?";
		$typeList="sii";
		$parameterList=array($wfsDescription,$wfId,$wfsNextStep);
	}elseif($wfsPrevStep!="-1" and $wfsNextStep=="-1"){
		$fieldList="wfsDescription,wfsWorkflow,wfsPrevStep";
		$valueList="?,?,?";
		$typeList="sii";
		$parameterList=array($wfsDescription,$wfId,$wfsPrevStep);
	}else{
		$fieldList="wfsDescription,wfsWorkflow,wfsPrevStep,wfsNextStep";
		$valueList="?,?,?,?";
		$typeList="siii";
		$parameterList=array($wfsDescription,$wfId,$wfsPrevStep,$wfsNextStep);
	}
	$q="INSERT INTO workflowSteps($fieldList) VALUES($valueList)";
	if ($stmt = mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt,$typeList,...$parameterList)){
			if(mysqli_stmt_execute($stmt)){
				$infomessage="Saved!...";
				$newItemId=mysqli_insert_id($dbc);
			}else{
				$errorMessage="Execution error!...";
				$errorMessage.="<br>".mysqli_stmt_error($stmt);
			}
		}else{
			$errorMessage="Binding error!...";
			$errorMessage.="<br>".mysqli_stmt_error($stmt);
		}
		mysqli_stmt_close($stmt);
	}else{
		$errorMessage="Preparation error!...";
		$errorMessage.="<br>".mysqli_error($dbc);
	}
	//adjust previous
	if($wfsPrevStep !=-1){
		$q="UPDATE workflowSteps set wfsNextStep=? WHERE wfsId=?";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,"ii",$newItemId,$wfsPrevStep)){
				if(mysqli_stmt_execute($stmt)){
					$infomessage.="<br>Previous Updates!...";
				}else{
					$errorMessage="Execution error in previous!...";
					$errorMessage.="<br>".mysqli_stmt_error($stmt);
				}
			}else{
				$errorMessage="Binding error in previous!...";
				$errorMessage.="<br>".mysqli_stmt_error($stmt);
			}
			mysqli_stmt_close($stmt);
		}else{
			$errorMessage="Preparation error in previous!...";
			$errorMessage.="<br>".mysqli_error($dbc);
		}
	}
	if($wfsNextStep != "-1"){
		$q="UPDATE workflowSteps set wfsPrevStep=? WHERE wfsId=?";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,"ii",$newItemId,$wfsNextStep)){
				if(mysqli_stmt_execute($stmt)){
					$infomessage.="<br>Next Updates!...";
				}else{
					$errorMessage="Execution error in next!...";
					$errorMessage.="<br>".mysqli_stmt_error($stmt);
				}
			}else{
				$errorMessage="Binding error in next!...";
				$errorMessage.="<br>".mysqli_stmt_error($stmt);
			}
			mysqli_stmt_close($stmt);
		}else{
			$errorMessage="Preparation error in next!...";
			$errorMessage.="<br>".mysqli_error($dbc);
		}
	}
	$mode="steplist";
}

//*****************************************************************************************
//delete workflow
//*****************************************************************************************
if($mode == "delete"){
	$q="DELETE FROM workflows WHERE wfId=?";
	if ($stmt = mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt,"i",$wfId)){
			if(mysqli_stmt_execute($stmt)){
				$message="Workflow deleted!...";
			}    
		}
		mysqli_stmt_close($stmt);
	}
	$mode="showlist";
}

//*****************************************************************************************
//delete workflow step
//*****************************************************************************************
if($mode == "stepdelete"){
	$q="UPDATE workflowSteps SET wfsNextStep=? WHERE wfsId=?";
	if ($stmt = mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt,"ii",$wfsNextStep,$wfsPrevStep)){
			if(mysqli_stmt_execute($stmt)){
				$message="Previous disconnected!...";
			}    
		}
		mysqli_stmt_close($stmt);
	}
	$q="UPDATE workflowSteps SET wfsPrevStep=? WHERE wfsId=?";
	if ($stmt = mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt,"ii",$wfsPrevStep,$wfsNextStep)){
			if(mysqli_stmt_execute($stmt)){
				$message.="<br>Next disconnected!...";
			}    
		}
		mysqli_stmt_close($stmt);
	}	$q="DELETE FROM workflowSteps WHERE wfsId=?";
	if ($stmt = mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt,"i",$wfsId)){
			if(mysqli_stmt_execute($stmt)){
				$message.="<br>Step deleted!...";
			}    
		}
		echo mysqli_error($dbc);
		mysqli_stmt_close($stmt);
	}
	$mode="steplist";
}

//*****************************************************************************************
//Add/Edit form
//*****************************************************************************************
	if($mode=="add" or $mode=="edit"){
        if(isset($errorMessage)){
            echo "<div class='alertmessages'>$errorMessage</div>";
        }
		echo "<center><form method='post'>";
		echo "<table>";

		echo "<tr><td style='width: 150px;'>Workflow Name:</td><td style='width:650px;'>";
		echo "<div class='input-container'>";
		echo "<input class='input-field' type='text' placeholder='Workflow name' maxlength='255' name='wfDescription'";
		if(isset($wfDescription)){
			echo " value='$wfDescription'";
		} 
		echo "></div></td></tr>";  

		echo "</table>";
		if($mode=='edit'){
			echo "<input type='hidden' name='wfId' value='$wfId'>";
		}
		$newMode="save$mode";
		//save and cancel buttons
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'> Save </button> <button type='button' class='cnlBtn' onclick='window.location=\"$filename\";'> Cancel </button></div>";
		echo "</form>";
		echo "</center>";	
	}

//*****************************************************************************************
//Step Add/Edit form
//*****************************************************************************************
if($mode=="stepadd" or $mode=="stepedit"){
	if(isset($errorMessage)){
		echo "<div class='alertmessages'>$errorMessage</div>";
	}
	echo "<h3>Step in workflow $wfDescription</h3>";
	echo "<center><form method='post'>";
	echo "<table>";

	echo "<tr><td style='width: 150px;'>Step Name:</td><td style='width:650px;'>";
	echo "<div class='input-container'>";
	echo "<input class='input-field' type='text' placeholder='Step name' maxlength='255' name='wfsDescription'";
	if(isset($wfsDescription)){
		echo " value='$wfsDescription'";
	} 
	echo "></div></td></tr>";

	if($mode=="stepadd"){
		echo "<tr><td style='width: 150px;'>Previous Step:</td><td style='width:650px;'>";
		echo "<div class='input-container'>";
		echo "<select class='input-field' name='wfsPrevStep'><option value='-1'>Select previous step</option>";
		$stepfilter="";
		$types="i";
		$parameters[]=$wfId;
		if($mode=="stepedit"){
			$stepfilter=" AND wfsId<>?";
			$types="ii";
			$parameters=array($wfId);
		}
		$q="SELECT wfsId,wfsDescription FROM workflowSteps WHERE wfsWorkflow=?$stepfilter";
		if($stmt=mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt, $types, ...$parameters)){
				if(mysqli_stmt_execute($stmt)){
					if(mysqli_stmt_bind_result($stmt, $wfsId,$wfsDescription)){
						while(mysqli_stmt_fetch($stmt)){
							echo "<option value='$wfsId'>$wfsDescription</option>";
						}
					}
				}    
			}
			mysqli_stmt_close($stmt);
		}
		echo "</select></div></td></tr>";
		
		echo "<tr><td style='width: 150px;'>Next Step:</td><td style='width:650px;'>";
		echo "<div class='input-container'>";
		echo "<select class='input-field' name='wfsNextStep'><option value='-1'>Select next step</option>";
		$stepfilter="";
		$types="i";
		$parameters=array($wfId);
		if($mode=="stepedit"){
			$stepfilter=" AND wfsId<>?";
			$types="ii";
			$parameters[]=$wfsId;
		}
		$q="SELECT wfsId,wfsDescription FROM workflowSteps WHERE wfsWorkflow=?$stepfilter";
		if($stmt=mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt, $types, ...$parameters)){
				if(mysqli_stmt_execute($stmt)){
					if(mysqli_stmt_bind_result($stmt, $wfsId,$wfsDescription)){
						while(mysqli_stmt_fetch($stmt)){
							echo "<option value='$wfsId'>$wfsDescription</option>";
						}
					}
				}    
			}
			mysqli_stmt_close($stmt);
		}
		echo "</select></div></td></tr>";	
	}

	echo "</table>";
	echo "<input type='hidden' name='wfId' value='$wfId'>";
	if($mode=='stepedit'){
		echo "<input type='hidden' name='wfsId' value='$wfsId'>";
	}
	$newMode="save$mode";
	//save and cancel buttons
	echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'> Save </button> <button type='submit' class='cnlBtn' name='mode' value='steplist'> Cancel </button></div>";
	echo "</form>";
	echo "</center>";	
}

//*****************************************************************************************
//display workflow
//*****************************************************************************************
	if($mode == "view"){
		echo "<center>";
		echo "<h2 style='text-align: center;'>Workflow Basic Data</h2>";
		echo "<table>";
		echo "<tr><td>Workflow id</td><td>:</td><td align='right'> $wfId</td></tr>";
		echo "<tr><td>Workflow name</td><td>:</td><td align='right'> $wfDescription </td></tr>";
		echo "</table>";
		echo "<div class='frmButtons'><button type='button' class='okBtn' onclick='window.location=\"$filename\";'> حسنا </button></div>";
		echo "</center>";	
	}

//*****************************************************************************************
//delete workflow confirm
//*****************************************************************************************
	if($mode=="deleteconfirm"){
		echo "<center>";
		echo "<h2 style='text-align: center;'> Delete workflow </h2>";
		echo "<div style='text-align: center;'>$wfDescription will be deleted <br> Are you sure?...</div><br>";
		echo "<form method='post' style='max-width:500px;margin:auto;'>";
		echo "<input type='hidden' name='wfId' value='$wfId'>";
		echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='delete'> Yes </button> <button type='button' class='cnlBtn' name='mode' onclick='window.location=\"$filename\";'> No </button></div>";
		echo "</form>";
  		echo "</center>";
	}

//*****************************************************************************************
//delete workflow step confirm
//*****************************************************************************************
if($mode=="stepdeleteconfirm"){
	echo "<center>";
	echo "<h2 style='text-align: center;'> Delete Workflow Step</h2>";
	echo "<div style='text-align: center;'>$wfsDescription will be deleted <br> Are you sure?...</div><br>";
	echo "<form method='post' style='max-width:500px;margin:auto;'>";
	echo "<input type='hidden' name='wfId' value='$wfId'>";
	echo "<input type='hidden' name='wfsId' value='$wfsId'>";
	echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='stepdelete'> Yes </button> <button type='button' class='cnlBtn' name='mode' onclick='window.location=\"$filename\";'> No </button></div>";
	echo "</form>";
	  echo "</center>";
}

	
//****************************************************************************************
//the step list
//****************************************************************************************
	if($mode=="steplist"){
		if(isset($errorMessage)){
			echo "<div class='alertmessages'>$errorMessage</div>";
			echo "<br><br>";
		}
		if(isset($message)){
			echo "<div class='infomessage'>$message</div>";
			echo "<br><br>";
		}
		echo "<h3>Steps for $wfDescription</h3>";
		$itemList=array();
		//read root item
		$q="SELECT wfsId,wfsDescription,wfsNextStep FROM workflowSteps WHERE wfsWorkflow=? AND wfsPrevStep IS NULL";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt, "i", $wfId)){
				if(mysqli_stmt_execute($stmt)){
					if(mysqli_stmt_bind_result($stmt, $wfsId,$wfsDescription,$wfsNextStep)){
						if(mysqli_stmt_fetch($stmt)){
							$itemList[$wfsId]=$wfsDescription;
						}
					}
				}
			}
			mysqli_stmt_close($stmt);
		}
		//read the rest of the list
		$q="SELECT wfsId,wfsDescription,wfsNextStep FROM workflowSteps WHERE wfsId=?";
		if ($stmt = mysqli_prepare($dbc, $q)){
			while(!is_null($wfsNextStep)){
				if(mysqli_stmt_bind_param($stmt, "i", $wfsNextStep)){
					if(mysqli_stmt_execute($stmt)){
						if(mysqli_stmt_bind_result($stmt, $wfsId,$wfsDescription,$wfsNextStep)){
							if(mysqli_stmt_fetch($stmt)){
								$itemList[$wfsId]=$wfsDescription;
							}
						}
					}
				}
			}
			mysqli_stmt_close($stmt);
		}
		//new form
		echo "<div style='text-align: center;'><form method='post'>";
		echo "<button type='submit' class='addBtn' name='mode' value='stepadd' style='width:150px;'>Add New Step</button>";
		echo "<input type='hidden' name='wfId' value='$wfId'>";
		echo "</form></div><br>";
		$numberOfButtons=5;
		$buttonCellWidth=$numberOfButtons * 115;
		$buttonCellWidth .= "px";
		//filter list form 
		echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Search...'>";
		echo "<table id='masterTable'><tr class='header'><th> Steps </th><th style='width: $buttonCellWidth'>&nbsp;</th></tr>";
		$ser=1;
		foreach($itemList as $key => $value){
			echo "<tr>";
			echo "<td> $ser - $value [id=$key]</td>";
			echo "<td style='width: $buttonCellWidth;'>";
			echo "<form method='post'>";
			echo "<input type='hidden' name='wfId' value='$wfId'>";
			echo "<input type='hidden' name='wfsId' value='$key'>";
			echo "<button type='submit' class='edtBtn' name='mode' value='stepedit'>Edit</button> ";
			echo "<button type='submit' class='viewBtn' name='mode' value='stepView'>View</button> ";
			echo "<button type='submit' class='delBtn' name='mode' value='stepdeleteconfirm'>Delete</button> ";
			echo "<button type='submit' class='upBtn' name='mode' value='stepMoveUp'>Move Up</button> ";
			echo "<button type='submit' class='dnBtn' name='mode' value='stepMoveDn'>Move Dn</button> ";
			echo "</form>";
			echo "</tr>";
			$ser++;
		}
		echo "</table>";
		echo "<br>";
		echo "<div class='frmButtons'><button type='button' class='cnlBtn' name='mode' onclick='window.location=\"$filename\";'> Return </button></div>";
	}

//****************************************************************************************
//the record list
//****************************************************************************************
	if($mode=="showlist"){
		if(isset($errorMessage)){
			echo "<div class='alertmessages'>$errorMessage</div>";
			echo "<br><br>";
		}
		if(isset($message)){
			echo "<div class='infomessage'>$message</div>";
			echo "<br><br>";
		}
		//new form
		echo "<div style='width: 110px; margin: auto;'><form method='post'>";
		echo "<button type='submit' class='addBtn' name='mode' value='add'>Add New</button>";
		echo "</form></div><br>";
		$numberOfButtons=4;
		$buttonCellWidth=$numberOfButtons * 115;
		$buttonCellWidth .= "px";
		//filter list form 
		echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Search...'>";
		echo "<table id='masterTable'><tr class='header'><th> Workflow </th><th style='width: $buttonCellWidth'>&nbsp;</th></tr>";
		$q="SELECT wfId,wfDescription FROM workflows ORDER BY wfDescription";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_execute($stmt)){
				if(mysqli_stmt_bind_result($stmt, $wfId,$wfDescription)){
					$ser=1;
					while(mysqli_stmt_fetch($stmt)){
						echo "<tr>";
						echo "<td> $ser - $wfDescription [id=$wfId]</td>";
						echo "<td style='width: $buttonCellWidth;'>";
						echo "<form method='post'>";
						echo "<input type='hidden' name='wfId' value='$wfId'>";
						echo "<button type='submit' class='edtBtn' name='mode' value='edit'>Edit</button> ";
						echo "<button type='submit' class='viewBtn' name='mode' value='view'>View</button> ";
						echo "<button type='submit' class='delBtn' name='mode' value='deleteconfirm'>Delete</button> ";
						echo "<button type='submit' class='desBtn' name='mode' value='steplist'>Design</button> ";
						echo "</form>";
						echo "</tr>";
						$ser++;
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