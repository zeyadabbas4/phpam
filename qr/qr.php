<?php
if(isset($_GET['lnk'])){
	$lnk=urldecode($_GET['lnk']);
}
if(!isset($lnk)){
	$lnk="https://aast.edu/en/institutes/pti/";
}else if($lnk==""){
	$lnk="https://aast.edu/en/institutes/pti/";
}
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $title; ?></title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="qrcode.js"></script>
</head>
<body>
<?php
echo "<input id='text' type='hidden' value='$lnk'><br>";
echo "<div id='qrcode' style='width:200px; height:100px; margin:Auto;'></div>";
?>
<script type="text/javascript">
var qrcode = new QRCode(document.getElementById("qrcode"), {
	width : 200,
	height : 200,
	colorDark : "#000000",
	colorLight : "#ffffff"
});

function makeCode () {		
	var elText = document.getElementById("text");
	
	if (!elText.value) {
		alert("Input a text");
		elText.focus();
		return;
	}
	
	qrcode.makeCode(elText.value);
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
