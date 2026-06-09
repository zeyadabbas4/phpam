<?php
$showList=true;
if($activeTab=="messages"){
    if($mode==5){
        //save new value
        if($msgTitle==""){
            $errorMessage.="لابد من إدخال موضوع الرسالة<br>";
            $mode=1;
        }
        if($msgBody==""){
            $errorMessage.="لابد من إدخال الرسالة<br>";
            $mode=1;
        }
        if($mode==5){
            $q="insert into messages(msgTitle,msgBody,msgFrom,msgTo) values(?,?,$__uid,?)";
            if ($stmt = mysqli_prepare($dbc, $q)) {
                mysqli_stmt_bind_param($stmt, "ssi", $msgTitle,$msgBody,$msgTo);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }
    if($mode==1){
        $showList=false;
        $q="select usrId,usrFullName from users where usrId != ?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $__uid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $usrId,$usrFullName);
            $i=0;
            while(mysqli_stmt_fetch($stmt)){
                $toUsers[$i]['usrId']=$usrId;
                $toUsers[$i]['usrFullName']=$usrFullName;
                $i++;
            }
            mysqli_stmt_close($stmt);
        }
        $usrCount=$i-1;

        echo "<form method='post' style='max-width:500px;margin:auto'>";
        echo "<h3 style='text-align: center;'>مهمة جديدة</h3>";
        echo "المرسل اليه:<br>";
        echo "<div class='input-container'>";
        echo "<select  class='input-field' name='msgTo'>";
        for($i=0;$i<=$usrCount;$i++){
            echo "<option value='".$toUsers[$i]['usrId']."'>".$toUsers[$i]['usrFullName']."</option>";
        }
        echo "</select></div>";
        echo "عنوان الرسالة:<br>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='عنوان الرسالة . . .' name='msgTitle'";
        if(isset($msgTitle))
            echo " value='$msgTitle'";
        echo ">";
        echo "</div>";
        echo "نص الرسالة:<br>";
        echo "<div class='input-container'>";
        echo "<textarea class='input-field' placeholder='نص الرسالة . . .' name='msgBody' rows='5' cols='40'>";
        if(isset($msgBody))
            echo "$msgBody";
        echo "</textarea></div>";
        echo "<input type='hidden' name='activeTab' value='messages'>";
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='5'>إرسال</button> <button type='submit' class='cnlBtn' name='mode' value='0'>تراجع</button></div>";
        echo "</form>";
        echo "<div class='error'><p>$errorMessage</p></div>";
    }
    if($mode==3){
        $showList=false;
        $q="select usrId,usrFullName from users";
        $r=mysqli_query($dbc,$q);
        if($r){
            while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
                foreach($row as $key => $value){
                    $$key=$value;
                }
                $userList[$usrId]=$usrFullName;
            }
        }
        $q="select msgTitle,msgBody,msgFrom,msgTo from messages where msgId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $msgId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $msgTitle,$msgBody,$msgFrom,$msgTo);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }
        $q="update messages set msgRead=1 where msgId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $msgId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        echo "<form method='post' style='max-width:500px;margin:auto'>";
        echo "<h3 style='text-align: center;'>الرسائل - عرض رسالة</h3>";
        echo "المرسل اليه:<br>";
        echo "<div class='input-container'>";
        echo $userList[$msgTo];
        echo "</div>";
        echo "عنوان الرسالة:<br>";
        echo "<div class='input-container'>";
        echo "<b>$msgTitle</b>";
        echo "</div>";
        echo "نص الرسالة:<br>";
        echo "<div class='input-container'>";
        echo nl2br($msgBody);
        echo "</div>";
        echo "<input type='hidden' name='folder' value='$folder'>";
        echo "<input type='hidden' name='msgTitle' value='$msgTitle'>";
        echo "<input type='hidden' name='msgBody' value='$msgBody'>";
        echo "<input type='hidden' name='activeTab' value='messages'>";
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='1'>إرسال</button> <button type='submit' class='cnlBtn' name='mode' value='0'>العودة</button></div>";
        echo "</form>";
    }
    if($mode==4){
        if($folder!=3){
            $q="update messages set msgDeleted=1 where msgId=?";
        }else{
            $q="delete from messages where msgId=?";
        }
        if ($stmt = mysqli_prepare($dbc, $q)){
            mysqli_stmt_bind_param($stmt, "i", $msgId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    if($mode==6){
        $q="update messages set msgDeleted=0 where msgId=?";
        if ($stmt = mysqli_prepare($dbc, $q)){
            mysqli_stmt_bind_param($stmt, "i", $msgId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}
if($showList){
    $q="select usrId,usrFullName from users";
    $r=mysqli_query($dbc,$q);
    if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
                $$key=$value;
            }
            $userList[$usrId]=$usrFullName;
        }
    }
    if(!isset($folder)){
        $folder=1;
    }
    switch($folder){
        case 1:
            $q="select msgId,msgTitle,msgFrom,msgRead from messages where msgTo=$__uid and msgDeleted=0 order by msgDate";
            $folderName="صندوق الوارد";
            $col1Name="اسم المرسل";
            $col2Name="عنوان الرسالة";
            $buttonsColumnWidth="300px";
            $showCol3=false;
            break;
        case 2:
            $q="select msgId,msgTitle,msgTo,msgRead from messages where msgFrom=$__uid and msgDeleted=0  order by msgDate";
            $folderName="صندوق الصادر";
            $col1Name="اسم المرسل اليه";
            $col2Name="عنوان الرسالة";
            $buttonsColumnWidth="300px";
            $showCol3=false;
            break;
        case 3:
            $q="select msgId,msgTitle,msgFrom,msgTo,msgRead from messages where msgDeleted=1  order by msgDate";
            $folderName="الرسائل الملغاه";
            $col1Name="اسم المرسل";
            $col2Name="اسم المرسل اليه";
            $col3Name="عنوان الرسالة";
            $buttonsColumnWidth="400px";
            $showCol3=true;
    }
    echo "<h2 style='text-align: center;'>قائمة الرسائل - $folderName</h2>";
    $r=mysqli_query($dbc,$q);
    if($r){
        echo "<div style='width:100%;height:35px;'>";
        echo "<div style='width: 100px;height:35px; float:right;'>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='mode' value='1'>";
        echo "<input type='hidden' name='activeTab' value='messages'>";
        echo "<button type='submit' class='newBtn'>رسالة جديدة</button>";
        echo "</form></div>";
        echo "<div style='width: 310px;height:35px; float:left;'>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='activeTab' value='messages'>";
        echo "<button type='submit' name='folder' value='1' class='edtBtn'>صندوق الوارد</button> ";
        echo "<button type='submit' name='folder' value='2' class='edtBtn'>صندوق الصادر</button> ";
        echo "<button type='submit' name='folder' value='3' class='edtBtn'>الرسائل الملغاه</button>";
        echo "</form></div>";
        echo "</div>";
        echo "<br>";
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث . . .' title='Type in a name'>";
        echo "<table id=\"mainTable\">";
        echo "<tr class=\"header\">";
        echo "<th>$col1Name</th><th>$col2Name</th>";
        if($showCol3){
            echo "<th>$col3Name</th>";
        }
        echo "<th style='width:$buttonsColumnWidth;text-align: center;'></th></tr>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
                $$key=$value;
            }
            switch($folder){
                case 1:
                    $col1Data=$userList[$msgFrom];
                    $col2Data=$msgTitle;
                    $buutons="<button type='submit' class='viwBtn' name='mode' value='3'>عرض</button> <button type='submit' class='delBtn' name='mode' value='4'>الغاء</button>";
                    break;
                case 2:
                    $col1Data=$userList[$msgTo];
                    $col2Data=$msgTitle;
                    $buutons="<button type='submit' class='viwBtn' name='mode' value='3'>عرض</button> <button type='submit' class='delBtn' name='mode' value='4'>الغاء</button>";
                    break;
                case 3:
                    $col1Data=$userList[$msgFrom];
                    $col2Data=$userList[$msgTo];
                    $col3Data=$msgTitle;
                    $buutons="<button type='submit' class='viwBtn' name='mode' value='3'>عرض</button> <button type='submit' class='yesBtn' name='mode' value='6'>إسترجاع</button> <button type='submit' class='delBtn' name='mode' value='4'>الغاء</button>";
            }
            echo "<tr><td>$col1Data</td><td>$col2Data</td>";
            if($showCol3){
                echo "<td>$col3Data</td>";
            }
            echo "<td><form method='post'>";
            echo "<input type='hidden' name='msgId' value='$msgId'>";
            echo "<input type='hidden' name='activeTab' value='messages'>";
            echo "<input type='hidden' name='folder' value='$folder'>";
            echo $buutons;
            echo "</form></td>";
            echo "</tr>";
        }
        echo "</table>";
        mysqli_free_result($r);
    }
}
?>