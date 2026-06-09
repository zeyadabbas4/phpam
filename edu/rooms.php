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
$title="الغرف";
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
	//read ref data
	//read buildings
	$buildings=array();
	$q="SELECT bldId,bldDescription FROM  buildings ORDER BY bldDescription";
	if($stmt=mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_execute($stmt)){
			if(mysqli_stmt_bind_result($stmt, $bldId, $bldDescription)){
				while(mysqli_stmt_fetch($stmt)){
					$buildings[$bldId]=$bldDescription;
				}
			}
		}    
        mysqli_stmt_close($stmt);
    }

//*****************************************************************************************
//sec:edit-view-deleteconfirm read a record for view or edit
//*****************************************************************************************
if($mode=="edit" or $mode=="view" or $mode=="deleteconfirm"){
    $q="SELECT roomNo, roomDescription, roomBuilding, roomFloor, bldDescription, floorDescription FROM rooms INNER JOIN buildings ON roomBuilding=bldId INNER JOIN floors ON roomFloor=floorId WHERE roomId=?";
    if($stmt=mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $roomId)){
            if(mysqli_stmt_execute($stmt)){
                if(mysqli_stmt_bind_result($stmt, $roomNo, $roomDescription, $roomBuilding, $roomFloor, $bldDescription, $floorDescription)){
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

    // التحقق من إدخال اسم الغرفة
    if ($roomNo == "") {
        $errorMessage .= "لابد من إدخال رقم الغرفة<br>";
    }

    // التحقق من إدخال وصف الغرفة
    if ($roomDescription == "") {
        $errorMessage .= "لابد من إدخال وصف الغرفة<br>";
    }

    if ($roomBuilding == "") {
        $errorMessage .= "لابد من إدخال اسم المبنى<br>";
    }
  
    if ($roomFloor == "") {
        $errorMessage .= "لابد من إدخال رقم الطابق<br>";
    }

    if ($errorMessage != "") {
        $mode = substr($mode, 4); 
    }
}

//*****************************************************************************************
//sec:sqveedit bsave edit data
//*****************************************************************************************
if ($mode == "saveedit") {
    $q = "UPDATE rooms SET roomNo=?, roomDescription=?, roomBuilding=?, roomFloor=? WHERE roomId=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "sssii", $roomNo, $roomDescription, $roomBuilding, $roomFloor, $roomId)) {
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
    $mode = "showList";
}
}

//*****************************************************************************************
//sec:saveadd Save added data
//*****************************************************************************************
if ($mode == "saveadd") {
    $q = "INSERT INTO rooms(roomNo, roomDescription, roomBuilding, roomFloor) VALUES(?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if (mysqli_stmt_bind_param($stmt, "sssi", $roomNo, $roomDescription, $roomBuilding, $roomFloor)) {
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
//sec:delete delete record
//*****************************************************************************************
if ($mode == "delete") {
    $q = "DELETE FROM rooms WHERE roomId=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $roomId)) { // ربط معرّف الغرفة
            if (mysqli_stmt_execute($stmt)) {
                $infoMessage = "تم الإلغاء بنجاح!";
            }
        }
        mysqli_stmt_close($stmt); 
    }
    $mode = "showList";  
}

//*****************************************************************************************
//sec:add-edit Add/Edit form 
//*****************************************************************************************
if ($mode == "add" or $mode == "edit") {
    if (isset($errorMessage)) {
        echo "<div class='errorMessages'>$errorMessage</div>";
    }
    if ($mode == "add") {
        $formTitle = " غرفة جديدة";
    } else {
        $formTitle = "تعديل الغرفة";
    }

    echo "<h3>$formTitle</h3>";
    echo "<center><form method='post'>";
    echo "<table>";

    // رقم الغرفة first
    echo "<tr><td style='width: 100px;'>رقم الغرفة:</td><td style='width:650px;'>";
    echo "<div class='input-container'>";
    echo "<input class='input-field' type='text' placeholder='رقم الغرفة' maxlength='255' name='roomNo'";
    if (isset($roomNo)) {
        echo " value='$roomNo'";
    }
    echo "></div></td></tr>";

    // اسم الغرفة
    echo "<tr><td style='width: 100px;'>اسم الغرفة:</td><td style='width:650px;'>";
    echo "<div class='input-container'>";
    echo "<input class='input-field' type='text' placeholder='اسم الغرفة' maxlength='255' name='roomDescription'";
    if (isset($roomDescription)) {
        echo " value='$roomDescription'";
    }
    echo "></div></td></tr>";

    // اسم المبنى as select dropdown
    echo "<tr><td style='width: 100px;'>اسم المبنى:</td><td style='width:650px;'>";
    echo "<div class='input-container'>";
    echo "<select class='input-field' name='roomBuilding'>";

    // Fetch buildings from database
    $q = "SELECT bldId, bldDescription FROM buildings ORDER BY bldDescription";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_bind_result($stmt, $bldId, $bldDescription)) {
                while (mysqli_stmt_fetch($stmt)) {
                    // Check if roomBuilding is already selected
                    $selected = (isset($roomBuilding) && $roomBuilding == $bldId) ? "selected" : "";
                    echo "<option value='$bldId' $selected>$bldDescription</option>";
                }
            }
        }
        mysqli_stmt_close($stmt);
    }

    echo "</select></div></td></tr>";

    // الطابق - Dropdown
