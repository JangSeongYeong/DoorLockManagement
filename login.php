<?php
session_start();
include "CI/db_conn.php";

if (isset($_POST['uname'])&& isset($_POST['password'])){
    
    function validate($data){
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']);

    if (empty($uname)) {
        header("Location: index.php?error=아이디를 작성해 주세요.");
        exit();
    } else if(empty($pass)){
        header("Location: index.php?error=비밀번호를 작성해 주세요.");
        exit();
    } else{
        // hashing the password
        $pass = md5($pass);
        $sql = "SELECT * FROM users WHERE user_name='$uname' AND password='$pass'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result)){
            $row = mysqli_fetch_assoc($result);
            if ($row['user_name'] === $uname && $row['password'] === $pass){
                $_SESSION['user_name'] = $row['user_name'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];
                header("Location: home.php");
                exit();
            }else {
              header("Location: index.php?error=아이디 또는 비밀번호가 올바르지 않습니다.");
              exit();    
            }
        }else{
          header("Location: index.php?error=아이디 또는 비밀번호가 올바르지 않습니다.");
          exit();
        }
    }

  }else{
    header("Location: index.php?error");
    exit();
}

?>