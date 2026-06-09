<?php
//ini_set('display_errors',1);
//error_reporting(E_ALL);
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
    $pageTitle=getSystemValue('systemName')." - "." إعدادات ".$user['usrFullName'];
    $customStyles=".tab {overflow: hidden; border: 1px solid #ccc; background-color: #f1f1f1;}
        .tab button {background-color: inherit; float: right; border: none; outline: none; cursor: pointer; padding: 14px 16px; transition: 0.3s; font-size: 17px;}
        .tab button:hover {background-color: #ddd;}
        .tab button.active {background-color: #ccc;}
        .tabcontent {display: none;padding: 6px 12px;border: 1px solid #ccc;border-top: none;}
        #colorsTable {border-collapse: collapse;width: 50%;border: 1px solid #ddd;font-size: 18px;color: ".$colors['listFG'].";background-color: ".$colors['listBG'].";margin:auto;}
        #colorsTable th, #colorsTable td {text-align: $galn;padding: 12px;}
        #colorsTable tr {border-bottom: 1px solid #ddd;}
        #colorsTable tr.header, #colorsTable tr:hover {background-color: ".$colors['lstHdBG'].";color: ".$colors['lstHdFG'].";}
        .defBtn {background-color: #B22222;  color: white;  padding: 10px 10px;  border: none;  cursor: pointer;  width: 150px;  opacity: 0.9;}
        .defBtn:hover {opacity: 1;}
            ";
    include("user_head.php");
    foreach($_POST as $key => $value)
        $$key=$value;
    echo "<center><h3>$pageTitle</h3></center>";
    //Start user code
    echo "<div class='tab'>";
    echo "    <button class='tablinks' onclick='openTab(event, \"colors\")' id='defaultOpen'>اﻷلوان</button>";
    echo "    <button class='tablinks' onclick='openTab(event, \"other\")'>حول النظام</button>";
    echo "</div>";

    echo "<div id='colors' class='tabcontent'>";
    include("user_colors.php");
    echo "</div>";

    echo "<div id='other' class='tabcontent'>";
    include("user_about.php");
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
    </script>";
    //end user code
    include("user_bottom.php");
}
if(!$logged){
  include('expired.php');
}