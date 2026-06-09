<?php
$showList=true;
echo "<h2 style='text-align: center;'>سجل الملاحظات</h2>";
if($activeTab=="notes"){
    if($mode==5){
        //save new value
        if($noteTitle==""){
            $errorMessage.="لابد من إدخال العنوان<br>";
            $mode=1;
        }
        if($mode==5){
            $q="insert into notes(noteTitle,noteText,noteUser) values(?,?,?)";
            if ($stmt = mysqli_prepare($dbc, $q)) {
                mysqli_stmt_bind_param($stmt, "ssi", $noteTitle,$noteText,$__uid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }
    if($mode==1){
        $showList=false;
        echo "<form method='post' style='max-width:500px;margin:auto'>";
        echo "<h3 style='text-align: center;'>ملحوظة جديدة</h3>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='العنوان . . .' name='noteTitle' required";
        if(isset($noteTitle))
            echo " value='$noteTitle'";
        echo ">";
        echo "</div>";
        echo "<div class='input-container'>";
        echo "<textarea  wrap='hard' class='input-field' placeholder='الملاحظة . . .' name='noteText' rows='5' cols='40'>";
        if(isset($noteText))
            echo "$noteText";
        echo "</textarea></div>";
        echo "<input type='hidden' name='activeTab' value='notes'>";
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='5'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value='0'>تراجع</button></div>";
        echo "</form>";
        echo "<div class='error'><p>$errorMessage</p></div>";
    }
    if($mode==6){
        //save new value
        if($noteTitle==""){
            $errorMessage.="لابد من إدخال العنوان<br>";
            $mode=1;
        }
        if($mode==6){
            $q="update notes set noteTitle=?,noteText=? where noteId=?";
            if ($stmt = mysqli_prepare($dbc, $q)) {
                mysqli_stmt_bind_param($stmt, "ssi", $noteTitle,$noteText,$noteId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }
    if($mode==2){
        $showList=false;
        $q="select noteTitle,noteText from notes where noteId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $noteId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $noteTitle,$noteText);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }
        echo "<form method='post' style='max-width:500px;margin:auto'>";
        echo "<h3 style='text-align: center;'>تعديل</h3>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='العنوان . . .' name='noteTitle' required";
        if(isset($noteTitle))
            echo " value='$noteTitle'";
        echo ">";
        echo "</div>";
        echo "<div class='input-container'>";
        echo "<textarea wrap='hard' class='input-field' placeholder='الملاحظة . . .' name='noteText' rows='5' cols='40'>";
        if(isset($noteText))
            echo "$noteText";
        echo "</textarea></div>";
        echo "<input type='hidden' name='activeTab' value='notes'>";
        echo "<input type='hidden' name='noteId' value='$noteId'>";
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='6'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value='0'>تراجع</button></div>";
        echo "</form>";
        echo "<div class='error'><p>$errorMessage</p></div>";
    }

    if($mode==3){
        $showList=false;
        $q="select noteTitle,noteText from notes where noteId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $noteId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $noteTitle,$noteText);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }
        echo "<form method='post' style='max-width:500px;margin:auto'>";
        echo "<h3 style='text-align: center;'>عرض</h3>";
        echo "<div class='input-container'><strong> $noteTitle </strong></div>";
        echo "<div class='input-container'> ". nl2br($noteText) ." </div>";
        echo "<input type='hidden' name='activeTab' value='notes'>";
        echo "<input type='hidden' name='noteId' value='$noteId'>";
        echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode' value='0'>موافق</button></div>";
        echo "</form>";
    }
    if($mode==4){
        $showList=false;
        $q="select noteTitle from notes where noteId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $noteId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $noteTitle);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }
        echo "<form method='post' style='max-width:500px;margin:auto'>";
        echo "<h3 style='text-align: center;'>الغاء</h3>";
        echo "<div style='text-align: center;'>سيتم الغاء $noteTitle هل أنت متأكد?...</div><br>";
        echo "<input type='hidden' name='activeTab' value='notes'>";
        echo "<input type='hidden' name='sysvalid' value='$noteId'>";
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='7'>موافق</button> <button type='submit' class='cnlBtn' name='mode' value='0'>تراجع</button></div>";
        echo "</form>";
    }
    if($mode==7){
        $q="delete from notes where noteId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $noteId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}
if($showList){
    $q="select noteId,noteTitle from notes where noteUser=$__uid";
    $r=mysqli_query($dbc,$q);
    if($r){
        echo "<div style='width: 110px; margin: auto;'><form method='post'><input type='hidden' name='mode' value='1'><input type='hidden' name='activeTab' value='notes'><button type='submit' class='newBtn'>ملاحظة جديد</button></form></div><br>";
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث . . .' title='Type in a name'>";
        echo "<table id=\"mainTable\">";
        echo "<tr class=\"header\">";
        echo "<th>الملاحظة</th><th style=\"width:400px;text-align: center;\"></th></tr>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value)
                $$key=$value;
            echo "<tr><td>$noteTitle</td>";
            echo "<td><form method='post'><input type='hidden' name='noteId' value='$noteId'><input type='hidden' name='activeTab' value='notes'><button type='submit' class='edtBtn' name='mode' value='2'>تعديل</button> <button type='submit' class='viwBtn' name='mode' value='3'>عرض</button> <button type='submit' class='delBtn' name='mode' value='4'>الغاء</button></form></td>";
            echo "</tr>";
        }
        echo "</table>";
        mysqli_free_result($r);
    }
}
?>