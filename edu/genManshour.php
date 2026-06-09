<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$__systemRoot="../";                        //path to system root
include($__systemRoot."functions.php");     //system Functions don't remove
include('functions.php');                   //uncomment this line if you have a local functions file
include("appdb.php");                       //application database credentials
foreach($_POST as $key => $value){
    $$key=$value;
}
$__dir="rtl";
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
$i=1;
$courses=array();
$q="SELECT CoursId FROM Courses WHERE CoursYear=9 and CoursType=1 order by CoursFromPln";
if ($stmt = mysqli_prepare($dbc, $q)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $CoursId);
    while(mysqli_stmt_fetch($stmt)){
        $courses[$i]=$CoursId;
        $i++;
    }
    mysqli_stmt_close($stmt);
} 

echo "Read courses finished<br>";

$q="update Courses set CoursBulletin=? where CoursId=?";
if ($stmt = mysqli_prepare($dbc, $q)) {
    foreach($courses as $key => $value){
        echo "Setting course No $value set to Manshour No $key ...<br>";
        mysqli_stmt_bind_param($stmt, "ii", $key, $value);
        if(mysqli_stmt_execute($stmt)){
            echo "Course No $value set to Manshour No $key<br>";
        }
    } 
    mysqli_stmt_close($stmt);
}else{
    echo mysqli_error($dbc);
}

?>