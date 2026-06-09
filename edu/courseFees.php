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
//***********************************************************************************************
// control variables
//***********************************************************************************************
$__dir="rtl";
$pageTitle="رسوم الدورات";
if(!$dbc=dbConnect($db_host,$db_schema,$db_user,$db_password)){
    die("Could not connect to database please contact system admin...");
}
$showList=true;
$logged=false;
if(!isset($mode)){
  $mode="";
}
if(!isset($errorMessage)){
  $errorMessage="";
}
if(!isset($errorNo)){
  $errorNo = 0;
}
if(!isset($validationError)){
  $validationError=false;
}

//***********************************************************************************************
// form variables
//***********************************************************************************************
$masterTable="districts";
$noFields=2;
$fieldNames[0]="dist_id";
$fieldNames[1]="dist_name";
$fieldDataTypes[0]="int";
$fieldDataTypes[1]="str";
$fieldInputTypes[1]="text";
$fieldInputTypes[2]="text";
$fieldLabels[0]="كود المنطقة";
$fieldLabels[1]="اسم المنطقة";
$fieldDefaultValues[0]="";
$fieldDefaultValues[1]="";
$fieldsRequired[0]=false;
$fieldsRequired[1]=true;
$insertFields=array(1);                   
$updateFields=array(1);
$masterKey=array(0);
$listFields=array(1);                         //fields in the list
$orderList=true;                                  //user order clause
$listOrderFields=array(1);                        //fiels in order clause
$filterList=false;                                 // use where clause
$listFilter=array(array("",3,"=","1"));           //where clause components (arrat of Connector(and or ...),field,operator(= < ...),vlaue)
$showKeyInAdd=false;
$addFormLabel="جديد";
$addFormFields=array(1);
$editFormLabel="تعديل";
$editFormFields=array(1);
$saveLabel="حفظ";
$cancelLabel="تراجع";
$okLabel="حسنا";
$showAddButton=true;
$addButtonLabel="إضافة";
$viewFormLabel="عرض";
$viewFields=array(0,1);
$deleteConfirmationLabel="الغاء";
$deleteConfirmationMessage="هل أنت متأكد انك تريد الغاء ";
$deleteConfirmationField=1;
$yesButton="نعم";
$noButton="لا";
//***********************************************************************************************
// check session
//***********************************************************************************************
if(isset($_SESSION['__uid'])){
  $__uid=$_SESSION['__uid'];
  $fileName="districts.php";
  $mnuId=getCommandMenuId($fileName);
  if(checkUserMenuItem($__uid,$mnuId)){
    $logged=true;
  }
  if($logged){
    $logged=true;
    if(!isset($mode)){
      $mode=0;
    }
    if(!isset($errmsg)){
      $errmsg="";
    }
    if(!isset($msg)){
      $msg="";
    }
    

//***********************************************************************************************
// form html header
//***********************************************************************************************
    echo "<!DOCTYPE html><html><head>";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
    echo "<title>$pageTitle</title>";
    echo "<style>";
    echo "* { box-sizing: border-box;}";
    echo "#filterBox {background-image: url('images/searchicon.png');background-position: 10px 10px;background-repeat: no-repeat;width: 100%;font-size: 16px;padding: 12px 20px 12px 40px;border: 1px solid #ddd;margin-bottom: 12px;}";
    echo "#masterTable {border-collapse: collapse;width: 100%;border: 1px solid #ddd;font-size: 18px;}";
    echo "#masterTable th, #masterTable td {text-align: left;padding: 12px;}";
    echo "#masterTable tr {border-bottom: 1px solid #ddd;}";
    echo "#masterTable tr.header, #masterTable tr:hover {background-color: #f1f1f1;}";
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
    echo ".input-container {display: -ms-flexbox; /* IE10 */  display: flex;  width: 100%;  margin-bottom: 15px;}";
    echo ".input-field { width: 100%;  padding: 10px;  outline: none;}";
    echo ".input-field:focus {border: 2px solid dodgerblue;}";
    echo ".error{color: red; font-size: small;text-align:center;}";
    echo ".frmButtons{text-align:center; margin: auto; width: 220px;}";    
    echo "</style>";
	  echo "</head>";
	  echo "<body>";
    echo "<br><h2 style='text-align: center;color: RoyalBlue;'>$pageTitle</h2>";

//*****************************************************************************************
//Validation
//*****************************************************************************************
    if($mode=="saveadd" or $mode=="saveedit"){
      if($mode=="saveadd"){
        if(isset($crsfeeCrs)){
          if($crsfeeCrs==-1){
            $errmsg.="لابد من تحديد الدورة<br>";
          }
        }else{
          $errmsg.="لابد من تحديد الدورة <br>";
        }  
      }
      if(isset($crsfeePerEgyLoc)){
        if($crsfeePerEgyLoc==""){
          $errmsg.="لابد من إدخال سعر الشخصي مصريين<br>";
        }
      }else{
        $errmsg.="لابد من إدخال سعر الشخصي مصريين<br>";
      }
      if(isset($crsfeePerFrnLoc)){
        if($crsfeePerFrnLoc==""){
          $errmsg.="لابد من إدخال سعر الشخصي أجانب<br>";
        }
      }else{
        $errmsg.="لابد من إدخال  سعر الشخصي أجانب<br>";
      }
      if(isset($crsfeePerAbroad)){
        if($crsfeePerAbroad==""){
          $errmsg.="لابد من إدخال سعر الشخصي بالخارج<br>";
        }
      }else{
        $errmsg.="لابد من إدخال  سعر الشخصي بالخارج<br>";
      }
      if(isset($crsfeeConEgyLoc)){
        if($crsfeeConEgyLoc==""){
          $errmsg.="لابد من إدخال سعر الشركات مصريين<br>";
        }
      }else{
        $errmsg.="لابد من إدخال سعر الشركات مصريين<br>";
      }
      if(isset($crsfeeConFrnLoc)){
        if($crsfeeConFrnLoc==""){
          $errmsg.="لابد من إدخال سعر الشركات أجنبي<br>";
        }
      }else{
        $errmsg.="لابد من إدخال سعر الشركات أجنبي<br>";
      }
      if(isset($crsfeeConAbroad)){
        if($crsfeeConAbroad==""){
          $errmsg.="لابد من إدخال سعر الشركات خارج مصر<br>";
        }
      }else{
        $errmsg.="لابد من إدخال  سعر الشركات خارج مصر<br>";
      }
      if($errmsg != ""){
        $mode=substr($mode,4);
      }
    }

//*****************************************************************************************
//Read Record
//*****************************************************************************************
    if(($mode=="edit" or $mode=="view" or $mode=="deleteConfirm") and $errmsg == ""){
      $q="SELECT crsfeeCrs,crsfeePerEgyLoc,crsfeePerFrnLoc,crsfeePerAbroad,crsfeeConEgyLoc,crsfeeConFrnLoc,crsfeeConAbroad FROM Coursefees WHERE crsfeeId=$crsfeeId";
      $r=mysqli_query($dbc,$q);
      if($r){
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value){
            $$key=$value;
          }
        }
      }
      //read course name
      $q="SELECT CrsName FROM CoursesGuide WHERE CrsId=$crsfeeCrs";
      $r=mysqli_query($dbc,$q);
      if($r){
          if($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
              foreach($row as $key => $value){
                  $$key=$value;
              }
          }   
      }
    }

