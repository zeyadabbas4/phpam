<?php
/**
 * displays travel and transportaion table for a course in edut
 * 
 * displays a table with details of travel and transportation for lecturers along with the fees and number of days
 * in order for this file to work properly include the file readCourseLecs.php and readStaff.php prior to including this file 
 * 
 * @param   array   $lecs   array of lecture data details in a course
 * @param   array   $staff  array of staff names
 * 
 * @return void
 */
//بيانات بدل السفر والانتقال
echo "<div style='text-align: right;font-weight: bold;text-decoration:underline;'>بيانات بدل السفر والانتقال:</div>";    
echo "<table width='80%' class='lecTable'>";
echo "<tr><th>م</th><th>المحاضر</th><th>عدد الليالي</th><th>الفئة</th><th>النسبة</th><th>بدل السفر</th><th>جهة الانتقال</th><th>الفئة</th><th>بدل الانتقال</th></tr>";
foreach($lecs as $key => $value){
$ser=$key+1;
$lectId=$value['lecId'];
$lecName=$staff[$lectId];
$Nights=$value['Nights'];
if($Nights != 0){
    $ratio=$value['ratio'];
    $fees=get_TravelData($dbc,$lectId);
    $travelFees=$fees * $Nights * $ratio / 100;
    $to=$value['To'];
    $from=$value['From'];
    $distination=$dists[$to];
    $toFees=getTransportFees($dbc,$from,$to);
    $returnFees=getTransportFees($dbc,$to,$from);
    $transportCount=$value['Days'];
    $return=$value['Return'];
    $transportFees = $toFees * $transportCount + $returnFees * $transportCount * $return;  
}else{
    $ratio=0;
    $fees=0;
    $travelFees=0;
    $distination="";
    $toFees=0;
    $transportFees = 0;  
}
echo "<tr><td> $ser </td><td> $lecName </td><td> $Nights </td><td> $fees </td><td> $ratio % </td><td> $travelFees </td><td> $distination </td><td> $toFees </td><td> $transportFees </td></tr>";
}
echo "</table>";
?>