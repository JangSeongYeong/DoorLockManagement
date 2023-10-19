<?php
  include 'top_menu.php'; // 상단 메뉴바 가져오기

  if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    // 현재 비밀번호 받아오는 함수
    function getPassword($connection) {
      $pwsql = "SELECT pw FROM password ORDER BY date DESC LIMIT 1";
      $stmt = $connection->query($pwsql);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['pw'];
    }
    
    // 비밀번호 변경을 눌렀을 때 실행함수(랜덤 비밀번호 배치)
    if (isset($_POST['P_RESET'])) {
      // 4자리 숫자 비밀번호 생성
      $password = strval(rand(1000, 9999));
      // password 테이블 pw 컬럼에 비밀번호 삽입하는 코드
      dbInsertData($connection, 'password', 'pw', $password);
    }
    // 현재 비밀번호 불러오기
    $password = getPassword($connection);
  }else {
    header("Location: index.php");
    exit();
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>HOME</title>
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <style>
    /*pw변경, pw설정 스타일*/
    .button-container {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .button-container button {
        max-width: 400px; 
        padding: 10px;
        height: 40px;
        margin: 2px;
        white-space: nowrap; /*줄바꿈 안 함*/
        overflow: hidden; /* 너비 벗어나면 숨김 */
        text-overflow: ellipsis; /* 너비 벗어나는 부분 ... 표시 */
    }
  </style>
</head>
<body>
  <form id="myForm" method="POST">
    <h2>현재 비밀번호 : <?php echo $password; ?></h2><br>
    
    <div class="button-container">
        <button type="submit" name="P_RESET">비밀번호 변경</button>
        <button type="button" onclick="redirectToPage('number')">비밀번호 설정</button><br>
        <!--redirectToPage()는 top_menu.php 확인-->
    </div>

    <br><br><a href="logout.php">로그아웃</a>
  </form> 
</body>
</html>
