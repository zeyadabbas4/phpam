<?php
$__systemRoot="../";
include($__systemRoot."functions.php");
include("appdb.php");
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
$submitted=$_POST['submitted'];
echo "<!DOCTYPE html><html><head><title>Import CSV</title><meta charset='UTF-8'>";
echo "<script>
var rows=0;
function addrows(noRows){
    var table=document.getElementById(\"formTable\");
    var currentRows=table.rows.length;
    var newRows=noRows-currentRows+3;
    if(newRows>0){
        for(var i=1;i<=newRows;i++){
            var row=table.insertRow();
            rows++;
            row.id=\"row\"+rows;
            var cell=row.insertCell(0);
            cell.innerHTML=\" Field \" + rows + \" name:\";
            var cell=row.insertCell(1);
            cell.innerHTML=\" <input type='text' name='field[\"+rows+\"]' required>\";
            var cell=row.insertCell(2);
            cell.innerHTML=\" Field \" + rows + \" type:\";
            var cell=row.insertCell(3);
            cell.innerHTML=\" <select name='fieldtype[\"+rows+\"]'><option value='s'>String</option><option value='i'>Number</option></select>\";
        }    
    }
    if(newRows<0){
        newRows=Math.abs(newRows);
        for(i=1;i<=newRows;i++){
            rowToRemove=rows+2;
            document.getElementById(\"formTable\").deleteRow(rowToRemove);
            rows--;
        }
    }
  }
    </script>
";
echo "</head><body><center>";
echo "<h2>Import CSV into table</h2>";
if(isset($submitted)){
    $target_dir = "csv/";
    $target_file = $target_dir . basename($_FILES["csvFileName"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if($fileType != "csv"){
        echo "<span style='color:red;'>File type not correct.</span><br>";
        $uploadOk = 0;
    }
    if (file_exists($target_file)) {
        echo "<span style='color:red;'>File already exists.</span><br>";
        $uploadOk = 0;
    }
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "<span style='color:red;'>Your file is too large.</span><br>";
        $uploadOk = 0;
    } 
    if ($uploadOk == 0) {
        echo "<span style='color:red;'>Sorry, file not uploaded.</span><br>";
        unset($submitted);
    } else {
        if (move_uploaded_file($_FILES["csvFileName"]["tmp_name"], $target_file)) {
            echo "<span style='color:green;'>The file ". basename( $_FILES["csvFileName"]["name"]). " has been uploaded.</span><br>";
        } else {
            echo "<span style='color:red;'>Sorry, there was an error moving ".$_FILES["csvFileName"]["tmp_name"]." to $target_file.</span><br>";
            unset($submitted);
        }
    }
    if($uploadOk == 1){
        //analysing csv
        $ro = 1;
        $fileOk=1;
        if (($handle = fopen("$target_file", "r")) !== FALSE) {
            while (($data[$ro] = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $ro++;
            }
            fclose($handle);
            $ro--;
            $cols=count($data[1]);
            echo "<span style='color:green;'>$ro lines found with $cols columns each</span><br>";
            for($i=1;$i<=$ro;$i++){
                $rcols=count($data[$i]);
                if($cols != $rcols){
                    echo "<span style='color:red;'>Invalid number of columns in row $i</span><br>";
                    echo "<span style='color:red;'>Found only $rcols columns...</span><br>";
                    echo "<span style='color:red;'>Dumbing row data:</span><br>";
                    foreach($data[$i] as $value3){
                        echo "[ " . $value3 . " ]";
                    }
                    echo "<br>";
                    $fileOk=0;
                }
            }
        }
        if($fileOk==0){
            echo "<span style='color:red;'>Errors found in file aborting!...</span><br>";
            unset($submitted);
        }else{

        }
    }
/*
    $q="SELECT `CrsId`,`CrsCode` FROM `CoursesGuide`";
    $r=mysqli_query($dbc,$q);
    if($r){
        $ro=1;
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            $crsId[$ro]=$row['CrsId'];
            $crsCode[$ro]=$row['CrsCode'];
            $ro++;
        }
    }
    echo "Total $ro courses loaded!...<br>";
    $ro = 1;
    if (($handle = fopen("co2.csv", "r")) !== FALSE) {
        while (($data[$ro] = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $ro++;
        }
        fclose($handle);
        $ro--;
        echo "Total $ro topic lines found in csv<br>";
        echo "<table border='1' cellspacing='0'>";
        foreach($data as $key => $value){
            if(array_search($value[3],$crsCode)){
                $tpcCourse=$crsId[array_search($value[3],$crsCode)];
                if($value[0] != ''){
                    $q="INSERT INTO CourseTopics(tpcId,tpccourse,tpcdesc,tpcthrs,tpcphrs) VALUES ($key,'$tpcCourse','$value[0]',$value[1],$value[2])";
                    $r=mysqli_query($dbc,$q);
                }
                echo "<tr>";
                foreach($value as $value2){
                    echo "<td>$value2</td>";
                }
                echo "</tr>";    
            }
        }
        echo "</table>";
    }
    echo "<br><br><center><button type='button' onclick='window.location.replace(\"import_topics.php\");'> Back </button></center>";
    */
}
if(!isset($submitted)){
    echo "<form method='post' enctype='multipart/form-data'>";
    echo "<table id='formTable'><tr><td>CSV file:</td><td colspan='3'><input type='file' name='csvFileName' required></td></tr>";
    echo "<tr><td>Table name:</td><td colspan='3'><input type='text' name='table' required>";
    echo "<tr><td>Number of Columns:</td><td colspan='3'><input type='text' name='colNo' onchange='addrows(this.value);' required>";
    echo "</table>";
    echo "<input type='hidden' name='submitted' value='1'>";
    echo "<input type='submit' value='upload'>";
    echo "</form>";
}
echo "</center></body></html>";
?>
