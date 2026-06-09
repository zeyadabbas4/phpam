<?php
/**
 * read lecturers of a course in edu
 * 
 * this file reads the lecturers data of a course and creates an array with this data
 * a variable $CoursId containing the id of the course to display its lecturers should be present for this file to work properly
 * 
 * @param   int $CoursId   id of the course to get the lecturers data for
 * @param   object  $dbc    database connection
 * 
 * @return  array   $lecs
 */
$lecs=array();
$q="select clhLecId,clhHoursP, clhHoursT, clhNights, clhratio, clhDistrictFrom, clhDistrictTo, clhDays, clhReturn from CourseLecHours where clhCrsId=$CoursId";
$r=mysqli_query($dbc,$q);
if($r){
    $i=0;
    while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $lecs[$i]['lecId']=$row['clhLecId'];
        $lecs[$i]['thhrs']=$row['clhHoursT'];
        $lecs[$i]['prhrs']=$row['clhHoursP'];
        $lecs[$i]['Nights']=$row['clhNights'];
        $lecs[$i]['ratio']=$row['clhratio'];
        $lecs[$i]['From']=$row['clhDistrictFrom'];
        $lecs[$i]['To']=$row['clhDistrictTo'];
        $lecs[$i]['Days']=$row['clhDays'];
        $lecs[$i]['Return']=$row['clhReturn'];
        $i++;
    }
}

?>
