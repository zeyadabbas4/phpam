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
$pageTitle="Pcs";
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
// user code
//***********************************************************************************************
  $q="select max(strtrnNo) as nextaddno from storesTrnMs where strtrntype=1";
  $r=mysqli_query($dbc,$q);
  if($r){
    while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
      foreach($row as $key => $value){
        $$key=$value;
      }
      $nextadd=$nextaddno+1;
    }
  }

//***********************************************************************************************
// form variables
//***********************************************************************************************
$masterTable="hosts";
$fromClause=$masterTable;
$detailTable="routers";  // detail table
$detailTableKey="rID";  // detail table key
$detailTableDesc="rIP"; // detail table Desc column
$masterFieldFK="hGateWay"; // fk at master table
$noFields=4;
$fieldNames[0]="hID";
$fieldNames[1]="hIP";
$fieldNames[2]="hName";
$fieldNames[3]="hGateWay";
$fieldDataTypes[0]="int";
$fieldDataTypes[1]="str";
$fieldDataTypes[2]="str";
$fieldDataTypes[3]="str";
$fieldInputTypes[1]="text";
$fieldInputTypes[2]="text";
$fieldInputTypes[3]="drop";
$fieldLabels[0]="Host ID";
$fieldLabels[1]="Host IP";
$fieldLabels[2]="Host Name";
$fieldLabels[3]="GateWay";
$fieldDefaultValues[0]="";
$fieldDefaultValues[1]="192.168.";
$fieldDefaultValues[2]="";
$fieldDefaultValues[3]="";
$fieldsRequired[0]=false;
$fieldsRequired[1]=true;
$fieldsRequired[2]=true;
$fieldsRequired[3]=true;
$fieldsDataBaseType[0]="N";
$fieldsDataBaseType[1]="N";
$fieldsDataBaseType[2]="N";
$fieldsDataBaseType[3]="FK";
$insertFields=array(1,2,3);
$updateFields=array(1,2,3);
$masterKey=array(0);
$listFields=array(2,3);                         //fields in the list
$orderList=true;                                  //user order clause
$listOrderFields=array(1);                        //fiels in order clause
$filterList=false;                                 // use where clause
$listFilter=array(array("",3,"=","1"));           //where clause components (arrat of Connector(and or ...),field,operator(= < ...),vlaue)
$showKeyInAdd=false;
$addFormLabel="جديد";
$addFormFields=array(1,2,3);
$editFormLabel="تعديل";
$editFormFields=array(1);
$saveLabel="حفظ";
$cancelLabel="تراجع";
$okLabel="حسنا";
$showAddButton=true;
$addButtonLabel="إضافة";
$viewFormLabel="عرض";
$viewFields=array(1,2,3);
$deleteConfirmationLabel="الغاء";
$deleteConfirmationMessage="هل أنت متأكد انك تريد الغاء ";
$deleteConfirmationField=1;
$yesButton="نعم";
$noButton="لا";
$detailTableList=array();
$q="select  $detailTableKey,$detailTableDesc from $detailTable";
              $r=mysqli_query($dbc,$q);
              if($r)
              {
              while($row=mysqli_fetch_array($r,MYSQLI_ASSOC))
                {
                  foreach ($row as $key => $value)
                  {
                    $$key = $value;
                  }
                  $detailTableList [$$detailTableKey] = $$detailTableDesc;
                }
                  mysqli_free_result($r);
              }

//***********************************************************************************************
//
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
    if(!isset($errorMessage)){
      $errorMessage="";
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
    if($mode=="addSave" or $mode=="editSave"){
      for($i=0;$i<$noFields;$i++){
        if($fieldsRequired[$i]){
          $fieldName=$fieldNames[$i];
          $fieldLabel=$fieldLabels[$i];
          if($$fieldName==""){
            $validationError=true;
            $errorMessage.="لا بد من ادخال $fieldLabel<br>";
          }
        }
      }
    }
//*****************************************************************************************
//Read Record
//*****************************************************************************************
    if(($mode=="edit" or $mode=="view" or $mode=="deleteConfirm") and $errorMessage == ""){
      $fieldList="";
      $resultArray=array();
      foreach($fieldNames as $value){
        $fieldList.= $value .",";
        $resultArray[]="";
      }
      $fieldList=substr($fieldList,0,-1);
      $typeslist="";
      $keyValueList=array();
      $whereClause="Where ";
      foreach($masterKey as $value){
        if($whereClause=="Where "){
          $connector="";
        }else{
          $connector=" and ";
        }
        $fieldName=$fieldNames[$value];
        $whereClause.= $connector . $fieldName . "=?";
        $keyValueList[]=$$fieldName;
        if($fieldDataTypes[$value]=="str" or $fieldDataTypes[$value]=="dat"){
          $typeslist.="s";
        }else{
          $typeslist.="i";
        }
      }
      $readQuery="select $fieldList from $masterTable $whereClause";

      $q=$readQuery;
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, $typeslist, ...$keyValueList);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, ...$resultArray);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
      }
      foreach($resultArray as $key => $value){
        $fieldName=$fieldNames[$key];
        $$fieldName=$value;
      }
    }

