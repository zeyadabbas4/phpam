<?php
$__includeDir="../include";                //path to include directory
$__systemRoot="../";                        //path to system root
include($__systemRoot."functions.php");     //system Functions don't remove
include('functions.php');                   //uncomment this line if you have a local functions file
include("appdb.php");                       //application database credentials
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
  }  
if(isset($_GET['CoursId'])){
    $CoursId=$_GET['CoursId'];
}
if(isset($CoursId)){
    if(!is_numeric($CoursId)){
        $die;
    }
    $errorMessage="";
    $q="SELECT `CoursNotesLink`,`CrsName`,`CrsCode` FROM `Courses` INNER JOIN `CoursesGuide` ON `CoursCrsId`=`CrsId` WHERE `CoursId`=?";
    if ($stmt = mysqli_prepare($dbc, $q)){
        if(mysqli_stmt_bind_param($stmt, "i", $CoursId)){
          if(mysqli_stmt_execute($stmt)){
            if(mysqli_stmt_bind_result($stmt, $CoursNotesLink,$CrsName,$CrsCode)){
              if(!mysqli_stmt_fetch($stmt)){
                $errorMessage.= "<span dir='ltr'>Error reading data [030105".$__uid.date("YmdHis")."]!...</span><br>";
              }
            }else{
              $errorMessage.= "Error reading data [030104".$__uid.date("YmdHis")."]!...<br>";
            }
          }else{
            $errorMessage.= "Error reading [030103".$__uid.date("YmdHis")."]!...<br>";
          }
        }else{
          $errorMessage.= "Error reading [030102".$__uid.date("YmdHis")."]!...<br>";
        }
        mysqli_stmt_close($stmt);
      }else{
        $errorMessage.= "Error reading [030101".$__uid.date("YmdHis")."]!...<br>";
      }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Course notes QR code</title>
        <script type="text/javascript" src="qrcode.js"></script>
    </head>
    <body style="direction:rtl;font-size: 14pt;">
        <div style="width:19cm;height:24cm;margin:auto;">
            <img src="img/qrhead.jpg" style="width: 100%;">
            <table style="width:100%;">
                <tr><td>البرنامج التدريبي:</td><td><?php echo $CrsName; ?></td></tr>
                <tr><td>كود البرنامج:</td><td><?php echo $CrsCode; ?></td></tr>
            </table>
            <?php
            if(isset($errorMessage)){
                echo "<div style='text-align:center;color:red;font-weight:bold;'>$errorMessage</div>";
            }
            ?>
            <br>
            <p style="text-align: center;font-weight: bold;">
                لتحميل المادة العلمية برجاء<br>مسح رمز الاستجابة السريعة
            </p>
            <?php
            if($CoursNotesLink !=""){
                echo "<div id='qrcode' style='width:200px; height:100px; margin:Auto;'></div>";
            }
            ?>
            <br><br><br><br><br><br><br><br>
            <table style="width:100%;">
                <tr>
                    <td width=""80%><img src="img/addressLine.png" style="width:100%;"></td>
                    <td><img src="img/40YearsLogo.png" style="width:100%;"></td>
                </tr>
            </table>    
        </div>
        <script type="text/javascript">
            var qrcode = new QRCode(document.getElementById("qrcode"), {
                width : 200,
                height : 200,
                colorDark : "#000000",
                colorLight : "#ffffff"
            });
            
            function makeCode () {
                qrcode.makeCode("<?php echo $CoursNotesLink; ?>");
            }
            
            makeCode();
            
            $("#text").
                on("blur", function () {
                    makeCode();
                }).
                on("keydown", function (e) {
                    if (e.keyCode == 13) {
                        makeCode();
                    }
                });
            </script>            
    </body>
</html>