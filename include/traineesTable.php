<?php
/**
 * displays trainees table in a course in edu
 * 
 * displays a table of the students of a course with the details of the scores
 * a variable CoursId should be present for this file to work properly
 * 
 * @param int   $CourseId   id of the course to display the trainees for
 * 
 * @return  void
 */
//read trainees
$trainees=array();
$q="SELECT TrnName,TrnNo,crstrnresultAttend,crstrnresultexam,cmpName  FROM coursetrainees INNER JOIN Trainees ON TrnNo=crstrntrainee INNER JOIN companies on cmpId=crstrncompany WHERE crstrncourse=$CoursId";
$r=mysqli_query($dbc,$q);
if($r){
    $i=0;
    while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
    $trainees[$i]['name']=$row['TrnName'];
    $trainees[$i]['attendance']=$row['crstrnresultAttend'];
    $trainees[$i]['score']=$row['crstrnresultexam'];
    $trainees[$i]['company']=$row['cmpName'];
    $i++;
    }
}
//بيانات المتدربين
    echo "<div style='text-align: right;font-weight: bold;text-decoration:underline;'>بيانات المتدربين:</div>";
    echo "<table width='80%' class='lecTable'>";
    echo "<tr><th> م </th><th> اسم المتدريب </th><th>الجهة</th><th> درجة اﻹختبار </th><th> درجة المواظبة </th><th> اجمالي الدرجة </th></tr>";
    foreach($trainees as $key => $value){
      $ser=$key+1;
      $Name=$value['name'];
      $attend=$value['attendance'];
      $score=$value['score'];
      $company=$value['company'];
      $totScore=$attend+$score;
      echo "<tr><td> $ser </td><td> $Name </td><td> $company </td><td> $score </td><td> $attend </td><td> $totScore </td></tr>";
    }
    echo "</table>";
?>