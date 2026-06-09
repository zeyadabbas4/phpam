<?php
if(!isset($afterSaveMode)){
  $afterSaveMode="registeration";
}
if(!isset($returnMode)){
  $returnMode="";
}
//*****************************************************************************************
//save registeration details
//*****************************************************************************************

    if($mode=="saveRegDetails" or $mode=="saveEditDetails"){
      //validation
      if($mode=="saveRegDetails"){
        $errMode="regDetails";
      }else{
        $errMode="editTrnInfo";
      }
      $saveRegDetailserrorMessage="";
      if($TrnName == ""){
        $saveRegDetailserrorMessage .= "لابد من ادخال اسم المتدرب<br>";
        $mode=$errMode;
      }
      if($trnEname == ""){
        $saveRegDetailserrorMessage .= "لابد من ادخال اسم المتدرب باللغة الانجليزية<br>";
        $mode=$errMode;
      }
      if($TrnAddress == ""){
        $saveRegDetailserrorMessage .= "لابد من ادخال عنوان المتدرب<br>";
        $mode=$errMode;
      }
      if($TrnTels == ""){
        $saveRegDetailserrorMessage .= "لابد من ادخال تليفون المتدرب<br>";
        $mode=$errMode;
      }
      if($TrnCo == -1){
        $saveRegDetailserrorMessage .= "لابد من ادخال جهة عمل المتدرب<br>";
        $mode=$errMode;
      }
      if($trnBDate == ""){
        $saveRegDetailserrorMessage .= "لابد من ادخال تاريخ ميلاد المتدرب<br>";
        $mode=$errMode;
      }else{
        $useAgeRestriction=getSystemValue("useAgeRestriction")  ;
        if($useAgeRestriction=='yes'){
          $today = date("Y-m-d");
          $diff = date_diff(date_create($trnBDate), date_create($today));
          $age=$diff->format('%y');
          if($age < 18){
            $saveRegDetailserrorMessage .= "سن المتدرب لا يجب أن يقل عن 18 عاما<br>";
            $mode=$errMode;
          }
        }
      }
      if($trnBGovernrate == -1 and $trnBState == -1){
        $saveRegDetailserrorMessage .= "لابد من ادخال جهة ميلاد المتدرب<br>";
        $mode=$errMode;
      }
      if($trnNationality == -1){
        $saveRegDetailserrorMessage .= "لابد من ادخال جنسية المتدرب<br>";
        $mode=$errMode;
      }
      if($trnNationality == 63 and strlen($TrnIdNo) < 14 ){
        $saveRegDetailserrorMessage .= "لابد من ادخال الرقم القومي للمتدربين المصريين<br>";
        $mode=$errMode;
      }
      if($trnNationality != 63 and $trnPassportNo =="" ){
        $saveRegDetailserrorMessage .= "لابد من ادخال رقم الحواز للمتدربين غير المصريين<br>";
        $mode=$errMode;
      }
      if($trnjobName == ""){
        $saveRegDetailserrorMessage .= "لابد من ادخال وظيفة المتدرب<br>";
        $mode=$errMode;
      }
      if($trncrtName == ""){
        $saveRegDetailserrorMessage .= "لابد من ادخال مؤهل المتدرب<br>";
        $mode=$errMode;
      }

      //check if trainee alreay registered
      if(isset($TrnNo) and $mode=="saveRegDetails"){
        $q="SELECT ifnull(COUNT(crstrnid),0) crsCount FROM coursetrainees WHERE crstrntrainee=$TrnNo and crstrncourse=$CoursId";
        $r=mysqli_query($dbc,$q);
        if($r){
          if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            $crsCount=$row['crsCount'];  
          }
        }
        if($crsCount != 0){
          $saveRegDetailserrorMessage .= "لقد تم تسجيل المتدرب في الدورة مسبقا<br>";
          echo "<div class='error'><p>لقد تم تسجيل المتدرب في الدورة مسبقا</p></div>";
          $mode=$afterSaveMode;
        }
      }

      if($saveRegDetailserrorMessage == ""){
        if(isset($TrnNo)){
          $q="update Trainees set TrnName='$TrnName',trnEname='$trnEname',TrnAddress='$TrnAddress',TrnTels='$TrnTels',TrnWhatsApp='$TrnWhatsApp',trnBDate='$trnBDate',trnNationality=$trnNationality";
          if($trnBGovernrate != -1){
            $q.=",trnBGovernrate=$trnBGovernrate";
          }
          if($trnBState != -1){
            $q.=",trnBState=$trnBState";
          }
          if($trnPassportNo != ""){
            $q.=",trnPassportNo='$trnPassportNo'";
          }
          $q .= " where TrnNo=$TrnNo";
        }else{
          $q="INSERT INTO Trainees (TrnName, trnEname, TrnAddress, TrnTels, TrnWhatsApp, TrnCo, TrnIdNo, trnBDate, trnNationality";
          if($trnBGovernrate != -1){
            $q.=", trnBGovernrate";
          }
          if($trnBState != -1){
            $q.=", trnBState";
          }
          if($trnPassportNo != ""){
            $q.=", trnPassportNo";
          }
          $q .= ") values('$TrnName', '$trnEname', '$TrnAddress', '$TrnTels', '$TrnWhatsApp', $TrnCo, '$TrnIdNo', '$trnBDate', $trnNationality";
          if($trnBGovernrate != -1){
            $q.=", $trnBGovernrate";
          }
          if($trnBState != -1){
            $q.=", $trnBState";
          }
          if($trnPassportNo != ""){
            $q.=", '$trnPassportNo'";
          }
          $q.= ")";
        }
        $r=mysqli_query($dbc,$q);
        if($r){
          if(!isset($TrnNo)){
            $TrnNo=mysqli_insert_id($dbc);
          }
          
          //read last job
          $q="select trnjobName as lastjobname from TraineeJobs where trnjobTrainee=$TrnNo order by trnjobDate desc";
          $r=mysqli_query($dbc,$q);
          if($r){
            if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
              $lastjob=$row['lastjobname'];  
            }
          }
          $savejob=true;
          if(isset($lastjob)){
            if($lastjob == $trnjobName){
              $savejob=false;
            }  
          }
          if($savejob){
            $jobDate=date("Y-m-d");
            $q="INSERT INTO TraineeJobs (trnjobTrainee, trnjobName, trnjobDate) VALUES ($TrnNo, '$trnjobName', '$jobDate')";
            $r=mysqli_query($dbc,$q);
          }

          //read last certificate
          $q="select trncrtName as lastCert from TraineeCerts where trncrtTrn=$TrnNo order by trncrtDate desc";
          $r=mysqli_query($dbc,$q);
          if($r){
            if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
              $lastCert=$row['lastCert'];
            }
          }
          $savecert=true;
          if(isset($lastCert)){
            if($lastCert == $trncrtName){
              $savecert=false;
            }  
          }
          if($savecert){
            $certDate=date("Y-m-d");
            $q="INSERT INTO TraineeCerts (trncrtTrn, trncrtDate, trncrtName) VALUES ($TrnNo, '$certDate', '$trncrtName')";
            $r=mysqli_query($dbc,$q);
          }

          if($TrnCopurses !=""){
            $courses = array();
            $courses = explode("\n",$TrnCopurses);
            $crsDate=date("Y-m-d");
            print_r($courses);
            foreach($courses as $key => $value){
              $q="INSERT INTO TraineeCourses (trncrsTrn, trncrsDate, trncrsName) VALUES ($TrnNo, '$crsDate', '$value')";
              echo "<br>$q";
              $r=mysqli_query($dbc,$q);
            }
          }

          //register trainee into course
          if($mode=="saveRegDetails"){
            $q="INSERT INTO coursetrainees ( crstrntrainee, crstrncourse, crstrnStatus,crstrncompany) VALUES ($TrnNo, $CoursId, '1',$TrnCo);";
            $r=mysqli_query($dbc,$q);
            if($r){
              echo "<center><span style='color: green;'>تم التسجيل</span></center>";
              if(isset($fileName)){
                if($fileName=="obligatoryCourses.php"){
                  $_SESSION['TrnNo']=$TrnNo;
                  $_SESSION['CoursId']=$CoursId;
                  //echo "<script>window.open('trnInfo.php');</script>";
                  //echo "<script>window.open('invoice.php');</script>";  
                }
              }
            }
          }
          //update course status to 2
          if($mode=="saveEditDetails"){
            $mode="showTList";
          }else{
            $mode=$afterSaveMode;
          }
        }else{
          echo "<div class='error'><p>خطأ في حفظ البيانات</p></div>";
        }

      }
    }

