<?php
session_start();
include "CI/db_conn.php";

if (isset($_POST['uname'])&& isset($_POST['password']) 
    && isset($_POST['name'])&& isset($_POST['re_password'])){
    
    function validate($data){
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    $uname = validate($_POST['uname']); // 아이디
    $pass = validate($_POST['password']); // 비밀번호
    
    $re_pass = validate($_POST['re_password']);
    $name = validate($_POST['name']); // 이름

    $user_data = 'uname='. $uname. '$name='. $name;
    

    if (empty($uname)) {
      header("Location: signup.php?error=아이디를 작성해 주세요.&$user_data");
      exit();
    } else if(mb_strlen($uname)<4 || mb_strlen($uname)>15){
        header("Location: signup.php?error=아이디를 4~15글자 사이로 작성해 주세요.&$user_data");
        exit();
      }
    
    else if(empty($pass)){
      header("Location: signup.php?error=비밀번호를 작성해 주세요.&$user_data");
      exit();
    }
    
    else if(empty($re_pass)){
      header("Location: signup.php?error=비밀번호를 재입력해 주세요.&$user_data");
      exit();  
    } 
    
    else if(empty($name)){
      header("Location: signup.php?error=이름을 작성해 주세요.&$user_data");
      exit();  
    } 
    
    else if($pass != $re_pass){
      header("Location: signup.php?error=비밀번호가 일치하지 않습니다.&$user_data");
      exit();  
    }
    
    else{
        $sql = "SELECT * FROM users WHERE user_name='$uname' "; // 사용하려는 아이디가 이미 존재하는지 찾기 
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            header("Location: signup.php?error=중복된 아이디입니다. 다시 입력해 주세요.&$user_data");
            exit();
        } else { // 중복되지 않을시 데이터베이스에 Insert
            $sql2 = "INSERT INTO users(user_name, password, name) VALUES('$uname', '$pass', '$name')";
            $sql3 = "INSERT INTO userManagement(user_name, pw, tp, errorcode) VALUES('$uname', '1234', '0', '0')";
            $result2 = mysqli_query($conn, $sql2);
            $result3 = mysqli_query($conn, $sql3);
            if($result2){
              header("Location: signup.php?success=계정이 성공적으로 생성되었습니다.");
              exit();
            } else {
              header("Location: signup.php?error=unknown error occurred&$user_data");
              exit();
            }
        }
    }

  }else{
    header("Location: signup.php?");
    exit();
}
?>