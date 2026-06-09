<?php
/**
 * read districts
 * 
 * read districts into array
 * 
 * @param   object  $dbc    dtabase connection
 * 
 * @return  array   $dists  districts array
 */
$dists=array();
$q="select dist_id,dist_name from districts";
$r=mysqli_query($dbc,$q);
if($r){
    while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $dists[$row['dist_id']]=$row['dist_name'];
    }
}
?>