//*****************************************************************************************
//register details form
//*****************************************************************************************
if($mode=='regDetails' or $mode=='editTrnInfo'){
    $showList=false;

    //validation
    if($mode=='regDetails'){
      if(!isset($saveRegDetailserrorMessage)){
        if($TemptrnNationality == -1){
          $errorMessage .= "لابد من ادخال جنسية المتدرب<br>";
          $mode="register";
        }
        if($TemptrnNationality == 63 and strlen($TempTrnIdNo) < 14 ){
          $errorMessage .= "لابد من ادخال الرقم القومي للمتدربين المصريين<br>";
          $mode="register";
        }
        if($TemptrnNationality != 63 and $TempTrnIdNo =="" ){
          $errorMessage .= "لابد من ادخال رقم الهوية للمتدربين غير المصريين<br>";
          $mode="register";
        }  
      }  
    }

    if($errorMessage == ""){
      //read trainee info
      if($mode=='regDetails'){
        $trnNationality=$TemptrnNationality;
        $trnBState=$TemptrnNationality;
        $TrnIdNo=$TempTrnIdNo;
        $q="SELECT TrnNo,TrnName,trnEname,TrnAddress,TrnTels,TrnWhatsApp,TrnCo,TrnIdNo,trnBDate,trnBGovernrate,trnBState,trnNationality,trnPassportNo FROM Trainees WHERE TrnIdNo='$TrnIdNo'";
      }else{
        $q="SELECT TrnNo,TrnName,trnEname,TrnAddress,TrnTels,TrnWhatsApp,TrnCo,TrnIdNo,trnBDate,trnBGovernrate,trnBState,trnNationality,trnPassportNo FROM Trainees WHERE TrnNo='$TrnNo'";
      }
      $r=mysqli_query($dbc,$q);
      if($r){
        if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value){
            $$key=$value;
          }
        }
      }

      if(isset($TrnNo)){
        //read job
        $q="select trnjobName from TraineeJobs where trnjobTrainee=$TrnNo order by trnjobDate desc";
        $r=mysqli_query($dbc,$q);
        if($r){
          if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
              $$key=$value;
            }  
          }
        }

        //read certificates
        $q="select trncrtName from TraineeCerts where trncrtTrn=$TrnNo order by trncrtDate desc";
        $r=mysqli_query($dbc,$q);
        if($r){
          if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
              $$key=$value;
            }  
          }
        }

        //read external courses
        $crsList=array();
        $q="select trncrsName from TraineeCourses where trncrsTrn=$TrnNo order by trncrsDate desc";
        $r=mysqli_query($dbc,$q);
        if($r){
          while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
              $$key=$value;
            }
            $crsList[]=$trncrsName;
          }
        }

        //read internal courses
        $q="select CrsName from (CoursesGuide inner join Courses on  CrsId=CoursCrsId) inner join coursetrainees on CoursId=crstrncourse where crstrntrainee=$TrnNo";
        $r=mysqli_query($dbc,$q);
        if($r){
          while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
              $$key=$value;
            }
            $crsList[]="[$CrsName]";
          }
        }

      }else{
        if($TemptrnNationality == 63){
          $century=substr($TrnIdNo,0,1);
          $year=substr($TrnIdNo,1,2);
          $month=substr($TrnIdNo,3,2);
          $day=substr($TrnIdNo,5,2);
          $gov=substr($TrnIdNo,7,2);
          if($century=="2"){
            $year="19".$year;
          }elseif($century=="3"){
            $year="20".$year;
          }
          $trnBDate="$year-$month-$day";
          $q="SELECT GovId from Governrates WHERE GovNatId='$gov'";
          $r=mysqli_query($dbc,$q);
          if($r){
            if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
              foreach($row as $key => $value){
                $$key=$value;
              }
              $trnBGovernrate=$GovId;
            }
          }  
        }
      } 
      if(isset($TrnNo)){
        //$fieldsReadOnly=" readonly";
        $fieldsReadOnly="";
      }else{
        $fieldsReadOnly="";
      }
      echo "<form method='post' style='max-width:800px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>تسجيل بيانات مشارك</h2>";
      if(isset($saveRegDetailserrorMessage)){
        echo "<div class='error'><p>$saveRegDetailserrorMessage</p></div>";
      }
      echo "<table width='100%'>";
      echo "<tr id='idNo' ><td style='width: 225px;'>الاسم (عربي):</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' name='TrnName'$fieldsReadOnly";
      if(isset($TrnName)){
        echo " value='$TrnName'";
      }
      echo " placeholder='الاسم (عربي)'>";
      echo "</div></td></tr>";  
      echo "<tr id='idNo' ><td style='width: 225px;'>الاسم (انجليزي):</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' name='trnEname'$fieldsReadOnly";
      if(isset($trnEname)){
        echo " value='$trnEname'";
      }
      echo " placeholder='الاسم (انجليزي)'>";
      echo "</div></td></tr>";  
      echo "<tr id='idNo' ><td style='width: 225px;'>العنوان:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' name='TrnAddress'$fieldsReadOnly";
      if(isset($TrnAddress)){
        echo " value='$TrnAddress'";
      }
      echo " placeholder='العنوان'>";
      echo "</div></td></tr>";  
      echo "<tr id='idNo' ><td style='width: 225px;'>التليفون:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' name='TrnTels'$fieldsReadOnly";
      if(isset($TrnTels)){
        echo " value='$TrnTels'";
      }
      echo " placeholder='التليفون'>";
      echo "</div></td></tr>";  
      echo "<tr id='idNo' ><td style='width: 225px;'>تليفون واتساب:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' name='TrnWhatsApp'$fieldsReadOnly";
      if(isset($TrnWhatsApp)){
        echo " value='$TrnWhatsApp'";
      }
      echo " placeholder='تليفون واتساب'>";
      echo "</div></td></tr>";  
      echo "<tr id='idNo' ><td style='width: 225px;'>الجهة التابع لها:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select name='TrnCo' class='input-field'><option value='-1'>الجهة التابع لها</option>";
      $q="select cmpId,cmpName from companies order by cmpName";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value){
            $$key=$value;
          }
          echo "<option value='$cmpId'";
          if(isset($TrnCo)){
            if($cmpId == $TrnCo){
              echo " selected";
            }
          }
          echo ">$cmpName</option>";
        }
      }
      echo "</select>";
      echo "</div></td></tr>";  
      echo "<tr id='idNo' ><td style='width: 225px;'>تاريخ الميلاد:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='date' name='trnBDate'$fieldsReadOnly";
      if(isset($trnBDate)){
        echo " value='$trnBDate'";
      }
      echo " placeholder='تاريخ الميلاد'>";
      echo "</div></td></tr>";  
      echo "<tr id='idNo' ><td style='width: 225px;'>جهة الميلاد:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select name='trnBGovernrate' class='input-field'$fieldsReadOnly><option value='-1'>جهة الميلاد</option>";
      $q="select GovId,GovName from Governrates order by GovName";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value){
            $$key=$value;
          }
          echo "<option value='$GovId'";
          if(isset($trnBGovernrate)){
            if($GovId == $trnBGovernrate){
              echo " selected";
            }
          }
          echo ">$GovName</option>";
        }
      }
      echo "</select>";
      echo "<select name='trnBState' class='input-field'$fieldsReadOnly><option value='-1'>دولة الميلاد</option>";
      $q="select cntId,cntArabName from Countries order by cntNumCode,cntArabName";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value){
            $$key=$value;
          }
          echo "<option value='$cntId'";
          if(isset($trnBState)){
            if($trnBState == $cntId){
              echo " selected";
            }
          }
          echo ">$cntArabName</option>";
        }
      }
      echo "</select>";
      echo "</div></td></tr>";  
      echo "<tr id='idNo' ><td style='width: 225px;'>الجنسية:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<select name='trnNationality' class='input-field'$fieldsReadOnly><option value='-1'>الجنسية</option>";
      $q="select cntId,cntArabName from Countries order by cntNumCode,cntArabName";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value){
            $$key=$value;
          }
          echo "<option value='$cntId'";
          if(isset($trnNationality)){
            if($cntId == $trnNationality){
              echo " selected";
            }
          }
          echo ">$cntArabName</option>";
        }
      }
      echo "</select>";
      echo "</div></td></tr>";  
      echo "<tr id='idNo' ><td style='width: 225px;'>الرقم القومي/رقم الهوية:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' name='TrnIdNo'$fieldsReadOnly";
      if(isset($TrnIdNo)){
        echo " value='$TrnIdNo'";
      }
      echo " placeholder='الرقم القومي/رقم الهوية'>";
      echo "</div></td></tr>";  
      echo "<tr id='idNo' ><td style='width: 225px;'>رقم جواز السفر البحري:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' name='trnPassportNo'$fieldsReadOnly";
      if(isset($trnPassportNo)){
        echo " value='$trnPassportNo'";
      }
      echo " placeholder='رقم جواز السفر البحري'>";
      echo "</div></td></tr>"; 
      echo "<tr id='idNo' ><td style='width: 225px;'>الوظيفة الحالية:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' name='trnjobName'$fieldsReadOnly";
      if(isset($trnjobName)){
        echo " value='$trnjobName'";
      }
      echo " placeholder='الوظيفة الحالية'>";
      echo "</div></td></tr>";  
      echo "<tr id='idNo' ><td style='width: 225px;'>المؤهلات العلمية:</td><td colspan='2'>";
      echo "<div class='input-container'>";
      echo "<input class='input-field' type='text' name='trncrtName'$fieldsReadOnly";
      if(isset($trncrtName)){
        echo " value='$trncrtName'";
      }
      echo " placeholder='المؤهلات العلمية'>";
      echo "</div></td></tr>";  
      echo "<tr id='idNo'><td style='width: 225px;' valign='top'>الدورات الحاصل عليها:</td><td colspan='2'>";
      if(isset($TrnNo)){
        if(count($crsList) > 0){
          foreach($crsList as $value){
            echo "$value<br>";
          }
        }  
      }
      echo "<div class='input-container'>";
      echo "<textarea class='input-field' rows='5' name='TrnCopurses'$fieldsReadOnly>";
      echo "</textarea>";
      echo "</div></td></tr>";  

      echo "</table>";
      echo "<input type='hidden' name='CoursId' value='$CoursId'>";
      if($mode=='regDetails'){
        echo "<input type='hidden' name='TemptrnNationality' value='$TemptrnNationality'>";
        echo "<input type='hidden' name='TempTrnIdNo' value='$TempTrnIdNo'>";
      }
      if(isset($TrnNo)){
        echo "<input type='hidden' name='TrnNo' value='$TrnNo'>";
      }
      if($mode=='regDetails'){
        $cancelMode="Registration";
        $saveMode="saveRegDetails";
      }else{
        $cancelMode="showTList";
        $saveMode="saveEditDetails";
      }
      echo "<input type='hidden' name='afterSaveMode' value='$afterSaveMode'>";
      echo "<input type='hidden' name='returnMode' value='$returnMode'>";
      echo "<input type='hidden' name='cancelLabel' value='تراجع'>";  
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$saveMode'>تسجيل</button> <button type='submit' class='cnlBtn' name='mode' value='$cancelMode'>$cancelLabel</button></div>";
      echo "</form>";
    }
  }

