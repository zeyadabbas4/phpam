<?php
//ini_set('display_errors',1);
//error_reporting(E_ALL);
session_start();
$logged=false;
if(isset($_SESSION['__uid'])){
  $__uid=$_SESSION['__uid'];
  if($__uid==0){
    $logged=true;
    $showList=true;
    foreach($_POST as $key => $value)
      $$key=$value;
    include ("functions.php");
    $dbc=sysdbConnect();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo getSystemValue('systemName'); ?> Network Password</title>
</head>
<body>
<center>
<img src='images/<?php echo getSystemValue('systemLogo'); ?>' width='150px'><br>
<h2><?php echo getSystemValue('systemName'); ?></h2>
<h2>Network Credentials</h2></center>
<hr>
<h3 align='left' dir='ltr'>Mr./Mrs.: <?php echo $fullname;?></h3>
<hr>
<h3>
User Id &nbsp;:  <?php echo $user;?>
<br>
Password :  <?php echo $password;?>
</h3>
</body>
</html>
<script>
window.print();
window.close();
</script>
<?php
  }
}
if(!$logged){
  include('expired.php');
}
?>