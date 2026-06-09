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
	if(isset($_SESSION['caller'])){
		$caller=$_SESSION['caller'];
	}
	if(isset($_SESSION['sigdocId'])){
		$sigdocId=$_SESSION['sigdocId'];
	}
    $sesOk=true;
}else{
    $sesOk=false;
}
$gdir="ltr";
$galign="left";
$title="Document Signatures";
$subtitle="signatures";
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
            .selectList{
                width:400px;
                height:270px;
                font-size: 18px;
            }
		</style>
    </head>
    <body>
	<br><h2><?php echo $title; ?></h2><h3><?php echo $subtitle; ?></h3>
<?php
if($sesOk){
    //session is up check for user permission!...   
    $filename=basename(__FILE__);                               //get script name
	$filename=$caller;
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
//sec:addOne
//*****************************************************************************************
	if($mode=="addOne"){
		$q="SELECT IFNULL(MAX(`sigOrder`),0) AS lastOrder FROM `signatures` WHERE `sigDoc`=?";
		$parameters=array($sigdocId);
		$types="i";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,$types,...$parameters)){
				if(mysqli_stmt_execute($stmt)){
					if(mysqli_stmt_bind_result($stmt,$lastOrder)){
						if(mysqli_stmt_fetch($stmt)){
							$newOrder=$lastOrder+1;
						}
					}
				}
			}
			mysqli_stmt_close($stmt);
		}
		if(isset($sigImageFrom)){
			$q="INSERT INTO signatures(sigImage,sigDoc,sigOrder) VALUE(?,?,?)";
			$parameters=array($sigImageFrom,$sigdocId,$newOrder);
			$types="iii";
			if ($stmt = mysqli_prepare($dbc, $q)){
				if(mysqli_stmt_bind_param($stmt,$types,...$parameters)){
					mysqli_stmt_execute($stmt);
				}
				mysqli_stmt_close($stmt);
			}	
		}
	}

//*****************************************************************************************
//sec:remOne
//*****************************************************************************************
if($mode=="remOne"){
	if(isset($sigImageTo)){
		$q="DELETE FROM signatures WHERE sigImage=? AND sigDoc=?";
		$parameters=array($sigImageTo,$sigdocId);
		$types="ii";
		if ($stmt = mysqli_prepare($dbc, $q)){
			if(mysqli_stmt_bind_param($stmt,$types,...$parameters)){
				mysqli_stmt_execute($stmt);
			}
			mysqli_stmt_close($stmt);
		}	
	}
}

//*****************************************************************************************
//sec: lists
//*****************************************************************************************
	echo "<center>";
	echo "<form method='post'>";
	echo "<table>";
	echo "<tr>";
	echo "<td>";
	echo "<select name='sigImageFrom' size='10' id='sigImageFrom' class='selectList' ondblclick='addone();'>";
	$q="SELECT sigimgId,sigimgDescription FROM signatureImages WHERE sigimgId NOT IN(SELECT sigImage FROM signatures WHERE sigDoc=?)";
	$parameters=array($sigdocId);
	$types="i";
	if ($stmt = mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt,$types,...$parameters)){
			if(mysqli_stmt_execute($stmt)){
				if(mysqli_stmt_bind_result($stmt,$sigimgId,$sigimgDescription)){
					while(mysqli_stmt_fetch($stmt)){
						echo "<option value='$sigimgId'>$sigimgDescription</option>";
					}
				}
			}
		}
		mysqli_stmt_close($stmt);
	}
	echo "</select>";
	echo "</td>";
	echo "<td>";
	echo "<button type='submit' class='savBtn' name='mode' value='addOne' id='addOne'> &gt; </button>";
	echo "<br><br>";
	echo "<button type='submit' class='savBtn' name='mode' value='remOne' id='remOne'> &lt; </button>";
	echo "</td>";
	echo "<td>";
	echo "<select name='sigImageTo' size='10' id='sigImageTo' class='selectList' ondblclick='remone();'>";
	$q="SELECT sigimgId,sigimgDescription,sigOrder,sigId FROM signatureImages INNER JOIN signatures ON sigimgId=sigImage WHERE sigimgId IN(SELECT sigImage FROM signatures WHERE sigDoc=?)";
	$parameters=array($sigdocId);
	$types="i";
	if ($stmt = mysqli_prepare($dbc, $q)){
		if(mysqli_stmt_bind_param($stmt,$types,...$parameters)){
			if(mysqli_stmt_execute($stmt)){
				if(mysqli_stmt_bind_result($stmt,$sigimgId,$sigimgDescription,$sigOrder,$sigId)){
					while(mysqli_stmt_fetch($stmt)){
						echo "<option value='$sigimgId'>$sigOrder-[$sigId]-$sigimgDescription</option>";
					}
				}
			}
		}
		mysqli_stmt_close($stmt);
	}
	echo "</select>";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</form>";
	echo "<br>";
	echo "<button type='button' class='cnlBtn' name='mode' onclick='window.location=\"$caller\";'> Back </button>";
	echo "</center>";
}
?>
	<script>
	function addone(){
		document.getElementById('addOne').click();
	}
	function remone(){
		document.getElementById('remOne').click();
	}
	</script>
    </body>
</html>