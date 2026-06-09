<?php
/**
 * Read Porgram Guide (الدليل)
 * 
 * @param   object   $dbc    database connction
 * 
 * @return  array
 */
$prgs=array();
$q="select CrsId,CrsProgram,CrsCode,CrsName from CoursesGuide";
$r=mysqli_query($dbc,$q);
if($r){
    while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
    $prgs[$row['CrsId']]['Name']=$row['CrsName'];
    $prgs[$row['CrsId']]['Code']=$row['CrsCode'];
    $prgs[$row['CrsId']]['Program']=$row['CrsProgram'];
    }
}
?>