//*****************************************************************************************
//registeration form
//*****************************************************************************************
  if($mode=='registeration'){
    $showList=false;
    echo "<form method='post' style='max-width:800px;margin:auto;direction:$__dir;'>";
    echo "<h2 style='text-align: center;'>تسجيل مشارك</h2>";
    echo "<div class='error'><p>$errorMessage</p></div>";
    echo "<table width='100%'>";

    $countries=array();
    $q="SELECT cntId,cntArabName FROM Countries ORDER BY cntNumCode,cntArabName";
    $r=mysqli_query($dbc,$q);
    if($r){
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value){
          $$key=$value;
        }
        $countries[$cntId]=$cntArabName;
      }
    }
    if(!isset($TemptrnNationality)){
      $TemptrnNationality=63;
    }
    echo "<tr><td style='width: 225px;'>الجنسية:</td><td colspan='2'>";
    echo "<div class='input-container'>";
    echo "<select class='input-field' name='TemptrnNationality' onchange='setNextField(this.value);'><option value='-1'>حدد الجنسية</option>";
    foreach($countries as $key => $value){
      echo "<option value='$key'";
      if(isset($TemptrnNationality)){
        if($TemptrnNationality == $key){
          echo " selected";
        }
      }
      echo ">$value</option>";
    }
    echo "</slect>";
    echo "</div></td></tr>";  

    echo "<tr><td style='width: 225px;'>الرقم القومي/رقم الهوية:</td><td colspan='2'>";
    echo "<div class='input-container'>";
    echo "<input class='input-field' type='text' name='TempTrnIdNo' placeholder='الرقم القومي/رقم الهوية'";
    if(isset($TempTrnIdNo)){
      echo " value='$TempTrnIdNo'";
    }
    echo ">";
    echo "</div></td></tr>";    
    echo "</table>";
    echo "<input type='hidden' name='CoursId' value='$CoursId'>";
    echo "<input type='hidden' name='afterSaveMode' value='$afterSaveMode'>";
    echo "<input type='hidden' name='returnMode' value='$returnMode'>";
    echo "<input type='hidden' name='cancelLabel' value='تراجع'>";
    echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='regDetails'>تسجيل</button> <button type='submit' class='cnlBtn' name='mode' value='$returnMode'>العودة</button></div>";
    echo "</form>";
  }


    ?>