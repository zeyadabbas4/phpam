<?php
if(!isset($acceptAmount)){
  $acceptAmount=1;
}
if(!isset($rejectAmount)){
  $rejectAmount=1;
}
 if ($mode == "agreeSave") {
      $dividable = courseIsDevidable($dbc, $CoursId);
      if ($dividable) {
        incPerStat($dbc, $clhPeriod,$acceptAmount);
        if($stage == "dean"){
          setPeriodDeanAccredation($dbc,$__uid,$clhPeriod);
        }
        if($stage == "benefits"){
          setPeriodHRAccredation($dbc,$__uid,$clhPeriod);
        }

        $allClosed = allPeriodsEnded($dbc, $CoursId, $formCourseStatusLevel);
        if ($allClosed) {
          //incCrsStat($dbc, $CoursId);
        }
      }
      else{
        incCrsStat($dbc, $CoursId,$acceptAmount);
        if($stage == "dean"){
          setDeanAccredation($dbc,$__uid,$CoursId);
        }      
        if($stage == "benefits"){
          setHRAccredation($dbc,$__uid,$CoursId);
        }      
      }
    }

    if ($mode == "rejectSave") {
      $allClosed = allPeriodsEnded($dbc, $CoursId, $formCourseStatusLevel);
      $dividable = courseIsDevidable($dbc, $CoursId);
      if ($dividable) {
        decPerStat($dbc, $clhPeriod,$rejectAmount);

        if (getCrsStat($dbc, $CoursId) == $formCourseStatusLevel) {
          decCrsStat($dbc, $CoursId,$rejectAmount);
        }
      } else {
        decCrsStat($dbc, $CoursId);
      }
    }


    if ($mode == "editSave") {
      $newFlag = 0; //back to heba
      $mode = "Save";
    }


?>
