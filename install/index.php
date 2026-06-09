<!DOCTYPE html>
<html>
<head>
<title>phpam setup script</title>
<script>
function calcContentSize(){
    var x=window.innerWidth;
    var y=window.innerHeight;
    document.getElementById('content').width=x-5;
    document.getElementById('content').height=y-95;
}
</script>
</head>
<body dir='$gdir' style='padding:0;' onpageshow='calcContentSize();' onload='calcContentSize();' onresize='calcContentSize();'>
<iframe src='creatertph.php' id='content' style='margin:auto;width:100%;border-style:none;'></iframe>
</body>
</html>