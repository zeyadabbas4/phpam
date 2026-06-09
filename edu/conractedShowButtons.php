<?php
if(!isset($showPrintButtons)){
  $showPrintButtons=true;
}
if(!isset($showPlainView)){
  $showPlainView=false;
}
$currentEduYear=getSystemValue('curEduYear');
$previousEduYear=getSystemValue('PreEduYear');
$q = "SELECT distinct CoursId,CoursBulletin,CrsName,CoursStatus,CoursYear FROM Courses inner join CoursesGuide on CoursCrsId =CrsId left JOIN  coursPeriods on CoursId=crprCourseId WHERE CoursType=2 AND (CoursYear=" . $user_year['Id'] . " OR CoursYear=$previousEduYear) AND (CoursStatus >=$formCourseStatusLevel OR crprStatus >=$formCourseStatusLevel) order by CoursId";
      if (isset($perms['Developer'])) {
        echo $q;
      }
      $r = mysqli_query($dbc, $q);
      if ($r) {
        //filter list form
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        if($showPlainView){
          echo "<table style='width:100%;' id='preFilter'>";
          echo "<tr><td style='text-align: center;'><form method='post'>";
          echo "<button type='submit' class='pwdBtn' name='fmode' value='filterReview' >مراجعة</button> ";
          echo "<button type='submit' class='pwdBtn' name='fmode' value='filterView' >عرض</button> ";
          echo "<button type='submit' class='pwdBtn' name='fmode' value='filterAll' >الكل</button> ";
          echo "</form></td></tr></table>";  
        }
        echo "<table id='mainTable'>";
        echo "<tr class='header'>";
        echo "<th>المنشور - اسم الدورة</th><th style='width:900px;text-align: center;'></th></tr>";
        if(!isset($fmode)){
            $fmode='filterAll';
        }
        $rowNo = 0;
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
          foreach ($row as $key => $value) {
            $$key = $value;
          }

          $periods = comparePeriodStatus($dbc, $CoursId, ">=", $formCourseStatusLevel);
          $approvedPrds = comparePeriodStatus($dbc, $CoursId, ">", $formCourseStatusLevel);
          $allPeriodsApproved = false;
          $dividable = courseIsDevidable($dbc, $CoursId);
          // record status
          $buttonCaption = "عرض";
          $recordStatus = "view";
          $buttonClass = "edtBtn";

          if (count($periods) == count($approvedPrds) and $dividable) {
            $allPeriodsApproved = true;
          }

          if (!$dividable) {
            $allPeriodsApproved = true;
          }

          if ($CoursStatus > $formCourseStatusLevel and $allPeriodsApproved) {
            $buttonCaption = "عرض";
            $recordStatus = "view";
            $buttonClass = "edtBtn";
          } elseif(!$allPeriodsApproved or (!$dividable and $CoursStatus == $formCourseStatusLevel)) {
            $buttonCaption = "مراجعة";
            $recordStatus = "review";
            $buttonClass = "viewBtn";
          }
          $dividable = courseIsDevidable($dbc, $CoursId);
          $devInfo = "";
          if (isset($perms['Developer'])) {
            include('conractedDevInfo.php');
          }
          $rowNo++;

          $buttonStatus = "";
          $listDisabled = "";
          if (count($periods) == 0) {
            $listDisabled = " disabled ";
          }

          $taggedPrd = tagPrd($periods, $formCourseStatusLevel);
          $allCrsPrds = readPeriods($dbc, $CoursId);
          if (count($taggedPrd) != count($allCrsPrds)) {
            $buttonStatus = " disabled style='background-color: lightgrey;'";
          }
          $sortedPrd = srtPrd($taggedPrd, "Status");

          if ($recordStatus == "review" and $fmode == "filterView") {
            $fmode = "filterView";
            continue;
          }
          if ($recordStatus == "view" and $fmode == "filterReview") {
            $fmode = "filterReview";
            continue;
          }

          $message="";
          if($CoursYear == $previousEduYear){
            $message="<span style='color:red;'>[العام السابق]</span>";
          }

          echo "<tr><td>$CoursBulletin - $CrsName $message <br> $devInfo</td>";
          echo "<td style='text-align: left;'><form method='post'>";
          echo "<input type='hidden' name='CoursId' value='$CoursId'>";
          echo "<input type='hidden' name='CoursYear' value='$CoursYear'>";
          echo "<input type='hidden' name='fmode' value='$fmode'>";
          echo "<button type='submit' class='pwdBtn' name='mode' value='conReport' $buttonStatus >بيانات الدورة</button> ";
          echo "<button type='submit' class='$buttonClass' name='mode' value='view'>$buttonCaption</button> ";
          echo "<select name='clhPeriod' style='width: 260px; height: 37px;'$listDisabled><option value='0'>حدد الفترة</option>";

          foreach ($sortedPrd as $key => $value) {
            echo "<option value='$key'>" . $value['Description'] . " - " . $value['tag'] . " (" . $value['From'] . " - " . $value['To'] . ")</option>";
          }
          echo "</select>";
          echo "</form></td></tr>";
        }
        echo "</table>";
        mysqli_free_result($r);
}
