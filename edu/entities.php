<?php
//uncomment those two lines for debugging
ini_set('display_errors',1); 
error_reporting(E_ALL);
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
$title="جهات العهدة";
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
        echo "<br><div class='errorMessages' style='direction: rtl'>Access denied!...</div>";    
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
// sec:edit-view-deleteconfirm read a record for view or edit
//*****************************************************************************************
if($mode=="edit" or $mode=="view" or $mode=="deleteconfirm"){
    $q="SELECT entDescription FROM entities WHERE entId=?";
    if($stmt=mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $entId)){
            if(mysqli_stmt_execute($stmt)){
                if(mysqli_stmt_bind_result($stmt, $entDescription)){
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
// sec:saveedit-saveadd validate for saveedit or saveadd
//*****************************************************************************************
if($mode=="saveedit" or $mode=="saveadd"){
    $errorMessage="";
    if($entDescription==""){
        $errorMessage="لابد من ادخال اسم  الجهة<br>";
    }

    if($errorMessage != ""){
        $mode=substr($mode,4);
    }
}

//*****************************************************************************************
// sec:saveedit save edit data
//*****************************************************************************************
if($mode=="saveedit"){
    $q="UPDATE entities SET entDescription=? WHERE entId=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt,"si",$entDescription,$entId)){
            try{
                if(mysqli_stmt_execute($stmt)){
                    $infoMessage="حفظ!...";
                }    
            }
            catch(Exception $e){
                $errorMessage=$e->getMessage();
            }            
        }
        mysqli_stmt_close($stmt);
    }
    $mode="showList";
}


//*****************************************************************************************
// sec:saveadd Save added data
//*****************************************************************************************
if($mode=="saveadd"){
    $q="INSERT INTO entities(entDescription) VALUES(?)";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt,"s",$entDescription)){
            if(mysqli_stmt_execute($stmt)){
                $infoMessage="تم الحفظ!...";
            }else{
                $errorMessage="خطأ في الحفظ<br>".mysqli_error($dbc)."!...";
            } 
        }
        mysqli_stmt_close($stmt);
    }
    $mode="showList";
}

//*****************************************************************************************
// sec:delete delete record
//*****************************************************************************************
if($mode == "delete"){
    $q="DELETE FROM entities WHERE entId=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt,"i",$entId)){
            if(mysqli_stmt_execute($stmt)){
                $infoMessage="تم الالغاء!...";
            }    
        }
        mysqli_stmt_close($stmt);
    }
    $mode="showList";
}


//*****************************************************************************************
// sec:add-edit Add/Edit form
//*****************************************************************************************
if($mode=="add" or $mode=="edit"){
    if(isset($errorMessage)){
        echo "<div class='errorMessages'>$errorMessage</div>";
    }
    if($mode=="add"){
        $formTitle="اضف كيان جديد";
    }else{
        $formTitle="تعديل  الجهة";
    }

    echo "<h3>$formTitle</h3>";
    echo "<center><form method='post'>";
    echo "<table>";

    echo "<tr><td style='width: 100px;'>اسم  الجهة:</td><td style='width:650px;'>";
    echo "<div class='input-container'>";
    echo "<input class='input-field' type='text' placeholder='اسم  الجهة' maxlength='255' name='entDescription'";
    if(isset($entDescription)){
        echo " value='$entDescription'";
    } 
    echo "></div></td></tr>";  

    echo "</table>";
    if($mode=='edit'){
        echo "<input type='hidden' name='entId' value='$entId'>";
    }
    $newMode="save$mode";
    //save and cancel buttons
    echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'> حفظ </button> <button type='button' class='cnlBtn' onclick='window.location=\"$filename\";'> تراجع </button></div>";
    echo "</form>";
    echo "</center>";    
}


//*****************************************************************************************
// sec:view display code
//*****************************************************************************************
if($mode == "view"){
    echo "<center>";
    echo "<h3> بيانات  الجهة </h3>";
    echo "<table>";
    echo "<tr><td>Description</td><td>:</td><td align='right'> $entDescription </td></tr>";
    echo "</table>";
    echo "<div class='frmButtons'><button type='button' class='okBtn' onclick='window.location=\"$filename\";'> حسنا </button></div>";
    echo "</center>";    
}


//*****************************************************************************************
// sec:deleteconfirm delete confirm
//*****************************************************************************************
if($mode=="deleteconfirm"){
    echo "<center>";
    echo "<h3> الغاء  الجهة </h3>";
    echo "<div style='text-align: center;'>سيتم الغاء $entDescription <br> هل أنت متأكد؟...</div><br>";
    echo "<form method='post' style='max-width:500px;margin:auto;'>";
    echo "<input type='hidden' name='entId' value='$entId'>";
    echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='delete'> نعم </button> <button type='button' class='cnlBtn' name='mode' onclick='window.location=\"$filename\";'> لا </button></div>";
    echo "</form>";
    echo "</center>";
}


//*****************************************************************************************
// sec:list the record list
//*****************************************************************************************
if($mode=="showList"){
    if($errorMessage!=""){
        echo "<div class='errorMessages'>$errorMessage</div>";
        echo "<br><br>";
    }
    if($infoMessage!=""){
        echo "<div class='infoMessages'>$infoMessage</div>";
        echo "<br><br>";
    }
        //new form
        echo "<div style='width: 110px; margin: auto;'><form method='post'>";
        echo "<button type='submit' class='addBtn' name='mode' value='add'>جديد</button>";
        echo "</form></div><br>";
        $numberOfButtons=3;
        $buttonCellWidth=$numberOfButtons * 115;
        $buttonCellWidth .= "px";
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث...'>";
        echo "<table id='masterTable'><tr class='header'><th>  الجهة </th><th style='width: $buttonCellWidth'>&nbsp;</th></tr>";
        $q="SELECT entId, entDescription FROM entities ORDER BY entId";
        $res=mysqli_query($dbc,$q);
        while($row=mysqli_fetch_assoc($res)){
            echo "<tr><td align='right'>".$row['entDescription']."</td>
                    <td align='center' style='width: $buttonCellWidth'>
                    <form method='post'>
                    <input type='hidden' name='entId' value='".$row['entId']."'>
                    <button class='viewBtn' type='submit' name='mode' value='view'>عرض</button> 
                    <button class='edtBtn' type='submit' name='mode' value='edit'>تعديل</button> 
                    <button class='delBtn' type='submit' name='mode' value='deleteconfirm'>حذف</button>
                    </form></td></tr>";
        }
        echo "</table>";
    }
}
if(isset($dbc)){
    mysqli_close($dbc);
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