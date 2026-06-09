<?php
$showList=true;
if(!isset($mode)){
    $mode="";
}
$errorMessage="";
echo "<h2 style='text-align: center;'>أكواد qr</h2>";
if($activeTab=="qr"){

    if($mode=='edit' or $mode=='view' or $mode=='deleteconfirm'){
        $showList=false;
        $q="select qrDescription,qrLink,qrTitle from qr where qrId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $qrId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $qrDescription,$qrLink,$qrTitle);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    if($mode=='saveadd' or $mode=='saveedit'){
        //data validation
        $errorMessage="";
        if($qrDescription==""){
            $errorMessage.="لابد من إدخال وصف للكود<br>";
        }
        if($qrTitle==""){
            $errorMessage.="لابد من ادخال عنوان للكود<br>";
        }
        if($qrLink==""){
            $errorMessage.="لابد من ادخال وصلة الكود<br>";
        }
        if($errorMessage != ""){
            $mode=$substr($mode,4);
        }
    }

    if($mode=='saveadd'){
        //save added value
        $q="insert into qr(qrDescription,qrLink,qrTitle,qrUser) values(?,?,?,?)";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "sssi", $qrDescription,$qrLink,$qrTitle,$__uid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    if($mode=='saveedit'){
        //save edited value
        $q="update phoneBook set qrDescription=?,qrLink=?,qrTitle=? where qrId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "sssi", $qrDescription,$qrLink,$qrTitle,$qrId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    if($mode=='add' or $mode=='edit'){
        $showList=false;
        echo "<form method='post' style='max-width:500px;margin:auto'>";
        echo "<h3 style='text-align: center;'>إضافة qr جديد</h3>";

        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='عنوان الكود . . .' name='qrTitle'";
        if(isset($qrTitle))
            echo " value='$qrTitle'";
        echo ">";
        echo "</div>";

        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='وصف الكود . . .' name='qrDescription'";
        if(isset($qrDescription))
            echo " value='$qrDescription'";
        echo ">";
        echo "</div>";

        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' placeholder='الوصلة . . .' name='qrLink'";
        if(isset($qrLink))
            echo " value='$qrLink'";
        echo ">";
        echo "</div>";

        $newMode="save".$mode;
        if($mode=="edit"){
            echo "<input type='hidden' name='qrId' value='$qrId'>";
        }
        echo "<input type='hidden' name='activeTab' value='qr'>";
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'>حفظ</button> <button type='submit' class='cnlBtn' name='mode' value=''>تراجع</button></div>";
        echo "</form>";
        echo "<div class='error'><p>$errorMessage</p></div>";
    }

    if($mode=='view'){
        $showList=false;
        echo "<h3 style='text-align: center;'>بيانات qr</h3>";

        echo "<div class='input-container'>";
        echo "عنوان الكود : " . $qrTitle;
        echo "</div>";

        echo "<div class='input-container'>";
        echo "وصف الكود : " . $qrDescription;
        echo "</div>";

        echo "<div class='input-container'>";
        echo "الوصلة : " . $qrLink;
        echo "</div>";

        echo "<div style='text-align: center;'>";
        echo "<iframe src='qr/qr.php?lnk=".urlencode($qrLink)."' width='250px' height='250px'></iframe>";
        echo "</div>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='activeTab' value='qr'>";
        echo "<div class='frmButtons'><button type='submit' class='savBtn'>حسنا</button></div>";
        echo "</form>";
        echo "<div class='error'><p>$errorMessage</p></div>";
    }


    if($mode=="deleteconfirm"){
        $showList=false;
        echo "<form method='post' style='max-width:500px;margin:auto'>";
        echo "<h3 style='text-align: center;'>الغاء</h3>";
        echo "<div style='text-align: center;'>سيتم الغاء $qrTitle هل أنت متأكد?...</div><br>";
        echo "<input type='hidden' name='activeTab' value='qr'>";
        echo "<input type='hidden' name='qrId' value='$qrId'>";
        echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='delete'>موافق</button> <button type='submit' class='cnlBtn' name='mode' value='0'>تراجع</button></div>";
        echo "</form>";
    }
    if($mode=="delete"){
        $q="delete from qr where qrId=?";
        if ($stmt = mysqli_prepare($dbc, $q)) {
            mysqli_stmt_bind_param($stmt, "i", $qrId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}
if($showList){
    $q="select * from qr where qrUser=$__uid order by qrId";
    $r=mysqli_query($dbc,$q);
    if($r){
        echo "<div style='width: 110px; margin: auto;'><form method='post'>
        <input type='hidden' name='mode' value='add'>
        <input type='hidden' name='activeTab' value='qr'>
        <button type='submit' class='newBtn'>كود جديد</button></form></div><br>";
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='بحث . . .' title='Type in a name'>";
        echo "<table id=\"mainTable\">";
        echo "<tr class=\"header\">";
        echo "<th>وصف الكود</th><th style=\"width:330px;text-align: center;\"></th></tr>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value)
                $$key=$value;
            echo "<tr><td>$qrTitle</td>";
            echo "<td><form method='post'>
            <input type='hidden' name='qrId' value='$qrId'>
            <input type='hidden' name='activeTab' value='qr'>
            <button type='submit' class='edtBtn' name='mode' value='edit'>تعديل</button> 
            <button type='submit' class='edtBtn' name='mode' value='view'>عرض</button> 
            <button type='submit' class='delBtn' name='mode' value='deleteconfirm'>الغاء</button>
            </form></td>";
            echo "</tr>";
        }
        echo "</table>";
        mysqli_free_result($r);
    }
}
?>