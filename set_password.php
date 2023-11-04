<?php
  // include 'top_menu.php'; // 상단 메뉴바 가져오기
  include 'CI/db_conn_web.php';
  if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    
  }else {
    header("Location: index.php");
    exit();
  }
?>
<!DOCTYPE html>
<html>
<head>
    <title>비밀번호 설정하기</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/styleSetPw.css">
</head>
<body>
    <form id="myForm" action="set_password_process.php" method="POST">
        <br><label for="new_password">비밀번호 설정</label>
        <br><input type="password" id="new_password" name="new_password" required pattern="\d{4}" placeholder="네 자리 숫자로 입력해 주세요."
         title="네 자리 숫자로 입력하세요">
        <br>
        <button type="submit" name="set_password">변경하기</button>
      
        <div class="button-contain" id="buttonContainer" style="display: flex; justify-content: left;">
          <button type="button" onclick="redirectToPage('home')">
              <i class="fas fa-house" style="color: #444; font-size: 20px;"></i>
          </button>
        </div>
    </form>
    <script src="js/common_function.js"></script>
</body>
</html>