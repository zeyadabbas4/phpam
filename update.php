<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
include ("functions.php");
$dbc=sysdbConnect();
$logged=false;
if(isset($_SESSION['__uid'])){
    $__uid=$_SESSION['__uid'];
    $user=getUserDetails($__uid);
    if($__uid==0 or $user['usrClass']==2){
        $logged=true;
    }
}
if($logged){
    echo "<!DOCTYPE html><html><head><title> system updater </title><meta charset='utf-8'></head><body>";
    echo "<center><br>";
    echo "<h2>Upload Manager</h2>";
    echo "==============";
    if(isset($_POST["submitted"])){
        echo "<div style='border-style:solid;border-color:black;border-width:1px;width:600px'>";
        $target_dir = "update_cache/";
        $file_basename=basename($_FILES["fileToUpload"]["name"]);
        $final_destination=$_POST['final_destination'];
        $target_file = $target_dir . $file_basename;
        if($final_destination != ''){
            $file_finaldestination=$final_destination . "/" . $file_basename;
        }else{
            $file_finaldestination=$file_basename;
        }
        $uploadOk = 1;
        $FileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "<br>Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "<br>Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($FileType != "php" && $FileType != "zip"){
            echo "<br>Sorry, unsupported file type.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "<br>Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            echo "<br>Moving uploaded file to $target_file ...";
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "<br>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            } else {
                echo "<br>Sorry, there was an error uploading your file.";
                $uploadOk = 0;
            }
        }
        if($uploadOk==1){
            if($FileType=="zip"){
                //read zip fle
                $zipOk=true;
                $zip = new ZipArchive;
                $res = $zip->open($target_file);
                if ($res === TRUE) {
                    echo 'Ok';
                    $zip->extractTo($target_dir);
                    $ro=0;
                    if(file_exists($target_dir."update.list")){
                        if (($handle = fopen($target_dir."update.list", "r")) !== FALSE) {
                            while (($data[$ro] = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                $ro++;
                            }
                        fclose($handle);
                        }
                        echo "<br>This archive contains the following updates...";
                        for($i=0;$i<$ro;$i++){
                            echo "<br>Applying ".$data[$i][0]."=>".$data[$i][1]."=>".$data[$i][2];
                            if($data[$i][0]=="file"){
                                if($data[$i][2]==""){
                                    $seperator="";
                                }else{
                                    $seperator="/";
                                }
                                if(rename($target_dir.$data[$i][1],$data[$i][2].$seperator.$data[$i][1])){
                                    echo " Successfull...";
                                }else{
                                    echo " with errors...";
                                }
                            }elseif($data[$i][0]=="menu"){
                                $q="select mnuId from menus where mnuName='".$data[$i][3]."' and mnuLevel=1  and mnuParent=(select mnuId from menus where mnuName='".$data[$i][2]."' and mnuLevel=0)";
                                $r=mysqli_query($dbc,$q);
                                if($r){
                                    while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
                                        foreach($row as $key => $value){
                                            $$key=$value;
                                        }
                                    }
                                }
                                $mnuName=$data[$i][1];
                                $mnuParent=$mnuId;
                                $mnuCommand=$data[$i][4];
                                $mnuLevel=2;
                                $q="Insert into menus(mnuName,mnuParent,mnuCommand,mnuLevel) values('$mnuName',$mnuParent,'$mnuCommand',$mnuLevel)";
                                $r=mysqli_query($dbc,$q);
                                if($r){
                                    echo " Successfull...";
                                }else{
                                    echo " with errors...";
                                }
                            }
                        }
                    }else{
                        echo "<br>Invalid archive";
                    }
                    $zip->close();
                } else {
                    echo 'failed, code:' . $res;
                    $zipOk=false;
                }
            }else{
                echo "<br>Moving to final destination...";
                if(rename($target_file,$file_finaldestination)){
                    echo "<br>Successfully moved $target_file to $file_finaldestination ...";
                }else{
                    echo "<br>Sorry, failed to move $target_file to $file_finaldestination ...";    
                }
            }
        }
        //clean up cache area
        echo "<br>Clean up update cache ...";
        $allfiles=scandir($target_dir);
        foreach($allfiles as $value){
            if($value != "." and $value != ".."){
                if(unlink($target_dir . $value)){
                    echo "<br>Deleted $target_dir" . "$value" . "...";
                }else{
                    echo "<br>Error deleting $target_dir" . "$value";
                }    
            }
        }
        echo "<br><br></div>";
    }
    echo "==============";
    echo "<br><br><form method='post' enctype='multipart/form-data'>";
    echo "Select file to upload:";
    echo "<input type='file' name='fileToUpload' id='fileToUpload' accept='.zip,.php' required><br><br>";
    echo "[ Only .zip or .php are accepted... ]<br><br>";
    echo "Target application (in case of php files): <select name='final_destination'>";
    if(!isset($final_destination)){
        $final_destination="";
    }
    echo "<option value=''";
    if($final_destination == ''){
        echo "selected";
    }
    echo ">System</option>";
    $q="SELECT mnuName,mnuDirectory FROM menus WHERE mnuLevel= 0 ";
    $r=mysqli_query($dbc,$q);
    if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
                $$key=$value;
            }
            echo "<option value='$mnuDirectory'";
            if($final_destination == $mnuDirectory){
                echo "selected";
            }
            echo ">$mnuName</option>";
        }
        echo "</select><br><br>";
        mysqli_free_result($r);
    }
    echo "<input type='hidden' name='submitted' value='1'>";
    echo "<input type='submit' value='Upload File'>";
    echo "</form>";
    echo "</center>";
    echo "</body></html>";
}
if(!$logged){
    include('expired.php');
}
?>