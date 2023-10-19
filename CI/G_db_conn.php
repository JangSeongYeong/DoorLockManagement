<?php
  $sname = "localhost";
  $uname = "";
  $password = "";

  $db_name = "";

  $conn = mysqli_connect($sname, $uname, $password, $db_name);

  if(!$conn) {
      echo "Connection failed";
  }

?>