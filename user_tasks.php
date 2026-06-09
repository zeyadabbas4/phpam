<?php
$showList=true;
if(!isset($mode)){
    $mode="";
}
if(!isset($errorMessage)){
    $errorMessage="";
}
if(!isset($showCompleted)){
    $showCompleted=0;
}
echo "<h2 style='text-align: center;'>سجل المهام</h2>";
if($activeTab=="tasks"){
    if($mode=="saveAdd"){
        //save new value
        if($tskTitle==""){
            $errorMessage.="لابد من إدخال اسم<br>";
            $mode="Add";
        }
        if($tskPercentComplete==""){
            $tskPercentComplete='0';
        }
        if($mode=="saveAdd"){
            $q="insert into tasks(tskTitle,tskDescription,tskStartDate,tskEndDate,tskPercentComplete,tskUser) values(?,?,?,?,?,?)";
            if ($stmt = mysqli_prepare($dbc, $q)) {
                mysqli_stmt_bind_param($stmt, "ssssii", $tskTitle,$tskDescription,$tskStartDate,$tskEndDate,$tskPercentComplete,$__uid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }
    if($mode=='add'){
        $showList=false;
        echo "<form method='post' style='max-width:500px;margin:auto'>";
        echo "<h3 style='text-align: center;'>مهمة جديدة</h3>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='اﻹسم . . .' name='tskTitle'";
        if(isset($tskTitle))
            echo " value='$tskTitle'";
        echo ">";
        echo "</div>";
        echo "<div class='input-container'>";
        echo "<textarea class='input-field' placeholder='التفاصيل . . .' name='tskDescription' rows='5' cols='40'>";
        if(isset($tskDescription))
            echo "$tskDescription";
        echo "</textarea></div>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='date' placeholder='تاريخ البداية. . .' name='tskStartDate'";
        if(isset($tskStartDate))
            echo " value='$tskStartDate'";
        echo ">";
        echo "</div>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='date' placeholder='تاريخ النهاية . . .' name='tskEndDate'";
        if(isset($tskEndDate))
            echo " value='$tskEndDate'";
        echo ">";
        echo "</div>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='number' placeholder='نسبة الانجاز . . .' name='tskPercentComplete' min='0' max='100'";
        if(isset($tskPercentComplete))
            echo " value='$tskPercentComplete'";
        echo ">";
        echo "</div>";
        echo "<input type='hidden' name='activeTab' value='tasks'>";
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='saveAdd'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value=''>تراجع</button></div>";
        echo "</form>";
        echo "<div class='error'><p>$errorMessage</p></div>";
    }
    if($mode=="saveEdit"){
        //save new value
        if($tskTitle==""){
            $errorMessage.="لابد من إدخال اسم<br>";
            $mode="edit";
        }
        if($tskPercentComplete==""){
            $tskPercentComplete='0';
        }
        if($mode=="saveEdit"){
        $q="update tasks set tskTitle=?,tskDescription=?,tskStartDate=?,tskEndDate=?,tskPercentComplete=? where tskId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "ssssii", $tskTitle,$tskDescription,$tskStartDate,$tskEndDate,$tskPercentComplete,$tskId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        }
    }
    if($mode=="edit"){
        $showList=false;
        $q="select tskTitle,tskDescription,tskStartDate,tskEndDate,tskPercentComplete from tasks where tskId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $tskId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $tskTitle,$tskDescription,$tskStartDate,$tskEndDate,$tskPercentComplete);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }
        echo "<form method='post' style='max-width:500px;margin:auto'>";
        echo "<h3 style='text-align: center;'>تعديل</h3>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='اﻹسم . . .' name='tskTitle' required";
        if(isset($tskTitle))
            echo " value='$tskTitle'";
        echo ">";
        echo "</div>";
        echo "<div class='input-container'>";
        echo "<textarea class='input-field' placeholder='التفاصيل . . .' name='tskDescription' rows='5' cols='40'>";
        if(isset($tskDescription))
            echo "$tskDescription";
        echo "</textarea></div>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='date' placeholder='تاريخ البداية. . .' name='tskStartDate' required";
        if(isset($tskStartDate))
            echo " value='$tskStartDate'";
        echo ">";
        echo "</div>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='date' placeholder='تاريخ النهاية . . .' name='tskEndDate' required";
        if(isset($tskEndDate))
            echo " value='$tskEndDate'";
        echo ">";
        echo "</div>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='number' placeholder='نسبة الانجاز . . .' name='tskPercentComplete' min='0' max='100'";
        if(isset($tskPercentComplete))
            echo " value='$tskPercentComplete'";
        echo ">";
        echo "</div>";
        echo "<input type='hidden' name='activeTab' value='tasks'>";
        echo "<input type='hidden' name='tskId' value='$tskId'>";
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='saveEdit'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value=''>تراجع</button></div>";
        echo "</form>";
        echo "<div class='error'><p>$errorMessage</p></div>";
    }
    if($mode=="view"){
        $showList=false;
        $q="select tskTitle,tskDescription,tskStartDate,tskEndDate,tskPercentComplete from tasks where tskId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $tskId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $tskTitle,$tskDescription,$tskStartDate,$tskEndDate,$tskPercentComplete);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }
        echo "<form method='post' style='max-width:500px;margin:auto'>";
        echo "<h3 style='text-align: center;'>عرض</h3>";
        echo "<div class='input-container'>اسم المهمة: $tskTitle </div>";
        echo "<div class='input-container'>تفاصيل المهمة:". nl2br($tskDescription) ."</div>";
        echo "<div class='input-container'>تاريخ البداية: $tskStartDate </div>";
        echo "<div class='input-container'>تاريخ النهاية: $tskEndDate </div>";
        echo "<div class='input-container'>نسبة الانجاز: $tskPercentComplete %</div>";
        echo "<div style='width:100%;Height:10px;background-color:grey;'><div style='width:".$tskPercentComplete."%;Height:10px;background-color:green;'></div></div><br>";
        echo "<input type='hidden' name='activeTab' value='tasks'>";
        echo "<input type='hidden' name='tskId' value='$tskId'>";
        echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode' value=''>موافق</button></div>";
        echo "</form>";
    }
    if($mode=="deleteConfirm"){
        $showList=false;
        $q="select tskTitle from tasks where tskId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $tskId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $tskTitle);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }
        echo "<form method='post' style='max-width:500px;margin:auto'>";
        echo "<h3 style='text-align: center;'>الغاء</h3>";
        echo "<div style='text-align: center;'>سيتم الغاء $tskTitle هل أنت متأكد?...</div><br>";
        echo "<input type='hidden' name='activeTab' value='tasks'>";
        echo "<input type='hidden' name='sysvalid' value='$tskId'>";
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='delete'>موافق</button> <button type='submit' class='cnlBtn' name='mode' value=''>تراجع</button></div>";
        echo "</form>";
    }
    if($mode=="delete"){
        $q="delete from tasks where tskId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $tskId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}
if($showList){
    $hideCompleted="";
    $buttonLabel="إخفاء المكتمل";
    $buttonValue="0";
    if($showCompleted==0){
        $hideCompleted="AND tskPercentComplete<100";
        $buttonLabel="إظهار المكتمل";
        $buttonValue="1";
    }
    $q="select tskId,tskTitle,tskPercentComplete,if(tskEndDate<curdate() and tskPercentComplete<100,1,0) as tskLate from tasks where tskUser=$__uid $hideCompleted order by tskStartDate";
    $r=mysqli_query($dbc,$q);
    if($r){
        echo "<div style='width: 210px; margin: auto;'><table><tr><td><form method='post'><input type='hidden' name='activeTab' value='tasks'><button type='submit' name='mode' value='add' class='newBtn'>مهمة جديد</button></form></td>";
        echo "<td><form method='post'><input type='hidden' name='activeTab' value='tasks'><button type='submit' name='showCompleted' value='$buttonValue' class='newBtn'>$buttonLabel</button></form></td></tr></table></div><br>";
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث . . .' title='Type in a name'>";
        echo "<table id=\"mainTable\">";
        echo "<tr class=\"header\">";
        echo "<th>المهمة</th><th style=\"width:400px;text-align: center;\"></th></tr>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value)
                $$key=$value;
            if($tskLate==0)
                $barColor="Green";
            else
                $barColor="Red";
            echo "<tr><td>$tskTitle<br><div style='width:100%;Height:10px;background-color:grey;'><div style='width:".$tskPercentComplete."%;Height:10px;background-color:$barColor;'></div></div></td>";
            echo "<td><form method='post'>";
            echo "<input type='hidden' name='tskId' value='$tskId'><input type='hidden' name='activeTab' value='tasks'>";
            echo "<button type='submit' class='edtBtn' name='mode' value='edit'>تعديل</button> ";
            echo "<button type='submit' class='viwBtn' name='mode' value='view'>عرض</button> ";
            echo "<button type='submit' class='delBtn' name='mode' value='deleteConfirm'>الغاء</button> ";
            echo "</form></td>";
            echo "</tr>";
        }
        echo "</table>";
        mysqli_free_result($r);
    }
}
?>