//*****************************************************************************************
//Add save (new record)
//*****************************************************************************************
    if($mode=="addSave"){
      if(!$validationError){
        //insert query
        $insertValueArray=array();
        $insertList="";
        $valueList="";
        $typeslist="";
        foreach($insertFields as $value){
          $insertList .= $fieldNames[$value] .",";
          if($fieldInputTypes[$value]=="insert"){
            if($fieldDataTypes[$value]=="str" or $fieldDataTypes[$value]=="dat"){
              $quot="'";
            }else{
              $quot="";
            }
            if(substr($fieldDefaultValues[$value],0,2)=="@@"){
              $var=substr($fieldDefaultValues[$value],2);
              $fieldDefaultValue=$$var;
            }else{
              $fieldDefaultValue=$fieldDefaultValues[$value];
            }
            $valueList .= $quot.$fieldDefaultValue.$quot.",";
          }else{
            $valueList .= "?,";
            $fieldName=$fieldNames[$value];
            $insertValueArray[]=$$fieldName;
            if($fieldDataTypes[$value]=="str" or $fieldDataTypes[$value]=="dat"){
              $typeslist.="s";
            }else{
              $typeslist.="i";
            }
          }
        }
        $valueList=substr($valueList,0,-1);
        $insertList=substr($insertList,0,-1);
        $insertQuery="insert into $masterTable($insertList) values($valueList)";
        $q=$insertQuery;
        if($stmt = mysqli_prepare($dbc, $q)){
          mysqli_stmt_bind_param($stmt, $typeslist, ...$insertValueArray);
          mysqli_stmt_execute($stmt);
          $errorNo.=mysqli_stmt_errno($stmt);
          if($errorNo != 0){
            $errorMessage.="خطأ في الحفظ ($errorNo)";
          }
          mysqli_stmt_close($stmt);
        }
      }else{
        $mode="add";
      }
    }

