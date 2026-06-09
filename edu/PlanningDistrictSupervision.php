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
        <title>districtSupervision</title>
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
	<br><h2 style='text-align: center;color: RoyalBlue;'>بيانات إشراف المناطق</h2>
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
$dists=array();
$q="SELECT dist_id,dist_name FROM  districts";
if($stmt=mysqli_prepare($dbc, $q)){
	if(mysqli_stmt_execute($stmt)){
		if(mysqli_stmt_bind_result($stmt, $dist_id,$dist_name)){
			while(mysqli_stmt_fetch($stmt)){
				$dists[$dist_id]=$dist_name;
			}
		}
	}
	mysqli_stmt_close($stmt);
}


//*****************************************************************************************
//validate for saveedit or saveadd
//*****************************************************************************************
	if($mode=="saveedit" or $mode=="saveadd"){
		$errorMessage="";
		if($dstsupDistrict==-1){
			$errorMessage="لابد من اختيار المنطقة<br>";
		}
		if($dstsupAmount==""){
			$errorMessage.="لابد من ادخال قيمة اﻹشراف<br>";
		}

		if($errorMessage != ""){
			$mode=substr($mode,4);
		}
	}

//*****************************************************************************************
//save edit data
//*****************************************************************************************
	if($mode=="saveedit"){
		$q="UPDATE districtSupervision SET dstsupAmount=?,dstsupDistrict=? WHERE dstsupId=?";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,"dii",$dstsupAmount,$dstsupDistrict,$dstsupId)){
				if(mysqli_stmt_execute($stmt)){
					$message="تم الحفظ!...";
				}    
			}
			mysqli_stmt_close($stmt);
		}
	}

//*****************************************************************************************
//Save added data
//*****************************************************************************************
	if($mode=="saveadd"){
		$q="INSERT INTO districtSupervision(dstsupDistrict,dstsupAmount) VALUES(?,?)";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,"id",$dstsupDistrict,$dstsupAmount)){
				if(mysqli_stmt_execute($stmt)){
					$message="تم الحفظ!...";
				}    
			}
			mysqli_stmt_close($stmt);
		}
	}
	
//*****************************************************************************************
//read a record for view or edit
//*****************************************************************************************
if($mode=="edit" or $mode=="view" or $mode=="deleteconfirm"){
	$q="SELECT dstsupAmount,dstsupDistrict FROM districtSupervision WHERE dstsupId=?";
	if($stmt=mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt, "i", $dstsupId)){
			if(mysqli_stmt_execute($stmt)){
				if(mysqli_stmt_bind_result($stmt, $dstsupAmount,$dstsupDistrict)){
					if(!mysqli_stmt_fetch($stmt)){
						$errorMessage="خطأ في البيانات!...";
						$showList=true;
						$mode="";
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
		$q="DELETE FROM districtSupervision WHERE dstsupId=?";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,"i",$dstsupId)){
				if(mysqli_stmt_execute($stmt)){
					$message="تم الالغاء!...";
				}    
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
		echo "<table>";

		echo "<tr><td style='width: 100px;'>المنطقة:</td><td style='width:650px;'>";
		echo "<div class='input-container'>";
		echo "<select class='input-field' name='dstsupDistrict'><option value='-1'>المنطقة</option>";
		foreach($dists as $key => $value){
			echo "<option value='$key'";
			if(isset($dstsupDistrict)){
				if($dstsupDistrict == $key){
					echo " selected";
				}
			}
			echo ">$value</option>";
		}
		echo "</select></div></td></tr>";

		echo "<tr><td style='width: 100px;'>اﻹشراف اﻹجمالي:</td><td style='width:650px;'>";
		echo "<div class='input-container'>";
		echo "<input class='input-field' type='text' placeholder='اﻹشراف اﻹجمالي' maxlength='255' name='dstsupAmount'";
		if(isset($dstsupAmount)){
			echo " value='$dstsupAmount'";
		} 
		echo "></div></td></tr>";  

		echo "</table>";
		if($mode=='edit'){
			echo "<input type='hidden' name='dstsupId' value='$dstsupId'>";
		}
		$newMode="save$mode";
		//save and cancel buttons
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'> حفظ </button> <button type='button' class='cnlBtn' onclick='window.location=\"districtSupervision.php\";'> تراجع </button></div>";
		echo "</form>";
		echo "</center>";	
	}

//*****************************************************************************************
//display code
//*****************************************************************************************
/*	if($mode == "view"){
		$showList=false;
		echo "<center>";
		echo "<h2 style='text-align: center;'> بيانات القسم </h2>";
		echo "<table>";
		echo "<tr><td>اسم القسم</td><td>:</td><td align='right'> $SecName </td></tr>";
		echo "</table>";
		echo "<div class='frmButtons'><button type='button' class='okBtn' onclick='window.location=\"districtSupervision.php\";'> حسنا </button></div>";
		echo "</center>";	
	}
*/
//*****************************************************************************************
//delete confirm
//*****************************************************************************************
	if($mode=="deleteconfirm"){
		$showList=false;
		echo "<center>";
		echo "<h2 style='text-align: center;'> الغاء اشراف منطقة </h2>";
		echo "<div style='text-align: center;'>سيتم الغاء منطقة $dists[$dstsupDistrict] <br> هل أنت متأكد؟...</div><br>";
		echo "<form method='post' style='max-width:500px;margin:auto;'>";
		echo "<input type='hidden' name='dstsupId' value='$dstsupId'>";
		echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='delete'> نعم </button> <button type='button' class='cnlBtn' name='mode' onclick='window.location=\"districtSupervision.php\";'> لا </button></div>";
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
		echo "<button type='submit' class='addBtn' name='mode' value='add'>إضافة جديد</button>";
		echo "</form></div><br>";
		$numberOfButtons=2;
		$buttonCellWidth=$numberOfButtons * 120;
		$buttonCellWidth .= "px";
		//filter list form 
		echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث...'>";
		echo "<table id='masterTable'><tr class='header'><th> المنطقة </th><th style='width: $buttonCellWidth'>&nbsp;</th></tr>";
		$q="SELECT dstsupId,dstsupAmount,dstsupDistrict FROM districtSupervision ORDER BY dstsupDistrict";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_execute($stmt)){
				if(mysqli_stmt_bind_result($stmt, $dstsupId,$dstsupAmount,$dstsupDistrict)){
					while(mysqli_stmt_fetch($stmt)){
						echo "<tr>";
						echo "<td> $dists[$dstsupDistrict] - $dstsupAmount </td>";
						echo "<td style='width: $buttonCellWidth;'>";
						echo "<form method='post'>";
						echo "<input type='hidden' name='dstsupId' value='$dstsupId'>";
						echo "<button type='submit' class='edtBtn' name='mode' value='edit'>تعديل</button> ";
						//echo "<button type='submit' class='viewBtn' name='mode' value='view'>عرض</button> ";
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