<?php
  include 'CI/db_conn_web.php';
  include 'top_menu.php';

  if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    // 현재 비밀번호 받아오는 함수
    function getPassword($connection, $user_name) {
      $pwsql = "SELECT pw FROM userManagement WHERE user_name = :user_name";
      
      $stmt = $connection->prepare($pwsql);
      $stmt->bindParam(':user_name', $user_name);
      $stmt->execute();
      
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if ($result) {
          return $result['pw'];
      } else {
          return "사용자를 찾을 수 없거나 비밀번호가 없습니다.";
      }
    }
    
    // 현재 비밀번호 불러오기
    $password = getPassword($connection, $_SESSION['user_name']);
  } else {
    header("Location: index.php");
    exit();
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HOME</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/styleHome.css">
</head>
<body>
  <form id="myForm" action="home_process.php" method="POST">
    <div style="display: flex; align-items: center;">
      <input type="checkbox" id="menuicon">
      <label for="menuicon">
        <span></span>
        <span></span>
        <span></span>
      </label>
      <div style="text-align: right; flex-grow: 1;">
        <div class="user-info">
          <p><?php echo $_SESSION['name']; ?> 호 &nbsp | </p>
        </div>
        <a href="logout.php" class="logout-link">로그아웃 &nbsp</a>
      </div>
    </div>
    <hr><br><br><h2>현재 비밀번호 : <?php echo $password; ?></h2><br>
    
    <div class="button-container">
        <button type="submit" name="ranPassword">비밀번호 변경</button>
        <button type="button" onclick="redirectToPage('set_password')">비밀번호 설정</button><br>
    </div>
    
    <br><br>
  </form>
</body>
</html>
