<?php
include("../functions.php");
include("appdb.php");
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
if(isset($_GET['distId'])){
    $distId=$_GET['distId'];
}
if(isset($distId)){
    $result=array();
    $i=0;
    $q="SELECT cmpId,cmpName FROM companies WHERE cmpDistrict=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt,"i",$distId)){
            if(mysqli_stmt_execute($stmt)){
                if(mysqli_stmt_bind_result($stmt,$cmpId,$cmpName)){
                    while(mysqli_stmt_fetch($stmt)){
                        $result[$i]['id']=$cmpId;
                        $result[$i]['name']=$cmpName;
                        $i++;
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
}
echo json_encode($result);
?>