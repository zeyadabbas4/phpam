<?php
/**
 * read Lecturer names into an array
 * 
 * read Lecturer names into an array 
 * 
 * @param   object  $dbc    database connection
 * 
 * @return  array   $lecNames  array of lecturer names 
 */
$lecNames=array();
$q="SELECT staffid,staffname FROM staff WHERE staffislec=1 AND staffdeleted=0 ORDER BY staffname";
$r=mysqli_query($dbc,$q);
if($r){
    while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value){
            $$key=$value;
        }
        $lecNames[$staffid]=$staffname;
    }
}
?>