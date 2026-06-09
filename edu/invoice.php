<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$__systemRoot="../";  
include($__systemRoot."functions.php"); 
include("appdb.php");                       //application database credentials
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
//get course name
$CrsNames=array();
$CoursBulletins=array();
$crsFees=array();
if(isset($_SESSION['CoursId'])){
    $CoursId=$_SESSION['CoursId'];
    //read course name
    $q="select CrsName,CoursBulletin from CoursesGuide inner join Courses on CrsId=CoursCrsId where CoursId=$CoursId";
    $r=mysqli_query($dbc,$q);
    if($r){
        if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            foreach($row as $key => $value){
                $$key=$value;
            }
            $CrsNames[]=$CrsName;
            $CoursBulletins[]=$CoursBulletin;
            $crsFees[]=1650;
        }
    }    
}
//Read companies
$comps=array();
$q="SELECT cmpId,cmpName from companies";
$r=mysqli_query($dbc,$q);
if($r){
  while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
    foreach($row as $key => $value){
      $$key=$value;
    }
    $comps[$cmpId]=$cmpName;
  }
}
if(isset($_SESSION['TrnNo'])){
    $TrnNo=$_SESSION['TrnNo'];
    //read trainnee info
    $q="SELECT TrnNo,TrnName,TrnCo,trnNationality,TrnIdNo,TrnWhatsApp,TrnTels FROM Trainees WHERE TrnNo='$TrnNo'";
    $r=mysqli_query($dbc,$q);
    if($r){
      if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value){
          $$key=$value;
        }
        $cmpName=$comps[$TrnCo];
      }
    }
}
$otherLable="إصدار شهادات / مصروفات إدارية ولوجستية";
$otherFees=500;
//calculate invoice amount
$totFees=0;
foreach($crsFees as $key => $value){
    $totFees +=  $value;
}
$totFees+=$otherFees;
//calculate invoice no.
$lastInvNo="";
$invoiceDate=date("Y-m-d");
$q="SELECT COUNT(invId) AS invCount FROM invoices WHERE invDate='$invoiceDate'";
$r=mysqli_query($dbc,$q);
if($r){
  if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
    foreach($row as $key => $value){
      $$key=$value;
    }
  }
}
//$ser=substr("000" . $invCount+1,-3,3);
$newSer=$invCount+1;
$ser=substr("000".$newSer,-3,3);
$invoiceNo=date("dmyHis").$ser;
//save invoice
$invSaveError=false;
$q="INSERT INTO invoices (invId, invTrn, invDate, invAmount, invPaid) VALUES ('$invoiceNo', $TrnNo, '$invoiceDate', $totFees, 0);";
$r=mysqli_query($dbc,$q);
if($r){
    foreach($CrsNames as $key => $value){
        $q="INSERT INTO InvoiceDetails (invdetInvoice, indetser, invdetItem, invdetAmount) VALUES ('$invoiceNo', $key, '$value', $crsFees[$key])";
        $r=mysqli_query($dbc,$q);
        if(!$r){
            $invSaveError=true;
        }
    }
    $ser=$key+1;
    $q="INSERT INTO InvoiceDetails (invdetInvoice, indetser, invdetItem, invdetAmount) VALUES ('$invoiceNo', $ser, '$otherLable', $otherFees)";
    $r=mysqli_query($dbc,$q);
    if(!$r){
        $invSaveError=true;
    }
}else{
    $invSaveError=true;
}



function drawInvoice($copy=false){
    global $CrsNames,$TrnName,$cmpName,$TrnIdNo,$TrnWhatsApp,$TrnTels,$CoursBulletins,$otherLable,$otherFees,$crsFees,$invoiceNo,$invoiceDate,$totFees;
    
    if($copy){
        $copyStyle=" copy";
    }else{
        $copyStyle="";
    }
    echo "
        <div class='invoice$copyStyle'>
            <table>
                <tr>
                    <td class='logo'><img src='img/pti.png'></td>
                    <td class='title title-text'>اﻷكاديمية العربية للعلوم والتكنولوجيا والنقل البحري<br>معهد تدريب الموانئ</td>
                </tr>
                <tr>
                    <td colspan='2' class='title title-text'>حافظة توريد رسوم دورات رقم: <span class='invoiceNo'> *$invoiceNo* </span></td>
                </tr>
                <tr>
                    <td colspan='2' class='details details-text'><span class='details-title'>إسم الطالب:</span> $TrnName ".str_repeat("&nbsp;",5)."<span class='details-title'>الرقم القومي:</span> $TrnIdNo </td>
                </tr>
                <tr>
                <td colspan='2' class='details details-text'><span class='details-title'>جهة اﻹيفاد:</span> $cmpName ".str_repeat("&nbsp;",5)."<span class='details-title'>رقم التليفون:</span> $TrnTels </td>
                </tr>
                <tr>
                    <td colspan='2' class='details details-text'><span class='details-title-underlined'>المصروفات اﻷساسية:</span><!--(دورة: )--><br>
                        <table class='details-table'>";
    $l=0;
    foreach($CrsNames as $key => $value){
        echo "<tr><td class='details-label'> $value - $CoursBulletins[$key] </td><td class='details-amount'> $crsFees[$key] </td></tr>";
        $l++;
    }
    for($i=4;$i>$l;$i--){
        echo "<tr><td class='details-label'> &nbsp; </td><td class='details-amount'> &nbsp; </td></tr>";
    }
    echo "              </table>
                    </td>
                </tr>
                <tr>
                    <td colspan='2' class='details details-text'><span class='details-title-underlined'>مصروفات أخرى:</span>
                        <table class='details-table'>
                            <tr><td class='details-label'> $otherLable </td><td class='details-amount'> $otherFees </td></tr>
                        </table>
                    </td>
                </tr>";
    echo "      <tr>
                    <td colspan='2' class='details details-text'>
                        <table class='details-table'>
                            <tr><td class='details-title'>إجمالي المصروفات</td><td class='details-amount total-amount'> $totFees </td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan='2'>
                        <table class='signatures-table'>
                            <tr>
                                <td class='signatures signatures-text'>مسئول التسجيل</td>
                                <td class='signatures signatures-text'>قسم المراجعة</td>
                                <td class='signatures signatures-text'>أمين الخزينة<br></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan='2'>
                        <table class='footer-table'>
                            <tr><td class='footer-comment'><u>ملحوظة</u><br>- لا تعتمد هذه الحافظة إلا بتوقيع الخزينة</td><td class='footer-date'>تحريرا في $invoiceDate</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>حافظة سداد مصروفات</title>
        <meta charset='utf8'>
<?php
    include("invoicecss.php");
?>
    </head>
    <body>
        <?php
        if(!$invSaveError){
            drawInvoice(); 
            echo "<div class='seperator'><br><hr></div>";
            drawInvoice(true);     
        }else{
            echo "<div style='color:red;'>خطأ في الحفظ</div>";
        }
        ?>
    </body>
</html>