//*****************************************************************************************
//Add save (new record)
//*****************************************************************************************
    if($mode=="saveadd"){
      //save new record
      if($errmsg==""){
        //insert query
        $q="INSERT INTO Coursefees(crsfeeCrs,crsfeePerEgyLoc,crsfeePerFrnLoc,crsfeePerAbroad,crsfeeConEgyLoc,crsfeeConFrnLoc,crsfeeConAbroad) VALUES($crsfeeCrs,$crsfeePerEgyLoc,$crsfeePerFrnLoc,$crsfeePerAbroad,$crsfeeConEgyLoc,$crsfeeConFrnLoc,$crsfeeConAbroad)";
        $r=mysqli_query($dbc,$q);
        if($r){
          $mes="تم الحفظ!...";
        }else{
          $errmsg="خطأ في الحفظ!...";
        }
      }
    }
//*****************************************************************************************
//Edit Save (save edited record)
//*****************************************************************************************
    if($mode=="saveedit"){
      //save edited record
      if($errmsg==""){
        //insert query
        $q="UPDATE Coursefees SET crsfeePerEgyLoc=$crsfeePerEgyLoc,crsfeePerFrnLoc=$crsfeePerFrnLoc,crsfeePerAbroad=$crsfeePerAbroad,crsfeeConEgyLoc=$crsfeeConEgyLoc,crsfeeConFrnLoc=$crsfeeConFrnLoc,crsfeeConAbroad=$crsfeeConAbroad WHERE crsfeeId=$crsfeeId";
        $r=mysqli_query($dbc,$q);
        if($r){
          $mes="تم الحفظ!...";
        }else{
          $errmsg="خطأ في الحفظ!...";
        }
      }
    }