//*****************************************************************************************
//Edit Save (save edited record)
//*****************************************************************************************
    if($mode=="editSave"){
      //save edited value
      if(!$validationError){
        //insert query
        $valueArray=array();
        $updateList="";
        $typeslist="";
        foreach($updateFields as $value){
          $fieldName=$fieldNames[$value];
          $updateList .= "$fieldName = ?,";
          $valueArray[]=$$fieldName;
          if($fieldDataTypes[$value]=="str" or $fieldDataTypes[$value]=="dat"){
            $typeslist.="s";
          }else{
            $typeslist.="i";
          }
        }
        $updateList=substr($updateList,0,-1);
        $whereClause="Where ";
        foreach($masterKey as $value){
          if($whereClause=="Where "){
            $connector="";
          }else{
            $connector=" and ";
          }
          $fieldName=$fieldNames[$value];
          $whereClause.= $connector . $fieldName . "=?";
          $valueArray[]=$$fieldName;
          if($fieldDataTypes[$value]=="str" or $fieldDataTypes[$value]=="dat"){
            $typeslist.="s";
          }else{
            $typeslist.="i";
          }
        }
        $updateQuery="update $masterTable set $updateList $whereClause";
        $q=$updateQuery;
        if ($stmt = mysqli_prepare($dbc, $q)){
          mysqli_stmt_bind_param($stmt, $typeslist, ...$valueArray);
          mysqli_stmt_execute($stmt);
          $errorNo.=mysqli_stmt_errno($stmt);
          if($errorNo != 0){
            $errorMessage.="خطأ في الحفظ ($errorNo)";
          }
          mysqli_stmt_close($stmt);
        }
      }else{
        $mode="edit";
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
      echo "<form method='post' style='max-width:800px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>$formLabel</h2>";
      echo "<table width='100%'>";
      for($i=0;$i<=$noFields;$i++){
        $showField=false;
        if(in_array($i,$masterKey)){
          if($showKeyInAdd){
            $showField=true;
          }
        }else{
          if(in_array($i,$addFormFields)){
            $showField=true;
          }
        }
        if($showField){
          $fieldName=$fieldNames[$i];
          $fieldLabel=$fieldLabels[$i];
          $fieldInputType=$fieldInputTypes[$i];
          if(isset($$fieldName)){
            $fieldDefaultValue=$$fieldName;
          }else{
            if(substr($fieldDefaultValues[$i],0,2)=="@@"){
              $var=substr($fieldDefaultValues[$i],2);
              $fieldDefaultValue=$$var;
            }else{
              $fieldDefaultValue=$fieldDefaultValues[$i];
            }
          }
          switch ($fieldInputType){
            case "text":
              echo "<tr><td style='width: 225px;'>$fieldLabel:</td><td colspan='2'>";
              echo "<div class='input-container'>";
              echo "<input class='input-field' type='text' placeholder='$fieldLabel' name='$fieldName' value='$fieldDefaultValue'>";
              echo "</div></td></tr>";
              break;
            case "date":
              echo "<tr><td style='width: 225px;'>$fieldLabel:</td><td colspan='2'>";
              echo "<div class='input-container'>";
              echo "<input class='input-field' type='date' name='$fieldName' value='$fieldDefaultValue'>";
              echo "</div></td></tr>";
              break;
            case "drop":
              echo "<tr><td style='width: 225px;'>$fieldLabel:</td><td colspan='2'>";
              echo "<div class='input-container'>";
                            dropdownlista($detailTableList,"$masterFieldFK");
              echo "</div></td></tr>";
              break;
            case "hidden":
              echo "<input class='input-field' type='hidden' name='$fieldName' value='$fieldDefaultValue'>";
              break;
            }
        }
      }
      $newMode=$mode."Save";
      echo "</table>";
      if($mode=="edit"){
        foreach($masterKey as $value){
          $fieldName=$fieldNames[$value];
          echo "<input type='hidden' name='$fieldName' value='".$$fieldName."'>";
        }
      }
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='$newMode'>$saveLabel</button> <button type='submit' class='cnlBtn' name='mode' value=''>$cancelLabel</button></div>";
      echo "</form>";
      echo "<div class='error'><p>$errorMessage</p></div>";
    }

//*****************************************************************************************
//Delete Confirmation
//*****************************************************************************************
    if($mode=="deleteConfirm"){
      $showList=false;
      $fieldName=$fieldNames[$deleteConfirmationField];
      echo "<form method='post' style='max-width:500px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>$deleteConfirmationLabel</h2>";
      echo "<div style='text-align: center;direction:$__dir;'>$deleteConfirmationMessage ".$$fieldName." ?...</div><br>";
      foreach($masterKey as $value){
        $fieldName=$fieldNames[$value];
        echo "<input type='hidden' name='$fieldName' value='".$$fieldName."'>";
      }
      echo "<div class='frmButtons'><button type='submit' class='savBtn' name='mode' value='delete'>$yesButton</button> <button type='submit' class='cnlBtn' name='mode' value=''>$noButton</button></div>";
      echo "</form>";
    }

//*****************************************************************************************
//Delete
//*****************************************************************************************
    if($mode=="delete"){
      $typeslist="";
      $keyValueList=array();
      $whereClause="Where ";
      foreach($masterKey as $value){
        if($whereClause=="Where "){
          $connector="";
        }else{
          $connector=" and ";
        }
        $fieldName=$fieldNames[$value];
        $whereClause.= $connector . $fieldName . "=?";
        $keyValueList[]=$$fieldName;
        if($fieldDataTypes[$value]=="str" or $fieldDataTypes[$value]=="dat"){
          $typeslist.="s";
        }else{
          $typeslist.="i";
        }
      }
      $deleteQuery="delete from $masterTable $whereClause";
      $q=$deleteQuery;
      if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, $typeslist, ...$keyValueList);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
      }
    }

