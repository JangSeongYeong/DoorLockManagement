<?php
  session_start();
  $host = 'localhost';
  $db = '';
  $user = '';
  $pass = '';
  $dbServer = '';
  
  
  $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
  $connection = new PDO($dsn, $user, $pass);
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // PDO::ATTR_ERRMODE: PDO의 오류 보고 모드
  // PDO::ERRMODE_EXCEPTION PDOException 을 발생시킵니다.
?>