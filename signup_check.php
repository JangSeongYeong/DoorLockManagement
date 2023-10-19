<?php
session_start();
include "db_conn.php";

if (isset($_POST['uname'])&& isset($_POST['password']) 
    && isset($_POST['name'])&& isset($_POST['re_password'])){
    
    function validate($data){
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']);
    
    $re_pass = validate($_POST['re_password']);
    $name = validate($_POST['name']);

    $user_data = 'uname='. $uname. '$name='. $name;
    

    if (empty($uname)) {
        header("Location: signup.php?error=아이디를 작성해 주세요.&$user_data");
        exit();
      }    if(mb_strlen($uname)<4){
           header("Location: signup.php?error=아이디를 4글자 이상 작성해 주세요.&$user_data");
           exit;
           }else if(15<mb_strlen($uname)){
            header("Location: signup.php?error=아이디를 4~15글자 사이로 작성해 주세요.&$user_data");
            exit;
            }
    
    else if(empty($pass)){
        header("Location: signup.php?error=비밀번호를 작성해 주세요.&$user_data");
        exit();
    } else if(!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,15}$/", $pass)){
      header("Location: signup.php?error=비밀번호는 영어, 특수문자, 숫자를 포함하여 6~15자여야 합니다.&$user_data");
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

        // hashing the password
        $pass = md5($pass);

        $sql = "SELECT * FROM users WHERE user_name='$uname' ";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            header("Location: signup.php?error=중복된 아이디입니다. 다시 입력해 주세요.&$user_data");
            exit();
        } else {
            $sql2 = "INSERT INTO users(user_name, password, name) VALUES('$uname', '$pass', '$name')";
            $result2 = mysqli_query($conn, $sql2);
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