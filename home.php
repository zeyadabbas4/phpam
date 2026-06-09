<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$logged=false;
if(isset($_SESSION['__uid'])){
    $logged=true;    
    $__uid=$_SESSION['__uid'];
    include ("functions.php");
    $user=getUserDetails($__uid);
    $colors=getusercolors($__uid);
    $gdir=getSystemValue("globalDirection");
    $dbc=sysdbConnect();
    $pageTitle=getSystemValue('systemName')." - الصفحة الرئيسية";
    $customStyles=".tab {overflow: hidden; border: 1px solid #ccc; background-color: #f1f1f1;}
        .tab button {background-color: inherit; float: right; border: none; outline: none; cursor: pointer; padding: 14px 16px; transition: 0.3s; font-size: 17px;}
        .tab button:hover {background-color: #ddd;}
        .tab button.active {background-color: #ccc;}
        .tabcontent {display: none;padding: 6px 12px;border: 1px solid #ccc;border-top: none;}";
    include("user_head.php");
    foreach($_POST as $key => $value)
        $$key=$value;
    //echo "<center><h3>$pageTitle</h3></center>";
    if(!isset($activeTab))
        $activeTab="about";
    //Start user code
    $id['about']="";
    $id['phoneBook']="";
    $id['messages']="";
    $id['tasks']="";
    $id['notes']="";
    $id['qr']="";
    $id[$activeTab]="id='defaultOpen'";
    echo "<div class='tab' dir='$gdir'>";
    echo "    <button class='tablinks' onclick='openTab(event, \"about\")' ".$id['about']."><i class='fa-solid fa-house'></i> الصفحة الرئيسية</button>";
    echo "    <button class='tablinks' onclick='openTab(event, \"phoneBook\")' ".$id['phoneBook']."><i class='fa-solid fa-square-phone-flip'></i> دليل الهاتف</button>";
    echo "    <button class='tablinks' onclick='openTab(event, \"messages\")' ".$id['messages']."><i class='fa-solid fa-envelope'></i> الرسائل</button>";
    echo "    <button class='tablinks' onclick='openTab(event, \"tasks\")' ".$id['tasks']."><i class='fa-solid fa-bars-progress'></i> سجل المهام</button>";
    echo "    <button class='tablinks' onclick='openTab(event, \"notes\")' ".$id['notes']."><i class='fa-solid fa-note-sticky'></i> دفتر الملاحظات</button>";
    echo "    <button class='tablinks' onclick='openTab(event, \"qr\")' ".$id['qr']."><i class='fa-solid fa-qrcode'></i> كود qr</button>";
    echo "</div>";

    echo "<div id='about' class='tabcontent' dir='$gdir'>";
    include("user_dash.php");
    echo "</div>";

    echo "<div id='phoneBook' class='tabcontent' dir='$gdir'>";
    include("user_phoneBook.php");
    echo "</div>";

    echo "<div id='messages' class='tabcontent' dir='$gdir'>";
    include("user_messages.php");
    echo "</div>";

    echo "<div id='tasks' class='tabcontent' dir='$gdir'>";
    include("user_tasks.php");
    echo "</div>";

    echo "<div id='notes' class='tabcontent' dir='$gdir'>";
    include("user_notes.php");
    echo "</div>";

    echo "<div id='qr' class='tabcontent' dir='$gdir'>";
    include("user_qr.php");
    echo "</div>";

    echo "<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName('tabcontent');
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = 'none';
        }
        tablinks = document.getElementsByClassName('tablinks');
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(' active', '');
        }
        document.getElementById(tabName).style.display = 'block';
        evt.currentTarget.className += ' active';
    }
    document.getElementById('defaultOpen').click();
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
    </script>";
    //end user code
    include("user_bottom.php");
}
if(!$logged){
  include('expired.php');
}