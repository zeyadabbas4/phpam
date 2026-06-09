<?php
      $q="SELECT cmpName FROM companies INNER JOIN Courses ON cmpId=CoursSupervisorExt WHERE CoursId=?";
      if($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i",$CoursId)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt,$cmpName)){
              mysqli_stmt_fetch($stmt);
            }
          }
        }
        mysqli_stmt_close($stmt);
      }

      echo "<div style='text-align: right;font-weight: bold;text-decoration:underline;'>بيانات الاشراف:</div>";
      echo "<div style='text-align: right;font-weight: bold;width: 80%;margin: auto;'>جهة الاشراف: $cmpName</div>";
      echo "<table width='80%' class='lecTable'>";
      echo "<tr><th> اسم المشرف </th><th width='175px'> مبلغ الاشراف </th></tr>";

      $q="SELECT supSalutation,supName,supAmount FROM Supervisors WHERE supCompany=?";
      if($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i",$CoursSupervisorExt)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt,$supSalutation,$supName,$supAmount)){
              while(mysqli_stmt_fetch($stmt)){
                echo "<tr><td> $supSalutation / $supName </td><td> $supAmount </td></tr>";
              }
            }
          }
        }
        mysqli_stmt_close($stmt);
      }
      echo "</table>";

?>