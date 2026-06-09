<?php
$showList=true;
echo "<h2 style='text-align: center;'>سجل الهاتف</h2>";
if($activeTab=="phoneBook"){
    if($mode==4){
        //save new value
        if($contName==""){
            $errorMessage.="لابد من إدخال اسم<br>";
            $mode=1;
        }
        if($contTele==""){
            $errorMessage.="لابد من ادخال رقم الهاتف<br>";
            $mode=1;
        }
        if($mode==4){
            $q="insert into phoneBook(contName,contTele,contUser) values(?,?,?)";
            if ($stmt = mysqli_prepare($dbc, $q)) {
                mysqli_stmt_bind_param($stmt, "ssi", $contName,$contTele,$__uid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }
    if($mode==1){
        $showList=false;
        echo "<form method='post' style='max-width:500px;margin:auto'>";
        echo "<h3 style='text-align: center;'>إضافة اسم جديد</h3>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='اﻹسم . . .' name='contName'";
        if(isset($contName))
            echo " value='$contName'";
        echo ">";
        echo "</div>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='رقم الهاتف . . .' name='contTele'";
        if(isset($contTele))
            echo " value='$contTele'";
        echo ">";
        echo "</div>";
        echo "<input type='hidden' name='activeTab' value='phoneBook'>";
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='4'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value='0'>تراجع</button></div>";
        echo "</form>";
        echo "<div class='error'><p>$errorMessage</p></div>";
    }
    if($mode==5){
        //save new value
        if($contName==""){
            $errorMessage.="لابد من إدخال اسم<br>";
            $mode=2;
        }
        if($contTele==""){
            $errorMessage.="لابد من ادخال رقم الهاتف<br>";
            $mode=2;
        }
        if($mode==5){
        $q="update phoneBook set contName=?,contTele=? where contId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "ssi", $contName,$contTele,$contId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        }
    }
    if($mode==2){
        $showList=false;
        $q="select contName,contTele from phoneBook where contId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $contId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $contName,$contTele);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }
        echo "<form method='post' style='max-width:500px;margin:auto'>";
        echo "<h3 style='text-align: center;'>تعديل</h3>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='اﻹسم . . .' name='contName'";
        if(isset($contName))
            echo " value='$contName'";
        echo ">";
        echo "</div>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='رقم الهاتف . . .' name='contTele'";
        if(isset($contTele))
            echo " value='$contTele'";
        echo ">";
        echo "</div>";
        echo "<input type='hidden' name='activeTab' value='phoneBook'>";
        echo "<input type='hidden' name='contId' value='$contId'>";
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='5'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value='0'>تراجع</button></div>";
        echo "</form>";
        echo "<div class='error'><p>$errorMessage</p></div>";
    }
    if($mode==3){
        $showList=false;
        $q="select contName from phoneBook where contId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $contId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $contName);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }
        echo "<form method='post' style='max-width:500px;margin:auto'>";
        echo "<h3 style='text-align: center;'>الغاء</h3>";
        echo "<div style='text-align: center;'>سيتم الغاء $contName هل أنت متأكد?...</div><br>";
        echo "<input type='hidden' name='activeTab' value='phoneBook'>";
        echo "<input type='hidden' name='contId' value='$contId'>";
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='6'>موافق</button> <button type='submit' class='cnlBtn' name='mode' value='0'>تراجع</button></div>";
        echo "</form>";
    }
    if($mode==6){
        $q="delete from phoneBook where contId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $contId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}
if($showList){
    $q="select * from phoneBook where contUser=$__uid order by contName";
    $r=mysqli_query($dbc,$q);
    if($r){
        echo "<div style='width: 110px; margin: auto;'><form method='post'><input type='hidden' name='mode' value='1'><input type='hidden' name='activeTab' value='phoneBook'><button type='submit' class='newBtn'>أسم جديد</button></form></div><br>";
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث . . .' title='Type in a name'>";
        echo "<table id=\"mainTable\">";
        echo "<tr class=\"header\">";
        echo "<th>الاسم</th><th>رقم الهاتف</th><th style=\"width:300px;text-align: center;\"></th></tr>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value)
                $$key=$value;
            echo "<tr><td>$contName</td><td>$contTele</td>";
            echo "<td><form method='post'><input type='hidden' name='contId' value='$contId'><input type='hidden' name='activeTab' value='phoneBook'><button type='submit' class='edtBtn' name='mode' value='2'>تعديل</button> <button type='submit' class='delBtn' name='mode' value='3'>الغاء</button></form></td>";
            echo "</tr>";
        }
        echo "</table>";
        mysqli_free_result($r);
    }
}
?>