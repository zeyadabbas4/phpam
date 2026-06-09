<?php
try{
$dbPDO = new PDO ('mysql:host=localhost;dbname=pti;charset=utf8mb4','pti','select*fromemp', array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
}
catch(PDOException $e){
    echo $e->getMessage();
}
$DB_HOST='localhost';
$DB_USER='pti';
$DB_PASSWORD='select*fromemp';
$DB_NAME='pti';
$dbc= @mysqli_connect($DB_HOST,$DB_USER,$DB_PASSWORD,$DB_NAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());
if (!mysqli_set_charset($dbc, "utf8"))
{
  printf("Error loading character set utf8: %s\n", mysqli_error($dbc));
}
?>
