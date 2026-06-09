<?php
/**
 * read Currnecy names into an array
 *
 * read Currency names into an array indexed with their id's
 *
 *
 * @param    object  $dbc        database connection
 *
 * @return   array   $currency   array of currenies names
 *
 * */
$currency = array();
$q="select CurId,CurName from Currencies";
$r=mysqli_query($dbc,$q);
if($r)
    {
        while($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
            {
                $currency[$row['CurId']]=$row['CurName'];
            }
    }

?>