//*****************************************************************************************
// add - Edit form
//*****************************************************************************************
    if($mode=="add" or $mode=="edit"){
        $showList=false;
        if($mode=="add"){
            $formLabel=$addFormLabel;
        }else{
            $formLabel=$editFormLabel;
        }
        if(isset($errmsg)){
          echo "<div style='text-align: center; color: red;'>$errmsg</div>";
        }

        echo "<form method='post' style='max-width:800px;margin:auto;direction:$__dir;'>";
        echo "<h2 style='text-align: center;'>$formLabel</h2>";
        echo "<table width='100%'>";

        if($mode=="add"){
          echo "<tr><td style='width: 225px;'>البرنامج</td><td colspan='2'>";
          echo "<div class='input-container'>";
          echo "<select class='input-field' name='prg' onchange='setCourse(this.value);'>";
          $q="select PrgId,PrgName from Programs";
          $r=mysqli_query($dbc,$q);
          if($r){
              while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
                  foreach($row as $key => $value){
                      $$key=$value;
                  }
                  echo "<option value='$PrgId'>$PrgName</option>";
              }   
          }
          echo "</select>";
          echo "</div></td></tr>";  
          
          echo "<tr><td style='width: 225px;'>الدورات</td><td colspan='2'>";
          echo "<div class='input-container'>";
          echo "<select class='input-field' name='crsfeeCrs' id='crsfeeCrs'><option value='-1'>---</option></select>";
          echo "</div></td></tr>";    
        }else{
          echo "<h2 style='text-align: center;'>أسعار دورة: $CrsName</h2>";
        }

        echo "<tr><td style='width: 225px;'>مصريين - شخصي</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' name='crsfeePerEgyLoc'";
        if(isset($crsfeePerEgyLoc)){
          echo " value='$crsfeePerEgyLoc'>";
        }
        echo "</div></td></tr>";  
        
        echo "<tr><td style='width: 225px;'>أجانب - شخصي</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' name='crsfeePerFrnLoc'";
        if(isset($crsfeePerFrnLoc)){
          echo " value='$crsfeePerFrnLoc'>";
        }
        echo "</div></td></tr>";  
        
        echo "<tr><td style='width: 225px;'>شخصي - بالخارج</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' name='crsfeePerAbroad'";
        if(isset($crsfeePerAbroad)){
          echo " value='$crsfeePerAbroad'>";
        }
        echo "</div></td></tr>";  
        
        echo "<tr><td style='width: 225px;'>شركات - مصريين</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' name='crsfeeConEgyLoc'";
        if(isset($crsfeeConEgyLoc)){
          echo " value='$crsfeeConEgyLoc'>";
        }
        echo "</div></td></tr>";  
        
        echo "<tr><td style='width: 225px;'>شركات - أجانب</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' name='crsfeeConFrnLoc'";
        if(isset($crsfeeConFrnLoc)){
          echo " value='$crsfeeConFrnLoc'>";
        }
        echo "</div></td></tr>";  
        
        echo "<tr><td style='width: 225px;'>شركات بالخارج</td><td colspan='2'>";
        echo "<div class='input-container'>";
        echo "<input class='input-field' type='text' name='crsfeeConAbroad'";
        if(isset($crsfeeConAbroad)){
          echo " value='$crsfeeConAbroad'>";
        }
        echo "</div></td></tr>";  
            
        $newMode="save".$mode;
        echo "</table>";
        if($mode=="edit"){
            echo "<input type='hidden' name='crsfeeId' value='$crsfeeId'>";
        }  
    
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'>$saveLabel</button> <button type='submit' class='cnlBtn' name='mode' value=''>$cancelLabel</button></div>";
      echo "</form>";
?>
<script>
const crses=[];
const crsid=[];
const progs=[];
<?php
$q="select CrsId,CrsName,CrsProgram from CoursesGuide";
$r=mysqli_query($dbc,$q);
if($r){
    $i=0;
    while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
        foreach($row as $key => $value){
            $$key=$value;
        }
        echo "crses[$i]=\"$CrsName\";\n";
        echo "crsid[$i]=$CrsId;\n";
        echo "progs[$i]=$CrsProgram;\n";
        $i++;
    }   
}

?>
function setCourse(prgId){
  var sel = document.getElementById("crsfeeCrs");
  var i, L = sel.options.length - 1;
  for(i = L; i > 0; i--) {
    sel.remove(i);
  }
  for(i=0;i<progs.length;i++){
    if(progs[i] == prgId){
      var option = document.createElement("option");
      option.text=crses[i];
      option.value=crsid[i];
      sel.add(option); 
    }
  }
}
</script>
<?php
    }

