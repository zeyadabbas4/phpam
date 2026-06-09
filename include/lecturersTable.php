<?php
/**
 * lecturer's table in a course in edu
 * 
 * Prints lecturer's table in a course including names,houres and totals
 * include both readCourseLecs.php and readStaff.php prior to including this file
 * 
 * @param   array   $lecs   the lecturer data array from readCourseLecs.php
 * @param   array   $staff  staff names array from readStaff.php
 * @return  void
 */
//بيانات المحاضرين
echo "<div style='text-align: right;font-weight: bold;text-decoration:underline;'>بيانات المحاضرين:</div>";
echo "<table width='80%' class='lecTable'>";
echo "<tr><th> م </th><th> اسم المحاضر </th><th> ساعات نظري </th><th> ساعات عملي </th><th> اجمالي المحاضر </th></tr>";
$totThours=0;
$totPhours=0;
$totCrsHours=0;
foreach($lecs as $key => $value){
$ser=$key+1;
$lecName=$staff[$value['lecId']];
$tHours=$value['thhrs'];
$pHours=$value['prhrs'];
$totLecHours=$tHours+$pHours;
$totThours+=$tHours;
$totPhours+=$pHours;
$totCrsHours=$totThours+$totPhours;
echo "<tr><td> $ser </td><td> $lecName </td><td> $tHours </td><td> $pHours </td><td> $totLecHours </td></tr>";
}
echo "<tr><td colspan='2'>اﻹجمالي</td><td>$totThours</td><td>$totPhours</td><td>$totCrsHours</td></tr>";
echo "</table>";

?>