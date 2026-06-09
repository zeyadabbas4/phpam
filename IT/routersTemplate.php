<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$__systemRoot="../";                        //path to system root
include($__systemRoot."functions.php");     //system Functions don't remove
//include('functions.php');                   //uncomment this line if you have a local functions file
include("appdb.php");                       //application database credentials
foreach($_POST as $key => $value){
  $$key=$value;
}

if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
$__dir="rtl";
$showList=true;
$logged=false;
$labelWidth="100px";
$formWidth="1000px";
if(!isset($mode)){
  $mode="";
}
if(!isset($errorMessage)){
  $errorMessage="";
}
if(isset($_SESSION['__uid'])){
  $__uid=$_SESSION['__uid'];
  $fileName = basename($_SERVER['PHP_SELF']);
  $mnuId=getCommandMenuId($fileName);
  if(checkUserMenuItem($__uid,$mnuId)){
    $logged=true;
  }
  if($logged){
    $logDir=$__systemRoot."/logs";
    $logFile="IT.log";
    $pageTitle=" Routers List ";
    echo "<!DOCTYPE html><html><head>";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
    echo "<title>$pageTitle</title>";
    echo "<style>";
    echo "* { box-sizing: border-box;}";
    echo "#filterBox {background-image: url('images/searchicon.png');background-position: 10px 10px;background-repeat: no-repeat;width: 100%;font-size: 16px;padding: 12px 20px 12px 40px;border: 1px solid #ddd;margin-bottom: 12px;}";
    echo "#mainTable {border-collapse: collapse;width: 100%;border: 1px solid #ddd;font-size: 18px;}";
    echo "#mainTable th, #mainTable td {text-align: left;padding: 12px;}";
    echo "#mainTable tr {border-bottom: 1px solid #ddd;}";
    echo "#mainTable tr.header, #mainTable tr:hover {background-color: #f1f1f1;}";
    echo ".navBtn {background-color: dodgerblue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".navBtn:hover {opacity: 1;}";
    echo ".addBtn {background-color: MidnightBlue ;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;margin: auto;}";
    echo ".addBtn:hover {opacity: 1;}";
    echo ".edtBtn {background-color: teal;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".edtBtn:hover {opacity: 1;}";
    echo ".pwdBtn {background-color: Blue;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".pwdBtn:hover {opacity: 1;}";
    echo ".delBtn {background-color: darkred;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".delBtn:hover {opacity: 1;}";
    echo ".savBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".savBtn:hover {opacity: 1;}";
    echo ".genBtn {background-color: DarkMagenta ;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".genBtn:hover {opacity: 1;}";
    echo ".viewBtn {background-color: Chocolate;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".viewBtn:hover {opacity: 1;}";
    echo ".cnlBtn {background-color: red;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".cnlBtn:hover {opacity: 1;}";
    echo ".okBtn {background-color: indigo;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".okBtn:hover {opacity: 1;}";
    echo ".grpBtn {background-color: green;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".grpBtn:hover {opacity: 1;}";
    echo ".arrowBtn {background-color: DarkOrchid;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 50px;  opacity: 0.9;}";
    echo ".arrowBtn:hover {opacity: 1;}";
    echo ".disBtn {background-color: grey;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 100px;  opacity: 0.9;}";
    echo ".disBtn:hover {opacity: 1;}";
    echo ".input-container {display: -ms-flexbox; /* IE10 */  display: flex;  width: 100%;  margin-bottom: 15px;}";
    echo ".input-field { width: 100%;  padding: 10px;  outline: none;}";
    echo ".input-field:focus {border: 2px solid dodgerblue;}";
    echo ".error{color:red; font-weight:bold;text-align:center;direction:rtl;}";
    echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";
    echo "</style>";
    echo "</head>";
    echo "<script>
          var rows=0;
          function filterList() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById(\"filterBox\");
            filter = input.value.toUpperCase();
            table = document.getElementById(\"mainTable\");
            tr = table.getElementsByTagName(\"tr\");
            for (i = 0; i < tr.length; i++) {
              td = tr[i].getElementsByTagName(\"td\")[0];
              if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                  tr[i].style.display = \"\";
                } else {
                  tr[i].style.display = \"none\";
                }
              }
            }
          }
          function addrow(conttitle='',contname='',contjob='',conttels='',contemail=''){
            var table=document.getElementById(\"subForm\");
            var row=table.insertRow();
            rows++;
            row.id=\"row\"+rows;
            var cell=row.insertCell(0);
            cell.innerHTML=\"<input class='input-field' type='text' placeholder='اللقب' name='conttitle[\"+rows+\"]' value='\"+conttitle+\"'>\";
            var cell=row.insertCell(1);
            cell.innerHTML=\"<input class='input-field' type='text' placeholder='الاسم' name='contname[\"+rows+\"]' value='\"+contname+\"'>\";
            var cell=row.insertCell(2);
            cell.innerHTML=\"<input class='input-field' type='text' placeholder='الوظيفة' name='contjob[\"+rows+\"]' value='\"+contjob+\"'>\";
            var cell=row.insertCell(3);
            cell.innerHTML=\"<input class='input-field' type='text' placeholder='التليفونات' name='conttels[\"+rows+\"]' value='\"+conttels+\"'>\";
            var cell=row.insertCell(4);
            cell.innerHTML=\"<input class='input-field' type='text' placeholder='البريد الالكتروني' name='contemail[\"+rows+\"]' value='\"+contemail+\"'>\";
            var cell=row.insertCell(5);
            cell.innerHTML=\"<button type='button' class='cnlBtn' style='width:50px;' onclick='delRow(this);'> - </button>\";
            cell.style=\"text-align:center\";
          }
          function delRow(row) {
            var i = row.parentNode.parentNode.rowIndex;
            document.getElementById(\"subForm\").deleteRow(i);
          }
        </script>";
	  echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>$pageTitle</h2>";

    $routers=array();
    $q="select rID,rIP from routers";
    $r=mysqli_query($dbc,$q);
    if($r){
      while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        $routers[$row['rID']]['ID']=$row['rID'];
        $routers[$row['rID']]['IP']=$row['rIP'];
      }
    }
echo "<pre>";
print_r($routers);
echo "</pre>";

        mysqli_close($dbc);
    echo "</body></html>";
  }} if(!$logged){include($__systemRoot."expired.php");}
?>
