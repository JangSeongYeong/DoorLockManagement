<?php
  include 'top_menu.php'; // 상단 메뉴바 가져오기

  if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    // 비밀번호를 입력하고 저장하기를 눌렀을때 
    if (isset($_POST['set_password'])) {
        $new_password = $_POST['new_password'];

        // 비밀번호 데이터베이스에 업데이트 - 데이터 베이스의 모든 pw가 다 동일한거로 바뀜
        // $updateSql = "UPDATE password SET pw = :new_password" ;
        // $updateStmt = $connection->prepare($updateSql);
        // $updateStmt->bindParam(':new_password', $new_password);
        // $updateStmt->execute();
        // echo "<script>alert('비밀번호를 설정했습니다.');</script>";
        dbInsertData($connection, 'password', 'pw', $new_password);
        header("Location: home.php");
        exit();
    }
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
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <form id="myForm" method="POST">
        <label for="new_password">비밀번호 설정</label>
        <input type="password" id="new_password" name="new_password" required pattern="\d{4}" placeholder="네 자리 숫자로 입력해 주세요."
         title="네 자리 숫자로 입력하세요">
        <br>
        <button type="submit" name="set_password">저장하기</button>
        <a href="home.php" class="ca">홈으로</a>
    </form>
</body>
</html>