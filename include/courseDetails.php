<?php
/**
 * display course details
 * 
 * Course details table requires the inclusion of readCourseData.php
 * 
 * @param   array   $prgs   course guide array 
 * @param   int     $CoursCrsId     program Id
 * @param   int     $CoursBulletin  Bulletin Number
 * @param   array   $dists  Areas array
 * @param   int     $CoutsArea  district Id
 * @param   date    $CoursFromAct   Course start date
 * @param   date    $CoursToAct     Course End date
 * @param   array   $comps   companies array
 * @param   int     $CoursLocation  location of the course
 * @param   array   $staff     staff array
 * @param   int     $CoursSupervisorInt     internal supervisor
 * 
 * @return  void
 */
echo "<table width='80%'>";
echo "<tr><td>اسم الدورة: </td><td>". $prgs[$CoursCrsId]["Name"]."</td><td>رقم المنشور: </td><td>$CoursBulletin</td><td>المنطقة: </td><td>".$dists[$CoursArea]."</td></tr>";
echo "</table>";
echo "<table width='100%'>";
echo "<tr><td>خلال الفترة (فعلي) من: $CoursFromAct الى: $CoursToAct <br>".str_repeat("&nbsp;",12)."(مخطط)من: $CoursFromPln الى: $CouursToPln</td><td>مكان اﻹنعقاد: ".$comps[$CoursLocation]." </td><td>اﻹشراف: ".$staff[$CoursSupervisorInt]."</td></tr>";  // الاشراف الخارجي." / ".$comps[$CoursSupervisorExt]
echo "</table>";
?>