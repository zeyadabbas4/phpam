<?php
/**
 * read staff names into an array
 * 
 * read staff names into an array indexed with their id's
 * 
 * @param   object  $dbc    database connection
 * 
 * @return  array   $staff  array of staff names 
 */
$staff=array();
$q="select staffid,staffname from staff";
$r=mysqli_query($dbc,$q);
if($r){
    while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $staff[$row['staffid']]=$row['staffname'];
    }
}

?>