//*****************************************************************************************
//Delete Confirmation
//*****************************************************************************************
    if($mode=="deleteConfirm"){
      $showList=false;
      echo "<form method='post' style='max-width:500px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>$deleteConfirmationLabel</h2>";
      echo "<div style='text-align: center;direction:$__dir;'>$deleteConfirmationMessage أسعار دورة: $CrsName ?...</div><br>";
      echo "<input type='hidden' name='crsfeeId' value='$crsfeeId'>";
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='delete'>$yesButton</button> <button type='submit' class='cnlBtn' name='mode' value=''>$noButton</button></div>";
      echo "</form>";
    }

//*****************************************************************************************
//Delete
//*****************************************************************************************
    if($mode=="delete"){
      $q="DELETE FROM Coursefees WHERE crsfeeId=$crsfeeId";
      $r=mysqli_query($dbc,$q);
      if($r){
        $mes="تم الحفظ!...";
      }else{
        $errmsg="خطأ في الحفظ!...";
      }    }

//*****************************************************************************************
//View Record
//*****************************************************************************************
    if($mode=="view"){
      //View form
      $showList=false;
      echo "<h2 style='text-align: center;'>أسعار دورة: $CrsName</h2>";

      echo "<table width='50%' style='margin: auto;'>";
      echo "<tr><td style='width: 225px;'>مصريين - شخصي</td><td colspan='2'>";
      echo "<div class='input-container'>$crsfeePerEgyLoc</div></td></tr>";  
      
      echo "<tr><td style='width: 225px;'>أجانب - شخصي</td><td colspan='2'>";
      echo "<div class='input-container'>$crsfeePerFrnLoc</div></td></tr>";  
      
      echo "<tr><td style='width: 225px;'>شخصي - بالخارج</td><td colspan='2'>";
      echo "<div class='input-container'>$crsfeePerAbroad</div></td></tr>";  
      
      echo "<tr><td style='width: 225px;'>شركات - مصريين</td><td colspan='2'>";
      echo "<div class='input-container'>$crsfeeConEgyLoc</div></td></tr>";  
      
      echo "<tr><td style='width: 225px;'>شركات - أجانب</td><td colspan='2'>";
      echo "<div class='input-container'>$crsfeeConFrnLoc</div></td></tr>";  
      
      echo "<tr><td style='width: 225px;'>شركات بالخارج</td><td colspan='2'>";
      echo "<div class='input-container'>$crsfeeConAbroad</div></td></tr>";  

      echo "</table>";
 
      echo "<div class='frmButtons'><button type='button' class='okBtn' onclick='window.location=\"courseFees.php\"'> إغلاق </button></div>";
  
    }

//*****************************************************************************************
//Record List
//*****************************************************************************************
    if($showList){
      //list query
      $q="select crsfeeId,CrsName from Coursefees inner join CoursesGuide on crsfeeCrs=CrsId";
      $r=mysqli_query($dbc,$q);
      if($r){
        if($showAddButton){
          //new form
          echo "<div style='width: 110px; margin: auto;'><form method='post'>";
          echo "<input type='hidden' name='mode' value='add'>";
          echo "<button type='submit' class='addBtn'>إضافة</button>";
          echo "</form></div><br>";
        }
        if(isset($errmsg)){
          echo "<div style='text-align: center; color: red;'>$errmsg</div>";
        }
        //filter list form 
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table id='masterTable'>";
        echo "<tr class='header'>";
        echo "<th>الدورة</th><th style='width:600px;text-align: center;'></th></tr>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          echo "<tr><td> $CrsName </td>";
          echo "<td style='text-align: right;'><form method='post'>";
          echo "<input type='hidden' name='crsfeeId' value='$crsfeeId'>";
          echo "<button type='submit' class='edtBtn' name='mode' value='edit'>تعديل</button> ";
          echo "<button type='submit' class='viewBtn' name='mode' value='view'>عرص</button> ";
          echo "<button type='submit' class='delBtn' name='mode' value='deleteConfirm'>الغاء</button> ";
          echo "</form></td></tr>";
        }
        echo "</table>";
        mysqli_free_result($r);
        //*****************************************************************************************
        //Form scripts
        //*****************************************************************************************
        echo "<script>
        function filterList() {
          var input, filter, table, tr, td, i, txtValue;
          input = document.getElementById(\"filterBox\");
          filter = input.value.toUpperCase();
          table = document.getElementById(\"masterTable\");
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
      </script>";
      }
    }
    mysqli_close($dbc);
    echo "</body></html>";
  }
}

//*****************************************************************************************
//log in error
//*****************************************************************************************
if(!$logged){
  include($__systemRoot."expired.php");
}
?>
