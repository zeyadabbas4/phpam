<?php
    $statusBarMessage=getSystemValue("statusBarMessage");
    $showStatusBarMessage=getSystemValue("showStatusBarMessage");
    if(!isset($statusBarMessage))
        $statusBarMessage="Powered by phpam ver. ".getSystemVersion();
    if(!isset($statusBarBackground))
        $statusBarBackground=getSystemValue("statusBarBG");
    if(!isset($statusBarMessageColor))
        $statusBarMessageColor=getSystemValue("statusBarFG");
    echo "<div style='overflow:hidden;background-color:$statusBarBackground;color:$statusBarMessageColor;position:fixed;bottom:0;left:0;width:100%;text-align:center;font-size:small;'><p>&nbsp;";
    if($showStatusBarMessage=="Yes")
        echo "$statusBarMessage";
    echo "&nbsp;</p></div></body></html>";
?>