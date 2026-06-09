<?php
echo "<!DOCTYPE html>";
echo "<html><head><meta charset=\"UTF-8\">";
echo "<title>System database</title>";
echo "<style>";
echo "body {font-family: Arial, Helvetica, sans-serif;text-align: center;} * {box-sizing: border-box;}";
echo ".btn {background-color: dodgerblue;  color: white;  padding: 15px 20px;  border: none;  cursor: pointer;  width: 100%;  opacity: 0.9;}";
echo ".btn:hover {opacity: 1;}";
echo ".ok {color:green;}";
echo ".qry {color: blue;}";
echo ".err {color: red;}";
echo "h2{color: #30589a;}";
echo ".error{color: red; font-size: small;text-align:center}";
echo "</style>";
echo "</head>";
echo "<body><h2>System Database</h2>";
$error=false;
foreach($_POST as $key => $value)
    $$key=$value;
$errorMessage="";
if(isset($submitted)){
	if($host == ""){
		$errorMessage.="Must Enter Host!<br>";
		unset($submitted);
    }
    if($dbase==""){
		$errorMessage.="Must Enter database!<br>";
		unset($submitted);
    }
    if($user==""){
		$errorMessage.="Must E<?phpnter User Name!<br>";
		unset($submitted);
    }
    if($password==""){
		$errorMessage.="Must Enter password!<br>";
		unset($submitted);
    }
    if($errorMessage == ""){
		//create sys.db file
		$handle = fopen("../sys.db", "w") or die("Unable to open file!");
		fwrite($handle, "$host\n");
		fwrite($handle, "$user\n");
		fwrite($handle, "$password\n");
		fwrite($handle, "$dbase\n");
		fclose($handle);
		echo "<div style='width: 800px; margin: auto;'>";
		echo "<span class='ok'>sys.db saved!...</span><br>";
		echo "Host: $host<br>";
		echo "Database: $dbase<br>";
		echo "User: $user<br>";
		echo "Password: $password<br>";
		echo "------------------<br>";
		// Database creation commands
		$q[0]="CREATE SCHEMA `$dbase` DEFAULT CHARACTER SET utf8";
		$q[1]="CREATE TABLE `$dbase`.`colorgroups` (`clrgrpId` int(11) NOT NULL AUTO_INCREMENT,`clrgrpNameEn` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `clrgrpNameAr` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, PRIMARY KEY (`clrgrpId`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
		$q[2]="CREATE TABLE `$dbase`.`colornames` (`clrId` int(11) NOT NULL AUTO_INCREMENT,`clrName` varchar(128) DEFAULT NULL,`clrArName` varchar(128) DEFAULT NULL,`clrVariable` varchar(45) DEFAULT NULL,`clrGroup` int(11) DEFAULT NULL,PRIMARY KEY (`clrId`),KEY `clrGroup` (`clrGroup`),CONSTRAINT `colornames_ibfk_1` FOREIGN KEY (`clrGroup`) REFERENCES `colorgroups` (`clrgrpId`) ON DELETE CASCADE ON UPDATE CASCADE ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
		$q[3]="CREATE TABLE `$dbase`.`frmTypes` (`frmTypeId` int(11) NOT NULL AUTO_INCREMENT,`frmTypeName` varchar(128) NOT NULL,`frmTypeDesigner` varchar(128) NOT NULL,PRIMARY KEY (`frmTypeId`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
		$q[4]="CREATE TABLE `$dbase`.`forms` (`frmId` int(11) NOT NULL AUTO_INCREMENT,`frmName` varchar(128) COLLATE utf8_unicode_ci NOT NULL,`frmType` int(11) NOT NULL,PRIMARY KEY (`frmId`),KEY `frmType` (`frmType`),CONSTRAINT `forms_ibfk_1` FOREIGN KEY (`frmType`) REFERENCES `frmTypes` (`frmTypeId`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$q[5]="CREATE TABLE `$dbase`.`groups` (`grpId` int(11) NOT NULL AUTO_INCREMENT,`grpName` varchar(128) DEFAULT NULL,PRIMARY KEY (`grpId`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
		$q[6]="CREATE TABLE `$dbase`.`menus` (`mnuId` int(11) NOT NULL AUTO_INCREMENT,`mnuName` varchar(45) DEFAULT NULL,`mnuParent` int(11) DEFAULT NULL,`mnuOrder` int(11) DEFAULT '1',`mnuCommand` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,`mnuCmdType` int(11) DEFAULT '0',`mnuDirectory` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,`mnuLevel` int(11) DEFAULT '0',PRIMARY KEY (`mnuId`),KEY `mnuParent` (`mnuParent`),CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`mnuParent`) REFERENCES `menus` (`mnuId`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
		$q[7]="CREATE TABLE `$dbase`.`userclass` (`usrclsId` int(11) NOT NULL AUTO_INCREMENT,`usrclsDescription` varchar(45) DEFAULT NULL,PRIMARY KEY (`usrclsId`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
		$q[8]="CREATE TABLE `$dbase`.`rptTypes` (`rptTypeId` int(11) NOT NULL AUTO_INCREMENT,`rptTypeName` varchar(128) COLLATE utf8_unicode_ci NOT NULL,`rptTypeDesigner` varchar(128) COLLATE utf8_unicode_ci NOT NULL,PRIMARY KEY (`rptTypeId`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$q[9]="CREATE TABLE `$dbase`.`reports` (`rptId` int(11) NOT NULL AUTO_INCREMENT,`rptName` varchar(128) COLLATE utf8_unicode_ci NOT NULL,`rptType` int(11) NOT NULL,PRIMARY KEY (`rptId`),KEY `rptType` (`rptType`),CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`rptType`) REFERENCES `rptTypes` (`rptTypeId`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$q[10]="CREATE TABLE `$dbase`.`groupmenus` (`grpmnuGroup` int(11) NOT NULL,`grpmnuMenu` int(11) NOT NULL,PRIMARY KEY (`grpmnuGroup`,`grpmnuMenu`),KEY `fk_groupmenus_menus1_idx` (`grpmnuMenu`),CONSTRAINT `fk_groupmenus_groups1` FOREIGN KEY (`grpmnuGroup`) REFERENCES `groups` (`grpId`) ON DELETE NO ACTION ON UPDATE NO ACTION,CONSTRAINT `fk_groupmenus_menus1` FOREIGN KEY (`grpmnuMenu`) REFERENCES `menus` (`mnuId`) ON DELETE NO ACTION ON UPDATE NO ACTION) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		$q[11]="CREATE TABLE `$dbase`.`users` (`usrId` int(11) NOT NULL AUTO_INCREMENT,`usrName` varchar(100) DEFAULT NULL,`usrPassword` varchar(100) DEFAULT NULL,`usrFullName` varchar(255) DEFAULT NULL,`usrClass` int(11) DEFAULT NULL,`usrAllowRemote` tinyint(1) DEFAULT NULL,PRIMARY KEY (`usrId`),KEY `fk_users_userclass_idx` (`usrClass`),CONSTRAINT `fk_userclass_ibfk_1` FOREIGN KEY (`usrClass`) REFERENCES `userclass` (`usrclsId`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
		$q[12]="CREATE TABLE `$dbase`.`usergroups` (`usrgrpUser` int(11) NOT NULL,`usrgrpGroup` int(11) NOT NULL,PRIMARY KEY (`usrgrpUser`,`usrgrpGroup`),KEY `fk_usergroups_groups1_idx` (`usrgrpGroup`),CONSTRAINT `fk_usergroups_groups1` FOREIGN KEY (`usrgrpGroup`) REFERENCES `groups` (`grpId`) ON DELETE NO ACTION ON UPDATE NO ACTION,CONSTRAINT `fk_usergroups_users1` FOREIGN KEY (`usrgrpUser`) REFERENCES `users` (`usrId`) ON DELETE NO ACTION ON UPDATE NO ACTION) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		$q[13]="CREATE TABLE `$dbase`.`usermenus` (`usrmnuUser` int(11) NOT NULL,`usrmnuMenu` int(11) NOT NULL,PRIMARY KEY (`usrmnuUser`,`usrmnuMenu`),KEY `fk_usermenus_menus1_idx` (`usrmnuMenu`),CONSTRAINT `fk_usermenus_menus1` FOREIGN KEY (`usrmnuMenu`) REFERENCES `menus` (`mnuId`) ON DELETE NO ACTION ON UPDATE NO ACTION,CONSTRAINT `fk_usermenus_users1` FOREIGN KEY (`usrmnuUser`) REFERENCES `users` (`usrId`) ON DELETE NO ACTION ON UPDATE NO ACTION) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		$q[14]="CREATE TABLE `$dbase`.`usercolors` (`usrclrUserId` int(11) NOT NULL,`usrclrColorId` int(11) NOT NULL,`usrclrColorValue` varchar(7) DEFAULT NULL,PRIMARY KEY (`usrclrUserId`,`usrclrColorId`),KEY `fk_usercolors_colornames1_idx` (`usrclrColorId`),CONSTRAINT `fk_usercolors_colornames1` FOREIGN KEY (`usrclrColorId`) REFERENCES `colornames` (`clrId`) ON DELETE NO ACTION ON UPDATE NO ACTION,CONSTRAINT `fk_usercolors_users1` FOREIGN KEY (`usrclrUserId`) REFERENCES `users` (`usrId`) ON DELETE NO ACTION ON UPDATE NO ACTION) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		$q[15]="CREATE TABLE `$dbase`.`sysvalues` (`svlId` INT NOT NULL AUTO_INCREMENT,`svlName` VARCHAR(255) NOT NULL, `svlValue` VARCHAR(255) NOT NULL, PRIMARY KEY (`svlId`), UNIQUE INDEX `sysvalname_UNIQUE` (`svlName` ASC)) ENGINE = InnoDB";
		$q[16]="CREATE TABLE `$dbase`.`messages` (`msgId` int(11) NOT NULL AUTO_INCREMENT,`msgTitle` varchar(100) COLLATE utf8_unicode_ci NOT NULL,`msgBody` longtext COLLATE utf8_unicode_ci NOT NULL,`msgFrom` int(11) NOT NULL,`msgTo` int(11) NOT NULL,`msgDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,`msgRead` int(11) NOT NULL DEFAULT '0',`msgDeleted` int(11) NOT NULL DEFAULT '0',PRIMARY KEY (`msgId`),KEY `msgFrom` (`msgFrom`),KEY `msgTo` (`msgTo`),CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`msgFrom`) REFERENCES `users` (`usrId`) ON DELETE CASCADE ON UPDATE CASCADE,CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`msgTo`) REFERENCES `users` (`usrId`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$q[17]="CREATE TABLE `$dbase`.`msgAttachments` (`matId` int(11) NOT NULL AUTO_INCREMENT,`matMessage` int(11) NOT NULL,`matFile` varchar(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,PRIMARY KEY (`matId`),KEY `matMessage` (`matMessage`),CONSTRAINT `msgAttachments_ibfk_2` FOREIGN KEY (`matMessage`) REFERENCES `messages` (`msgId`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		$q[18]="CREATE TABLE `$dbase`.`notes` (`noteId` int(11) NOT NULL AUTO_INCREMENT,`noteTitle` varchar(100) COLLATE utf8_unicode_ci NOT NULL,`noteText` longtext COLLATE utf8_unicode_ci NOT NULL,`noteUser` int(11) NOT NULL,PRIMARY KEY (`noteId`),KEY `noteUser` (`noteUser`),CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`noteUser`) REFERENCES `users` (`usrId`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$q[19]="CREATE TABLE `$dbase`.`phoneBook` (`contId` int(11) NOT NULL AUTO_INCREMENT,`contName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,`contTele` varchar(25) COLLATE utf8_unicode_ci NOT NULL,`contUser` int(11) NOT NULL,PRIMARY KEY (`contId`),KEY `contUser` (`contUser`),CONSTRAINT `phoneBook_ibfk_1` FOREIGN KEY (`contUser`) REFERENCES `users` (`usrId`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$q[20]="CREATE TABLE `$dbase`.`tasks` (`tskId` int(11) NOT NULL AUTO_INCREMENT,`tsktitle` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,`tskDescription` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,`tskStartDate` date NOT NULL,`tskEndDate` date NOT NULL,`tskPercentComplete` int(11) NOT NULL,`tskUser` int(11) NOT NULL,PRIMARY KEY (`tskId`),KEY `tskUser` (`tskUser`),CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`tskUser`) REFERENCES `users` (`usrId`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8";
		$q[21]="INSERT INTO `$dbase`.`colorgroups` (`clrgrpId`, `clrgrpNameEn`, `clrgrpNameAr`) VALUES(1,'Data Screens','شاشات البيانات'),(2,'Menus and status bar','القوائم وشريط الحالة'),(3,'Buttons','اﻷزرار'),(4,'Login screen','شاشة الولوج')";
		$q[22]="INSERT INTO `$dbase`.`colornames` (`clrId`, `clrName`, `clrArName`, `clrVariable`, `clrGroup`) VALUES(1, 'Text color','لون كتابة الصفحة','txtColor',1),(2,'Background color','لون خلفية الصفحة','bgColor',1),(3,'List color','لون الكتابة في الجداول','listFG',1),(4,'List background','لون خلفية الجداول','listBG',1),(5,'List heading color','لون الكتابة في رأس الجدول','lstHdFG',1),(6,'List heading background','لون خلفية رأس الجدول','lstHdBG',1),(7,'Top bar text color','لون كتابة الشريط العلوي','topBarFG',2),(8,'Top bar background','لون خلفية الشريط العلوي','topBarBG',2),(9,'Top bar button color','لون كتابة عناصر القائمة الرئيسية','topBarActiveFG',2),(10, 'Top bar button background','لون خلفية عناصر القائمة الرئيسية','topBarActiveBG',2),(11, 'Menu text color','لون القوائم','menuBarFG',2),(12, 'Menu background','لون خلفية القائمة','menuBarBG',2),(13, 'Menu button color','لون كتابة عناصر القوائم','menuBarActiveFG',2),(14, 'Menu button background','لون خلفية عناصر القائمة','menuBarActiveBG',2),(15, 'Dropdown text color','لون كتابة القائمة المنسدلة','dropdownFG',2),(16, 'Dropdown background','لون خلفية القائمة المنسدلة','dropdownBG',2),(17, 'Dropdown active text color','لون كتابة العنصر النشط في القائمة المنسدلة','dropdownActiveFG',2),(18, 'Dropdown active background','لون خلفية العنصر النشط في القائمة المنسدلة','dropdownActiveBG',2),(19, 'Status bar text color','لون كتابة شريط الحالة','statusBarFG',2),(20, 'Status bar background','لون خلفية شريط الحالة','statusBarBG',2),(21, 'Ok Button Text','لون كتابة زر الموافقة','okButtonFg',3),(22, 'Ok button background','لون خلفية زر الموافقة','okButtonBg',3),(23, 'Add button text','لون كتابة زر الاضافة','newButtonFg',3),(24, 'Add button background','لون خلفية زر الاضافة','newButtonBg',3),(25, 'Edit button text','لون كتابة زر التعديل','editButtonFg',3),(26, 'Edit button background','لون خلفية زر التعديل','editButtonBg',3),(27, 'Delete button text','لون كتابة زر الالغاء','deleteButtonFg',3),(28, 'Delete button background','لون خلفية زر الالغاء','deleteButtonBg',3),(29, 'Save button text','لون كتابة زر الحفظ','saveButtonFg',3),(30, 'Save button background','لون خلفية زر الحفظ','saveButtonBg',3),(31, 'Cancel button text','لون كتابة زر التراجع','cancelButtonFg',3),(32, 'Cancel button background','لون خلفية زر التراجع','cancelButtonBg',3),(33, 'View button text','لون كتابة زر العرض','viewButtonFg',3),(34, 'View button background','لون خلفية زر العرض','viewButtonBg',3),(35, 'Yes button text','لون كتابة زر نعم','yesButtonFg',3),(36, 'Yes button background','لون خلفية زر نعم','yesButtonBg',3),(37, 'No button text','لون كتابة زر لا','noButtonFg',3),(38, 'No button background','لون خلفية زر لا','noButtonBg',3),(39, 'Login message text','لون كتابة رسالة الولوج','loginMessageText',4),(40, 'Login form icons foreground','لون أيقونات نموذج الولوج','loginFormIconsFG',4),(41, 'Login form icons background','لون خلفية أيقونات الولوج','loginFormIconsBG',4),(42, 'Login form fields border','لون إطار حقول نموذج الولوج','loginFormFields',4),(43, 'Login button text','لون كتابة زر الولوج','loginFormButtonFG',4),(44, 'Login button background','لون خلفية زر الولوج','loginFormButtonBG',4),(45, 'Login status bar foreground','لون كتابة شريط الحالة بشاشة الولوج','loginStatusBarFG',4),(46, 'Login status bar background','لون خلفية شريط الحالة بشاشة الولوج','loginStatusBarBG',4)";
		$q[23]="INSERT INTO `$dbase`.`frmTypes` (`frmTypeId`, `frmTypeName`, `frmTypeDesigner`) VALUES(1,'Crud','crudDesigner.php'),(2,'Selector','selectorDesigner.php'),(3,'Master Detail','masterDesigner.php')";
		$q[24]="INSERT INTO `$dbase`.`groups` (`grpId`, `grpName`) VALUES(1,'All users')";
		$q[25]="INSERT INTO `$dbase`.`userclass` (`usrclsId`, `usrclsDescription`) VALUES(1,'User'),(2,'Admin')";
		$q[26]="INSERT INTO `$dbase`.`rptTypes` (`rptTypeId`, `rptTypeName`, `rptTypeDesigner`) VALUES(1,'Columnar','columnarDesigner.php'),(2,'Cross','crossDesigner.php')";
		$q[27]="INSERT INTO `sysvalues` (`svlId`, `svlName`, `svlValue`) VALUES(1,'databaseManager','adminer.php'),(2,'pwdMaxNums','4'),(3,'pwdMaxCaps','4'),(4,'pwdMaxLwrs','4'),(5,'systemLogo','phpam.png'),(6,'systemName','phpam ver. 1.0'),(7,'defaultGroupId','1'),(8,'databaseManagerTarget','_self'),(9,'txtColor','#000000'),(10,'bgColor','#ffffff'),(11,'listFG','#000000'),(12,'listBG','#ffffff'),(13,'lstHdFG','#000000'),(14,'lstHdBG','#f1f1f1'),(15,'topBarFG','#ffffff'),(16,'topBarBG','#30589a'),(17,'topBarActiveFG','#ffffff'),(18,'topBarActiveBG','#ff0000'),(19,'menuBarFG','#ffffff'),(20,'menuBarBG','#ff0000'),(21,'menuBarActiveFG','#000000'),(22,'menuBarActiveBG','#ffffff'),(23,'dropdownFG','#000000'),(24,'dropdownBG','#ffffff'),(25,'dropdownActiveFG','#000000'),(26,'dropdownActiveBG','#eeeeee'),(27,'statusBarFG','#ffffff'),(28,'statusBarBG','#30589a'),(29,'okButtonFg','#ffffff'),(30,'okButtonBg','#4B0082'),(31,'newButtonFg','#ffffff'),(32,'newButtonBg','#191970'),(33,'editButtonFg','#ffffff'),(34,'editButtonBg','#008080'),(35,'deleteButtonFg','#ffffff'),(36,'deleteButtonBg','#8B0000'),(37,'saveButtonFg','#ffffff'),(38,'saveButtonBg','#008000'),(39,'cancelButtonFg','#ffffff'),(40,'cancelButtonBg','#ff0000'),(41,'viewButtonFg','#ffffff'),(42,'viewButtonBg','#d2691e'),(43,'yesButtonFg','#ffffff'),(44,'yesButtonBg','#008000'),(45,'noButtonFg','#ffffff'),(46,'noButtonBg','#ff0000'),(199,'loginMessage','Please login to phpam'),(200,'statusBarMessage','Powered By phpam version 1.0'),(201,'showStatusBarMessage','Yes'),(202,'loginFormIconsFG','#ffffff'),(203,'loginFormIconsBG','#1e90ff'),(204,'loginFormFields','#1e90ff'),(205,'loginFormButtonFG','#ffffff'),(206,'loginFormButtonBG','#1e90ff'),(207,'loginMessageText','#30589a'),(208,'loginStatusBarFG','#ffffff'),(209,'loginStatusBarBG','#1e90ff'),(210,'globalDirection','rtl'),(211,'globalAlign','right')";
		// create database and tables
		$DB_HOST=$host;
		$DB_USER=$user;
		$DB_PASSWORD=$password;
		$DB_NAME=$dbase;
		$dbc=@mysqli_connect($DB_HOST,$DB_USER,$DB_PASSWORD) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
		if (!mysqli_set_charset($dbc, "utf8")){
			echo "<span class='err'>Error loading character set utf8: ".mysqli_error($dbc)."</span> <br>";
			$error=true;
		}
		$r=mysqli_query($dbc,$q[0]);
		echo "<span class='qry'>".$q[0]."</span><br>";
		if($r){
			echo "<span class='ok'>Query Ok!...</span><br>";
			mysqli_free_result($r);
		}else{
			echo "<span class='err'>Error executing quey<br>".mysqli_error($dbc)."</span><br>";
			$error=true;
		}
		mysqli_close($dbc);
		$dbc=@mysqli_connect($DB_HOST,$DB_USER,$DB_PASSWORD,$DB_NAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
		if (!mysqli_set_charset($dbc, "utf8")){
			echo "<span class='err'>Error loading character set utf8: ".mysqli_error($dbc)." </span><br>";
			$error=true;
		}
		for($i=1;$i<=27;$i++){
			$r=mysqli_query($dbc,$q[$i]);
			echo "<span class='qry'>".$q[$i]."</span><br>";
			if($r){
				echo "<span class='ok'>Query Ok!...</span><br>";
				mysqli_free_result($r);
			}else{
				echo "<span class='err'>Error executing quey<br>".mysqli_error($dbc)."</span><br>";
				$error=true;
			}
		}
		mysqli_close($dbc);
		if(!$error){
			echo "<button class='btn' type='button' onclick='window.parent.location=\"../index.php\"'>Continue</button>";
		}
	}
}
if(!isset($submitted)){
	echo "<div style='width: 400px; margin: auto;'>";
	echo "<form method='post'>";
	echo "<div style='width: 100%;text-align: left;'>Host Name:</div>";
	echo "<input type='text' name='host' style='width: 100%;'>";
	echo "<br><br><div style='width: 100%;text-align: left;'>Database Name:</div>";
	echo "<input type='text' name='dbase' style='width: 100%;'>";
	echo "<br><br><div style='width: 100%;text-align: left;'>User Name:</div>";
	echo "<input type='text' name='user' style='width: 100%;'>";
	echo "<br><br><div style='width: 100%;text-align: left;'>Password:</div>";
	echo "<input type='text' name='password' style='width: 100%;'>";
	echo "<br><br><button type='submit' class='btn'>Create</button>";
	echo "<input type='hidden' name='submitted' value='-1'>";
	echo "</form>";
	echo "<div class='error'><p>$errorMessage</p></div>";
}
echo "</div></body></html>";
?>