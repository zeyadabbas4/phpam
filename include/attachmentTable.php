<?php
/**
 * display the attachment table in edu
 * 
 * display a list of attachments of a course with a button to view the attachment
 * for the view button to work incluse also viewAttachment.php 
 * a CourseId variable should be present for this file to work
 * 
 * @param   int $CourseId   Id of the Course to display it's attachments
 * 
 * @return  void
 */
//read attachments
$attCount=0;
$q="SELECT COUNT(*) AS attCount FROM courseAttachments WHERE catCourse=?";
if ($stmt = mysqli_prepare($dbc, $q)){
    if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
    if(mysqli_stmt_execute($stmt)){
        if(mysqli_stmt_bind_result($stmt, $attCount)){
        mysqli_stmt_fetch($stmt);
        }
    }
    }
    mysqli_stmt_close($stmt);
}

if($attCount !=0){
    $attachments=array();
    $i=0;
    $q="SELECT catid,catDescription,catFile FROM courseAttachments WHERE catCourse=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
    if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
        if(mysqli_stmt_execute($stmt)){
        if(mysqli_stmt_bind_result($stmt, $catid,$catDescription,$catFile)){
            while(mysqli_stmt_fetch($stmt)){
            $attachments[$i]['Id']=$catid;
            $attachments[$i]['Description']=$catDescription;
            $attachments[$i]['File']=$catFile;
            $i++;
            }
        }
        }
    }
    mysqli_stmt_close($stmt);
    }  
}
 //بيانات المرفقات
echo "<div style='text-align: right;font-weight: bold;text-decoration:underline;'>بيانات المرفقات:</div>";
if($attCount !=0){
    echo "<table width='80%' class='lecTable'>";
    echo "<tr><th> م </th><th> بيان </th><th> &nbsp; </th></tr>";
    foreach($attachments as $key => $value){
    $ser=$key+1;
    $desc=$value['Description'];
    $catid=$value['Id'];
    $button="<form method='post'><button type='submit' class='viewBtn' name='mode' value='viewAttachment'>عرض</button>
    <input type='hidden' name='CoursId' value='$CoursId'>
    <input type='hidden' name='catid' value='$catid'>
    <input type='hidden' name='returnValue' value='view'>
    </form>";
    echo "<tr><td> $ser </td><td> $desc </td><td style='text-align: center'> $button </td></tr>";
    }
    echo "</table>";
}
?>