echo "<tr><td style='width: 100px;'>الطابق:</td><td style='width:650px;'>";
echo "<div class='input-container'>";
echo "<select class='input-field' name='roomFloor'>";

// Fetch floors from the database
$q = "SELECT floorId, floorDescription FROM floors ORDER BY floorDescription";
if ($stmt = mysqli_prepare($dbc, $q)) {
    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_bind_result($stmt, $floorId, $floorDescription)) {
            while (mysqli_stmt_fetch($stmt)) {
                // Check if roomFloor is already selected
                $selected = (isset($roomFloor) && $roomFloor == $floorId) ? "selected" : "";
                echo "<option value='$floorId' $selected>$floorDescription</option>";
            }
        }
    }
    mysqli_stmt_close($stmt);
}

echo "</select></div></td></tr>";

    echo "</table>";
    if ($mode == 'edit') {
        echo "<input type='hidden' name='roomId' value='$roomId'>";
    }
    $newMode = "save$mode";
    // Save and cancel buttons
    echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'> حفظ </button> <button type='button' class='cnlBtn' onclick='window.location=\"$filename\";'> تراجع </button></div>";
    echo "</form>";
    echo "</center>";
}
//*****************************************************************************************
//sec:view display code
//*****************************************************************************************
if ($mode == "view") {
    echo "<center>";
    echo "<h3>بيانات الغرفة</h3>";
    echo "<table>";
    echo "<tr><td>رقم الغرفة</td><td>:</td><td align='right'> $roomNo </td></tr>";
    echo "<tr><td>وصف الغرفة</td><td>:</td><td align='right'> $roomDescription </td></tr>";
    echo "<tr><td>اسم المبنى</td><td>:</td><td align='right'> $bldDescription </td></tr>";
    echo "<tr><td> الطابق</td><td>:</td><td align='right'> $floorDescription </td></tr>";
    echo "</table>";
    echo "<div class='frmButtons'><button type='button' class='okBtn' onclick='window.location=\"$filename\";'> حسنا </button></div>";
    echo "</center>";
}

//*****************************************************************************************
//sec:deleteconfirm delete confirm
//*****************************************************************************************
if ($mode == "deleteconfirm") {
    echo "<center>";
    echo "<h3>إلغاء الغرفة</h3>";
    echo "<div style='text-align: center;'>سيتم إلغاء الغرفة $roomNo <br> هل أنت متأكد؟...</div><br>";
    echo "<form method='post' style='max-width:500px;margin:auto;'>";
    echo "<input type='hidden' name='roomId' value='$roomId'>"; 
    echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='delete'> نعم </button> <button type='button' class='cnlBtn' onclick='window.location=\"$filename\";'> لا </button></div>";
    echo "</form>";
    echo "</center>";
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
    //نموذج إضافة غرفة جديدة
    echo "<div style='width: 110px; margin: auto;'><form method='post'>";
    echo "<button type='submit' class='addBtn' name='mode' value='add'> غرفة جديدة</button>";
    echo "</form></div><br>";

    $numberOfButtons = 3;
    $buttonCellWidth = $numberOfButtons * 115;
    $buttonCellWidth .= "px";

   //filter list form 
   echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث...'>";
   echo "<table id='masterTable'><tr class='header'><th> الغرفة </th><th style='width: $buttonCellWidth'>&nbsp;</th></tr>";
   $q="SELECT roomId, roomNo,roomDescription FROM rooms ORDER BY roomNo";
   if ($stmt = mysqli_prepare($dbc, $q)){
	   if(mysqli_stmt_execute($stmt)){
		   if(mysqli_stmt_bind_result($stmt, $roomId, $roomNo, $roomDescription)){
			   while(mysqli_stmt_fetch($stmt)){
				   echo "<tr>";
				   echo "<td> $roomNo - $roomDescription </td>";
				   echo "<td style='width: $buttonCellWidth;'>";
				   echo "<form method='post'>";
				   echo "<input type='hidden' name='roomId' value='$roomId'>";
				   echo "<button type='submit' class='viewBtn' name='mode' value='view'>عرض</button> ";
				   echo "<button type='submit' class='edtBtn' name='mode' value='edit'>تعديل</button> ";
				   echo "<button type='submit' class='delBtn' name='mode' value='deleteconfirm'>حذف</button> ";
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
