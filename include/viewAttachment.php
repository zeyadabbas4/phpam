<?php
/**
 * View Course Attachment
 * 
 * This file should be included whenever you need to display a course attachment
 * the parameters are variables that are used in the file and should be provided
 * for it to function properly
 * it requires the including of readPrograms.php and readCourseData.php before using it
 * 
 * @param   object  $dbc        dtabase connection
 * @param   array   $prgs       Porgram Guide(include readProgram.php)
 * @param   int     $CoursCrsId Id of Program in program guide(include readCourseData.php)
 * @param   string  $returnValue    the name of the section to return to
 * @param   int     $catid      ID of the attachment to be viewed
 * @param   int     $coursId    the Id of the course to be forwarded
 * 
 * @return  void
 */
echo "<div style='text-align: center;width: 800px; margin: auto;'>";
echo "<h3>مرفقات دورة " . $prgs[$CoursCrsId]['Name']."</h3>";
$q="SELECT catDescription,catFile FROM courseAttachments WHERE catid=?";
if($stmt = mysqli_prepare($dbc, $q)){
    if(mysqli_stmt_bind_param($stmt, "i", $catid)){
        if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $catDescription,$catFile)){
                if(mysqli_stmt_fetch($stmt)){
                echo "$catDescription<br><br>";
                $fileType = strtolower(pathinfo($catFile,PATHINFO_EXTENSION));
                if($fileType == "jpg"){
                    echo "<img src='../edu/Attachments/$catFile' style='width: 80%;'>";
                }else if($fileType == "pdf"){
                    echo "<iframe src='../edu/Attachments/$catFile' style='width: 700px; height: 1000px;'></iframe>";
                }
                
                }
            }
        }
    }
}
echo "<form method='post'>";
echo "<input type='hidden' name='CoursId' value='$CoursId'>";
echo "<button type='submit' name='mode' class='okBtn' value='$returnValue' > حسنا </button> ";
echo "</form>";
echo "</div>";
?>