//*****************************************************************************************
//View Record
//*****************************************************************************************
    if($mode=="view"){
      //View form
      $showList=false;
      echo "<form method='post' style='max-width:800px;margin:auto;direction:$__dir;'>";
      echo "<h2 style='text-align: center;'>$viewFormLabel</h2>";
      echo "<table width='100%'>";
      foreach($viewFields as $value){
        $fieldLabel=$fieldLabels[$value];
        $fieldName=$fieldNames[$value];
        echo "<tr><td style='width: 225px;'>$fieldLabel:</td><td>";
        echo "<div class='input-container'>".$$fieldName."</div></td></tr>";
      }
      echo "</table>";
      echo "<div class='frmButtons'><button type='submit' class='okBtn' name='mode' value=''>$okLabel</button></div>";
      echo "</form>";
    }

//*****************************************************************************************
//Record List
//*****************************************************************************************
    if($showList){
      //list query
      $fieldList="";
      foreach($masterKey as $value){
        $fieldList.= $fieldNames[$value] .",";
      }
      foreach($listFields as $value){
        $fieldList.= $fieldNames[$value] .",";
        if($fieldsDataBaseType[$value] == "FK")
            {
           //$fieldList.= $detailTableDesc . ",";
           $fk=true;
           $fromClause .= ", $detailTable";
           $whereAppend = "$detailTableKey = $masterFieldFK";
          }
      }
      echo $fieldList;
      $fieldList=substr($fieldList,0,-1);
      if($filterList){
        $whereClause="Where ";
        foreach($listFilter as $value){
          if($fieldDataTypes[$value[1]]=="str" or $fieldDataTypes[$value[1]]=="dat"){
            $quot="'";
          }else{
            $quot="";
          }
          $whereClause.=" " . $value[0] . " " . $fieldNames[$value[1]] . $value[2] . $quot . $value[3] . $quot;
        }
      }else{
        $whereClause="";
      }

      if($fk)
        {
         if($whereClause == "")
           $whereClause = "where ".$whereAppend;
         //echo $whereClause;
        }
      if($orderList){
        $orderClause=" order by ";
        foreach($listOrderFields as $value){
          $orderClause .= $fieldNames[$value] . ",";
        }
        $orderClause=substr($orderClause,0,-1);
      }else{
        $orderClause="";
      }
      $listQuery="select $fieldList from $fromClause $whereClause $orderClause";
      $q=$listQuery;
      $r=mysqli_query($dbc,$q);
      if($r){
        if($showAddButton){
          //new form
          echo "<div style='width: 110px; margin: auto;'><form method='post'>";
          echo "<input type='hidden' name='mode' value='add'>";
          echo "<button type='submit' class='addBtn'>$addButtonLabel</button>";
          echo "</form></div><br>";
        }
        //filter list form
        echo "<input type='text' id='filterBox' onkeyup='filterList()' placeholder='Filter list..' title='Type in a name'>";
        echo "<table id='masterTable'>";
        echo "<tr class='header'>";
        echo "<th>";
        $colLabel="";
        foreach($listFields as $value){
          $colLabel .= $fieldLabels[$value] . " - ";
        }
        echo substr($colLabel,0,-3);
        echo "</th><th style='width:600px;text-align: center;'></th></tr>";
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
          foreach($row as $key => $value)
            $$key=$value;
          echo "<tr><td>";
          $listLabel="";
          foreach($listFields as $value){
            $fieldName=$fieldNames[$value];
            $listLabel .= $$fieldName . " - ";
          }
          echo substr($listLabel,0,-3);
          echo "</td>";
          echo "<td style='text-align: right;'><form method='post'>";
          foreach($masterKey as $value){
            $fieldName=$fieldNames[$value];
          echo $detailTableList[$$masterFieldFK];  //ahsC
            echo "<input type='hidden' name='$fieldName' value='".$$fieldName."'>";
          }
          echo "<button type='submit' class='edtBtn' name='mode' value='edit'>تعديل</button> ";
          echo "<button type='submit' class='viewBtn' name='mode' value='view'>عرص</button> ";
          echo "<button type='submit' class='delBtn' name='mode' value='deleteConfirm'>الغاء</button> ";
          echo "</form></td></tr>";
        }
        echo "</table>";
        mysqli_free_result($r);
      }
    }
    mysqli_close($dbc);

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
