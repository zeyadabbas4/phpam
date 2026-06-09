<?php
echo "<!DOCTYPE html>";
echo "<html><head><meta charset=\"UTF-8\">";
echo "<title>System login</title>";
echo "<style>";
echo "body {font-family: Arial, Helvetica, sans-serif;text-align: center;} * {box-sizing: border-box;}";
echo ".btn {background-color: dodgerblue;  color: white;  padding: 15px 20px;  border: none;  cursor: pointer;  width: 100%;  opacity: 0.9;}";
echo ".btn:hover {opacity: 1;}";
echo "h2{color: #30589a;}";
echo ".error{color: red; font-size: small;text-align:center}";
echo "</style>";
echo "</head>";
echo "<body><h2>Create database key</h2>";
echo "<div style='width: 400px; margin: auto;'>";
$key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
$nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
$myfile = fopen("../dbpk.php", "w") or die("Unable to open file!");
$txt = "<?php\r\n\$dbpk='$key';\r\n";
fwrite($myfile, $txt);
$txt = "\$nonce='$nonce';\r\n?>";
fwrite($myfile, $txt);
fclose($myfile);
echo "Key saved!...";
echo "</body></html>";
?>
