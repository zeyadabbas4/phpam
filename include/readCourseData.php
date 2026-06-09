<?php
      //read course data
      $q="select CoursCrsId,CoursType,CoursBulletin,CoursFromPln,CouursToPln,CoursFromAct,CoursToAct,CoursSupervisorInt,CoursSupervisorExt,CoursYear,CoursArea,CoursSection,CoursGenRept,CoursAreaRept,CoursLocation,CoursStatus from Courses where CoursId=?";
      if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $CoursCrsId,$CoursType,$CoursBulletin,$CoursFromPln,$CouursToPln,$CoursFromAct,$CoursToAct,$CoursSupervisorInt,$CoursSupervisorExt,$CoursYear,$CoursArea,$CoursSection,$CoursGenRept,$CoursAreaRept,$CoursLocation,$CoursStatus)){
              if(!mysqli_stmt_fetch($stmt)){
                $errorMessage.= "<span dir='ltr'>Error reading data [030105".$__uid.date("YmdHis")."]!...</span><br>";
                appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030105".$__uid.date("YmdHis"));        
              }
            }else{
              $errorMessage.= "Error reading data [030104".$__uid.date("YmdHis")."]!...<br>";
              appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030104".$__uid.date("YmdHis"));      
            }
          }else{
            $errorMessage.= "Error saving reading [030103".$__uid.date("YmdHis")."]!...<br>";
            appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030103".$__uid.date("YmdHis"));    
          }
        }else{
          $errorMessage.= "Error saving reading [030102".$__uid.date("YmdHis")."]!...<br>";
          appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030102".$__uid.date("YmdHis"));
        }
        mysqli_stmt_close($stmt);
      }else{
        $errorMessage.= "Error saving reading [030101".$__uid.date("YmdHis")."]!...<br>";
        appendLog($logDir,$logFile,$q." - ".mysqli_error($dbc),"030101".$__uid.date("YmdHis"));
      }
?>
