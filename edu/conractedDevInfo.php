<?php
$boolarray = array(false => 'false', true => 'true');
$periods = comparePeriodStatus($dbc, $CoursId, ">=", $formCourseStatusLevel);
$approvedPrds = comparePeriodStatus($dbc, $CoursId, ">", $formCourseStatusLevel);
$allPeriodsApproved = false;
$dividable = courseIsDevidable($dbc, $CoursId);
if (count($periods) == count($approvedPrds) and $dividable) {
  $allPeriodsApproved = true;
}
if (!$dividable) {
  $allPeriodsApproved = true;
}
$devInfo = "<br>[Id = $CoursId - crsSts = $CoursStatus/formId=$formCourseStatusLevel - allPrdAprv=$boolarray[$allPeriodsApproved]- dvd=$boolarray[$dividable]]";
