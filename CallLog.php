<?php
//uncomment those two lines for debugging
//ini_set('display_errors',1); 
//error_reporting(E_ALL);
session_start();
if(isset($_SESSION['uid'])){
	$usrid=$_SESSION['uid'];
	//page variables

	//list section customization variables
	$usePaging=true;
	$showNavTop=true;
	$showNavBottom=true;
	$pagesize=50;
	//Standard Queries
	$countQuery="SELECT count(*) as reccount FROM callLog";
	$listQueryBase="SELECT * FROM callLog where callUser=$usrid ORDER BY callDateTime desc";
	//standard Columns
	$showEdit=true;
	$showVeiw=false;
	$showDelete=true;
	$showNew=true;

	//phpam basic include files DO NOT REMOVE
	include('sysdb.php');
	include('functions.php');
	//uncomment line below  if you have a local functions file
	//include('functions.php');
	//set php base script file name for user permission validation
	$filename="";
	//read post data
	foreach ($_POST as $key => $value) 
	$$key=$value;
	//get get screen id to check for user permission validation
	$scrid=getscreenid($filename);
//	if(checkuserscreen($usrid,$scrid)){
	if(True){
		//write the title of your page
		//include page header and standard title 
		include('top.php');
		// set labels according to direction options
		//Navigation Labels
		if($glang=="arb"){
			$PageTitle="سجل المكالمات";
			$First="اﻷول";
			$Previous="السابق";
			$Next="التالي";
			$Last="اﻷخير";
			$Goto="إذهب الى";
			$From="من";
			$editLabel="تعديل";
			$viewLabel="عرض";
			$deleteLabel="الغاء";
			$New="جديد";
		}else{
			$PageTitle="Call log";
			$First="First";
			$Previous="Previous";
			$Next="Next";
			$Last="Last";
			$Goto="Goto";
			$From="From";
			$editLabel="Edit";
			$viewLabel="View";
			$deleteLabel="Delete";
			$New="New";
		}
		include('title.php');
		// Your code goes below this line
		$dbc= @mysqli_connect(SYSDBSERVER,SYSDBDBUSER,decrypt(SYSDBEDBPASS),SYSDBDBASE) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
		if (!mysqli_set_charset($dbc, "utf8")){
			printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
		}
		$showtable=true;

		//***********************************************************************************************************************
		//save add code
		//***********************************************************************************************************************
		if($submitted == '-5'){
			$q="insert into callLog(callContent,callUser) values('$callContent',$usrid)";
			if(runquery($dbc,$q))
				echo "<div style='color:green;text-align:center;direction:ltr;'>Data saved!...</div><br>";
			else
				echo "<div style='color:red;text-align:center;direction:ltr;'>Error saving data!...</div><br>";
		}

		//***********************************************************************************************************************
		//save edit code
		//***********************************************************************************************************************
		if($submitted == '-6'){
			$q="update callLog set callContent='$callContent' where callId=$callId";
			if(runquery($dbc,$q))
			  echo "<div style='color:green;text-align:center;direction:ltr;'>Data saved!...</div><br>";
			else
			  echo "<div style='color:red;text-align:center;direction:ltr;'>Error saving data!...</div><br>";
		}
	
		//***********************************************************************************************************************
		//delete code
		//***********************************************************************************************************************
		if($submitted == '-7'){
			$q="delete from callLog where callId=$callId";
			if(runquery($dbc,$q))
				echo "<div style='color:green;text-align:center;direction:ltr;'>تم اﻹلغاء!...</div><br>";
			else
				echo "<div style='color:red;text-align:center;direction:ltr;'>Error running query data!...<br>$q</div><br>";
		}

		//***********************************************************************************************************************
		//add form
		//***********************************************************************************************************************
		if($submitted == '-1'){
			$showtable=false;
			echo "<center><form method='post'>";
			echo "<table>";
			//input field repeat for each field if necessery devide over lines
			echo "<tr><td>Call Details</td><td>:</td><td align='right'><textarea name='callContent' placeholder='Call content' cols='50' rows='5'>$callContent</textarea></td></tr>";
			//save and cancel buttons
			echo "<input type='hidden' name='currentpage' value='$currentpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='hidden' name='submitted' value='-5'>";
			echo "<tr><td colspan='3' style='text-align:center;'><table width='100%'>";
			echo "<tr><td align='center'><input type='submit' value='حفظ'></form></td><td></td>";
			echo "<td align='center'><form method='post'>";
			echo "<input type='hidden' name='currentpage' value='$currentpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='submit' value='تراجع'></form></td></tr></table></td></tr>";
			echo "</table>";
			echo "</center>";
		}

		//***********************************************************************************************************************
		//edit form
		//***********************************************************************************************************************
		if($submitted == '-2'){
			$showtable=false;
			echo "<center><form method='post'>";
			echo "<table>";
			//input field repeat for each field if necessery devide over lines
			echo "<tr><td>Call Details</td><td>:</td><td align='right'><textarea name='callContent' placeholder='Call content' cols='50' rows='5'>$callContent</textarea></td></tr>";
			//save and cancel buttons
			echo "<input type='hidden' name='callId' value='$callId'>";
			echo "<input type='hidden' name='currentpage' value='$currentpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='hidden' name='submitted' value='-6'>";
			echo "<tr><td colspan='3' style='text-align:center;'><table width='100%'>";
			echo "<tr><td align='center'><input type='submit' value='حفظ'></form></td><td></td>";
			echo "<td align='center'><form method='post'>";
			echo "<input type='hidden' name='currentpage' value='$currentpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='submit' value='تراجع'></form></td></tr></table></td></tr>";
			echo "</table>";
			echo "</center>";
		}

		//***********************************************************************************************************************
		//display code
		//***********************************************************************************************************************
		if($submitted == '-3'){
			$showtable=false;
			$sections=ReadTable('Sections','SecId','SecName',$dbc);     	//read ref data
			echo "<center>";
			echo "<table>";
			echo "<tr><td>اسم الكتاب</td><td>:</td><td align='right'> $BookName </td></tr>";
			echo "<tr><td>القسم</td><td>:</td><td align='right'> $sections[$BookSection] </td></tr>";
			echo "<input type='hidden' name='currentpage' value='$currentpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='hidden' name='submitted' value='-5'>";
			echo "<tr><td align='center' colspan='3'><form method='post'>";
			echo "<input type='hidden' name='currentpage' value='$currentpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='submit' value='حسنا'></form></td></tr>";
			echo "</table>";
			echo "</center>";	
		}
	
		//***********************************************************************************************************************
		//delete confirmation form
		//***********************************************************************************************************************
		if($submitted == '-4'){
			$showtable=false;
			echo "<center>";
			echo "<table>";
			echo "<tr><tdcolspan='3'>سيتم الغاء بيانات المكالمة بتاريخ $callDateTime <br> هل أنت متأكد؟...</td></tr>";
			echo "<tr><td align='center'><form method='post'>";
			echo "<input type='hidden' name='callId' value='$callId'>";
			echo "<input type='hidden' name='currentpage' value='$currentpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='hidden' name='submitted' value='-7'>";
			echo "<input type='submit' value='موافق'></form></td><td></td>";
			echo "<td align='center'><form method='post'>";
			echo "<input type='hidden' name='currentpage' value='$currentpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='submit' value='تراجع'></form></td></tr>";
			echo "</table>";
			echo "</center>";
		}

	//***********************************************************************************************************************
	//Show the list
	//***********************************************************************************************************************
	if($showtable){

		echo "<center>";
		//New record form
		if($showNew)
			echo "<table align='center' border='0'><tr><td><form method='post'><input type='submit' value=' $New '><input type='hidden' name='submitted' value='-1'><input type='hidden' name='FilterText' value='$FilterText'><input type='hidden' name='currentpage' value='$currentpage'></form></td></tr></table><br>";

		//count the records to be displayed
		$reccount=0;
		if($usePaging)
			$reccount=getfield($dbc,$countQuery,"reccount");
		if($showNavTop and $reccount != 0){
		//Build navigation bar variables don't edit
			$pages=ceil($reccount / $pagesize);
			$firstpage=1;
			$lastpage=$pages;
			if($currentpage<1) $currentpage=1;
			$previouspage=$currentpage-1;
			if($previouspage<1) $previouspage=1;
			$nextpage=$currentpage+1;
			if($nextpage>$lastpage) $nextpage=$lastpage;
			$firstrec=($currentpage-1)*$pagesize;

			//Display Top Navigation Bar don't edit
			echo "<table><tr>\n";
			//first rec
			echo  "<td>";
			echo  "<form name='fpage' method='post'>";
			echo "<input type='hidden' name='currentpage' value='$firstpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='submit' value='$First'></form>";
			echo "</td>\n";
			//previous rec
			echo  "<td>";
			echo  "<form name='ppage' method='post'>";
			echo "<input type='hidden' name='currentpage' value='$previouspage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='submit' value='$Previous'></form>";
			echo "</td>\n";
			//next rec
			echo  "<td>";
			echo  "<form name='npage' method='post'>";
			echo "<input type='hidden' name='currentpage' value='$nextpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='submit' value='$Next'></form>";
			echo "</td>\n";
			//last rec
			echo  "<td>";
			echo  "<form name='lpage' method='post'>";
			echo "<input type='hidden' name='currentpage' value='$lastpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='submit' value='$Last'></form>";
			echo "</td>\n";
			//goto form
			echo  "<td align='center'>";
			echo  "<form name='gotopage' method='post'>";
			echo "<input type='hidden' name='oldcurrentpage' value='$currentpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='submit' value='$Goto'> ";
			echo "<input type='text' name='currentpage' size='4' value='$currentpage'> $From $pages</form>";
			echo "</td>\n";
			echo "</tr>";
			echo "</table>";
		}
		echo "<table style='text-align:center;border-spacing:3px;border-color:$tblborderclr;'>";
		//query to get the record to be displayed
		$q=$listQueryBase;
		if($reccount!=0) $q .= " limit $firstrec,$pagesize";
		$r=mysqli_query($dbc,$q);
		if($r){
			//column headers - Add columns as needed
			echo "<tr style='font-size:medium;font-weight:bold;background-color:$tblheadbg;'>";
			echo "<td>&nbsp;Call date/time&nbsp;</td>";
			echo "<td width='400px'>&nbsp;Call content&nbsp;</td>";
			echo str_repeat("<td>&nbsp;</td>",2);		//empty cells for action buttons
			echo "</tr>";
			while($row=mysqli_fetch_array($r)){
				foreach ($row as $key => $value)
					if(!is_numeric($key))
						$$key=$value;
			//Data columns
			echo "<tr style='font-size:medium;font-weight:bold;background-color:$tblcellbg;'>";
			echo "<td style='text-align:center;'>&nbsp;$callDateTime &nbsp;</td>";
			echo "<td style='text-align:right;'>&nbsp;$callContent &nbsp;</td>";
			//Button columns don't edit
			//Edit Button
			if($showEdit){
				echo "<td><form method='post'><input type='hidden' name='submitted' value='-2'>";
				foreach ($row as $key => $value) {
					echo "<input type='hidden' name='$key' value='$value'>" ; 
				}
				echo "<input type='hidden' name='currentpage' value='$currentpage'>";
				echo "<input type='hidden' name='FilterText' value='$FilterText'>";
				echo "<input type='submit' value='$editLabel'></form></td>\r\n";
			}
			//View button
			if($showVeiw){
				echo "<td><form method='post'><input type='hidden' name='submitted' value='-3'>";
				foreach ($row as $key => $value) {
					echo "<input type='hidden' name='$key' value='$value'>" ; 
				}
				echo "<input type='hidden' name='currentpage' value='$currentpage'>";
				echo "<input type='hidden' name='FilterText' value='$FilterText'>";
				echo "<input type='submit' value='$viewLabel'></form></td>\r\n";
			}
			//Delete Button
			if($showDelete){
				echo "<td><form method='post'><input type='hidden' name='submitted' value='-4'>";
				foreach ($row as $key => $value) {
					echo "<input type='hidden' name='$key' value='$value'>" ; 
				}
				echo "<input type='hidden' name='currentpage' value='$currentpage'>";
				echo "<input type='hidden' name='FilterText' value='$FilterText'>";
				echo "<input type='submit' value='$deleteLabel'></form></td>\r\n";
			}
			//Extra buttons un-comment code below for extra button and repeat as many as needed
			/*
			echo "<td><form method='post' action='Type action script name' target='State target if needed'>";
			foreach ($row as $key => $value) {
				echo "<input type='hidden' name='$key' value='$value'>" ; 
			}
			echo "<input type='hidden' name='currentpage' value='$currentpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='submit' value='Button label'></form></td>\r\n";
			*/
			echo "</tr>\r\n";
			}
			mysqli_free_result($r);
		}

		echo "</table>";

		if($showNavBottom and $reccount != 0){
			//Display Bottom Navigation Bar don't edit
			echo "<table><tr>\n";
			//first rec
			echo  "<td>";
			echo  "<form name='fpage' method='post'>";
			echo "<input type='hidden' name='currentpage' value='$firstpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='submit' value='$First'></form>";
			echo "</td>\n";
			//previous rec
			echo  "<td>";
			echo  "<form name='ppage' method='post'>";
			echo "<input type='hidden' name='currentpage' value='$previouspage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='submit' value='$Previous'></form>";
			echo "</td>\n";
			//next rec
			echo  "<td>";
			echo  "<form name='npage' method='post'>";
			echo "<input type='hidden' name='currentpage' value='$nextpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='submit' value='$Next'></form>";
			echo "</td>\n";
			//last rec
			echo  "<td>";
			echo  "<form name='lpage' method='post'>";
			echo "<input type='hidden' name='currentpage' value='$lastpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='submit' value='$Last'></form>";
			echo "</td>\n";
			//goto form
			echo  "<td align='center'>";
			echo  "<form name='gotopage' method='post'>";
			echo "<input type='hidden' name='oldcurrentpage' value='$currentpage'>";
			echo "<input type='hidden' name='FilterText' value='$FilterText'>";
			echo "<input type='submit' value='$Goto'> ";
			echo "<input type='text' name='currentpage' size='4' value='$currentpage'> $From $pages</form>";
			echo "</td>\n";                                        
			echo "</tr>";
			echo "</table>";
		}
		echo "</center>";
	}
	mysqli_close($dbc);
	// Your code ends above this line
	include('../bot.php');
	}else{
	$target="../index.php";
	include('../redirect.php');
	}
}else{
	$target="../index.php";
	include('../redirect.php');
}
?>
