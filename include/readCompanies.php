<?php
/**
 * read comopanies
 * 
 * read companies table into an array for later use
 * 
 * @param   object  $dbc    dtabase connection
 * 
 * @return  array   $comps  companies array
 */
$comps=array();
$q="select cmpId,cmpName from companies order by cmpName";
$r=mysqli_query($dbc,$q);
if($r){
    while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $comps[$row['cmpId']]=$row['cmpName'];
    }
}
?>