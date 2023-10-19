<?php
  session_start();
  $host = 'localhost';
  $db = '';
  $user = '';
  $pass = '';
  $dbServer = '';
  
  $connection = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
?>