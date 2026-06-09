<?php
/**
 * read scetions
 * 
 * read sections from hr database
 * 
 * @param   object  $dbc    dtabase connection
 * 
 * @return  array   $secs   Sections array
 */
$secs=array();
$q="select PrgId,PrgName from Programs";
$r=mysqli_query($dbc,$q);
if($r){
    while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $secs[$row['PrgId']]=$row['PrgName'];
    }
}
?>