<?php
  include 'CI/db_conn_web.php';
  include 'top_menu.php';

  if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    $user_name = $_SESSION['user_name'];

    // SELECT SQL 쿼리 작성
    $dataSql = "SELECT word, date FROM actionRecoding WHERE user_name = :user_name ORDER BY date DESC LIMIT 10";
    
    // SQL 쿼리 실행
    $dataStmt = $connection->prepare($dataSql);
    $dataStmt->bindParam(':user_name', $user_name);
    $dataStmt->execute();
    
    // 결과 가져오기
    $dataResults = $dataStmt->fetchAll(PDO::FETCH_ASSOC);
  } else {
    header("Location: index.php");
    exit();
  }
?>

<!DOCTYPE html>
<html>
<head>
  <title>작동 내역</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/styleLog.css">
</head>
<body>
  <form id="myForm" method="POST">
    <div style="display: flex; align-items: center;">
      <input type="checkbox" id="menuicon">
      <label for="menuicon">
        <span></span>
        <span></span>
        <span></span>
      </label>
      &nbsp &nbsp &nbsp <p style="font-size:20px"><strong>작동 내역</strong></p>
    </div>
    <!--이 부분 가능하면 수정-->
    <table>
      <?php foreach ($dataResults as $row): ?>
        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date('m.d H:i:s', strtotime($row['date'])); ?></td>
          <?php if ($row['word'] === "사진을 촬영했습니다." || $row['word'] === "비밀번호가 비활성화 되었습니다."): ?>
            <td style="color: red;"><?php echo $row['word']; ?></td>
          <?php else: ?>
            <td><?php echo $row['word']; ?></td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </table>
    <br>
  </form>
</body>
</html>
