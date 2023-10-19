<?php
  include 'top_menu.php';

  if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    $dataSql = "SELECT word, date FROM textms ORDER BY date DESC LIMIT 10";
    $dataStmt = $connection->query($dataSql);
    $dataResults = $dataStmt->fetchAll(PDO::FETCH_ASSOC);
  }else {
    header("Location: index.php");
    exit();
  }
?>

<!DOCTYPE html>
<html>
<head>
    <title>작동 내역</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
  <form id="myForm" method="POST">
    <p style="font-size:20px"><strong>작동 내역</strong></p>
    <table>
      <?php foreach ($dataResults as $row): ?>
        <tr>
          <td><?php echo date('m.d H:i:s', strtotime($row['date'])); ?></td>
          <?php if ($row['word'] === "사진을 촬영했습니다." 
                    || $row['word'] === "비밀번호가 비활성화 되었습니다."): ?>
            <td style="color: red;"><?php echo $row['word']; ?></td>
          <?php else: ?>
            <td><?php echo $row['word']; ?></td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </table>
    <br>
    <a href="home.php" class="ca">Home</a>
  </form>
</body>
</html>
