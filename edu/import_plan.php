<?php
$__systemRoot="../";
include($__systemRoot."functions.php");
include("appdb.php");
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
$submitted=$_POST['submitted'];
echo "<!DOCTYPE html><html><head><title>Import Plan</title><meta charset='UTF-8'>";
echo "</head><body><center>";
echo "<h2>Import CSV into table</h2>";
if(isset($submitted)){
    $target_dir = "stage/";
    $target_file = $target_dir . basename($_FILES["fileName"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if($fileType != "zip"){
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
        if (move_uploaded_file($_FILES["fileName"]["tmp_name"], $target_file)) {
            echo "<span style='color:green;'>The file ". basename( $_FILES["fileName"]["name"]). " has been uploaded.</span><br>";
        } else {
            echo "<span style='color:red;'>Sorry, there was an error moving ".$_FILES["fileName"]["tmp_name"]." to $target_file.</span><br>";
            unset($submitted);
        }
    }
    if($uploadOk == 1){
        //read zip fle
        $zipOk=true;
        $zip = new ZipArchive;
        $res = $zip->open($target_file);
        if ($res === TRUE) {
            echo 'ok';
            $zip->extractTo('stage/');
            $zip->close();
        } else {
            echo 'failed, code:' . $res;
            $zipOk=false;
        }
/*
        //reading csv
        $ro = 1;
        if (($handle = fopen("$target_file", "r")) !== FALSE) {
            while (($data[$ro] = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $ro++;
            }
            fclose($handle);
            $ro--;
            echo "<span style='color:green;'>$ro lines found...</span><br>";
        }

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
        if (($handle = fopen("$target_file", "r")) !== FALSE) {
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
                } else {
                    $notFound[]=$value;
                }
            }
            echo "</table>";
            echo "<br><br>";
            echo "<h2>Items not found</h2>";
            echo "<table border='1' cellspacing='0'>";
            foreach($notfound as $key => $value){
                echo "<tr><td>$value[3]</td><td>$value[0]</td></tr>";
            }
            echo "</table>";
        }
        echo "<br><br><center><button type='button' onclick='window.location.replace(\"import_topics.php\");'> Back </button></center>";
        */
    }
}
if(!isset($submitted)){
    echo "<form method='post' enctype='multipart/form-data'>";
    echo "<table id='formTable'><tr><td>CSV file:</td><td colspan='3'><input type='file' name='fileName' required></td></tr>";
    echo "</table>";
    echo "<input type='hidden' name='submitted' value='1'>";
    echo "<input type='submit' value='upload'>";
    echo "</form>";
}
echo "</center></body></html>";
?>
