<?php
//read apps
$q="select mnuId,mnuName,mnuDirectory from menus where mnuLevel=0 and (mnuId in (select usrmnuMenu from usermenus where usrmnuUser=?) or mnuId in(select grpmnuMenu from groupmenus where grpmnuGroup in (select usrgrpGroup from usergroups where usrgrpUser=?))) order by mnuOrder";
if ($stmt = mysqli_prepare($dbc, $q)){
    mysqli_stmt_bind_param($stmt, "ii", $__uid, $__uid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$mnuId,$mnuName,$mnuDirectory);
    $i=0;
    while(mysqli_stmt_fetch($stmt)){
        $app[$i]['mnuId']=$mnuId;
        $app[$i]['mnuName']=$mnuName;
        $app[$i]['mnuDirectory']=$mnuDirectory;
        $i++;
    }
    $apps=$i-1;
    mysqli_stmt_close($stmt);
}
//read menus
for($j=0;$j<=$apps;$j++){
    $q="select mnuId,mnuName from menus where mnuLevel=1 and mnuParent=? and (mnuId in (select usrmnuMenu from usermenus where usrmnuUser=?) or mnuId in(select grpmnuMenu from groupmenus where grpmnuGroup in (select usrgrpGroup from usergroups where usrgrpUser=?))) order by mnuOrder";
    if ($stmt = mysqli_prepare($dbc, $q)){
        mysqli_stmt_bind_param($stmt, "iii", $app[$j]['mnuId'],$__uid, $__uid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt,$mnuId,$mnuName);
        $i=0;
        while(mysqli_stmt_fetch($stmt)){
            $mnu[$j][$i]['mnuId']=$mnuId;
            $mnu[$j][$i]['mnuName']=$mnuName;
            $i++;
        }
        $mnus[$j]=$i-1;
        mysqli_stmt_close($stmt);
    }
}
//read commands
for($j=0;$j<=$apps;$j++){
    for($k=0;$k<=$mnus[$j];$k++){
        $q="select mnuId,mnuName,mnuCommand,mnuCmdType from menus where mnuLevel=2 and mnuParent=? and (mnuId in (select usrmnuMenu from usermenus where usrmnuUser=?) or mnuId in(select grpmnuMenu from groupmenus where grpmnuGroup in (select usrgrpGroup from usergroups where usrgrpUser=?))) order by mnuOrder";
        if ($stmt = mysqli_prepare($dbc, $q)){
            mysqli_stmt_bind_param($stmt, "iii", $mnu[$j][$k]['mnuId'],$__uid, $__uid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt,$mnuId,$mnuName,$mnuCommand,$mnuCmdType);
            $i=0;
            while(mysqli_stmt_fetch($stmt)){
                $cmd[$j][$k][$i]['mnuId']=$mnuId;
                $cmd[$j][$k][$i]['mnuName']=$mnuName;
                $cmd[$j][$k][$i]['mnuCommand']=$mnuCommand;
                $cmd[$j][$k][$i]['mnuCmdType']=$mnuCmdType;
                $i++;
            }
            $cmds[$j][$k]=$i-1;
            mysqli_stmt_close($stmt);
        }
    }
}
echo "<div class='navbar'>";
echo "<a href='#' onclick='document.getElementById(\"content\").src=\"home.php\"'>الرئيسية</a>";
if($user['usrClass']==2){
    echo "<div class='subnav'>";
    echo "<button class='subnavbtn'>إدارة النظام</button>";
    echo "<div class='subnav-content'>";
    echo "<div class='dropdown'>";
    echo "<button class='dropbtn'>القوائم والمستخدمين</button>";
    echo "<div class='dropdown-content'>";
    echo "<a href='#' onclick='document.getElementById(\"content\").src=\"users.php\"'>&nbsp;&nbsp;&nbsp;المستخدمين</a>";
    echo "<a href='#' onclick='document.getElementById(\"content\").src=\"groups.php\"'>&nbsp;&nbsp;&nbsp;المحموعات</a>";
    echo "<a href='#' onclick='document.getElementById(\"content\").src=\"menus.php\"'>&nbsp;&nbsp;&nbsp;القوائم</a>";
    echo "<a href='#' onclick='document.getElementById(\"content\").src=\"update.php\"'>&nbsp;&nbsp;&nbsp;التحديثات</a>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}
for($j=0;$j<=$apps;$j++){
    echo "<div class='subnav'>";
    echo "<button class='subnavbtn'>".$app[$j]['mnuName']."</button>";
    echo "<div class='subnav-content'>";
    for($k=0;$k<=$mnus[$j];$k++){
        echo "<div class='dropdown'>";
        echo "<button class='dropbtn'>".$mnu[$j][$k]['mnuName']."</button>";
        echo "<div class='dropdown-content'>";
        for($i=0;$i<=$cmds[$j][$k];$i++){
            $link=$app[$j]['mnuDirectory']."/".$cmd[$j][$k][$i]['mnuCommand'];
            echo "<a href='#' onclick='document.getElementById(\"content\").src=\"$link\"'>&nbsp;&nbsp;&nbsp;".$cmd[$j][$k][$i]['mnuName']."</a>";
        }
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";
    echo "</div>";
}
echo "<a href='#' onclick=\"window.location='logout.php';\" style='float: left;'>تسجيل خروج</a>";
echo "<a href='#' onclick='document.getElementById(\"content\").src=\"user_settings.php\"' style='float: left;'>إعدادات</a>";
echo "</div>";
?>