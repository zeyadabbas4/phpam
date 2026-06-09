<?php
/**
 * read course nominees into an array
 * 
 * read course nominees into an array 
 * 
 * @param   object  $dbc    database connection
 * 
 * @return  array   $nom  array of course nominees
 */
//Nominated counts
$nom=array();
$q="SELECT crscmpPlanned,cmpName FROM courseCompanies INNER JOIN companies ON crscmpCompany=cmpId WHERE crscmpCourse=?";
if ($stmt = mysqli_prepare($dbc, $q)) {
if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
    if(mysqli_stmt_execute($stmt)){
    if(mysqli_stmt_bind_result($stmt, $crscmpPlanned,$cmpName)){
        $i=0;
        while(mysqli_stmt_fetch($stmt)){
        $nom[$i]['company']=$cmpName;
        $nom[$i]['planned']=$crscmpPlanned;
        $nom[$i]['actual']=0;
        $i++;
        }
    }
    }
}
mysqli_stmt_close($stmt);
}
?>